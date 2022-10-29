@extends('layouts.main')

@section('title', $title)

@section('content')

<nav>
    <ul>
        <li>
            <a href="{{ route('shop-view-product', ['shop' => $shop->code,]) }}">Show Shop</a>
        </li>
        @can('update', \App\models\Shop::class)
            <li>
                <a href="{{ route('shop-update-form', ['shop' => $shop->code,]) }}">Update</a>
            </li>
        @endcan
        @can('delete', \App\models\Shop::class)
            <li>
                <a href="{{ route('shop-delete', ['shop' => $shop->code,]) }}">Delete</a>
            </li>
        @endcan
        <li>
            <a href="{{ session()->get('bookmark.shop-view', route('shop-list')) }}">&lt; Back</a>
        </li>
    </ul>
</nav>

<main>
    <br><br>
    <div align="center">
        <caption><b>Shop</b> - {{ $shop->name }}</caption>
    </div>
    <br><br>

    <table>
    <p>
        <tr>
            <td>
                <div align="center">
                <img src="{{asset('images/shops/shop.jpg')}}" alt="The image of {{$shop['name'] }}"
                style="width: 500px" ></div>
            </td>
        </tr>

        <tr>
            <td>
                <b>Address ::</b>
                    <pre style="font-size: 15px;">{{ $shop->address }}</pre>
                <br />
            </td>
        </tr>
    </table>
</main>

@endsection