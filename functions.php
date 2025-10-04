<?php
    // functions.php - File ini untuk fungsi-fungsi aplikasi

    // Fungsi untuk mendapatkan semua tugas
    function getAllTasks($pdo) {
        $stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    // Fungsi untuk menambah tugas baru
    function addTask($pdo, $title) {
        $stmt = $pdo->prepare("INSERT INTO tasks (title) VALUES (?)");
        return $stmt->execute([$title]);
    }

    // Fungsi untuk mengubah status tugas
    function toggleTaskStatus($pdo, $id) {
        $stmt = $pdo->prepare("UPDATE tasks SET is_completed = NOT is_completed WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Fungsi untuk menghapus tugas
    function deleteTask($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Fungsi untuk menghitung total tugas
    function getTotalTasks($pdo) {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM tasks");
        return $stmt->fetch()['total'];
    }

    // Fungsi untuk menghitung tugas yang selesai
    function getCompletedTasks($pdo) {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM tasks WHERE is_completed = 1");
        return $stmt->fetch()['total'];
    }
?>