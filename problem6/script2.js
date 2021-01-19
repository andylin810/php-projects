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

    createBuildingUI(9,2)

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
        $(`.indicator-up-${floor}`).html('&#9650;')
        building.processRequest(request)
    })

    $('.button-down').on('click', function(){
        let floor = Number($(this).parent().attr('class'))
        let request = new Person(floor,1,-1)
        // $(this).css('backgroundColor','red')
        $(`.indicator-down-${floor}`).html('&#9660;')
        building.processRequest(request)
    })

    //animation
    $('.test').on('click', function(){
        var elem = $('.elevator')
        var pos = $('.elevator').position().top
        console.log(pos)
        var id = setInterval(frame, 10);
        function frame() {
          if (pos == 0) {
            clearInterval(id);
          } else {
            pos--; 
            elem.css('top',pos + 'px') 
          }
        }
    })

    $('.test1').on('click', moveElevator);
    

})


// function moveElevator(origin,dest,handler){
//     let count = origin+1
//     let end = dest
//     let a = setInterval(function(){
//         if(count <= end) {
//             handler(count)
//             count++
//         } else {
//             clearInterval(a)
//         }
//     },1000)
// }


function removeIndicator(floor) {
    document.querySelector(`#indicator-${floor}`).innerHTML = ''
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

function moveElevator(direction) {
    var elem = $('.elevator')
    let start = 0
    var pos = $('.elevator').position().top
    let id = direction? setInterval(moveUp, 20) : setInterval(moveDown, 20)

    function moveUp() {
        if( pos > 0 ) {
            if (start == 50) {
                clearInterval(id);
            } else {
                start++; 
                elem.css('top',pos - start + 'px') 
            }
        }
    }

    function moveDown() {
        if( pos < 400 ) {
            if (start == 50) {
                clearInterval(id);
            } else {
                start++; 
                elem.css('top',pos + start + 'px') 
            }
        }
    }
}

function updateElevator(start,elevator) {
    if (start == 50) {
        elevator.status === 1? elevator.currentFloor++ : elevator.currentFloor--
        elevator.time++
        elevator.landFloor(elevator.currentFloor)
        updateUIFromElevator(elevator)
        start = 0
    }
}

function createBuildingUI(floor,numOfElevator) {
    for(var ele = 1; ele <= numOfElevator; ele++) {
        let eleContainer = `<div class="ele-container test-container-${ele}"></div>`
        $('.elevator-container').append(eleContainer)
        $('.test-container-'+ele).css('height',floor * 50)
        for(var i = 1; i<=floor; i++) {
            let posTop = (floor-i) * 50
            let str = `<div class="floor" style="top: ${posTop}px;">
                            <span class="floor-num">${i}</span>
                            <span class='indicator up-indicator indicator-up-${i}'></span>
                            <span class='indicator down-indicator indicator-down-${i}'></span>
                        </div>`;
            $('.test-container-'+ele).append(str)
        }
        let elevator = `<div class="elevator elevator-${ele}"></div>`
        $('.test-container-'+ele).append(elevator)
        $(`.elevator-${ele}`).css('top',`${(floor-1)*50}`)
    }

}


