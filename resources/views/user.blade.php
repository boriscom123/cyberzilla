@extends('layouts.main')

@section('title', $data['title'])
@section('description', $data['description'])
@section('user-name', $data['user-name'])

@section('content')

    <main class="site-main">
        <div class="container">
            <div class="container-content">
                @if ($data['auth'] === true)
                    <user-component
                        app_env='{{ env('APP_ENV', 'local') }}'
                        :user='@json($data['data-user']['user'], JSON_UNESCAPED_UNICODE)'
                        :user_options='@json($data['data-user']['user-options'], JSON_UNESCAPED_UNICODE)'
                        :user_role='@json($data['data-user']['user-role'], JSON_UNESCAPED_UNICODE)'
                    ></user-component>
                @endif
            </div>
        </div>
    </main>

@endsection
