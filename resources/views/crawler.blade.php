<html>
<head>
	<meta charset="UTF-8">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<title>Crawler</title>
	<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
	<style>
		.wrap_form {
			width: 200px;
			float: left;
			text-align: center;
		}
			.wrap_form form input {
				margin: auto;
				margin-top: 10px;
			}
		.wrap_list {
			float: left;
			clear: none;
			margin-left: 100px;
		}
			.wrap_list table {
				border-collapse: collapse;
				border: solid 1px black;
			}
				.wrap_list table th,
				.wrap_list table td {
					border: solid 1px black;
					padding: 10px;
				}
				.wrap_list .status::first-letter {
					text-transform: uppercase;
				}
	</style>
</head>
<body>
	<div class="wrap_form">
		<form id="crawler_form" action="{{route('crawler')}}" method="post">
			<label>Input key word</label>
			<input type="text" name="key_word">
			<input type="submit" value="regist">
		</form>
	</div>
	<div class="wrap_list">
		<table>
			<thead>
				<tr>
					<th>ID</th>
					<th>KWD</th>
					<th>Status</th>
					<th>Input Date</th>
				</tr>
			</thead>
			<tbody>
			@if (count($key_words) > 0)
				@foreach ($key_words as $key_word)
					<tr>
						<td>{{$key_word->id}}</td>
						<td>
							<a href="{{url('/')}}/list_url/{{$key_word->id}}">
							{{$key_word->word}}
							</a>
						</td>
						<td class="status">
							@if ($key_word->status==0)
								{{TEXT_TODO}}
							@elseif ($key_word->status==1)
								{{TEXT_PROCESS_URL}}
							@elseif ($key_word->status==2)
								{{TEXT_PROCESS_ANCHOR}}
							@elseif ($key_word->status==3)
								{{TEXT_DONE}}
							@endif
						</td>
						<td>{{$key_word->input_date}}</td>
					</tr>
				@endforeach
			@endif
			</tbody>
		</table>
	</div>
	<script>
		
		$.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
	    });
	    $('#crawler_form').submit( function(e) {
	    	e.preventDefault();
	    	var key_word = $('input[name="key_word"]').val();
	    	$.ajax({
			   	url : "/crawler_demo/",
			   	method : "POST",
			   	data : { 
			   		key_word : key_word
			   	},
			   	dataType : 'json',
			   	success : function(data) {
			   		// row = data.row;
			   		// tbody = $('.wrap_list').find('table').children('tbody')
			   		// new_row = '<tr>';
			   		// 	new_row += '<td>' + (tbody.find('tr').length+1) + '</td>';
			   		// 	new_row += '<td>' + row.word + '</td>';
			   		// 	new_row += '<td>' + row.status + '</td>';
			   		// 	new_row += '<td>' + row.input_date + '</td>';
			   		// new_row += '</tr>';
			   		// tbody.append(new_row);
			   		location.reload();
			   	},
			   	error : function() {
			   		console.log('error call ajax');
			   	}
			});
	    });
		
		
	</script>
</body>
</html>