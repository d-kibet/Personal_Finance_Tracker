<?php
// expense.php
include 'header.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = trim($_POST['category']);
    $amount = $_POST['amount'];
    $entry_date = $_POST['entry_date'];
    $description = trim($_POST['description']);
    $stmt = $pdo->prepare("INSERT INTO expenses (user_id, category, amount, entry_date, description) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $category, $amount, $entry_date, $description]);
    $success = "Expense entry added successfully.";
}
?>
<div class="row justify-content-center fade-in">
  <div class="col-md-6">
    <div class="card p-4">
      <h2 class="text-center mb-4">Add Expense</h2>
      <?php if(isset($success)): ?>
        <div class="alert alert-success text-center"><?php echo $success; ?></div>
      <?php endif; ?>
      <form method="post" action="expense.php">
        <div class="mb-3">
          <label for="category" class="form-label">Category</label>
          <input type="text" class="form-control" name="category" required>
        </div>
        <div class="mb-3">
          <label for="amount" class="form-label">Amount</label>
          <input type="number" step="0.01" class="form-control" name="amount" required>
        </div>
        <div class="mb-3">
          <label for="entry_date" class="form-label">Date</label>
          <input type="date" class="form-control" name="entry_date" required>
        </div>
        <div class="mb-3">
          <label for="description" class="form-label">Description (optional)</label>
          <input type="text" class="form-control" name="description">
        </div>
        <button type="submit" class="btn btn-primary w-100">Add Expense</button>
      </form>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
