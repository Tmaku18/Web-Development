<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get final stats before updating account
$final_wealth = $_SESSION['wealth'] ?? 0;
$final_age = $_SESSION['age'] ?? 18;
$education = $_SESSION['education'] ?? 'none';
$board_completed = $_SESSION['board'] ?? 1;

// Update account statistics and add to leaderboard
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Update session stats
    $_SESSION['games_played'] = ($_SESSION['games_played'] ?? 0) + 1;
    $_SESSION['total_earnings'] = ($_SESSION['total_earnings'] ?? 0) + $final_wealth;

    // Update best score if this is better
    if ($final_wealth > ($_SESSION['best_score'] ?? 0)) {
        $_SESSION['best_score'] = $final_wealth;
    }

    // Save back to accounts storage
    if (isset($_SESSION['accounts'][$username])) {
        $_SESSION['accounts'][$username]['games_played'] = $_SESSION['games_played'];
        $_SESSION['accounts'][$username]['total_earnings'] = $_SESSION['total_earnings'];
        $_SESSION['accounts'][$username]['best_score'] = $_SESSION['best_score'];
    }

    // Add to leaderboard
    if (!isset($_SESSION['leaderboard'])) {
        $_SESSION['leaderboard'] = [];
    }

    $_SESSION['leaderboard'][] = [
        'name' => $_SESSION['username'],
        'wealth' => $final_wealth,
        'age' => $final_age,
        'education' => $education,
        'date' => date('m/d/Y')
    ];

    // Keep only top 10 scores
    usort($_SESSION['leaderboard'], function($a, $b) {
        return $b['wealth'] - $a['wealth'];
    });
    $_SESSION['leaderboard'] = array_slice($_SESSION['leaderboard'], 0, 10);
}

include '../includes/header.php';

// Determine success level
if ($final_wealth >= 50000) {
    $success_level = "🏆 Millionaire Lifestyle";
    $success_message = "Congratulations! You've achieved financial freedom and can retire in luxury!";
    $success_class = "win";
    $icon = "🏆";
} elseif ($final_wealth >= 25000) {
    $success_level = "🌟 Comfortable Retirement";
    $success_message = "Well done! You have enough for a comfortable retirement.";
    $success_class = "win";
    $icon = "🌟";
} elseif ($final_wealth >= 10000) {
    $success_level = "😊 Modest Success";
    $success_message = "You made it! A modest but secure retirement awaits.";
    $success_class = "win";
    $icon = "😊";
} else {
    $success_level = "😅 Barely Made It";
    $success_message = "You survived, but retirement will be tight. Every penny counts!";
    $success_class = "win";
    $icon = "😅";
}
?>

<div class="game-result <?php echo $success_class; ?> animate-slideIn">
    <div class="result-icon"><?php echo $icon; ?></div>
    <h1 class="result-title">🎉 Congratulations! 🎉</h1>
    <h2><?php echo $success_level; ?></h2>
    <p class="result-message"><?php echo $success_message; ?></p>

    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 2rem; margin: 2rem 0;">
        <h3>📊 Your Life Summary</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; text-align: left;">
            <div>
                <p><strong>💰 Final Wealth:</strong> $<?php echo number_format($final_wealth); ?></p>
                <p><strong>🎂 Retirement Age:</strong> <?php echo $final_age; ?></p>
            </div>
            <div>
                <p><strong>🎓 Education:</strong> <?php echo ucfirst($education); ?></p>
                <p><strong>🎯 Boards Completed:</strong> <?php echo $board_completed; ?>/3</p>
            </div>
        </div>
    </div>

    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 1.5rem; margin: 1rem 0;">
        <h3>🏆 Achievement Unlocked!</h3>
        <p>You've successfully navigated through life's challenges and reached retirement!</p>
        <?php if ($final_wealth >= 50000): ?>
            <p>🌟 <strong>Luxury Bonus:</strong> You can travel the world in style!</p>
        <?php elseif ($final_wealth >= 25000): ?>
            <p>🏠 <strong>Comfort Bonus:</strong> You own your house outright!</p>
        <?php elseif ($final_wealth >= 10000): ?>
            <p>🛡️ <strong>Security Bonus:</strong> You have a safety net for emergencies!</p>
        <?php else: ?>
            <p>💪 <strong>Survivor Bonus:</strong> You made it through all the challenges!</p>
        <?php endif; ?>
    </div>

    <div class="action-buttons">
        <a href="index.php" class="btn btn-primary">🎮 Play Again</a>
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
