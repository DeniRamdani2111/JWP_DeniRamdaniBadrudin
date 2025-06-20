<?php
session_start();

// Inisialisasi array tugas di session
if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}
$tasks = &$_SESSION['tasks'];

// Fungsi untuk menambahkan tugas
function addTask($title, &$tasks) {
    $tasks[] = [
        'title' => htmlspecialchars($title),
        'done' => false
    ];
}

// Tambah tugas baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task']) && !isset($_POST['edit_index'])) {
    $newTask = trim($_POST['task']);
    if ($newTask !== '') {
        addTask($newTask, $tasks);
    }
    header("Location: todolist.php");
    exit;
}

// Edit tugas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_index'])) {
    $editIndex = (int)$_POST['edit_index'];
    $editTitle = trim($_POST['edit_task']);
    if ($editTitle !== '' && isset($tasks[$editIndex])) {
        $tasks[$editIndex]['title'] = htmlspecialchars($editTitle);
    }
    header("Location: todolist.php");
    exit;
}

// Tandai selesai
if (isset($_GET['done'])) {
    $doneIndex = (int)$_GET['done'];
    if (isset($tasks[$doneIndex])) {
        $tasks[$doneIndex]['done'] = !$tasks[$doneIndex]['done'];
    }
    header("Location: todolist.php");
    exit;
}

// Hapus tugas
if (isset($_GET['delete'])) {
    $deleteIndex = (int)$_GET['delete'];
    if (isset($tasks[$deleteIndex])) {
        array_splice($tasks, $deleteIndex, 1);
    }
    header("Location: todolist.php");
    exit;
}

