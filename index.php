<?php
session_start();
include('includes/header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema Booking - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Cinema_Booking_System/assets/css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Raleway', sans-serif;
            background: #000;
            color: #fff;
        }
        .hero-video {
            position: relative;
            width: 100%;
            height: 600px;
            overflow: hidden;
        }
        .hero-video video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(60%);
        }
        .video-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
            text-align: center;
            z-index: 2;
        }
        .video-overlay h1 {
            font-size: 3rem;
            color: #ff0000;
            text-shadow: 0 2px 5px rgba(0,0,0,0.7);
            font-family: 'Raleway', sans-serif;
        }
        .video-overlay p {
            font-size: 1.2rem;
            color: #fff;
            margin: 10px 0;
            font-family: 'Raleway', sans-serif;
        }
        .page-section {
            width: 100%;
            position: relative;
            overflow: hidden;
        }
        .page-section img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        .section-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .section-overlay h2 {
            font-size: 2rem;
            color: #fff;
            text-shadow: 0 2px 5px rgba(0,0,0,0.7);
            font-family: 'Raleway', sans-serif;
        }
        .section-overlay a {
            color: #fff;
            text-decoration: none;
            background: rgba(255,0,0,0.6);
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: bold;
            margin-top: 10px;
            display: inline-block;
            transition: background 0.3s ease;
        }
        .section-overlay a:hover {
            background: rgba(255,0,0,0.8);
        }
    </style>
</head>
<body>
<section class="hero-video">
    <video autoplay muted loop playsinline>
        <source src="/Cinema_Booking_System/assets/videos/banner.mp4" type="video/mp4">
    </video>
    <div class="video-overlay">
        <h1>Welcome to AuraCinema</h1>
        <p>“Your Seat. Your Show. Your Aura.”<br>Book your favorite movies in seconds.<br>Fast, Easy, Elegant.</p>
    </div>
</section>

<section class="page-section">
    <img src="/Cinema_Booking_System/assets/img/homepage1.jpg" alt="Now Showing">
    <div class="section-overlay">
        <div>
            <h2>Now Showing</h2>
            <a href="/Cinema_Booking_System/user/now_showing.php">Explore</a>
        </div>
    </div>
</section>

<section class="page-section">
    <img src="/Cinema_Booking_System/assets/img/upcoming.jpg" alt="Upcoming Movies">
    <div class="section-overlay">
        <div>
            <h2>Upcoming Movies</h2>
            <a href="/Cinema_Booking_System/user/upcoming_movies.php">Explore</a>
        </div>
    </div>
</section>

<section class="page-section">
    <img src="/Cinema_Booking_System/assets/img/seat_booking.jpg" alt="Book Seats">
    <div class="section-overlay">
        <div>
            <h2>Book Your Seats</h2>
            <a href="/Cinema_Booking_System/user/select_seats.php">Explore</a>
        </div>
    </div>
</section>

<section class="page-section">
    <img src="/Cinema_Booking_System/assets/img/profile.jpg" alt="My Profile">
    <div class="section-overlay">
        <div>
            <h2>My Profile</h2>
            <a href="/Cinema_Booking_System/user/profile.php">Explore</a>
        </div>
    </div>
</section>

</body>
</html>