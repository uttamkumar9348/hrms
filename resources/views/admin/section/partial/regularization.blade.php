<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            margin-right: 2px;
            width: fit-content;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        #myBtn:hover {
            cursor: pointer;
        }

        /* h2 {
            margin: 0;
            padding: 10px;
            text-align: center;
            color: #333;
        } */
        .modal-heading {
            display: flex;
            justify-content: center;
            /* Horizontal center */
            align-items: center;
            font-size: 28px;
        }
        .modal-heading,h2 {
           
            font-size: 28px;
        }
    </style>
</head>

<body>
    <li class="nav-item {{ request()->routeIs('admin.tadas.*')  ? 'active' : '' }}">
        <a id="myBtn" class="nav-link">

            <i class="link-icon" data-feather="file-text"></i>
            <span class="link-title">Regularization</span>
        </a>
    </li>

    <div id="myModal" class="modal">

        <!-- Modal content -->
        <!-- <div class="modal-content"> -->
        <div class="col-xxl-3 col-xl-4 d-flex m-auto">
            <div class="card w-100">
                <div class="modal-heading">
                    <h2>Regularization</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="card-body text-center clock-display">

                    <div id="clockContainer" class="mb-3">
                        <div id="hour"></div>
                        <div id="minute"></div>
                        <div id="second"></div>
                    </div>

                    <p id="date" class="text-primary fw-bolder mb-3"></p>


                    <div class="check-text d-flex align-items-center justify-content-around">
                        <span>Check In At<p class="text-success fw-bold h5" id="checkInTime">{{$viewCheckIn}} </p></span>
                        <span>Check Out At<p class="text-danger fw-bold h5" id="checkOutTime">{{$viewCheckOut}} </p></span>
                    </div>
                    <div class="punch-btn mt-2 mb-2 d-flex align-items-center justify-content-around">
                        <button href="{{route('admin.dashboard.takeAttendance','checkIn')}}" class="btn btn-lg btn-success  {{ $checkInAt ? 'd-none' : ''}}" id="startWorkingBtn" data-audio="{{asset('assets/audio/beep.mp3')}}">
                            Punch In
                        </button>
                        <button href="{{route('admin.dashboard.takeAttendance','checkOut')}}" class="btn btn-lg btn-danger {{ $checkOutAt ? 'd-none' : ''}}" id="stopWorkingBtn" data-audio="{{asset('assets/audio/beep.mp3')}}">
                            Regularize
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!-- </div> -->

    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the button that opens the modal
        var btn = document.getElementById("myBtn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal 
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>

</html>