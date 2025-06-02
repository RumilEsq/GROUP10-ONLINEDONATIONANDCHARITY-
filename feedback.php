<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get campaigns the user has donated to
$campaign_stmt = $conn->prepare("
    SELECT DISTINCT c.campaign_id, c.title 
    FROM campaigns c
    JOIN donations d ON c.campaign_id = d.campaign_id
    WHERE d.user_id = :user_id
");
$campaign_stmt->bindParam(':user_id', $user_id);
$campaign_stmt->execute();
$campaigns = $campaign_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $campaign_id = $_POST['campaign_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    
    try {
        $stmt = $conn->prepare("INSERT INTO feedback (user_id, campaign_id, rating, comment) 
                               VALUES (:user_id, :campaign_id, :rating, :comment)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':campaign_id', $campaign_id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);
        $stmt->execute();
        
        $success = "Thank you for your feedback!";
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<?php include 'header.php'; ?>

<div class="container">
    <h2>Provide Feedback</h2>
    <?php if(isset($success)): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>
    <?php if(isset($error)): ?>
        <div class="alert"><?= $error ?></div>
    <?php endif; ?>
    
    <?php if(count($campaigns) > 0): ?>
        <form method="POST">
            <div class="form-group">
                <label for="campaign_id">Select Campaign:</label>
                <select name="campaign_id" required>
                    <?php foreach($campaigns as $campaign): ?>
                        <option value="<?= $campaign['campaign_id'] ?>">
                            <?= $campaign['title'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Rating:</label>
                <div class="rating">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" required>
                        <label for="star<?= $i ?>">★</label>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="comment">Comments:</label>
                <textarea name="comment" rows="4" required></textarea>
            </div>
            
            <button type="submit" class="btn">Submit Feedback</button>
        </form>
    <?php else: ?>
        <p>You haven't donated to any campaigns yet. <a href="campaigns.php">Browse campaigns</a> to make a donation and provide feedback.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>