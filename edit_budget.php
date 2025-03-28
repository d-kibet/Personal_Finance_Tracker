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
$budget_id = $_GET['id'];

// Fetch the budget record
$stmt = $pdo->prepare("SELECT * FROM budgets WHERE id = ? AND user_id = ?");
$stmt->execute([$budget_id, $user_id]);
$record = $stmt->fetch();
if (!$record) {
    echo "<p class='text-center'>Record not found.</p>";
    include 'footer.php';
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = trim($_POST['category']);
    $allocated_amount = $_POST['allocated_amount'];
    // Note: In our improved version, we auto-calculate running_balance, so we do not take it as an input.
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    
    // Update the budget record (running_balance will be updated automatically by a separate script)
    $stmt = $pdo->prepare("UPDATE budgets SET category = ?, allocated_amount = ?, start_date = ?, end_date = ? WHERE id = ? AND user_id = ?");
    if ($stmt->execute([$category, $allocated_amount, $start_date, $end_date, $budget_id, $user_id])) {
        $success = "Budget record updated successfully.";
    } else {
        $error = "Failed to update record.";
    }
}
?>

<div class="row justify-content-center fade-in">
  <div class="col-md-6">
    <div class="card p-4">
      <h2 class="text-center mb-4">Edit Budget Record</h2>
      <?php if (isset($success)): ?>
         <div class="alert alert-success text-center"><?php echo $success; ?></div>
         <a href="view_records.php" class="btn btn-primary">Back to Records</a>
      <?php else: ?>
         <?php if (isset($error)): ?>
             <div class="alert alert-danger text-center"><?php echo $error; ?></div>
         <?php endif; ?>
         <form method="post" action="edit_budget.php?id=<?php echo $budget_id; ?>">
            <div class="mb-3">
               <label class="form-label">Category</label>
               <input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($record['category']); ?>" required>
            </div>
            <div class="mb-3">
               <label class="form-label">Allocated Amount</label>
               <input type="number" step="0.01" name="allocated_amount" class="form-control" value="<?php echo htmlspecialchars($record['allocated_amount']); ?>" required>
            </div>
            <div class="mb-3">
               <label class="form-label">Start Date</label>
               <input type="date" name="start_date" class="form-control" value="<?php echo htmlspecialchars($record['start_date']); ?>" required>
            </div>
            <div class="mb-3">
               <label class="form-label">End Date (optional)</label>
               <input type="date" name="end_date" class="form-control" value="<?php echo htmlspecialchars($record['end_date']); ?>">
            </div>
            <button type="submit" class="btn btn-primary w-100">Update Record</button>
         </form>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
