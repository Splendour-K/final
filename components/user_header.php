<?php
if (isset($message)) {
    foreach ($message as $msg) {
        echo '<div class="message">
                <span>' . htmlspecialchars($msg) . '</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
              </div>';
    }
}
?>

<header class="header">
    <section class="flex">

        <a href="home.php" class="logo">CWC<span style="color:rgb(241, 242, 245);">Academy</span></a>

        <form action="search_course.php" method="post" class="search-form">
            <input type="text" name="search_course" placeholder="Search courses..." required maxlength="100" class="search-box">
            <button type="submit" class="fas fa-search search-btn" name="search_course_btn"></button>
        </form>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="search-btn" class="fas fa-search"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
        </div>

        <div class="profile">
            <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if ($select_profile->rowCount() > 0) {
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <img src="uploaded_files/<?= htmlspecialchars($fetch_profile['image']); ?>" alt="Profile Image">
            <h3><?= htmlspecialchars($fetch_profile['name']); ?></h3>
            <span>Student</span>
            <a href="profile.php" class="btn">View Profile</a>
            <a href="components/user_logout.php" onclick="return confirm('Logout from this website?');" class="delete-btn">Logout</a>
            <?php } else { ?>
            <h3>Please login or register</h3>
            <div class="flex-btn">
                <a href="login.php" class="option-btn">Login</a>
                <a href="register.php" class="option-btn">Register</a>
            </div>
            <?php } ?>
        </div>

    </section>
</header>

<!-- Header Section Ends -->

<!-- Sidebar Section Starts -->

<div class="side-bar">

    <div class="close-side-bar">
        <i class="fas fa-times"></i>
    </div>

    <div class="profile">
        <?php
        $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
        $select_profile->execute([$user_id]);
        if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
        <img src="uploaded_files/<?= htmlspecialchars($fetch_profile['image']); ?>" alt="Profile Image">
        <h3><?= htmlspecialchars($fetch_profile['name']); ?></h3>
        <span>Student</span>
        <a href="profile.php" class="btn">View Profile</a>
        <?php } else { ?>
        <h3>Please login or register</h3>
        <div class="flex-btn">
            <a href="login.php" class="option-btn">Login</a>
            <a href="register.php" class="option-btn">Register</a>
        </div>
        <?php } ?>
    </div>

    <nav class="navbar">
        <a href="home.php"><i class="fas fa-home"></i><span>Home</span></a>
        <a href="courses.php"><i class="fas fa-graduation-cap"></i><span>Courses</span></a>
        <a href="teachers.php"><i class="fas fa-chalkboard-user"></i><span>Teachers</span></a>
        <a href="contact.php"><i class="fas fa-headset"></i><span>Contact Us</span></a>
    </nav>

</div>

<!-- Sidebar Section Ends -->

<style>
    .header {
        background:#007bff;
        color: #fff;
        padding: 10px 20px;
    }
    .header .logo {
        font-size: 1.5rem;
        font-weight: bold;
        color: #fff;
    }
    .header .search-form {
        display: flex;
        align-items: center;
    }
    .header .search-form .search-box {
        padding: 5px;
        border: none;
        border-radius: 5px;
        margin-right: 10px;
    }
    .header .search-form .search-btn {
        background: #fff;
        color: #28a745;
        padding: 5px 10px;
        border-radius: 5px;
    }
    .side-bar {
        background: #f8f9fa;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .side-bar .navbar a {
        color: #28a745;
        margin-bottom: 10px;
        display: block;
        text-decoration: none;
        padding: 10px;
        border-radius: 5px;
    }
    .side-bar .navbar a:hover {
        background:rgb(183, 194, 245);
        color: #fff;
    }
</style>
