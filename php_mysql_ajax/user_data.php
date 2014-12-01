<?php /*
 * Author   => Sean Hellebusch | sahellebusch@gmail.com
 * Date     => 12.1.2014
 *
 * Script to retrieve all the users from the database and present
 * them in a table.  The user can click the column headers to sort
 * the table using asynchronous AJAX.
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

include( 'pdo_connector.php' );

$connector = new PDO_Connector();
$pdo = $connector->connect();

$query = "SELECT * FROM user";

try {
    $stmnt = $pdo->prepare($query);
    $stmnt->execute();
    $users = $stmnt->fetchAll();
  } catch(PDOException $e) {
      echo 'error: ' . $e->getMessage();
} 

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="author" content="Sean Hellebusch" />
    <link rel="stylesheet" href="user.css">
    <title>User Data</title>
  </head>

  <body>
    <table>
      <tr>
        <th id="first">First&uarr;&darr;</th>
        <th id="last">Last&uarr;&darr;</th>
        <th id="email">Email&uarr;&darr;</th>
        <th id="age">Age&uarr;&darr;</th>
      </tr>
    </table>      
    <table id="users">
    <?php
        foreach( $users as $urow ): 
          $bd         = new DateTime($urow["birthday"]);
          $now        = new DateTime();
          $diff_array = $now->diff($bd);
          $age        = intval($diff_array->format("%d")) / 365.25;
          $age       += intval($diff_array->format("%m")) / 12;
          $age       += intval($diff_array->format("%Y"));
    ?>
      <tr>
        <td><?= $urow["first_name"] ?></td>
        <td><?= $urow["last_name"] ?></td>
        <td><?= $urow["email"] ?></td>
        <td><?= $age ?></td>
      </tr>
    <?php endforeach; ?>      
    </table>
    <script src="user.js"></script>
  </body>
</html>
