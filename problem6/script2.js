jQuery(function() { 
    let currentFloor = 1
    let dest = []
    let building = new Building(9,2)

    let func = (floor) => {
        $('.elevator-status').text(floor)
    }
    let func2 = (floor) => {
        $('.elevator2-status').text(floor)
    }

    $('.elevator-button button').on('click', function(){
        let floor = Number($(this).text())
        $(this).css('backgroundColor','red')
        // moveElevator(currentFloor,floor,func)

        let request = new Person(floor,9,1)
        building.processRequest(request)
        
        // elevator.move()
    })

    // $('.elevator2-button button').on('click', function(){
    //     let floor = Number($(this).text())
    //     $(this).css('backgroundColor','red')
    //     // moveElevator(currentFloor,floor,func2)
    //     let request = new Person(floor,8,1)
    //     building.processRequest(request)
    // })

    // $('.elevator2-button .button-up').on('click', function(){
    //     let floor = Number($(this).parent().attr('class'))
    //     let request = new Person(floor,9,1)
    //     // $(this).css('backgroundColor','red')
    //     building.processRequest(request)
    // })

    // $('.elevator2-button .button-down').on('click', function(){
    //     let floor = Number($(this).parent().attr('class'))
    //     let request = new Person(floor,1,-1)
    //     // $(this).css('backgroundColor','red')
    //     building.processRequest(request)
    // })


    $('.button-up').on('click', function(){
        let floor = Number($(this).parent().attr('class'))
        let request = new Person(floor,9,1)
        // $(this).css('backgroundColor','red')
        building.processRequest(request)
    })

    $('.button-down').on('click', function(){
        let floor = Number($(this).parent().attr('class'))
        let request = new Person(floor,1,-1)
        // $(this).css('backgroundColor','red')
        building.processRequest(request)
    })

})


function moveElevator(origin,dest,handler){
    let count = origin+1
    let end = dest
    let a = setInterval(function(){
        if(count <= end) {
            handler(count)
            count++
        } else {
            clearInterval(a)
        }
    },1000)
}


function updateUIFromElevator(elevator) {
    let id = elevator.id
    let direction = elevator.status
    $(`.elevator${id}-status`).text(elevator.currentFloor)
    $(`#ele${id} .arrow`).removeClass('moving')
    switch(direction) {
        case 1:
            $(`#ele${id} .up`).addClass('moving')
            break
        case -1:
            $(`#ele${id} .down`).addClass('moving')
            break
    }

    let str = ""
    let count = 1
    for(var key in elevator.taskQueue) {
        for(var i = 0; i<elevator.taskQueue[key].length; i++) {
            if(typeof(elevator.taskQueue[key][i]) == 'number') {
                str += `<div>Request ${count}: From Floor: current, To Floor: ${key}</div>`
            } else {
                str += `<div>Request ${count}: From Floor: ${elevator.taskQueue[key][i].from}, To Floor: ${elevator.taskQueue[key][i].dest}</div>`
            }
            count++
        }
    }

    let str2 = ''
    let count2 = 1
    for(var i = 0; i<elevator.building.finishedRequests.length; i++) {
        str2 += `<div>Finished Request ${count2}: From Floor: ${elevator.building.finishedRequests[i].from}, To Floor: ${elevator.building.finishedRequests[i].dest},
         Total Wait Time: ${elevator.building.finishedRequests[i].timeEnd - elevator.building.finishedRequests[i].timeStart}</div>`
         count2++
    }


    $(`.ele${id}-request`).html(str)
    $(`.finished-request`).html(str2)
}