<?php
// editor.php - Professional code editor with template support and database saving
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$game_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$game_title = '';
$template_type = isset($_GET['template_type']) ? trim($_GET['template_type']) : 'custom';
$code = '';

// Load existing game from DB
if ($game_id) {
    try {
        $stmt = $pdo->prepare("SELECT game_title, template_type, code FROM games WHERE id = ? AND user_id = ?");
        $stmt->execute([$game_id, $_SESSION['user_id']]);
        $game = $stmt->fetch();
        if ($game) {
            $game_title = $game['game_title'];
            $template_type = $game['template_type'];
            $code = $game['code'];
        } else {
            $game_id = null; // Reset if game not found
        }
    } catch (\PDOException $e) {
        $error_msg = "Database error: " . $e->getMessage();
    }
}

// Prepopulate templates if new game
if (!$game_id && empty($code) && !empty($template_type)) {
    if ($template_type === 'maze_runner') {
        $game_title = 'My Maze Runner';
        $code = '<!DOCTYPE html>
<html>
<head>
  <style>
    canvas { background: #111; display: block; margin: 0 auto; border: 2px solid #00d2ff; }
    body { background: #222; color: #fff; text-align: center; font-family: sans-serif; margin-top: 20px; }
  </style>
</head>
<body>
  <h2>Maze Runner - Use Arrow Keys</h2>
  <canvas id="gameCanvas" width="400" height="400"></canvas>
  <script>
    const canvas = document.getElementById("gameCanvas");
    const ctx = canvas.getContext("2d");
    const grid = 20;
    
    // 20x20 Maze layout: 1 = wall, 0 = path, 3 = goal
    const maze = [
      [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1],
      [1,0,0,0,1,0,0,0,0,0,1,0,0,0,0,0,0,0,0,1],
      [1,0,1,0,1,0,1,1,1,0,1,0,1,1,1,1,1,1,0,1],
      [1,0,1,0,0,0,1,0,0,0,1,0,0,0,0,0,1,0,0,1],
      [1,0,1,1,1,1,1,0,1,1,1,1,1,1,1,0,1,0,1,1],
      [1,0,0,0,0,0,1,0,0,0,0,0,0,0,1,0,1,0,0,1],
      [1,1,1,1,1,0,1,1,1,1,1,1,1,0,1,0,1,1,0,1],
      [1,0,0,0,1,0,0,0,0,0,0,0,1,0,1,0,0,0,0,1],
      [1,0,1,0,1,1,1,1,1,1,1,0,1,0,1,1,1,1,1,1],
      [1,0,1,0,0,0,0,0,0,0,1,0,1,0,0,0,0,0,0,1],
      [1,0,1,1,1,1,1,1,1,0,1,0,1,1,1,1,1,1,0,1],
      [1,0,0,0,0,0,0,0,1,0,1,0,0,0,0,0,1,0,0,1],
      [1,1,1,1,1,1,1,0,1,0,1,1,1,1,1,0,1,0,1,1],
      [1,0,0,0,0,0,1,0,1,0,0,0,0,0,1,0,1,0,0,1],
      [1,0,1,1,1,0,1,0,1,1,1,1,1,0,1,0,1,1,0,1],
      [1,0,1,0,0,0,1,0,0,0,0,0,1,0,1,0,0,1,0,1],
      [1,0,1,0,1,1,1,1,1,1,1,0,1,0,1,1,0,1,0,1],
      [1,0,1,0,1,0,0,0,0,0,1,0,1,0,1,0,0,1,0,1],
      [1,0,0,0,1,0,1,1,1,0,0,0,1,0,0,0,1,1,0,3],
      [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1]
    ];
    
    let player = { x: 1, y: 1 };
    
    function draw() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      for(let r=0; r<maze.length; r++) {
        for(let c=0; c<maze[r].length; c++) {
          if(maze[r][c] === 1) {
            ctx.fillStyle = "#444";
            ctx.fillRect(c*grid, r*grid, grid, grid);
          } else if(maze[r][c] === 3) {
            ctx.fillStyle = "#00ffcc";
            ctx.fillRect(c*grid, r*grid, grid, grid);
          }
        }
      }
      ctx.fillStyle = "#ff007f";
      ctx.fillRect(player.x*grid, player.y*grid, grid, grid);
    }
    
    window.addEventListener("keydown", e => {
      let nx = player.x;
      let ny = player.y;
      if(e.key === "ArrowUp") ny--;
      else if(e.key === "ArrowDown") ny++;
      else if(e.key === "ArrowLeft") nx--;
      else if(e.key === "ArrowRight") nx++;
      
      if(maze[ny] && maze[ny][nx] !== 1) {
        player.x = nx;
        player.y = ny;
        if(maze[ny][nx] === 3) {
          alert("You Win!");
          player = { x: 1, y: 1 };
        }
      }
      draw();
    });
    draw();
  </script>
</body>
</html>';
    } else if ($template_type === 'jump_adventure') {
        $game_title = 'My Jump Adventure';
        $code = '<!DOCTYPE html>
<html>
<head>
  <style>
    canvas { background: #87CEEB; display: block; margin: 0 auto; border: 2px solid #333; }
    body { background: #222; color: #fff; text-align: center; font-family: sans-serif; margin-top: 20px; }
  </style>
</head>
<body>
  <h2>Jump Adventure - Press Space to Jump</h2>
  <canvas id="gameCanvas" width="480" height="320"></canvas>
  <script>
    const canvas = document.getElementById("gameCanvas");
    const ctx = canvas.getContext("2d");
    
    let player = { x: 50, y: 200, width: 20, height: 30, dy: 0, gravity: 0.6, jumpPower: -10, grounded: false };
    let obstacles = [];
    let score = 0;
    let gameOver = false;
    
    function spawnObstacle() {
      if(Math.random() < 0.015) {
        obstacles.push({ x: canvas.width, y: 220, width: 15, height: 30 });
      }
    }
    
    function update() {
      if(gameOver) return;
      
      player.dy += player.gravity;
      player.y += player.dy;
      
      if(player.y >= 220) {
        player.y = 220;
        player.dy = 0;
        player.grounded = true;
      }
      
      obstacles.forEach((obs, index) => {
        obs.x -= 4;
        if(obs.x + obs.width < 0) {
          obstacles.splice(index, 1);
          score++;
        }
        
        // AABB Collision check
        if(player.x < obs.x + obs.width &&
           player.x + player.width > obs.x &&
           player.y < obs.y + obs.height &&
           player.y + player.height > obs.y) {
          gameOver = true;
        }
      });
      
      spawnObstacle();
    }
    
    function draw() {
      ctx.clearRect(0,0,canvas.width,canvas.height);
      
      // Ground
      ctx.fillStyle = "#8B4513";
      ctx.fillRect(0, 250, canvas.width, canvas.height - 250);
      ctx.fillStyle = "#228B22";
      ctx.fillRect(0, 250, canvas.width, 10);
      
      // Player
      ctx.fillStyle = "#FF4500";
      ctx.fillRect(player.x, player.y, player.width, player.height);
      
      // Obstacles
      ctx.fillStyle = "#000000";
      obstacles.forEach(obs => {
        ctx.fillRect(obs.x, obs.y, obs.width, obs.height);
      });
      
      // Score
      ctx.fillStyle = "#fff";
      ctx.font = "20px Arial";
      ctx.fillText("Score: " + score, 10, 30);
      
      if(gameOver) {
        ctx.fillStyle = "rgba(0,0,0,0.5)";
        ctx.fillRect(0,0,canvas.width,canvas.height);
        ctx.fillStyle = "#fff";
        ctx.fillText("Game Over! Press Space to restart", 80, 160);
      }
    }
    
    function loop() {
      update();
      draw();
      requestAnimationFrame(loop);
    }
    
    window.addEventListener("keydown", e => {
      if(e.code === "Space") {
        if(gameOver) {
          gameOver = false;
          obstacles = [];
          score = 0;
          player.y = 200;
        } else if(player.grounded) {
          player.dy = player.jumpPower;
          player.grounded = false;
        }
      }
    });
    
    loop();
  </script>
</body>
</html>';
    } else if ($template_type === 'catch_the_fruit') {
        $game_title = 'My Catch the Fruit';
        $code = '<!DOCTYPE html>
<html>
<head>
  <style>
    canvas { background: #333; display: block; margin: 0 auto; border: 2px solid #fff; }
    body { background: #222; color: #fff; text-align: center; font-family: sans-serif; margin-top: 20px; }
  </style>
</head>
<body>
  <h2>Catch the Fruit - Use Left/Right Arrow Keys</h2>
  <canvas id="gameCanvas" width="400" height="400"></canvas>
  <script>
    const canvas = document.getElementById("gameCanvas");
    const ctx = canvas.getContext("2d");
    
    let basket = { x: 160, y: 360, width: 80, height: 20, speed: 8 };
    let fruit = { x: Math.random() * 380 + 10, y: 0, r: 10, speed: 4 };
    let score = 0;
    let keys = {};
    
    function update() {
      if(keys["ArrowLeft"] && basket.x > 0) basket.x -= basket.speed;
      if(keys["ArrowRight"] && basket.x + basket.width < canvas.width) basket.x += basket.speed;
      
      fruit.y += fruit.speed;
      
      if(fruit.y + fruit.r >= basket.y && 
         fruit.x >= basket.x && 
         fruit.x <= basket.x + basket.width) {
        score++;
        fruit.y = 0;
        fruit.x = Math.random() * 380 + 10;
        fruit.speed += 0.2;
      } else if(fruit.y > canvas.height) {
        score = 0;
        fruit.y = 0;
        fruit.x = Math.random() * 380 + 10;
        fruit.speed = 4;
      }
    }
    
    function draw() {
      ctx.clearRect(0,0,canvas.width,canvas.height);
      
      // Basket
      ctx.fillStyle = "#00ff00";
      ctx.fillRect(basket.x, basket.y, basket.width, basket.height);
      
      // Fruit
      ctx.fillStyle = "#ff0000";
      ctx.beginPath();
      ctx.arc(fruit.x, fruit.y, fruit.r, 0, Math.PI*2);
      ctx.fill();
      
      // Score
      ctx.fillStyle = "#fff";
      ctx.font = "20px Arial";
      ctx.fillText("Score: " + score, 10, 30);
    }
    
    function loop() {
      update();
      draw();
      requestAnimationFrame(loop);
    }
    
    window.addEventListener("keydown", e => keys[e.key] = true);
    window.addEventListener("keyup", e => keys[e.key] = false);
    
    loop();
  </script>
</body>
</html>';
    } else {
        $game_title = 'My New Game';
        $code = '<!DOCTYPE html>
<html>
<head>
  <style>
    body { background: #222; color: #fff; text-align: center; font-family: sans-serif; margin-top: 50px; }
  </style>
</head>
<body>
  <h1>Welcome to Your New Game!</h1>
  <p>Start editing the HTML/JavaScript code in the editor to build your custom game.</p>
</body>
</html>';
    }
}

// Full screen run mode check
if (isset($_GET['run']) && $_GET['run'] === 'true') {
    if (!empty($code)) {
        echo $code;
    } else {
        echo "<h1>No code found for this game.</h1>";
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Universal Code Editor - CodePlay</title>
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
    .editor-container {
      flex: 1;
    }
    textarea {
      font-family: 'Courier New', Courier, monospace;
      font-size: 14px;
      resize: vertical;
      background-color: #1e1e1e;
      color: #d4d4d4;
    }
    textarea:focus {
      background-color: #1e1e1e;
      color: #d4d4d4;
    }
    iframe {
      width: 100%;
      height: 500px;
      border: 1px solid #ccc;
      border-radius: 4px;
      background-color: #fff;
    }
    pre {
      background: #111;
      color: #0f0;
      padding: 1rem;
      white-space: pre-wrap;
      display: none;
      min-height: 200px;
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
          <li class="nav-item"><a class="nav-link active" href="CG.php">Create Game</a></li>
          <li class="nav-item"><a class="nav-link" href="my_games.php">My Games</a></li>
          <li class="nav-item"><a class="nav-link" href="compiler.html">Try Code</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Editor Section -->
  <div class="container-fluid editor-container my-4 px-4">
    <div class="row align-items-center mb-3">
      <div class="col-md-4">
        <div class="input-group">
          <span class="input-group-text">Game Title</span>
          <input type="text" id="gameTitle" class="form-control" placeholder="Enter game title..." value="<?php echo htmlspecialchars($game_title); ?>">
        </div>
      </div>
      <div class="col-md-3">
        <div class="input-group">
          <span class="input-group-text">Language</span>
          <select id="languageSelect" class="form-select">
            <option value="63" selected>JavaScript / HTML (Live Preview)</option>
            <option value="71">Python</option>
            <option value="54">C++</option>
            <option value="62">Java</option>
          </select>
        </div>
      </div>
      <div class="col-md-5 text-end">
        <button id="saveBtn" class="btn btn-primary me-2">Save Game</button>
        <?php if ($game_id): ?>
          <a href="editor.php?id=<?php echo $game_id; ?>&run=true" target="_blank" class="btn btn-success">Run Fullscreen</a>
        <?php endif; ?>
      </div>
    </div>

    <?php if (isset($error_msg)): ?>
      <div class="alert alert-danger mb-3"><?php echo htmlspecialchars($error_msg); ?></div>
    <?php endif; ?>

    <div class="row">
      <!-- Code Input -->
      <div class="col-lg-6 mb-3">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Source Code Editor</span>
            <span class="badge bg-secondary">Auto-runs Live JS</span>
          </div>
          <div class="card-body p-0">
            <textarea id="code" class="form-control border-0 rounded-0" rows="22" placeholder="Write your HTML/JavaScript or selected language code here..."><?php echo htmlspecialchars($code); ?></textarea>
          </div>
        </div>
      </div>

      <!-- Preview Output -->
      <div class="col-lg-6 mb-3">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-dark text-white">
            <span class="fw-semibold">Preview / Execution Output</span>
          </div>
          <div class="card-body p-2 bg-light">
            <iframe id="js-preview"></iframe>
            <pre id="compiled-output">Waiting for execution output...</pre>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="footer text-center py-3">
    <div class="container">
      <p class="mb-0">© 2025 CodePlay. All Rights Reserved.</p>
    </div>
  </footer>

  <script>
    const codeBox = document.getElementById("code");
    const langSelect = document.getElementById("languageSelect");
    const jsPreview = document.getElementById("js-preview");
    const outputBox = document.getElementById("compiled-output");
    const saveBtn = document.getElementById("saveBtn");
    const gameTitleInput = document.getElementById("gameTitle");

    let currentGameId = <?php echo $game_id ? $game_id : 'null'; ?>;
    let currentTemplateType = '<?php echo $template_type; ?>';

    function updateJSPreview(code) {
      const doc = jsPreview.contentDocument || jsPreview.contentWindow.document;
      doc.open();
      doc.write(code);
      doc.close();
    }

    async function compileCode(code, langId) {
      outputBox.textContent = "Compiling and running code on Judge0...";
      try {
        const res = await fetch("run_game.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ source_code: code, language_id: langId })
        });
        const result = await res.json();
        outputBox.textContent =
          result.stdout || result.stderr || result.compile_output || result.message || "Execution completed. No output.";
      } catch (err) {
        outputBox.textContent = "Execution failed: " + err.message;
      }
    }

    async function renderPreview() {
      const code = codeBox.value;
      const langId = langSelect.value;

      if (langId === "63") {
        jsPreview.style.display = "block";
        outputBox.style.display = "none";
        updateJSPreview(code);
      } else {
        jsPreview.style.display = "none";
        outputBox.style.display = "block";
        await compileCode(code, langId);
      }
    }

    // Save Game logic
    saveBtn.addEventListener("click", async () => {
      const title = gameTitleInput.value.trim();
      if (!title) {
        alert("Please enter a game title before saving.");
        return;
      }

      saveBtn.disabled = true;
      saveBtn.textContent = "Saving...";

      try {
        const response = await fetch("save_game.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            game_id: currentGameId,
            game_title: title,
            template_type: currentTemplateType,
            code: codeBox.value
          })
        });
        const result = await response.json();
        if (result.status === "success") {
          alert(result.message);
          currentGameId = result.game_id;
          // Refresh URL to include new ID if it was a new game
          if (!window.location.search.includes("id=")) {
            window.location.href = `editor.php?id=${result.game_id}`;
          }
        } else {
          alert("Error: " + result.message);
        }
      } catch (err) {
        alert("Failed to save: " + err.message);
      } finally {
        saveBtn.disabled = false;
        saveBtn.textContent = "Save Game";
      }
    });

    // Run automatically on load and debounce input for JS preview
    let debounceTimer;
    codeBox.addEventListener("input", () => {
      if (langSelect.value === "63") {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(renderPreview, 300);
      }
    });

    langSelect.addEventListener("change", renderPreview);
    window.addEventListener("load", renderPreview);
  </script>
</body>
</html>
