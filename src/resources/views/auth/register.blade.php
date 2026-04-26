@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}" />
@endsection

@section('content')
<div class="register__content">

    <div class="register__heading">
        <h1 class="register__title">会員登録</h1>
    </div>

    <form class="register__form" action="{{ route('register') }}" method="post" novalidate>
        @csrf

        <div class="form__item">
            <label class="form__label">
                <span class="label-text">ユーザー名</span>
                <input class="form__input" type="text" name="name" value="{{ old('name') }}" />
            </label>
            <div class="form__error">
                @error('name')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form__item">
            <label class="form__label">
                <span class="label-text">メールアドレス</span>
                <input class="form__input" type="email" name="email" value="{{ old('email') }}" />
            </label>
            <div class="form__error">
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form__item">
            <label class="form__label">
                <span class="label-text">パスワード</span>
                <input class="form__input" type="password" name="password" />
            </label>
            <div class="form__error">
                @error('password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form__item">
            <label class="form__label">
                <span class="label-text">確認用パスワード</span>
                <input class="form__input" type="password" name="password_confirmation" />
            </label>
            <div class="form__error">
                @error('password_confirmation')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form__button">
            <button class="form__button-submit" type="submit">登録する</button>
        </div>
    </form>

    <div class="register__footer">
        <a class="register__login-link" href="/login">ログインはこちら</a>
    </div>

</div>
@endsection
