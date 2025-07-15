<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>


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
            <img src="<?= esc($user['profile_image'] ?? base_url('Assets/avatar.png')) ?>" alt="Avatar" class="rounded-circle rounded-avatar me-3">

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

    <div class="px-6 py-10">
        <h2 class="text-2xl font-bold mb-4">Semua Transaksi</h2>

        <!-- Filter Bulan -->
        <?php
        $months = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];
        $currentMonth = date('m');
        ?>

        <div id="bulan-buttons" class="flex flex-wrap gap-3 mb-6">
            <?php foreach ($months as $num => $name): ?>
                <button
                    data-bulan="<?= $num ?>"
                    class="px-4 py-2 text-sm text-gray-700 <?= $num === $currentMonth ? 'text-red-600' : 'bg-white text-gray-700' ?>">
                    <?= $name ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Transaksi -->
        <div id="tx-container" class="space-y-6"></div>

        <!-- Tombol Load More -->
        <div class="text-center mt-6">
            <a id="load-more" class="px-4 py-2 hover:underline text-red-600 rounded">Load More</a>
        </div>
    </div>
</div>
<!-- JS -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let offset = 0;
        let selectedMonth = null;

        async function fetchData(reset = false) {
            document.getElementById('load-more').disabled = true;

            const params = new URLSearchParams({
                offset
            });
            if (selectedMonth) params.append('month', selectedMonth);

            const res = await fetch(`<?= base_url('transaction/loadMore') ?>?${params.toString()}`);
            const json = await res.json();

            if (!json.success) return alert(json.message);

            if (reset) document.getElementById('tx-container').innerHTML = '';

            for (const [bulan, txs] of Object.entries(json.data)) {
                const monthBlock = document.createElement('div');
                txs.forEach(tx => {
                    const isTopup = tx.transaction_type === 'TOPUP';
                    const amount = new Intl.NumberFormat('id-ID').format(tx.total_amount);
                    const date = new Date(tx.created_on);
                    const tanggal = date.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    });
                    const jam = date.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    // Loop untuk setiap transaksi
                    monthBlock.innerHTML += `
                            <div class="flex justify-between border border-gray-200 rounded-lg p-4 mb-3">
                                <div>
                                    <div class="${isTopup ? 'text-green-500' : 'text-red-500'} font-semibold">
                                        ${isTopup ? '+' : '-'} Rp${amount}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        ${tanggal} • ${jam} WIB
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500">
                                    ${tx.description}
                                </div>
                            </div>`;
                });
                document.getElementById('tx-container').appendChild(monthBlock);
            }

            offset = json.nextOffset;

            // Cek apakah masih ada data untuk dimuat
            if (json.hasMore) {
                document.getElementById('load-more').classList.remove('hidden');
                document.getElementById('load-more').disabled = false;
            } else {
                document.getElementById('load-more').classList.add('hidden');
            }
        }

        // Tombol Load More
        document.getElementById('load-more').addEventListener('click', () => {
            fetchData();
        });

        // Tombol Bulan
        document.querySelectorAll('#bulan-buttons button').forEach(btn => {
            btn.addEventListener('click', () => {
                offset = 0;
                selectedMonth = btn.dataset.bulan;

                // Reset semua tombol ke tampilan default
                document.querySelectorAll('#bulan-buttons button').forEach(b => {
                    b.className = "px-4 py-2 text-sm bg-white text-gray-700";
                });

                // Set tombol aktif
                btn.className = "px-4 py-2 text-sm text-red-600";

                fetchData(true); // reset dan load data baru
            });
        });

        // Load data awal
        fetchData(true);
    });

    function toggleSaldo() {
        const saldoText = document.getElementById('saldoText');
        const realText = "Rp <?= number_format($balance, 0, ',', '.') ?>";
        const hiddenText = "Rp ●●●●●●●●";

        saldoText.innerText = saldoText.innerText.includes('●') ? realText : hiddenText;
    }
</script>

</main>

<?= $this->endSection() ?>