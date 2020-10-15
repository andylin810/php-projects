let emptyCell = 0
let total = 0

//setting up the table for the game
function setUp() {
    for(var row =0; row < 9; row++) {
        $(".game").append("<tr class='" + row + "'></tr>")
        for(var col =0; col < 9; col++) {
            let rowNum = row + 1
            let colNum = col + 1
            let rowClass = ''
            let colClass = ''
            if ( rowNum < 9 && (rowNum % 3) === 0) {
                rowClass = ' border-bot'
            }
            if ( colNum < 9 && (colNum % 3) === 0) {
                colClass = ' border-right'
            }
            if(grid[row][col] !== 0){
                $("."+row).append(`<td class='cell${rowClass}${colClass}' data-row='${row}' data-col='${col}'>${grid[row][col]}</td>`)
            } else {
                emptyCell++
                $("."+row).append(`<td class='cell empty-cell${rowClass}${colClass}' data-row='${row}' data-col='${col}'></td>`)
            }
        }
    }
    total = emptyCell
    $('.cell-remaining').text(emptyCell)
}

$(document).ready(function() {
    setUp()
    $('.empty-cell').click(function() {
        selectEmptyCell(this)
    })
    $('#fill').click(function () {
        let {row,col} = findNext(grid)
        if (row >=0 && col >=0) {
            let num = solutionGrid[row][col]
            const selector = `.empty-cell[data-row="${row}"][data-col="${col}"]`
            fillCellAndUI(grid,row,col,num,selector,true)
            checkAllEmptyCell(row,col)
            selectEmptyCell(selector)
            if (checkWin(emptyCell)) {
                let message = document.getElementById("message");
                let winScreen = document.getElementById('winning');
                message.innerHTML = "You Win";
                winScreen.style.display = 'flex';
            }
        }
        
    })
})

$(document).on('keypress', function(e) {
    //only handle keyboard input from number keys or <-
    if(e.which == 8 || !isNaN(String.fromCharCode(e.which))){
        fillCellWithSelector(grid,'.selected-cell',e)
        if (checkWin(emptyCell)) {
            let message = document.getElementById("message");
            let winScreen = document.getElementById('winning');
            message.innerHTML = "You Win";
            winScreen.style.display = 'flex';
        }
    }
})

function fillCellWithSelector(grid,selector,event) {
    if($(selector).length){
        const num = Number(event.key)
        const row =  Number($(selector).attr('data-row'))
        const col =  Number($(selector).attr('data-col'))

        //if <- or 0 is pressed, clear the grid
        if(event.which == 48 || event.which == 8) {
            console.log(event.which)
            removeCellAndUI(grid,row,col,selector)

            //else the number is entered
        } else {
            fillCellAndUI(grid,row,col,num,selector,false)
        }
    }
}

function fillCellAndUI(grid,row,col,num,selector,hint){
    const validCell = $(selector).hasClass('valid-cell') 
    $(selector).text(num)
    if(hint) {
        emptyCell--
        $(selector).addClass('hint-cell')
        $(selector).addClass('valid-cell')
        $(selector).removeClass('invalid-cell')
    } else {
        grid[row][col] = 0
        if (!checkValid(grid,row,col,num)) {
            if (validCell) emptyCell++
            $(selector).addClass('invalid-cell')
            $(selector).removeClass('valid-cell')
            $(selector).removeClass('hint-cell')
        } else {
            if (!validCell) emptyCell--
            $(selector).removeClass('invalid-cell')
            $(selector).addClass('valid-cell')
        }
    }
    grid[row][col] = num
    $('.cell-remaining').text(emptyCell)
}

function removeCellAndUI(grid,row,col,selector){
    removeCell(grid,row,col)
    removeCellUI(selector)
    checkAllEmptyCell(row,col)
}

function removeCell(grid,row,col) {
    grid[row][col] = 0
}

function removeCellUI(selector){
    const validCell = $(selector).hasClass('valid-cell') || $(selector).hasClass('hint-cell')
    if (validCell) emptyCell++
    $(selector).removeClass('invalid-cell')
    $(selector).removeClass('valid-cell')
    $(selector).text("")
    $('.cell-remaining').text(emptyCell)
}

function selectEmptyCell(selector) {
    $('.empty-cell').removeClass('selected-cell')
    $(selector).addClass('selected-cell')
}

function checkAllEmptyCell(row, col) {

    //checking the row if number exists
    for(var c = 0; c < 9; c++) {
        checkCell(row,c)
    }

    //checking the column if number exists
    for(var r = 0; r < 9; r++) {
        checkCell(r,col)    
    }

    //checking the sector
    let i  = Math.floor(row / 3) * 3;
    let j  = Math.floor(col / 3) * 3;

    for( var r = i; r<i + 3;r++) {
        for( var c = j; c<j+3; c++) {
            checkCell(r,c)
        }
    }
}

function checkCell(row,col) {
    const selector = `.cell[data-row="${row}"][data-col="${col}"]`
    if ($(selector).hasClass('empty-cell') && ($(selector).hasClass('valid-cell') || $(selector).hasClass('invalid-cell')) &&
    !$(selector).hasClass('hint-cell')) {
        const num = Number($(selector).text())
        if(checkValid(grid,row,col,num)) {
            $(selector).addClass('valid-cell')
            $(selector).removeClass('invalid-cell')
        } else {
            $(selector).addClass('invalid-cell')
            $(selector).removeClass('valid-cell')
        }
    }
    countEmptyCell()
}

function countEmptyCell() {
    let count = 0
    $('.empty-cell.valid-cell').each(()=>{
        count++
    })
    emptyCell = total - count
    $('.cell-remaining').text(emptyCell)
    return count
}

function checkWin(cellCount) {
    return cellCount === 0
}

