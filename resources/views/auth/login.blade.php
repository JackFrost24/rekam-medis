@extends('layouts.guest')

@section('title', 'Login Dokter')

@section('content')
    <div class="text-center">
        <img class="mx-auto h-20 w-auto" src="{{ asset('images/logo.png') }}" alt="Logo Klinik">
        <h2 class="mt-6 text-2xl font-bold text-gray-900">Rekam Medis Klinik Gigi</h2>
        <p class="mt-2 text-sm text-gray-600">Silakan login untuk mengakses sistem</p>
    </div>

    @if($errors->any())
        <div class="mt-4 rounded-md bg-red-50 p-4">
            <div class="text-sm text-red-600">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form class="mt-6 space-y-4" method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email Dokter</label>
            <input id="email" name="email" type="email" required autocomplete="email" autofocus
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
                   value="{{ old('email') }}">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" name="password" type="password" required autocomplete="current-password"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember_me" name="remember" type="checkbox"
                       class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="remember_me" class="ml-2 block text-sm text-gray-900">Ingat saya</label>
            </div>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-500">
                    Lupa password?
                </a>
            @endif
        </div>

        <div>
            <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                Login sebagai Dokter
            </button>
        </div>
    </form>
@endsection