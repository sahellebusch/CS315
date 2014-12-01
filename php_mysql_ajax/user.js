"use strict";

window.onload = attachEventHandlers;

function attachEventHandlers() {
  usersbyfirst();
  document.getElementById("first").onclick = usersbyfirst;
  document.getElementById("last").onclick = usersbylast;
  document.getElementById("email").onclick = usersbyemail;
  document.getElementById("age").onclick = usersbyage;

}

function usersbyfirst() {
        var request = new XMLHttpRequest();

        request.open( "GET", 
                      "usersbyfirst.php",
                      false );
        request.send( null );
        document.getElementById("users").innerHTML = request.responseText;
}

function usersbylast() {
        var request = new XMLHttpRequest();

        request.open( "GET", 
                      "usersbylast.php",
                      false );
        request.send( null );
        document.getElementById("users").innerHTML = request.responseText;
}

function usersbyemail() {
        var request = new XMLHttpRequest();

        request.open( "GET", 
                      "usersbyemail.php",
                      false );
        request.send( null );
        document.getElementById("users").innerHTML = request.responseText;
}

function usersbyage() {
        var request = new XMLHttpRequest();

        request.open( "GET", 
                      "usersbyage.php",
                      false );
        request.send( null );
        document.getElementById("users").innerHTML = request.responseText;
}