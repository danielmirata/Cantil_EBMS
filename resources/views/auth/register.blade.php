@if(!isset($isModal))
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Register</h3>
                    </div>
                    <div class="card-body">
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ isset($isModal) ? route('admin.users.store') : route('register') }}" id="registerForm">
    @csrf
    <div class="mb-3">
        <label for="fullname" class="form-label">Full Name</label>
        <input type="text" class="form-control @error('fullname') is-invalid @enderror" 
               id="fullname" name="fullname" value="{{ old('fullname') }}" required>
        @error('fullname')
            <div class="form-error">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control @error('username') is-invalid @enderror" 
               id="username" name="username" value="{{ old('username') }}" required>
        @error('username')
            <div class="form-error">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" 
               id="email" name="email" value="{{ old('email') }}" required>
        @error('email')
            <div class="form-error">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="account_type" class="form-label">Account Type</label>
        <select class="form-select @error('account_type') is-invalid @enderror" 
                id="account_type" name="account_type" required>
            <option value="">Select Account Type</option>
            @if(isset($isModal))
                <option value="secretary" {{ old('account_type') == 'secretary' ? 'selected' : '' }}>Secretary</option>
                <option value="official" {{ old('account_type') == 'official' ? 'selected' : '' }}>Official</option>
                <option value="captain" {{ old('account_type') == 'captain' ? 'selected' : '' }}>Captain</option>
                <option value="resident" {{ old('account_type') == 'resident' ? 'selected' : '' }}>Resident</option>
            @else
                <option value="resident" {{ old('account_type') == 'resident' ? 'selected' : '' }}>Resident</option>
                <option value="captain" {{ old('account_type') == 'captain' ? 'selected' : '' }}>Captain</option>
                <option value="official" {{ old('account_type') == 'official' ? 'selected' : '' }}>Official</option>
                <option value="secretary" {{ old('account_type') == 'secretary' ? 'selected' : '' }}>Secretary</option>
                <option value="admin" {{ old('account_type') == 'admin' ? 'selected' : '' }}>Admin</option>
            @endif
        </select>
        @error('account_type')
            <div class="form-error">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" 
               id="password" name="password" required>
        @error('password')
            <div class="form-error">{{ $message }}</div>
        @enderror
        <div class="password-requirements">
            <p class="mb-1">Password must contain:</p>
            <ul>
                <li id="length" class="invalid">At least 8 characters</li>
                <li id="uppercase" class="invalid">At least one uppercase letter</li>
                <li id="lowercase" class="invalid">At least one lowercase letter</li>
                <li id="number" class="invalid">At least one number</li>
                <li id="special" class="invalid">At least one special character</li>
            </ul>
        </div>
    </div>
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" 
               id="password_confirmation" name="password_confirmation" required>
    </div>
    <div class="d-grid">
        <button type="submit" class="btn btn-primary">{{ isset($isModal) ? 'Add User' : 'Register' }}</button>
    </div>
</form>

@if(!isset($isModal))
        </div>
        <div class="text-center mt-3">
            <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
        </div>
    </div>
</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Password validation
    $('#password').on('input', function() {
        var password = $(this).val();
        
        // Length check
        if(password.length >= 8) {
            $('#length').removeClass('invalid').addClass('valid');
        } else {
            $('#length').removeClass('valid').addClass('invalid');
        }
        
        // Uppercase check
        if(/[A-Z]/.test(password)) {
            $('#uppercase').removeClass('invalid').addClass('valid');
        } else {
            $('#uppercase').removeClass('valid').addClass('invalid');
        }
        
        // Lowercase check
        if(/[a-z]/.test(password)) {
            $('#lowercase').removeClass('invalid').addClass('valid');
        } else {
            $('#lowercase').removeClass('valid').addClass('invalid');
        }
        
        // Number check
        if(/[0-9]/.test(password)) {
            $('#number').removeClass('invalid').addClass('valid');
        } else {
            $('#number').removeClass('valid').addClass('invalid');
        }
        
        // Special character check
        if(/[!@#$%^&*]/.test(password)) {
            $('#special').removeClass('invalid').addClass('valid');
        } else {
            $('#special').removeClass('valid').addClass('invalid');
        }
    });

    // Form validation
    $('#registerForm').on('submit', function(e) {
        var password = $('#password').val();
        var isValid = true;
        
        // Check all password requirements
        if(password.length < 8) isValid = false;
        if(!/[A-Z]/.test(password)) isValid = false;
        if(!/[a-z]/.test(password)) isValid = false;
        if(!/[0-9]/.test(password)) isValid = false;
        if(!/[!@#$%^&*]/.test(password)) isValid = false;
        
        if(!isValid) {
            e.preventDefault();
            alert('Please ensure your password meets all requirements.');
        }
    });
});
</script>
</body>
</html>
@endif 