<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role_id = 2; // Default role: Donor
    
    try {
        $stmt = $conn->prepare("INSERT INTO users (role_id, full_name, email, password_hash) 
                               VALUES (:role_id, :full_name, :email, :password)");
        $stmt->bindParam(':role_id', $role_id);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        
        $success = "Registration successful! Please login.";
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<?php include 'header.php'; ?>

<div class="container">
    <h2>Register</h2>
    <?php if(isset($success)): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>
    <?php if(isset($error)): ?>
        <div class="alert"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

<?php include 'footer.php'; ?>