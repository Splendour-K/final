<?php

include '../components/connect.php';

if(isset($_POST['submit'])){

   $id = unique_id();
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $profession = filter_var($_POST['profession'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $pass = sha1(filter_var($_POST['pass'], FILTER_SANITIZE_STRING));
   $cpass = sha1(filter_var($_POST['cpass'], FILTER_SANITIZE_STRING));

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_files/'.$rename;

   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ?");
   $select_tutor->execute([$email]);
   
   if($select_tutor->rowCount() > 0){
      $message[] = 'Email already taken!';
   }else{
      if($pass != $cpass){
         $message[] = 'Passwords do not match!';
      }else{
         $insert_tutor = $conn->prepare("INSERT INTO `tutors`(id, name, profession, email, password, image) VALUES(?,?,?,?,?,?)");
         $insert_tutor->execute([$id, $name, $profession, $email, $cpass, $rename]);
         move_uploaded_file($image_tmp_name, $image_folder);
         $message[] = 'Registration successful! Please login now.';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>
   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <!-- Custom CSS -->
   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
       body {
           padding-left: 0;
           display: flex;
           justify-content: center;
           align-items: center;
           min-height: 100vh;
           background: linear-gradient(135deg, #f8f9fa, #e9ecef);
       }
       .form-container {
           background: #ffffff;
           border-radius: 10px;
           padding: 30px;
           box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
           max-width: 600px;
           width: 100%;
       }
       .form-container h3 {
           color: #007bff;
       }
       .btn {
           background-color: #007bff;
           color: white;
           padding: 10px 20px;
           border-radius: 5px;
           border: none;
           cursor: pointer;
           transition: background-color 0.3s ease;
       }
       .btn:hover {
           background-color: #0056b3;
       }
       .link a {
           color: #007bff;
           text-decoration: none;
       }
       .link a:hover {
           text-decoration: underline;
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

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message form">
         <span>'.htmlspecialchars($message).'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<section class="form-container">

   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>Register New</h3>
      <div class="flex">
         <div class="col">
            <p>Your Name <span>*</span></p>
            <input type="text" name="name" placeholder="Enter your name" maxlength="50" required class="box">
            <p>Your Profession <span>*</span></p>
            <select name="profession" class="box" required>
               <option value="" disabled selected>-- Select Your Profession</option>
               <option value="Entrepreneur">Entrepreneur</option>
               <option value="Designer">Designer</option>
               <option value="Founder">Founder</option>
               <option value="Teacher">Teacher</option>
            </select>
            <p>Your Email <span>*</span></p>
            <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
         </div>
         <div class="col">
            <p>Your Password <span>*</span></p>
            <input type="password" name="pass" placeholder="Enter your password" maxlength="50" required class="box">
            <p>Confirm Password <span>*</span></p>
            <input type="password" name="cpass" placeholder="Confirm your password" maxlength="50" required class="box">
            <p>Select Picture <span>*</span></p>
            <input type="file" name="image" accept="image/*" required class="box">
         </div>
      </div>
      <p class="link">Already have an account? <a href="login.php">Login Now</a></p>
      <input type="submit" name="submit" value="Register Now" class="btn">
   </form>

</section>

<script>
let darkMode = localStorage.getItem('dark-mode');
let body = document.body;

const enableDarkMode = () => {
   body.classList.add('dark');
   localStorage.setItem('dark-mode', 'enabled');
};

const disableDarkMode = () => {
   body.classList.remove('dark');
   localStorage.setItem('dark-mode', 'disabled');
};

if (darkMode === 'enabled') {
   enableDarkMode();
} else {
   disableDarkMode();
}
</script>

</body>
</html>
