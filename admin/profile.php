<?php

include '../components/connect.php';

if (!isset($_COOKIE['tutor_id'])) {
    header('location:login.php');
    exit();
}
$tutor_id = $_COOKIE['tutor_id'];

$select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
$select_playlists->execute([$tutor_id]);
$total_playlists = $select_playlists->rowCount();

$select_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
$select_contents->execute([$tutor_id]);
$total_contents = $select_contents->rowCount();

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
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
       body {
           background: linear-gradient(135deg, #f8f9fa, #e9ecef);
           font-family: Arial, sans-serif;
           padding: 20px;
       }
       .tutor-profile {
           background: #fff;
           padding: 20px;
           border-radius: 10px;
           box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
           max-width: 800px;
           margin: 0 auto;
       }
       .tutor-profile h1 {
           color: #007bff;
           text-align: center;
           margin-bottom: 20px;
       }
       .details {
           display: flex;
           flex-direction: column;
           align-items: center;
           gap: 20px;
       }
       .details .tutor {
           text-align: center;
       }
       .details .tutor img {
           width: 100px;
           height: 100px;
           border-radius: 50%;
           margin-bottom: 10px;
       }
       .details .flex {
           display: flex;
           flex-wrap: wrap;
           gap: 15px;
           justify-content: center;
       }
       .details .box {
           background: #f8f9fa;
           padding: 15px;
           border-radius: 8px;
           text-align: center;
           width: 150px;
           box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
       }
       .details .box span {
           font-size: 24px;
           color: #007bff;
           font-weight: bold;
       }
       .details .box p {
           margin: 10px 0;
           color: #6c757d;
       }
       .details .btn {
           background: #007bff;
           color: #fff;
           padding: 10px 15px;
           border-radius: 5px;
           text-decoration: none;
           transition: background 0.3s;
       }
       .details .btn:hover {
           background: #0056b3;
       }
   </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="tutor-profile"> 

   <h1 class="heading">Profile Details</h1>

   <div class="details">
      <div class="tutor">
         <img src="../uploaded_files/<?= htmlspecialchars($fetch_profile['image']); ?>" alt="Profile Picture">
         <h3><?= htmlspecialchars($fetch_profile['name']); ?></h3>
         <span><?= htmlspecialchars($fetch_profile['profession']); ?></span>
         <a href="update.php" class="btn">Update Profile</a>
      </div>
      <div class="flex">
         <div class="box">
            <span><?= $total_playlists; ?></span>
            <p>Total Playlists</p>
            <a href="playlists.php" class="btn">View Playlists</a>
         </div>
         <div class="box">
            <span><?= $total_contents; ?></span>
            <p>Total Videos</p>
            <a href="contents.php" class="btn">View Contents</a>
         </div>
         <div class="box">
            <span><?= $total_likes; ?></span>
            <p>Total Likes</p>
            <a href="contents.php" class="btn">View Contents</a>
         </div>
         <div class="box">
            <span><?= $total_comments; ?></span>
            <p>Total Comments</p>
            <a href="comments.php" class="btn">View Comments</a>
         </div>
      </div>
   </div>

</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>
