@extends('layouts.app')

@section('content')
    <dashboard :users='@json($users)'></dashboard>
@endsection
