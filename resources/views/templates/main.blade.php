@extends('app')

@section('content')
    @include('templates.parts.global.navbar')

    <main class="main">
        <h1 style="text-align: center;">@stack('subtitle')</h1>
        @yield('page')
    </main>

    @include('templates.parts.global.footer')
@endsection
