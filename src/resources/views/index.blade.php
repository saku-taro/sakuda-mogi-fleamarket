@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}" />
<link rel="stylesheet" href="{{ asset('css/item-card.css') }}" />
@endsection

@section('content')

<div class="item-tabs">
    <a class="tab-button {{ $currentTab === 'all' ? 'active' : '' }}" href="{{ route('item.index', ['tab' => 'all', 'keyword' => request('keyword')]) }}">
        おすすめ
    </a>
    <a class="tab-button {{ $currentTab === 'mylist' ? 'active' : '' }}" href="{{ route('item.index', ['tab' => 'mylist', 'keyword' => request('keyword')]) }}">
        マイリスト
    </a>
</div>

<div class="item-container">
    <div class="item-grid">
        @if($currentTab === 'all')
            @foreach($allItems as $item)
                @include('item-card', ['item' => $item])
            @endforeach
        @else
            @foreach($mylistItems as $item)
                @include('item-card', ['item' => $item])
            @endforeach
        @endif
    </div>
</div>
@endsection
