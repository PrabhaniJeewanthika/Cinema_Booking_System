<!DOCTYPE html>
<html>
<head>
    <title>Movie Details</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: url('img/back.jpg') no-repeat center center fixed, linear-gradient(120deg, #1a0000 0%, #e50914 100%);
            background-size: cover;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            color: #fff;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(120deg, rgba(0,0,0,0.97) 80%, rgba(229,9,20,0.18) 100%);
            z-index: 0;
        }
        .container {
            max-width: 950px;
            margin: 48px auto;
            background: rgba(30,0,0,0.92);
            padding: 32px 28px 28px 28px;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(229,9,20,0.25), 0 2px 8px #000;
            position: relative;
            z-index: 1;
        }
        .movie-header {
            display: flex;
            gap: 32px;
            align-items: flex-start;
            flex-wrap: wrap;
        }
        .movie-header img {
            width: 320px;
            height: 470px;
            border-radius: 14px;
            object-fit: cover;
            box-shadow: 0 4px 24px #e50914, 0 2px 8px #000;
            border: 3px solid #e50914;
            background: #000;
        }
        .movie-info h1 {
            margin-top: 0;
            font-size: 2.3em;
            color: #e50914;
            letter-spacing: 2px;
            font-weight: bold;
            text-shadow: 0 2px 8px #000;
        }
        .movie-info p {
            margin: 8px 0;
            font-size: 1.1em;
            color: #fff;
            text-shadow: 0 1px 4px #000;
        }
        .movie-info strong {
            color: #e50914;
        }
        .description {
            margin-top: 28px;
            font-size: 1.08em;
            line-height: 1.7;
            color: #fff;
            background: rgba(229,9,20,0.08);
            border-left: 4px solid #e50914;
            padding: 18px 22px;
            border-radius: 8px;
            box-shadow: 0 2px 8px #e50914;
        }
        a.back-link {
            display: inline-block;
            margin-top: 32px;
            text-decoration: none;
            background: linear-gradient(90deg, #e50914 60%, #b0060f 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1.1em;
            box-shadow: 0 2px 8px #e50914;
            transition: background 0.2s, box-shadow 0.2s;
        }
        a.back-link:hover {
            background: #b0060f;
            box-shadow: 0 4px 16px #e50914;
        }
        @media (max-width: 900px) {
            .container {
                padding: 16px 4vw;
            }
            .movie-header {
                flex-direction: column;
                align-items: center;
            }
            .movie-header img {
                width: 90vw;
                max-width: 340px;
                height: auto;
            }
        }
    </style>
</head>
<body>

<?php
include_once '../config/db.php';

$movie = null;
$error = '';
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ?");
        $stmt->execute([$id]);
        $movie = $stmt->fetch();
        if (!$movie) {
            $error = 'Movie not found.';
        }
    } else {
        $error = 'Invalid movie ID.';
    }
} else {
    $error = 'No movie ID specified.';
}
?>
<div class="container">
    <?php if ($movie): ?>
        <div class="movie-header">
            <img src="../assets/img/<?php echo htmlspecialchars($movie['poster']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
            <div class="movie-info">
                <h1><?php echo htmlspecialchars($movie['title']); ?></h1>
                <p><strong>Genre:</strong> <?php echo htmlspecialchars($movie['genre']); ?></p>
                <p><strong>Language:</strong> <?php echo htmlspecialchars($movie['language']); ?></p>
                <p><strong>Duration:</strong> <?php echo htmlspecialchars($movie['duration']); ?> minutes</p>
                <p><strong>Release Date:</strong> <?php echo htmlspecialchars($movie['release_date']); ?></p>
                <p><strong>Show Time:</strong> <?php echo htmlspecialchars($movie['show_time']); ?></p>
            </div>
        </div>
        <div class="description">
            <?php echo htmlspecialchars($movie['description']); ?>
        </div>
        <a href="booking_process.php?movie_id=<?php echo $movie['id']; ?>" class="back-link" style="background:linear-gradient(90deg,#28a745 60%,#218838 100%);margin-right:16px;">Book Now</a>
    <?php else: ?>
        <div class="movie-header">
            <div class="movie-info">
                <h1>Error</h1>
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        </div>
    <?php endif; ?>
    <a href="Now_showing.php" class="back-link" style="background:linear-gradient(90deg,#e50914 60%,#b0060f 100%);">&larr; Back to Now Showing</a>
</div>

</body>
</html>