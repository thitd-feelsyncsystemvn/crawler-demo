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
				margin-bottom: 10px;
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
	<p>URL : {{$page->link}}</p>
	<div class="wrap_list">
		<table>
			<thead>
				<tr>
					<th>Title</th>
					<th>Meta Description</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>{{$page->title}}</td>
					<td>{{$page->meta_description}}</td>
				</tr>
			</tbody>
		</table>
		<table>
			<thead>
				<tr>
					<th>ID</th>
					<th>Anchor Text</th>
					<th>Anchor Type</th>
					<th>Anchor URL</th>
				</tr>
			</thead>
			<tbody>
			@if (count($anchors) > 0)
				@foreach ($anchors as $anchor)
					<tr>
						<td>{{$anchor->id}}</td>
						<td>{{$anchor->text}}</td>
						<td>{{$anchor->type}}</td>
						<td>{{$anchor->url}}</td>
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