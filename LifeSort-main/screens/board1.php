<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Initialize game if not started
if (!isset($_SESSION['wealth'])) {
    $_SESSION['wealth'] = rand(5000, 15000);
    $_SESSION['starting_wealth'] = $_SESSION['wealth'];
    $_SESSION['age'] = 18;
    $_SESSION['position'] = 1;
    $_SESSION['board'] = 1;
    $_SESSION['career_boost'] = 0;
    $_SESSION['experience'] = 0;
    $_SESSION['education'] = 'none';
}

include '../includes/header.php';
?>

<div class="board-container animate-slideIn">
    <div class="board-header">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1rem; border-radius: 15px; margin-bottom: 1rem; text-align: center;">
            <h3>🎯 You started with: $<?php echo number_format($_SESSION['starting_wealth']); ?></h3>
        </div>

        <h1 class="board-title">🌱 Early Life Board (Ages 18-30)</h1>
        <div class="player-stats">
            <div class="stat-item">💰 Current Wealth: $<?php echo number_format($_SESSION['wealth']); ?></div>
            <div class="stat-item">🎂 Age: <?php echo $_SESSION['age']; ?></div>
            <div class="stat-item">📍 Position: <?php echo $_SESSION['position']; ?>/20</div>
        </div>

        <?php if (isset($_SESSION['last_choice'])): ?>
            <div style="background: #e6fffa; border: 2px solid #38b2ac; border-radius: 10px; padding: 1rem; margin: 1rem 0; text-align: center;">
                <strong>Last Action:</strong> <?php echo $_SESSION['last_choice']; unset($_SESSION['last_choice']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['last_event'])): ?>
            <div style="background: #fff5f5; border: 2px solid #fc8181; border-radius: 10px; padding: 1rem; margin: 1rem 0; text-align: center;">
                <strong>Dice Event:</strong> <?php echo $_SESSION['last_event']; unset($_SESSION['last_event']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['last_roll'])): ?>
            <div style="background: #f0fff4; border: 2px solid #48bb78; border-radius: 10px; padding: 1rem; margin: 1rem 0; text-align: center;">
                <strong>🎲 Last Roll:</strong> You rolled a <?php echo $_SESSION['last_roll']; ?>! <?php unset($_SESSION['last_roll']); ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Game Board Path -->
    <div class="game-board">
        <h3 style="text-align: center; margin-bottom: 1rem;">🎯 Life Path</h3>
        <div class="board-path">
            <?php for ($i = 1; $i <= 20; $i++): ?>
                <div class="board-space <?php echo ($_SESSION['position'] == $i) ? 'current-position' : ''; ?>">
                    <div class="space-number"><?php echo $i; ?></div>
                    <div class="space-event">
                        <?php
                        $events = [
                            1 => "🏠 Start", 2 => "📚 Study", 3 => "💼 Part Job", 4 => "🎉 Party", 5 => "💰 Save",
                            6 => "🚗 Accident", 7 => "🏠 Apartment", 8 => "📱 Debt", 9 => "🎓 Graduate", 10 => "💕 Dating",
                            11 => "🌍 Study Abroad", 12 => "💼 Job Offer", 13 => "🏥 Medical", 14 => "🎨 Side Hustle", 15 => "💰 Bonus",
                            16 => "🏠 Moving", 17 => "📉 Bad Invest", 18 => "🚔 ARREST!", 19 => "🚀 Breakthrough", 20 => "🎊 Milestone"
                        ];
                        echo $events[$i];
                        ?>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <!-- Dice Rolling Section -->
        <div style="text-align: center; margin: 1rem 0; padding: 1rem; background: rgba(255,255,255,0.8); border-radius: 10px;">
            <h4>🎲 Roll the Dice to Move Forward!</h4>
            <div style="font-size: 2rem; margin: 0.5rem 0;">🎲</div>
            <form method="post" action="roll.php" style="display: inline;">
                <input type="hidden" name="board" value="1">
                <button type="submit" class="btn btn-roll">🎲 Roll Dice & Move</button>
            </form>
        </div>
    </div>

    <!-- Space Event Result -->
    <?php if (isset($_SESSION['space_event'])): ?>
    <div style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); border-radius: 15px; padding: 2rem; margin: 2rem 0; text-align: center;">
        <h3>📍 What Just Happened</h3>
        <p style="font-size: 1.3rem; margin: 1rem 0; font-weight: bold;"><?php echo $_SESSION['space_event']; ?></p>
    </div>
    <?php endif; ?>

    <!-- Navigation -->
    <div class="action-buttons">
        <a href="index.php" class="btn btn-secondary">🏠 Back to Menu</a>
        <?php if ($_SESSION['position'] >= 20): ?>
            <a href="board2.php" class="btn btn-primary">➡️ Next: Mid-Life Board</a>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
