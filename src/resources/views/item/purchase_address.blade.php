@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase_address.css') }}" />
@endsection

@section('content')
<div class="form__container">

    <div class="form__heading">
        <h2 class="form__title">住所の変更</h2>
    </div>

    <form class="edit__form" action="{{ route('purchase.address.update', ['item_id' => $item->id]) }}" method="post" novalidate>
        @csrf

        <input type="hidden" name="payment_method" value="{{ session('payment_method', old('payment_method')) }}">

        <div class="form__group">
            <label class="form__label">
                <span class="label-text">郵便番号</span>
                <input class="form__input" type="text" name="shipping_postcode" value="{{ old('shipping_postcode') }}" inputmode="numeric"/>
            </label>
            <div class="form__error">
                @error('shipping_postcode')
                    <p class="form__error-text">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form__group">
            <label class="form__label">
                <span class="label-text">住所</span>
                <input class="form__input" type="text" name="shipping_address" value="{{ old('shipping_address') }}"/>
            </label>
            <div class="form__error">
                @error('shipping_address')
                    <p class="form__error-text">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form__group">
            <label class="form__label">
                <span class="label-text">建物名</span>
                <input class="form__input" type="text" name="shipping_building" value="{{ old('shipping_building') }}"/>
            </label>
            <div class="form__error">
                @error('shipping_building')
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
