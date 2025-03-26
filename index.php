<?php
// index.php
include 'header.php';
?>
<div class="fade-in">
  <!-- Hero Section -->
  <div class="text-center mb-5">
    <h1 class="display-4">Welcome to Your Personal Finance Tracker</h1>
    <p class="lead">Manage your income, expenses, and budgets effectively.</p>
    <div class="mt-4">
      <a href="login.php" class="btn btn-primary btn-lg me-2">Login</a>
      <a href="register.php" class="btn btn-success btn-lg">Register</a>
    </div>
  </div>

  <!-- Features Section -->
  <div class="row mb-5">
    <div class="col-md-3 mb-4">
      <div class="card p-3 h-100">
        <div class="text-center mb-3">
          <!-- Example using Bootstrap Icons or Font Awesome -->
          <!-- Using a Bootstrap icon: https://icons.getbootstrap.com/ -->
          <i class="bi bi-cash-stack" style="font-size: 3rem;"></i>
        </div>
        <h5 class="text-center">Track Income</h5>
        <p class="text-center">
          Easily record and manage your earnings, whether salary, bonus, or side hustle.
        </p>
      </div>
    </div>
    <div class="col-md-3 mb-4">
      <div class="card p-3 h-100">
        <div class="text-center mb-3">
          <i class="bi bi-cart-check" style="font-size: 3rem;"></i>
        </div>
        <h5 class="text-center">Monitor Expenses</h5>
        <p class="text-center">
          Keep tabs on your daily spend and see exactly where your money goes.
        </p>
      </div>
    </div>
    <div class="col-md-3 mb-4">
      <div class="card p-3 h-100">
        <div class="text-center mb-3">
          <i class="bi bi-card-checklist" style="font-size: 3rem;"></i>
        </div>
        <h5 class="text-center">Set Budgets</h5>
        <p class="text-center">
          Create and manage budgets for different categories to prevent overspending.
        </p>
      </div>
    </div>
    <div class="col-md-3 mb-4">
      <div class="card p-3 h-100">
        <div class="text-center mb-3">
          <i class="bi bi-bar-chart-line" style="font-size: 3rem;"></i>
        </div>
        <h5 class="text-center">View Reports</h5>
        <p class="text-center">
          Generate clear, visual reports to understand your financial health at a glance.
        </p>
      </div>
    </div>
  </div>

  <!-- About / Why Choose Us Section -->
  <div class="card p-4 mb-5">
    <h2 class="text-center mb-4">Why Choose Our Finance Tracker?</h2>
    <div class="row">
      <div class="col-md-6 mb-3">
        <p>
          Our Personal Finance Tracker is designed to simplify money management for everyone.
          Whether you’re a busy professional or a student on a budget, our user-friendly
          interface helps you easily log income, expenses, and savings goals.
        </p>
        <p>
          With real-time charts and detailed reports, you’ll never lose sight of where
          your money goes. Plus, robust security measures ensure your data stays safe.
        </p>
      </div>
      <div class="col-md-6 mb-3">
        <p>
          Tired of juggling multiple apps or dealing with messy spreadsheets?
          Our all-in-one platform consolidates everything in one place.
          Track, budget, and visualize your finances to make informed decisions
          and work toward a more secure future.
        </p>
        <p>
          Ready to get started? <strong>Login</strong> or <strong>Register</strong> now
          and take the first step toward smarter money management!
        </p>
      </div>
    </div>
  </div>

  <!-- Call to Action -->
  <div class="text-center fade-in">
    <h3>Start Taking Control of Your Finances</h3>
    <p class="mb-4">Sign up today and see the difference it makes.</p>
    <a href="register.php" class="btn btn-success btn-lg">Get Started</a>
  </div>
</div>

<?php
include 'footer.php';
?>
