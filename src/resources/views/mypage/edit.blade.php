@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}" />
@endsection

@section('content')
<div class="edit__content">

    <div class="edit__heading">
        <h1 class="edit__title">プロフィール設定</h1>
    </div>

    <form class="edit__form" action="1" method="post" novalidate>
        {{-- action="{{ route('login') }} --}}
        @csrf

        <label class="form__label">
            <span class="label-text">ユーザー名</span>
            <input class="form__input" type="text" name="name" value="{{ old('name') }}" />
        </label>
        {{-- <div class="form__error">
            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror
        </div> --}}

        <label class="form__label">
            <span class="label-text">郵便番号</span>
            <input class="form__input" type="email" name="email" value="{{ old('email') }}" />
        </label>
        {{-- <div class="form__error">
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror
        </div> --}}

        <label class="form__label">
            <span class="label-text">パスワード</span>
            <input class="form__input" type="password" name="password" />
        </label>

        <div class="form__button">
            <button class="form__button-submit" type="submit">ログインする</button>
        </div>
    </form>

    <div class="login__footer">
        <a class="login__register-link" href="/register">会員登録はこちら</a>
    </div>

</div>
@endsection
