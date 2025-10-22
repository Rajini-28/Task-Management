<?php
require 'config.php';
require_login();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// fetch basic stats (optional)
$stmt = $pdo->prepare('SELECT COUNT(*) as total, SUM(progress)/COUNT(*) as avg_progress FROM tasks WHERE user_id = ?');
$stmt->execute([$user_id]);
$stats = $stmt->fetch();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dashboard — TaskApp</title>
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body class="page-dashboard">
  <header class="topbar">
    <h2>Hi, <?php echo htmlspecialchars($username); ?> 🎉</h2>
    <nav>
      <a href="logout.php" class="btn small">Logout</a>
    </nav>
  </header>

  <main class="container">
    <section class="panel">
      <div class="panel-head">
        <h3>Your tasks</h3>
        <button id="btn-new" class="btn">+ New Task</button>
      </div>
      <div id="tasks"></div>
    </section>

    <aside class="panel side">
      <h4>Quick stats</h4>
      <p>Total tasks: <?php echo (int)($stats['total'] ?? 0); ?></p>
      <p>Average progress: <?php echo round($stats['avg_progress'] ?? 0); ?>%</p>
    </aside>
  </main>

  <!-- Add / Edit modal (simple) -->
  <div id="modal" class="modal hidden">
    <div class="modal-inner">
      <button id="modal-close" class="modal-close">✕</button>
      <h3 id="modal-title">New Task</h3>
      <form id="task-form">
        <input type="hidden" name="id" id="task-id">
        <label>Title <input name="title" id="title" required></label>
        <label>Description <textarea name="description" id="description"></textarea></label>
        <label>Priority
          <select name="priority" id="priority">
            <option>Low</option>
            <option selected>Medium</option>
            <option>High</option>
          </select>
        </label>
        <label>Deadline <input type="date" name="deadline" id="deadline"></label>
        <label>Progress <input type="number" name="progress" id="progress" min="0" max="100" value="0"></label>
        <label>Status
          <select name="status" id="status">
            <option value="pending">pending</option>
            <option value="in-progress">in-progress</option>
            <option value="done">done</option>
          </select>
        </label>
        <button type="submit" class="btn">Save</button>
      </form>
    </div>
  </div>

  <script>
    const USER_ID = <?php echo json_encode($user_id); ?>;
  </script>
  <script src="assets/script.js"></script>
</body>
</html>
