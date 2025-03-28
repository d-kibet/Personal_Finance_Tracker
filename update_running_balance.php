<?php
include 'header.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'config.php';

$user_id = $_SESSION['user_id'];

// Get all budget records for the user
$stmt = $pdo->prepare("SELECT id, category, allocated_amount, start_date, end_date FROM budgets WHERE user_id = ?");
$stmt->execute([$user_id]);
$budgets = $stmt->fetchAll();

foreach ($budgets as $budget) {
    $budget_id = $budget['id'];
    $category = $budget['category'];
    $start_date = $budget['start_date'];
    $end_date = $budget['end_date'];
    
    // Sum all expenses for that category within the budget period
    if ($end_date) {
        $stmt_exp = $pdo->prepare("SELECT SUM(amount) FROM expenses WHERE user_id = ? AND category = ? AND entry_date BETWEEN ? AND ?");
        $stmt_exp->execute([$user_id, $category, $start_date, $end_date]);
    } else {
        // If no end date is set, consider expenses from the start_date onward
        $stmt_exp = $pdo->prepare("SELECT SUM(amount) FROM expenses WHERE user_id = ? AND category = ? AND entry_date >= ?");
        $stmt_exp->execute([$user_id, $category, $start_date]);
    }
    $sum_expenses = $stmt_exp->fetchColumn();
    if (!$sum_expenses) {
        $sum_expenses = 0;
    }
    // Calculate running balance
    $running_balance = $budget['allocated_amount'] - $sum_expenses;
    
    // Update the budget record with the new running balance
    $stmt_update = $pdo->prepare("UPDATE budgets SET running_balance = ? WHERE id = ? AND user_id = ?");
    $stmt_update->execute([$running_balance, $budget_id, $user_id]);
}

echo "<div class='alert alert-success text-center'>Running balances updated successfully.</div>";
echo "<div class='text-center'><a href='view_records.php' class='btn btn-primary'>Back to Records</a></div>";
include 'footer.php';
?>
