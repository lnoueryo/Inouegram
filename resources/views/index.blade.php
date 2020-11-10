@extends('layouts.app')

@section('content')
<index :my-info='@json($my_info)' :my-posts='@json($my_posts)' :my-likes='@json($my_likes)'></index>

@endsection
