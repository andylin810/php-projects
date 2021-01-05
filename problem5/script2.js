// const performance = require('perf_hooks').performance;

jQuery(function() { 

    $(document).on('click','#automatic', function(e) {
        e.preventDefault()
        $('.main-container').load('automatic.html')
        $('#manual').removeClass('active')
        $(this).addClass('active')
    })

    $(document).on('click','#manual', function(e) {
        e.preventDefault()
        $('.main-container').load('manual.html')
        $('#automatic').removeClass('active')
        $(this).addClass('active')
    })

    $(document).on('submit','#matrix', function(e) {
        e.preventDefault()
        let a = $('#matrix-num').val()
        let count = Number(a)
        if(a <= 1) {
            $('#error-msg').html("please enter a number greater than 1")
            return
        }
        $('#error-msg').empty()
        let form = showTables(count)
        $('.table').html(form)
        $('.result').empty()
    })

    $(document).on('submit','#auto-generate', function(e) {
        e.preventDefault()
        let size = Number($('#matrix-size').val())
        let shape = Number($('#matrix-shape').val())
        if(size <= 1) {
            $('#error-msg').html("please enter a number greater than 1 for number of matrices")
            return
        }

        if(shape <= 0) {
            $('#error-msg').html("please enter a number greater than 0 for size of matrices")
            return
        }
        $('#error-msg').empty()
        $('.loading').text('loading...')
        let matrices = []
        let times = 0
        window.setTimeout(function (){ 
            matrices = generateRandomMatrices(size,shape) 
            times = displayTimeForCalculation(matrices,false)
            $('.table').html(times)
            $('.loading').empty()
            // $('.loading').text('finished loading...')
        }, 0);
        
        // $('.table').html(times)
    })
    
    $(document).on('submit','#enter-matrices', function(e) {
        e.preventDefault()
        let dimensions = []
        $('#enter-matrices td input').each(function () {
            $(this).removeClass('highlight-cell')
        })
        let len = $(this).attr('data-length')
        for(var i = 0; i<len; i++ ) {
            let row = []
            $(`input[name="strings[]"].${i}`).each( function (){
                let a = $(this).val()
                let num = Number(a)
                row.push(num)
            })
            dimensions.push(row)
        }
        for(var i = 1; i<len; i++) {
            if(dimensions[i][0] != dimensions[i-1][1]) {
                $('#error-msg').html("Please enter a the correct dimensions that allow matrix arithmetic eg. row=col")
                $(`tr#${i-1} td.col input`).addClass('highlight-cell')
                $(`tr#${i} td.row input`).addClass('highlight-cell')                
                return
            }
        }
        $('#error-msg').empty()
        $('#enter-matrices td input').each(function () {
            $(this).removeClass('highlight-cell')
        })
        let matrices = showTablesForMatrices(dimensions)
        $('.table').html(matrices)
        $('table#0').css('display','table')
    })

    $(document).on('click','button.nav',function() {
        const curr = Number($('.current-matrix').val())
        const len = $('#matrix-entries').attr('data-length')
        if($(this).attr('id') == 'left') {
            if(curr>1) {
                $(`table#${curr-1}`).css('display','none')
                $(`table#${curr-2}`).css('display','table')
                $('.current-matrix').val(curr-1)
                $('.current-matrix').data('previous',curr-1)
            }
        } else {
            if(curr<len) {
                $(`table#${curr-1}`).css('display','none')
                $(`table#${curr}`).css('display','table')
                $('.current-matrix').val(curr+1)
                $('.current-matrix').data('previous',curr+1)
            }
        }
    })

    $(document).on('change','.current-matrix', function() {
        let previous = Number($(this).data('previous'))
        $(`table#${previous-1}`).css('display','none')
        let current = $(this).val()
        $(this).data('previous',current)
        $(`table#${current-1}`).css('display','table')
    })

    $(document).on('submit','#matrix-entries', function(e) {
        e.preventDefault()
        let matrices = []
        const len = $(this).attr('data-length')
        let a = $('#matrix-num').val()
        for(var i =0; i<len; i++) {
            let matrix = []
            let row = $('table#'+i).attr('data-row')
            let col = $('table#'+i).attr('data-col')
            for(var r = 0; r<row; r++){
                let row = []
                for(var c = 0; c<col; c++) {
                    let a = Number($(`table#${i} td.row${r}.col${c}`).children().val())
                    row.push(a)
                }
                matrix.push(row)
            }
            matrices.push(matrix)
        }
        const result = displayTimeForCalculation(matrices)
        $('.result').html(result)
        $('.table').empty()
    })

    $(document).on('click','#entry', function() {
        let a = $('input').filter(function(){
            return this.value == ""
        }).first()
        let b = a.parents('table')
        let num = Number(b.attr('id'))

        let previous = Number($('.current-matrix').data('previous'))
        $(`table#${previous-1}`).css('display','none')
        b.css('display','table')
        $('.current-matrix').data('previous',num+1)
        $('.current-matrix').val(num+1)

    })


})

