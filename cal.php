<!DOCTYPE html>
<html lang = "en">
  <head>
    <title>Calendar</title>
    <link href="calendar.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  </head>
  <body><div id="page-body">
  <h1> Calendar </h1>
  <div class = "calendar">
  <button id = "logout_btn"> Logout </button>
  Username: <p id="ui-username"> </p>
    <div id="calendarDate">
      <select id="calendarMonth"> </select>
      </div>
      <select id="calendarYear"> </select>
      <input id="calendar-set" value= "Change" type= "button"/>
    <div id="calendar-border">
    </div>
    <div id="calEvent">
    </div>
  </div>
  <button id="nightMode" onclick="nightModeON()">Night Mode</button>

<div class = "login-screen">
<h1 class = "center">
Login
</h1>
<input type="text" id="username" placeholder="Username"/>
<input type="password" id="password" placeholder="Password"/>
<button id = "create_btn"> Create Account </button>

<input type="text" id="username_log" placeholder="Username" />
<input type="password" id="password_log" placeholder="Password" />
<button id= "login_btn">Log In</button>

</div>
<div class = "calendar">
<h1> Events This Month </h1>
Input username
<input type = "text" id = "user">
<button id = "show_event"> Show Events </button>
<div id = "events"> </div>
</div>
<div class = "calendar">
<h1> Event Sharing </h1>
<p> Enter event by tile you wish to share, user who you wish to share with, and your username </p>
<input type = "text" id = "sharedEvent" placeholder = "Share Event Title"/>
<input type = "text" id = "userShare" placeholder = "User Share" />
<button id = "share_event"> Share Event </button>
</div>

</body>
</html>
<script>