// Mode edit
$editMode = isset($_GET['edit']) ? (int)$_GET['edit'] : -1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Tugas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-color: #4cc9f0;
            --warning-color: #f8961e;
            --danger-color: #f72585;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gray-color: #6c757d;
            --bg-gradient: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        }
        
        body {
            background: var(--bg-gradient);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            color: var(--dark-color);
        }
        
        .container {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .card {
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .list-group-item {
            transition: all 0.3s ease;
            border-radius: 10px !important;
            margin-bottom: 8px;
            background-color: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(0, 0, 0, 0.05);
            padding: 15px 20px;
        }
        
        .list-group-item:hover {
            background: rgba(248, 249, 250, 0.9);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transform: translateX(5px);
        }
        
        .done-task {
            text-decoration: line-through;
            color: var(--gray-color);
            background-color: rgba(248, 249, 250, 0.7);
        }
        
        .btn-action {
            border: none;
            background: none;
            padding: 0 8px;
            color: var(--primary-color);
            font-size: 1.1rem;
            transition: all 0.2s ease;
        }
        
        .btn-action:hover {
            color: var(--secondary-color);
            transform: scale(1.2);
        }
        
        .badge-status {
            font-size: 0.75rem;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        
        .edit-form {
            margin-top: 15px;
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-control {
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 10px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .btn {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 0.85rem;
        }
        
        .btn-success {
            background-color: var(--success-color);
            border: none;
        }
        
        .btn-success:hover {
            background-color: #3aa8d8;
            transform: translateY(-2px);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: var(--gray-color);
            border: none;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }
        
        h1, h4 {
            color: var(--dark-color);
            font-weight: 700;
        }
        
        h1 {
            background: var(--bg-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }
        
        .empty-state {
            padding: 30px;
            text-align: center;
            color: var(--gray-color);
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #dee2e6;
        }
        
        .progress-container {
            margin-bottom: 20px;
        }
        
        .progress {
            height: 10px;
            border-radius: 5px;
            background-color: #e9ecef;
        }
        
        .progress-bar {
            background-color: var(--success-color);
        }
        
        .task-counter {
            font-size: 0.9rem;
            color: var(--gray-color);
            margin-bottom: 15px;
        }
        
        .floating-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 5px 20px rgba(67, 97, 238, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
            border: none;
        }
        
        .floating-btn:hover {
            transform: scale(1.1) translateY(-5px);
            background: var(--secondary-color);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container my-5" style="max-width: 650px;">
        <header class="text-center mb-5">
            <h1 class="fw-bold display-4"><i class="bi bi-check2-circle me-2"></i>Task Management Assistant</h1>
            <p class="text-white-50">Kelola tugas harianmu dengan lebih produktif</p>
        </header>

        <!-- Progress Indicator -->
        <?php if (!empty($tasks)): ?>
            <div class="progress-container card p-3 mb-4">
                <?php 
                    $totalTasks = count($tasks);
                    $completedTasks = count(array_filter($tasks, function($task) { return $task['done']; }));
                    $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                ?>
                <div class="task-counter d-flex justify-content-between mb-2">
                    <span><i class="bi bi-list-task me-1"></i> Total: <?= $totalTasks ?></span>
                    <span><i class="bi bi-check-circle me-1"></i> Selesai: <?= $completedTasks ?></span>
                </div>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: <?= $progress ?>%" 
                         aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Form Tambah Tugas -->
        <section class="card p-4 mb-4">
            <form method="post" action="">
                <div class="row g-2 align-items-center">
                    <div class="col-md-9 col-8">
                        <input type="text" name="task" class="form-control form-control-lg" 
                               placeholder="Apa yang ingin kamu kerjakan hari ini?" required autocomplete="off"
                               autofocus>
                    </div>
                    <div class="col-md-3 col-4 d-grid">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-plus-lg me-1"></i> Tambah
                        </button>
                    </div>
                </div>
            </form>
        </section>

        <!-- Daftar Tugas -->
        <section class="card p-4">
            <h4 class="mb-3 d-flex align-items-center">
                <i class="bi bi-card-checklist me-2"></i> Daftar Tugas
                <span class="badge bg-primary ms-2"><?= count($tasks) ?></span>
            </h4>
            
            <ul class="list-group">
                <?php if (empty($tasks)): ?>
                    <div class="empty-state py-4">
                        <i class="bi bi-emoji-smile-fill text-muted" style="font-size: 2.5rem;"></i>
                        <h5 class="mt-3">Tidak ada tugas</h5>
                        <p class="text-muted mb-0">Tambahkan tugas pertama Anda</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($tasks as $index => $task): ?>
                        <li class="list-group-item d-flex flex-column p-3 <?= $task['done'] ? 'done-task' : '' ?>">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center flex-grow-1">
                                    <div class="form-check me-3">
                                        <input type="checkbox" class="form-check-input" 
                                            <?= $task['done'] ? 'checked' : '' ?>
                                            onchange="window.location.href='?done=<?= $index ?>'"
                                            style="width: 1.2em; height: 1.2em; cursor: pointer;">
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="task-title fw-medium <?= $task['done'] ? 'text-muted' : '' ?>">
                                            <?= $task['title'] ?>
                                        </span>
                                        <div class="mt-1">
                                            <span class="badge <?= $task['done'] ? 'bg-success' : 'bg-warning text-dark' ?> badge-status">
                                                <i class="bi <?= $task['done'] ? 'bi-check-circle' : 'bi-hourglass' ?> me-1"></i>
                                                <?= $task['done'] ? 'Selesai' : 'Dalam Proses' ?>
                                            </span>
                                            <small class="text-muted ms-2">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                <?= date('d M Y') ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <a href="?edit=<?= $index ?>" class="btn-action text-primary" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="?delete=<?= $index ?>" class="btn-action text-danger ms-2" title="Hapus" 
                                    onclick="return confirm('Yakin ingin menghapus tugas ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Form Edit -->
                            <?php if ($editMode === $index): ?>
                                <form method="post" class="edit-form mt-3" action="">
                                    <div class="input-group">
                                        <input type="text" name="edit_task" class="form-control" 
                                            value="<?= $task['title'] ?>" required>
                                        <input type="hidden" name="edit_index" value="<?= $index ?>">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save me-1"></i> Simpan
                                        </button>
                                        <a href="todolist.php" class="btn btn-outline-secondary">
                                            <i class="bi bi-x me-1"></i> Batal
                                        </a>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </section>
    </div>

    <!-- Floating Action Button -->
    <button class="floating-btn" onclick="document.querySelector('input[name=\'task\']').focus()">
        <i class="bi bi-plus"></i>
    </button>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animasi saat elemen muncul
        document.addEventListener('DOMContentLoaded'), function() {
            const items = document.querySelectorAll('.list-group-item');
            items.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
            });
        };
    </script>
</body>
</html>