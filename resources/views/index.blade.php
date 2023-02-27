@extends('layouts.main')

@section('title', $data['title'])
@section('description', $data['description'])
@section('user-name', $data['user-name'])

@section('content')

    <main class="site-main">
        <div class="container">
            <div class="container-content">
                @if ($data['auth'] === true)
                    @if ($data['user-role']->roles_list || $data['user-role']->users_list || $data['user-role']->payments_list)
                        <div class="title"><h2>Перейдите в необходимый раздел</h2></div>

                        <div class="navigation">
                            @if ($data['user-role']->roles_list == true)
                                <a href="{{ route('roles') }}">Права пользователей</a>
                            @endif
                            @if ($data['user-role']->users_list == true)
                                <a href="{{ route('users') }}">Пользователи</a>
                            @endif
                            @if ($data['user-role']->payments_list == true)
                                <a href="{{ route('payments') }}">Платежи</a>
                            @endif
                        </div>
                    @endif
                @else
                    <login-component app_env='{{ env('APP_ENV', 'local') }}'></login-component>
                @endif
            </div>
        </div>
    </main>

@endsection
