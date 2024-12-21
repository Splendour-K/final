<?php

include '../components/connect.php';

// Ensure tutor is authenticated
if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
} else {
   header('location:login.php');
   exit;
}

// Ensure valid content ID
if(isset($_GET['get_id'])){
   $get_id = filter_var($_GET['get_id'], FILTER_SANITIZE_STRING);
} else {
   header('location:dashboard.php');
   exit;
}

// Handle content update
if(isset($_POST['update'])){
   $video_id = filter_var($_POST['video_id'], FILTER_SANITIZE_STRING);
   $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
   $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
   $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
   $playlist = filter_var($_POST['playlist'], FILTER_SANITIZE_STRING);

   $update_content = $conn->prepare("UPDATE `content` SET title = ?, description = ?, status = ? WHERE id = ?");
   $update_content->execute([$title, $description, $status, $video_id]);

   if(!empty($playlist)){
      $update_playlist = $conn->prepare("UPDATE `content` SET playlist_id = ? WHERE id = ?");
      $update_playlist->execute([$playlist, $video_id]);
   }

   $old_thumb = filter_var($_POST['old_thumb'], FILTER_SANITIZE_STRING);
   $thumb = $_FILES['thumb']['name'];
   $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
   $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
   $rename_thumb = unique_id().'.'.$thumb_ext;
   $thumb_size = $_FILES['thumb']['size'];
   $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
   $thumb_folder = '../uploaded_files/'.$rename_thumb;

   if(!empty($thumb)){
      if($thumb_size > 2000000){
         $message[] = 'Image size is too large!';
      } else {
         $update_thumb = $conn->prepare("UPDATE `content` SET thumb = ? WHERE id = ?");
         $update_thumb->execute([$rename_thumb, $video_id]);
         move_uploaded_file($thumb_tmp_name, $thumb_folder);
         if($old_thumb && $old_thumb != $rename_thumb && file_exists('../uploaded_files/'.$old_thumb)){
            unlink('../uploaded_files/'.$old_thumb);
         }
      }
   }

   $old_video = filter_var($_POST['old_video'], FILTER_SANITIZE_STRING);
   $video = $_FILES['video']['name'];
   $video = filter_var($video, FILTER_SANITIZE_STRING);
   $video_ext = pathinfo($video, PATHINFO_EXTENSION);
   $rename_video = unique_id().'.'.$video_ext;
   $video_tmp_name = $_FILES['video']['tmp_name'];
   $video_folder = '../uploaded_files/'.$rename_video;

   if(!empty($video)){
      $update_video = $conn->prepare("UPDATE `content` SET video = ? WHERE id = ?");
      $update_video->execute([$rename_video, $video_id]);
      move_uploaded_file($video_tmp_name, $video_folder);
      if($old_video && $old_video != $rename_video && file_exists('../uploaded_files/'.$old_video)){
         unlink('../uploaded_files/'.$old_video);
      }
   }

   $message[] = 'Content updated successfully!';
}

// Handle content deletion
if(isset($_POST['delete_video'])){
   $delete_id = filter_var($_POST['video_id'], FILTER_SANITIZE_STRING);

   $delete_video_thumb = $conn->prepare("SELECT thumb FROM `content` WHERE id = ? LIMIT 1");
   $delete_video_thumb->execute([$delete_id]);
   $fetch_thumb = $delete_video_thumb->fetch(PDO::FETCH_ASSOC);
   if(file_exists('../uploaded_files/'.$fetch_thumb['thumb'])){
      unlink('../uploaded_files/'.$fetch_thumb['thumb']);
   }

   $delete_video = $conn->prepare("SELECT video FROM `content` WHERE id = ? LIMIT 1");
   $delete_video->execute([$delete_id]);
   $fetch_video = $delete_video->fetch(PDO::FETCH_ASSOC);
   if(file_exists('../uploaded_files/'.$fetch_video['video'])){
      unlink('../uploaded_files/'.$fetch_video['video']);
   }

   $conn->prepare("DELETE FROM `likes` WHERE content_id = ?")->execute([$delete_id]);
   $conn->prepare("DELETE FROM `comments` WHERE content_id = ?")->execute([$delete_id]);
   $conn->prepare("DELETE FROM `content` WHERE id = ?")->execute([$delete_id]);

   header('location:contents.php');
   exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Content</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="video-form">
   <h1 class="heading">Update Content</h1>

   <?php
      $select_videos = $conn->prepare("SELECT * FROM `content` WHERE id = ? AND tutor_id = ?");
      $select_videos->execute([$get_id, $tutor_id]);
      if($select_videos->rowCount() > 0){
         while($fetch_videos = $select_videos->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="video_id" value="<?= $fetch_videos['id']; ?>">
      <input type="hidden" name="old_thumb" value="<?= $fetch_videos['thumb']; ?>">
      <input type="hidden" name="old_video" value="<?= $fetch_videos['video']; ?>">
      <p>Update Status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="<?= $fetch_videos['status']; ?>" selected><?= ucfirst($fetch_videos['status']); ?></option>
         <option value="active">Active</option>
         <option value="deactive">Deactive</option>
      </select>
      <p>Update Title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="Enter video title" class="box" value="<?= $fetch_videos['title']; ?>">
      <p>Update Description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="Write description" maxlength="1000" cols="30" rows="10"><?= $fetch_videos['description']; ?></textarea>
      <p>Update Playlist</p>
      <select name="playlist" class="box">
         <option value="<?= $fetch_videos['playlist_id']; ?>" selected>--Select Playlist--</option>
         <?php
            $select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
            $select_playlists->execute([$tutor_id]);
            if($select_playlists->rowCount() > 0){
               while($fetch_playlist = $select_playlists->fetch(PDO::FETCH_ASSOC)){
         ?>
         <option value="<?= $fetch_playlist['id']; ?>"><?= $fetch_playlist['title']; ?></option>
         <?php
               }
            } else {
               echo '<option value="" disabled>No playlists available</option>';
            }
         ?>
      </select>
      <p>Update Thumbnail</p>
      <img src="../uploaded_files/<?= $fetch_videos['thumb']; ?>" alt="">
      <input type="file" name="thumb" accept="image/*" class="box">
      <p>Update Video</p>
      <video src="../uploaded_files/<?= $fetch_videos['video']; ?>" controls></video>
      <input type="file" name="video" accept="video/*" class="box">
      <input type="submit" value="Update Content" name="update" class="btn">
      <div class="flex-btn">
         <a href="view_content.php?get_id=<?= $fetch_videos['id']; ?>" class="option-btn">View Content</a>
         <input type="submit" value="Delete Content" name="delete_video" class="delete-btn" onclick="return confirm('Are you sure you want to delete this content?');">
      </div>
   </form>
   <?php
         }
      } else {
         echo '<p class="empty">Content not found! <a href="add_content.php" class="btn" style="margin-top: 1.5rem;">Add Content</a></p>';
      }
   ?>

</section>

<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>
</body>
</html>
