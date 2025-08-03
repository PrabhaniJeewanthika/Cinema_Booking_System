<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        Email
        <?php
        require_once(__DIR__ . '/config/db.php');

        $conn = Database::getConnection();

        $sql = "SELECT * FROM movies";
        $result = $conn->query($sql);

        echo "<h1>Movie List</h1>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . $row['title'] . "</li>";
        }
        echo "</ul>";

        Database::closeConnection();
        ?>


    </body>
</html>
