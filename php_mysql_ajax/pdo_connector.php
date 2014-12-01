<?php 
/*
 * Author   => Sean Hellebusch | sahellebusch@gmail.com
 * Date     => 11.30.2014
 *
 * PHP class to abstract out DB connection
 */
class PDO_Connector {
    
    public function connect() {
        try {
            // Database login
            $dbuser = 'hellebusch';
            $dbpass = 'admin';
            // Connect to DB
            $pdo = new PDO("mysql:host=Enterprise;dbname=test;charset=utf8", "hellebusch", "admin");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
            return $pdo;
        } catch(PDOException $e) {
            echo 'error: ' . $e->getMessage();
        }
    }
}
?>