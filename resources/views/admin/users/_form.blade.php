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
    <div class="password-field">
        <input type="password" name="password" @required(! $user->exists) data-password-input>
        <button type="button" class="password-toggle" data-password-toggle aria-label="Show password">Show</button>
    </div>
</label>

<label>
    Confirm Password
    <div class="password-field">
        <input type="password" name="password_confirmation" @required(! $user->exists) data-password-input>
        <button type="button" class="password-toggle" data-password-toggle aria-label="Show password">Show</button>
    </div>
</label>

<button type="submit">{{ $buttonText }}</button>
