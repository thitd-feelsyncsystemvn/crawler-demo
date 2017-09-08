<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class CrawlerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CrawlerCommand written at FSS';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            // get all record status = To do
            $status_todo = DB::table('key_word')->where('status', 0)->get();
            if (count($status_todo)>0) {
                foreach ($status_todo as $row) {
                    $keyword_id = $row->id;
                    // Update status => text process url 
                    DB::table('key_word')
                        ->where('id', $keyword_id)
                        ->update(['status' => 1]);
                    // craw urls from google search result
                    $craw_urls = $this->craw_url($row->word);
                    if (count($craw_urls)>0) {
                        // insert pages
                        foreach ($craw_urls as $item) {
                            // update page
                            DB::table('page')->insert([
                                     'keyword_id' => $keyword_id
                                    ,'title' => $item['title']
                                    ,'link' => $item['link']
                                    ,'meta_description' => ''
                                    ]);
                            DB::table('key_word')->where('id', $keyword_id)->increment('number_link');
                        }
                    }
                }                
            }
            // get all record status = Process
            $status_process = DB::table('key_word')->where('status', 1)->get();
            if (count($status_process)>0) {
                foreach ($status_process as $row) {
                    $keyword_id = $row->id;
                    // Update status => text process anchor 
                    DB::table('key_word')
                        ->where('id', $keyword_id)
                        ->update(['status' => 2]);
                    // get all links
                    $page_links = DB::table('page')->where('keyword_id', $keyword_id)->get();
                    $total_link = 0;
                    if (count($page_links)>0) {
                        foreach ($page_links as $page_link) {
                            $page_id = $page_link->id;
                            // parse link
                             $parse_link = $this->parse_link($page_link->link);
                             $total_link = count($parse_link['list']);
                            // update meta description
                            if (isset($parse_link['meta_description'])) {
                                DB::table('page')
                                    ->where('id', $page_id)
                                    ->update(['meta_description'=>$parse_link['meta_description']]);
                            }
                            DB::table('page')->where('keyword_id', $keyword_id)
                                    ->where('id', $page_id)
                                    ->update(['total_link'=>$total_link]); 
                            // update page detail
                            if (count($parse_link['list'])>0) {
                                foreach ($parse_link['list'] as $key => $item) {
                                    if (isset($item['img_link'])) {
                                        DB::table('page_detail')->insert([
                                             'page_id' => $page_id
                                            ,'text' => $item['img_link']
                                            ,'type' => 'img'
                                            ,'url' => $item['url']
                                            ]);
                                    }
                                    else {
                                        DB::table('page_detail')->insert([
                                             'page_id' => $page_id
                                            ,'text' => htmlentities($item['text'])
                                            ,'type' => 'text'
                                            ,'url' => $item['url']
                                            ]);
                                    }
                                    DB::table('page')->where('id', $page_id)->increment('number_link');

                                }
                            }
                            $number_links = DB::table('page')->select('number_link')->where('id', $page_id)->get();
                            $number_link = $number_links{0}->number_link;
                            if ($number_link == $total_link) {
                                DB::table('key_word')->where('id', $keyword_id)->update(['status'=>3]);
                            }
                        }
                    }
                    
                }
            }
        } catch (Exception $e) {
            $this->error('Error : ' . $e);
        }
    }

// parse link
    private function parse_link($link)
    {
        $arr_return = array();

        $html = file_get_contents($link);
        $dom = new \DOMDocument();
        $root_link = rtrim($link,"/");
        @$dom->loadHTML($html);
        $elements = $dom->getElementsByTagName('a');
        $metas = $dom->getElementsByTagName('meta');
        foreach ($metas as $key => $meta){
            if ($meta->getAttribute('name')=="description") {
                $arr_return['meta_description'] = $meta->getAttribute('content');
            }
        }
        $arr_return['list'] = array();
        foreach ($elements as $key => $element){
            $arr_tmp = array();
            $url = $element->getAttribute('href');
            $first_character = substr($url, 0, 1);
            if ($first_character == "" || $first_character == "#" || $first_character == "/") {
                $url = $root_link.$url;
            }
            $arr_tmp['url'] = $url;
            $arr_tmp['text'] = preg_replace("/\s+/", " ", trim($element->nodeValue));
            $children = $element->childNodes;
            foreach ($children as $item) {
                if (isset($item->tagName)) {
                    if (strtolower($item->tagName) == 'img') {
                        $arr_tmp['img_link'] = $text = $root_link.$item->getAttribute('src');
                    }
                }
            }
            array_push($arr_return['list'], $arr_tmp);
        }
        return $arr_return;
    }
// craw link from google search result
    private function craw_url($key_word)
    {
        $key_word = str_replace(" ","+", $key_word);
        $url = 'https://www.googleapis.com/customsearch/v1?key='.GCSE_API_KEY.'&cx='.GCSE_SEARCH_ENGINE_ID.'&q='.$key_word;
        try {
            $body = file_get_contents($url);
            $json = json_decode($body);
            $arr_return = array();    
            if ( $json->items ) {
                foreach ( $json->items as $item ) {
                    array_push($arr_return, array('title' => $item->title, 'link' => $item->link));
                }
            }
        } catch (Exception $e) {
            $this->error('Error : ' . $e);
        }
        return $arr_return;
    }
}
