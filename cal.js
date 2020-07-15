let cal = {
    /* [PROPERTIES] */
    months : ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"], // Month Names
    data : null, // Events for the selected period
    selectedYear : 0, // Current selected year
    monday : false, // Week start on Monday?
    selectedDay : 0, // Current selected day
    selectedMonth : 0, // Current selected month
    
  
    /* [FUNCTIONS] */
    list : function () {
    // cal.list() : draw the calendar for the given month
  
      // BASIC CALCULATIONS
      // Note - Jan is 0 & Dec is 11 in JS.
      // Note - Sun is 0 & Sat is 6
      cal.selectedMonth = parseInt(document.getElementById("cal-mth").value); // selected month
      cal.selectedYear = parseInt(document.getElementById("cal-yr").value); // selected year
      let numDaysMonth = new Date(cal.selectedYear, cal.selectedMonth + 1, 0).getDate(), // number of days in selected month
          firstDayMonth = new Date(cal.selectedYear, cal.selectedMonth, 1).getDay(), // first day of the month
          lastDayMonth = new Date(cal.selectedYear, cal.selectedMonth, numDaysMonth).getDay(); // last day of the month
  
      // LOAD DATA FROM LOCALSTORAGE
      cal.data = localStorage.getItem("cal-" + cal.selectedMonth + "-" + cal.selectedYear);
      if (cal.data==null) {
        localStorage.setItem("cal-" + cal.selectedMonth + "-" + cal.selectedYear, "{}");
        cal.data = {};
      } else {
        cal.data = JSON.parse(cal.data);
      }
  
      // DRAWING CALCULATIONS
      // Determine the number of blank calendarBoxes before start of month
      let calendarBoxes = [];
      if (cal.monday && firstDayMonth != 1) {
        let blanks = firstDayMonth == 0 ? 7 : firstDayMonth ;
        for (let x = 1; x < blanks; x++) 
        { 
          calendarBoxes.push("b"); 
          }
      }
      if (!cal.monday && firstDayMonth != 0) 
      {
        for (let y = 0; y < firstDayMonth; y++) 
        { 
          calendarBoxes.push("b"); 
          }
      }
  
      // Populate the days of the month
      for (let x = 1; x <= numDaysMonth; x++) 
      { 
        calendarBoxes.push(x); 
        }
  
      // Determine the number of blank calendarBoxes after end of month
      if (cal.monday && lastDayMonth != 0) 
      {
        let blanks = lastDayMonth==6 ? 1 : 7-lastDayMonth;
        for (let y = 0; y < blanks; y++) 
        { 
          calendarBoxes.push("b"); 
          }
      }
      if (!cal.monday && lastDayMonth != 6) 
      {
        let blanks = lastDayMonth==0 ? 6 : 6-lastDayMonth;
        for (let x = 0; x < blanks; x++) 
        { 
          calendarBoxes.push("b"); 
          }
      }
  
      // DRAW HTML
      // Container & Table
      let container = document.getElementById("cal-container"),
          cTable = document.createElement("table");
      cTable.id = "calendar";
      container.innerHTML = "";
      container.appendChild(cTable);
  
      // First row - Days
      let dayRow = document.createElement("tr"),
          cCell = null,
          days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
      if (cal.monday) 
      { 
        days.push(days.shift()); 
        }
      for (let d of days) 
      {
        cCell = document.createElement("td");
        cCell.innerHTML = d;
        dayRow.appendChild(cCell);
      }
      dayRow.classList.add("head");
      cTable.appendChild(dayRow);
  
      // Days in Month
      let total = calendarBoxes.length;
      dayRow = document.createElement("tr");
      dayRow.classList.add("day");
      for (let i=0; i<total; i++) {
        cCell = document.createElement("td");
        if (calendarBoxes[i]=="b") { cCell.classList.add("blank"); }
        else {
          cCell.innerHTML = "<div class='dd'>"+calendarBoxes[i]+"</div>";
          if (cal.data[calendarBoxes[i]]) {
            cCell.innerHTML += "<div class='evt'>" + cal.data[calendarBoxes[i]] + "</div>";
          }
          cCell.addEventListener("click", function(){
            cal.show(this);
          });
        }
        dayRow.appendChild(cCell);
        if (i!=0 && (i+1)%7==0) {
          cTable.appendChild(dayRow);
          dayRow = document.createElement("tr");
          dayRow.classList.add("day");
        }
      }
  
      // REMOVE ANY ADD/EDIT EVENT DOCKET
      cal.close();
    },
    show : function (el) {
    // cal.show() : show edit event docket for selected day
    // PARAM el : Reference back to cell clicked
  
      // FETCH EXISTING DATA
      cal.selectedDay = el.getElementsByClassName("dd")[0].innerHTML;
  
      // DRAW FORM
      let tForm = "<h1>" + (cal.data[cal.selectedDay] ? "EDIT" : "ADD") + " EVENT</h1>";
      tForm += "<div id='evt-date'>" + cal.selectedDay + " " + cal.months[cal.selectedMonth] + " " + cal.selectedYear + "</div>";
      tForm += "<textarea id='evt-details' required>" + (cal.data[cal.selectedDay] ? cal.data[cal.selectedDay] : "") + "</textarea>";
      tForm += "<input type='button' value='Close' onclick='cal.close()'/>";
      tForm += "<input type='button' value='Delete' onclick='cal.del()'/>";
      tForm += "<input type='submit' value='Save'/>";
  
      // ATTACH
      let eForm = document.createElement("form");
      eForm.addEventListener("submit", cal.save);
      eForm.innerHTML = tForm;
      let container = document.getElementById("cal-event");
      container.innerHTML = "";
      container.appendChild(eForm);
    },
  
    close : function () {
    // cal.close() : close event docket
  
      document.getElementById("cal-event").innerHTML = "";
    },
  
    save : function (evt) {
    // cal.save() : save event
  
      evt.stopPropagation();
      evt.preventDefault();
      cal.data[cal.selectedDay] = document.getElementById("evt-details").value;
      localStorage.setItem("cal-" + cal.selectedMonth + "-" + cal.selectedYear, JSON.stringify(cal.data));
      cal.list();
    },
  
    del : function () {
    // cal.del() : Delete event for selected date
  
      if (confirm("Remove event?")) {
        delete cal.data[cal.selectedDay];
        localStorage.setItem("cal-" + cal.selectedMonth + "-" + cal.selectedYear, JSON.stringify(cal.data));
        cal.list();
      }
    }
  };
  
  // INIT - DRAW MONTH & YEAR SELECTOR
  window.addEventListener("load", function () {
    // DATE NOW
    let now = new Date(),
        nowMth = now.getMonth(),
        nowYear = parseInt(now.getFullYear());
  
    // APPEND MONTHS SELECTOR
    let month = document.getElementById("cal-mth");
    for (let i = 0; i < 12; i++) {
      let opt = document.createElement("option");
      opt.value = i;
      opt.innerHTML = cal.months[i];
      if (i==nowMth) { opt.selected = true; }
      month.appendChild(opt);
    }
  
    // APPEND YEARS SELECTOR
    // Set to 10 years range. Change this as you like.
    let year = document.getElementById("cal-yr");
    for (let i = nowYear-10; i<=nowYear+10; i++) {
      let opt = document.createElement("option");
      opt.value = i;
      opt.innerHTML = i;
      if (i==nowYear) { opt.selected = true; }
      year.appendChild(opt);
    }
  
    // START - DRAW CALENDAR
    document.getElementById("cal-set").addEventListener("click", cal.list);
    cal.list();
  });
  
  //LOGIN
  //https://codeshack.io/basic-login-system-nodejs-express-mysql/
  
  function accountAjax(){
    let username = document.getElementById("username").value;   //get values from html form
    let password = document.getElementById("password").value;
    const data = {username, password};
    console.log("Username" + username + "password" + password);
    fetch('../Module5-Group/createUser.php', {                  //connect to php for account creation
      method: 'POST',
      body: JSON.stringify(data),
      headers: { 'content-type': 'application/json'}
      })
    .then(res => res.text())
    .then(response => console.log('Success:', JSON.stringify(response)))
    .catch(error => console.error('Error:',error))
  }
  
  function loginAjax(){
    let username = document.getElementById("username_log").value;   //get values from html form
    let password = document.getElementById("password_log").value;
     console.log("Username" + username + "password" + password);
    const data = {username, password};
    fetch('../Module5-Group/loginUser.php', {                  //connect to php for account creation
      method: 'POST',
      body: JSON.stringify(data),
      headers: { 'content-type': 'application/json'}
      })
    .then(res => res.text())
    .then(response => console.log('Success:', JSON.stringify(response)))
    .catch(error => console.error('Error:',error))
  }
  
  let create = document.getElementById('create_btn');
  if(create){
    create.addEventListener('click', accountAjax);
  }
  let login = document.getElementById('login_btn');
  if(login){
  login.addEventListener("click", loginAjax);
  }