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

// Fetch the expense record
$stmt = $pdo->prepare("SELECT * FROM expenses WHERE id = ? AND user_id = ?");
$stmt->execute([$expense_id, $user_id]);
$record = $stmt->fetch();
if (!$record) {
    echo "<p class='text-center'>Record not found.</p>";
    include 'footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = trim($_POST['category']);
    $amount = $_POST['amount'];
    $entry_date = $_POST['entry_date'];
    $description = trim($_POST['description']);
    
    $stmt = $pdo->prepare("UPDATE expenses SET category = ?, amount = ?, entry_date = ?, description = ? WHERE id = ? AND user_id = ?");
    if ($stmt->execute([$category, $amount, $entry_date, $description, $expense_id, $user_id])) {
        $success = "Expense record updated successfully.";
    } else {
        $error = "Failed to update record.";
    }
}
?>

<div class="row justify-content-center fade-in">
  <div class="col-md-6">
    <div class="card p-4">
      <h2 class="text-center mb-4">Edit Expense Record</h2>
      <?php if (isset($success)): ?>
         <div class="alert alert-success text-center"><?php echo $success; ?></div>
         <a href="view_records.php" class="btn btn-primary">Back to Records</a>
      <?php else: ?>
         <?php if (isset($error)): ?>
             <div class="alert alert-danger text-center"><?php echo $error; ?></div>
         <?php endif; ?>
         <form method="post" action="edit_expense.php?id=<?php echo $expense_id; ?>">
            <div class="mb-3">
               <label class="form-label">Category</label>
               <input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($record['category']); ?>" required>
            </div>
            <div class="mb-3">
               <label class="form-label">Amount</label>
               <input type="number" step="0.01" name="amount" class="form-control" value="<?php echo htmlspecialchars($record['amount']); ?>" required>
            </div>
            <div class="mb-3">
               <label class="form-label">Entry Date</label>
               <input type="date" name="entry_date" class="form-control" value="<?php echo htmlspecialchars($record['entry_date']); ?>" required>
            </div>
            <div class="mb-3">
               <label class="form-label">Description (optional)</label>
               <input type="text" name="description" class="form-control" value="<?php echo htmlspecialchars($record['description']); ?>">
            </div>
            <button type="submit" class="btn btn-primary w-100">Update Record</button>
         </form>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
