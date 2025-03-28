<?php
// view_records.php
include 'header.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'config.php';

$user_id = $_SESSION['user_id'];

// Fetch Income records
$stmt = $pdo->prepare("SELECT id, amount, entry_date, description FROM income WHERE user_id = ? ORDER BY entry_date DESC");
$stmt->execute([$user_id]);
$incomeRecords = $stmt->fetchAll();

// Fetch Expense records
$stmt = $pdo->prepare("SELECT id, category, amount, entry_date, description FROM expenses WHERE user_id = ? ORDER BY entry_date DESC");
$stmt->execute([$user_id]);
$expenseRecords = $stmt->fetchAll();

// Fetch Budget records
$stmt = $pdo->prepare("SELECT id, category, allocated_amount, running_balance, start_date, end_date FROM budgets WHERE user_id = ? ORDER BY start_date DESC");
$stmt->execute([$user_id]);
$budgetRecords = $stmt->fetchAll();
?>

<div class="row fade-in">
  <div class="col-12">
    <h2 class="text-center mb-4">Your Financial Records</h2>
    <!-- Bootstrap Tabs Navigation -->
    <ul class="nav nav-tabs" id="recordsTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="income-tab" data-bs-toggle="tab" data-bs-target="#income" type="button" role="tab" aria-controls="income" aria-selected="true">Income</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="expenses-tab" data-bs-toggle="tab" data-bs-target="#expenses" type="button" role="tab" aria-controls="expenses" aria-selected="false">Expenses</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="budget-tab" data-bs-toggle="tab" data-bs-target="#budget" type="button" role="tab" aria-controls="budget" aria-selected="false">Budget</button>
      </li>
    </ul>
    <div class="tab-content" id="recordsTabContent">
      <!-- Income Tab -->
      <div class="tab-pane fade show active" id="income" role="tabpanel" aria-labelledby="income-tab">
        <div class="table-responsive mt-3">
          <table class="table table-striped table-dark">
            <thead>
              <tr>
                <th>ID</th>
                <th>Amount</th>
                <th>Entry Date</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($incomeRecords) > 0): ?>
                <?php foreach ($incomeRecords as $record): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($record['id']); ?></td>
                    <td>$<?php echo number_format($record['amount'], 2); ?></td>
                    <td><?php echo htmlspecialchars($record['entry_date']); ?></td>
                    <td><?php echo htmlspecialchars($record['description']); ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="4" class="text-center">No income records found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- Expenses Tab -->
      <div class="tab-pane fade" id="expenses" role="tabpanel" aria-labelledby="expenses-tab">
        <div class="table-responsive mt-3">
          <table class="table table-striped table-dark">
            <thead>
              <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Entry Date</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($expenseRecords) > 0): ?>
                <?php foreach ($expenseRecords as $record): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($record['id']); ?></td>
                    <td><?php echo htmlspecialchars($record['category']); ?></td>
                    <td>$<?php echo number_format($record['amount'], 2); ?></td>
                    <td><?php echo htmlspecialchars($record['entry_date']); ?></td>
                    <td><?php echo htmlspecialchars($record['description']); ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="5" class="text-center">No expense records found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- Budget Tab -->
      <div class="tab-pane fade" id="budget" role="tabpanel" aria-labelledby="budget-tab">
        <div class="table-responsive mt-3">
          <table class="table table-striped table-dark">
            <thead>
              <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Allocated Amount</th>
                <th>Running Balance</th>
                <th>Start Date</th>
                <th>End Date</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($budgetRecords) > 0): ?>
                <?php foreach ($budgetRecords as $record): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($record['id']); ?></td>
                    <td><?php echo htmlspecialchars($record['category']); ?></td>
                    <td>$<?php echo number_format($record['allocated_amount'], 2); ?></td>
                    <td>$<?php echo number_format($record['running_balance'], 2); ?></td>
                    <td><?php echo htmlspecialchars($record['start_date']); ?></td>
                    <td><?php echo htmlspecialchars($record['end_date']); ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="6" class="text-center">No budget records found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
