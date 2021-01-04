@extends('layouts.login')

@section('content')
<reset-password :email='@json($email)'　:token='@json($token)'></reset-password>
@endsection
