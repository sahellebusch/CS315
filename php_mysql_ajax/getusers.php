<?php
/*
 * Author   => Sean Hellebusch | sahellebusch@gmail.com
 * Date     => 12.1.2014
 *
 * Script to retrieve all the users from the database
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

foreach( $users as $urow ): 
  $bd         = new DateTime($urow["birthday"]);
  $now        = new DateTime();
  $diff_array = $now->diff($bd);
  $age        = intval($diff_array->format("%d")) / 365.25;
  $age       += intval($diff_array->format("%m")) / 12;
  $age       += intval($diff_array->format("%Y"));
?>
  <tr class="inner">
    <td><?= $urow["first_name"] ?></td>
    <td><?= $urow["last_name"] ?></td>
    <td><?= $urow["email"] ?></td>
    <td><?= $age ?></td>
  </tr>
<?php endforeach; ?>