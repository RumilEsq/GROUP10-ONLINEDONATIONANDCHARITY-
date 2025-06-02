<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get campaigns
$campaign_stmt = $conn->query("SELECT * FROM campaigns WHERE is_active = 1");
$campaigns = $campaign_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get payment methods
$payment_stmt = $conn->query("SELECT * FROM payment_methods");
$payment_methods = $payment_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $campaign_id = $_POST['campaign_id'];
    $amount = $_POST['amount'];
    $payment_method_id = $_POST['payment_method_id'];
    
    try {
        // Insert donation
        $stmt = $conn->prepare("INSERT INTO donations (user_id, campaign_id, amount, payment_method_id) 
                               VALUES (:user_id, :campaign_id, :amount, :payment_method_id)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':campaign_id', $campaign_id);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':payment_method_id', $payment_method_id);
        $stmt->execute();
        
        // Update campaign total
        $updateStmt = $conn->prepare("UPDATE campaigns SET current_amount = current_amount + :amount 
                                     WHERE campaign_id = :campaign_id");
        $updateStmt->bindParam(':amount', $amount);
        $updateStmt->bindParam(':campaign_id', $campaign_id);
        $updateStmt->execute();
        
        $success = "Thank you for your donation!";
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<?php include 'header.php'; ?>

<div class="container">
    <h2>Make a Donation</h2>
    <?php if(isset($success)): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>
    <?php if(isset($error)): ?>
        <div class="alert"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label for="campaign_id">Select Campaign:</label>
            <select name="campaign_id" required>
                <?php foreach($campaigns as $campaign): ?>
                    <option value="<?= $campaign['campaign_id'] ?>">
                        <?= $campaign['title'] ?> - Goal: $<?= number_format($campaign['goal_amount'], 2) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="amount">Amount ($):</label>
            <input type="number" name="amount" step="0.01" min="1" required>
        </div>
        
        <div class="form-group">
            <label for="payment_method_id">Payment Method:</label>
            <select name="payment_method_id" required>
                <?php foreach($payment_methods as $method): ?>
                    <option value="<?= $method['payment_method_id'] ?>">
                        <?= $method['method_name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <button type="submit" class="btn">Donate Now</button>
    </form>
</div>

<?php include 'footer.php'; ?>