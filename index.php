<?php
// CodePlay Dashboard - Version 1.0.0
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - CodePlay</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="logo.png" alt="CodePlay Logo" class="logo me-2" style="height: 40px; width: auto;">
        CodePlay
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="index.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="CG.php">Create Game</a></li>
          <li class="nav-item"><a class="nav-link" href="my_games.php">My Games</a></li>
          <li class="nav-item"><a class="nav-link" href="compiler.html">Try Code</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card shadow-sm h-100">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title fw-bold">Create Game</h5>
            <p class="card-text text-muted flex-grow-1">Build your own game using our pre-built canvas and JavaScript game templates.</p>
            <a href="CG.php" class="btn btn-primary mt-auto">Open Editor</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow-sm h-100">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title fw-bold">My Games</h5>
            <p class="card-text text-muted flex-grow-1">Manage, edit, or run your saved game codes from your collection.</p>
            <a href="my_games.php" class="btn btn-primary mt-auto">View Games</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow-sm h-100">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title fw-bold">Try Code</h5>
            <p class="card-text text-muted flex-grow-1">Write and run codes in multiple languages including Python, C++, and Java instantly.</p>
            <a href="compiler.html" class="btn btn-success mt-auto">Try Now</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
