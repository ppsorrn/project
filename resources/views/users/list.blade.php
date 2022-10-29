@extends('layouts.main')

@section('title', $title)

@section('content')

<table>
    <form action="{{ route('user-list') }}" method="get" class="search-form">
        <label>
            Search
            <input type="text" name="term" value="{{ $search['term'] }}" />
        </label>
        <br><br>
        <button type="submit" class="primary">Search</button>
        <a href="{{ route('user-list') }}">
            <button type="button" class="accent">Clear</button>
        </a>
    </form>
    <nav>
        <ul>
            @can('create', \App\Models\User::class)
            <li>
                <a href="{{ route('user-create-form') }}">New User</a>
            </li>
            @endcan
        </ul>
    </nav>
    <div>{{ $users->withQueryString()->links() }}</div>
<tr>
    <th>E-mail</th>
    <th>Name</th>
    <th>Role</th>
</tr>
@foreach($users as $user)
<tr>
    <th>
        <a href="{{ route('user-view', ['user' => $user->email, ]) }}">
            {{ $user->email }}
        </a>
    </th>
    <td>{{ $user->name }}</td>
    <td>{{ $user->role }}</td>
</tr>
@endforeach
</table>

@endsection