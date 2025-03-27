<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// If the export parameter is set, output CSV data and exit immediately.
if (isset($_GET['export'])) {
    require 'config.php';
    $user_id = $_SESSION['user_id'];
    
    // Set headers for CSV download.
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=data_export.csv');
    
    $output = fopen('php://output', 'w');

    // Export Income Data
    fputcsv($output, ['Income Data']);
    fputcsv($output, ['ID', 'Amount', 'Entry Date', 'Description']);
    $stmt = $pdo->prepare("SELECT id, amount, entry_date, description FROM income WHERE user_id = ?");
    $stmt->execute([$user_id]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
       fputcsv($output, $row);
    }
    fputcsv($output, []); // empty line

    // Export Expenses Data
    fputcsv($output, ['Expenses Data']);
    fputcsv($output, ['ID', 'Category', 'Amount', 'Entry Date', 'Description']);
    $stmt = $pdo->prepare("SELECT id, category, amount, entry_date, description FROM expenses WHERE user_id = ?");
    $stmt->execute([$user_id]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
       fputcsv($output, $row);
    }
    fputcsv($output, []); // empty line

    // Export Budgets Data
    fputcsv($output, ['Budgets Data']);
    fputcsv($output, ['ID', 'Category', 'Allocated Amount', 'Running Balance', 'Start Date', 'End Date']);
    $stmt = $pdo->prepare("SELECT id, category, allocated_amount, running_balance, start_date, end_date FROM budgets WHERE user_id = ?");
    $stmt->execute([$user_id]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
       fputcsv($output, $row);
    }
    fclose($output);
    exit;
}
?>
<?php include 'header.php'; ?>
<div class="row justify-content-center fade-in">
  <div class="col-md-6">
    <div class="card p-4">
      <h2 class="text-center mb-4">Export Your Data</h2>
      <p class="text-center">Click the button below to download your financial data (Income, Expenses, and Budgets) as a CSV file.</p>
      <div class="text-center">
         <a href="export.php?export=1" class="btn btn-success btn-lg">Export Data</a>
      </div>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
