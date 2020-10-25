@extends('layouts.app')

@section('content')
{{--  <vue-canvas></vue-canvas>  --}}
<br>
<br>
<br>
{{--  <form id="image_form" action="{{ url('/api/create') }}" method="POST">  --}}
{{--  <post></post>  --}}
<crop-canvas></crop-canvas>
    {{--  @csrf  --}}
    {{--  <input id="image" type="hidden" name="image" value="">  --}}
    {{--  <input id="submit_image" type="submit" name="submit" value="送信">
</form>  --}}
{{--  <index :latest-posted-users="{{ $latest_posted_users }}" :latest-posts="{{ $latest_posts }}"></index>  --}}
{{--  <practice></practice>  --}}
@endsection
