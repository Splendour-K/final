<?php

include '../components/connect.php';

if (!isset($_COOKIE['tutor_id'])) {
    header('location:login.php');
    exit();
}
$tutor_id = $_COOKIE['tutor_id'];

if (isset($_POST['delete'])) {
    $delete_id = filter_var($_POST['playlist_id'], FILTER_SANITIZE_STRING);

    $verify_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? AND tutor_id = ? LIMIT 1");
    $verify_playlist->execute([$delete_id, $tutor_id]);

    if ($verify_playlist->rowCount() > 0) {
        $delete_playlist_thumb = $conn->prepare("SELECT thumb FROM `playlist` WHERE id = ? LIMIT 1");
        $delete_playlist_thumb->execute([$delete_id]);
        $fetch_thumb = $delete_playlist_thumb->fetch(PDO::FETCH_ASSOC);

        if ($fetch_thumb && file_exists('../uploaded_files/' . $fetch_thumb['thumb'])) {
            unlink('../uploaded_files/' . $fetch_thumb['thumb']);
        }

        $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE playlist_id = ?");
        $delete_bookmark->execute([$delete_id]);

        $delete_playlist = $conn->prepare("DELETE FROM `playlist` WHERE id = ?");
        $delete_playlist->execute([$delete_id]);

        $message[] = 'Playlist deleted!';
    } else {
        $message[] = 'Playlist already deleted!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Playlists</title>
   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <!-- Custom CSS -->
   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
       .playlists {
           background: linear-gradient(135deg, #f8f9fa, #e9ecef);
           padding: 20px;
       }
       .box-container {
           display: grid;
           grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
           gap: 20px;
       }
       .box {
           background: #ffffff;
           border-radius: 10px;
           padding: 20px;
           box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
           transition: transform 0.3s ease;
       }
       .box:hover {
           transform: translateY(-5px);
       }
       .btn, .option-btn, .delete-btn {
           background-color: #007bff;
           color: white;
           padding: 10px 15px;
           border-radius: 5px;
           text-decoration: none;
           transition: background-color 0.3s ease;
       }
       .btn:hover, .option-btn:hover, .delete-btn:hover {
           background-color: #0056b3;
       }
       .thumb {
           position: relative;
           text-align: center;
       }
       .thumb span {
           position: absolute;
           top: 10px;
           left: 10px;
           background: #007bff;
           color: white;
           padding: 5px 10px;
           border-radius: 5px;
           font-size: 14px;
       }
       .message {
           background: #ffc107;
           color: #856404;
           padding: 10px;
           border-radius: 5px;
           margin-bottom: 10px;
           text-align: center;
           position: relative;
       }
       .message i {
           position: absolute;
           top: 10px;
           right: 10px;
           cursor: pointer;
       }
   </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="playlists">

   <h1 class="heading">Added Playlists</h1>

   <div class="box-container">

      <div class="box" style="text-align: center;">
         <h3 class="title" style="margin-bottom: .5rem;">Create New Playlist</h3>
         <a href="add_playlist.php" class="btn">Add Playlist</a>
      </div>

      <?php
         $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ? ORDER BY date DESC");
         $select_playlist->execute([$tutor_id]);
         if ($select_playlist->rowCount() > 0) {
            while ($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)) {
               $playlist_id = $fetch_playlist['id'];
               $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
               $count_videos->execute([$playlist_id]);
               $total_videos = $count_videos->rowCount();
      ?>
      <div class="box">
         <div class="flex">
            <div><i class="fas fa-circle-dot" style="<?php echo $fetch_playlist['status'] === 'active' ? 'color:limegreen;' : 'color:red;'; ?>"></i><span style="<?php echo $fetch_playlist['status'] === 'active' ? 'color:limegreen;' : 'color:red;'; ?>"><?= htmlspecialchars($fetch_playlist['status']); ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= htmlspecialchars($fetch_playlist['date']); ?></span></div>
         </div>
         <div class="thumb">
            <span><?= $total_videos; ?></span>
            <img src="../uploaded_files/<?= htmlspecialchars($fetch_playlist['thumb']); ?>" alt="Playlist Thumbnail">
         </div>
         <h3 class="title"><?= htmlspecialchars($fetch_playlist['title']); ?></h3>
         <p class="description"><?= htmlspecialchars($fetch_playlist['description']); ?></p>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="playlist_id" value="<?= $playlist_id; ?>">
            <a href="update_playlist.php?get_id=<?= $playlist_id; ?>" class="option-btn">Update</a>
            <input type="submit" value="Delete" class="delete-btn" onclick="return confirm('Delete this playlist?');" name="delete">
         </form>
         <a href="view_playlist.php?get_id=<?= $playlist_id; ?>" class="btn">View Playlist</a>
      </div>
      <?php
            }
         } else {
            echo '<p class="empty">No playlist added yet!</p>';
         }
      ?>

   </div>

</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

<script>
   document.querySelectorAll('.playlists .box-container .box .description').forEach(content => {
      if (content.innerHTML.length > 100) content.innerHTML = content.innerHTML.slice(0, 100);
   });
</script>

</body>
</html>
