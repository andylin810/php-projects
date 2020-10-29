const { performance } = require('perf_hooks');

let arr = 
    [
        [1,0,1],
        [2,-2,-1],
        [3,0,0]
    ]

let arr3 = 
    [
        [2,3,4],
        [0,1,1],
        [3,4,4]
    ]

let newArr = [
    [1,3,5,9],
    [1,3,1,7],
    [4,3,9,7],
    [5,2,0,9]
]

let arr2 = [[1,2],[5,1],[1,1]]

function findDeterminant(matrix,size) {
    //base case
    if(size === 2) {
        return matrix[0][0] * matrix[1][1] - matrix[1][0] * matrix[0][1]
    }

    let total = 0

    for(var i = 0; i<size; i++) {
        let subMatrix = getSubMatrix(matrix,size,0,i)
        let sign = 1
        if (i % 2 === 1) sign *= -1 
        total += sign * matrix[0][i] * findDeterminant(subMatrix,size-1)
    }
    return total;

}


function getSubMatrix(matrix,size,r,c) {

    let arr = []
    for(var i = 0; i < size; i++ ) {
        if( i !== r) {
            let row = []
            for( var j = 0; j < size; j++) {
                if (j !== c ) row.push(matrix[i][j])
            }
            arr.push(row)
        }
    }
    return arr
}

function getInverseMatrix(matrix,size) {
    let arr = []
    let arr2 = []

    //matrix rows
    for(var row = 0; row<size; row++) {
        let r = []
        let sign = 1
        if (row % 2 === 1) sign *= -1
        //matrix columns
        for(var col = 0; col < size; col++) {
            let sign2 = sign
            if (col % 2 === 1) sign2 *= -1
            const subMatrix = getSubMatrix(matrix,size,row,col)
            r.push(sign2 * findDeterminant(subMatrix,size-1))
        }
        arr.push(r)
    }

    for(var i = 0; i<size; i++) {
        arr2[i] = arr[i].slice()
    }

    for(var i = 0; i<size; i++) {
        for(var j = 0; j < size; j++) {
            arr[i][j] = arr2[j][i]
        }
    }
    return arr;
}

function getFinalInverse(matrix) {
    let len = matrix.length
    let inverse = getInverseMatrix(matrix,len)
    let det = findDeterminant(matrix,len)

    for(var i = 0; i< len; i++) {
        for(var j = 0; j < len; j++) {
            inverse[i][j] /= det
        }
    }
    return inverse
}

function generateIdentityMatrix(size) {
    let matrix = []
    for(var i = 0; i<size; i++) {
        let row = []
        for(var j = 0; j<size; j++) {
            j===i? row.push(1) : row.push(0)
        }
        matrix.push(row)
    }
    return matrix
}

function getInverse(matrix) {
    let i = 0
    let j = 0
    const size = matrix.length
    let identity = generateIdentityMatrix(size)

    while (i<size && j < size) {
        let pivot = i
        for(var k = i+1; k<size; k++) {
            if(Math.abs(matrix[k][j]) > Math.abs(matrix[pivot][j])) {
                pivot = k
            }
        }

        if (matrix[pivot][j] !== 0 ) {
            swapRow(matrix,i,pivot)
            swapRow(identity,i,pivot) 

            let div = matrix[i][j]

            for (var index = 0; index < size; index ++) {
                matrix[i][index] /= div
                identity[i][index] /= div
            }

            for(var u = i+1; u < size; u++) {
                let multiplier = matrix[u][j]
                for ( var l = 0; l<size; l++) {
                    matrix[u][l] -= multiplier * matrix[i][l]
                    identity[u][l] -= multiplier * identity[i][l]
                }
            }
        } else {
            throw "Inverse matrix does not exist"
        }
        i++
        j++
    }

    j = size - 1
    i = size - 1

    while(i>=0 && j >= 0) {
        for (var row = i-1; row >= 0; row--) {
            let multiplier = matrix[row][j]
            for(var l = size-1; l>=0; l--) {
                matrix[row][l] -= multiplier * matrix[i][l]
                identity[row][l] -= multiplier * identity[i][l]
            }
        }
        i--
        j--
    }
    return identity
}

function generateRandomMatrices(size,shape) {
    let matrices = []
    for (var i = 0; i < size; i++) {
        let matrix = []
        for(var r = 0; r < shape; r++) {
            let row = []
            for(var c = 0; c < shape; c++) {
                let sign = Math.floor(Math.random() * 2)
                let num = Math.floor(Math.random() * 10)
                if (sign % 2 ===1) num *= -1
                row.push(num)
            }
            matrix.push(row)
        }
        matrices.push(matrix)
    }
    return matrices
}

function swapRow(matrix,row1,row2) {
    let rowCopy = matrix[row1].slice()
    matrix[row1] = matrix[row2]
    matrix[row2] = rowCopy
    return matrix
}

