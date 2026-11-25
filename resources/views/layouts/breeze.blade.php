@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Navigation -->
    @include('layouts.navigation')

    <!-- Page Heading -->
    @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    <!-- Page Content -->
    <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
            {{ $slot }}
        </div>
    </main>
</div>
@endsection
