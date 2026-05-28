@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}" />
@endsection

@section('content')
    <form class="purchase-form" action="{{ route('purchase.store', ['item_id' => $item->id]) }}" method="POST">
        @csrf
        <div class="purchase-container">
            <div class="purchase-container__left">
                <div class="purchase-item__group">
                    <div class="purchase-item__image-box">
                        @if($item->item_images?->isNotEmpty())
                            <img class="purchase-item__image" src="{{ asset('storage/' . $item->item_images->first()->image_path) }}" alt="{{ $item->name }}">
                        @else
                            <div class="purchase-item__no-image">
                                <span class="purchase-item__no-image-text">NO IMAGE</span>
                            </div>
                        @endif
                    </div>

                    <div class="purchase-item__info-box">
                        <h2 class="purchase-item__name">{{ $item->name }}</h2>

                        <p class="purchase-item__price">
                            <span class="purchase-item__price-symbol">¥</span>
                            {{ number_format($item->price) }}
                        </p>
                    </div>
                </div>

                <div class="form__group">
                    <h3 class="form__label">支払い方法</h3>
                    <select class="form__input--select" name="payment_method" id="payment-select">
                        <option value="" selected disabled hidden>選択してください</option>
                        <option value="コンビニ払い"{{ old('payment_method') === 'コンビニ払い' ? 'selected' : '' }}>コンビニ払い</option>
                        <option value="カード支払い"{{ old('payment_method') === 'カード支払い' ? 'selected' : '' }}>カード支払い</option>
                    </select>
                    <div class="form__error">
                        @error('payment_method')
                            <p class="form__error-text">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="shipping-info__group">
                    <div class="shipping-info__header">
                        <h3 class="shipping-info__label">配送先</h3>
                        <a class="shipping-info__link" href="{{ route('purchase.address', ['item_id' => $item->id]) }}">変更する</a>
                    </div>
                    <p class="shipping-info__postcode">〒{{ session('shipping_postcode', $user->postcode) }}</p>
                    <p class="shipping-info__address">
                        {{ session('shipping_address', $user->address) }}
                        @if(session('shipping_building', $user->building))
                            {{ ' ' . session('shipping_building', $user->building) }}
                        @endif
                    </p>
                    <input type="hidden" name="shipping_postcode" value="{{ session('shipping_postcode', $user->postcode) }}">
                    <input type="hidden" name="shipping_address" value="{{ session('shipping_address', $user->address) }}">
                    <input type="hidden" name="shipping_building" value="{{ session('shipping_building', $user->building) }}">
                    <div class="form__error">
                        @error('shipping_postcode')
                            <p class="form__error-text">{{ $message }}</p>
                        @enderror
                        @error('shipping_address')
                            <p class="form__error-text">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="purchase-container__right">
                    <div class="purchase-summary__group">
                        <div class="purchase-summary__item">
                            <span class="purchase-summary__label">商品代金</span>
                            <span class="purchase-summary__value">¥{{ number_format($item->price) }}</span>
                            <input type="hidden" name="total_price" value="{{ $item->price }}">
                        </div>
                        <div class="purchase-summary__item">
                            <span class="purchase-summary__label">支払い方法</span>
                            <span class="purchase-summary__value" id="display-payment">{{ old('payment_method', '未選択') }}</span>
                        </div>
                    </div>
                    <button class="purchase-button" type="submit">購入する</button>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const selectElement = document.getElementById('payment-select');
            const displayElement = document.getElementById('display-payment');

            selectElement.addEventListener('change', (event) => {
                displayElement.textContent = event.target.value;
            });
        });
    </script>
@endsection
