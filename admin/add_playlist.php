<?php
include '../components/connect.php';

if (!isset($_COOKIE['tutor_id'])) {
    header('location:login.php');
    exit();
}
$tutor_id = $_COOKIE['tutor_id'];

if (isset($_POST['submit'])) {
    $id = unique_id();
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = unique_id() . '.' . $ext;
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_files/' . $rename;

    if ($image_size > 2000000) {
        $message[] = 'Image size is too large!';
    } else {
        $add_playlist = $conn->prepare("INSERT INTO `playlist` (id, tutor_id, title, description, thumb, status) VALUES (?, ?, ?, ?, ?, ?)");
        $add_playlist->execute([$id, $tutor_id, $title, $description, $rename, $status]);
        move_uploaded_file($image_tmp_name, $image_folder);
        $message[] = 'New playlist created!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Playlist</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="playlist-form">
    <h1 class="heading">Create Playlist</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <p>Playlist Status <span>*</span></p>
        <select name="status" class="box" required>
            <option value="" selected disabled>-- Select Status</option>
            <option value="active">Active</option>
            <option value="deactive">Deactive</option>
        </select>
        <p>Playlist Title <span>*</span></p>
        <input type="text" name="title" maxlength="100" required placeholder="Enter playlist title" class="box">
        <p>Playlist Description <span>*</span></p>
        <textarea name="description" class="box" required placeholder="Write description" maxlength="1000" cols="30" rows="10"></textarea>
        <p>Playlist Thumbnail <span>*</span></p>
        <input type="file" name="image" accept="image/*" required class="box">
        <input type="submit" value="Create Playlist" name="submit" class="btn">
    </form>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>

</body>
</html>
