@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}" />
@endsection

@section('content')
<div class="form__container">

    <div class="form__heading">
        <h2 class="form__title">プロフィール設定</h2>
    </div>

    <form class="edit__form" action="{{ route('profile.update') }}" enctype="multipart/form-data" method="post" novalidate>
        @csrf
        @method('PATCH')

        {{-- 「選択した瞬間に円形のプレビューを表示させたい」場合は、JavaScriptが必要 --}}
        <div class="form__group">
            <div class="form__item-flex">
                {{-- <div class="icon-preview">
                    @if(Auth::user()->profile_image)
                        <img id="preview" class="circle-image" src="{{ asset('storage/profile_images/' . Auth::user()->profile_image) }}">
                    @else
                        <img id="preview" class="circle-image" style="display: none;">
                        <div class="no-image-circle"></div>
                    @endif
                </div> --}}
                <div class="icon-preview">
                    <img id="preview" class="circle-image"
                        src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : '' }}"
                        style="{{ Auth::user()->profile_image ? '' : 'display: none;' }}">
                    @if(!Auth::user()->profile_image)
                        <div id="no-image" class="no-image-circle"></div>
                    @endif
                </div>
                <label class="form__label">
                    <span class="form__file-button">画像を選択する</span>
                    <input id="image-input" class="form__input--file" type="file" accept="image/*" name="profile_image" />
                </label>
            </div>
            <div class="form__error">
                @error('profile_image')
                    <p class="form__error-text">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form__group">
            <label class="form__label">
                <span class="label-text">ユーザー名</span>
                <input class="form__input" type="text" name="name" value="{{ old('name', $user->name) }}" />
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
                <input class="form__input" type="text" name="postcode" value="{{ old('postcode', $user->postcode) }}" inputmode="numeric" />
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
                <input class="form__input" type="text" name="address" value="{{ old('address', $user->address) }}" />
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
                <input class="form__input" type="text" name="building" value="{{ old('building', $user->building) }}" />
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

@section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('image-input');
        if (!input) return;

        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('preview');
            const noImage = document.getElementById('no-image');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    if (noImage) noImage.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });
    });
    </script>
@endsection
