<?php
require_once(__DIR__ . '/../config/db.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title        = $_POST['Movie_Title'];
    $duration     = (int)$_POST['duration'];
    $genre        = $_POST['Genre'];
    $description  = $_POST['Description'];
    $release_date = $_POST['release_date'];
    $show_time    = $_POST['show_Time'];

    // Handle file upload (with or without image)
    $movie_poster = null;
    $upload_message = "";
    
    if (isset($_FILES['Movie_poster']) && $_FILES['Movie_poster']['error'] === UPLOAD_ERR_OK) {
        // File uploaded successfully
        $movie_poster = file_get_contents($_FILES['Movie_poster']['tmp_name']);
        $upload_message = " (with image)";
    } else {
        // No file or file upload failed - still save movie without image
        if (isset($_FILES['Movie_poster']) && $_FILES['Movie_poster']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload_message = " (image upload failed - error code: " . $_FILES['Movie_poster']['error'] . ")";
        } else {
            $upload_message = " (no image uploaded)";
        }
    }

    try {
        $conn = Database::getConnection();

        $stmt = $conn->prepare("
            INSERT INTO movies (Movie_Title, duration, Genre, Description, release_date, show_Time, Movie_poster)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind params - movie_poster can be null if no image
        $stmt->bind_param("sissssb", $title, $duration, $genre, $description, $release_date, $show_time, $movie_poster);

        if ($stmt->execute()) {
            echo "<script>alert('Movie added successfully! ID: " . $conn->insert_id . $upload_message . "');</script>";
        } else {
            echo "<script>alert('Error saving movie: " . $stmt->error . "');</script>";
        }

        $stmt->close();
        Database::closeConnection();
    } catch (Exception $e) {
        echo "<script>alert('Database Error: " . $e->getMessage() . "');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #1c1c1c;
            min-height: 100vh;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .header h1 {
            color: #333;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .welcome-text {
            color: #666;
            font-size: 0.9rem;
        }

        .logout-btn {
            background: #ff6b6b;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: #ff5252;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 5rem 0 2rem 0;
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.1);
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 1rem 2rem;
            color: #666;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            cursor: pointer;
        }

        .sidebar-item:hover,
        .sidebar-item.active {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            border-left-color: #667eea;
        }

        .sidebar-item i {
            width: 20px;
            margin-right: 1rem;
            font-size: 1.1rem;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 6rem 2rem 2rem 2rem;
        }

        .dashboard-title {
            color: white;
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 2rem;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border-left: 5px solid;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-card.movies {
            border-left-color: #4ecdc4;
        }

        .stat-card.bookings {
            border-left-color: #45b7d1;
        }

        .stat-card.users {
            border-left-color: #96ceb4;
        }

        .stat-card.revenue {
            border-left-color: #feca57;
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stat-title {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .stat-icon {
            font-size: 2rem;
            opacity: 0.3;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            line-height: 1;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: white;
            margin: 2% auto;
            padding: 0;
            width: 90%;
            max-width: 700px;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease-out;
            max-height: 90vh;
            overflow-y: auto;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            background: #1c1c1c;
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 15px 15px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .close {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            border: none;
            background: none;
            padding: 0.5rem;
            border-radius: 50%;
            transition: background 0.3s;
        }

        .close:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .modal-body {
            padding: 2rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .required {
            color: #e74c3c;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.3s, box-shadow 0.3s;
            background: #fafafa;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .file-input-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-input-display {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            border: 2px dashed #e1e8ed;
            border-radius: 8px;
            background: #fafafa;
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-input-display:hover {
            border-color: #667eea;
            background: #f0f4ff;
        }

        .file-input-display i {
            margin-right: 0.5rem;
            color: #667eea;
        }

        .modal-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid #e1e8ed;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding-top: 5rem;
                order: 2;
            }

            .main-content {
                order: 1;
                padding: 6rem 1rem 2rem 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .header {
                padding: 1rem;
            }

            .header h1 {
                font-size: 1.2rem;
            }

            .modal-content {
                width: 95%;
                margin: 5% auto;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .modal-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-film"></i> Cinema Admin Panel</h1>
            <div class="header-right">
                <span class="welcome-text">Welcome, Admin</span>
                <a href="login.html" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <a href="dashboard.php" class="sidebar-item active">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            <div class="sidebar-item" onclick="openAddMovieModal()">
                <i class="fas fa-film"></i>
                ADD MOVIES
            </div>
            <a href="#" class="sidebar-item">
                <i class="fas fa-edit"></i>
                UPDATE & DELETE MOVIES
            </a>
            <a href="#" class="sidebar-item">
                <i class="fas fa-ticket-alt"></i>
                Booking Management
            
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h2 class="dashboard-title">Welcome to Admin Dashboard</h2>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card movies">
                    <div class="stat-header">
                        <span class="stat-title">Total Movies</span>
                        <i class="fas fa-film stat-icon"></i>
                    </div>
                    <div class="stat-number">25</div>
                </div>

                <div class="stat-card bookings">
                    <div class="stat-header">
                        <span class="stat-title">Total Bookings</span>
                        <i class="fas fa-ticket-alt stat-icon"></i>
                    </div>
                    <div class="stat-number">342</div>
                </div>

                <div class="stat-card users">
                    <div class="stat-header">
                        <span class="stat-title">Registered Users</span>
                        <i class="fas fa-users stat-icon"></i>
                    </div>
                    <div class="stat-number">156</div>
                </div>

                <div class="stat-card revenue">
                    <div class="stat-header">
                        <span class="stat-title">Monthly Revenue</span>
                        <i class="fas fa-dollar-sign stat-icon"></i>
                    </div>
                    <div class="stat-number">$8,420</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Movie Modal -->
    <div id="addMovieModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-film"></i> Movie Registration</h2>
                <button class="close" onclick="closeAddMovieModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addMovieForm" method="POST" enctype="multipart/form-data" action="">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="movieTitle">Movie Title <span class="required">*</span></label>
                            <input type="text" id="movieTitle" name="Movie_Title" placeholder="Enter movie title" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="duration">Duration (minutes) <span class="required">*</span></label>
                            <input type="number" id="duration" name="duration" placeholder="Enter duration in minutes" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="genre">Genre <span class="required">*</span></label>
                            <select id="genre" name="Genre" required>
                                <option value="">Select genre</option>
                                <option value="Action">Action</option>
                                <option value="Comedy">Comedy</option>
                                <option value="Drama">Drama</option>
                                <option value="Horror">Horror</option>
                                <option value="Romance">Romance</option>
                                <option value="Sci-Fi">Sci-Fi</option>
                                <option value="Thriller">Thriller</option>
                                <option value="Animation">Animation</option>
                            </select>
                        </div>
                        
                        
                        
                        <div class="form-group full-width">
                            <label for="description">Description <span class="required">*</span></label>
                            <textarea id="description" name="Description" placeholder="Enter movie description" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="releaseDate">Release Date</label>
                            <input type="date" id="releaseDate" name="release_date">
                        </div>
                        
                        <div class="form-group">
                            <label for="showTime">Show Time <span class="required">*</span></label>
                            <input type="time" id="showTime" name="show_Time" required>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="movieImage">Movie Poster <span class="required">*</span></label>
                            <div class="file-input-wrapper">
                                <input type="file" id="movieImage" name="Movie_poster" class="file-input" accept="image/*" required>
                                <div class="file-input-display">
                                    <i class="fas fa-upload"></i>
                                    <span>Choose movie poster image</span>
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeAddMovieModal()">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="submit" form="addMovieForm" class="btn btn-primary">
                    <i class="fas fa-save"></i> Register Movie
                </button>
            </div>
        </div>
    </div>

    <script>
        // Modal functionality
        function openAddMovieModal() {
            document.getElementById('addMovieModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeAddMovieModal() {
            document.getElementById('addMovieModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            document.getElementById('addMovieForm').reset();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('addMovieModal');
            if (event.target == modal) {
                closeAddMovieModal();
            }
        }

        // File input display
        document.getElementById('movieImage').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Choose movie poster image';
            const display = document.querySelector('.file-input-display span');
            display.textContent = fileName;
        });

        // Form submission - let it submit normally to PHP
        document.getElementById('addMovieForm').addEventListener('submit', function(e) {
            // No preventDefault() - form will submit to PHP normally
            // PHP will handle the database insertion and show real messages
        });
    </script>
</body>
</html>
