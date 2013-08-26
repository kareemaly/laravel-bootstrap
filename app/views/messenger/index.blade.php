@extends('layout1.index')

@section('content')
    <h1>{{ $messenger->getTitle() }}</h1>


    <p>
        {{ $messenger->getDescription() }}
    </p>
@stop