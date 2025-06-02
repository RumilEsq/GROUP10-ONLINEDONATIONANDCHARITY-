<?php
include 'db.php';
include 'header.php';

try {
    $stmt = $conn->query("SELECT * FROM campaigns");
    $campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<div class="container">
    <h1>Active Campaigns</h1>
    
    <div class="campaign-grid">
        <?php foreach ($campaigns as $campaign): 
            $progress = ($campaign['current_amount'] / $campaign['goal_amount']) * 100;
            $progress = min(100, $progress);
        ?>
        <div class="campaign-card">
            <h2><?= htmlspecialchars($campaign['title']) ?></h2>
            <p><?= htmlspecialchars(substr($campaign['description'], 0, 100)) ?>...</p>
            
            <div class="progress-bar">
                <div class="progress" style="width: <?= $progress ?>%"></div>
            </div>
            <p>
                $<?= number_format($campaign['current_amount'], 2) ?> raised of 
                $<?= number_format($campaign['goal_amount'], 2) ?>
            </p>
            
            <a class="btn" href="view_campaign.php?id=<?= $campaign['campaign_id'] ?>">
                View Details
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>