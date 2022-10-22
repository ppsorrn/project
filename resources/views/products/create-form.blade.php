@extends('layouts.main')

@section('title', $title)

@section('content')
<form action="{{ route('product-create') }}" method="post">
    @csrf

    <label>
        <b>Code ::</b>
        <input name="code" value="{{ old('code') }}" required />
    </label><br />
    <label>Name ::<input type="text" name="name" value="{{ old('code') }}" required /></label><br />
    <label>Type ::</label>
    <select name="type" require>
        <option value="">-- Please Select Type --</option>
        @foreach($types as $type)
        <option value="{{ $type->code }}"
            @selected(old('type') === $type->code)>
            [{{ $type->code}}] {{ $type->name }}</option>
        @endforeach
    </select>
    <label>Price ::<input type="number" step="any" name="price" value="{{ old('code') }}" required /></label>
    <label>
        Description ::
        <textarea name="description" cols="80" rows="10" require>{{ old('description') }}</textarea>
    </label><br />

    <button type="submit">Create</button>
</form>
@endsection