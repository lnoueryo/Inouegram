@extends('layouts.app')

@section('content')
<profile :this-user-posts="{{ $this_user_posts }}"></profile>
@endsection
