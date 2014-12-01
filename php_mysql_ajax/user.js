"use strict";

window.onload = attachEventHandlers;

function attachEventHandlers() {
  usersbyfirst();
  // document.getElementById("first_name").onclick = sortbyfirstname;
}

function usersbyfirst() {
        var request = new XMLHttpRequest();

        request.open( "GET", 
                      "usersbyname.php",
                      false );
        request.send( null );
        console.log(request.responseText);
        document.getElementById("users").innerHTML = request.responseText;
}