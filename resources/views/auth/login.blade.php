@extends('layouts.login')

@section('content')

@if(isset($google_user))
<login :google-user='@json($google_user)'></login>
@elseif(isset($github_user))
<login :github-user='@json($github_user)'></login>
@else
<login></login>
@endisset

@endsection
