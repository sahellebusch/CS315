"use strict";

window.onload = attachEventHandlers;

function attachEventHandlers() {
  document.getElementById("first").onclick = sortusers;
  document.getElementById("last").onclick  = sortusers;
  document.getElementById("email").onclick = sortusers;
  document.getElementById("age").onclick   = sortusers;
}

function sortusers() {
  var option = this.innerHTML;
  if(option != "") {
    if(option.match(/First/) == "First")
      option = "first_name";
    if(option.match(/Last/) == "Last")
      option = "last_name";
    if(option.match(/Email/) == "Email")
      option = "email";
    if(option.match(/Age/) == "Age")
      option = "birthday";

    var request = new XMLHttpRequest();
    
    request.onreadystatechange = function() {
          if(request.readyState == 4 && request.status == 200) {
            document.getElementById("users").innerHTML = request.responseText;
          }
        };
          request.open( "GET", 
                        "usersort.php?option=" + option,
                        true );
          request.send( null );
      }
}
