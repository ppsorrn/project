@extends('layouts.main')

@section('title', $title)

@section('content')
<form action="{{ route('product-update', ['product' => $product->code,]) }}" method="post">
    @csrf

    <label>Code
        <input type="text" name="code" value="{{ old('code', $product->code) }}" />
    </label><br />
    <label>Name
        <input type="text" name="name" value="{{ old('name', $product->name) }}" />
    </label><br />
    <label>Price
        <input type="number" name="price" value="{{ old('price', $product->price) }}" step="any" required/>
    </label><br />
    <label>Type ::</label>
    <select name="type" required>
        @foreach($types as $type)
        <option value="{{ $type->code }}"
            @selected(old('type', $product->type->code) === $type->code)>
            [{{ $type->code }}] {{ $type->name }}
        </option>
        @endforeach
    </select><br />
    <label>
        Description
        <textarea name="description" cols="80" rows="10" >
        {{ old('description', $product->description) }}</textarea>
    </label><br />
        
    <button type="submit">Update</button>
</form>
@endsection