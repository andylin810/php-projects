class Elevator{
    constructor(building,id){
        this.id = id
        this.building = building
        this.status = 0
        this.currentFloor = 1
        this.taskQueue = {}
        this.oppositeTaskQueue = {}
        this.oppositeDirectionDestFloor = 0
        this.currentDirectiondestFloor = 0
        this.time = 0
    }
    fetch(person){
        if(this.status===0) {
            this.taskQueue[person.from] = 1
            if(this.currentFloor !== person.from) {
                this.status = this.currentFloor>person.from ? -1 : 1
                this.currentDirectiondestFloor = person.from
                this.move()
            }
        } else if(person.direction === this.status) {
            this.taskQueue[person.from] = 1
            if(!(person.from in this.taskQueue)) {
            }
            if (person.from > this.currentDirectiondestFloor) {
                this.currentDirectiondestFloor = person.from
            }

            // switch(this.status) {
            //     case 1:




            //     case -1:


            //     default:
            //         break

            // }


        } else {
            this.oppositeTaskQueue[person.from] = 1
            if(person.direction === 1) {
                if (person.from > this.oppositeDirectionDestFloor) 
                    this.oppositeDirectionDestFloor = person.from
            } else {
                if (person.from < this.oppositeDirectionDestFloor || this.oppositeDirectionDestFloor === 0) 
                    this.oppositeDirectionDestFloor = person.from
            }
        }

    }

    assignRequest(person) {
        person.timeStart = this.time
        //elevator currently idle
        if(this.status === 0) {
            if(this.currentFloor !== person.from) {
                // this.taskQueue[person.from*person.direction] = person
                this.addTask(person.from,this.taskQueue)
                this.status = this.currentFloor>person.from ? -1 : 1
                this.assignDestinationFloor(person.from)
                this.move()
            } else {
                //TODO
                this.status = person.direction
                // this.taskQueue[person.dest] = person
                this.addTask(person,this.taskQueue)
                this.currentDirectiondestFloor = person.dest
                delete this.building.requests[this.currentFloor]
                delete this.building.requestInfo[this.currentFloor]
                this.move()
            }
            //elevator currently moving
        } else {
            //when elevator is moving up
            if(this.status === 1) {
                //when request is also going up
                if(this.status === person.direction) {
                    //when request floor is higher than elevator current floor
                    if(person.from > this.currentFloor){
                        // this.taskQueue[person.from*person.direction] = person
                        this.addTask(person.from,this.taskQueue)
                        this.assignDestinationFloor(person.from)
                    } else {
                        //when request floor is lower than the elevator current floor
                        // this.oppositeTaskQueue[person.from*person.direction] = person
                        this.addTask(person.from,this.oppositeTaskQueue)
                        this.assignDestinationFloor(person.from,true)
                    }
                    //request going down, different direction as the elevator
                } else {
                    if(person.from > this.currentDirectiondestFloor) {
                        // this.taskQueue[person.from*person.direction] = person
                        this.addTask(person.from,this.taskQueue)
                        this.assignDestinationFloor(person.from)
                    } else {
                        // this.oppositeTaskQueue[person.from*person.direction] = person
                        this.addTask(person.from,this.oppositeTaskQueue)
                        this.assignDestinationFloor(person.from,true)
                    }

                }
                //when elevator is moving down
            } else {                
                //when request is also going down
                if(this.status === person.direction) {
                    //when request floor is lower than elevator current floor
                    if(person.from < this.currentFloor){
                        // this.taskQueue[person.from*person.direction] = person
                        this.addTask(person.from,this.taskQueue)
                        this.assignDestinationFloor(person.from)
                    } else {
                        //when request floor is higher than the elevator current floor
                        // this.oppositeTaskQueue[person.from*person.direction] = person
                        this.addTask(person.from,this.oppositeTaskQueue)
                        this.assignDestinationFloor(person.from,true)
                    }
                } else {
                    if(person.from < this.currentDirectiondestFloor) {
                        // this.taskQueue[person.from*person.direction] = person
                        this.addTask(person.from,this.taskQueue)
                        this.assignDestinationFloor(person.from)
                    } else {
                        // this.oppositeTaskQueue[person.from*person.direction] = person
                        this.addTask(person.from,this.oppositeTaskQueue)
                        this.assignDestinationFloor(person.from,true)
                    }

                }

            }


        }
    }
    
    assignDestinationFloor(floor,opposite=false) {
        switch (this.status){
            case 1:
                if(opposite) {
                    if(floor < this.oppositeDirectionDestFloor || this.oppositeDirectionDestFloor === 0) {
                        this.oppositeDirectionDestFloor = floor
                    }
                } else {
                    if(floor > this.currentDirectiondestFloor)
                        this.currentDirectiondestFloor = floor
                }
                break
            case -1:
                if(opposite) {
                    if(floor > this.oppositeDirectionDestFloor) {
                        this.oppositeDirectionDestFloor = floor
                    }
                } else {
                    if(floor < this.currentDirectiondestFloor || this.currentDirectiondestFloor===0)
                        this.currentDirectiondestFloor = floor
                }
                break
            default:
                this.currentDirectiondestFloor = floor
                break      
        }
    }

    clearTask(floor) {
        if(this.taskQueue[floor].length > 1) {
            const index = this.taskQueue[floor].indexOf(1)
            this.taskQueue[floor].splice(index, 1);
        } else {
            delete this.taskQueue[floor]
        } 
    }

    pickUp(floor){
        if(this.building.requests[floor] === this.id){
            // removeIndicator(floor)
            let person = this.building.requestInfo[floor]
            // this.clearTask(floor)

            //TODO
            // delete this.taskQueue[floor]
            
            
            if(this.status === person.direction || this.currentDirectiondestFloor === floor) {
                // delete this.taskQueue[floor]

                if(this.taskQueue[floor].includes(1)) {
                    if(this.taskQueue[floor].length > 1) {
                        const index = this.taskQueue[floor].indexOf(1)
                        this.taskQueue[floor].splice(index, 1)
                    } else {
                        delete this.taskQueue[floor]
                    }
                } 

                if(Object.keys(this.taskQueue).length === 0) 
                    this.currentDirectiondestFloor = 0

                //new stuff
                if(person.direction !== this.status) {
                    this.taskQueue = this.oppositeTaskQueue
                    this.oppositeTaskQueue = {}
                    this.currentDirectiondestFloor = this.oppositeDirectionDestFloor
                    this.oppositeDirectionDestFloor = 0
                }
                //TODO
                // person.dest in this.taskQueue? this.taskQueue.push(person) : this.taskQueue[person.dest] = [person]
                this.addTask(person,this.taskQueue)



                this.status = person.direction
                this.assignDestinationFloor(person.dest)
                
                delete this.building.requests[floor]
                delete this.building.requestInfo[floor]
            } 
            // else {
            //     // this.clearTask(floor)
            //     this.addTask(floor,this.oppositeTaskQueue)
            //     this.oppositeDirectionDestFloor = this.getCurrentDestination(this.status * -1)
            // }
        } 
    }

    dropOff(floor) {
        if(floor in this.taskQueue) {
            for(var i = 0; i<this.taskQueue[floor].length; i++) {
                if(this.taskQueue[floor][i] !== 1) {
                    this.taskQueue[floor][i].timeEnd = this.time
                    this.building.finishedRequests.push(this.taskQueue[floor][i])
                }
            } 

            if(this.taskQueue[floor].includes(1)) {
                this.taskQueue[floor] = [1]
            } else {
                delete this.taskQueue[floor]
            }

            let totalTask = Object.keys(this.taskQueue).length
            let oppositeTask = Object.keys(this.oppositeTaskQueue).length
            if(totalTask===0 && this.currentFloor === this.currentDirectiondestFloor){
                if (oppositeTask !== 0) {
                    this.taskQueue = this.oppositeTaskQueue
                    this.oppositeTaskQueue = {}
                    this.currentDirectiondestFloor = this.oppositeDirectionDestFloor
                    this.oppositeDirectionDestFloor = 0
                    this.status *= -1
                } else {
                    this.status = 0
                    this.currentDirectiondestFloor = 0
                }  
                //TODO
            } else if (totalTask!==0 && this.currentFloor === this.currentDirectiondestFloor) {
                if (oppositeTask !== 0) {
                    for(var key in this.taskQueue) {
                        this.addTask(key,this.oppositeTaskQueue)
                    }
                    this.taskQueue = this.oppositeTaskQueue
                    this.oppositeTaskQueue = {}
                    this.currentDirectiondestFloor = this.getCurrentDestination(this.status)
                    this.status *= -1
                } else {
                    this.currentDirectiondestFloor = this.getCurrentDestination(this.status)
                    this.status *= -1
                } 
            }
        }

    }

    // move() {
    //     var self = this
    //     var a = setInterval(function() {
    //         if(self.currentFloor !== self.currentDirectiondestFloor && self.currentDirectiondestFloor!==0) {
    //             console.log(self.currentDirectiondestFloor)
    //             console.log(self.taskQueue)

    //             //animation elevator
    //             let direction = self.status > 0? true : false
    //             moveElevator(direction)

    //             self.status === 1? self.currentFloor++ : self.currentFloor--
    //             self.time++
    //             self.landFloor(self.currentFloor)
    //             updateUIFromElevator(self)

                
    //         } else {
    //             clearInterval(a)  
    //         }
    //     },1000)

    //     // var myFunction = function() {
    //     //     counter *= 10;
    //     //     setTimeout(myFunction, counter);
    //     // }
    //     // setTimeout(myFunction, counter);
    // }

    move() {
        var self = this
        // var a = setInterval(function() {
        //     if(self.currentFloor !== self.currentDirectiondestFloor && self.currentDirectiondestFloor!==0) {
        //         console.log(self.currentDirectiondestFloor)
        //         console.log(self.taskQueue)

        //         //animation elevator
        //         let direction = self.status > 0? true : false
        //         moveElevator(direction)

        //         self.status === 1? self.currentFloor++ : self.currentFloor--
        //         self.time++
        //         self.landFloor(self.currentFloor)
        //         updateUIFromElevator(self)

                
        //     } else {
        //         clearInterval(a)  
        //     }
        // },1000)

        // let destination = (9 - self.currentDirectiondestFloor) * 50
        let eleNum = this.id + 1
        var elem = $('.elevator-' + eleNum)
        let start = 0
        var pos = $('.elevator-'+eleNum).position().top

        let id = setInterval(moveUp, 20)
        // clearInterval(id)

        function moveUp() {
            if (self.currentFloor === self.currentDirectiondestFloor || self.currentDirectiondestFloor===0) {
                clearInterval(id);
            } else {
                start++
                if (self.status > 0) {
                    pos--
                    elem.css('top',pos + 'px')
                } else {
                    pos++
                    elem.css('top',pos + 'px')
                }
                if (start == 50) {
                    self.status === 1? self.currentFloor++ : self.currentFloor--
                    self.time++
                    self.landFloor(self.currentFloor)
                    updateUIFromElevator(self)
                    start = 0
                }
            }    
        }
    }

    landFloor(floor) {
        this.pickUp(floor)
        this.dropOff(floor)
    }

    distanceToPerson(person) {
        if(this.status ===0) 
            return Math.abs(person.from - this.currentFloor)

        //new 
        if(person.direction === this.status) {
            if (this.status === 1) {
                if (person.from > this.currentFloor) 
                    return person.from - this.currentFloor
            } else {
                if (person.from < this.currentFloor)
                    return this.currentFloor - person.from
            }
        }
        let a = Math.abs(this.currentDirectiondestFloor - this.currentFloor)
        let b = Math.abs(person.from - this.currentDirectiondestFloor)
        return a+b

    }

    getCurrentDestination(direction){
        let count = 0
        let curr = 0
        for(var key in this.taskQueue){
            key = Math.abs(key)
            if(count === 0) {
                curr = key
            }
            else {
                if(direction === 1) {
                    if(key > curr) curr = key
                } else {
                    if(key < curr) curr = key
                }
            }
            count++
        }
        return curr
    }

    addTask(task,queue,to=true){
        if(typeof(task) == 'number') {
            task in queue? queue[task].push(task) : queue[task] = [1]
        } else {
            if (to) {task.dest in queue? queue[task.dest].push(task) : queue[task.dest] = [task]}
            else {task.from in queue? queue[task.dest].push(task) : queue[task.from] = [task]}
        }
    }

}

