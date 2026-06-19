<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT id, game_title, template_type, updated_at FROM games WHERE user_id = ? ORDER BY updated_at DESC");
    $stmt->execute([$user_id]);
    $games = $stmt->fetchAll();
} catch (\PDOException $e) {
    $error_msg = "Database query failed: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Games - CodePlay</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .logo {
      height: 40px;
      width: auto;
    }
    .main-content {
      flex: 1;
    }
    .footer {
      background-color: #212529;
      color: #fff;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="logo.png" alt="CodePlay Logo" class="logo me-2">
        CodePlay
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="CG.php">Create Game</a></li>
          <li class="nav-item"><a class="nav-link active" href="my_games.php">My Games</a></li>
          <li class="nav-item"><a class="nav-link" href="compiler.html">Try Code</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Content -->
  <div class="container main-content mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Your Saved Games</h2>
      <a href="CG.php" class="btn btn-success">+ Create New Game</a>
    </div>

    <?php if (isset($error_msg)): ?>
      <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($error_msg); ?>
      </div>
    <?php endif; ?>

    <div class="card shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover table-striped mb-0 align-middle">
            <thead class="table-dark">
              <tr>
                <th scope="col" class="ps-4">Game Title</th>
                <th scope="col">Template Type</th>
                <th scope="col">Last Updated</th>
                <th scope="col" class="text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($games)): ?>
                <tr>
                  <td colspan="4" class="text-center py-5 text-muted">
                    <p class="mb-2">You haven't saved any games yet!</p>
                    <a href="CG.php" class="btn btn-sm btn-outline-primary">Choose a template to start building</a>
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($games as $row): ?>
                  <tr>
                    <td class="ps-4 fw-semibold"><?php echo htmlspecialchars($row['game_title']); ?></td>
                    <td>
                      <span class="badge bg-secondary text-capitalize">
                        <?php echo htmlspecialchars(str_replace('_', ' ', $row['template_type'])); ?>
                      </span>
                    </td>
                    <td><?php echo htmlspecialchars($row['updated_at']); ?></td>
                    <td class="text-end pe-4">
                      <a href="editor.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary me-2">Edit Code</a>
                      <!-- We can have a run link that opens in a preview tab -->
                      <a href="editor.php?id=<?php echo $row['id']; ?>&run=true" class="btn btn-sm btn-success" target="_blank">Run</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <footer class="footer text-center py-3 mt-5">
    <div class="container">
      <p class="mb-0">© 2025 CodePlay. All Rights Reserved.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>