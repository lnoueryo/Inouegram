@extends('layouts.app')

@section('content')
<index :my-info='@json($my_info)' :posts='@json($posts)' :my-likes='@json($my_likes)' :my-users='@json($my_users)' :likes='@json($likes)'></index>

@endsection
