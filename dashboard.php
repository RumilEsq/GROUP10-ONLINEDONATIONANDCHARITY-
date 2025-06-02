<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user donations
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT d.*, c.title, p.method_name 
    FROM donations d
    JOIN campaigns c ON d.campaign_id = c.campaign_id
    JOIN payment_methods p ON d.payment_method_id = p.payment_method_id
    WHERE d.user_id = :user_id
");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$donations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get user feedback
$feedback_stmt = $conn->prepare("
    SELECT f.*, c.title 
    FROM feedback f
    JOIN campaigns c ON f.campaign_id = c.campaign_id
    WHERE f.user_id = :user_id
");
$feedback_stmt->bindParam(':user_id', $user_id);
$feedback_stmt->execute();
$feedbacks = $feedback_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'header.php'; ?>

<div class="container">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['full_name']) ?>!</h2>
    
    <div class="dashboard-actions">
        <a href="donate.php" class="btn">Make a Donation</a>
        <a href="view_campaigns.php" class="btn">View Campaigns</a>
        <a href="create_campaign.php" class="btn">Create New Campaign</a>
        <?php if($_SESSION['role_id'] == 3):  ?>
            

        <?php endif; ?>
    </div>
    
    <div class="dashboard-section">
        <h3>Your Donations</h3>
        <?php if(count($donations) > 0): ?>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Campaign</th>
                    <th>Payment Method</th>
                </tr>
                <?php foreach($donations as $donation): ?>
                <tr>
                    <td><?= date('M d, Y', strtotime($donation['donation_date'])) ?></td>
                    <td>$<?= number_format($donation['amount'], 2) ?></td>
                    <td><?= $donation['title'] ?></td>
                    <td><?= $donation['method_name'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>You haven't made any donations yet.</p>
        <?php endif; ?>
    </div>
    
    <div class="dashboard-section">
        <a href="feedback.php" class="btn">Give us a Feedback</a>
        <?php if(count($feedbacks) > 0): ?>
            <table>
                <tr>
                    <th>Campaign</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Date</th>
                </tr>
                <?php foreach($feedbacks as $feedback): ?>
                <tr>
                    <td><?= $feedback['title'] ?></td>
                    <td>
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <?= $i <= $feedback['rating'] ? '★' : '☆' ?>
                        <?php endfor; ?>
                    </td>
                    <td><?= htmlspecialchars($feedback['comment']) ?></td>
                    <td><?= date('M d, Y', strtotime($feedback['feedback_date'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>You haven't submitted any feedback yet.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>