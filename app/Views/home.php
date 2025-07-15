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
<!-- USER SECTION -->
<section class="greeting">
    <div class="user-info">
        <img src="<?= $avatar ?>" alt="Avatar" class="avatar" />
        <div>
            <p>Selamat datang,</p>
            <h2><?= esc($first_name) ?> <?= esc($last_name) ?></h2>
        </div>
    </div>

    <div class="col-md-6">
        <div class="balance-card text-center">
            <div class="saldo-card">
                <p>Saldo anda</p>
                <h2>Rp <?= number_format($balance, 0, ',', '.') ?></h2>
                <a href="#" onclick="toggleSaldo()">Sembunyikan Saldo 👁️</a>
            </div>
        </div>
    </div>

</section>

<!-- SERVICES -->
<section class="services">
    <?php if (!empty($services)): ?>
        <?php foreach ($services as $service): ?>
            <a href="<?= base_url('payment/' . esc($service['service_code'])) ?>" class="service-item">
                <img src="<?= esc($service['service_icon']) ?>"
                    alt="<?= esc($service['service_name']) ?>"
                    class="w-10 h-10 mx-auto mb-1 object-contain">

                <p class="text-xs leading-tight" style="color: inherit;"><?= esc($service['service_name']) ?></p>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-sm text-gray-500">Layanan belum tersedia.</p>
    <?php endif; ?>
</section>



<!-- BANNER -->
<section class="promo">
    <h3>Temukan promo menarik</h3>
    <div class="promo-cards">
        <?php foreach ($banners as $banner): ?>
            <img src="<?= $banner['banner_image'] ?>" alt="Promo">
        <?php endforeach; ?>
    </div>
</section>

<script>
    function toggleSaldo() {
        const saldoText = document.querySelector('.saldo-card h2');
        if (saldoText.innerText.includes('●')) {
            saldoText.innerText = "Rp <?= number_format($balance, 0, ',', '.') ?>";
        } else {
            saldoText.innerText = "Rp ●●●●●●●●";
        }
    }
</script>


<?= $this->endSection(); ?>