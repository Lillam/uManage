<!DOCTYPE HTML>
<html>
    <head>
        <title>Quotes</title>
        <style type="text/css">
            * {
                box-sizing: border-box;
            }
            html,
            body {
                margin: 0;
                padding: 0;
                background-color: #f1f1f1;
            }
            body {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
            }
            body .world {
                width: 710px;
                height: 710px;
                background-color: rgba(0,0,0,0.1);
                padding: 10px;
            }
            body .world .room_row {
                display: flex;
            }
            body .world .room_row .room {
                height: 100%;
                width: 100%;
                padding: 10px;
            }

            body .world .room_row .room > div {
                height: 100%;
                width: 100%;
            }

            body .world .room_row .room.active > div {
                border-color: rgba(0,0,0,0.2);
                border-style: dashed;
            }

            body .world .room_row .room.inactive {
                background-color: rgba(0,0,0,0.1);
            }
        </style>
    </head>
    <body>
        <div class="world"></div>
    </body>
    <!-- This is a link to jquery; this will want replacing to a Jquery package (version 3.*) -->
{{--    <script src="{{ asset('assets/vendor/uikit/jquery.js') }}"></script>--}}
{{--    <script type="text/javascript">--}}
{{--        class World {--}}
{{--            rooms = 100;--}}
{{--            rooms_per_row = 10;--}}
{{--            element = null;--}}
{{--            room_matrix = [];--}}

{{--            constructor(element) {--}}
{{--                this.element = element;--}}
{{--                this.generateRoomMatrix();--}}
{{--            }--}}

{{--            generateRoomMatrix() {--}}
{{--                var row = 0;--}}

{{--                for (let i = 0; i < this.rooms; i ++) {--}}
{{--                    // if we have reached the capacity of rooms per row; then we are going to increment the row; which--}}
{{--                    // will subsequently start appending rooms to the next row.--}}
{{--                    if (i % this.rooms_per_row === 0) {--}}
{{--                        row ++;--}}
{{--                    }--}}

{{--                    // we are going to want to instantiate the array (matrix) of rooms so that we can append a variety--}}
{{--                    // of rooms within this row... this doesn't really do anything other than give us an array to push--}}
{{--                    // rooms into.--}}
{{--                    if (typeof (this.room_matrix[row]) === "undefined") {--}}
{{--                        this.room_matrix[row] = [];--}}
{{--                    }--}}

{{--                    // we are going to just randomly draw the room for now... and randomly decide whether or not each--}}
{{--                    // entry of a row; can be a room or not, and this will be 0 or 1... this will randomly draw the base--}}
{{--                    // of the map; but we aren't going to stop here... we are going to want to then further proceed to--}}
{{--                    // iterate over the array to see if there is a direct path from the top, to bottom.--}}
{{--                    this.room_matrix[row].push(Math.floor(Math.random() * 2))--}}
{{--                }--}}

{{--                this.room_matrix = this.room_matrix.filter(--}}
{{--                    (v) => typeof v !== "undefined"--}}
{{--                );--}}
{{--            }--}}

{{--            generateRooms() {--}}
{{--                this.room_matrix.forEach((room_row) => {--}}
{{--                    var $row = $('<div class="room_row"></div>');--}}
{{--                    $row.css({--}}
{{--                        height: `${690 / (this.rooms / this.rooms_per_row)}px`--}}
{{--                    });--}}
{{--                    room_row.forEach((room) => {--}}
{{--                        var $room = $(`<div class="room ${room === 1 ? 'active' : 'inactive'}"><div></div></div>`);--}}
{{--                        $row.append($room);--}}
{{--                    });--}}

{{--                    this.element.append($row);--}}
{{--                });--}}
{{--            }--}}

{{--            generateRoom() {--}}

{{--            }--}}
{{--        }--}}

{{--        $(() => {--}}
{{--            const $body = $('body');--}}

{{--            (new World($body.find('.world'))).generateRooms();--}}
{{--        });--}}
{{--    </script>--}}
</html>