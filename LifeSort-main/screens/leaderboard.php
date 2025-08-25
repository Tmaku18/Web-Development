<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Initialize leaderboard in session if not exists
if (!isset($_SESSION['leaderboard'])) {
    $_SESSION['leaderboard'] = [
        [
            'name' => 'Alex_Champion',
            'wealth' => 47500,
            'age' => 65,
            'education' => 'college',
            'date' => '12/25/2024'
        ]
    ];
}

// Handle clear leaderboard
if (isset($_POST['clear_leaderboard'])) {
    if (isset($_POST['confirm_clear'])) {
        $_SESSION['leaderboard'] = [];
        header('Location: leaderboard.php');
        exit;
    } else {
        $show_confirm = true;
    }
}

// Sort leaderboard by wealth (highest first)
$leaderboard = $_SESSION['leaderboard'];
usort($leaderboard, function($a, $b) {
    return $b['wealth'] - $a['wealth'];
});

include '../includes/header.php';
?>

<div class="leaderboard animate-slideIn">
    <h1 style="text-align: center; color: #2d3748; margin-bottom: 2rem;">🏆 LifeRoll Leaderboard 🏆</h1>

    <div style="text-align: center; margin-bottom: 2rem;">
        <p>See how you stack up against other players who've completed their life journey!</p>
    </div>

    <div class="leaderboard-content">
        <?php if (empty($leaderboard)): ?>
            <div style="text-align: center; padding: 2rem;">
                <p>🎮 No scores yet! Play the game to see your results here!</p>
            </div>
        <?php else: ?>
            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th>🏆 Rank</th>
                        <th>👤 Player</th>
                        <th>💰 Wealth</th>
                        <th>🎂 Age</th>
                        <th>🎓 Education</th>
                        <th>📅 Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leaderboard as $index => $score): ?>
                        <tr>
                            <td>
                                <?php
                                if ($index === 0) echo '🥇';
                                elseif ($index === 1) echo '🥈';
                                elseif ($index === 2) echo '🥉';
                                else echo ($index + 1) . '.';
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($score['name']); ?></td>
                            <td>$<?php echo number_format($score['wealth']); ?></td>
                            <td><?php echo $score['age']; ?></td>
                            <td><?php echo ucfirst($score['education']); ?></td>
                            <td><?php echo $score['date']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <?php if (isset($show_confirm)): ?>
        <div style="background: #fed7d7; border: 2px solid #feb2b2; border-radius: 10px; padding: 1.5rem; margin: 2rem auto; max-width: 400px; text-align: center;">
            <h3 style="color: #c53030; margin-bottom: 1rem;">⚠️ Confirm Action</h3>
            <p style="color: #c53030; margin-bottom: 1.5rem;">Are you sure you want to clear all scores? This cannot be undone!</p>
            <form method="post" style="display: inline; margin-right: 1rem;">
                <button type="submit" name="clear_leaderboard" class="btn btn-danger">
                    <input type="hidden" name="confirm_clear" value="1">
                    ✅ Yes, Clear All
                </button>
            </form>
            <a href="leaderboard.php" class="btn btn-secondary">❌ Cancel</a>
        </div>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 2rem;">
        <a href="index.php" class="btn btn-primary">🎮 Play Game</a>
        <?php if (!isset($show_confirm)): ?>
            <form method="post" style="display: inline;">
                <button type="submit" name="clear_leaderboard" class="btn btn-secondary">
                    🗑️ Clear Scores
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
