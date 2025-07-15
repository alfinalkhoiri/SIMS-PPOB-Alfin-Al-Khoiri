<?php echo $this->extend('layout/template'); ?>

<?php echo $this->section('content'); ?>
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
            <img src="<?= esc($user['profile_image'] ?? base_url('Assets/avatar.png')) ?>" class="rounded-circle rounded-avatar me-3">
            <div>
                <p class="mb-0">Selamat datang,</p>
                <h4 class="mb-0"><?= esc($user['first_name']) ?> <?= esc($user['last_name']) ?></h4>
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

    <div class="row align-items-center mb-4">
        <div class="max-w-xl mx-auto py-10 px-4" x-data="{ showModal: false }">
            <h2 class="text-xl font-semibold mb-4">Pembayaran</h2>

            <!-- Service Info -->
            <div class="flex items-center gap-3 mb-4">
                <img src="<?= esc($service['service_icon']) ?>" alt="icon" class="w-10 h-10">
                <span class="text-lg font-semibold"><?= esc($service['service_name']) ?></span>
            </div>

            <!-- Input Harga -->
            <div class="relative mb-4">
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                    <input type="text" disabled readonly
                        value="Rp <?= number_format($service['service_tariff'], 0, ',', '.') ?>"
                        class="bg-gray-50 rounded border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 ">
                </div>
            </div>

            <!-- Tombol Bayar -->
            <button data-modal-target="modal-konfirmasi" data-modal-toggle="modal-konfirmasi"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-lg text-center">
                Bayar
            </button>

            <!-- Modal Konfirmasi Pembayaran -->
            <div id="modal-konfirmasi" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50 backdrop-blur-sm">

                <div class="relative p-4 w-full max-w-md max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow p-6 text-center">

                        <!-- Icon -->
                        <div class="flex justify-center mb-4">
                            <img src="<?= esc($service['service_icon']) ?>" alt="icon" class="w-12 h-12">
                        </div>

                        <!-- Judul & Nominal -->
                        <h3 class="mb-1 text-gray-800">Beli <?= esc($service['service_name']) ?> senilai
                        </h3>
                        <p class="text-2xl font-bold text-gray-900 mb-4">
                            Rp<?= number_format($service['service_tariff'], 0, ',', '.') ?> ?
                        </p>

                        <!-- Form -->
                        <form action="<?= base_url('payment/transaction') ?>" method="post"
                            class="flex flex-col items-center gap-3">
                            <?= csrf_field() ?>
                            <input type="hidden" name="service_code" value="<?= esc($service['service_code']) ?>">

                            <!-- Tombol Lanjut -->
                            <button data-modal-hide="modal-konfirmasi" type="submit"
                                class="text-red-600 font-semibold hover:underline text-sm">
                                Ya, lanjutkan Bayar
                            </button>

                            <!-- Tombol Batal -->
                            <button data-modal-hide="modal-konfirmasi" type="button"
                                class="text-gray-500 font-medium hover:underline text-sm">
                                Batalkan
                            </button>
                        </form>
                    </div>
                </div>
            </div>



            <!-- Modal feedback -->
            <div id="modal-feedback" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50 backdrop-blur-sm">

                <div class="relative p-4 w-full max-w-md max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow p-6 text-center">

                        <!-- Icon -->
                        <div class="flex justify-center mb-4">
                            <?php if (session('success')): ?>
                                <i class="fa-solid fa-circle-check text-green-500 text-4xl"></i>
                            <?php else: ?>
                                <i class="fa-solid fa-circle-xmark text-red-500 text-4xl"></i>
                            <?php endif; ?>
                        </div>

                        <!-- Judul & Nominal -->
                        <h3 class="mb-1 text-gray-800">Pembayaran <?= esc($service['service_name']) ?> senilai</h3>
                        <p class="text-2xl font-bold text-gray-900 mb-4">
                            Rp<?= number_format($service['service_tariff'], 0, ',', '.') ?>
                        </p>

                        <!-- Status -->
                        <h3 class="mb-1 text-gray-800 font-bold">
                            <?= session('success') ? 'Berhasil' : 'Gagal' ?>
                        </h3>

                        <!-- Pesan -->
                        <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                            <?= esc(session('success') ?? session('error')) ?>
                        </p>

                        <!-- Tombol -->
                        <div class="flex justify-center">
                            <a href="<?= base_url('/') ?>"
                                class="<?= session('success') ? 'text-green-600' : 'text-red-600' ?> font-semibold hover:underline text-sm">
                                Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Script Trigger Modal Feedback -->
            <?php if (session('success') || session('error')): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const modalElement = document.getElementById('modal-feedback');
                        if (modalElement && window.Flowbite?.default?.Modal) {
                            const modal = new window.Flowbite.default.Modal(modalElement);
                            modal.show();

                            <?php if (session('success')): ?>
                                setTimeout(() => {
                                    window.location.href = "<?= base_url('/') ?>";
                                }, 3000);
                            <?php endif; ?>
                        }
                    });
                </script>
            <?php endif; ?>



        </div>

    </div>

    <script>
        const form = document.getElementById('transactionForm');
        const amountInput = document.getElementById('amountInput');
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        const failModal = new bootstrap.Modal(document.getElementById('failModal'));

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const amount = parseInt(amountInput.value);

            document.getElementById('confirmAmount').innerText = "Rp" + amount.toLocaleString("id-ID");
            confirmModal.show();
        });

        document.getElementById('confirmYes').addEventListener('click', function() {
            confirmModal.hide();

            const amount = amountInput.value;
            const serviceCode = form.querySelector('input[name="service_code"]').value;

            fetch("<?= base_url('transaction/submit') ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `amount=${amount}&service_code=${serviceCode}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
                })
                .then(response => response.json())
                .then(result => {
                    const formattedAmount = "Rp" + parseInt(amount).toLocaleString("id-ID");
                    if (result.status === 0) {
                        document.getElementById('successAmount').innerText = formattedAmount;
                        successModal.show();
                    } else {
                        document.getElementById('failAmount').innerText = formattedAmount;
                        failModal.show();
                    }
                })
                .catch(() => {
                    document.getElementById('failAmount').innerText = "Rp" + parseInt(amount).toLocaleString("id-ID");
                    failModal.show();
                });
        });
    </script>
    <!-- Scripts -->
    <script>
        function toggleSaldo() {
            const saldoText = document.getElementById('saldoText');
            const realText = "Rp <?= number_format($balance, 0, ',', '.') ?>";
            const hiddenText = "Rp ●●●●●●●●";

            saldoText.innerText = saldoText.innerText.includes('●') ? realText : hiddenText;
        }
    </script>
    <?php echo $this->endSection(); ?>