<?php

include '../components/connect.php';

// Ensure tutor is authenticated
if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
} else {
   header('location:login.php');
   exit;
}

// Sanitize and handle video deletion
if(isset($_POST['delete_video'])) {
   $delete_id = filter_var($_POST['video_id'], FILTER_SANITIZE_STRING);
   $verify_video = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
   $verify_video->execute([$delete_id]);

   if($verify_video->rowCount() > 0) {
      $fetch_thumb = $verify_video->fetch(PDO::FETCH_ASSOC);
      if(file_exists('../uploaded_files/'.$fetch_thumb['thumb'])) unlink('../uploaded_files/'.$fetch_thumb['thumb']);
      if(file_exists('../uploaded_files/'.$fetch_thumb['video'])) unlink('../uploaded_files/'.$fetch_thumb['video']);

      $conn->prepare("DELETE FROM `likes` WHERE content_id = ?")->execute([$delete_id]);
      $conn->prepare("DELETE FROM `comments` WHERE content_id = ?")->execute([$delete_id]);
      $conn->prepare("DELETE FROM `content` WHERE id = ?")->execute([$delete_id]);

      $message[] = 'Video deleted successfully!';
   } else {
      $message[] = 'Video not found or already deleted!';
   }
}

// Sanitize and handle playlist deletion
if(isset($_POST['delete_playlist'])) {
   $delete_id = filter_var($_POST['playlist_id'], FILTER_SANITIZE_STRING);
   $verify_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? AND tutor_id = ? LIMIT 1");
   $verify_playlist->execute([$delete_id, $tutor_id]);

   if($verify_playlist->rowCount() > 0) {
      $fetch_thumb = $verify_playlist->fetch(PDO::FETCH_ASSOC);
      if(file_exists('../uploaded_files/'.$fetch_thumb['thumb'])) unlink('../uploaded_files/'.$fetch_thumb['thumb']);

      $conn->prepare("DELETE FROM `bookmark` WHERE playlist_id = ?")->execute([$delete_id]);
      $conn->prepare("DELETE FROM `playlist` WHERE id = ?")->execute([$delete_id]);

      $message[] = 'Playlist deleted successfully!';
   } else {
      $message[] = 'Playlist not found or already deleted!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- External libraries -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

   <!-- Inline styling for responsiveness -->
   <style>
      .box {
         transition: transform 0.3s ease-in-out;
      }
      .box:hover {
         transform: scale(1.05);
      }
   </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="contents">
   <h1 class="heading">Contents</h1>
   <div class="box-container">
      <?php
         if(isset($_POST['search'])) {
            $search = htmlspecialchars($_POST['search'], ENT_QUOTES, 'UTF-8');
            $select_videos = $conn->prepare("SELECT * FROM `content` WHERE title LIKE ? AND tutor_id = ? ORDER BY date DESC");
            $select_videos->execute(['%'.$search.'%', $tutor_id]);

            if($select_videos->rowCount() > 0) {
               while($fetch_videos = $select_videos->fetch(PDO::FETCH_ASSOC)) {
                  $video_id = $fetch_videos['id'];
      ?>
      <div class="box">
         <div class="flex">
            <div>
               <i class="fas fa-dot-circle" style="color:<?= $fetch_videos['status'] == 'active' ? 'limegreen' : 'red'; ?>;"></i>
               <span><?= $fetch_videos['status']; ?></span>
            </div>
            <div>
               <i class="fas fa-calendar"></i><span><?= $fetch_videos['date']; ?></span>
            </div>
         </div>
         <img src="../uploaded_files/<?= $fetch_videos['thumb']; ?>" class="thumb" alt="Thumbnail">
         <h3 class="title"><?= $fetch_videos['title']; ?></h3>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="video_id" value="<?= $video_id; ?>">
            <a href="update_content.php?get_id=<?= $video_id; ?>" class="option-btn">Update</a>
            <input type="submit" value="Delete" class="delete-btn" onclick="return confirm('Are you sure you want to delete this video?');" name="delete_video">
         </form>
         <a href="view_content.php?get_id=<?= $video_id; ?>" class="btn">View Content</a>
      </div>
      <?php
               }
            } else {
               echo '<p class="empty">No contents found!</p>';
            }
         } else {
            echo '<p class="empty">Please search for something!</p>';
         }
      ?>
   </div>
</section>

<section class="playlists">
   <h1 class="heading">Playlists</h1>
   <div class="box-container">
      <?php
         if(isset($_POST['search'])) {
            $search = htmlspecialchars($_POST['search'], ENT_QUOTES, 'UTF-8');
            $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE title LIKE ? AND tutor_id = ? ORDER BY date DESC");
            $select_playlist->execute(['%'.$search.'%', $tutor_id]);

            if($select_playlist->rowCount() > 0) {
               while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)) {
                  $playlist_id = $fetch_playlist['id'];
                  $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
                  $count_videos->execute([$playlist_id]);
                  $total_videos = $count_videos->rowCount();
      ?>
      <div class="box">
         <div class="flex">
            <div>
               <i class="fas fa-circle-dot" style="color:<?= $fetch_playlist['status'] == 'active' ? 'limegreen' : 'red'; ?>;"></i>
               <span><?= $fetch_playlist['status']; ?></span>
            </div>
            <div>
               <i class="fas fa-calendar"></i><span><?= $fetch_playlist['date']; ?></span>
            </div>
         </div>
         <div class="thumb">
            <span><?= $total_videos; ?></span>
            <img src="../uploaded_files/<?= $fetch_playlist['thumb']; ?>" alt="Thumbnail">
         </div>
         <h3 class="title"><?= $fetch_playlist['title']; ?></h3>
         <p class="description"><?= substr($fetch_playlist['description'], 0, 100); ?>...</p>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="playlist_id" value="<?= $playlist_id; ?>">
            <a href="update_playlist.php?get_id=<?= $playlist_id; ?>" class="option-btn">Update</a>
            <input type="submit" value="Delete" class="delete-btn" onclick="return confirm('Are you sure you want to delete this playlist?');" name="delete_playlist">
         </form>
         <a href="view_playlist.php?get_id=<?= $playlist_id; ?>" class="btn">View Playlist</a>
      </div>
      <?php
               }
            } else {
               echo '<p class="empty">No playlists found!</p>';
            }
         } else {
            echo '<p class="empty">Please search for something!</p>';
         }
      ?>
   </div>
</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>
</body>
</html>
