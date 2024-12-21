<?php
include '../components/connect.php';

if (!isset($_COOKIE['tutor_id'])) {
    header('location:login.php');
    exit();
}
$tutor_id = $_COOKIE['tutor_id'];

if (isset($_POST['delete_comment'])) {
    $delete_id = filter_var($_POST['comment_id'], FILTER_SANITIZE_STRING);

    $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ?");
    $verify_comment->execute([$delete_id]);

    if ($verify_comment->rowCount() > 0) {
        $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
        $delete_comment->execute([$delete_id]);
        $message[] = 'Comment deleted successfully!';
    } else {
        $message[] = 'Comment already deleted!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Comments</title>
   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <!-- Custom CSS -->
   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
       .box {
           border: 1px solid #ccc;
           border-radius: 5px;
           padding: 10px;
           margin-bottom: 15px;
           background-color: #f9f9f9;
       }
       .inline-delete-btn {
           background-color: #dc3545;
           color: white;
           border: none;
           padding: 5px 10px;
           border-radius: 3px;
           cursor: pointer;
           transition: background-color 0.3s ease;
       }
       .inline-delete-btn:hover {
           background-color: #c82333;
       }
   </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="comments">
   <h1 class="heading">User Comments</h1>

   <div class="show-comments">
      <?php
      $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
      $select_comments->execute([$tutor_id]);

      if ($select_comments->rowCount() > 0) {
          while ($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)) {
              $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ?");
              $select_content->execute([$fetch_comment['content_id']]);
              $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <div class="content">
            <span><?= htmlspecialchars($fetch_comment['date']); ?></span>
            <p> - <?= htmlspecialchars($fetch_content['title']); ?> - </p>
            <a href="view_content.php?get_id=<?= htmlspecialchars($fetch_content['id']); ?>">View Content</a>
         </div>
         <p class="text">"<?= htmlspecialchars($fetch_comment['comment']); ?>"</p>
         <form action="" method="post">
            <input type="hidden" name="comment_id" value="<?= htmlspecialchars($fetch_comment['id']); ?>">
            <button type="submit" name="delete_comment" class="inline-delete-btn" onclick="return confirm('Delete this comment?');">Delete Comment</button>
         </form>
      </div>
      <?php
          }
      } else {
          echo '<p class="empty">No comments added yet!</p>';
      }
      ?>
   </div>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>

</body>
</html>
