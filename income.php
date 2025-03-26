<?php
// income.php
include 'header.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $entry_date = $_POST['entry_date'];
    $description = trim($_POST['description']);
    $stmt = $pdo->prepare("INSERT INTO income (user_id, amount, entry_date, description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $amount, $entry_date, $description]);
    $success = "Income entry added successfully.";
}
?>
<div class="row justify-content-center fade-in">
  <div class="col-md-6">
    <div class="card p-4">
      <h2 class="text-center mb-4">Add Income</h2>
      <?php if(isset($success)): ?>
        <div class="alert alert-success text-center"><?php echo $success; ?></div>
      <?php endif; ?>
      <form method="post" action="income.php">
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
        <button type="submit" class="btn btn-primary w-100">Add Income</button>
      </form>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
