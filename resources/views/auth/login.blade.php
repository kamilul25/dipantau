<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DIPANTAU</title>
    <link rel="icon" href="{{ asset('images/dinas.png') }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

    <style>
        #particles-js {
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            background: linear-gradient(135deg, #3a8dde 0%, #1e3c72 100%);
        }

        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Roboto', sans-serif;
            overflow: hidden;
        }

        .login-card {
            position: relative;
            z-index: 1;
            max-width: 420px;
            margin: 100px auto;
            padding: 30px;
            border-radius: 12px;
            background-color: rgba(255, 255, 255, 0.90);
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        }

        .login-card h3 {
            margin-bottom: 25px;
            color: #2c3e50;
            font-weight: 700;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }

        @media (max-height: 600px) {
            .login-card {
                margin: 40px auto;
            }
        }
    </style>
</head>
<body>

<div id="particles-js"></div>

<div class="container">
    <div class="login-card shadow">

        <div class="text-center mb-3">
            <img src="{{ asset('images/dinas.png') }}" alt="Logo"
                 style="max-width: 70px;">
        </div>

        <h3 class="text-center">DIPANTAU</h3>

        {{-- Error Validation --}}
        @if($errors->any())
            <div class="alert alert-danger">
                Username atau password salah!
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" autocomplete="off">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email"
                       name="email"
                       class="form-control"
                       placeholder="Masukkan email"
                       required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password"
                       name="password"
                       class="form-control"
                       placeholder="Masukkan password"
                       required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk
                </button>
            </div>

        </form>

        <div class="mt-3 text-center">
            <a href="{{ route('publik.beranda') }}">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>

    </div>
</div>


<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Particles.js -->
<script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>

<script>
particlesJS("particles-js", {
  "particles": {
    "number": { "value": 60, "density": { "enable": true, "value_area": 800 }},
    "color": { "value": "#ffffff" },
    "shape": { "type": "circle" },
    "opacity": { "value": 0.5 },
    "size": { "value": 3, "random": true },
    "line_linked": {
      "enable": true,
      "distance": 150,
      "color": "#ffffff",
      "opacity": 0.3,
      "width": 1
    },
    "move": { "enable": true, "speed": 3 }
  },
  "interactivity": {
    "events": {
      "onhover": { "enable": true, "mode": "grab" }
    },
    "modes": {
      "grab": { "distance": 140, "line_linked": { "opacity": 0.6 }}
    }
  },
  "retina_detect": true
});
</script>

</body>
</html>