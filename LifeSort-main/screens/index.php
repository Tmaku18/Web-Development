<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include '../includes/header.php';
?>

<div class="main-menu">
  <h1>🎲 LifeRoll: Wealth & Career Odyssey 🎲</h1>
  <div class="game-logo">
    <div class="dice-icon">🎲</div>
    <div class="money-icon">💰</div>
  </div>

  <div class="rules-box">
    <h2>📋 Game Rules:</h2>
    <ul>
      <li>🎯 Start with random wealth ($5,000 - $15,000)</li>
      <li>🎲 Roll dice to move through life stages</li>
      <li>💼 Make career and life choices</li>
      <li>🏆 Reach retirement with wealth intact!</li>
      <li>💀 Go broke = Game Over!</li>
    </ul>
  </div>

  <div class="menu-buttons">
    <a href="leaderboard.php" class="btn btn-secondary">🏆 Leaderboard</a>
    <a href="board1.php" class="btn btn-primary">🚀 Start New Game</a>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
