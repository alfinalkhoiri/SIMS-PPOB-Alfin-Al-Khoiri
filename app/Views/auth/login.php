<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - SIMS PPOB</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            height: 100vh;
            background-color: #fff;
        }

        .container {
            display: flex;
            flex: 1;
        }

        .left {
            max-width: 400px;
            margin: 0 auto;
            padding: 60px 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .logo img {
            width: 24px;
            height: 24px;
        }

        h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
        }

        input,
        button {
            width: 100%;
            padding: 14px 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
            box-sizing: border-box;
        }

        input::placeholder {
            color: #aaa;
        }

        button {
            padding: 14px;
            background-color: #F44336;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .login-link {
            margin-top: 10px;
            font-size: 14px;
            text-align: center;
        }

        .login-link a {
            color: #F44336;
            text-decoration: none;
            font-weight: 600;
        }

        .right {
            width: 50%;
            background-color: #FFF5F5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .illustration img {
            max-width: 80%;
        }

        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #e7f9ed;
            color: #2d7a46;
        }

        .alert-danger {
            background-color: #ffe5e5;
            color: #c00;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .left,
            .right {
                width: 100%;
            }

            .right {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left">
            <div class="header">
                <div class="logo">
                    <img src="<?= base_url('Assets/Logo.png') ?>" alt="logo">
                    <span><strong>SIMS PPOB</strong></span>
                </div>
                <h2>Masuk atau buat akun untuk memulai</h2>
            </div>

            <!-- Flash Alert -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php elseif (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <!-- Validasi Error -->
            <?php if (isset($validation)): ?>
                <div class="alert alert-danger">
                    <?= $validation->listErrors() ?>
                </div>
            <?php endif; ?>

            <!-- Form Login -->
            <form action="<?= base_url('/login') ?>" method="post">
                <?= csrf_field() ?>

                <input type="email" name="email" placeholder="masukan email anda"
                    value="<?= old('email') ?>" required
                    title="Masukkan format email yang valid" />

                <input type="password" name="password" placeholder="masukan password anda"
                    required minlength="8"
                    title="Password minimal 8 karakter" />

                <button type="submit">Masuk</button>
            </form>

            <div class="login-link">
                belum punya akun? <a href="<?= base_url('/registration') ?>">registrasi di sini</a>
            </div>
        </div>

        <div class="right">
            <div class="illustration">
                <img src="<?= base_url('Assets/illustrasi-login.png') ?>" alt="3D Character" />
            </div>
        </div>
    </div>
</body>

</html>