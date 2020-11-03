@extends('layouts.app')

@section('content')
<index :my-info='@json($my_info)' :my-posts='@json($my_posts)'></index>
{{ $my_info }}
{{--  {{ $my_posts }}  --}}
@endsection
