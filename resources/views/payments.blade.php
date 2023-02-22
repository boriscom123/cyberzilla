@extends('layouts.main')

@section('title', $data['title'])
@section('description', $data['description'])
@section('user-name', $data['user']['name'])

@section('content')

    <main class="site-main">
        <div class="container">
            <div class="container-content">
                @if ($data['auth'] === true)
                    <payments-component
                        app_env='{{ env('APP_ENV', 'local') }}'
                        :payments='@json($data['payments'], JSON_UNESCAPED_UNICODE)'
                        :payments_status='@json($data['payments_status'], JSON_UNESCAPED_UNICODE)'
                        :users='@json($data['users'], JSON_UNESCAPED_UNICODE)'
                    ></payments-component>
                @endif
            </div>
        </div>
    </main>

@endsection
