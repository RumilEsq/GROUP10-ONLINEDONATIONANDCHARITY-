<?php
include 'db.php';
include 'header.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 3)) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $goal_amount = $_POST['goal_amount'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    
    try {
        $stmt = $conn->prepare("INSERT INTO campaigns 
                              (user_id, title, description, goal_amount, start_date, end_date) 
                              VALUES (:user_id, :title, :description, :goal_amount, :start_date, :end_date)");
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':goal_amount', $goal_amount);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->execute();
        
        $campaign_id = $conn->lastInsertId();
        $success = "Campaign created successfully!";
    } catch(PDOException $e) {
        $error = "Error creating campaign: " . $e->getMessage();
    }
}
?>

<div class="container">
    <h1>Create New Campaign</h1>
    
    <?php if(isset($success)): ?>
        <div class="success"><?= $success ?></div>
        <a class="btn" href="view_campaign.php?id=<?= $campaign_id ?>">View Campaign</a>
        <a class="btn" href="dashboard.php">Dashboard</a>
    <?php else: ?>
        <?php if(isset($error)): ?>
            <div class="alert"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" required>
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="5" required></textarea>
            </div>
            
            <div class="form-group">
                <label>Goal Amount ($)</label>
                <input type="number" name="goal_amount" min="1" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label>Start Date</label>
                <input type="date" name="start_date" required>
            </div>
            
            <div class="form-group">
                <label>End Date</label>
                <input type="date" name="end_date" required>
            </div>
            
            <button type="submit" class="btn">Create Campaign</button>
            <a class="btn" href="dashboard.php">Cancel</a>
        </form>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>