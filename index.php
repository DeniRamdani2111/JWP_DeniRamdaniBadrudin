<?php
require_once 'functions.php';

$tugas = ambil_data();
$edit_id = isset($_GET['edit']) ? $_GET['edit'] : null;
$data_edit = $edit_id !== null ? $tugas[$edit_id] : ['nama'=>'', 'deskripsi'=>'', 'deadline'=>''];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Aplikasi To Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="col-md-8 mx-auto">
        <h2 class="text-center mb-4 text-primary">Aplikasi To Do List</h2>

        <?php if (isset($_GET['alert'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_GET['alert']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endif; ?>

        <form action="action.php" method="POST" class="card card-body mb-4 shadow-sm">
            <input type="hidden" name="aksi" value="<?= $edit_id !== null ? 'edit' : 'tambah' ?>">
            <?php if ($edit_id !== null): ?>
                <input type="hidden" name="id" value="<?= $edit_id ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Tugas</label>
                <input type="text" name="nama" id="nama" class="form-control" required
                       value="<?= htmlspecialchars($data_edit['nama']) ?>">
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <input type="text" name="deskripsi" id="deskripsi" class="form-control"
                       value="<?= htmlspecialchars($data_edit['deskripsi']) ?>">
            </div>
            <div class="mb-3">
                <label for="deadline" class="form-label">Deadline</label>
                <input type="date" name="deadline" id="deadline" class="form-control" required
                       value="<?= htmlspecialchars($data_edit['deadline']) ?>">
            </div>

            <div class="d-flex justify-content-between">
                <button class="btn btn-<?= $edit_id !== null ? 'warning' : 'primary' ?>" type="submit">
                    <?= $edit_id !== null ? 'Perbarui Tugas' : 'Tambah Tugas' ?>
                </button>
                <?php if ($edit_id !== null): ?>
                    <a href="index.php" class="btn btn-secondary">Batal Edit</a>
                <?php endif; ?>
            </div>
        </form>

        <div class="list-group shadow-sm">
            <?php foreach ($tugas as $index => $item): ?>
                <div class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold"><?= htmlspecialchars($item['nama']) ?></div>
                        <small><?= htmlspecialchars($item['deskripsi']) ?> - Deadline: <?= $item['deadline'] ?></small>
                    </div>
                    <div class="btn-group btn-group-sm">
                        <a href="index.php?edit=<?= $index ?>" class="btn btn-outline-secondary">Edit</a>
                        <a href="action.php?hapus=<?= $index ?>" class="btn btn-outline-danger"
                           onclick="return confirm('Yakin ingin menghapus tugas ini?')">Hapus</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
