<?php
include '../components/connect.php';

if (!isset($_COOKIE['tutor_id'])) {
    header('location:login.php');
    exit();
}
$tutor_id = $_COOKIE['tutor_id'];

$select_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
$select_contents->execute([$tutor_id]);
$total_contents = $select_contents->rowCount();

$select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
$select_playlists->execute([$tutor_id]);
$total_playlists = $select_playlists->rowCount();

$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
$select_likes->execute([$tutor_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
$select_comments->execute([$tutor_id]);
$total_comments = $select_comments->rowCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
        .box {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
            background: linear-gradient(135deg, #e3f2fd,rgb(254, 255, 255));
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .box:hover {
            transform: scale(1.05);
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .option-btn {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .option-btn:hover {
            background-color: #218838;
        }
        .flex-btn {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .heading {
            color: #007bff;
        }
    </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="dashboard">
    <h1 class="heading">Dashboard</h1>

    <div class="box-container">
        <div class="box">
            <h3>Welcome!</h3>
            <p><?= htmlspecialchars($fetch_profile['name']); ?></p>
            <a href="profile.php" class="btn">View Profile</a>
        </div>

        <div class="box">
            <h3><?= htmlspecialchars($total_contents); ?></h3>
            <p>Total Contents</p>
            <a href="add_content.php" class="btn">Add New Content</a>
        </div>

        <div class="box">
            <h3><?= htmlspecialchars($total_playlists); ?></h3>
            <p>Total Playlists</p>
            <a href="add_playlist.php" class="btn">Add New Playlist</a>
        </div>

        <div class="box">
            <h3><?= htmlspecialchars($total_likes); ?></h3>
            <p>Total Likes</p>
            <a href="contents.php" class="btn">View Contents</a>
        </div>

        <div class="box">
            <h3><?= htmlspecialchars($total_comments); ?></h3>
            <p>Total Comments</p>
            <a href="comments.php" class="btn">View Comments</a>
        </div>

        <div class="box">
            <h3>Quick Select</h3>
            <p>Add New Student</p>
            <div class="flex-btn">
                <a href="register.php" class="option-btn">Register</a>
            </div>
        </div>
    </div>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>

</body>
</html>
