jQuery(function() { 
    $(document).on('submit','#matrix', function(e) {
        e.preventDefault()
        let a = $('#matrix-num').val()
        let count = Number(a)
        if(a < 0) {
            $('#error-msg').html("please enter a number greater than 0")
            return
        }
        $('#error-msg').empty()
        let form = showTables(count)
        $('.table').html(form)
    })

    $(document).on('submit','#enter-matrices', function(e) {
        e.preventDefault()
        let dimensions = []
        $('#enter-matrices td').each(function () {
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
                $('#error-msg').html("please enter a the correct dimensions that allow matrix arithmetic")
                $(`tr#${i-1} td.col`).addClass('highlight-cell')
                $(`tr#${i} td.row`).addClass('highlight-cell')                
                return
            }
        }
        $('#error-msg').empty()
        $('#enter-matrices td').each(function () {
            $(this).removeClass('highlight-cell')
        })
        showTablesForMatrices(dimensions)
    })

})

function showTables(num) {
    let head = `<form data-length='${num}' id='enter-matrices'><table>`
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
    let tail = `</table><input type="submit" value="enter">
    </form>`
    let row = head+header+body+tail
    return row

}
 
function createMatrixEntries(row,col,index) {
    let header = `<table id='${index}'>`;
    let body = ''
    for(var i = 0; i<row; i++) {
        body += `<tr>`
        for(var j = 0; j<col; j++) {
            body += `
            <td class='row${i} col${j}'><input name='strings[]' required='required' class='matrix-input' type="number"></td>        
            `;
        }
        body += `</tr>`
    }
    let tail = `</table>`
    let row = head+header+body+tail
    return row
}

function showTablesForMatrices(dimensions) {
    let head = `<form data-length='' id='matrix-entries'>`
}
