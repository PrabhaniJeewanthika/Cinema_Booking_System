<?php
require_once __DIR__ . '/../../config/db.php';
session_start();

class UserLogin {
    private $conn;
    private $username;
    private $password;

    public function __construct($dbConn) {
        $this->conn = $dbConn;
    }

    public function setCredentials($username, $password) {
        $this->username = trim($username);
        $this->password = trim($password);
    }

    public function authenticate() {
        $stmt = $this->conn->prepare("SELECT id, name, email, contact, password FROM registeredusers WHERE username = ?");
        $stmt->bind_param("s", $this->username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($this->password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_contact'] = $row['contact'];
                header("Location: ../../index.php");
                exit;
            } else {
                echo "Invalid password!";
            }
        } else {
            echo "Invalid username!";
        }
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = Database::getConnection();
    $login = new UserLogin($conn);
    $login->setCredentials($_POST['username'], $_POST['password']);
    $login->authenticate();
    Database::closeConnection();
}
?>
