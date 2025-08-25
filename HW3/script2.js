document.addEventListener('DOMContentLoaded', () => {
    // --- DOM Elements ---
    const setupScreen = document.getElementById('setup-screen');
    const gameScreen = document.getElementById('game-screen');
    const startBtn = document.getElementById('start-btn');
    const difficultySelect = document.getElementById('difficulty');
    const pairsSelect = document.getElementById('pairs');
    const gameBoard = document.getElementById('game-board');
    const timeLeftSpan = document.getElementById('time-left');
    const currentScoreSpan = document.getElementById('current-score');

    // Modals
    const gameOverModal = document.getElementById('game-over-modal');
    const leaderboardModal = document.getElementById('leaderboard-modal');
    const gameOverTitle = document.getElementById('game-over-title');
    const gameOverMessage = document.getElementById('game-over-message');
    const finalScoreSpan = document.getElementById('final-score');
    const playerNameInput = document.getElementById('player-name');
    const saveScoreBtn = document.getElementById('save-score-btn');
    const playAgainBtn = document.getElementById('play-again-btn');
    const leaderboardBtn = document.getElementById('leaderboard-btn');
    const closeLeaderboardBtn = document.getElementById('close-leaderboard-btn');
    const leaderboardList = document.getElementById('leaderboard-list');

    // --- Game State Variables ---
    let gameSettings = {};
    let cards = [];
    let hasFlippedCard = false;
    let lockBoard = false;
    let firstCard, secondCard;
    let matchedPairs = 0;
    let score = 0;
    let timeLeft = 0;
    let timerInterval;

    // --- Image Sources ---
    
    const imageSources = [
        'https://codd.cs.gsu.edu/~tmakuvaza1/WP/HW/3/images/dog.png',
        'https://codd.cs.gsu.edu/~tmakuvaza1/WP/HW/3/images/pug.jpg',
        'https://codd.cs.gsu.edu/~tmakuvaza1/WP/HW/3/images/vulture.png',
        'https://codd.cs.gsu.edu/~tmakuvaza1/WP/HW/3/images/lion.jpg',
        'https://codd.cs.gsu.edu/~tmakuvaza1/WP/HW/3/images/moose.jpg',
        'https://codd.cs.gsu.edu/~tmakuvaza1/WP/HW/3/images/deer.jpg',
        'https://codd.cs.gsu.edu/~tmakuvaza1/WP/HW/3/images/fox.jpg',
        'https://codd.cs.gsu.edu/~tmakuvaza1/WP/HW/3/images/bear.jpg',
        'https://codd.cs.gsu.edu/~tmakuvaza1/WP/HW/3/images/owl.jpg',
        'https://codd.cs.gsu.edu/~tmakuvaza1/WP/HW/3/images/swan.jpg',
        'https://codd.cs.gsu.edu/~tmakuvaza1/WP/HW/3/images/wolf.jpg',
        'https://codd.cs.gsu.edu/~tmakuvaza1/WP/HW/3/images/eagle.jpg',
    ];

    // --- Event Listeners ---
    startBtn.addEventListener('click', startGame);
    playAgainBtn.addEventListener('click', resetGame);
    leaderboardBtn.addEventListener('click', showLeaderboard);
    closeLeaderboardBtn.addEventListener('click', () => leaderboardModal.classList.add('hidden'));
    saveScoreBtn.addEventListener('click', saveScore);


    // --- Functions ---

    function startGame() {
        // 1. Get settings
        const numPairs = parseInt(pairsSelect.value);
        const memorizationTime = parseInt(difficultySelect.value);
        const timeLimits = { 8: 120, 10: 150, 12: 180 };
        timeLeft = timeLimits[numPairs];

        gameSettings = { numPairs, memorizationTime, timeLeft };
        
        // 2. Reset score and board
        score = 0;
        matchedPairs = 0;
        currentScoreSpan.innerText = score;
        timeLeftSpan.innerText = timeLeft;
        gameBoard.innerHTML = ''; // Clear previous cards

        // 3. UI updates
        setupScreen.classList.add('hidden');
        gameScreen.classList.remove('hidden');
        gameOverModal.classList.add('hidden');
        
        // 4. Create and shuffle cards
        let selectedImages = imageSources.slice(0, numPairs);
        let cardImages = [...selectedImages, ...selectedImages]; // Duplicate for pairs
        shuffle(cardImages);

        // 5. Generate card elements and setup grid
        gameBoard.style.gridTemplateColumns = `repeat(${numPairs === 12 ? 6 : 4}, 100px)`;
        cards = []; // Clear the cards array
        cardImages.forEach((imageSrc, index) => {
            const card = createCardElement(imageSrc, index + 1);
            gameBoard.appendChild(card);
            cards.push(card);
        });

        // 6. Memorization phase
        lockBoard = true;
        cards.forEach(card => card.classList.add('flip')); // Show all images

        setTimeout(() => {
            cards.forEach(card => card.classList.remove('flip'));
            lockBoard = false;
            // 7. Start the game timer AFTER memorization
            startTimer();
        }, memorizationTime);
    }

    function createCardElement(imageSrc, number) {
        const card = document.createElement('div');
        card.classList.add('card');
        card.dataset.image = imageSrc;

        card.innerHTML = `
            <div class="card-face card-front">${number}</div>
            <div class="card-face card-back">
                <img src="${imageSrc}" alt="Card Image">
            </div>
        `;
        card.addEventListener('click', flipCard);
        return card;
    }

    function shuffle(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
    }

    function flipCard() {
        if (lockBoard) return;
        if (this === firstCard) return; // Prevent double clicking the same card

        this.classList.add('flip');

        if (!hasFlippedCard) {
            // First card flipped
            hasFlippedCard = true;
            firstCard = this;
        } else {
            // Second card flipped
            secondCard = this;
            checkForMatch();
        }
    }

    function checkForMatch() {
        let isMatch = firstCard.dataset.image === secondCard.dataset.image;

        if (isMatch) {
            score += 10;
            matchedPairs++;
            disableCards();
        } else {
            score = Math.max(0, score - 5); // Score can't go below 0
            unflipCards();
        }

        currentScoreSpan.innerText = score;

        // Check for win condition
        if (matchedPairs === gameSettings.numPairs) {
            endGame(true); // Player won
        }
    }

    function disableCards() {
        firstCard.removeEventListener('click', flipCard);
        secondCard.removeEventListener('click', flipCard);
        resetBoard();
    }

    function unflipCards() {
        lockBoard = true;
        setTimeout(() => {
            firstCard.classList.remove('flip');
            secondCard.classList.remove('flip');
            resetBoard();
        }, 1200); // Wait 1.2 seconds before flipping back
    }

    function resetBoard() {
        [hasFlippedCard, lockBoard] = [false, false];
        [firstCard, secondCard] = [null, null];
    }
    
    function startTimer() {
        clearInterval(timerInterval); // Clear any existing timer
        timerInterval = setInterval(() => {
            timeLeft--;
            timeLeftSpan.innerText = timeLeft;
            if (timeLeft <= 0) {
                endGame(false); // Player lost (time ran out)
            }
        }, 1000);
    }

    function endGame(isWin) {
        clearInterval(timerInterval);
        lockBoard = true;

        if (isWin) {
            gameOverTitle.innerText = "Congratulations, You Won!";
            // Optional: Add a winning animation/effect here
        } else {
            gameOverTitle.innerText = "Time's Up!";
            // Calculate penalty for time over (not applicable here as game ends at 0)
        }

        finalScoreSpan.innerText = score;
        gameOverModal.classList.remove('hidden');
    }

    function resetGame() {
        gameOverModal.classList.add('hidden');
        gameScreen.classList.add('hidden');
        setupScreen.classList.remove('hidden');
        resetBoard();
    }

    function saveScore() {
        const name = playerNameInput.value.trim();
        if (!name) {
            alert("Please enter a name.");
            return;
        }

        const leaderboard = JSON.parse(localStorage.getItem('memoryGameLeaderboard')) || [];
        const newScore = { name, score };

        leaderboard.push(newScore);
        leaderboard.sort((a, b) => b.score - a.score); // Sort descending
        leaderboard.splice(5); // Keep only top 5

        localStorage.setItem('memoryGameLeaderboard', JSON.stringify(leaderboard));
        
        playerNameInput.value = '';
        showLeaderboard(); // Show updated leaderboard
        document.getElementById('leaderboard-entry').classList.add('hidden'); // Hide entry form
    }

    function showLeaderboard() {
        const leaderboard = JSON.parse(localStorage.getItem('memoryGameLeaderboard')) || [];
        leaderboardList.innerHTML = ''; // Clear previous list

        if (leaderboard.length === 0) {
            leaderboardList.innerHTML = '<li>No scores yet. Be the first!</li>';
        } else {
            leaderboard.forEach(entry => {
                const li = document.createElement('li');
                li.textContent = `${entry.name} - ${entry.score} points`;
                leaderboardList.appendChild(li);
            });
        }
        
        leaderboardModal.classList.remove('hidden');
    }

});