class Building{
    constructor(floor,numOfElevator){
        this.floor = floor
        this.elevators = []
        this.requests = {}
        this.requestInfo = {}
        this.finishedRequests = []
        for(var i = 0; i<numOfElevator; i++){
            this.elevators.push(new Elevator(this,i))
        }
    }

    processRequest(request){
        let floor = 100
        let elevatorId = -1
        for(var i = 0; i<this.elevators.length;i++){
            let distance = this.elevators[i].distanceToPerson(request)
            if(distance < floor) {
                floor = distance
                elevatorId = i
            }
        }
        this.requests[request.from] = elevatorId
        this.requestInfo[request.from] = request
        this.elevators[elevatorId].assignRequest(request)
        console.log(elevatorId)
    }


}

class Person{
    constructor(from,dest,direction){
        this.from = from
        this.dest = dest
        this.direction = direction
        this.timeStart = 0
        this.timeEnd = 0
    }
}

// let elevator = new Elevator()
// let person = new Person(3,7,1)
// elevator.fetch(person)

// //picked up person at floor 3
// elevator.currentFloor = 3
// elevator.pickUp(person)

// //drop off person at floor 7
// elevator.currentFloor = 7
// elevator.dropOff(7)

// console.log(elevator)



// let building = new Building(5,2)
// let request = new Person(3,4,1)
// building.processRequest(request)

// // building.elevators[0].currentFloor = 4
// // building.elevators[0].pickUp(request)
// // request = new Person(4,5,1)
// // building.processRequest(request)
// building.elevators[0].move()
// console.log(typeof 2)
