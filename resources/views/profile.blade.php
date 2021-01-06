@extends('layouts.app')

@section('content')
<profile
    :main-user='@json($main_user)'
    :requested-user='@json($requested_user)'
    :requested-user-posts='@json($posts)' 
    :main-user-likes='@json($main_user_likes)' 
    :requested-user-likes='@json($requested_user_likes)' 
    :requested-user-followed='@json($requested_user_followed)'
    :requested-user-comments='@json($requested_user_comments)'
    :request-id='@json($request_id)'
   ></profile>
@endsection
