<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Clear previous space event
unset($_SESSION['space_event']);

// Roll dice (1-6)
$roll = rand(1, 6);
$_SESSION['last_roll'] = $roll;

// Move player
$_SESSION['position'] += $roll;
$_SESSION['age'] += 1; // Age 1 year per move

// Get current board
$current_board = $_SESSION['board'] ?? 1;

// Define space events for each board
$board_events = [
    1 => [ // Early Life Board
        1 => ['wealth' => 0, 'message' => '🏠 Starting your journey!'],
        2 => ['wealth' => 300, 'message' => '📚 Studied hard! +$300'],
        3 => ['wealth' => 800, 'message' => '💼 Part-time job! +$800'],
        4 => ['wealth' => -1200, 'message' => '🎉 Party too hard! -$1,200'],
        5 => ['wealth' => 500, 'message' => '💰 Saved money! +$500'],
        6 => ['wealth' => -2500, 'message' => '🚗 Car accident & repairs! -$2,500'],
        7 => ['wealth' => -3000, 'message' => '🏠 Expensive apartment deposit! -$3,000'],
        8 => ['wealth' => -1500, 'message' => '📱 Credit card debt! -$1,500'],
        9 => ['wealth' => 1500, 'message' => '🎓 Graduated! +$1,500 bonus'],
        10 => ['wealth' => -2000, 'message' => '💕 Expensive relationship! -$2,000'],
        11 => ['wealth' => -3500, 'message' => '🌍 Study abroad debt! -$3,500'],
        12 => ['wealth' => 2000, 'message' => '💼 Great job offer! +$2,000'],
        13 => ['wealth' => -800, 'message' => '🏥 Medical emergency! -$800'],
        14 => ['wealth' => 400, 'message' => '🎨 Side hustle income! +$400'],
        15 => ['wealth' => 1000, 'message' => '💰 Performance bonus! +$1,000'],
        16 => ['wealth' => -2500, 'message' => '🏠 Moving & security deposits! -$2,500'],
        17 => ['wealth' => -1500, 'message' => '📉 Bad investment! -$1,500'],
        18 => ['wealth' => 'bankruptcy', 'message' => '🚔 ARRESTED! Legal fees bankrupt you!'],
        19 => ['wealth' => 1800, 'message' => '🚀 Career breakthrough! +$1,800'],
        20 => ['wealth' => 2000, 'message' => '🎊 Early life milestone! +$2,000']
    ],
    2 => [ // Mid-Life Board
        1 => ['wealth' => 2000, 'message' => '💼 New career boost! +$2,000'],
        2 => ['wealth' => 800, 'message' => '📊 Successful meeting! +$800'],
        3 => ['wealth' => 1500, 'message' => '💰 Salary raise! +$1,500'],
        4 => ['wealth' => -12000, 'message' => '🏠 House down payment! -$12,000'],
        5 => ['wealth' => -5000, 'message' => '👶 Baby expenses! -$5,000'],
        6 => ['wealth' => -3000, 'message' => '🚗 Family car payment! -$3,000'],
        7 => ['wealth' => 'random', 'message' => '📈 Risky investment!'],
        8 => ['wealth' => -4000, 'message' => '🏥 Family medical bills! -$4,000'],
        9 => ['wealth' => 3000, 'message' => '💼 Big promotion! +$3,000'],
        10 => ['wealth' => 2500, 'message' => '🌟 Success bonus! +$2,500'],
        11 => ['wealth' => 1500, 'message' => '💰 Year-end bonus! +$1,500'],
        12 => ['wealth' => -2500, 'message' => '🏖️ Expensive family vacation! -$2,500'],
        13 => ['wealth' => -6000, 'message' => '🎓 Kids college tuition! -$6,000'],
        14 => ['wealth' => 1200, 'message' => '🤝 Networking success! +$1,200'],
        15 => ['wealth' => 'bankruptcy', 'message' => '💔 MESSY DIVORCE! Lost everything!'],
        16 => ['wealth' => -3500, 'message' => '🏠 Home repairs emergency! -$3,500'],
        17 => ['wealth' => 3500, 'message' => '💼 Leadership role! +$3,500'],
        18 => ['wealth' => -8000, 'message' => '📉 Business investment failed! -$8,000'],
        19 => ['wealth' => 4000, 'message' => '🎯 Peak performance! +$4,000'],
        20 => ['wealth' => 5000, 'message' => '🚀 Career mastery! +$5,000']
    ],
    3 => [ // Pre-Retirement Board
        1 => ['wealth' => 1500, 'message' => '🏖️ Retirement planning! +$1,500'],
        2 => ['wealth' => 3000, 'message' => '💰 Savings milestone! +$3,000'],
        3 => ['wealth' => 2000, 'message' => '📊 Portfolio review! +$2,000'],
        4 => ['wealth' => -5000, 'message' => '🏠 Downsizing costs! -$5,000'],
        5 => ['wealth' => 2500, 'message' => '👨‍👩‍👧‍👦 Legacy planning! +$2,500'],
        6 => ['wealth' => 'random', 'message' => '📈 Final risky investment!'],
        7 => ['wealth' => -8000, 'message' => '🏥 Major health crisis! -$8,000'],
        8 => ['wealth' => 2000, 'message' => '💡 Wisdom pays off! +$2,000'],
        9 => ['wealth' => 3000, 'message' => '🤝 Mentoring income! +$3,000'],
        10 => ['wealth' => 4000, 'message' => '🌟 Peak earnings! +$4,000'],
        11 => ['wealth' => -6000, 'message' => '📉 Market crash hits savings! -$6,000'],
        12 => ['wealth' => 4500, 'message' => '🏆 Lifetime achievement! +$4,500'],
        13 => ['wealth' => -10000, 'message' => '🏠 Reverse mortgage scam! -$10,000'],
        14 => ['wealth' => 1500, 'message' => '🎨 Retirement hobbies! +$1,500'],
        15 => ['wealth' => -4000, 'message' => '🌍 Expensive medical tourism! -$4,000'],
        16 => ['wealth' => 2500, 'message' => '👴 Elder wisdom! +$2,500'],
        17 => ['wealth' => 'bankruptcy', 'message' => '🚨 PONZI SCHEME! Lost everything!'],
        18 => ['wealth' => 3500, 'message' => '🏖️ Almost ready! +$3,500'],
        19 => ['wealth' => 5000, 'message' => '🎊 So close! +$5,000'],
        20 => ['wealth' => 6000, 'message' => '🏁 Retirement ready! +$6,000']
    ]
];

