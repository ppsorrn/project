<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
<h1> Login </h1>
    <form action="{{ route('authenticate') }}" method="post">
    @csrf
        <label>
            E-mail ::
            <input type="text" name="email" required />
        </label><br />
        <label>
            Password ::
            <input type="password" name="password" required />
        </label>
        <br /><br />
        <button type="submit">Login</button>
        <br /><br />
        @error('credentials')
            <div class="warn">{{ $message }}</div>
        @enderror
    </form>
</body>
</html>