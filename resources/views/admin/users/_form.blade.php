@csrf

<label>
    Name
    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
</label>

<label>
    Email
    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
</label>

<label>
    Role
    <select name="role" required>
        @foreach ($roles as $value => $label)
            <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>{{ $label }}</option>
        @endforeach
    </select>
</label>

<label>
    Password
    <input type="password" name="password" @required(! $user->exists)>
</label>

<label>
    Confirm Password
    <input type="password" name="password_confirmation" @required(! $user->exists)>
</label>

<button type="submit">{{ $buttonText }}</button>
