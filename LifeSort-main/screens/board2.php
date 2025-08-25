<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include '../includes/header.php';

// Ensure starting wealth is set
if (!isset($_SESSION['starting_wealth'])) {
    $_SESSION['starting_wealth'] = $_SESSION['wealth'];
}
?>

<div class="board-container animate-slideIn">
    <div class="board-header">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1rem; border-radius: 15px; margin-bottom: 1rem; text-align: center;">
            <h3>🎯 You started with: $<?php echo number_format($_SESSION['starting_wealth']); ?></h3>
        </div>

        <h1 class="board-title">💼 Mid-Life Board (Ages 30-50)</h1>
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
        <h3 style="text-align: center; margin-bottom: 1rem;">🎯 Career Path</h3>
        <div class="board-path">
            <?php for ($i = 1; $i <= 20; $i++): ?>
                <div class="board-space <?php echo ($_SESSION['position'] == $i) ? 'current-position' : ''; ?>">
                    <div class="space-number"><?php echo $i; ?></div>
                    <div class="space-event">
                        <?php
                        $events = [
                            1 => "💼 Career", 2 => "📊 Meeting", 3 => "💰 Raise", 4 => "🏠 House", 5 => "👶 Baby",
                            6 => "🚗 Car Pay", 7 => "📈 Risky Invest", 8 => "� Medical", 9 => "💼 Promotion", 10 => "🌟 Success",
                            11 => "💰 Bonus", 12 => "🏖️ Vacation", 13 => "🎓 Tuition", 14 => "🤝 Network", 15 => "� DIVORCE!",
                            16 => "� Repairs", 17 => "💼 Leadership", 18 => "� Failed Biz", 19 => "🎯 Peak", 20 => "🚀 Mastery"
                        ];
                        echo $events[$i];
                        ?>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <!-- Dice Rolling Section -->
        <div style="text-align: center; margin: 1rem 0; padding: 1rem; background: rgba(255,255,255,0.8); border-radius: 10px;">
            <h4>🎲 Roll the Dice to Advance Your Career!</h4>
            <div style="font-size: 2rem; margin: 0.5rem 0;">🎲</div>
            <form method="post" action="roll.php" style="display: inline;">
                <input type="hidden" name="board" value="2">
                <button type="submit" class="btn btn-roll">🎲 Roll Dice & Move</button>
            </form>
        </div>
    </div>

    <!-- Current Space Event -->
    <?php
    $current_space = $_SESSION['position'];
    $space_events = [
        1 => ['name' => '💼 New Career', 'effect' => '+$3,000 salary boost'],
        2 => ['name' => '📊 Big Meeting', 'effect' => 'Roll again for bonus'],
        3 => ['name' => '💰 Salary Raise', 'effect' => '+$2,500'],
        4 => ['name' => '🏠 Buy House', 'effect' => '-$8,000 but asset gained'],
        5 => ['name' => '👶 Start Family', 'effect' => '-$3,000 but happiness boost'],
        6 => ['name' => '🚗 Car Upgrade', 'effect' => '-$1,500'],
        7 => ['name' => '📈 Investment', 'effect' => '50% chance: +$5,000 or -$2,000'],
        8 => ['name' => '🎯 Career Goals', 'effect' => '+$1,000'],
        9 => ['name' => '💼 Promotion', 'effect' => '+$4,000'],
        10 => ['name' => '🌟 Success Bonus', 'effect' => '+$3,500'],
        11 => ['name' => '💰 Year-end Bonus', 'effect' => '+$2,000'],
        12 => ['name' => '🏖️ Vacation', 'effect' => '-$1,000 but stress relief'],
        13 => ['name' => '📚 Learn Skills', 'effect' => '+$1,500 future earning'],
        14 => ['name' => '🤝 Networking', 'effect' => '+$2,000'],
        15 => ['name' => '� Great Ideas', 'effect' => '+$3,000'],
        16 => ['name' => '🏆 Industry Award', 'effect' => '+$2,500'],
        17 => ['name' => '💼 Leadership Role', 'effect' => '+$5,000'],
        18 => ['name' => '📈 Business Growth', 'effect' => '+$4,000'],
        19 => ['name' => '🎯 Peak Performance', 'effect' => '+$6,000'],
        20 => ['name' => '🚀 Career Mastery', 'effect' => '+$7,500']
    ];

    ?>
    <!-- Space Event Result -->
    <?php if (isset($_SESSION['space_event'])): ?>
    <div style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); border-radius: 15px; padding: 2rem; margin: 2rem 0; text-align: center;">
        <h3>📍 What Just Happened</h3>
        <p style="font-size: 1.3rem; margin: 1rem 0; font-weight: bold;"><?php echo $_SESSION['space_event']; ?></p>
    </div>
    <?php endif; ?>



    <!-- Navigation -->
    <div class="action-buttons">
        <a href="board1.php" class="btn btn-secondary">⬅️ Back to Early Life</a>
        <a href="index.php" class="btn btn-secondary">🏠 Main Menu</a>
        <?php if ($_SESSION['position'] >= 20): ?>
            <a href="board3.php" class="btn btn-primary">➡️ Next: Pre-Retirement</a>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
