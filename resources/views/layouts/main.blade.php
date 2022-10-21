<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benjarong - @yield('title')</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" />
</head>
<body>
    <header>
        <h1 style="color:gray">Benjarong - @yield('title')</h1>

        @auth
            <nav class="user-panel">
                <span>{{ \Auth::user()->name }}</span>
                <a href="{{ route('logout') }}">Logout</a>
            </nav>
        @endauth

        <div class="topic">
            <nav>
                <ul>
                    <li>
                        <a href="{{ route('product-list') }}">Product</a>
                    </li>
                    <li>
                        <a href="{{ route('category-list') }}">Category</a>
                    </li>
                    <li>
                        <a href="{{ route('shop-list') }}">Shop</a>
                    </li>
                    @can('view', \App\Models\User::class)
                        <li>
                            <a href="{{ route('user-list') }}">User</a>
                        </li>
                    @endcan
                </ul> 
            </nav>
        </div>
    </header>

    @if(session()->has('status'))
        <div class="status">
            <span class="info">{{ session()->get('status') }}</span>
        </div>
    @endif

    @error('error')
        <div class="status">
            <span class="warn">{{ $message }}</span>
        </div>
    @enderror

    <div class="content">
        @yield('content')
    </div>

    <footer class="footer">
        &#xA9; Copyright, 2022, Benjarong.
    </footer>
</body>
</html>