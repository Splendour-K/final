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

        <a href="dashboard.php" class="logo">Admin<span style="color: #007bff;">Panel</span></a>

        <form action="search_page.php" method="post" class="search-form">
            <input type="text" name="search" placeholder="Search here..." required maxlength="100" class="search-box">
            <button type="submit" class="fas fa-search search-btn" name="search_btn"></button>
        </form>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="search-btn" class="fas fa-search"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
        </div>

        <div class="profile">
            <?php
            $select_profile = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
            $select_profile->execute([$tutor_id]);
            if ($select_profile->rowCount() > 0) {
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <img src="../uploaded_files/<?= htmlspecialchars($fetch_profile['image']); ?>" alt="Profile Image">
            <h3><?= htmlspecialchars($fetch_profile['name']); ?></h3>
            <span><?= htmlspecialchars($fetch_profile['profession']); ?></span>
            <a href="profile.php" class="btn">View Profile</a>
            <div class="flex-btn">
                <a href="login.php" class="option-btn">Login</a>
                <a href="register.php" class="option-btn">Register</a>
            </div>
            <a href="../components/admin_logout.php" onclick="return confirm('Logout from this website?');" class="delete-btn">Logout</a>
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
        $select_profile = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
        $select_profile->execute([$tutor_id]);
        if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
        <img src="../uploaded_files/<?= htmlspecialchars($fetch_profile['image']); ?>" alt="Profile Image">
        <h3><?= htmlspecialchars($fetch_profile['name']); ?></h3>
        <span><?= htmlspecialchars($fetch_profile['profession']); ?></span>
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
        <a href="dashboard.php"><i class="fas fa-home"></i><span>Home</span></a>
        <a href="playlists.php"><i class="fa-solid fa-bars-staggered"></i><span>Playlists</span></a>
        <a href="contents.php"><i class="fas fa-graduation-cap"></i><span>Contents</span></a>
        <a href="comments.php"><i class="fas fa-comment"></i><span>Comments</span></a>
        <a href="../components/admin_logout.php" onclick="return confirm('Logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>Logout</span></a>
    </nav>

</div>

<!-- Sidebar Section Ends -->

<style>
    .header {
        background: #007bff;
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
        color: #007bff;
        padding: 5px 10px;
        border-radius: 5px;
    }
    .side-bar {
        background: #f8f9fa;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .side-bar .navbar a {
        color: #007bff;
        margin-bottom: 10px;
        display: block;
        text-decoration: none;
        padding: 10px;
        border-radius: 5px;
    }
    .side-bar .navbar a:hover {
        background:rgb(196, 211, 226);
        color: #fff;
    }
</style>
