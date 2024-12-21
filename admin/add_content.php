<?php
include '../components/connect.php';

if (!isset($_COOKIE['tutor_id'])) {
    header('location:login.php');
    exit();
}
$tutor_id = $_COOKIE['tutor_id'];

if (isset($_POST['submit'])) {
    $id = unique_id();
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $playlist = filter_var($_POST['playlist'], FILTER_SANITIZE_STRING);

    $thumb = $_FILES['thumb']['name'];
    $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
    $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
    $rename_thumb = unique_id() . '.' . $thumb_ext;
    $thumb_size = $_FILES['thumb']['size'];
    $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
    $thumb_folder = '../uploaded_files/' . $rename_thumb;

    $video = $_FILES['video']['name'];
    $video = filter_var($video, FILTER_SANITIZE_STRING);
    $video_ext = pathinfo($video, PATHINFO_EXTENSION);
    $rename_video = unique_id() . '.' . $video_ext;
    $video_tmp_name = $_FILES['video']['tmp_name'];
    $video_folder = '../uploaded_files/' . $rename_video;

    if ($thumb_size > 2000000) {
        $message[] = 'Image size is too large!';
    } else {
        $add_content = $conn->prepare("INSERT INTO `content` (id, tutor_id, playlist_id, title, description, video, thumb, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $add_content->execute([$id, $tutor_id, $playlist, $title, $description, $rename_video, $rename_thumb, $status]);

        move_uploaded_file($thumb_tmp_name, $thumb_folder);
        move_uploaded_file($video_tmp_name, $video_folder);
        $message[] = 'New course uploaded!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Upload Content</title>
   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <!-- Custom CSS -->
   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
       .btn {
           background-color: #28a745; /* Green color */
           color: white;
           border: none;
           padding: 10px 20px;
           border-radius: 5px;
           cursor: pointer;
           transition: background-color 0.3s ease;
       }
       .btn:hover {
           background-color: #218838; /* Darker green */
       }
       .box {
           border: 1px solid #ccc;
           border-radius: 5px;
           padding: 10px;
       }
   </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="video-form">
   <h1 class="heading">Upload Content</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <p>Video Status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="" selected disabled>-- Select Status</option>
         <option value="active">Active</option>
         <option value="deactive">Deactive</option>
      </select>
      <p>Video Title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="Enter video title" class="box">
      <p>Video Description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="Write description" maxlength="1000" cols="30" rows="10"></textarea>
      <p>Video Playlist <span>*</span></p>
      <select name="playlist" class="box" required>
         <option value="" disabled selected>-- Select Playlist</option>
         <?php
         $select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
         $select_playlists->execute([$tutor_id]);
         if ($select_playlists->rowCount() > 0) {
            while ($fetch_playlist = $select_playlists->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="' . htmlspecialchars($fetch_playlist['id']) . '">' . htmlspecialchars($fetch_playlist['title']) . '</option>';
            }
         } else {
            echo '<option value="" disabled>No playlist created yet!</option>';
         }
         ?>
      </select>
      <p>Select Thumbnail <span>*</span></p>
      <input type="file" name="thumb" accept="image/*" required class="box">
      <p>Select Video <span>*</span></p>
      <input type="file" name="video" accept="video/*" required class="box">
      <input type="submit" value="Upload Video" name="submit" class="btn">
   </form>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>

</body>
</html>
