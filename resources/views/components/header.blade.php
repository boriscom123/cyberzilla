<header class="site-header">
    <div class="fixed-header">
        <div class="container">
            <div class="header-container-content">
                <a href="/" class="header-logo">CyberZilla</a>
                @if ($data['auth'] === true)
                    <div class="navigation">
                        <div class="user-info">
                            <div class="user-name">{{ $data['user']['name'] }}</div>
                            <div class="user-name">|</div>
                            <div class="user-role">{{ $data['user-role']['name'] }}</div>
                        </div>
                        @if ($data['user-role']->roles_list || $data['user-role']->users_list || $data['user-role']->payments_list)

                            <div class="links">
                                @if ($data['user-role']->roles_list == true)
                                    <a href="{{ route('roles') }}"
                                       @if ($data['active-navigation'] === 'roles') class="active" @endif>Права
                                        пользователей</a>
                                @endif
                                @if ($data['user-role']->users_list == true)
                                    <a href="{{ route('users') }}"
                                       @if ($data['active-navigation'] === 'users') class="active" @endif>Пользователи</a>
                                @endif
                                @if ($data['user-role']->payments_list == true)
                                    <a href="{{ route('payments') }}"
                                       @if ($data['active-navigation'] === 'payments') class="active" @endif>Платежи</a>
                                @endif
                            </div>

                        @endif
                    </div>

                @endif
                <div class="header-navigation">
                    <div class="nav-item">
                        @if ($data['auth'] === true)
                            <a href="{{ route('user.logout') }}">Выход</a>
                        @endif
                        <div class="nav-item-decoration"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
