<?php
session_start();
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Auth.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

$auth->logout();

// Po odhlásení presmerujeme na domovskú stránku
header("Location: index.php");
exit;
?>