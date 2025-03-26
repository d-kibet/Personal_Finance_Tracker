<?php
// budget.php
include 'header.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'config.php';

// Process budget form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = trim($_POST['category']);
    $allocated_amount = $_POST['allocated_amount'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $stmt = $pdo->prepare("INSERT INTO budgets (user_id, category, allocated_amount, start_date, end_date, running_balance) VALUES (?, ?, ?, ?, ?, 0)");
    $stmt->execute([$_SESSION['user_id'], $category, $allocated_amount, $start_date, $end_date]);
    $success = "Budget set successfully.";
}
?>
<div class="row justify-content-center fade-in">
  <div class="col-md-6">
    <div class="card p-4">
      <h2 class="text-center mb-4">Set Budget</h2>
      <?php if(isset($success)): ?>
        <div class="alert alert-success text-center"><?php echo $success; ?></div>
      <?php endif; ?>
      <form method="post" action="budget.php">
        <div class="mb-3">
          <label for="category" class="form-label">Budget Category</label>
          <input type="text" class="form-control" name="category" required>
        </div>
        <div class="mb-3">
          <label for="allocated_amount" class="form-label">Allocated Amount</label>
          <input type="number" step="0.01" class="form-control" name="allocated_amount" required>
        </div>
        <div class="mb-3">
          <label for="start_date" class="form-label">Start Date</label>
          <input type="date" class="form-control" name="start_date" required>
        </div>
        <div class="mb-3">
          <label for="end_date" class="form-label">End Date (optional)</label>
          <input type="date" class="form-control" name="end_date">
        </div>
        <button type="submit" class="btn btn-primary w-100">Set Budget</button>
      </form>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
