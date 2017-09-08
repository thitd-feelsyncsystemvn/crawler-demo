<html>
<head>
	<meta charset="UTF-8">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<title>Crawler</title>
	<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
	<style>
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
	<div class="wrap_list">
		<table>
			<thead>
				<tr>
					<th>Rank</th>
					<th>Title</th>
					<th>URL</th>
				</tr>
			</thead>
			<tbody>
			@if (count($pages) > 0)
				@foreach ($pages as $page)
					<tr>
						<td>{{$page->id}}</td>
						<td>
							<a href="{{url('/')}}/page_detail/{{$page->id}}">
							{{$page->title}}
							</a>
						</td>
						<td>{{$page->link}}</td>
					</tr>
				@endforeach
			@endif
			</tbody>
		</table>
	</div>
	<script>
	</script>
</body>
</html>