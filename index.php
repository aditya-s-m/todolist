<?php
// index.php - Halaman utama aplikasi Todo List
require_once 'koneksi.php';
require_once 'functions.php';

// Proses aksi POST/GET
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_task'])) {
        // Tambah tugas baru
        $title = trim($_POST['title']);
        
        if (!empty($title)) {
            addTask($pdo, $title);
            header('Location: index.php');
            exit;
        }
    }
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($action === 'toggle' && $id > 0) {
        // Toggle status tugas
        toggleTaskStatus($pdo, $id);
        header('Location: index.php');
        exit;
    } elseif ($action === 'delete' && $id > 0) {
        // Hapus tugas
        deleteTask($pdo, $id);
        header('Location: index.php');
        exit;
    }
}

// Ambil semua tugas
$tasks = getAllTasks($pdo);
// echo "<pre>";
// var_dump($tasks);
// echo "</pre>";

$totalTasks = getTotalTasks($pdo);
$completedTasks = getCompletedTasks($pdo);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="main-container">
        <!-- Header -->
        <div class="text-center mb-4">
            <h1 class="display-4 text-secondary fw-bold">
                Todo List App
            </h1>
            <p class="text-white-50">Kelola tugas Anda dengan mudah</p>
        </div>

        <div class="box-wrapper">
            <div class="left-box">
                <!-- Task Card -->
                <div class="stats-card bg-secondary">
                    <div class="row text-center">
                        <div class="col-6">
                            <h3 class="mb-0"><?php echo $totalTasks; ?></h3>
                            <small>Total Tugas</small>
                        </div>
                        <div class="col-6">
                            <h3 class="mb-0"><?php echo $completedTasks; ?></h3>
                            <small>Selesai</small>
                        </div>
                    </div>
                </div>

                <!-- Form Tambah Tugas -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Tambah Tugas Baru</h5>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <input type="text" class="form-control" name="title" 
                                    placeholder="Judul tugas..." required>
                            </div>
                            <button type="submit" name="add_task" class="btn btn-secondary w-100">Tambah Tugas</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="right-box">
                <!-- Daftar Tugas -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Daftar Tugas</h5>
                        
                        <?php if (empty($tasks)): ?>
                            <div class="text-center text-muted py-5">
                                <p class="mt-3">Belum ada tugas. Tambahkan tugas pertama Anda!</p>
                            </div>
                        <?php else: ?>
                            <div class="list-group">
                                <?php foreach ($tasks as $task): ?>
                                    <div class="list-group-item task-item <?php echo $task['is_completed'] ? 'task-completed' : ''; ?>">
                                        <div class="d-flex align-items-start">
                                            <!-- Checkbox -->
                                            <div class="form-check me-3">
                                                <input class="form-check-input" type="checkbox" 
                                                       <?php echo $task['is_completed'] ? 'checked' : ''; ?>
                                                       onchange="window.location.href='?action=toggle&id=<?php echo $task['id']; ?>'">
                                            </div>
                                            
                                            <!-- Task Content -->
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 task-title"><?php echo htmlspecialchars($task['title']); ?></h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-clock"></i> 
                                                    <?php echo date('d M Y, H:i', strtotime($task['created_at'])); ?>
                                                </small>
                                            </div>
                                            
                                            <!-- Delete Button -->
                                            <div>
                                                <a href="?action=delete&id=<?php echo $task['id']; ?>" 
                                                   class="btn btn-sm btn-outline-danger"
                                                   onclick="return confirm('Yakin ingin menghapus tugas ini?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>


        <!-- Footer -->
        <div class="text-center mt-4">
            <p class="text-white-50 small">
                &copy; <?php echo date('Y'); ?> Todo List App. Dibuat dengan PHP & Bootstrap
            </p>
        </div>
    </div>
</body>
</html>