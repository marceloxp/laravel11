@extends('layouts.app')

@section('head')
    @parent
    {{-- Custom Head --}}
@endsection
 
@section('content')
    @include('partials.about')
@endsection
