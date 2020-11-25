@extends('layouts.app')

@section('content')
<profile :my-info='@json(Auth::user())' :this-user-posts='@json($this_user_posts)' :this-user='@json($this_user)'></profile>
@endsection
