<?php
// config.php
session_start();

$DB_HOST = 'localhost';
$DB_NAME = 'taskapp';
$DB_USER = 'root';
$DB_PASS = ''; // change this

try {
  $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
} catch (Exception $e) {
  die('Database connection error: ' . $e->getMessage());
}

function is_logged_in() {
  return !empty($_SESSION['user_id']);
}

function require_login() {
  if (!is_logged_in()) {
    header('Location: index.php');
    exit;
  }
}
