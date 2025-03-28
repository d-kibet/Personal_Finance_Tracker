<?php
include 'header.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'config.php';

$user_id = $_SESSION['user_id'];
if (!isset($_GET['id'])) {
    header('Location: view_records.php');
    exit;
}
$expense_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM expenses WHERE id = ? AND user_id = ?");
$stmt->execute([$expense_id, $user_id]);
$record = $stmt->fetch();
if (!$record) {
    echo "<p class='text-center'>Record not found.</p>";
    include 'footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("DELETE FROM expenses WHERE id = ? AND user_id = ?");
    if ($stmt->execute([$expense_id, $user_id])) {
        $success = "Record deleted successfully.";
    } else {
        $error = "Failed to delete record.";
    }
}
?>

<div class="row justify-content-center fade-in">
  <div class="col-md-6">
    <div class="card p-4">
      <h2 class="text-center mb-4">Delete Expense Record</h2>
      <?php if (isset($success)): ?>
         <div class="alert alert-success text-center"><?php echo $success; ?></div>
         <a href="view_records.php" class="btn btn-primary">Back to Records</a>
      <?php else: ?>
         <?php if (isset($error)): ?>
             <div class="alert alert-danger text-center"><?php echo $error; ?></div>
         <?php else: ?>
             <p class="text-center">Are you sure you want to delete this record? This action cannot be undone.</p>
             <form method="post" action="delete_expense.php?id=<?php echo $expense_id; ?>">
                <div class="text-center">
                  <button type="submit" class="btn btn-danger">Yes, Delete Record</button>
                  <a href="view_records.php" class="btn btn-secondary">Cancel</a>
                </div>
             </form>
         <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
