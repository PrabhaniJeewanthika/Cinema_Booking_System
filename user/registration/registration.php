<?php
class UserRegistration {
    private $conn;
    private $name;
    private $email;
    private $contact;
    private $username;
    private $password;

    public function __construct($dbConfig) {
        $this->conn = new mysqli($dbConfig['servername'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function setUserData($postData) {
        $this->name = trim($postData['name']);
        $this->email = trim($postData['email']);
        $this->contact = trim($postData['contact']);
        $this->username = trim($postData['username']);
        $this->password = password_hash($postData['password'], PASSWORD_DEFAULT);
    }

    public function register() {
        $stmt = $this->conn->prepare("INSERT INTO registeredusers (name, email, contact, username, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $this->name, $this->email, $this->contact, $this->username, $this->password);
        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!'); window.location='../../admin/login.html';</script>";
        } else {
            if ($this->conn->errno == 1062) {
                echo "<script>alert('Error: Username or Email already exists!'); window.history.back();</script>";
            } else {
                echo "<script>alert('Error: " . $this->conn->error . "'); window.history.back();</script>";
            }
        }
        $stmt->close();
    }

    public function close() {
        $this->conn->close();
    }
}

// Usage
$dbConfig = [
    'servername' => 'localhost',
    'username' => 'root',
    'password' => '',
    'dbname' => 'cinema_booking'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registration = new UserRegistration($dbConfig);
    $registration->setUserData($_POST);
    $registration->register();
    $registration->close();
}
?>
