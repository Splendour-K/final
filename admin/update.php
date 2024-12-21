<?php

include '../components/connect.php';

// Ensure tutor is authenticated
if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
} else {
   header('location:login.php');
   exit;
}

if(isset($_POST['submit'])){

   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ? LIMIT 1");
   $select_tutor->execute([$tutor_id]);
   $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);

   $prev_pass = $fetch_tutor['password'];
   $prev_image = $fetch_tutor['image'];

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $profession = filter_var($_POST['profession'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

   // Update name
   if(!empty($name)){
      $update_name = $conn->prepare("UPDATE `tutors` SET name = ? WHERE id = ?");
      $update_name->execute([$name, $tutor_id]);
      $message[] = 'Username updated successfully!';
   }

   // Update profession
   if(!empty($profession)){
      $update_profession = $conn->prepare("UPDATE `tutors` SET profession = ? WHERE id = ?");
      $update_profession->execute([$profession, $tutor_id]);
      $message[] = 'Profession updated successfully!';
   }

   // Update email
   if(!empty($email)){
      $select_email = $conn->prepare("SELECT email FROM `tutors` WHERE email = ? AND id != ?");
      $select_email->execute([$email, $tutor_id]);
      if($select_email->rowCount() > 0){
         $message[] = 'Email already taken!';
      } else {
         $update_email = $conn->prepare("UPDATE `tutors` SET email = ? WHERE id = ?");
         $update_email->execute([$email, $tutor_id]);
         $message[] = 'Email updated successfully!';
      }
   }

   // Update image
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_files/'.$rename;

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'Image size too large!';
      } else {
         $update_image = $conn->prepare("UPDATE `tutors` SET image = ? WHERE id = ?");
         $update_image->execute([$rename, $tutor_id]);
         move_uploaded_file($image_tmp_name, $image_folder);
         if($prev_image && $prev_image != $rename && file_exists('../uploaded_files/'.$prev_image)){
            unlink('../uploaded_files/'.$prev_image);
         }
         $message[] = 'Image updated successfully!';
      }
   }

   // Update password
   $empty_pass = sha1('');
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
         $message[] = 'Old password does not match!';
      } elseif($new_pass != $cpass){
         $message[] = 'Confirm password does not match!';
      } else {
         if($new_pass != $empty_pass){
            $update_pass = $conn->prepare("UPDATE `tutors` SET password = ? WHERE id = ?");
            $update_pass->execute([$new_pass, $tutor_id]);
            $message[] = 'Password updated successfully!';
         } else {
            $message[] = 'Please enter a new password!';
         }
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Profile</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container" style="min-height: calc(100vh - 19rem);">
   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>Update Profile</h3>
      <div class="flex">
         <div class="col">
            <p>Your Name</p>
            <input type="text" name="name" placeholder="<?= htmlspecialchars($fetch_tutor['name']); ?>" maxlength="50" class="box">
            <p>Your Profession</p>
            <select name="profession" class="box">
               <option value="" selected><?= htmlspecialchars($fetch_tutor['profession']); ?></option>
               <option value="developer">Developer</option>
               <option value="designer">Designer</option>
               <option value="musician">Musician</option>
               <option value="biologist">Biologist</option>
               <option value="teacher">Teacher</option>
               <option value="tech startup">Tech Startup</option>
            </select>
            <p>Your Email</p>
            <input type="email" name="email" placeholder="<?= htmlspecialchars($fetch_tutor['email']); ?>" maxlength="50" class="box">
         </div>
         <div class="col">
            <p>Old Password</p>
            <input type="password" name="old_pass" placeholder="Enter your old password" maxlength="20" class="box">
            <p>New Password</p>
            <input type="password" name="new_pass" placeholder="Enter your new password" maxlength="20" class="box">
            <p>Confirm Password</p>
            <input type="password" name="cpass" placeholder="Confirm your new password" maxlength="20" class="box">
         </div>
      </div>
      <p>Update Profile Picture</p>
      <input type="file" name="image" accept="image/*" class="box">
      <input type="submit" name="submit" value="Update Now" class="btn">
   </form>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>
</body>
</html>
