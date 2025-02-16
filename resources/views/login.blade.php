<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body style="background-color: #f8f9fa;">
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-lg p-5" style="border-radius: 15px; width: 100%; max-width: 400px; background-color: #ffffff;">
        <h2 class="text-center mb-4" style="color: #4e73df; font-weight: bold;">Login</h2>
        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
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
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group mb-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control rounded-pill" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" required>
            </div>
            <div class="form-group mb-4">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" class="form-control rounded-pill" id="password" name="password" placeholder="Masukkan kata sandi" required>
            </div>
            <div class="form-check mb-4">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Ingatkan saya</label>
            </div>
            <button type="submit" class="btn btn-primary w-100 rounded-pill">Masuk</button>
        </form>
    </div>
</div>
</body>
</html>

<script>
    // Prevent navigation back or forward
    (() => {
        // Push initial state to history
        window.history.pushState(null, null, window.location.href);

        // Listen for back/forward button navigation
        window.addEventListener("popstate", () => {
            window.history.pushState(null, null, window.location.href);
        });
    })();
</script>
