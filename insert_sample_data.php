<?php
include 'db.php';

try {
    // Insert roles
    $conn->exec("INSERT INTO roles (role_name) VALUES ('Admin'), ('Donor'), ('Beneficiary')");
    
    // Insert payment methods
    $conn->exec("INSERT INTO payment_methods (method_name) VALUES ('Credit Card'), ('PayPal'), ('Bank Transfer')");
    
    // Insert admin user (password: admin123)
    $admin_pass = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (role_id, full_name, email, password_hash) 
                           VALUES (1, 'Admin User', 'admin@example.com', :pass)");
    $stmt->bindParam(':pass', $admin_pass);
    $stmt->execute();
    
    // Insert donor user (password: donor123)
    $donor_pass = password_hash('donor123', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (role_id, full_name, email, password_hash) 
                           VALUES (2, 'Donor User', 'donor@example.com', :pass)");
    $stmt->bindParam(':pass', $donor_pass);
    $stmt->execute();
    
    // Insert beneficiary user (password: beneficiary123)
    $beneficiary_pass = password_hash('beneficiary123', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (role_id, full_name, email, password_hash) 
                           VALUES (3, 'Beneficiary User', 'beneficiary@example.com', :pass)");
    $stmt->bindParam(':pass', $beneficiary_pass);
    $stmt->execute();
    
    // Insert beneficiary profile
    $conn->exec("INSERT INTO beneficiaries (user_id, bio, needs) 
                VALUES (3, 'Single parent with three children', 'School supplies, clothing, food assistance')");
    
    // Insert campaigns
    $conn->exec("INSERT INTO campaigns (user_id, title, description, goal_amount, start_date, end_date) 
                VALUES 
                (1, 'Education Fund', 'Help children access quality education', 10000.00, '2023-01-01', '2023-12-31'),
                (1, 'Food Relief', 'Provide meals to families in need', 5000.00, '2023-02-01', '2023-11-30'),
                (1, 'Medical Assistance', 'Support medical treatments for the underprivileged', 15000.00, '2023-03-01', '2023-10-31')");
    
    // Insert donations
    $conn->exec("INSERT INTO donations (user_id, campaign_id, amount, payment_method_id) 
                VALUES 
                (2, 1, 100.00, 1),
                (2, 2, 50.00, 2),
                (2, 3, 200.00, 3)");
    
    // Insert campaign updates
    $conn->exec("INSERT INTO campaign_updates (campaign_id, update_text) 
                VALUES 
                (1, 'We have reached 25% of our goal! Thank you to all donors!'),
                (1, 'Partnered with 5 new schools to distribute supplies')");
    
    // Insert feedback
    $conn->exec("INSERT INTO feedback (user_id, campaign_id, rating, comment) 
                VALUES 
                (2, 1, 5, 'Great initiative! Happy to support education'),
                (2, 2, 4, 'Good program but needs more transparency')");
    
    echo "Sample data inserted successfully!";
} catch(PDOException $e) {
    echo "Error inserting sample data: " . $e->getMessage();
}
?>