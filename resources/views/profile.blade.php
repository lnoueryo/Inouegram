@extends('layouts.app')

@section('content')
<profile :my-info='@json(Auth::user())' :this-user-posts='@json($this_user_posts)'></profile>
@endsection
