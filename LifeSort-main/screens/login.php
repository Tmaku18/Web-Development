<?php 
session_start();

// If already logged in, redirect to game
if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

// Simple account storage in session
if (!isset($_SESSION['accounts'])) {
    $_SESSION['accounts'] = [];
}

// Handle login/register
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $action = $_POST['action'] ?? '';

    if (!empty($username) && !empty($password)) {
        if ($action === 'register') {
            // Check if username already exists
            if (isset($_SESSION['accounts'][$username])) {
                $error = "Username already exists! Please choose a different one.";
            } else {
                // Create new account
                $_SESSION['accounts'][$username] = [
                    'password' => $password,
                    'account_created' => date('Y-m-d H:i:s'),
                    'games_played' => 0,
                    'best_score' => 0,
                    'total_earnings' => 0
                ];

                // Log them in
                $_SESSION['username'] = $username;
                $_SESSION['account_created'] = $_SESSION['accounts'][$username]['account_created'];
                $_SESSION['games_played'] = 0;
                $_SESSION['best_score'] = 0;
                $_SESSION['total_earnings'] = 0;

                header('Location: index.php');
                exit;
            }
        } elseif ($action === 'login') {
            // Check login credentials
            if (isset($_SESSION['accounts'][$username]) && $_SESSION['accounts'][$username]['password'] === $password) {
                // Log them in
                $_SESSION['username'] = $username;
                $_SESSION['account_created'] = $_SESSION['accounts'][$username]['account_created'];
                $_SESSION['games_played'] = $_SESSION['accounts'][$username]['games_played'];
                $_SESSION['best_score'] = $_SESSION['accounts'][$username]['best_score'];
                $_SESSION['total_earnings'] = $_SESSION['accounts'][$username]['total_earnings'];

                header('Location: index.php');
                exit;
            } else {
                $error = "Invalid username or password!";
            }
        }
    } else {
        $error = "Please enter both username and password!";
    }
}

include '../includes/header.php'; 
?>

<div class="login-container">
    <div class="login-box">
        <h1>🎲 Welcome to LifeRoll!</h1>
        <p>Create an account or login to start your life journey</p>
        
        <?php if (isset($error)): ?>
            <div class="error-message">
                ⚠️ <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="post" class="login-form">
            <div class="input-group">
                <label for="username">👤 Username:</label>
                <input type="text" id="username" name="username" required
                       placeholder="Enter your username" maxlength="20">
            </div>

            <div class="input-group">
                <label for="password">🔒 Password:</label>
                <input type="password" id="password" name="password" required
                       placeholder="Enter your password" maxlength="50">
            </div>

            <div class="button-group">
                <button type="submit" name="action" value="login" class="btn btn-primary">
                    🔑 Login
                </button>
                <button type="submit" name="action" value="register" class="btn btn-secondary">
                    ✨ Create Account
                </button>
            </div>
        </form>
        
        <div class="info-box">
            <h3>🎯 About LifeRoll</h3>
            <ul>
                <li>🎲 Roll dice to move through life stages</li>
                <li>💰 Manage your wealth and make smart choices</li>
                <li>🏆 Reach retirement without going broke!</li>
                <li>⚠️ Watch out for bankruptcy events!</li>
            </ul>
        </div>
    </div>
</div>

<style>
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 80vh;
    padding: 2rem;
}

.login-box {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    padding: 3rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    backdrop-filter: blur(10px);
    max-width: 500px;
    width: 100%;
    text-align: center;
}

.login-box h1 {
    color: #2d3748;
    margin-bottom: 1rem;
    font-size: 2.5rem;
}

.login-box p {
    color: #666;
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

.error-message {
    background: #fed7d7;
    color: #c53030;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    border: 2px solid #feb2b2;
}

.login-form {
    margin-bottom: 2rem;
}

.input-group {
    margin-bottom: 1.5rem;
    text-align: left;
}

.input-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: bold;
    color: #2d3748;
}

.input-group input {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.input-group input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.button-group {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.info-box {
    background: #f7fafc;
    border: 2px solid #e2e8f0;
    border-radius: 15px;
    padding: 1.5rem;
    text-align: left;
}

.info-box h3 {
    color: #2d3748;
    margin-bottom: 1rem;
    text-align: center;
}

.info-box ul {
    list-style: none;
    padding: 0;
}

.info-box li {
    padding: 0.5rem 0;
    color: #4a5568;
}

@media (max-width: 768px) {
    .login-box {
        padding: 2rem;
        margin: 1rem;
    }
    
    .button-group {
        flex-direction: column;
    }
    
    .login-box h1 {
        font-size: 2rem;
    }
}
</style>

<?php include '../includes/footer.php'; ?>
