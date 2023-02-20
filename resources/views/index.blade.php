@extends('layouts.main')

@section('title', $data['title'])
@section('description', $data['description'])
@section('user-name', $data['user-name'])

@section('content')

    <main class="site-main">
        <div class="container">
            <div class="container-content">
                @if ($data['auth'] === true)
                    <div class="navigation">
                        <a href="{{ route('roles') }}">Права пользователей</a>
                        <a href="{{ route('users') }}">Пользователи</a>
                        <a href="{{ route('payments') }}">Платежи</a>
                    </div>
                @else
                    <login-component app_env='{{ env('APP_ENV', 'local') }}'></login-component>
                @endif
            </div>
        </div>
    </main>

@endsection
