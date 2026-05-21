@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}" />
@endsection

@section('content')
<div class="form__container">

    <div class="form__heading">
        <h2 class="form__title">ログイン</h2>
    </div>

    <form class="login__form" action="{{ route('login') }}" method="post" novalidate>
        @csrf

        <div class="form__group">
            <label class="form__label">
                <span class="label-text">メールアドレス</span>
                <input class="form__input" type="email" name="email" value="{{ old('email') }}" />
            </label>
            <div class="form__error">
                @error('email')
                    <p class="form__error-text">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form__group">
            <label class="form__label">
                <span class="label-text">パスワード</span>
                <input class="form__input" type="password" name="password" />
            </label>
            <div class="form__error">
                @error('password')
                    <p class="form__error-text">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form__button">
            <button class="form__button-submit" type="submit">ログインする</button>
        </div>
    </form>

    <div class="form__footer">
        <a class="register-link" href="{{ route('register') }}">会員登録はこちら</a>
    </div>

</div>
@endsection
