@section('title', $title)

@section('content')

<nav>
    <ul>
        <li>
            <a href="{{ route('product-view-shop', ['product' => $product->code,]) }}">Show Shop</a>
        </li>
        @can('update', \App\models\Product::class)
            <li>
                <a href="{{ route('product-update-form', ['product' => $product->code,]) }}">Update</a>
            </li>
        @endcan
        @can('delete', \App\models\Product::class)
            <li>
                <a href="{{ route('product-delete', ['product' => $product->code,]) }}">Delete</a>
            </li>
        @endcan
        <li>
            <a href="{{ session()->get('bookmark.product-view', route('product-list')) }}">&lt; Back</a>
        </li>
    </ul>
</nav>

<main>
    <p>
        <div class="img_v">
            <img src="{{asset('images/'.$product['code'].'.jpg')}}" alt="The image of {{$product['name'] }}" /><br />
        </div>

        <b>Code ::</b>
        <span class="cl-class">{{ $product->code }}</span>
        <br/>

        <b>Name ::</b>
        <em>{{ $product->name }}</em>
        <br/>

        <b>Type ID ::</b>
        <span class="cl-category">{{ $product->type_id }}</span>
        <br/>

        <b>Price ::</b>
        <span class="cl-number">{{ number_format((double)$product->price,2) }}</span>
        <br/>
    </p>
    <pre>{{ $product->description }}</pre>
</main>

@endsection