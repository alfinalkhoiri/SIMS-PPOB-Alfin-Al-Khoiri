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

    <!-- Top Up Box -->
    <div class="topup-box shadow-sm">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php elseif (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <h5 class="fw-bold mb-3">Masukkan <span class="text-danger">Nominal Top Up</span></h5>

        <form action="<?= base_url('topup/submit') ?>" method="post">
            <?= csrf_field() ?>
            <div class="input-group mb-3">
                <span class="input-group-text bg-light"><i class="bi bi-credit-card"></i></span>
                <input type="number" class="form-control" name="amount" id="amountInput"
                    placeholder="Minimal 10.000" min="10000" max="1000000" required>
            </div>

            <!-- Preset Amount Buttons -->
            <div class="d-flex flex-wrap gap-2 mb-3">
                <?php foreach ([10000, 20000, 50000, 100000, 250000, 500000] as $preset): ?>
                    <button type="button" class="btn btn-outline-secondary amount-btn"
                        onclick="setAmount(<?= $preset ?>)">
                        Rp<?= number_format($preset, 0, ',', '.') ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="btn btn-danger w-100" id="submitBtn" disabled>Top Up</button>
        </form>
    </div>
</div>

<!-- Scripts -->
<script>
    const amountInput = document.getElementById('amountInput');
    const submitBtn = document.getElementById('submitBtn');

    function setAmount(amount) {
        amountInput.value = amount;
        validateAmount();
    }

    function validateAmount() {
        const val = parseInt(amountInput.value);
        submitBtn.disabled = !(val >= 10000 && val <= 1000000);
    }

    amountInput.addEventListener('input', validateAmount);

    function toggleSaldo() {
        const saldoText = document.getElementById('saldoText');
        const realText = "Rp <?= number_format($balance, 0, ',', '.') ?>";
        const hiddenText = "Rp ●●●●●●●●";

        saldoText.innerText = saldoText.innerText.includes('●') ? realText : hiddenText;
    }
</script>

<?= $this->endSection(); ?>