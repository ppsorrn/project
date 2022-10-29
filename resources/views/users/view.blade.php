@extends('layouts.main')

@section('title', $title)

@section('content')

<nav>
    <ul>
        @can('update', \App\models\User::class)
            <li>
                <a href="{{ route('user-update-form', ['user' => $user->email,]) }}">Update</a>
            </li>
        @endcan
        @can('delete', \App\models\User::class)
            <li>
                <a href="{{ route('user-delete', ['user' => $user->email,]) }}">Delete</a>
            </li>
        @endcan
        <li>
            <a href="{{ session()->get('bookmark.user-view', route('user-list')) }}">&lt; Back</a>
        </li>
    </ul>
</nav>

<main>
    <p>
        <b>Code ::</b>
        <span class="cl-class">{{ $user->email }}</span>
        <br/>

        <b>Name ::</b>
        <span>{{ $user->name }}</span>
        <br/>

        <b>Role ::</b>
        <span>{{ $user->role }}</span>
        <br/>

    </p>
</main>

@endsection