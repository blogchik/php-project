<?php

namespace App\Functions;

function dbquery($query, $params = [])
{
    $host = $_ENV['MYSQL_HOST'];
    $username = $_ENV['MYSQL_USERNAME'];
    $password = $_ENV['MYSQL_PASSWORD'];
    $database = $_ENV['MYSQL_DATABASE'];

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("MySQL connection error: " . $conn->connect_error);
    }

    $conn->set_charset("utf8mb4");

    if ($stmt = $conn->prepare($query)) {
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();

        if (str_starts_with(strtolower($query), 'select')) {
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            $conn->close();
            return $data;
        }

        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        $conn->close();
        return $affected_rows;
    } else {
        $error = $conn->error;
        $conn->close();
        die("Query error: " . $error);
    }
}

// SELECT
// $query = "SELECT * FROM users WHERE email = ?";
// $params = ['test@example.com'];
// $result = dbquery($query, $params);
// print_r($result);

// INSERT
// $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
// $params = ['John Doe', 'john@example.com', password_hash('password123', PASSWORD_BCRYPT)];
// $inserted = dbquery($query, $params);
// echo "Inserted rows: " . $inserted;

// UPDATE
// $query = "UPDATE users SET name = ? WHERE id = ?";
// $params = ['Jane Doe', 1];
// $updated = dbquery($query, $params);
// echo "Updated rows: " . $updated;

// DELETE
// $query = "DELETE FROM users WHERE id = ?";
// $params = [1];
// $deleted = dbquery($query, $params);
// echo "Deleted rows: " . $deleted;

// CREATE TABLE
// $query = "
// CREATE TABLE IF NOT EXISTS users (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     name VARCHAR(255) NOT NULL,
//     email VARCHAR(255) UNIQUE NOT NULL,
//     password VARCHAR(255) NOT NULL,
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
// ) ENGINE=InnoDB;
// ";
// $result = dbquery($query);
// if ($result === 0) {
//     echo "Table 'users' successfully created or already exists.";
// } else {
//     echo "Something went wrong.";
// }

// CREATE TABLE with FK ON DELETE CASCADE
// $query = "
// CREATE TABLE IF NOT EXISTS orders (
//     order_id INT AUTO_INCREMENT PRIMARY KEY,
//     user_id INT NOT NULL,
//     product_name VARCHAR(255) NOT NULL,
//     quantity INT DEFAULT 1,
//     order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
// ) ENGINE=InnoDB;
// ";
// $result = dbquery($query);
// if ($result === 0) {
//     echo "Table 'orders' successfully created or already exists.";
// } else {
//     echo "Something went wrong.";
// }