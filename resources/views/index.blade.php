@extends('layouts.app')

@section('content')
<index :main-user='@json($main_user)' :posts='@json($posts)' :main-user-likes='@json($main_user_likes)' :following-users-info='@json($following_users_info)' :likes='@json($likes)' :liked-users='@json($liked_users)' :comments='@json($comments)'></index>
@endsection
