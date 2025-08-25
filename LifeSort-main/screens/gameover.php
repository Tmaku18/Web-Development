<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get final stats before destroying session
$final_wealth = $_SESSION['wealth'] ?? 0;
$final_age = $_SESSION['age'] ?? 18;
$education = $_SESSION['education'] ?? 'none';
$board_reached = $_SESSION['board'] ?? 1;

include '../includes/header.php';
?>

<div class="game-result lose animate-slideIn">
    <div class="result-icon">💸</div>
    <h1 class="result-title">Game Over!</h1>
    <h2>💔 Bankruptcy Struck!</h2>
    <p class="result-message">You ran out of money and couldn't continue your journey through life. Sometimes the dice don't roll in our favor!</p>

    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 2rem; margin: 2rem 0;">
        <h3>📊 Your Journey Summary</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; text-align: left;">
            <div>
                <p><strong>💸 Final Wealth:</strong> $<?php echo number_format($final_wealth); ?></p>
                <p><strong>🎂 Age Reached:</strong> <?php echo $final_age; ?></p>
            </div>
            <div>
                <p><strong>🎓 Education:</strong> <?php echo ucfirst($education); ?></p>
                <p><strong>🎯 Board Reached:</strong> <?php echo $board_reached; ?>/3</p>
            </div>
        </div>
    </div>

    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 1.5rem; margin: 1rem 0;">
        <h3>💡 Life Lessons Learned</h3>
        <ul style="text-align: left; margin: 1rem 0;">
            <li>💰 Money management is crucial for long-term success</li>
            <li>🎲 Sometimes luck plays a role, but planning helps</li>
            <li>🎓 Education and smart choices can provide safety nets</li>
            <li>🔄 Every failure is a chance to learn and try again!</li>
        </ul>
    </div>



    <div class="action-buttons">
        <a href="index.php" class="btn btn-primary">🔄 Try Again</a>
        <a href="leaderboard.php" class="btn btn-secondary">🏆 View Leaderboard</a>
    </div>
</div>

<?php
// Clear game data but keep account info
unset($_SESSION['wealth']);
unset($_SESSION['age']);
unset($_SESSION['position']);
unset($_SESSION['board']);
unset($_SESSION['career_boost']);
unset($_SESSION['experience']);
unset($_SESSION['education']);
unset($_SESSION['starting_wealth']);
unset($_SESSION['last_roll']);
unset($_SESSION['last_event']);
unset($_SESSION['space_event']);

include '../includes/footer.php';
?>
