<?php
require_once(__DIR__ . '/../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $duration = $_POST['duration'];

    $conn = Database::getConnection();

    $stmt = $conn->prepare("INSERT INTO movies (title, description, duration) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $description, $duration);

    if ($stmt->execute()) {
        echo "Movie added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    Database::closeConnection();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Movie Test</title>
</head>
<body>
    <h1>Add Movie to Database</h1>
    <form method="POST" action="">
        Title: <input type="text" name="title" required><br><br>
        Description: <textarea name="description" required></textarea><br><br>
        Duration (minutes): <input type="number" name="duration" required><br><br>
        <input type="submit" value="Add Movie">
    </form>
</body>
</html>
