<a class="item-card" href="">
    {{-- {{ route('item.show', ['item' => $item->id]) }} --}}
    <div class="item-card__inner">
        @if($item->item_images && $item->item_images->isNotEmpty())
            <img class="item-card__image" src="{{ asset('storage/' . $item->item_images->first()->image_path) }}" alt="{{ $item->name }}">
        @else
            <div class="item-card__no-image">
                <span class="item-card__no-image-text">NO IMAGE</span>
            </div>
        @endif

        @if($item->trading_status === 1)
            <div class="item-card__badge--sold">
                Sold
            </div>
        @endif

        <div class="item-card__name">
            {{ $item->name }}
        </div>
    </div>
</a>
