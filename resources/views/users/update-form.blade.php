@extends('layouts.main')

@section('title', $title)

@section('content')

<form action="{{ route('user-update', ['user' => $user->email,]) }}" method="post">
    @csrf

    <label>E-mail ::
        <input type="text" name="email" value="{{ old('email', $user->email) }} " required />
    </label><br />
    <label>Name ::
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required />
    </label><br />
    <label>Password ::
        <input type="number" step="any" name="password" placeholder="Leave blank if you don't need to edit" />
    </label><br />
    <label>Role ::</label>
    <select name="role" require>
        <option value=" ">-- Please select --</option>
        <option value="Admin" @selected(old('role', $user->role) === "Admin")>Admin</option>
        <option value="User" @selected(old('role', $user->role) === "User")>User</option>
    </select><br /> 
        
    <button type="submit">Update</button>
</form>
@endsection