@extends('layouts.app')

@section('head')
    @parent
    {{-- Custom Head --}}
@endsection

@section('content')
    @include('partials.home')
@endsection

@section('before_bottom_scripts')
    {{-- <script>
        console.log('Before Bottom Scripts');
    </script> --}}
@endsection

@section('after_bottom_scripts')
    {{-- <script src="{{ vasset('js/custom.js') }}"></script> --}}
@endsection
