@extends('layouts.main')

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
    <br><br>
    <div align="center">
        <caption>PRODUCT - {{ $product->code }}</caption>
    </div>
    <br><br>

    <table>
    <p>
        <tr>
            <td>
                <div align="left">
                <img src="{{asset('images/products/'.$product['code'].'.jpg')}}" alt="The image of {{$product['name'] }}"
                style="width: 500px" ></div><br />
            </td>
        </tr>

        <tr>
            <td>
                <b>Name ::</b>
                <em>{{ $product->name }}</em>
                <br/>

                <b>Type ID ::</b>
                <span class="cl-category">{{ $product->type_id }}</span>
                <br/>

                <b>Price ::</b>
                <span class="cl-number">{{ number_format((double)$product->price,2) }}</span>
                <br/>
            <   b>Description ::</b>
                <br/>
                    <pre style="font-size: 15px;">{{ $product->description }}</pre>
                <br>
            </td>
        </tr>
    </table>
</main>

@endsection