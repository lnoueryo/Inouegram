@extends('layouts.app')

@section('content')
<profile :main-user='@json($main_user)' :request-id='@json($request_id)'></profile>
@endsection
