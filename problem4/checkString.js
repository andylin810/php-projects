let someStr = "adjkhq387!@#*("
let dic = {}


function reverseString(str,dic) {
    let newStr = ""
    for(var i = str.length-1; i >= 0; i--) {
        newStr+=(str[i]);
        if(str[i] in dic) {
            dic[str[i]]++
        } else {
            dic[str[i]] = 1
        }
    }
    return newStr
}

function insideOutString(str) {
    const length = str.length
    const middle = Math.floor(length / 2)
    const front = str.substring(0,middle)
    const back = str.substring(middle,length)
    let newStr = ''

    for(var i = 0; i < front.length; i++) {
        newStr += back[i]
        newStr += front[front.length-1-i]
    }
    if (back.length > front.length) {
        newStr += back[back.length-1]
    }
    return newStr

}

function validateString(str){
    // const regExp = /^(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[*@#$%^&+=]).*$/
    const regExp = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[~!@#$%^&*()_`{}<>])(?=.*[:";',./?]).*$/
    return regExp.test(str)
}

// console.log(someStr)
// reverseString(someStr,dic)
// insideOutString(someStr)
// // console.log(dic)
// console.log(validateString('dA#a;wq'))