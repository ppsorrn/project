@extends('layouts.main')

@section('title', $title)

@section('content')

<form action="{{ route('order-create') }}" method="post">
@csrf

    <label>
        <b>Quantity ::</b>
        <input name="quantity" value="{{ old('quantity') }}" required />
    </label><br />
    <label>Name ::<input type="text" name="name" value="{{ old('name') }}" required /></label><br />
    <label>
        Address ::
        <textarea name="address" cols="80" rows="10" require>{{ old('address') }}</textarea>
    </label><br />
    <label>Phone ::<input type="number" step="any" name="phone" value="{{ old('phone') }}" required /></label><br />

    <button type="submit">Create</button>
</form>
@endsection