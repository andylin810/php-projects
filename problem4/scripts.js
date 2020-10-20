jQuery(function() { 
    $(document).on('submit','#string-number', function(e) {
        e.preventDefault()
        let form = $(this)
        let term = form.find( "input[name='count']" ).val()
        let strMin = form.find( "input[name='string-min']" ).val()
        let strMax = form.find( "input[name='string-max']" ).val()
        let head = `<form id='enter-string'>`
        let body = ''
        for(var i = 0; i<term; i++) {
            body += `<label class='string-label'>${i+1}<input name='strings[]' required='required' maxlength='${strMax}' class='string-input' id='row${i}' type="text"></label>`;
        }
        let tail = `<input type="submit" value="enter">
        </form>`
        let row = head+body+tail
        $('.enter-string').html(row)

    //regular expression for string requirement
    const regExp = RegExp(`^(?=.{${strMin},})(?=.*[a-z])(?=.*[0-9])(?=.*[*+-\/])(?=.*[A-Z])(?=.*[~\!@#$%^&()_\`{}<>])(?=.*[:";',\.?]).*$`)
        const newRegexp = regExp.source
        $('.string-input').each(function(){
            $(this).attr('pattern',newRegexp)
        })
    })

    $(document).on('submit','#enter-string', function(e) {
        e.preventDefault()
        let strs = []
        let form = $(this)
        $('.string-input').each(function() {
            let str = $(this).val();
            strs.push(str)
        })
        let tables = createTables(strs)
        $('#table1').html(tables[0])
        $('#table2').html(tables[1])
        $('#table3').html(tables[2])
    })



})

function createTables(strs) {
    let dic = {}
    let table = 
    `<table class='nice-table'><thead>
    <tr>
        <th>#</th>
        <th>Original</th>
        <th>Reversed</th>
        <th>InsideOut</th>
    </tr>
    </thead>`
    table += '<tbody>'
    for(var i =0; i<strs.length; i++) {
        let original = strs[i]
        let reversed = reverseString(original,dic)
        let insideOut = insideOutString(original)
        table += 
        `<tr>
            <td>${i+1}</td>
            <td>${original}</td>
            <td>${reversed}</td>
            <td>${insideOut}</td>
        </tr>`
    }
    table += '</tbody></table>'

    table2 = createTableFromDics(dic)[0]

    table3 = createTableFromDics(dic)[1]

    return [table,table2,table3]
}

function createTableFromDics(dic) {

    let table = `
    <table class='nice-table'>
    `
    let charArr = []
    for(var item in dic) {
        charArr.push([item,dic[item]])
    }
    charArr.sort((a,b) => {
        return b[1] - a[1]
    })

    let max = showTopChar(charArr)[0]
    let min = showTopChar(charArr)[1]

    table += `
    <thead>
    <tr>
        <th colspan='${charArr.length}'>Character counts</th>
    </tr>
    </thead>
    `

    //displaying characters used in previous table
    table += `<tr>`
    for(var i = 0; i<charArr.length; i++) {
        table += `<td>${charArr[i][0]}</td>`
    }
    table += `</tr>`

    //displaying character occurrences sorted high to low
    table += `<tr>`
    for(var i = 0; i<charArr.length; i++) {
        table += `<td>${charArr[i][1]}</td>`
    }
    table += `</tr>` 
    table += `</table>`

    //table 2
    let table2 = `
    <table class='nice-table'>
    `
    //displaying header for most and least 
    table2 += `<thead><tr>`
        table2 += `<th>Most Used Chars</th>` 
        table2 += `<th>Least Used Chars</th>` 
    table2 += `</tr></thead>` 

    //displaying most and least used characters
    table2 += `<tr>`

        //displaying max chars in a drop down
        table2 += `<td><select>`
        for(var i = 0; i<max.length; i++) {
            table2 += `<option>${max[i][0]}</option>`
        }
        table2 +=  `</select></td>`

        //displaying min chars in a drop down
        table2 += `<td><select>`
        for(var i = 0; i<min.length; i++) {
            table2 += `<option>${min[i][0]}</option>`
        }
        table2 +=  `</select></td>`
    table2 += `</tr>` 

    //displaying most and least used characters
    table2 += `<tr>`
    table2 += `<td>${max[0][1]}</td>`
    table2 += `<td>${min[0][1]}</td>`
    table2 += `</tr>` 

    table2 += `</table>`


    return [table,table2]

}

//get the most and least used chars from a sorted array
function showTopChar(arr) {

    let max = []
    let min = []
    max.push(arr[0])
    min.push(arr[arr.length-1])

    for(var i = 1; i<arr.length; i++) {
        if (arr[i][1] === arr[0][1]) {
            max.push(arr[i])
        }

    }

    for(var i = arr.length-2; i>=0; i--) {
        if (arr[i][1] === arr[arr.length-1][1]) {
            min.push(arr[i])
        }
    }

    return [max,min]
}