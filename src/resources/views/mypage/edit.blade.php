@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}" />
@endsection

@section('content')
<div class="form__content">

    <div class="form__heading">
        <h1 class="form__title">プロフィール設定</h1>
    </div>

    <form class="edit__form" action="1" method="post" novalidate>
        @csrf

        <div class="form__item">
            <label class="form__label">
                <span class="label-text">ユーザー名</span>
                <input class="form__input" type="text" name="name" value="{{ old('name') }}" />
            </label>
            <div class="form__error">
                @error('name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form__item">
            <label class="form__label">
                <span class="label-text">郵便番号</span>
                <input class="form__input" type="text" name="postcode" value="{{ old('postcode') }}" inputmode="numeric" pattern="\d{3}-\d{4}"/>
            </label>
            <div class="form__error">
                @error('postcode')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form__item">
            <label class="form__label">
                <span class="label-text">住所</span>
                <input class="form__input" type="text" name="address" value="{{ old('address') }}" />
            </label>
            <div class="form__error">
                @error('address')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form__item">
            <label class="form__label">
                <span class="label-text">建物名</span>
                <input class="form__input" type="text" name="building" value="{{ old('building') }}" />
            </label>
            <div class="form__error">
                @error('building')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form__button">
            <button class="form__button-submit" type="submit">更新する</button>
        </div>
    </form>

</div>
@endsection
