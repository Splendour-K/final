<?php

include '../components/connect.php';

// Ensure tutor is authenticated
if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
} else {
   header('location:login.php');
   exit;
}

// Ensure valid playlist ID
if(isset($_GET['get_id'])){
   $get_id = filter_var($_GET['get_id'], FILTER_SANITIZE_STRING);
} else {
   header('location:playlist.php');
   exit;
}

// Handle playlist deletion
if(isset($_POST['delete_playlist'])){
   $delete_id = filter_var($_POST['playlist_id'], FILTER_SANITIZE_STRING);

   $delete_playlist_thumb = $conn->prepare("SELECT thumb FROM `playlist` WHERE id = ? LIMIT 1");
   $delete_playlist_thumb->execute([$delete_id]);
   $fetch_thumb = $delete_playlist_thumb->fetch(PDO::FETCH_ASSOC);

   if(file_exists('../uploaded_files/'.$fetch_thumb['thumb'])){
      unlink('../uploaded_files/'.$fetch_thumb['thumb']);
   }

   $conn->prepare("DELETE FROM `bookmark` WHERE playlist_id = ?")->execute([$delete_id]);
   $conn->prepare("DELETE FROM `playlist` WHERE id = ?")->execute([$delete_id]);

   header('location:playlists.php');
   exit;
}

// Handle video deletion
if(isset($_POST['delete_video'])){
   $delete_id = filter_var($_POST['video_id'], FILTER_SANITIZE_STRING);

   $verify_video = $conn->prepare("SELECT thumb, video FROM `content` WHERE id = ? LIMIT 1");
   $verify_video->execute([$delete_id]);

   if($verify_video->rowCount() > 0){
      $fetch_video = $verify_video->fetch(PDO::FETCH_ASSOC);

      if(file_exists('../uploaded_files/'.$fetch_video['thumb'])){
         unlink('../uploaded_files/'.$fetch_video['thumb']);
      }
      if(file_exists('../uploaded_files/'.$fetch_video['video'])){
         unlink('../uploaded_files/'.$fetch_video['video']);
      }

      $conn->prepare("DELETE FROM `likes` WHERE content_id = ?")->execute([$delete_id]);
      $conn->prepare("DELETE FROM `comments` WHERE content_id = ?")->execute([$delete_id]);
      $conn->prepare("DELETE FROM `content` WHERE id = ?")->execute([$delete_id]);

      $message[] = 'Video deleted successfully!';
   } else {
      $message[] = 'Video already deleted!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Playlist Details</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="playlist-details">
   <h1 class="heading">Playlist Details</h1>

   <?php
      $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? AND tutor_id = ?");
      $select_playlist->execute([$get_id, $tutor_id]);

      if($select_playlist->rowCount() > 0){
         while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)){
            $playlist_id = $fetch_playlist['id'];

            $count_videos = $conn->prepare("SELECT id FROM `content` WHERE playlist_id = ?");
            $count_videos->execute([$playlist_id]);
            $total_videos = $count_videos->rowCount();
   ?>
   <div class="row">
      <div class="thumb">
         <span><?= $total_videos; ?></span>
         <img src="../uploaded_files/<?= htmlspecialchars($fetch_playlist['thumb']); ?>" alt="">
      </div>
      <div class="details">
         <h3 class="title"><?= htmlspecialchars($fetch_playlist['title']); ?></h3>
         <div class="date"><i class="fas fa-calendar"></i><span><?= htmlspecialchars($fetch_playlist['date']); ?></span></div>
         <div class="description"><?= htmlspecialchars($fetch_playlist['description']); ?></div>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="playlist_id" value="<?= $playlist_id; ?>">
            <a href="update_playlist.php?get_id=<?= $playlist_id; ?>" class="option-btn">Update Playlist</a>
            <input type="submit" value="Delete Playlist" class="delete-btn" onclick="return confirm('Delete this playlist?');" name="delete_playlist">
         </form>
      </div>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">No playlist found!</p>';
      }
   ?>

</section>

<section class="contents">
   <h1 class="heading">Playlist Videos</h1>
   <div class="box-container">
      <?php
         $select_videos = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ? AND playlist_id = ?");
         $select_videos->execute([$tutor_id, $get_id]);

         if($select_videos->rowCount() > 0){
            while($fetch_videos = $select_videos->fetch(PDO::FETCH_ASSOC)){
               $video_id = $fetch_videos['id'];
      ?>
      <div class="box">
         <div class="flex">
            <div><i class="fas fa-dot-circle" style="color:<?= $fetch_videos['status'] == 'active' ? 'limegreen' : 'red'; ?>;"></i><span><?= ucfirst($fetch_videos['status']); ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= htmlspecialchars($fetch_videos['date']); ?></span></div>
         </div>
         <img src="../uploaded_files/<?= htmlspecialchars($fetch_videos['thumb']); ?>" class="thumb" alt="">
         <h3 class="title"><?= htmlspecialchars($fetch_videos['title']); ?></h3>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="video_id" value="<?= $video_id; ?>">
            <a href="update_content.php?get_id=<?= $video_id; ?>" class="option-btn">Update</a>
            <input type="submit" value="Delete" class="delete-btn" onclick="return confirm('Delete this video?');" name="delete_video">
         </form>
         <a href="view_content.php?get_id=<?= $video_id; ?>" class="btn">Watch Video</a>
      </div>
      <?php
            }
         } else {
            echo '<p class="empty">No videos added yet! <a href="add_content.php" class="btn" style="margin-top: 1.5rem;">Add Videos</a></p>';
         }
      ?>
   </div>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>
</body>
</html>
