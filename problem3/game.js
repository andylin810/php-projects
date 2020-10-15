let backtracks = 0
let grid = []
let solutionGrid = []


let arr = []
let cellArr = []

//shuffling array containing the the order of number to insert
// and the array containing the position of the cell for removing purpose
function shuffleArray(array,array2) {
    //initialize array of 1-10
    for(var i = 1; i < 10; i++) {
        array.push(i)
    }

    //initialize array of 0-80
    for(var i = 0; i < 81; i++) {
        array2.push(i)
    }

    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }

    for (let i = array2.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array2[i], array2[j]] = [array2[j], array2[i]];
    }
}

//populate the grid array with 9 rows with 9 cells per row
// initially start at all 0s
function generateGrid() {
    for(var row =0; row < 9 ; row++) {
        grid[row] = [];
        for(var col = 0; col < 9; col++){
            grid[row][col] = 0
        }
    } 
}

//prints out the grid to console for testing purposes
function printGrid(grid) {
    let str = ''
    for(var row =0; row < 9 ; row++) {
        str = ''
        for(var col = 0; col < 9; col++){
            str += grid[row][col] + " "
        }
        console.log(str)
    } 
}

//chekcing to see if inserting num at the position row
// and col is valid by checking vertical horizontal and 
// sectors
function checkValid(grid, row, col, num) {

    let currCell = grid[row][col]
    grid[row][col] = 0

    //checking the row if number exists
    if (grid[row].includes(num)) {
        grid[row][col] = currCell
        return false
    }

    //checking the column if number exists
    for(var r = 0; r < 9; r++) {
        if (grid[r][col] === num) {
            grid[row][col] = currCell
            return false
        }
    }

    //checking the sector
    let i  = Math.floor(row / 3) * 3;
    let j  = Math.floor(col / 3) * 3;

    for( var r = i; r<i + 3;r++) {
        for( var c = j; c<j+3; c++) {
            if(grid[r][c] === num) {
                grid[row][col] = currCell
                return false
            }
        }
    }
    grid[row][col] = currCell

    return true
}

//scanning the grids left are fil to right top to bottom
// return the position of the next cell that is empty(0)
// if all cellsled return -1
function findNext(grid) {
    for(var i = 0; i < 9; i++) {
        for(var j = 0; j < 9; j++) {
            if(grid[i][j] === 0) {
                return {
                    row: i,
                    col: j
                }
            }
        }
    }
    return {
        row: -1,
        col: -1
    }
}

//recursively solve sudoku by backtracking, trying every number from 1-9 
// while checking its validity by the checkValid() function if not valid, backtrack set it back
// to 0 then try the next number

//This function will solve the grid with answers filled
function solveSudoku(grid) {
    //finding the next available cell
    //if -1 is returned, the grid is solved, end of recursion
    let cell = findNext(grid)
    let row = cell.row
    let col = cell.col

    if (row === -1) {
        return true
    }


    for(var i = 0; i<9; i++) {
        if(checkValid(grid,row,col,arr[i])) {
            grid[row][col] = arr[i]
            if (solveSudoku(grid)) {
                return true
            }
            backtracks++
            grid[row][col] = 0
        }
    }
    return false
}

//This function solves the sudoku by trying 9-1 instead of 1-9, this tells me 
// if the sudoku grid has more than one solution or not
function solveSudoku2(board) {
    let cell = findNext(board)
    let row = cell.row
    let col = cell.col

    if (row === -1) {
        return true
    }


    for(var i = 8; i>=0; i--) {
        if(checkValid(board,row,col,arr[i])) {
            board[row][col] = arr[i]
            if (solveSudoku2(board)) {
                return true
            }
            backtracks++
            board[row][col] = 0
        }
    }
    return false
}

//checking to see if the grid of sudoku has a unique solution
function checkUniqueSolution(grid) {
    let grid1 = []
    let grid2 = []

    //copying sudoku grid into two arrays
    for(var i = 0; i<grid.length; i++) {
        grid1[i] = grid[i].slice()
        grid2[i] = grid[i].slice()
    }

    if (solveSudoku(grid1)) {
        solveSudoku2(grid2)
    } else {
        return false
    }
    return compareArray(grid1,grid2)



}

function printSudoku() {
    generateGrid();
    solveSudoku(grid)
    //printGrid(grid)
    removeCells(cellArr,grid,1)
    // console.log("")
    // printGrid(grid)
    // console.log(checkUniqueSolution(grid))
    // solveSudoku(grid)
    // printGrid(grid)
}

//comparing the solution grids by checking if each cell is equal
function compareArray(arr1,arr2) {
    let len = arr1.length
    for(var row = 0; row < len; row++) {
        for(var col = 0 ; col < arr1[row].length; col ++) {
            if (arr1[row][col] !== arr2[row][col]) {
                return false
            }
        }
    }
    return true
}

//get row and col index from the number 1-81
function getIndex(num) {
    let row = Math.floor(num/9)
    let col = num % 9
    return {
        r: row,
        c: col
    }
}

//removing cells one by one from order the random array, if after removal the sudoku grid
// still produces a unique solution, the cell will be removed, otherwise place the number back
// and continue with the next random cell
function removeCells(cells,grid,removeNum) {
    for(var i = 0; i<grid.length; i++) {
        solutionGrid[i] = grid[i].slice(0)
    }
    
    let count = 0;
    let index = 0;
    //for(var i = 0; i<cells.length; i++) {
    while(count < removeNum) {
        let currentCellIndex = getIndex(cells[index])
        let currentRow = currentCellIndex.r
        let currentCol = currentCellIndex.c
        let currentCell = grid[currentRow][currentCol]
        count++
        index++
        grid[currentRow][currentCol] =  0
        if(!checkUniqueSolution(grid)) {
            grid[currentRow][currentCol] = currentCell
            count--
        }
    }
}

shuffleArray(arr,cellArr)
generateGrid();
solveSudoku(grid)
removeCells(cellArr,grid,50)
// printSudoku()




