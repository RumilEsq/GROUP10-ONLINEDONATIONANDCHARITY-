<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get current directory
$base_dir = __DIR__;

// Define file paths
$header_file = $base_dir . '/header.php';
$footer_file = $base_dir . '/footer.php';

// Check if files exist
if (!file_exists($header_file)) {
    die("Error: header.php not found at $header_file");
}

if (!file_exists($footer_file)) {
    die("Error: footer.php not found at $footer_file");
}

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include header
include $header_file;
?>

<div class="container">
    <h1>Welcome to the Online Donation System</h1>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Welcome back, <?= htmlspecialchars($_SESSION['full_name']) ?>!</p>
        <div class="button-group">
            <a class="btn" href="donate.php">Donate Now</a>
            <a class="btn" href="view_campaigns.php">View Campaigns</a>
               <a href="create_campaign.php" class="btn">Create New Campaign</a>
            <a class="btn" href="logout.php">Logout</a>
        </div>
    <?php else: ?>
        <p>Join us in making a difference</p>
        <div class="button-group">
            <a class="btn" href="login.php">Login</a>
            <a class="btn" href="register.php">Register</a>
        </div>
        <div class="ticker-container">
    <div class="ticker">
        <?php
        include 'db.php';
        $updates = $conn->query("SELECT update_text FROM campaign_updates ORDER BY update_date DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
        foreach($updates as $update) {
            echo "<span>" . htmlspecialchars($update['update_text']) . " &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;</span>";
        }
        ?>
    </div>
</div>

    <?php endif; ?>
</div>

<?php
// Include footer
include $footer_file;
?>