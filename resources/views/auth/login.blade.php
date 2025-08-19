<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - MGBK</title>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      margin: 0;
      background: linear-gradient(to right, #a78bfa, #c4b5fd);
      min-height: 100vh;
      overflow: hidden;
      position: relative;
    }

    .card-login {
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      background-color: #ffffffee;
      z-index: 10;
    }

    .form-control:focus {
      border-color: #a78bfa;
      box-shadow: 0 0 0 0.2rem rgba(167, 139, 250, 0.25);
    }

    .btn-purple {
      background-color: #a78bfa;
      border-color: #a78bfa;
    }

    .btn-purple:hover {
      background-color: #8b5cf6;
      border-color: #8b5cf6;
    }

    .wave {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      z-index: 1;
    }
  </style>
</head>
<body>
  <!-- Login Card -->
  <div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card card-login p-4" style="min-width: 400px;">
      <div class="text-center mb-4">
        <img src="{{ asset('storage/logo.png') }}" alt="MGBK Logo" width="80">
        <h4 class="mt-2 fw-bold text-purple">MGBK</h4>
      </div>
      <h5 class="text-center mb-3">Silakan Login</h5>

      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
          <label>Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="d-grid">
          <button class="btn btn-purple text-white">Login</button>
        </div>
      </form>
      {{-- <div class="text-center mt-3">
        <a href="{{ route('password.request') }}" class="text-decoration-none text-secondary">Lupa password?</a>
      </div> --}}
    </div>
  </div>

  <!-- Wave SVG Background -->
  <svg class="wave" viewBox="0 0 1440 320">
    <path fill="#ffffff" fill-opacity="1"
      d="M0,160L40,149.3C80,139,160,117,240,128C320,139,400,181,480,192C560,203,640,181,720,176C800,171,880,181,960,165.3C1040,149,1120,107,1200,96C1280,85,1360,107,1400,117.3L1440,128L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z">
    </path>
  </svg>

  <!-- Notifikasi SweetAlert -->
  @if (session('status'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: "{{ session('status') }}",
      confirmButtonText: 'OK'
    });
  </script>
  @endif

  @if ($errors->any())
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Login Gagal',
      html: `{!! implode('<br>', $errors->all()) !!}`,
      confirmButtonText: 'Coba Lagi'
    });
  </script>
  @endif
</body>
</html>
