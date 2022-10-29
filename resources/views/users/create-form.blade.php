@extends('layouts.main')

@section('title', $title)

@section('content')
<form action="{{ route('user-create') }}" method="post">
    @csrf

    <label>E-mail ::<input type="text" name="email" value="{{ old('email') }}" required /></label><br />
    <label>Name ::<input type="text" name="name" value="{{ old('name') }}" required /></label><br />
    <label>Password ::<input type="number" step="any" name="password" value="{{ old('password') }}" required /></label><br />
    <label>Role ::</label>
    <select name="role">
        <option value="">-- Please Select --</option>
        @foreach($roles as $role)
        <option value="{{ $role }}"
            @selected(old('role') === $role)>
            {{ $role }}
        </option>
        @endforeach
    </select><br />

    <button type="submit">Create</button>
</form>
@endsection