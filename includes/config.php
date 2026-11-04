<?php
// COVID-19 Portal - Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // Change to your MySQL username
define('DB_PASS', '');            // Change to your MySQL password
define('DB_NAME', 'covid19_db');
define('SITE_NAME', 'COVID-19 Info Portal');
define('BASE_URL', 'http://localhost/covid19_project/');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) { session_start(); }

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=3307;dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER, DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die("<div style='font-family:sans-serif;color:red;padding:20px;'>
         <h2>Database Connection Failed</h2>
         <p>" . $e->getMessage() . "</p>
         <p>Please check: 1) MySQL is running, 2) Credentials in config.php, 3) Database 'covid19_db' exists</p>
         </div>");
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}
function isLoggedIn() { return isset($_SESSION['user_id']); }
function isAdmin()    { return isset($_SESSION['role']) && $_SESSION['role'] === 'admin'; }
function formatNumber($num) {
    if ($num >= 1000000000) return round($num / 1000000000, 2) . 'B';
    if ($num >= 1000000)    return round($num / 1000000, 2) . 'M';
    if ($num >= 1000)       return round($num / 1000, 2) . 'K';
    return number_format($num);
}
?>
