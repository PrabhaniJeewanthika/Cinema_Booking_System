<?php
require_once(__DIR__ . '/../config/db.php');
require_once(__DIR__ . '/../models/Movies.php'); // ✅ matches your filename

session_start();

$movie_id = isset($_GET['movie_id']) ? intval($_GET['movie_id']) : 0;

if ($movie_id <= 0) {
    header("Location: movie_details.php");
    exit;
}

$movieModel = new Movie();
$movie = $movieModel->getMovieById($movie_id);
if (!$movie) {
    echo "<h2 style='color: white; text-align: center; margin-top: 100px;'>Movie not found.</h2>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($movie['title']); ?> - AuraCinema</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('../assets/img/now_showing.jpg') no-repeat center center/cover;
            font-family: 'Segoe UI', sans-serif;
            color: white;
        }
        .movie-container {
            max-width: 1000px;
            margin: 50px auto;
            background: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 15px;
        }
        .movie-content {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }
        .poster img {
            width: 300px;
            border-radius: 10px;
        }
        .movie-details h2 {
            margin-top: 0;
        }
        .btn-book {
            display: inline-block;
            margin-top: 20px;
            background: #ff004f;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 16px;
            transition: 0.3s ease;
        }
        .btn-book:hover {
            background: #ff3366;
        }
    </style>
</head>
<body>

<?php include('../includes/header.php'); ?>

<div class="movie-container">
    <div class="movie-content">
        <div class="poster">
            <img src="../assets/img/<?php echo htmlspecialchars($movie['poster']); ?>" alt="Poster">
        </div>
        <div class="movie-details">
            <h2><?php echo htmlspecialchars($movie['title']); ?></h2>
            <p><strong>Genre:</strong> <?php echo htmlspecialchars($movie['genre']); ?></p>
            <p><strong>Duration:</strong> <?php echo htmlspecialchars($movie['duration']); ?> minutes</p>
            <p><strong>Description:</strong></p>
            <p><?php echo nl2br(htmlspecialchars($movie['description'])); ?></p>
            <a href="film_details.php?movie_id=<?php echo $movie['id']; ?>" class="btn-book">Book Now</a>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>

</body>
</html>
