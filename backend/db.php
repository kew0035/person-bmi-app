<?php
function getPDO(){
  $host = 'localhost';
  $db = 'person_bmi';
  $user = 'root';
  $pass = 'ip_password';
  $chatset = 'utf8mb4';

  $dsn = "mysql:host=$host;dbname=$db;chatset=$chatset";

  try{
    return new PDO($dsn, $user, $pass, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
  }catch(PDOException $e){
    die('DB Connection failed: ' . $e->getMessage());
  }
}