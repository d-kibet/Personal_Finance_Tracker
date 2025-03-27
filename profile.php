<?php
// profile.php
include 'header.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'config.php';

$user_id = $_SESSION['user_id'];
// Fetch current user data
$stmt = $pdo->prepare("SELECT first_name, last_name, email, username FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$profile_success = "";
$profile_error = "";
$password_success = "";
$password_error = "";

// Process profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?");
    if ($stmt->execute([$first_name, $last_name, $email, $user_id])) {
       $profile_success = "Profile updated successfully.";
       $stmt = $pdo->prepare("SELECT first_name, last_name, email, username FROM users WHERE id = ?");
       $stmt->execute([$user_id]);
       $user = $stmt->fetch();
    } else {
       $profile_error = "Failed to update profile.";
    }
}

// Process password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_password = $stmt->fetchColumn();
    if (!password_verify($current_password, $user_password)) {
        $password_error = "Current password is incorrect.";
    } elseif ($new_password !== $confirm_password) {
        $password_error = "New password and confirm password do not match.";
    } else {
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        if ($stmt->execute([$hashedPassword, $user_id])) {
            $password_success = "Password changed successfully.";
        } else {
            $password_error = "Failed to change password.";
        }
    }
}
?>

<div class="row fade-in">
  <div class="col-md-6">
    <div class="card p-4 mb-4">
      <h2 class="text-center mb-4">Update Profile</h2>
      <?php if ($profile_success): ?>
         <div class="alert alert-success text-center"><?php echo $profile_success; ?></div>
      <?php elseif ($profile_error): ?>
         <div class="alert alert-danger text-center"><?php echo $profile_error; ?></div>
      <?php endif; ?>
      <form method="post" action="profile.php">
         <div class="mb-3">
            <label class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
         </div>
         <div class="mb-3">
            <label class="form-label">Last Name</label>
            <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
         </div>
         <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
         </div>
         <button type="submit" name="update_profile" class="btn btn-primary w-100">Update Profile</button>
      </form>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card p-4 mb-4">
      <h2 class="text-center mb-4">Change Password</h2>
      <?php if ($password_success): ?>
         <div class="alert alert-success text-center"><?php echo $password_success; ?></div>
      <?php elseif ($password_error): ?>
         <div class="alert alert-danger text-center"><?php echo $password_error; ?></div>
      <?php endif; ?>
      <form method="post" action="profile.php">
         <div class="mb-3">
            <label class="form-label">Current Password</label>
            <input type="password" name="current_password" class="form-control" required>
         </div>
         <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" name="new_password" class="form-control" required>
         </div>
         <div class="mb-3">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
         </div>
         <button type="submit" name="change_password" class="btn btn-primary w-100">Change Password</button>
      </form>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
