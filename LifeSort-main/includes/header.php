<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeRoll - Board Game</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/boards.css">
    <link rel="stylesheet" href="../assets/css/animations.css">
</head>
<body>
    <nav class="game-nav">
        <div class="nav-brand">🎲 LifeRoll</div>
        <div class="nav-center">
            <a href="index.php">🏠 Home</a>
            <a href="leaderboard.php">🏆 Leaderboard</a>
        </div>
        <div class="nav-account">
            <?php if (isset($_SESSION['username'])): ?>
                <div class="account-dropdown">
                    <div class="account-btn">
                        👤 <?php echo htmlspecialchars($_SESSION['username']); ?> ▼
                    </div>
                    <div class="dropdown-content">
                        <a href="account.php">👤 My Account</a>
                        <a href="logout.php">🚪 Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php" class="login-link">🔑 Login</a>
            <?php endif; ?>
        </div>
    </nav>
