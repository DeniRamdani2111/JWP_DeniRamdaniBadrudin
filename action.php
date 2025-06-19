<?php
require_once 'functions.php';

$tugas = ambil_data();
$alert = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'];
    $dataBaru = [
        'nama' => $_POST['nama'],
        'deskripsi' => $_POST['deskripsi'],
        'deadline' => $_POST['deadline']
    ];

    if ($aksi === 'tambah') {
        $tugas[] = $dataBaru;
        $alert = 'Tugas berhasil ditambahkan.';
    } elseif ($aksi === 'edit' && isset($_POST['id'])) {
        $tugas[$_POST['id']] = $dataBaru;
        $alert = 'Tugas berhasil diperbarui.';
    }

    simpan_data($tugas);
    header("Location: index.php?alert=" . urlencode($alert));
    exit();
}

if (isset($_GET['hapus'])) {
    unset($tugas[$_GET['hapus']]);
    $tugas = array_values($tugas); // reset indeks
    simpan_data($tugas);
    header("Location: index.php?alert=" . urlencode('Tugas berhasil dihapus.'));
    exit();
}
