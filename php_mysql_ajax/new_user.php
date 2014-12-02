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

include( 'pdo_connector.php' );

$success     = FALSE;
$errors      = FALSE;
$name_regex = '/^[a-zA-Z]+(\-[A-Za-z]+)?$/';
$email_regex = '/^[A-za-z+_\-\.\%]+\@[A-Z-a-z]+\.[A-Za-z]{2,4}$/';
$date_regex = '/^\d{4}-\d{2}-\d{2}$/';

if(isset($_POST["first_name"])
  && isset($_POST["last_name"])
  && isset($_POST["email"])
  && isset($_POST["birthday"])):

  $first_name = htmlspecialchars($_POST["first_name"]);
  $last_name  = htmlspecialchars($_POST["last_name"]);
  $birthday   = htmlspecialchars($_POST["birthday"]);
  $email      = htmlspecialchars($_POST["email"]);

  $errors = !preg_match($name_regex, $first_name) ||
            !preg_match($email_regex, $email) ||
            !preg_match($date_regex, $birthday);

  try {
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
  } catch(PDOException $e) {
    echo 'error: ' . $e->getMessage();
  }
endif;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="author" content="Sean Hellebusch" />
    <title>New User</title>
  </head>

  <body>
  <?php if(!$success):
          if($errors):
   ?>
 <p><?= print_r($_POST)?>
    <h2>There were errors in your submission.  Please try again.</h2>
  <?php endif; ?>
    <h2>Create A new User</h2>
    <form method="post" action="new_user.php" >
      <p>
        <label for="first_name">First name:</label>
        <input type="text" id="first_name" name="first_name" 
        required="required" pattern="^[a-zA-Z]+(\-[A-Za-z]+)?$"/>
      </p>

      <p>
        <label for="last_name">Last name:</label>
        <input type="text" id="last_name" name="last_name" 
        required="required" patttern="^[a-zA-Z]+(\-[A-Za-z]+)?$"/>
      </p>
      
      <p>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" 
        required="required" 
        patttern="^[A-za-z+_\-\.\%]+\@[A-Z-a-z]+\.[A-Za-z]{2,4}$"/>
      </p>

      <p>
        <label for="birthday">Birthday:</label>
        <input type="date" id="birthday" name="birthday" 
        required="required" />
      </p>

      <button id="submit" class="button" type="submit">Submit</button>
    </form>
  <?php else: ?>
    <h1>Success!</h1>
    <p><a href="user_data.php">User data</a></p>
  <?php endif; ?>
  </body>
</html>