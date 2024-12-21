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

// Handle playlist update
if(isset($_POST['submit'])){
   $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
   $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
   $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);

   $update_playlist = $conn->prepare("UPDATE `playlist` SET title = ?, description = ?, status = ? WHERE id = ?");
   $update_playlist->execute([$title, $description, $status, $get_id]);

   $old_image = filter_var($_POST['old_image'], FILTER_SANITIZE_STRING);
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_files/'.$rename;

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'Image size is too large!';
      } else {
         $update_image = $conn->prepare("UPDATE `playlist` SET thumb = ? WHERE id = ?");
         $update_image->execute([$rename, $get_id]);
         move_uploaded_file($image_tmp_name, $image_folder);
         if($old_image && $old_image != $rename && file_exists('../uploaded_files/'.$old_image)){
            unlink('../uploaded_files/'.$old_image);
         }
      }
   }

   $message[] = 'Playlist updated successfully!';  
}

// Handle playlist deletion
if(isset($_POST['delete'])){
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Playlist</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="playlist-form">
   <h1 class="heading">Update Playlist</h1>

   <?php
      $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ?");
      $select_playlist->execute([$get_id]);
      if($select_playlist->rowCount() > 0){
         while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)){
            $playlist_id = $fetch_playlist['id'];
            $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
            $count_videos->execute([$playlist_id]);
            $total_videos = $count_videos->rowCount();
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="old_image" value="<?= $fetch_playlist['thumb']; ?>">
      <input type="hidden" name="playlist_id" value="<?= $playlist_id; ?>">
      <p>Playlist Status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="<?= $fetch_playlist['status']; ?>" selected><?= ucfirst($fetch_playlist['status']); ?></option>
         <option value="active">Active</option>
         <option value="deactive">Deactive</option>
      </select>
      <p>Playlist Title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="Enter playlist title" value="<?= $fetch_playlist['title']; ?>" class="box">
      <p>Playlist Description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="Write description" maxlength="1000" cols="30" rows="10"><?= $fetch_playlist['description']; ?></textarea>
      <p>Playlist Thumbnail <span>*</span></p>
      <div class="thumb">
         <span><?= $total_videos; ?></span>
         <img src="../uploaded_files/<?= $fetch_playlist['thumb']; ?>" alt="">
      </div>
      <input type="file" name="image" accept="image/*" class="box">
      <input type="submit" value="Update Playlist" name="submit" class="btn">
      <div class="flex-btn">
         <input type="submit" value="Delete" class="delete-btn" onclick="return confirm('Are you sure you want to delete this playlist?');" name="delete">
         <a href="view_playlist.php?get_id=<?= $playlist_id; ?>" class="option-btn">View Playlist</a>
      </div>
   </form>
   <?php
         }
      } else {
         echo '<p class="empty">Playlist not found! <a href="add_playlist.php" class="btn" style="margin-top: 1.5rem;">Add Playlist</a></p>';
      }
   ?>

</section>

<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>
</body>
</html>
