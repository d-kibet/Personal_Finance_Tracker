<?php
// dashboard.php
include 'header.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'config.php';

// Fetch user info
$stmt = $pdo->prepare("SELECT first_name FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Fetch summary statistics: total income and expenses
$stmt = $pdo->prepare("SELECT SUM(amount) as total_income FROM income WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$total_income = $stmt->fetchColumn() ?: 0;

$stmt = $pdo->prepare("SELECT SUM(amount) as total_expense FROM expenses WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$total_expense = $stmt->fetchColumn() ?: 0;

$net_balance = $total_income - $total_expense;

// Fetch data for charts
$stmt = $pdo->prepare("SELECT entry_date, amount FROM income WHERE user_id = ? ORDER BY entry_date ASC");
$stmt->execute([$_SESSION['user_id']]);
$income_data = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT entry_date, amount, category FROM expenses WHERE user_id = ? ORDER BY entry_date ASC");
$stmt->execute([$_SESSION['user_id']]);
$expense_data = $stmt->fetchAll();

// Prepare data arrays for charts
$income_dates = [];
$income_amounts = [];
foreach ($income_data as $row) {
    $income_dates[] = $row['entry_date'];
    $income_amounts[] = $row['amount'];
}

$expense_dates = [];
$expense_amounts = [];
foreach ($expense_data as $row) {
    $expense_dates[] = $row['entry_date'];
    $expense_amounts[] = $row['amount'];
}

$expense_categories = [];
foreach ($expense_data as $row) {
    $cat = $row['category'];
    if (!isset($expense_categories[$cat])) {
        $expense_categories[$cat] = 0;
    }
    $expense_categories[$cat] += $row['amount'];
}
$expense_cat_labels = array_keys($expense_categories);
$expense_cat_data = array_values($expense_categories);
?>

<div class="text-center fade-in">
  <h1>Welcome, <?php echo htmlspecialchars($user['first_name']); ?>!</h1>
  <p class="lead">Here's your financial overview for a smarter future.</p>
</div>

<!-- Summary Cards -->
<div class="row mt-4 fade-in">
  <div class="col-md-4 mb-3">
    <div class="card text-center p-3">
      <h4>Total Income</h4>
      <h2>$<?php echo number_format($total_income, 2); ?></h2>
    </div>
  </div>
  <div class="col-md-4 mb-3">
    <div class="card text-center p-3">
      <h4>Total Expenses</h4>
      <h2>$<?php echo number_format($total_expense, 2); ?></h2>
    </div>
  </div>
  <div class="col-md-4 mb-3">
    <div class="card text-center p-3">
      <h4>Net Balance</h4>
      <h2>$<?php echo number_format($net_balance, 2); ?></h2>
    </div>
  </div>
</div>

<!-- Charts Section -->
<div class="row mt-5 fade-in">
  <div class="col-md-6 mb-4">
    <div class="card p-3">
      <h5 class="text-center">Income Over Time</h5>
      <canvas id="incomeChart"></canvas>
    </div>
  </div>
  <div class="col-md-6 mb-4">
    <div class="card p-3">
      <h5 class="text-center">Expenses Over Time</h5>
      <canvas id="expenseChart"></canvas>
    </div>
  </div>
</div>

<div class="row mt-4 fade-in">
  <div class="col-md-6 offset-md-3 mb-4">
    <div class="card p-3">
      <h5 class="text-center">Expense Distribution by Category</h5>
      <canvas id="expensePieChart"></canvas>
    </div>
  </div>
</div>

<script>
  // Income Line Chart
  const incomeCtx = document.getElementById('incomeChart').getContext('2d');
  const incomeChart = new Chart(incomeCtx, {
    type: 'line',
    data: {
      labels: <?php echo json_encode($income_dates); ?>,
      datasets: [{
        label: 'Income',
        data: <?php echo json_encode($income_amounts); ?>,
        borderColor: 'rgba(0, 123, 255, 1)',
        backgroundColor: 'rgba(0, 123, 255, 0.2)',
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        x: { 
          title: { display: true, text: 'Date', color: '#fff' },
          ticks: { color: '#fff' } 
        },
        y: { 
          title: { display: true, text: 'Amount', color: '#fff' },
          ticks: { color: '#fff' } 
        }
      }
    }
  });

  // Expense Line Chart
  const expenseCtx = document.getElementById('expenseChart').getContext('2d');
  const expenseChart = new Chart(expenseCtx, {
    type: 'line',
    data: {
      labels: <?php echo json_encode($expense_dates); ?>,
      datasets: [{
        label: 'Expenses',
        data: <?php echo json_encode($expense_amounts); ?>,
        borderColor: 'rgba(255, 99, 132, 1)',
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        x: { 
          title: { display: true, text: 'Date', color: '#fff' },
          ticks: { color: '#fff' } 
        },
        y: { 
          title: { display: true, text: 'Amount', color: '#fff' },
          ticks: { color: '#fff' } 
        }
      }
    }
  });

  // Expense Pie Chart
  const expensePieCtx = document.getElementById('expensePieChart').getContext('2d');
  const expensePieChart = new Chart(expensePieCtx, {
    type: 'pie',
    data: {
      labels: <?php echo json_encode($expense_cat_labels); ?>,
      datasets: [{
        data: <?php echo json_encode($expense_cat_data); ?>,
        backgroundColor: [
          'rgba(255,99,132,0.7)',
          'rgba(54,162,235,0.7)',
          'rgba(255,206,86,0.7)',
          'rgba(75,192,192,0.7)',
          'rgba(153,102,255,0.7)',
          'rgba(255,159,64,0.7)'
        ]
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom',
          labels: { color: '#fff' }
        }
      }
    }
  });
</script>

<?php include 'footer.php'; ?>
