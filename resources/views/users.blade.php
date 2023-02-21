@extends('layouts.main')

@section('title', $data['title'])
@section('description', $data['description'])
@section('user-name', $data['user']['name'])

@section('content')

    <main class="site-main">
        <div class="container">
            <div class="container-content">
                <users-component
                    app_env='{{ env('APP_ENV', 'local') }}'
                    :users='@json($data['users'], JSON_UNESCAPED_UNICODE)'
                    :roles='@json($data['roles'], JSON_UNESCAPED_UNICODE)'
                    :payments_status='@json($data['payments_status'], JSON_UNESCAPED_UNICODE)'
                ></users-component>
            </div>
        </div>
    </main>

@endsection
