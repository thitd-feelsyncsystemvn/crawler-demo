<form method="post" action="{{route('postForm')}}">
	<input type="text" name="name">
	{{ csrf_field() }}
	<input type="submit" value="submit">
</form>