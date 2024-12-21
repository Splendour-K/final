<?php

include '../components/connect.php';

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ? AND password = ? LIMIT 1");
   $select_tutor->execute([$email, $pass]);
   $row = $select_tutor->fetch(PDO::FETCH_ASSOC);
   
   if($select_tutor->rowCount() > 0){
     setcookie('tutor_id', $row['id'], time() + 60*60*24*30, '/');
     header('location:dashboard.php');
   }else{
      $message[] = 'Incorrect email or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
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
           max-width: 400px;
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
   <form action="" method="post" class="login">
      <h3>Welcome Back!</h3>
      <p>Your Email <span>*</span></p>
      <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
      <p>Your Password <span>*</span></p>
      <input type="password" name="pass" placeholder="Enter your password" maxlength="50" required class="box">
      <p class="link">Don't have an account? <a href="register.php">Register New</a></p>
      <input type="submit" name="submit" value="Login Now" class="btn">
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
