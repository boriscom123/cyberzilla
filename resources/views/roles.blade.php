@extends('layouts.main')

@section('title', $data['title'])
@section('description', $data['description'])
@section('user-name', $data['user']['name'])

@section('content')

    <main class="site-main">
        <div class="container">
            <div class="container-content">
                Вывод возможных ролей пользователей
            </div>
        </div>
    </main>

@endsection
