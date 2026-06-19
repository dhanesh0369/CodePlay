# CodePlay - Interactive Game Builder & Universal Compiler

CodePlay is a web-based playground for coding, playing, and sharing games. It features pre-configured HTML5/JavaScript game templates (Maze Runner, Jump Adventure, Catch the Fruit) that users can edit in real-time, compile, run full-screen, and save directly to a MySQL database. It also integrates with the Judge0 API to compile and execute code across multiple programming languages (C++, Java, Python, JavaScript).

---

## 🚀 Features

- **User Authentication**: Secure signup and login with password hashing.
- **Interactive Game Templates**: Start building with working canvas-based JS games:
  - 🎮 **Maze Runner**: A retro 2D grid maze crawler.
  - 🏃‍♂️ **Jump Adventure**: An endless side-scroller jumper.
  - 🍎 **Catch the Fruit**: A fast-paced reflex-catching game.
- **Universal Code Compiler**: Compile C++, Java, Python, and JavaScript on the fly powered by Judge0 CE.
- **Live Preview & Editor**: Split-screen live updates for HTML5/JS games with fullscreen run modes.
- **Dashboard & Collection Management**: Save your games to a custom portfolio, load them into the editor, or run them full-screen.

---

## 🛠️ Technology Stack

- **Frontend**: HTML5, Vanilla JavaScript (Canvas API), Bootstrap 5 (CSS framework).
- **Backend**: PHP (Sessions, dynamic routing, curl API requests).
- **Database**: MySQL via PHP Data Objects (PDO) for secure prepared statements.
- **API**: Judge0 CE API via RapidAPI for universal code compilation.

---

## 📂 Project Structure

```text
CodePlay/
├── .gitignore              # Files ignored by Git (e.g., config, large videos)
├── README.md               # Project documentation (this file)
├── CG.php                  # Interactive template chooser page
├── config.php              # Database connections using PDO & environment vars
├── database_setup.sql      # SQL schema to setup tables (users, games)
├── editor.php              # Split-screen editor, pre-loads templates & loads saved games
├── index.php               # Main dashboard with user links
├── login.html              # HTML login page
├── login.php               # Backend PHP script for login verification
├── logout.php              # Destroys session and logs out user
├── my_games.php            # Lists user's saved games with Edit/Run options
├── run_game.php            # Interfaces with Judge0 API for code execution
├── save_game.php           # Handles AJAX saving/updating of games in MySQL
├── signup.html             # HTML user registration page
├── signup.php              # Backend PHP script to create a user account
├── logo.png                # App Logo
├── Catch_the_Fruit.jpg     # Catch the Fruit card cover
├── Jump_Adventure.jpg      # Jump Adventure card cover
├── Maze_Runner.jpg         # Maze Runner card cover
├── nature.mp4              # Background video
└── cyberpunk-2077-...mp4   # Cyberpunk-themed background video for login/signup
```

---

## ⚙️ Local Setup Instructions

Follow these steps to run CodePlay on your local machine using an environment like **XAMPP** or **MAMP** on macOS:

### 1. Prerequisites
- Install **XAMPP** or **MAMP** (make sure PHP 7.4+ and MySQL are enabled).
- Ensure Git is installed.

### 2. Copy files to server root
Copy the project folder into your local web server document root directory:
- **XAMPP (macOS)**: `/Applications/XAMPP/htdocs/CodePlay`
- **MAMP (macOS)**: `/Applications/MAMP/htdocs/CodePlay`

### 3. Initialize the Database
1. Open your web browser and go to `http://localhost/phpmyadmin`.
2. Create a new database named **`codeplay`**.
3. Click on the `Import` tab, choose the `database_setup.sql` file from the project directory, and click **Go**.

### 4. Configure Database Credentials
By default, `config.php` will connect to `localhost`, database `codeplay`, user `root`, and no password (default for local setups). If your setup has different credentials, you can set the following environment variables in your server configuration (e.g., in `.htaccess` or Apache config), or edit the fallbacks directly in `config.php`:
- `DB_HOST` (e.g., `127.0.0.1`)
- `DB_NAME` (e.g., `codeplay`)
- `DB_USER` (e.g., `root`)
- `DB_PASS` (e.g., `yourpassword`)

### 5. Run the Application
Open your browser and navigate to:
`http://localhost/CodePlay/login.html`

Register an account, log in, select a template, and start building!
