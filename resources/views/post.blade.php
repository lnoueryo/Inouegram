@extends('layouts.app')

@section('content')
<post :user='@json(Auth::user())'></post>
{{--  <practice></practice>  --}}
@endsection
