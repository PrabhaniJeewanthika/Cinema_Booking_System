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

<div class="container">
    <div class="movie-header">
        <img src="img/jailer.jpg" alt="Jailer">
        <div class="movie-info">
            <h1>Jailer</h1>
            <p><strong>Genre:</strong> Action</p>
            <p><strong>Language:</strong> Tamil</p>
            <p><strong>Duration:</strong> 150 minutes</p>
            <p><strong>Release Date:</strong> 2025-08-10</p>
            <p><strong>Show Time:</strong> 6:30 PM</p>
        </div>
    </div>
    <div class="description">
        "Jailer" is an action-packed Tamil thriller featuring intense performances, gripping plot twists, and breathtaking stunt sequences. 
        Follow the journey of a man who will stop at nothing to protect what he values most.
    </div>
    <a href="index.php" class="back-link">&larr; Back to Movies</a>
</div>

</body>
</html>