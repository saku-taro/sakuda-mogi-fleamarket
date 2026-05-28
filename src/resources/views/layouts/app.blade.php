<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Flea Market</title>

    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <h1 class="header__logo">
                <a class="header__logo-link" href="{{ route('item.index') }}">
                    <img class="header_logo-img" src="{{ asset('img/coachtech_header_logo.png') }}" alt="coachtech">
                </a>
            </h1>

            @if (!Request::routeIs('login') && !Request::routeIs('register'))
                <div class="header__search">
                    <form class="search-form" action="{{ route('item.index') }}" method="GET">
                        <input type="hidden" name="tab" value="{{ $currentTab ?? request('tab', 'all') }}">

                        <input class="search-form__item-input" type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？">
                    </form>
                </div>

                <nav class="header__nav-group">
                    <ul class="header-nav">
                        @auth
                            <li class="header-nav__item">
                                <form class="header-nav__logout-form" action="{{ route('logout') }}" method="post">
                                @csrf
                                <button class="header-nav__logout-button" type="submit">ログアウト</button>
                                </form>
                            </li>
                        @endauth
                        @guest
                            <li class="header-nav__item">
                                <a class="header-nav__link" href="{{ route('login') }}">ログイン</a>
                            </li>
                        @endguest
                        <li class="header-nav__item">
                            <a class="header-nav__link" href="{{ route('profile.show') }}">マイページ</a>
                        </li>
                        <li class="header-nav__item">
                            <a class="header-nav__listing-link" href="{{ route('item.create') }}">出品</a>
                        </li>
                    </ul>
                </nav>
            @endif
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>

