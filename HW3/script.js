// Game variables
let secretNumber;
let guessesRemaining;
let gameTimer;
let seconds;
const GUESS_LIMIT = 10;

// Sound effects
const correctSound = new Audio('https://www.soundjay.com/buttons/sounds/button-1.mp3');
const wrongSound = new Audio('https://www.soundjay.com/buttons/sounds/button-10.mp3');
const winSound = new Audio('https://www.soundjay.com/human/sounds/applause-2.mp3');
const loseSound = new Audio('https://www.soundjay.com/human/sounds/crowd-boo-1.mp3');


// HTML element references
const guessInput = document.getElementById('guessInput');
const guessButton = document.getElementById('guessButton');
const message = document.getElementById('message');
const guessesRemainingDisplay = document.getElementById('guessesRemaining');
const timerDisplay = document.getElementById('timer');

// Event listener for the guess button
guessButton.addEventListener('click', handleGuess);

// Function to start a new game
function startNewGame() {
    secretNumber = Math.floor(Math.random() * 100) + 1;
    guessesRemaining = GUESS_LIMIT;
    seconds = 0;
    message.textContent = '';
    guessesRemainingDisplay.textContent = guessesRemaining;
    guessInput.value = '';
    guessInput.disabled = false;
    guessButton.disabled = false;
    clearInterval(gameTimer);
    gameTimer = setInterval(() => {
        seconds++;
        timerDisplay.textContent = `${seconds}s`;
    }, 1000);
}

// Function to handle the player's guess
function handleGuess() {
    const userGuess = parseInt(guessInput.value);

    if (isNaN(userGuess) || userGuess < 1 || userGuess > 100) {
        message.textContent = 'Please enter a valid number between 1 and 100.';
        return;
    }

    guessesRemaining--;
    guessesRemainingDisplay.textContent = guessesRemaining;

    if (userGuess === secretNumber) {
        message.textContent = `Congratulations! You guessed the number ${secretNumber}!`;
        winSound.play();
        endGame(true);
    } else if (userGuess < secretNumber) {
        message.textContent = 'Too low! Guess again.';
        wrongSound.play();
    } else {
        message.textContent = 'Too high! Guess again.';
        wrongSound.play();
    }

    if (guessesRemaining === 0 && userGuess !== secretNumber) {
        message.textContent = `You lost! The secret number was ${secretNumber}.`;
        loseSound.play();
        endGame(false);
    }

    guessInput.value = '';
    guessInput.focus();
}

// Function to end the game
function endGame(isWin) {
    guessInput.disabled = true;
    guessButton.disabled = true;
    clearInterval(gameTimer);
    setTimeout(startNewGame, 3000); // Start a new game after 3 seconds
}

// Start the first game when the page loads
window.onload = startNewGame;