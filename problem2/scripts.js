//initial game variables
let xTurn = true;
let grid = [];
let gameOver = false;
let cMode = false;
let numTurns = 0;
let score = {
    x: 0,
    o : 0,
    tie : 0
};

//computer object which contains logic for playing and the status of the board
let computer = {
    board : grid,
    moves : [0,1,2,3,4,5,6,7,8],
    makeMove : function (){
        console.log(this.moves);
        numTurns++;
        let move = -1
        
        for (var i=0; i<this.moves.length; i++) {
            this.board[this.moves[i]] = 1;
            if (checkWin(this.board)) {
                this.board[this.moves[i]] = 1;
                let o = document.getElementById(""+this.moves[i]);
                o.innerHTML = "O";
                this.moves.splice(i,1);
                return;
            } else {
                revertMove(this.moves[i],this.board);
            }
            this.board[this.moves[i]] = 2;
            if (checkWin(this.board)) {
                // this.board[this.moves[i]] = 1;
                // let o = document.getElementById(""+this.moves[i]);
                // o.innerHTML = "O";
                // this.moves.splice(i,1);
                move = i
                revertMove(this.moves[i],this.board);
            } else {
                revertMove(this.moves[i],this.board);
            }
        }

        if(move !== -1) {
                this.board[this.moves[move]] = 1;
                let o = document.getElementById(""+this.moves[move]);
                o.innerHTML = "O";
                this.moves.splice(move,1);
                return;
        }

        // for (var i=0; i<this.moves.length; i++) {
        //     this.board[this.moves[i]] = 2;
        //     if (checkWin(this.board)) {
        //         this.board[this.moves[i]] = 1;
        //         let o = document.getElementById(""+this.moves[i]);
        //         o.innerHTML = "O";
        //         this.moves.splice(i,1);
        //         return;
        //     } else {
        //         revertMove(this.moves[i],this.board);
        //     }
        // }

        const num = Math.floor(Math.random() * this.moves.length);
        const index = this.moves[num];
        this.board[index] = 1;
        let o = document.getElementById(""+index);
        o.innerHTML = "O";
        this.moves.splice(num,1);
        return;
    }
}

//setting up the table for the game
function setUp() {
    let id = 0;
    for(var row =0; row < 3; row++) {
        $(".game").append("<tr class='" + row + "'></tr>");
        for(var j =0; j < 3; j++) {
            $("."+row).append("<td class='cell' id='" + id + "'></td>");
            grid.push(0); 
            id++;
        }
    }
};

//setting score in score board
function setScore() {
    let scoreX = document.getElementById('x');
    let scoreO = document.getElementById('o');
    let scoreTie = document.getElementById('tie');
    scoreX.innerHTML = String(score.x);
    scoreO.innerHTML = String(score.o);
    scoreTie.innerHTML = String(score.tie);
}

//set up event listeners and functions when document is prepared
$(document).ready(function() {
    let message = document.getElementById("message");
    setUp();

    let winScreen = document.getElementById('winning');

    //adding event handler to each of the cells, enable playing
    $(".cell").click( (e) => {
        if (!gameOver) {
            let text = "";
            if (xTurn) {
                text = "X";
            } else {
                text = "O";
            }
            let index = Number(e.currentTarget.id);
            if (grid[index] === 0) {
                xTurn = !xTurn;
                placeMove(index,grid,xTurn);
                $(e.currentTarget).text(text);
                let win = checkWin(grid);
                if (win) {
                    gameOver = true;
                    xTurn ? message.innerHTML = "O Wins" : message.innerHTML = "X Wins";
                    xTurn ? score.o++ : score.x++;
                    setScore();
                    winScreen.style.display = 'flex';
                } else if (numTurns>=9) {
                    gameOver = true;
                    score.tie++;
                    setScore();
                    message.innerHTML = "Tie Game";
                    winScreen.style.display = 'flex';
                } else if (cMode){
                    let moveIndex = computer.moves.indexOf(index);
                    computer.moves.splice(moveIndex,1);
                    computer.makeMove();
                    xTurn = !xTurn;
                    if (checkWin(grid)) {
                        gameOver = true;
                        score.o++;
                        setScore();
                        xTurn ? message.innerHTML = "O Wins" : message.innerHTML = "X Wins";
                        winScreen.style.display = 'flex';
                    } else if (numTurns>=9) {
                        gameOver = true;
                        score.tie++;
                        setScore();
                        message.innerHTML = "Tie Game";
                        winScreen.style.display = 'flex';
                    }
                }
            }
        }
    });
    
    //restarting the game, reset all necessary states
    $("#restart").click( e => {
        xTurn = true;
        grid = [];
        gameOver = false;
        numTurns = 0;
        computer.moves = [];
        computer.board = grid;

        let message = document.getElementById("message");
        message.innerHTML = "";

        for(var i = 0; i < 9; i++){
            console.log(i);
            grid.push(0);
            computer.moves.push(i);
            let cell = document.getElementById(""+i);
            cell.innerHTML = "";

        }
        winScreen.style.display = 'none';
        
    });


    //click listener to start/stop computer mode
    $("#add-computer").click( e => {
        if (numTurns === 0 ) {
            cMode = !cMode;
            if (cMode) {
                document.getElementById('computer-mode').innerHTML = "Computer Mode On";
                document.getElementById('computer-mode').className = 'computer-color';

            } else {
                document.getElementById('computer-mode').innerHTML = "Computer Mode Off";
                document.getElementById('computer-mode').className = 'player-color';
            }
        }
    });

    

});

//place a move in the grid
function placeMove(index,grid,xTurn) {
    numTurns++;
    grid[index] = xTurn ? 1 : 2;
}

//revert the cell to original state
function revertMove(index,grid) {
    grid[index] = 0;
}

//checking winning condition
function checkWin(grid) {
    //check rows
    for(var i =0;i<7; i+=3) {

        if(grid[i] !== 0 && grid[i] === grid[i+1] && grid[i] === grid[i+2]) {
            return true;
        }
    }

    //check columns
    for (var j = 0; j < 3; j++){
        if(grid[j] !== 0 && grid[j] === grid[j+3] && grid[j] === grid[j+6]) {
            return true;
        }
    }

    //check diagnal 
    if( grid[4] !== 0 && ((grid[4] === grid[0] && grid[4] === grid[8]) || (grid[4] === grid[2] && grid[4] === grid[6]))) {
        return true;
    }

    return false;

}