@extends('layout1.index')

@section('content')
<div id="content">

    @include('parts.messages')

    <div class="main_tobic">

        {{ $page->description }}

        <div class="clr"></div>

        <br>
        @include('parts.facebook_like')
        <div class="clr"></div>
    </div>


</div><!-- END of content -->
@stop