@extends('layouts.app')

@section('title', 'About Page')

@section('head')
    @parent
    {{-- Custom Head --}}
@endsection
 
@section('content')
    <p>This is my body content.</p>
@endsection

@section('before_bottom_scripts')
@endsection

@section('after_bottom_scripts')
@endsection
