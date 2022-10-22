@extends('layouts.main')

@section('title', $title)

@section('content')

<nav>
    <ul>
        <li>
            <a href="{{ route('shop-view-product', ['shop' => $shop->code,]) }}">Show Product</a>
        </li>
        @can('update', \App\Models\Shop::class)
        <li>
            <a href="{{ route('shop-update-form', ['shop' => $shop->code,]) }}">Update</a>
        </li>
        @endcan
        @can('delete', \App\Models\Shop::class)
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
    <p>
        <b>Code ::</b>
        <span class="cl-class">{{ $shop->code }}</span>
        <br/>

        <b>Name ::</b>
        <em>{{ $shop->name }}</em>
        <br/>

        <pre><b>Address ::</b>{{ $shop->address }}</pre>
        <br/>
    </p>
</main>

@endsection