@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}" />
<link rel="stylesheet" href="{{ asset('css/item-card.css') }}" />
@endsection

@section('content')

<div class="profile-header">
    <div class="profile-header__user">
        <div class="profile-header__icon">
            @if($user->profile_image)
                <img class="icon-image" src="{{ asset('storage/' . $user->profile_image) }}" alt="プロフィール画像">
            @else
                <div class="icon-no-image"></div>
            @endif
        </div>

        <div class="profile-header__name">
            <h2 class="user-name">{{ $user->name }}</h2>
        </div>
    </div>

    <div class="profile-header__edit">
        <a class="edit-button" href="{{ route('profile.edit') }}">プロフィールを編集</a>
    </div>
</div>

<div class="item-tabs">
    <a class="tab-button {{ $currentTab === 'sell' ? 'active' : '' }}" href="{{ route('profile.show', ['tab' => 'sell']) }}">
        出品した商品
    </a>
    <a class="tab-button {{ $currentTab === 'buy' ? 'active' : '' }}" href="{{ route('profile.show', ['tab' => 'buy']) }}">
        購入した商品
    </a>
</div>

<div class="item-container">
    <div class="item-grid">
        @if($currentTab === 'sell')
            @foreach($sellItems as $item)
                @include('item-card', ['item' => $item])
            @endforeach
        @else
            @foreach($buyItems as $item)
                @include('item-card', ['item' => $item])
            @endforeach
        @endif
    </div>
</div>
@endsection
