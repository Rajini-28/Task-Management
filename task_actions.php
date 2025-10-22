<?php
// task_actions.php
require 'config.php';
header('Content-Type: application/json');
if (!is_logged_in()) {
  echo json_encode(['success' => false, 'error' => 'Not authenticated']);
  exit;
}
$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
  if ($action === 'list') {
    $stmt = $pdo->prepare('SELECT * FROM tasks WHERE user_id = ? ORDER BY priority DESC, deadline IS NULL, deadline ASC, created_at DESC');
    $stmt->execute([$user_id]);
    $tasks = $stmt->fetchAll();
    echo json_encode(['success' => true, 'tasks' => $tasks]);
    exit;
  }

  if ($action === 'create') {
    $title = trim($_POST['title'] ?? '');
    if (!$title) throw new Exception('Title required');
    $stmt = $pdo->prepare('INSERT INTO tasks (user_id, title, description, priority, deadline, progress, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$user_id, $title, $_POST['description'] ?? null, $_POST['priority'] ?? 'Medium', $_POST['deadline'] ?: null, (int)($_POST['progress'] ?? 0), $_POST['status'] ?? 'pending']);
    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    exit;
  }

  if ($action === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    if (!$id) throw new Exception('Invalid task id');
    // ensure ownership
    $stmt = $pdo->prepare('SELECT user_id FROM tasks WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $t = $stmt->fetch();
    if (!$t || $t['user_id'] != $user_id) throw new Exception('Not allowed');

    $stmt = $pdo->prepare('UPDATE tasks SET title = ?, description = ?, priority = ?, deadline = ?, progress = ?, status = ? WHERE id = ?');
    $stmt->execute([$_POST['title'] ?? '', $_POST['description'] ?? null, $_POST['priority'] ?? 'Medium', $_POST['deadline'] ?: null, (int)($_POST['progress'] ?? 0), $_POST['status'] ?? 'pending', $id]);
    echo json_encode(['success' => true]);
    exit;
  }

  if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if (!$id) throw new Exception('Invalid task id');
    $stmt = $pdo->prepare('DELETE FROM tasks WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $user_id]);
    echo json_encode(['success' => true]);
    exit;
  }

  echo json_encode(['success' => false, 'error' => 'Unknown action']);
} catch (Exception $e) {
  echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
