@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}" />
@endsection

@section('content')
<div class="form__container">

    <div class="form__heading">
        <h2 class="form__title">商品の出品</h2>
    </div>

    <form class="sell__form" action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="sell__section">
            <div class="form__group">
                <p class="form__label">商品画像</p>
                <div id="image-upload-zone" class="sell__image-upload">
                    <label class="sell__image-label">
                        <input  id="sell-image-input" class="sell__image-input" type="file" name="item_image" accept="image/*">
                        <span id="sell-image-button" class="sell__image-button">画像を選択する</span>
                        <img id="sell-preview" class="sell__preview-image" aria-autocomplete=""alt="商品画像プレビュー">
                    </label>
                </div>
                <div class="form__error">
                    @error('item_image')
                        <p class="form__error-text">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="sell__section">
            <h3 class="sell__sub-title">商品の詳細</h3>

            <div class="form__group">
                <p class="form__label">カテゴリー</p>
                <div class="sell__category-list">
                    @foreach($categories as $category)
                        <label class="sell__category-item">
                            <input class="sell__category-input" type="checkbox" name="category_ids[]" value="{{ $category->id }}" {{ is_array(old('category_ids')) && in_array($category->id, old('category_ids')) ? 'checked' : '' }}>
                            <span class="sell__category-text">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="form__error">
                    @error('category_ids')
                        <p class="form__error-text">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form__group">
                <p class="form__label">商品の状態</p>
                <select class="form__input form__input--select" name="status">
                    <option value="" selected disabled hidden>選択してください</option>
                    <option value="0"{{ old('status') === '0' ? 'selected' : '' }}>良好</option>
                    <option value="1"{{ old('status') === '1' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                    <option value="2"{{ old('status') === '2' ? 'selected' : '' }}>やや傷や汚れあり</option>
                    <option value="3"{{ old('status') === '3' ? 'selected' : '' }}>状態が悪い</option>
                </select>
                <div class="form__error">
                    @error('status')
                        <p class="form__error-text">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="sell__section">
            <h3 class="sell__sub-title">商品名と説明</h3>

            <div class="form__group">
                <p class="form__label">商品名</p>
                <input class="form__input" type="text" name="name" value="{{ old('name') }}">
                <div class="form__error">
                    @error('name')
                        <p class="form__error-text">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form__group">
                <p class="form__label">ブランド名</p>
                <input class="form__input" type="text" name="brand_name"value="{{ old('brand_name') }}">
            </div>

            <div class="form__group">
                <p class="form__label">商品の説明</p>
                <textarea class="form__input form__input--textarea" name="description">{{ old('description') }}</textarea>
                <div class="form__error">
                    @error('description')
                        <p class="form__error-text">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form__group">
                <p class="form__label">販売価格</p>
                <div class="sell__price-input-wrapper">
                    <input class="form__input" type="number" name="price" value="{{ old('price') }}">
                </div>
                <div class="form__error">
                    @error('price')
                        <p class="form__error-text">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form__button">
            <button class="form__button-submit" type="submit">出品する</button>
        </div>
    </form>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('sell-image-input');
    if (!input) return;

    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('sell-preview');
        const uploadButton = document.getElementById('sell-image-button');
        const uploadZone = document.getElementById('image-upload-zone');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';

                if (uploadButton) uploadButton.style.display = 'none';

                if (uploadZone) {
                    uploadZone.style.height = 'auto';
                }
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>
