<?php
/*
* Author   => Sean Hellebusch | sahellebusch@gmail.com
* Date     => 11.30.2014
* Version  => 1.0.0
*
* This program presents a quiz to the user and reports
* results upon completion.
*/
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "pdo_Connector.php";

$success = FALSE;

if(isset($_POST["first_name"])
  && isset($_POST["last_name"])
  && isset($_POST["email"])
  && isset($_POST["birthday"])):

  $first_name = $_POST["first_name"];
  $last_name  = $_POST["last_name"];
  $birthday   = $_POST["birthday"];
  $email      = $_POST["email"];

  $connector = new PDO_Connector();
  $pdo = $connector->connect();

  $query = "INSERT INTO user (created, first_name, last_name, email, birthday)
            VALUES (now(), :first_name, :last_name, :email, :birthday)";
  $stmnt = $pdo->prepare($query);

  $stmnt->bindValue(":first_name", $first_name);
  $stmnt->bindValue(":last_name", $last_name);
  $stmnt->bindValue(":birthday", $birthday);
  $stmnt->bindValue(":email", $email);

  $stmnt->execute();
  $success = TRUE;
endif;

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="author" content="Sean Hellebusch" />
    <!-- <link rel="stylesheet" href="user.css"> -->
    <title>New User</title>
  </head>

  <body>
  <?php if(!$success): ?>
    <form method="post" action="new_user.php" >
      <p>
        <label for="first_name">First name:</label>
        <input type="text" id="first_name" name="first_name" required="required" />
      </p>

      <p>
        <label for="last_name">Last name:</label>
        <input type="text" id="last_name" name="last_name" required="required" />
      </p>
      
      <p>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required="required" />
      </p>

      <p>
        <label for="birthday">Birthday:</label>
        <input type="date" id="birthday" name="birthday" required="required" />
      </p>

      <button id="submit" class="button" type="submit">Submit</button>
    </form>
  <?php else: ?>
    <h1>Success!</h1>
    <p><a href="user_data.html">User data</a></p>
  <?php endif; ?>
  </body>
</html>