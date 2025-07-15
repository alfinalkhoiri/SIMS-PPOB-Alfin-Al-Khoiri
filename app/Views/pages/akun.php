<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- Profile Section -->
<div class="container mt-5">
    <div class="text-center mb-4">
        <div class="position-relative d-inline-block">
            <img src="<?= $profile_image ?? 'Assets/Profile Photo.png' ?>" class="profile-img">
            <span class="edit-icon">
                <img src="https://img.icons8.com/ios-glyphs/20/000000/edit--v1.png" />
            </span>
        </div>
        <h4 class="mt-3"><?= $first_name ?? 'Nama Depan' ?> <?= $last_name ?? 'Nama Belakang' ?></h4>
    </div>

    <!-- Form -->
    <form action="/profile/update" method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" readonly class="form-control" id="email" name="email" value="<?= $email ?? '' ?>">
        </div>
        <div class="mb-3">
            <label for="first_name" class="form-label">Nama Depan</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="<?= $first_name ?? '' ?>">
        </div>
        <div class="mb-3">
            <label for="last_name" class="form-label">Nama Belakang</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?= $last_name ?? '' ?>">
        </div>

        <?php if (isset($edit_mode) && $edit_mode): ?>
            <button type="submit" class="btn btn-danger w-100">Simpan</button>
        <?php else: ?>
            <a href="/profile/edit" class="btn btn-danger w-100">Edit Profil</a>
            <a href="/logout" class="btn btn-outline-danger w-100 mt-2">Logout</a>
        <?php endif; ?>
    </form>
</div>
<?= $this->endSection(); ?>