function displayEvents(){
  console.log("arrived");
  fetch('../Module5-Group/displayEvents.php', {                  //connect to php for account creation
    method: 'POST'
    })
  .then(res => res.json())
  .then(response => {
     console.log('Success:', JSON.stringify(response));
     console.log(response.name_array);
     console.log(response.date_array);
     console.log(response.username);
    var length = response.date_array.length;
    var i = 0;
    while(length --> 0){
      var date = new Date(response.date_array[i]);
      console.log("DATE ARRAY: " + date);
      var event_ = response.name_array[i];
      // console.log(date);
      // console.log(event_);
      
      var day = date.getDate();
      var month = date.getMonth();
      var year = date.getFullYear();
      console.log(localStorage);
      localStorage.setItem('year', year);
      localStorage.setItem('month', month);
      var obj = {};
      obj[day]=event_;
      // console.log(obj);
      var ex = localStorage.getItem("cal-" + month + "-" + year);
      ex = ex ? JSON.parse(ex) : {};
       if(!!ex){
        ex[day] = event_;
        localStorage.setItem("cal-" + month + "-" + year, JSON.stringify(ex));
      }
       else{
         localStorage.setItem("cal-" + month + "-" + year, JSON.stringify(obj));
       }
       console.log(localStorage);
      // console.log(ex);
      // Save back to localStorage
      //localStorage.setItem('myLunch', JSON.stringify(existing));
      
      // console.log(localStorage);
      //calendar.list();
      //JSON.stringify(calendar.data)
      //localStorage.setItem("cal-" + month + "-" + year, "{}");
      //console.log(localStorage.getItem("cal-" + month + "-" + year, "{}"));
      //calendar.data.push(localStorage.getItem("cal-" + month + "-" + year, "{}"));

      //localStorage.setItem(calendar.data, calendar.data + JSON.stringify({"date": date, "event": event.type}));
      
      //calendar.list();
      //localStorage.setItem("cal-" + response.date_array )
      i++;
    }
    calendar.list();
    // console.log(localStorage);
    //console.log(calendar.data)
  })
      // calendar.data[calendar.selectedDay] = document.getElementById("evt-details").value;     //display event on desired day
      // localStorage.setItem("cal-" + calendar.selectedMonth + "-" + calendar.selectedYear, JSON.stringify(calendar.data));   
      // calendar.list();
}
window.onload = function(){
  $(".calendar").hide();
  $(".login-screen").show();
  checkUser();
  localStorage.clear();
  displayEvents();
}
//code from https://webdevtrick.com/html-css-javascript-calendar/
let calendar= {
  months : ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"], // Month Names
  data : null, 
  selectedYear : 0,                     //year selected
  selectedDay : 0,                      //day selected
  selectedMonth : 0,                    //month selected
  monday : false,                       //week doesn't start on monday
  
  list : function () {
    calendar.selectedYear = parseInt(document.getElementById("calendarYear").value); // selected year
    calendar.selectedMonth = parseInt(document.getElementById("calendarMonth").value); // selected month
    
    let firstDayMonth = new Date(calendar.selectedYear, calendar.selectedMonth, 1).getDay(); // first day of the month
    let numDaysMonth = new Date(calendar.selectedYear, calendar.selectedMonth + 1, 0).getDate(); // number of days in selected month
    let lastDayMonth = new Date(calendar.selectedYear, calendar.selectedMonth, numDaysMonth).getDay(); // last day of the month
    calendar.data = localStorage.getItem("cal-" + calendar.selectedMonth + "-" + calendar.selectedYear);
    if (calendar.data == null)
    {
      localStorage.setItem("cal-" + calendar.selectedMonth + "-" + calendar.selectedYear, "{}");
      calendar.data = {};
    } 
    else
    {
      calendar.data = JSON.parse(calendar.data);
      // console.log("LOOK HERE: " + calendar.data);
    }

let calendarBoxes = [];   //array containing number of days of month
    if (firstDayMonth && calendar.monday != 1) {
      let blanks = firstDayMonth == 0 ? 7 : firstDayMonth;    //determine number of blanks at start of calendar
      for (let x = 1; x < blanks; x++) 
      { 
        calendarBoxes.push("x"); 
        }
    }

    for (let x = 1; x <= numDaysMonth; x++)     //append days of month to calendar
    { 
      calendarBoxes.push(x); 
      }

  if (lastDayMonth && !calendar.monday != 6)    //if its the last day of month and Monday isnt the 6th day
    {
      let blanks = lastDayMonth == 0 ? 6 : 6 - lastDayMonth;      //find how many blanks to display
      for (let x = 0; x < blanks; x++) 
      { 
        calendarBoxes.push("x"); 
        }
    }
  
    if (!calendar.monday && firstDayMonth != 0)   
    {
      for (let y = 0; y < firstDayMonth; y++) 
      { 
        calendarBoxes.push("x"); 
        }
    }
   
    if (lastDayMonth && calendar.monday != 0)           //determine number of blanks at end of month
    {
      let blanks = lastDayMonth == 6 ? 1 : 7 - lastDayMonth;
      for (let y = 0; y < blanks; y++) 
      { 
        calendarBoxes.push("x"); 
        }
    }
  
    let containerTable = document.createElement("table");
    let container = document.getElementById("calendar-border");
    container.innerHTML = "";
    containerTable.id = "calendar";
    container.appendChild(containerTable);

    // code from https://code-boxx.com/simple-pure-javascript-calendar-events/
    let calendarCell = null;    //first row with names of days of week
    let dayRow = document.createElement("tr");
    let days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    if (calendar.monday) 
    { 
      days.push(days.shift()); 
      }
    for (let d of days) 
    {
      calendarCell = document.createElement("td");        //draw cell of tables based on number of days
      calendarCell.innerHTML = d;
      dayRow.appendChild(calendarCell);
    }
    dayRow.classList.add("head");
    containerTable.appendChild(dayRow);

    let total = calendarBoxes.length;     //number of days in month
    dayRow = document.createElement("tr");      //draw rows based on number of days in month
    dayRow.classList.add("day");
    for (let x = 0; x < total; x++) {
      calendarCell = document.createElement("td");    
      if (calendarBoxes[x]== "x") 
      { 
        calendarCell.classList.add("blank");      //if no date, make it blank
        }
      else 
      {
        calendarCell.innerHTML = "<div class ='dd'>" + calendarBoxes[x] + "</div>";      //set equal to text
        if (calendar.data[calendarBoxes[x]]) {
          calendarCell.innerHTML = calendarCell.innerHTML + "<div class='evt'>" + calendar.data[calendarBoxes[x]] + "</div>";
        }
        calendarCell.addEventListener("click", function()
        {
          calendar.show(this);
        });
      }
      dayRow.appendChild(calendarCell);
      if ((x + 1) % 7 == 0 && x != 0) 
      {
        containerTable.appendChild(dayRow);
        dayRow = document.createElement("tr");
        dayRow.classList.add("day");
      }
    }
    calendar.close();
  },
  show : function (el) {

    //get prexisting data
    calendar.selectedDay = el.getElementsByClassName("dd")[0].innerHTML;

    //event form
    let eventForm = "<h1>" + (calendar.data[calendar.selectedDay] ? "Edit" : "Add") + " Event</h1>";
    eventForm += "<div id='evt-date'>" + calendar.selectedDay + " " + calendar.months[calendar.selectedMonth] + " " + calendar.selectedYear + "</div>";
    eventForm += "<textarea id='evt-details' required>" + (calendar.data[calendar.selectedDay] ? calendar.data[calendar.selectedDay] : "") + "</textarea>";
    var rightmonth = parseInt(calendar.selectedMonth)+1;
    var year = calendar.selectedYear + "-";
    var month = rightmonth + "-";
    var day = calendar.selectedDay;
    if(rightmonth < 10){
      rightmonth = "0" + rightmonth + "-";
    }
    if(calendar.selectedDay < 10){
      day = "0" + calendar.selectedDay;
    }
    var time = "00:00:00";
    let fullDate = year + rightmonth + day + " " + time;
    //let fullDate = (year.concat(month)).concat(day).concat();
        //let fullDate = year.concat(month).concat(day);
    eventForm = eventForm + "<input id = 'selectDay' type ='hidden'/>";
    eventForm = eventForm + "<input value ='Close' type ='button' onclick='cal.close()'/>";
    eventForm = eventForm + "<input value ='Delete' type ='button' onclick='deleteEvent()'/>";
    eventForm = eventForm + "<input value ='Save' type ='submit'  onclick = 'storeEvent()'/>";

   
    //append data entered in event form
    let displayForm = document.createElement("form");
    displayForm.addEventListener("submit", calendar.save);
    displayForm.innerHTML = eventForm;
    let container = document.getElementById("calEvent");
    container.innerHTML = "";
    container.appendChild(displayForm);
    document.getElementById("selectDay").value = fullDate;
  },

save : function (evt) {
    calendar.data[calendar.selectedDay] = document.getElementById("evt-details").value;     //display event on desired day
    localStorage.setItem("cal-" + calendar.selectedMonth + "-" + calendar.selectedYear, JSON.stringify(calendar.data));  
    // console.log(localStorage);
    calendar.list();
  },

  close : function () {
    document.getElementById("calEvent").innerHTML = "";
  }
};

