@extends('layouts.main')

@section('title', $title)

@section('content')

<main>
    <form action="{{ route('product-list') }}" method="get" class="search-form">
        <div class="search">
            <label>
                Search
                <input type="text" name="term" value="{{ $search['term'] }}" />
            </label>
        </div>
        <br>
        <div class="press">
            <button type="submit" class="primary">Search</button>
            <a href="{{ route('product-list') }}">
                <button type="button" class="accent">Clear</button>
            </a>
        </div>
    </form>
    <nav>
        <ul>
            <li>
                <a href="{{ route('product-create-form') }}">New Product</a>
            </li>
        </ul>
    </nav>

    <div class="page">{{ $products->withQueryString()->links() }}</div>

    <div class="caption">
        <caption>PRODUCTS</caption>
    </div>
    
    @foreach($products as $product)

    <div class="column">
    <table>
        <colgroup>
            <col style="width: 5ch;">
            <col span="20" style="width: 65px;">
        </colgroup>
            <tr>
                <th colspan="5">
                    <center>
                        <li>
                            <img src="{{ asset('images/products/'.$product['code'].'.jpg') }}"
                            style="width: 150px"/>
                        </li>
                    </center>
                </th>
            </tr>
        <tr>
            <td colspan="5">
                <li>
                    <a href="{{ route('product-view', ['product' => $product->code, ]) }}">
                        {{ $product->code }}
                    </a>
                </li>
            </td>
        </tr>
    </table>
    </div>
    @endforeach
</main>

@endsection