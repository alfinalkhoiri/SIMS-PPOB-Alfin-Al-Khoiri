<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="path_to_your_stylesheet.css">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #333;
        }

        /* Mengatur warna teks service_name agar tidak biru */
        .service-item p {
            color: inherit;
        }

        /* NAVBAR */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            border-bottom: 1px solid #eee;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo img {
            width: 24px;
            height: 24px;
        }

        .menu a {
            margin-left: 20px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        /* GREETING + SALDO */
        .greeting {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 40px 60px;
            flex-wrap: wrap;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
        }

        .saldo-card {
            background-color: #F44336;
            color: white;
            padding: 20px 30px;
            border-radius: 16px;
            max-width: 300px;
            text-align: left;
        }

        .saldo-card a {
            color: white;
            text-decoration: underline;
            font-size: 14px;
        }

        /* SERVICES */
        .services {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 30px 60px;
            gap: 30px;
        }

        .service-item {
            text-align: center;
            width: 80px;
        }

        .service-item img {
            width: 50px;
            height: 50px;
            margin-bottom: 8px;
        }

        .service-item p {
            font-size: 14px;
            margin: 0;
        }

        /* PROMO */
        .promo {
            padding: 40px 60px;
        }

        .promo h3 {
            margin-bottom: 20px;
        }

        .promo-cards {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .promo-cards img {
            width: 250px;
            border-radius: 12px;
            flex-shrink: 0;
        }

        .logo a {
            text-decoration: none;
            color: inherit;
            display: inline-block;
        }
    </style>
</head>

<body>
    <?= $this->include('layout/navbar'); ?>

    <?= $this->renderSection('content'); ?>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>

</html>