<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registrasi SIMS PPOB</title>
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
            /* biar rapi */
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

        .password-container {
            position: relative;
        }

        .password-container input[type="password"] {
            padding-right: 40px;
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
                    <img src="Assets/Logo.png" alt="logo">
                    <span><strong>SIMS PPOB</strong></span>
                </div>
                <h2>Lengkapi data untuk membuat akun</h2>
            </div>
            <!-- Notifikasi -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger" style="color: red; margin-bottom: 15px;">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php elseif (session()->getFlashdata('success')): ?>
                <div class="alert alert-success" style="color: green; margin-bottom: 15px;">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('/registration') ?>" method="post">
                <?= csrf_field() ?>
                <input type="email" name="email" placeholder="masukan email anda" value="<?= old('email') ?>" required />
                <input type="text" name="first_name" placeholder="nama depan" value="<?= old('first_name') ?>" required />
                <input type="text" name="last_name" placeholder="nama belakang" value="<?= old('last_name') ?>" required />
                <div class="password-container">
                    <input type="password" name="password" placeholder="buat password" required />
                </div>
                <div class="password-container">
                    <input type="password" name="confirm_password" placeholder="konfirmasi password" required />
                </div>
                <button type="submit">Registrasi</button>
            </form>
            <div class="login-link">
                sudah punya akun? <a href="<?= base_url('/login') ?>">login di sini</a>
            </div>
        </div>
    </div>
    <div class="right">
        <div class="illustration">
            <img src="/Assets/illustrasi-login.png" alt="3D Character" />
        </div>
    </div>
    </div>
</body>

</html>