window.addEventListener("load", function () {   //on load listen to selection and display calendar accordingly
  
  let now = new Date();
  let monthNow = now.getMonth();
  let yearNow = parseInt(now.getFullYear());
  let month = document.getElementById("calendarMonth"); //choose month selector
  for (let x = 0; x < 12; x++) {
    let option = document.createElement("option");
   option.value = x;
   option.innerHTML = calendar.months[x];
    if (x == monthNow) 
    { 
     option.selected = true; 
      }
    month.appendChild(option);
  }
  let year = document.getElementById("calendarYear");     //choose year option
  for (let x = yearNow-10; x <= yearNow + 10; x++) {
    let option = document.createElement("option");
   option.value = x;
   option.innerHTML = x;
    if (x == yearNow) 
    {
      option.selected = true; 
      }
    year.appendChild(option);
  }
  document.getElementById("calendar-set").addEventListener("click", calendar.list);   //draw calendar
  calendar.list();
});

//https://codeshack.io/basic-login-system-nodejs-express-mysql/

function accountAjax(){ //create account
  let username = document.getElementById("username").value;   //get values from html form
  let password = document.getElementById("password").value;
  const data = {username, password};
  // console.log("Username" + username + "password" + password);
  fetch('../Module5-Group/createUser.php', {                  //connect to php for account creation
    method: 'POST',
    body: JSON.stringify(data),
    headers: { 'content-type': 'application/json'}
    })
  .then(res => res.text())
  .then(response => console.log('Success:', JSON.stringify(response)))
  .catch(error => console.error('Error:',error))
}

function loginAjax(){ //login
  let username = document.getElementById("username_log").value;   //get values from html form
  let password = document.getElementById("password_log").value;
  //  console.log("Username" + username + "password" + password);
  const data = {
    "username_log": username,
    "password_log": password
    };
  fetch('../Module5-Group/loginUser.php', {                  //connect to php for account creation
    method: 'POST',
    body: JSON.stringify(data)
    })
  .then(res => res.json())
  .then((response) => {
    // console.log('Success:', JSON.stringify(response));
    if (response.success) {
      username = response.username;
      // console.log("Username: " + response.username);
        //user provided correct credentials, log user in
        //console.log(document.getElementById("ui-username"));
        document.getElementById('ui-username').innerText = response.username;
        $(".calendar").show();     //wrap elements in div tag with id = "calendar"
        $(".login-screen").hide(); //wrap elements in div tag with id = "login-screen"
        let userStore = document.getElementById("ui-username").value;
        localStorage.setItem("ui-username", username);
        displayEvents();
    } 
    else 
    {
        //user provided incorrect credentials
    $("#login-screen").show();
    $("#calendar").hide();
    }
    })
}
function storeEvent(){  //store event in database
  let selectedDay = document.getElementById("selectDay").value;
  let name = document.getElementById("evt-details").value;
  // console.log(selectedDay, name);
  const data = {
    "evt-details": name,
    "selectDay": selectedDay
    };
  fetch('../Module5-Group/storeEvent.php', {                  //connect to php for account creation
    method: 'POST',
    body: JSON.stringify(data),
    headers: { 'content-type': 'application/json'}
    })
  .then(res => res.text())
  .then(response => console.log('Success:', JSON.stringify(response)))
  .catch(error => console.error('Error:',error))
}

