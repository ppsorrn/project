@extends('layouts.main')

@section('title', $title)

@section('content')

<table>
    <form action="{{ route('product-list') }}" method="get" class="search-form">
        <label>
            Search
            <input type="text" name="term" value="{{ $search['term'] }}" />
        </label>
        <br />
        <label>
            Min Price
            <input type="number" name="minPrice" value="{{ $search['minPrice'] }}"step="any" />
        </label>
        <br />
        <label>
            Max Price
            <input type="number" name="maxPrice" value="{{ $search['maxPrice'] }}"step="any" />
        </label>
        <br><br>
        <button type="submit" class="primary">Search</button>
        <a href="{{ route('product-list') }}">
        <button type="button" class="accent">Clear</button>
        </a>
    </form>
    <nav>
        <ul>
            @can('create', \App\Models\Product::class)
            <li>
                <a href="{{ route('product-create-form') }}">New Product</a>
            </li>
            @endcan
        </ul>
    </nav>
    <div>{{ $products->withQueryString()->links() }}</div>
<tr>
    <th>Image</th>
    <th>Code</th>
    <th>Name</th>
    <th>Price</th>
</tr>
@foreach($products as $product)
<tr>
    <td>
        <img src="{{ asset('image/products/'.$product['code'].'.jpg') }}">
    </td>
    <th>
        <a href="{{ route('product-view', ['product' => $product->code, ]) }}">
            {{ $product->code }}
        </a>
    </th>
    <td>{{ $product->name }}</td>
    <td>{{ $product->price }}</td>
</tr>
@endforeach
</table>

@endsection