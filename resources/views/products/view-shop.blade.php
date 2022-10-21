@extends('layouts.main')

@section('title', $title)

@section('content')

<table>
    <nav>
        <ul>
            <li>
                <a href="{{ route('product-view', ['product' => $product->code,]) }}">&lt; Back</a>
            </li>
        </ul>
    </nav>

    <form action="{{ route('product-view-shop', ['product' => $product->code, ]) }}" method="get" class="search-form">
        <label>
            Search
            <input type="text" name="term" value="{{ $search['term'] }}" />
        </label>
        <br />
        <button type="submit" class="primary">Search</button>
        <a href="{{ route('product-view-shop', ['product' => $product->code, ]) }}">
            <button type="button" class="accent">Clear</button>
        </a>
        <br>
        @can('update', \App\models\Product::class)
            <a href="{{ route('product-add-shop-form', ['product' => $product->code,]) }}">Add Shop</a>
        @endcan
    </form>

    <div>{{ $shops->withQueryString()->links() }}</div>

<tr>
    <th>Code</th>
    <th>Name</th>
    <th>Owner</th>
</tr>
<tbody>
@foreach($shops as $shop)
<tr>
    <th>
        <a href="{{ route('shop-view', ['shop' => $shop->code, ]) }}">
            {{ $shop->code }}
        </a>
    </th>
    <td>{{ $shop->name }}</td>
    <td>{{ $shop->owner }}</td>
    @can('update', \App\models\Product::class)
        <td>
            <a href="{{ route('product-remove-shop', ['product' => $product->code,'shop' => $shop->code,]) }}">Remove</a>
        </td>
    @endcan
</tr>
@endforeach
</tbody>
</table>

@endsection