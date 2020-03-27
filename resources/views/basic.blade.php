@extends('layouts.master')

@section('title', 'Page Title')

@section('index_link', $pages->index)
@section('about_me_link', $pages->about_me)

@section('content')
    <div class="wrapper">
        <h1>{{$title}}</h1>
        <h3>{{$subtitle}}</h3>
        <div class="row">
            <div class="mx-auto">
                <img src="{{$img}}" alt="">
            </div>
        </div>
    </div>
@endsection