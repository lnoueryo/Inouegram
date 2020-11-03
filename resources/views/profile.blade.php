@extends('layouts.app')

@section('content')
<profile :this-user-posts='@json($this_user_posts)'></profile>
@endsection
