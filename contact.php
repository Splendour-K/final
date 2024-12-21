<?php

include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}

if (isset($_POST['submit'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $msg = filter_var($_POST['msg'], FILTER_SANITIZE_STRING);

    $select_contact = $conn->prepare("SELECT * FROM `contact` WHERE name = ? AND email = ? AND number = ? AND message = ?");
    $select_contact->execute([$name, $email, $number, $msg]);

    if ($select_contact->rowCount() > 0) {
        $message[] = 'Message sent already!';
    } else {
        $insert_message = $conn->prepare("INSERT INTO `contact`(name, email, number, message) VALUES(?,?,?,?)");
        $insert_message->execute([$name, $email, $number, $msg]);
        $message[] = 'Message sent successfully!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="css/style.css">

   <style>
       body {
           background-color: #f9f9f9;
           font-family: Arial, sans-serif;
       }
       .heading {
           text-align: center;
           margin: 20px 0;
           font-size: 2rem;
           color: #333;
       }
       .contact {
           padding: 20px;
           max-width: 1200px;
           margin: auto;
           background: #fff;
           box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
           border-radius: 10px;
       }
       .contact .row {
           display: flex;
           flex-wrap: wrap;
           gap: 20px;
           align-items: center;
       }
       .contact .image img {
           width: 100%;
           max-width: 500px;
           border-radius: 10px;
       }
       .contact form {
           flex: 1;
           padding: 20px;
           background: #f8f9fa;
           border-radius: 10px;
       }
       .contact form h3 {
           font-size: 1.5rem;
           margin-bottom: 10px;
           color: #007bff;
       }
       .contact form .box {
           width: 100%;
           margin: 10px 0;
           padding: 10px;
           border: 1px solid #ccc;
           border-radius: 5px;
       }
       .contact form .inline-btn {
           background: #007bff;
           color: #fff;
           border: none;
           padding: 10px 20px;
           border-radius: 5px;
           cursor: pointer;
           transition: 0.3s;
       }
       .contact form .inline-btn:hover {
           background: #0056b3;
       }
       .box-container {
           display: flex;
           flex-wrap: wrap;
           gap: 20px;
           margin-top: 20px;
       }
       .box {
           flex: 1;
           min-width: 250px;
           background: #f8f9fa;
           padding: 20px;
           border-radius: 10px;
           text-align: center;
           box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
       }
       .box i {
           font-size: 2rem;
           color: #007bff;
           margin-bottom: 10px;
       }
       .box h3 {
           font-size: 1.2rem;
           color: #333;
           margin-bottom: 10px;
       }
       .box a {
           color: #007bff;
           text-decoration: none;
       }
       .box a:hover {
           text-decoration: underline;
       }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- Contact Section Starts -->

<section class="contact">
   <div class="row">
      <div class="image">
         <img src="images/contact-img.svg" alt="Contact Image">
      </div>
      <form action="" method="post">
         <h3>Get in Touch</h3>
         <input type="text" placeholder="Enter your name" required maxlength="100" name="name" class="box">
         <input type="email" placeholder="Enter your email" required maxlength="100" name="email" class="box">
         <input type="number" min="0" max="9999999999" placeholder="Enter your number" required maxlength="10" name="number" class="box">
         <textarea name="msg" class="box" placeholder="Enter your message" required cols="30" rows="10" maxlength="1000"></textarea>
         <input type="submit" value="Send Message" class="inline-btn" name="submit">
      </form>
   </div>
   <div class="box-container">
      <div class="box">
         <i class="fas fa-phone"></i>
         <h3>Phone Number</h3>
         <a href="tel:0903400000">0903400000</a>
         <a href="tel:0903400000">090230000</a>
      </div>
      <div class="box">
         <i class="fas fa-envelope"></i>
         <h3>Email Address</h3>
         <a href="mailto:splendor@gmail.com">splendor@gmail.com</a>
         <a href="mailto:splendor@gmail.com">splendor@gmail.com</a>
      </div>
      <div class="box">
         <i class="fas fa-map-marker-alt"></i>
         <h3>Office Address</h3>
         <a href="#">no. 1 Berekuso, Ashesi University, Accra, Ghana - 400104</a>
      </div>
   </div>
</section>

<!-- Contact Section Ends -->

<?php include 'components/footer.php'; ?>  

<!-- Custom JS File Link -->
<script src="js/script.js"></script>
   
</body>
</html>