// Apply space event automatically
$space = $_SESSION['position'];
if ($space <= 20 && isset($board_events[$current_board][$space])) {
    $space_event = $board_events[$current_board][$space];

    if ($space_event['wealth'] === 'random') {
        // Special random events
        if ($current_board == 2 && $space == 7) { // Investment
            $success = rand(0, 1);
            if ($success) {
                $_SESSION['wealth'] += 5000;
                $_SESSION['space_event'] = '📈 Investment succeeded! +$5,000';
            } else {
                $_SESSION['wealth'] -= 2000;
                $_SESSION['space_event'] = '📉 Investment failed! -$2,000';
            }
        } elseif ($current_board == 3 && $space == 6) { // Final investment
            $success = rand(1, 3) == 1; // 33% chance
            if ($success) {
                $_SESSION['wealth'] = (int)($_SESSION['wealth'] * 1.5);
                $_SESSION['space_event'] = '🚀 Final investment paid off! +50% wealth';
            } else {
                $_SESSION['wealth'] -= 3000;
                $_SESSION['space_event'] = '💥 Final investment failed! -$3,000';
            }
        }
    } else {
        if ($space_event['wealth'] === 'bankruptcy') {
            $_SESSION['wealth'] = 0;
            $_SESSION['space_event'] = $space_event['message'];
        } else {
            $_SESSION['wealth'] += $space_event['wealth'];
            $_SESSION['space_event'] = $space_event['message'];
        }
    }
}

// Check for game over (bankruptcy)
if ($_SESSION['wealth'] <= 0) {
    header('Location: gameover.php');
    exit;
}

// Check if completed board
$current_board = $_SESSION['board'] ?? 1;
if ($_SESSION['position'] >= 20) {
    if ($current_board == 1) {
        $_SESSION['board'] = 2;
        $_SESSION['position'] = 1;
        $_SESSION['last_event'] = '🎊 Completed Early Life! Moving to Mid-Life Board!';
        header('Location: board2.php');
        exit;
    } elseif ($current_board == 2) {
        $_SESSION['board'] = 3;
        $_SESSION['position'] = 1;
        $_SESSION['last_event'] = '🎊 Completed Mid-Life! Moving to Pre-Retirement Board!';
        header('Location: board3.php');
        exit;
    } elseif ($current_board == 3) {
        $_SESSION['last_event'] = '🏆 Completed all boards! Time to retire!';
        header('Location: win.php');
        exit;
    }
}

// Return to current board
header("Location: board{$current_board}.php");
exit;
?>
