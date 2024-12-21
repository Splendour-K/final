<?php

include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
    header('location:home.php');
    exit();  // Ensures no further code executes
}

if (isset($_POST['remove'])) {
    if ($user_id != '') {
        $content_id = filter_var($_POST['content_id'], FILTER_SANITIZE_STRING);

        // Combine verification and removal of like into a single query for efficiency
        $remove_like = $conn->prepare("DELETE FROM `likes` WHERE user_id = ? AND content_id = ?");
        $remove_like->execute([$user_id, $content_id]);

        if ($remove_like->rowCount() > 0) {
            $message[] = 'Removed from likes!';
        } else {
            $message[] = 'You have not liked this content yet.';
        }
    } else {
        $message[] = 'Please login first!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liked Videos</title>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- Liked Videos Section -->
<section class="liked-videos">
    <h1 class="heading">Liked Videos</h1>

    <div class="box-container">

    <?php
    // Query to get all liked videos
    $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
    $select_likes->execute([$user_id]);
    
    if ($select_likes->rowCount() > 0) {
        // Fetch and display liked videos
        while ($fetch_likes = $select_likes->fetch(PDO::FETCH_ASSOC)) {
            $content_id = $fetch_likes['content_id'];

            // Fetch content and tutor information in a single query
            $select_content = $conn->prepare("
                SELECT c.*, t.name AS tutor_name, t.image AS tutor_image 
                FROM `content` c 
                JOIN `tutors` t ON c.tutor_id = t.id 
                WHERE c.id = ? 
                ORDER BY c.date DESC
            ");
            $select_content->execute([$content_id]);

            if ($select_content->rowCount() > 0) {
                while ($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <div class="box">
                        <div class="tutor">
                            <img src="uploaded_files/<?= htmlspecialchars($fetch_content['tutor_image']); ?>" alt="">
                            <div>
                                <h3><?= htmlspecialchars($fetch_content['tutor_name']); ?></h3>
                                <span><?= htmlspecialchars($fetch_content['date']); ?></span>
                            </div>
                        </div>
                        <img src="uploaded_files/<?= htmlspecialchars($fetch_content['thumb']); ?>" alt="" class="thumb">
                        <h3 class="title"><?= htmlspecialchars($fetch_content['title']); ?></h3>
                        <form action="" method="post" class="flex-btn">
                            <input type="hidden" name="content_id" value="<?= htmlspecialchars($fetch_content['id']); ?>">
                            <a href="watch_video.php?get_id=<?= htmlspecialchars($fetch_content['id']); ?>" class="inline-btn">Watch Video</a>
                            <input type="submit" value="Remove" class="inline-delete-btn" name="remove">
                        </form>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="empty">Content not found!</p>';
            }
        }
    } else {
        echo '<p class="empty">Nothing added to likes yet!</p>';
    }
    ?>

    </div>
</section>

<?php include 'components/footer.php'; ?>

<!-- Custom JS File -->
<script src="js/script.js"></script>

</body>
</html>
