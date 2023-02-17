<header class="site-header">
    <div class="fixed-header">
        <div class="container">
            <div class="header-container-content">
                <a href="/" class="header-logo">CyberZilla</a>
                @if ($params['auth'] === true)
                    <div class="user-info">
                        @if (isset($params['user']))
                            <div class="user-name">{{ $params['user']['name'] }}</div>
                        @endif
                        <div class="user-name">|</div>
                        <div class="user-role">администратор</div>
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
