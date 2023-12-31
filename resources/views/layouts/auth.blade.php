@extends('layouts.base')
@section('body')
    <div
    class="relative flex flex-col justify-center w-full h-screen bg-no-repeat bg-cover"
        style="background-image:url('{{asset('headerLight.png')}}')">
        <div class="absolute inset-0 bg-gray-700/50 shdaow-xl dark:bg-black/50 " aria-hidden="true"></div>
        <div class="absolute top-0 right-0 p-4 ">
            <x-toggle-theme/>
        </div>
        <div class="container max-w-sm mx-auto">
            <div
                class="px-10 py-10 shadow-lg bg-white/50 dark:bg-gray-900/50 rounded-xl backdrop-blur-sm"
                >
                <div>
                    <a href="{{ route('login') }}">
                        <x-logo class="w-auto h-16 mx-auto text-primary-600" />
                    </a>

                    <h2 class="my-6 text-2xl font-extrabold leading-9 text-center text-gray-900 dark:text-gray-200">
                        @yield('title')
                    </h2>
                </div>
                @yield('content')

                @isset($slot)
                    {{ $slot }}
                @endisset
            </div>
        </div>
    </div>
@endsection
