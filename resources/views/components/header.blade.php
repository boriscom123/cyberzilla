<header class="site-header">
    <div class="fixed-header">
        <div class="container">
            <div class="header-container-content">
                <a href="/" class="header-logo">CyberZilla</a>
                @if ($params['auth'] === true)
                    <div class="navigation">
                        <div class="user-info">
                            <div class="user-name">{{ $params['user']['name'] }}</div>
                            <div class="user-name">|</div>
                            <div class="user-role">{{ $params['role']['name'] }}</div>
                        </div>
                        <div class="links">
                            <a href="{{ route('roles') }}" @if ($params['active-navigation'] === 'roles') class="active" @endif>Права пользователей</a>
                            <a href="{{ route('users') }}" @if ($params['active-navigation'] === 'users') class="active" @endif>Пользователи</a>
                            <a href="{{ route('payments') }}" @if ($params['active-navigation'] === 'payments') class="active" @endif>Платежи</a>
                        </div>
                    </div>

                @endif
                <div class="header-navigation">
                    <div class="nav-item">
                        @if ($params['auth'] === true)
                            <a href="{{ route('user.logout') }}">Выход</a>
                        @endif
                        <div class="nav-item-decoration"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
