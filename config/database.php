<?php

define('DB_USER', 'root');                   // user-ul bazei de date
define('DB_PASSWORD', 'password');           // parola
define('DB_HOST', '127.0.0.1');              // adresa serverului de baze de date
define('DB_NAME', 'agregator');              // numele bazei de date

try {
    $db = new PDO("mysql:dbname=" . DB_NAME . "; host=" . DB_HOST . "; charset=utf8", DB_USER, DB_PASSWORD);
}
catch (PDOException $e){
    die('Nu ma pot conecta la baza de date.');
}