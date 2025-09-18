@extends('admin_layout')

@section('content')

Content goes here
 
------------------------<br/>
{{
    $param2
}}

@foreach($param1 as $p)
    <p>{{ $p->name }}</p>
@endforeach

@endsection