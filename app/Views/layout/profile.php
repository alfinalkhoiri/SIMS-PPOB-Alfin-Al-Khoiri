<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<style>
    .balance-card {
        background-color: #f44336;
        color: #fff;
        border-radius: 12px;
        padding: 16px;
    }

    .amount-btn {
        min-width: 110px;
    }

    .topup-box {
        border: 1px solid #ddd;
        border-radius: 12px;
        padding: 24px;
        background-color: #fff;
    }

    .rounded-avatar {
        width: 60px;
        height: 60px;
        object-fit: cover;
    }

    .btn-danger {
        background-color: #f44336;
        border-color: #f44336;
    }

    .btn-danger:hover {
        background-color: #d7372d;
        border-color: #d7372d;
    }
</style>

<div class="container mt-5">
    <div class="row align-items-center mb-4">
        <!-- User Info -->
        <div class="col-md-6 d-flex align-items-center">
            <img src="<?= $profile_image ?>" alt="Avatar" class="rounded-circle rounded-avatar me-3">
            <div>
                <p class="mb-1 text-muted">Selamat datang,</p>
                <h5 class="mb-0"><?= esc($first_name) ?> <?= esc($last_name) ?></h5>
            </div>
        </div>

        <!-- Saldo -->
        <div class="col-md-6">
            <div class="balance-card text-center">
                <p class="mb-1">Saldo Anda</p>
                <h3 id="saldoText">Rp <?= number_format($balance, 0, ',', '.') ?></h3>
                <a href="javascript:void(0)" class="text-white text-decoration-underline small" onclick="toggleSaldo()">Sembunyikan Saldo 👁️</a>
            </div>
        </div>
    </div>