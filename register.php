<?php
// register.php
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'config.php';
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if($password !== $confirm_password){
        $error = "Passwords do not match.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, username, email, password) VALUES (?, ?, ?, ?, ?)");
        try {
            $stmt->execute([$first_name, $last_name, $username, $email, $hashedPassword]);
            header('Location: login.php');
            exit;
        } catch(PDOException $e){
            $error = "Registration failed: " . $e->getMessage();
        }
    }
}
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h2>Register</h2>
    <?php if(isset($error)): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="post" action="register.php">
      <div class="mb-3">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" class="form-control" name="first_name" required>
      </div>
      <div class="mb-3">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" class="form-control" name="last_name" required>
      </div>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" name="username" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" required>
      </div>
      <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" name="confirm_password" required>
      </div>
      <button type="submit" class="btn btn-success">Register</button>
    </form>
  </div>
</div>
<?php include 'footer.php'; ?>
