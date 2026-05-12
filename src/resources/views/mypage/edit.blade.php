@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}" />
@endsection

@section('nav')
<div class="header__search">
    <input class="search-form__item-input" type="text" name="keyword" value="{{ old('keyword') }}">
</div>

<nav class="header__nav-group">
    <ul class="header-nav">
        @if (Auth::check())
            <li class="header-nav__item">
                <form action="/logout" method="post">
                    @csrf
                    <button class="header-nav__logout-button" type="submit">ログアウト</button>
                </form>
            </li>
            <li class="header-nav__item">
                <a class="header-nav__link" href="/mypage">マイページ</a>
            </li>
            <li class="header-nav__item">
                <form action="出品" method="post">
                    @csrf
                    <button class="header-nav__listing-button">出品</button>
                </form>
            </li>
        @endif
    </ul>
</nav>
@endsection

@section('content')
<div class="form__container">

    <div class="form__heading">
        <h1 class="form__title">プロフィール設定</h1>
    </div>

    <form class="edit__form" action="/mypage/update" enctype="multipart/form-data" method="post" novalidate>
        @csrf

        {{-- 「選択した瞬間に円形のプレビューを表示させたい」場合は、JavaScriptが必要 --}}
        <div class="form__group">
            <div class="form__item-flex">
                <div class="icon-preview">
                    @if(Auth::user()->profile_image)
                        <img class="circle-image" src="{{ asset('storage/profile_images/' . Auth::user()->profile_image) }}">
                    @else
                        <div class="no-image-circle"></div>
                    @endif
                </div>
                <label class="form__label">
                    <span class="form__file-button">画像を選択する</span>
                    <input class="form__input--file" type="file" accept="image/*" name="profile_image" />
                </label>
            </div>
            <div class="form__error">
                @error('profile_image')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form__group">
            <label class="form__label">
                <span class="label-text">ユーザー名</span>
                <input class="form__input" type="text" name="name" value="{{ old('name') }}" />
            </label>
            <div class="form__error">
                @error('name')
                    <p class="form__error-text">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form__group">
            <label class="form__label">
                <span class="label-text">郵便番号</span>
                <input class="form__input" type="text" name="postcode" value="{{ old('postcode') }}" inputmode="numeric" />
            </label>
            <div class="form__error">
                @error('postcode')
                    <p class="form__error-text">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form__group">
            <label class="form__label">
                <span class="label-text">住所</span>
                <input class="form__input" type="text" name="address" value="{{ old('address') }}" />
            </label>
            <div class="form__error">
                @error('address')
                    <p class="form__error-text">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form__group">
            <label class="form__label">
                <span class="label-text">建物名</span>
                <input class="form__input" type="text" name="building" value="{{ old('building') }}" />
            </label>
            <div class="form__error">
                @error('building')
                    <p class="form__error-text">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form__button">
            <button class="form__button-submit" type="submit">更新する</button>
        </div>
    </form>

</div>
@endsection
