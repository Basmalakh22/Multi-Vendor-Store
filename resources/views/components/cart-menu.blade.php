<div class="cart-items">
    <a href="javascript:void(0)" class="main-btn">
        <i class="lni lni-cart"></i>
        <span class="total-items">2</span>
    </a>
    <!-- Shopping Item -->
    <div class="shopping-item">
        <div class="dropdown-cart-header">
            <span>{{ $items->count() }} Items</span>
            <a href="{{ route('cart.index') }}">View Cart</a>
        </div>
        <ul class="shopping-list">
            <li>
                @foreach ($items as $item)
            <li>
                <a href="javascript:void(0)" class="remove" title="Remove this item"><i class="lni lni-close"></i></a>
                <div class="cart-img-head">
                    <a class="cart-img" href="{{ route('products.show', $item->product->slug) }}">
                        <img src="{{ $item->product->image_url }}" alt="#"></a>
                </div>
                <div class="content">
                    <h4><a href="product-details.html">{{ $item->product->name }}</a></h4>
                    <p class="quantity">{{ $item->quantity }}x - <span
                            class="amount">{{ Currency::format($item->product->price ,'SAR') }}</span></p>
                </div>
            </li>
            @endforeach
            </li>
        </ul>
        <div class="bottom">
            <div class="total">
                <span>Total</span>
                <span class="total-amount">{{ currency::format($total ,'SAR') }}</span>
            </div>
            <div class="button">
                <a href="checkout.html" class="btn animate">Checkout</a>
            </div>
        </div>
    </div>
    <!--/ End Shopping Item -->
</div>