function showTables(num) {
    let head = `<form data-length='${num}' id='enter-matrices'><table class='nice-table'>`
    let header = `<tr>
    <th>#</th>
    <th>Row</th>
    <th></th>  
    <th>Column</th>        
    </tr>`;
    let body = ''
    for(var i = 0; i<num; i++) {
        body += `<tr id='${i}'>
        <td>matrix ${i+1}:</td>
        <td class='row'><input name='strings[]' class='${i}' required='required' class='matrix-input' type="number"></td>
        <td>X</td>
        <td class='col'><input name='strings[]' class='${i}' required='required' class='matrix-input' type="number"></td>        
        </tr>`;
    }
    let tail = `</table><div class='form-button'><input type="submit" value="enter"></div>
    </form>`
    let row = head+header+body+tail
    return row
}
 
function createMatrixEntries(row,col,index) {
    let header = `<table data-row='${row}' data-col='${col}' class='table-hide nice-table' id='${index}'>`;
    let body = ''
    body += '<tr>'
    body += `<td>Matrix ${index+1}</td>`
    for(var i = 0; i<col; i++) {
        body += `<td>Col ${i+1}</td>`
    }
    body += '</tr>'
    for(var i = 0; i<row; i++) {
        body += `<tr>`
        body += `<td>Row ${i+1}</td>`
        for(var j = 0; j<col; j++) {
            body += `
            <td class='row${i} col${j}'><input name='hello' required='required' class='matrix-input' type="number"></td>        
            `
        }
        body += `</tr>`
    }
    let tail = `</table>`
    let table = header+body+tail
    return table
}

function showTablesForMatrices(dimensions) {
    const len = dimensions.length
    let select = '<select data-previous="1" class="current-matrix flex-item">'
    for(var i = 0; i<len; i++) {
        select += `<option>${i+1}</option>`
    }
    select += "</select>"
    let buttons = '<button class="nav flex-item" id="right">Next</button></div>'
    let currentMatrix = `<div class="page"><button class="nav flex-item" id="left">Previous</button>${select}`
    
    let top = ''
    top += currentMatrix
    top += buttons
    let head = `<form data-length='${len}' id='matrix-entries'>`
    for(var i = 0; i<len; i++) {
        let dimension = dimensions[i]
        let table = createMatrixEntries(dimension[0],dimension[1],i)
        head += table
    }
    head += '<div class="form-button"><input id="entry" type="submit" value="enter"></div></form>'
    return top+head
}

function displayMatrix(matrix,title) {
    let header = `<table class='nice-table'>`;
    const row = matrix.length
    const col = matrix[0].length
    let body = ''
    body += '<tr>'
    body += `<td>${title}</td>`
    for(var i = 0; i<col; i++) {
        body += `<td>Col ${i+1}</td>`
    }
    body += '</tr>'
    for(var i = 0; i<row; i++) {
        body += `<tr>`
        body += `<td>row ${i+1}</td>`
        for(var j = 0; j<col; j++) {
            body += `
            <td>${matrix[i][j]}</td>        
            `;
        }
        body += `</tr>`
    }
    let tail = `</table>`
    let table = header+body+tail
    return table
}

function displayTimeForCalculation(matrices,showResult=true) {

    let add = "<div class='result-matrix'><div class='time-result'>Result of adding all matrices: "
    let sub =  "<div class='result-matrix'><div class='time-result'>Result of subtracting all matrices: "
    let multiply = "<div class='result-matrix'><div class='time-result'>Result of multiplying all matrices: "
    let divide = "<div class='result-matrix'><div class='time-result'>Result of dividing all matrices: "

    let sum = ''
    let product = ''
    let division = ''
    let difference = ''

    let sumTime = 0.0
    let subTime = 0.0
    let proTime = 0.0
    let divTime = 0.0

    try {
        let t0 = performance.now()
        let num = multiplyAllMatrices(matrices,multiplyMatrices)
        let t1 = performance.now()
        proTime = t1-t0
        product = displayMatrix(num,'Multiplication')
              
    } catch(err) {
        product = err
    }
    product += '</div>'

    try {
        let t0 = performance.now()
        let num = multiplyAllMatrices(matrices,addMatrices)
        let t1 = performance.now()
        sum = displayMatrix(num,'Addition')
        sumTime = t1-t0
    } catch(err) {
        sum = err
    }
    sum += '</div>'

    try {
        let t0 = performance.now()
        let num = multiplyAllMatrices(matrices,subtractMatrices)
        let t1 = performance.now()
        difference = displayMatrix(num,'Subtraction')
        subTime = t1-t0
    } catch(err) {
        difference = err
    }
    difference += '</div>'

    try {
        let t0 = performance.now()
        let num = multiplyAllMatrices(matrices,divideMatrices)
        let t1 = performance.now()
        divTime = t1-t0
        let roundedMatrix = roundDecimals(num)
        division = displayMatrix(roundedMatrix,'Division')

    } catch(err) {
        division = err
    }
    division += '</div>'

    let time1 = `<div class='time-result'>Time takes to finished additions: ${sumTime} milliseconds</div></div>`
    let time2 = `<div class='time-result'>Time takes to finished additions: ${subTime} milliseconds</div></div>`
    let time3 = `<div class='time-result'>Time takes to finished multiplications: ${proTime} milliseconds</div></div>`
    let time4 = `<div class='time-result'>Time takes to finished divisions: ${divTime} milliseconds</div></div>`

    let result = showResult? add+sum+time1+sub+difference+time2+multiply+product+time3+divide+division+time4 : time1+time2+time3+time4

    return result
}
