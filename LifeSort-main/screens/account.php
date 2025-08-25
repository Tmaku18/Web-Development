<?php 
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get account stats
$username = $_SESSION['username'];
$account_created = $_SESSION['account_created'] ?? 'Unknown';
$games_played = $_SESSION['games_played'] ?? 0;
$best_score = $_SESSION['best_score'] ?? 0;
$total_earnings = $_SESSION['total_earnings'] ?? 0;

include '../includes/header.php'; 
?>

<div class="account-container">
    <div class="account-header">
        <h1>👤 My Account</h1>
        <p>Welcome back, <?php echo htmlspecialchars($username); ?>!</p>
    </div>
    
    <div class="account-grid">
        <div class="account-card">
            <div class="card-icon">👤</div>
            <h3>Profile Info</h3>
            <div class="stat-item">
                <span class="stat-label">Username:</span>
                <span class="stat-value"><?php echo htmlspecialchars($username); ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Account Created:</span>
                <span class="stat-value"><?php echo date('M j, Y', strtotime($account_created)); ?></span>
            </div>
        </div>
        
        <div class="account-card">
            <div class="card-icon">🎮</div>
            <h3>Game Statistics</h3>
            <div class="stat-item">
                <span class="stat-label">Games Played:</span>
                <span class="stat-value"><?php echo number_format($games_played); ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Best Score:</span>
                <span class="stat-value">$<?php echo number_format($best_score); ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Total Earnings:</span>
                <span class="stat-value">$<?php echo number_format($total_earnings); ?></span>
            </div>
        </div>
        
        <div class="account-card">
            <div class="card-icon">🏆</div>
            <h3>Achievements</h3>
            <div class="achievement-list">
                <?php if ($games_played >= 1): ?>
                    <div class="achievement earned">🎯 First Game Played</div>
                <?php else: ?>
                    <div class="achievement locked">🔒 Play your first game</div>
                <?php endif; ?>
                
                <?php if ($best_score >= 25000): ?>
                    <div class="achievement earned">💰 Wealth Builder ($25K+)</div>
                <?php else: ?>
                    <div class="achievement locked">🔒 Reach $25,000 wealth</div>
                <?php endif; ?>
                
                <?php if ($best_score >= 50000): ?>
                    <div class="achievement earned">🏆 Millionaire Status ($50K+)</div>
                <?php else: ?>
                    <div class="achievement locked">🔒 Reach $50,000 wealth</div>
                <?php endif; ?>
                
                <?php if ($games_played >= 5): ?>
                    <div class="achievement earned">🎲 Experienced Player (5+ games)</div>
                <?php else: ?>
                    <div class="achievement locked">🔒 Play 5 games</div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="account-card">
            <div class="card-icon">⚙️</div>
            <h3>Account Actions</h3>
            <div class="action-buttons">
                <a href="index.php" class="btn btn-primary">🎮 Play Game</a>
                <a href="leaderboard.php" class="btn btn-secondary">🏆 Leaderboard</a>
                <a href="logout.php" class="btn btn-danger">🚪 Logout</a>
            </div>
        </div>
    </div>
</div>

<style>
.account-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 2rem;
}

.account-header {
    text-align: center;
    margin-bottom: 3rem;
}

.account-header h1 {
    font-size: 2.5rem;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.account-header p {
    font-size: 1.2rem;
    color: #666;
}

.account-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.account-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
    text-align: center;
}

.card-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.account-card h3 {
    color: #2d3748;
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-label {
    font-weight: bold;
    color: #4a5568;
}

.stat-value {
    color: #2d3748;
    font-weight: bold;
}

.achievement-list {
    text-align: left;
}

.achievement {
    padding: 0.75rem;
    margin: 0.5rem 0;
    border-radius: 8px;
    font-weight: bold;
}

.achievement.earned {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    color: white;
}

.achievement.locked {
    background: #f7fafc;
    color: #a0aec0;
    border: 2px dashed #e2e8f0;
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.btn-danger {
    background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(245, 101, 101, 0.4);
}

.btn-danger:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(245, 101, 101, 0.6);
}

@media (max-width: 768px) {
    .account-container {
        margin: 1rem;
        padding: 1rem;
    }
    
    .account-grid {
        grid-template-columns: 1fr;
    }
    
    .account-header h1 {
        font-size: 2rem;
    }
}
</style>

<?php include '../includes/footer.php'; ?>
