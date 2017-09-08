<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Artisan;


class Crawler extends Controller
{
    public function index()
    {   
        $this->test();

        $key_words = DB::table('key_word')->get();

        $data = array('key_words' => $key_words);

        return view('crawler', $data);
    }
    
    public function crawler_start(Request $request)
    {
        $arr_JSON = array();
        $key_word = $request->input('key_word');
        $input_date = date('d/m/Y');
        $id = DB::table('key_word')->insertGetId([
             'word'         => $key_word
            ,'input_date'   => $input_date
        ]);
        
        $arr_JSON['row'] = array(
             'id' => $id
            ,'word' => $key_word
            ,'input_date' => $input_date
        );


        // Artisan::queue('crawler:start');

        return response()->json($arr_JSON);
    }

/* FOR TEST */  
    private function test()
    {
        print_r("<pre>");
        $link = 'https://en.wikipedia.org/wiki/Laravel';
        $keyword = 'laravel';
        
        $number_link = DB::table('page')->select('number_link')->where('id', 1)->get();
        echo $number_link{0}->number_link;
        // $key_word = 'laravel';
        // $parse_link = $this->parse_link($link);
        // $craw_urls = $this->craw_url($keyword);

                
                // print_r($number_link);
                exit();
    }
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
            // $arr_tmp['sub'] = array();
            foreach ($children as $item) {
                // $text = trim($item->nodeValue);
                // $type = 'text';
                if (isset($item->tagName)) {
                    // $type = $item->tagName;
                    // $arr_tmp['is_img'] = $item->tagName;
                    if (strtolower($item->tagName) == 'img') {
                        $arr_tmp['img_link'] = $text = $root_link.$item->getAttribute('src');
                    }
                }
                // array_push($arr_tmp['sub'], array('text' => $text, 'type' => $type));
            }
            array_push($arr_return['list'], $arr_tmp);
        }
        return $arr_return;
    }
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
