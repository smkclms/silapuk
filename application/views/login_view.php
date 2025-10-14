<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #2c3e50, #34495e);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50px; /* sesuaikan jika perlu */
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            font-size: 1.2rem;
            z-index: 10;
        }

        .position-relative {
            position: relative;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h3 class="text-center mb-4">Silakan Login</h3>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('auth/authenticate'); ?>" method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required />
        </div>

        <div class="mb-4 position-relative">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required />
            <i class="bi bi-eye-slash password-toggle" id="togglePassword"></i>
        </div>

        <div class="mb-3">
            <label for="tahun_anggaran" class="form-label">Pilih Tahun Anggaran</label>
            <select name="tahun_anggaran" id="tahun_anggaran" class="form-select" required>
                <?php foreach ($tahun_anggaran as $tahun): ?>
                    <option value="<?= $tahun->id ?>"><?= $tahun->tahun ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Login</button>
        </div>
    </form>
</div>

<!-- Bootstrap JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Show/Hide Password Script -->
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });
</script>

</body>
</html>
