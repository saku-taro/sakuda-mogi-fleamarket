@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/show.css') }}" />
@endsection

@section('content')
<div class="item-detail__container">

    <div class="item-detail__image-box">
        @if($item->item_images?->isNotEmpty())
            <img class="item-detail__image" src="{{ asset('storage/' . $item->item_images->first()->image_path) }}" alt="{{ $item->name }}">
        @else
            <div class="item-detail__no-image">
                <span class="item-detail__no-image-text">NO IMAGE</span>
            </div>
        @endif

        @if($item->trading_status === 1)
            <div class="item-detail__badge--sold">
                Sold
            </div>
        @endif
    </div>

    <div class="item-detail__info-box">
        <h2 class="item-detail__name">{{ $item->name }}</h2>

        @if($item->brand_name)
            <p class="item-detail__brand">ブランド名：{{ $item->brand_name }}</p>
        @endif

        <p class="item-detail__price">
            <span class="item-detail__price-symbol">¥</span>
            {{ number_format($item->price) }}
            <span class="item-detail__tax-label">(税込)</span>
        </p>

        <div class="item-detail__interaction">
            <div class="item-detail__like">
                @auth
                    @php $isLiked = Auth::user()->favorites?->contains($item->id); @endphp
                    @if($isLiked)
                        <form action="{{ route('like.destroy', ['item_id' => $item->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="item-detail__like-button" type="submit">
                                <span class="item-detail__icon item-detail__like-icon--on"></span>
                            </button>
                        </form>
                    @else
                        <form action="{{ route('like.store', ['item_id' => $item->id]) }}" method="POST">
                            @csrf
                            <button class="item-detail__like-button" type="submit">
                                <span class="item-detail__icon item-detail__like-icon--off"></span>
                            </button>
                        </form>
                    @endif
                @endauth
                @guest
                    <a href="{{ route('login') }}">
                        <span class="item-detail__icon item-detail__like-icon--off"></span>
                    </a>
                @endguest
                <span class="item-detail__count">{{ $item->favoritedBy?->count() ?? 0 }}</span>
            </div>

            <div class="item-detail__comment">
                <span class="item-detail__icon item-detail__comment-icon-img"></span>
                <span class="item-detail__count">{{ $item->comments?->count() ?? 0 }}</span>
            </div>
        </div>

        @auth
            @if($item->trading_status === 1)
                <button class="item-detail__purchase-button--disabled" disabled>売り切れました</button>
            @elseif(Auth::id() !== $item->user_id)
                <a class="item-detail__purchase-button" href="{{ route('purchase.show', ['item_id' => $item->id]) }}">購入手続きへ</a>
            @else
                <button class="item-detail__purchase-button--disabled" disabled>自分が出品した商品です</button>
            @endif
        @endauth
        @guest
            <a class="item-detail__purchase-button" href="{{ route('login') }}">ログインして購入する</a>
        @endguest

        <div class="item-detail__description">
            <h3 class="item-detail__section-title">商品説明</h3>
            <p class="item-detail__description-text">{{ $item->description }}</p>
        </div>

        <div class="item-detail__meta">
            <h3 class="item-detail__section-title">商品情報</h3>
            <table class="item-detail__meta-table">
                <tr>
                    <th class="item-detail__meta-label">カテゴリー</th>
                    <td class="item-detail__meta-data">
                        @foreach($item->categories ?? [] as $category)
                            <span class="item-detail__category">{{ $category->name }}</span>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th class="item-detail__meta-label">商品の状態</th>
                    <td class="item-detail__meta-data">
                        <span class="item-detail__status">
                            @switch($item->status)
                                @case(0)
                                    良好
                                    @break
                                @case(1)
                                    目立った傷や汚れなし
                                    @break
                                @case(2)
                                    やや傷や汚れあり
                                    @break
                                @case(3)
                                    状態が悪い
                                    @break
                            @endswitch
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="item-detail__comment-section">
            <h3 class="item-detail__section-title item-detail__section-title--comment">コメント ({{ $item->comments?->count() ?? 0 }})</h3>

            <div class="item-detail__comment-list">
                @forelse($item->comments ?? [] as $comment)
                    <div class="item-detail__comment-item">
                        <div class="item-detail__comment-user">
                            @if($comment->user->profile_image)
                                <img class="item-detail__comment-avatar" src="{{ asset('storage/' . $comment->user->profile_image) }}" alt="プロフィール画像">
                            @else
                                <div class="item-detail__comment-avatar-placeholder"></div>
                            @endif
                            <span class="item-detail__comment-username">{{ $comment->user->name }}</span>
                        </div>
                        <p class="item-detail__comment-body">{{ $comment->body }}</p>
                    </div>
                @empty
                    <p class="item-detail__comment-empty">コメントはまだありません。</p>
                @endforelse
            </div>

            <h4 class="item-detail__comment-label">商品へのコメント</h4>
            @auth
                <form class="item-detail__comment-form" action="{{ route('comment.store', ['item_id' => $item->id]) }}" method="POST">
                    @csrf
                    <div class="item-detail__comment-form-group">
                        <textarea class="item-detail__comment-textarea" name="body">{{ old('body') }}</textarea>
                    </div>
                    <div class="form__error">
                        @error('body')
                            <p class="form__error-text">{{ $message }}</p>
                        @enderror
                    </div>
                    <button class="item-detail__comment-submit" type="submit">コメントを送信する</button>
                </form>
            @endauth
            @guest
                <div class="item-detail__comment-guest-group">
                    <p class="item-detail__comment-guest-text">
                        <a  class="item-detail__comment-login-link" href="{{ route('login') }}">ログイン</a>
                        するとコメントすることができます。
                    </p>
                </div>
            @endguest
        </div>
    </div>
</div>
@endsection
