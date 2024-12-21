<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="css/style.css">

   <style>
       body {
           background-color:rgb(218, 241, 227);
           font-family: Arial, sans-serif;
       }
       .heading {
           text-align: center;
           margin: 20px 0;
           font-size: 2rem;
           color: #333;
       }
       .quick-select {
           padding: 20px;
           max-width: 600px;
           margin: auto;
           background: #fff;
           box-shadow: 0 4px 8px rgba(211, 36, 36, 0.1);
           border-radius: 10px;
       }
       .box-container {
           display: flex;
           flex-wrap: wrap;
           gap: 20px;
           margin-top: 20px;
           justify-content: center;
       }
       .box {
           flex: 1;
           min-width: 250px;
           background: #f8f9fa;
           padding: 20px;
           border-radius: 10px;
           text-align: center;
           box-shadow: 0 2px 4px#b33aeb;
           transition: transform 0.3s;
       }
       .box:hover {
           transform: translateY(-5px);
       }
       .box h3 {
           font-size: 1.2rem;
           color: #007bff;
           margin-bottom: 10px;
       }
       .box p {
           font-size: 1rem;
           color: #555;
           margin-bottom: 10px;
       }
       .box a {
           display: inline-block;
           background: #007bff;
           color: #fff;
           border: none;
           padding: 10px 20px;
           border-radius: 5px;
           cursor: pointer;
           text-decoration: none;
           transition: 0.3s;
       }
       .box a:hover {
           background: #0056b3;
       }
       .tutor {
           background:rgb(79, 130, 218);
           border: 1px solid #007bff;
       }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- Quick Select Section Starts -->

<section class="quick-select">

   <h1 class="heading">Welcome</h1>

   <div class="box-container">

      <div class="box">
         <h3 class="title">Please Login or Register</h3>
         <div class="flex">
            <a href="login.php" class="inline-btn">Login</a>
            <a href="register.php" class="inline-btn">Register</a>
         </div>
      </div>

      <div class="box tutor">
         <h3 class="title">Become a Tutor</h3>
         <p>Share your Entrepreneurship journey with others and inspire future generations.</p>
         <a href="admin/register.php" class="inline-btn">Get Started</a>
         <a href="admin/login.php" class="inline-btn">Login</a>
      </div>

   </div>

</section>

<!-- Quick Select Section Ends -->

<!-- Footer Section Starts -->
<?php include 'components/footer.php'; ?>
<!-- Footer Section Ends -->

<!-- Custom JS File Link -->
<script src="js/script.js"></script>
   
</body>
</html>
