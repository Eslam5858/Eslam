@extends('layouts.app')

@section('content')
    <x-auth-card>

        <h1 class="text-xl text-center font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
            Login
        </h1>
        <form class="space-y-4 md:space-y-6" action="{{ route('auth') }}" method="POST">
            @csrf

            {{-- email --}}
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Email
                </label>
                <input type="email" name="email" id="email"
                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="your email" required="" value="{{ old('email') }}">
                @error('email')
                    <x-error-message :message="$message" />
                @enderror
            </div>

            {{-- password --}}
            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Password
                </label>
                <input type="password" name="password" id="password" placeholder="••••••••"
                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    required="">
                @error('password')
                    <x-error-message :message="$message" />
                @enderror
            </div>

            <button type="submit"
                class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                Sign in
            </button>
            <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                Don't have an account yet? <a href="{{ route('register') }}"
                    class="font-medium text-primary-600 hover:underline dark:text-primary-500">
                    Register here
                </a>
            </p>
        </form>

    </x-auth-card>
@endsection
