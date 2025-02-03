@extends('layouts.app')

@section('head')
    @parent
    {{-- Custom Head --}}
@endsection

@section('content')
    <h1>Home</h1>
    <p>This is my home page.</p>
@endsection

@section('before_bottom_scripts')
    {{-- <script>
        console.log('Before Bottom Scripts');
    </script> --}}
@endsection

@section('after_bottom_scripts')
    {{-- <script src="{{ vasset('js/custom.js') }}"></script> --}}
@endsection
