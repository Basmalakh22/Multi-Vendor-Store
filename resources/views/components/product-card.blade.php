 <!-- Start Single Product -->
 <div class="single-product">
    <div class="product-image">
        <img src="{{ asset( $product->image_url ) }}" alt="#">
        @if ($product->sale_precent)
        <span class="sale-tag">-{{ $product->sale_precent }}%</span>
        @endif
        @if ($product->new)
        <span class="new-tag">New</span>
        @endif
        <div class="button">
            <a href="{{ route('products.show',$product->id) }}" class="btn"><i class="lni lni-cart"></i> Add to
                Cart</a>
        </div>
    </div>
    <div class="product-info">
        <span class="category">{{ $product->category->name }}</span>
        <h4 class="title">
            <a href="{{ route('products.show',$product->slug) }}">{{ $product->name }}</a>
        </h4>
        <ul class="review">
            <li><i class="lni lni-star-filled"></i></li>
            <li><i class="lni lni-star-filled"></i></li>
            <li><i class="lni lni-star-filled"></i></li>
            <li><i class="lni lni-star-filled"></i></li>
            <li><i class="lni lni-star"></i></li>
            <li><span>4.0 Review(s)</span></li>
        </ul>
        <div class="price">
            <span>{{ currency::format($product->price ,'SAR') }}</span>
            @if ($product->compare_price)
            <span class="discount-price">{{ currency::format($product->compare_price ,'SAR')}}</span>
            @endif
        </div>
    </div>
</div>
<!-- End Single Product -->
