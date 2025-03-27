<?php
// reset.php
include 'header.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_reset'])) {
    $user_id = $_SESSION['user_id'];
    // Delete user's income data
    $stmt = $pdo->prepare("DELETE FROM income WHERE user_id = ?");
    $stmt->execute([$user_id]);
    // Delete user's expense data
    $stmt = $pdo->prepare("DELETE FROM expenses WHERE user_id = ?");
    $stmt->execute([$user_id]);
    // Delete user's budget data
    $stmt = $pdo->prepare("DELETE FROM budgets WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $success = "Your financial data has been successfully cleared.";
}
?>

<div class="row justify-content-center fade-in">
  <div class="col-md-6">
    <div class="card p-4">
      <h2 class="text-center mb-4">Reset Financial Data</h2>
      <?php if(isset($success)): ?>
         <div class="alert alert-success text-center"><?php echo $success; ?></div>
         <div class="text-center"><a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a></div>
      <?php else: ?>
         <p class="text-center">Warning: This action will permanently delete all your financial data (income, expenses, and budgets). This cannot be undone. Are you sure you want to proceed?</p>
         <form method="post" action="reset.php">
            <div class="text-center">
               <button type="submit" name="confirm_reset" class="btn btn-danger">Yes, Clear My Data</button>
               <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
            </div>
         </form>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
