<?php
// index.php

// Autoload Composer dependencies
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database connection parameters from environment variables
$dbHost = $_ENV['DB_HOST'] ?? 'localhost';
$dbName = $_ENV['DB_DATABASE'] ?? 'test_db';
$dbUser = $_ENV['DB_USERNAME'] ?? 'root';
$dbPass = $_ENV['DB_PASSWORD'] ?? 'password';

$dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

echo "<h1>PHP Application with MySQL PDO and Dotenv</h1>";

try {
    // Attempt to connect to the database
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
    echo "<p>Successfully connected to the database!</p>";

    // Example: Fetching data from a table
    // Ensure you have a 'users' table in your 'test_db' database
    echo "<h2>Users:</h2>";
    $stmt = $pdo->query("SELECT id, name, email FROM users");
    $users = $stmt->fetchAll();

    if (empty($users)) {
        echo "<p>No users found. Let's add one!</p>";
        // Example: Inserting data
        $name = "John Doe";
        $email = "john.doe@example.com";
        $insertStmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
        $insertStmt->execute(['name' => $name, 'email' => $email]);
        echo "<p>Added user: $name ($email)</p>";

        // Fetch again to show the newly added user
        $stmt = $pdo->query("SELECT id, name, email FROM users");
        $users = $stmt->fetchAll();
    }

    echo "<ul>";
    foreach ($users as $user) {
        echo "<li>ID: " . htmlspecialchars($user['id']) . ", Name: " . htmlspecialchars($user['name']) . ", Email: " . htmlspecialchars($user['email']) . "</li>";
    }
    echo "</ul>";

} catch (PDOException $e) {
    // Catch and display any database connection errors
    echo "<p style='color: red;'>Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    error_log("Database connection error: " . $e->getMessage());
}
?>
