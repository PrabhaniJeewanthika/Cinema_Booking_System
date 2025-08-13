<!DOCTYPE html>
<html>
<head>
    <title>Now Showing - Weekly Schedule</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(120deg, #e50914 0%, #000 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0 0 40px 0;
            position: relative;
            color: #fff;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('img/back.jpg') no-repeat center center fixed;
            background-size: cover;
            opacity: 0.13;
            z-index: 0;
            filter: brightness(0.3);
        }
        h1 {
            text-align: center;
            color: #e50914;
            font-size: 2.8em;
            margin: 32px 0 24px 0;
            letter-spacing: 2px;
            font-weight: bold;
            position: relative;
            z-index: 1;
            /* Change white shadows to black shadows */
            text-shadow: 0 2px 8px #000, 0 2px 8px #e50914;
        }
        h2 {
            margin-top: 40px;
            color: #e50914;
            font-size: 2em;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px #000;
            position: relative;
            z-index: 1;
        }
        .day-section {
            margin-bottom: 36px;
            background: rgba(0,0,0,0.85);
            border-radius: 16px;
            box-shadow: 0 4px 24px #e5091422, 0 2px 8px #000;
            padding: 18px 0 18px 0;
            position: relative;
            z-index: 1;
        }
        .movie-list {
            display: flex;
            flex-wrap: wrap;
            gap: 28px;
            justify-content: center;
            padding: 0 18px;
        }
        .movie-card {
            background: linear-gradient(135deg, #000 70%, #e50914 100%);
            padding: 18px 12px 18px 12px;
            border-radius: 12px;
            width: 260px;
            box-shadow: 0 4px 16px #e5091440, 0 2px 8px #000;
            text-align: center;
            border: 2.5px solid #e50914;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            z-index: 2;
            color: #fff;
        }
        .movie-card:hover {
            transform: scale(1.06) rotate(-1deg);
            box-shadow: 0 8px 32px #e50914, 0 2px 16px #000;
        }
        .movie-card img {
            width: 100%;
            height: 350px;
            object-fit: cover;
            border-radius: 8px;
            border-bottom: 2px solid #e50914;
            box-shadow: 0 2px 8px #e50914;
        }
        .movie-card h3 {
            margin: 14px 0 6px 0;
            color: #e50914;
            font-size: 1.3em;
            font-weight: bold;
            letter-spacing: 1px;
            /* Change white shadow to black */
            text-shadow: 0 1px 4px #000;
        }
        .movie-card p {
            margin: 5px 0;
            color: #ff4c4c;
            font-size: 1em;
            text-shadow: 0 1px 4px #000;
        }
        .movie-card a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 18px;
            background: linear-gradient(90deg, #e50914 60%, #b0060f 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 1em;
            box-shadow: 0 2px 8px #e50914;
            transition: background 0.2s, box-shadow 0.2s;
        }
        .movie-card a:hover {
            background: #b0060f;
            box-shadow: 0 4px 16px #e50914;
        }
        @media (max-width: 900px) {
            .movie-list {
                flex-direction: column;
                gap: 18px;
                padding: 0 2vw;
            }
            .movie-card {
                width: 95vw;
            }
        }
    </style>
</head>
<body>

    <h1>Now Showing This Week</h1>

    <?php
    include_once '../config/db.php';

    $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

    // Fetch all movies
    $sql = "SELECT * FROM movies";
    $result = mysqli_query($conn, $sql);
    $movies = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $movies[] = $row;
        }
    }

    // Display movies for each day
    foreach ($days as $day) {
        echo "<div class='day-section'>";
        echo "<h2>$day</h2>";
        echo "<div class='movie-list'>";
        foreach ($movies as $movie) {
            echo "
            <div class='movie-card'>
                <img src='../assets/img/" . htmlspecialchars($movie['poster']) . "' alt='" . htmlspecialchars($movie['title']) . "'>
                <h3>" . htmlspecialchars($movie['title']) . "</h3>
                <p>Genre: " . htmlspecialchars($movie['genre']) . "</p>
                <p>Language: " . htmlspecialchars($movie['language']) . "</p>
                <p>Duration: " . htmlspecialchars($movie['duration']) . " minutes</p>
                <p>Show Time: " . htmlspecialchars($movie['show_time']) . "</p>
                <a href='film_details.php?id=" . $movie['id'] . "'>View Details</a>
            </div>
            ";
        }
        echo "</div></div>";
    }
    ?>

</body>
</html>
