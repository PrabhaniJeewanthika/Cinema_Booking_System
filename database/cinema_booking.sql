-- Database: cinema_booking
CREATE DATABASE IF NOT EXISTS cinema_booking;
USE cinema_booking;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Movies Table
CREATE TABLE movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    genre VARCHAR(100) NOT NULL,
    duration INT NOT NULL, -- in minutes
    description TEXT NOT NULL,
    poster VARCHAR(255) NOT NULL -- just the filename, e.g., 'avatar.jpg'
);

-- Bookings Table
DROP TABLE IF EXISTS bookings;
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    movie_id INT NOT NULL,
    seats TEXT NOT NULL,
    booking_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (movie_id) REFERENCES movies(id)
);

-- Payments Table
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    payment_method ENUM('cash', 'online') DEFAULT 'cash',
    payment_status ENUM('pending', 'paid') DEFAULT 'pending',
    payment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id)
);

-- Dummy Data
INSERT INTO users (id, name, email, password, role) VALUES
(1, 'Test User', 'testuser@example.com', 'password', 'user')
ON DUPLICATE KEY UPDATE name='Test User';

INSERT INTO movies (id, title, description, duration) VALUES
(1, 'Sample Movie', 'This is a sample movie description.', 120)
ON DUPLICATE KEY UPDATE title='Sample Movie';

INSERT INTO movies (title, genre, duration, description, poster) VALUES
('Avatar: The Way of Water', 'Sci-Fi', 192, 'Jake Sully lives with his newfound family formed on the extrasolar moon Pandora.', 'avatar.jpg'),
('The Batman', 'Action', 176, 'Batman ventures into Gotham City’s underworld when a sadistic killer leaves behind a trail of cryptic clues.', 'batman.jpg'),
('Frozen II', 'Animation', 103, 'Anna, Elsa, Kristoff, Olaf and Sven leave Arendelle to travel to an ancient forest.', 'frozen2.jpg'),
('Top Gun: Maverick', 'Action', 131, 'After more than thirty years of service, Maverick is still pushing the envelope.', 'topgun.jpg');