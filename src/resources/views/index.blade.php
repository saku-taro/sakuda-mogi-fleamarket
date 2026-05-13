@extends('layouts.app')

@section('css')

@endsection

@section('nav')
<div class="header__search">
    <input class="search-form__item-input" type="text" name="keyword" value="{{ old('keyword') }}" placeholder="なにをお探しですか？">
</div>

<nav class="header__nav-group">
    <ul class="header-nav">
        @if (Auth::check())
            <li class="header-nav__item">
                <form class="header-nav__logout-form" action="/logout" method="post">
                    @csrf
                    <button class="header-nav__logout-button" type="submit">ログアウト</button>
                </form>
            </li>
        @else
            <li class="header-nav__item">
                <a class="header-nav__link" href="/login">ログイン</a>
            </li>
        @endif
            <li class="header-nav__item">
                <a class="header-nav__link" href="/mypage">マイページ</a>
            </li>
            <li class="header-nav__item">
                    <a class="header-nav__listing-link">出品</a>
            </li>
    </ul>
</nav>
@endsection
