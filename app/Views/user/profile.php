<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<!-- Notifikasi -->
<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Profile Section -->
<div class="container mt-5">
    <div class="text-center mb-4">
        <!-- Upload Foto Form -->
        <form action="<?= base_url('/profile/image') ?>" method="post" enctype="multipart/form-data" id="uploadForm">
            <?= csrf_field() ?>
            <div class="position-relative d-inline-block">
                <?php
                $defaultImage = base_url('Assets/Profile Photo.png');
                $image = session()->get('profile_image') ?? $defaultImage;
                ?>
                <img src="<?= $image . '?t=' . time() ?>" class="profile-img" id="previewImage">

                <label for="profileImage" class="edit-icon" style="cursor: pointer;">
                    <img src="https://img.icons8.com/ios-glyphs/20/000000/edit--v1.png" alt="Edit Icon">
                </label>

                <input type="file" name="image" id="profileImage" accept="image/*" style="display: none;">
            </div>
        </form>
        <h4 class="mt-3"><?= esc($first_name ?? 'Nama Depan') ?> <?= esc($last_name ?? 'Nama Belakang') ?></h4>
    </div>

    <!-- Form -->
    <form action="/profile/update" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="PUT">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" readonly class="form-control" id="email" name="email" value="<?= esc($email ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="first_name" class="form-label">Nama Depan</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="<?= esc($first_name ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="last_name" class="form-label">Nama Belakang</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?= esc($last_name ?? '') ?>">
        </div>

        <?php if (isset($edit_mode) && $edit_mode): ?>
            <button type="submit" class="btn btn-danger w-100">Simpan</button>
        <?php else: ?>
            <a href="/profile/edit" class="btn btn-danger w-100">Edit Profil</a>
            <a href="/logout" class="btn btn-outline-danger w-100 mt-2">Logout</a>
        <?php endif; ?>
    </form>
</div>

<!-- Upload Preview Script -->
<script>
    const fileInput = document.getElementById('profileImage');
    const previewImage = document.getElementById('previewImage');
    const uploadForm = document.getElementById('uploadForm');

    fileInput.addEventListener('change', function() {
        const file = this.files[0];

        if (file && file.size > 102400) {
            alert("Ukuran maksimum 100KB!");
            this.value = '';
            return;
        }

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
            };
            reader.readAsDataURL(file);

            uploadForm.submit();
        }
    });
</script>

<?= $this->endSection(); ?>