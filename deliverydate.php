<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Date and Time Selection</title>
    <style>
        #uni_modal .modal-footer {
            display: none;
        }

        .comfirm {
            border: 1px solid #f1f1f1;
            background-color: #04AA6D;
            color: white;
            width: 200px;
            height: 30px;
            font-size: 15px;
            font-weight: bold;
            border-radius: 5px;
            margin-top: 30px;
        }

        .comfirm:hover {
            background-color: #0acc03;
        }

        .getdatetime {
            display: block;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            /* Center-align the content */
        }

        .fordate {
            margin-top: 40px;
            margin-left: -260px;
        }

        .fortime {
            margin-top: -79px;
            margin-right: -260px;
        }

        .datetime {
            width: 200px;
            height: 40px;
            border: 0px;
            border-bottom: 1px solid rgb(194, 194, 194);
        }

        .dt {
            font-size: 12px;
            font-family: 'Times New Roman', Times, serif;
            color: rgb(158, 158, 158);
        }
    </style>
</head>
<body>

<div class="getdatetime" id="getdatetime">
    <h3 style="text-align: center !important;">Tell Us When You'd Like It</h3>
    <p style="text-align: center !important;">Pick a date and time for your order</p>
    <div class="fordate">
        <p class="dt">Date</p>
        <select id="date" class="datetime">
        </select>
    </div>

    <div class="fortime">
        <p class="dt">Time</p>
        <select id="time" name="time" class="datetime">
        </select>
    </div>

    <button class="comfirm" id="comfirm" name="comfirm" onclick="savedatetime()">Confirm</button>
</div>

<script>
    function formatDate(date) {
        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();
        return day + "/" + month + "/" + year;
    }

    function getDay(date) {
        var day = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        return day[date.getDay()];
    }

    function getdayofweek(day) {
        var today = new Date();
        var dayofweek = today.getDay();
        var daytonext = (day + 7 - dayofweek) % 7;
        var nextday = new Date(today);
        nextday.setDate(today.getDate() + daytonext);
        return nextday;
    }

    var today = new Date();
    var tomorrow = new Date(today);
    tomorrow.setDate(today.getDate() + 1);
    var nextTomorrow = new Date(today);
    nextTomorrow.setDate(today.getDate() + 2);

    document.getElementById("date").innerHTML += "<option value='" + formatDate(today) + "'>" + "Today " + getDay(today) + " " + formatDate(today) + "</option>";
    document.getElementById("date").innerHTML += "<option value='" + formatDate(tomorrow) + "'>" + getDay(tomorrow) + " " + formatDate(tomorrow) + "</option>";
    document.getElementById("date").innerHTML += "<option value='" + formatDate(nextTomorrow) + "'>" + getDay(nextTomorrow) + " " + formatDate(nextTomorrow) + "</option>";

    var currentTime = new Date();
var currentHour = currentTime.getHours();
var currentMinutes = currentTime.getMinutes();
var selectTimeElement = document.getElementById("time");
selectTimeElement.innerHTML = ""; // Clear existing options

if (currentHour >= 17) {
    // If current time is 17:00 or later, start generating options from the current time
    for (var i = currentHour; i <= 22; i++) {
        var hourString = (i < 10 ? '0' + i : i);
        if (i === currentHour) {
            // If it's the current hour, start generating options from the current minute
            for (var j = currentMinutes; j < 60; j += 30) {
                var minuteString = (j < 10 ? '0' + j : j);
                selectTimeElement.innerHTML += "<option value='" + hourString + ":" + minuteString + "'>" + hourString + ":" + minuteString + " PM</option>";
            }
        } else {
            selectTimeElement.innerHTML += "<option value='" + hourString + ":00" + "'>" + hourString + ":00 PM</option>";
            selectTimeElement.innerHTML += "<option value='" + hourString + ":30" + "'>" + hourString + ":30 PM</option>";
        }
    }
} else {
    // If current time is before 17:00, start generating options from 17:00
    for (var i = 17; i <= 22; i++) {
        var hourString = (i < 10 ? '0' + i : i);
        selectTimeElement.innerHTML += "<option value='" + hourString + ":00" + "'>" + hourString + ":00 PM</option>";
        selectTimeElement.innerHTML += "<option value='" + hourString + ":30" + "'>" + hourString + ":30 PM</option>";
    }
}



   function savedatetime() {
        var selectedDate = document.getElementById("date").value;
        var selectedTime = document.getElementById("time").value;
        localStorage.setItem("selectedDate", selectedDate);
        localStorage.setItem("selectedTime", selectedTime);
        window.location.href = "index.php?page=home";

    };
</script>

</body>
</html>
