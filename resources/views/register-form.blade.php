<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>
<form action="{{ route('register-form') }}" method="post">
    @csrf

    <label>E-mail ::<input type="text" name="email" value="{{ old('email') }}" required /></label><br />
    <label>Name ::<input type="text" name="name" value="{{ old('name') }}" required /></label><br />
    <label>Password ::<input type="number" step="any" name="password" value="{{ old('password') }}"
    required /></label><br />
    <label>Role ::</label>
    <select name="role">
        <option value="user" selected > User </option>
    </select>
    <br /><br >

    <button type="submit">Create</button>
</form>
</body>
</html>