@extends('layouts.main')

@section('title', $title)

@section('content')
<form action="{{ route('product-create') }}" method="post">
    @csrf

    <label>
        <b>Code ::</b>
            <input name="code" value="{{ old('code') }}" required />
    </label><br />

    <label>
        <b>Name ::</b>
            <input type="text" name="name" value="{{ old('code') }}" required />
    </label><br />
            
    <label>
        <b>Price ::</b>
            <input type="number" step="any" name="price" value="{{ old('price') }}" required />
        </label><br />

    <label>
        <b>Description ::</b>
            <textarea name="description" cols="80" rows="10" require>{{ old('description') }}</textarea>
    </label><br /><br />

    <button type="submit">Create</button>
</form>
@endsection