function multiplyMatrices(matrix1,matrix2) {
    let result = []
    if(matrix1[0].length !== matrix2.length) throw "multiplication of these matrices not possible"


    for(var i = 0; i< matrix1.length; i++) {
        let row = []
        for(var j = 0; j<matrix2[0].length; j++) {
            let num = 0
            for(var col = 0; col < matrix2.length; col++) {
                num += matrix1[i][col] * matrix2[col][j]
            }
            row.push(num)
        }
        result.push(row)
    }
    return result
}

function addMatrices(matrix1,matrix2) {
    const len = matrix1.length
    const width = matrix1[0].length
    let matrix = []
    if (len !== matrix2.length || width !== matrix2[0].length) {
        throw "matrices must be the same shape"
    }
    for(var i =0; i<len; i++) {
        let row = []
        for(var j = 0; j < len; j++) {
            row.push(matrix1[i][j] + matrix2[i][j])
        }
        matrix.push(row)
    }
    return matrix
}

function multiplyAllMatrices(matrices,operation) {
    let result = []
    for(var i = 0; i< matrices.length-1; i++) {
        if (result.length === 0) { 
            result = operation(matrices[i],matrices[i+1])
        } else {
            result = operation(result,matrices[i+1])
        }
    }
    return result
}

function dividematrices(matrix1,matrix2) {
    const inverse = getInverse(matrix2)
    return multiplyMatrices(matrix1,inverse)
}

class Fraction {
    constructor(numerator, denominator = 1, isPositive=true) {
        this.numerator = numerator
        this.denominator = denominator
        this.isPositive = isPositive
    }

    multiply(fraction) {
        this.isPositive = this.isPositive === fraction.isPositive
        this.numerator *= fraction.numerator
        this.denominator *= fraction.denominator
        this.simplify()
    }

    add(fraction) {
        if(this.isPositive === fraction.isPositive) {
            this.numerator = this.numerator*fraction.denominator + fraction.numerator*this.denominator
            this.denominator *= fraction.denominator
        } else {
            if(this.numerator*fraction.denominator > fraction.numerator*this.denominator) {
                this.numerator = this.numerator*fraction.denominator - fraction.numerator*this.denominator
                this.denominator *= fraction.denominator
            } else {
                this.isPositive = !this.isPositive
                this.numerator = fraction.numerator*this.denominator - this.numerator*fraction.denominator
                this.denominator *= fraction.denominator
            }
        }
        this.simplify()
    }

    simplify() {
        for(var i = this.numerator; i>1; i--) {
            if(this.denominator%i === 0 && this.numerator % i ===0) {
                this.numerator /= i
                this.denominator /= i
                break
            }
        }
    }

    printFraction() {
        if(this.denominator === 1) return this.isPositive? `${this.numerator}` : `-${this.numerator}`
        return this.isPositive? `${this.numerator}/${this.denominator}` : `-${this.numerator}/${this.denominator}`
    }

    clone() {
        let copy = Object.assign(Object.create(Object.getPrototypeOf(this)), this)
        return copy
    }
}

// console.log(findDeterminant(arr,3))
// console.log(findDeterminant(arr2,2))
// console.log(findDeterminant(newArr,4))
// console.log(getInverseMatrix(arr,3))
// console.log(getInverseMatrix(newArr,4))

//calculate time
// let matrices = generateRandomMatrices(10000,50)
// var t0 = performance.now()

// let result = multiplyAllMatrices(matrices,addMatrices)

// var t1 = performance.now()

// //multiply time
// var t2 = performance.now()

// let result2 = multiplyAllMatrices(matrices,multiplyMatrices)

// var t3 = performance.now()

// //divisiom time
// var t4 = performance.now()

// let result3 = multiplyAllMatrices(matrices,dividematrices)

// var t5 = performance.now()

// console.log("adding matrices took " + (t1 - t0) + " milliseconds.")
// console.log("multiplying matrices took " + (t3 - t2) + " milliseconds.")
// console.log("dividing matrices took " + (t5 - t4) + " milliseconds.")

// let a = getInverse(arr3)
// console.log(a)



try {
    let a = addMatrices(arr2,arr)
    console.log(a)
} catch(err) {
    console.log(err)
}

// let inverse = getFinalInverse(arr3)
// let matrix = divideMatrix(arr,arr3)
// console.log(inverse)
// console.log(matrix)


// console.log(result)

// let a = new Fraction(4,1,true)
// let c = new Fraction(5,1,false)
// let d = new Fraction(5,1,true)
// let b = new Fraction(2,3)

// let copy = a.clone()

// a.add(c)

// b.multiply(d)
// a.add(d)

// console.log(b)
// console.log(copy)
// console.log(a)