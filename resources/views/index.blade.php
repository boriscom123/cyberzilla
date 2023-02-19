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
                        <a href="{{ route('admin') }}">Права пользователей</a>
                        <a href="{{ route('admin') }}">Пользователи</a>
                        <a href="{{ route('admin') }}">Платежи</a>

                    </div>
                @else
                    <login-component app_env='{{ env('APP_ENV', 'local') }}'></login-component>
                @endif
            </div>
        </div>
    </main>

@endsection
