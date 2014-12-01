<?php
// this program generates the inner html of a select element
// thus, it generates a set of option elements

error_reporting(E_ALL);
ini_set('display_errors', '1');

include( 'pdo_connector.php' );

$connector = new PDO_Connector();
$pdo = $connector->connect();

$query = "SELECT * FROM user ORDER BY first_name";
$stmnt = $pdo->prepare($query);

$stmnt->execute();
$users = $stmnt->fetchAll();
?>
  <tr>
    <th>First Name&uarr;&darr;</th>
    <th>Last Name&uarr;&darr;</th>
    <th>Email&uarr;&darr;</th>
    <th>Age&uarr;&darr;</th>
  </tr>
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