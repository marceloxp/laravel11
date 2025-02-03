@extends('layouts.app')

@section('title', 'Home Page')

@section('head')
    @parent
    {{-- Custom Head --}}
@endsection

@section('content')
    <p>This is my body content.</p>
@endsection

@section('before_bottom_scripts')
    {{-- <script>
        console.log('Before Bottom Scripts');
    </script> --}}
@endsection

@section('after_bottom_scripts')
    {{-- <script src="{{ vasset('js/custom.js') }}"></script> --}}
@endsection
