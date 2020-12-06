@extends('layouts.app')

@section('content')
<example-component :posts='@json($posts)'></example-component>
@endsection