function deleteEvent(){   //delete events from database
      let eventDate = document.getElementById("selectDay").value;
  console.log(eventDate);
  const data = {
    "selectDay": eventDate
    };
  fetch('../Module5-Group/deleteEvent.php', {                  //connect to php for account creation
    method: 'POST',
    body: JSON.stringify(data),
    headers: { 'content-type': 'application/json'}
    })
  .then(res => res.text())
  .then(response => console.log('Success:', JSON.stringify(response)))
  .catch(error => console.error('Error:',error))
  delete calendar.data[calendar.selectedDay];
      localStorage.setItem("cal-" + calendar.selectedMonth + "-" + calendar.selectedYear, JSON.stringify(calendar.data));
      calendar.list();
}

function shareEvent(){  //share events with other users
  let shareTitle = document.getElementById("sharedEvent").value;   //get values from html form
  let shareUser = document.getElementById("userShare").value;
  
  const data = {
    "sharedEvent": shareTitle, 
    "userShare": shareUser
    };
  console.log("Title: " + shareTitle + "Share User: " + shareUser);
  fetch('../Module5-Group/shareEvent.php', {                  //connect to php for account creation
    method: 'POST',
    body: JSON.stringify(data),
    headers: { 'content-type': 'application/json'}
    })
  .then(res => res.text())
  .then(response => console.log('Success:', JSON.stringify(response)))
  .catch(error => console.error('Error:',error))
}

function showEvent(){     //click button to display events in chrono order
  let username = document.getElementById("user").value;   //get values from html form
  const data = {'user': username};
  console.log("Username" + username);
  fetch('../Module5-Group/showEvents.php', {                  //connect to php for account creation
    method: 'POST',
    body: JSON.stringify(data)
    })
  .then(res => res.json())    //res.text
  .then(response => printEvents(response));
  //.catch(error => console.error('Error:',error));
}

function printEvents(response){       //helper function to show event
  console.log(response);
  console.log(response.length);
  for(i = 0; i < response.length; i++){
    let eventTitle = response[i][0];
    let eventDate = response[i][1];
    let textNode = document.createTextNode(eventTitle + "-" + eventDate + "   ||||   " + i + ": ");
    console.log(textNode);
    document.getElementById('events').appendChild(textNode);
  }
}
function nightModeON(){     //sick as fuck
    var pageBody = document.body;
    pageBody.classList.toggle('dark-mode');
  }
function checkUser(){       //prevent refresh losing cache
  fetch('../Module5-Group/checkUser.php', {                  //connect to php for account creation
    method: 'POST'
    })
  .then(res => res.json())
  .then((response) => {
    console.log('Success:', JSON.stringify(response));
    if (response.success) {
      username = response.username;
        document.getElementById('ui-username').innerText = response.username;
        $(".calendar").show();     //wrap elements in div tag with id = "calendar"
        $(".login-screen").hide(); //wrap elements in div tag with id = "login-screen"
        let userStore = document.getElementById("ui-username").value;
        localStorage.setItem("ui-username", username);
    } 
});
}

function logout(){
  fetch('../Module5-Group/logout.php', {                 
    method: 'POST'
    })
  .then(res => res.json())
  .then((response) => {
    console.log('Success:', JSON.stringify(response));
    if (response.success) {
        $(".calendar").hide();     //wrap elements in div tag with id = "calendar"
        $(".login-screen").show(); //wrap elements in div tag with id = "login-screen"
    } 
})
}
//if (loggedIn) {
  //$("#calendar").show()
//} else {
  
//}
//this function gets called when we click add event
//function addEventButtonHandler() {
  
//}

document.getElementById('create_btn').addEventListener('click', accountAjax);     //listeners to redirect to desired functions
let create = document.getElementById('create_btn');
if(create){
  create.addEventListener('click', accountAjax);
}
let login = document.getElementById('login_btn');
if(login){
login.addEventListener("click", loginAjax);
}
let share = document.getElementById('share_event');
if(share){
  share.addEventListener('click', shareEvent);
}
let show = document.getElementById('show_event');
if(show){
  show.addEventListener('click', showEvent);
}
let logoutBtn = document.getElementById('logout_btn');
if(logoutBtn){
  logoutBtn.addEventListener('click', logout);
}

</script>