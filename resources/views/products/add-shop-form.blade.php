@extends('layouts.main')

@section('title', $title)

@section('content')
<main>  

    <form action="{{ route('product-add-shop-form', ['product' => $product->code, ]) }}" method="post">
    @csrf
        <label>
            Search
            <input type="text" name="term" value="{{ $search['term'] }}" />
        </label>
        <br />
        <button type="submit" class="primary">Search</button>
        <a href="{{ route('product-view-shop', ['product' => $product->code, ]) }}">
            <button type="button" class="accent">Clear</button>
        </a>
    </form>

    <nav>
        <ul>
            <li>
                <a href="{{ route('product-view-shop', ['product' => $product->code,]) }}">&lt; Back</a>
            </li>
        </ul>
    </nav>

    <div>{{ $shops->withQueryString()->links() }}</div>

<form action="{{ route('product-add-shop', ['product' => $product->code, ]) }}" method="post">
@csrf
<table>
    <tr>
        <th>Code</th>
        <th>Name</th>
        <th>Owner</th>
    </tr>
    <tbody>
    @foreach($shops as $shop)
    <tr>
        <th class="code">
            <a href="{{ route('shop-view', ['shop' => $shop->code, ]) }}">
                {{ $shop->code }}
            </a>
        </th>
        <td><em>{{ $shop->name }}</em></td>
        <td>{{ $shop->owner }}</td>
        <td>
            <button type="submit" name="shop" value="{{ $shop->code }}">Add</button>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
</form>
</main>

@endsection