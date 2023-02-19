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
                            <a href="{{ route('rules') }}" @if ($params['active-navigation'] === 'rules') class="active" @endif>Права пользователей</a>
                            <a href="{{ route('admin') }}">Пользователи</a>
                            <a href="{{ route('admin') }}">Платежи</a>
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
