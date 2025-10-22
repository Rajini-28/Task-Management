<?php
// index.php (login)
require 'config.php';
if (is_logged_in()) {
  header('Location: dashboard.php');
  exit;
}

$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';

  if ($email && $password) {
    $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
      // success
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      header('Location: dashboard.php');
      exit;
    } else {
      $login_error = 'Invalid credentials';
    }
  } else {
    $login_error = 'Please enter email and password';
  }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login — Task Manager</title>
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body class="page-login">
  <main class="card center">
    <h1>Welcome back 👋</h1>
    <?php if ($login_error): ?>
      <div class="error"><?php echo htmlspecialchars($login_error); ?></div>
    <?php endif; ?>

    <form method="post" class="form">
      <label>Email <input type="email" name="email" required></label>
      <label>Password <input type="password" name="password" required></label>
      <button type="submit" class="btn">Log in</button>
    </form>

    <p>Don't have an account? <a href="signup.php">Sign up</a></p>
  </main>
  <script src="assets/script.js"></script>
</body>
</html>
