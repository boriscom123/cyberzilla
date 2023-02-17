@extends('layouts.main')

@section('title', $data['title'])
@section('description', $data['description'])
@section('user-name', $data['user-name'])

@section('content')

    <main class="site-main">
        <div class="container">
            <div class="container-content">
                @if ($data['auth'] === true)
                    <a href="{{ route('admin') }}">Страница администратора</a>
                @else
                    <h1 class="title">Вход</h1>

                    <input type="checkbox" id="user-login-switch" name="user-login-switch" checked>

                    <form class="login-form" method="POST" action="{{ route('user.login') }}">
                        @csrf
                        <div class="form-block">
                            <label for="login-form-email" class="text">Введите email</label>
                            <div class="input">
                                <input type="email" placeholder="email@mail.ru" id="login-form-email" name="email" required>
                            </div>
                        </div>
                        <div class="form-block">
                            <label for="login-form-password" class="text">Введите пароль</label>
                            <div class="input">
                                <input type="password" placeholder="Пароль" id="login-form-password" name="password" required>
                            </div>
                        </div>
                        <div class="form-block-checkbox">
                            <div class="input">
                                <input type="checkbox" id="login-form-remember" name="remember" checked>
                            </div>
                            <label for="login-form-remember" class="text">Запомнить меня</label>
                        </div>
                        <div class="form-block">
                            <div class="input">
                                <input type="submit" value="Войти">
                            </div>
                        </div>
                    </form>

                    <label class="login-form-label" for="user-login-switch">Зарегистрироваться</label>

                    <form class="registration-form" method="POST" action="{{ route('user.registration') }}">
                        @csrf
                        <div class="form-block">
                            <label for="registration-form-name" class="text">Введите Имя</label>
                            <div class="input">
                                <input type="text" placeholder="Имя" id="registration-form-name" name="name" required>
                            </div>
                        </div>
                        <div class="form-block">
                            <label for="registration-form-email" class="text">Введите email</label>
                            <div class="input">
                                <input type="email" placeholder="email@mail.ru" id="registration-form-email" name="email" required>
                            </div>
                        </div>
                        <div class="form-block">
                            <label for="registration-form-password" class="text">Введите пароль</label>
                            <div class="input">
                                <input type="password" placeholder="password" id="registration-form-password" name="password" required>
                            </div>
                        </div>
                        <div class="form-block">
                            <div class="input">
                                <input type="submit" value="Зарегистрироваться">
                            </div>
                        </div>
                    </form>

                    <label class="registration-form-label" for="user-login-switch">Уже зарегистрированы?</label>
                @endif
            </div>
        </div>
    </main>

@endsection
