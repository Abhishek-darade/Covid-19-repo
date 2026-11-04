<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$pageTitle   = $pageTitle   ?? 'COVID-19 Info Portal';
$currentPage = basename($_SERVER['PHP_SELF']);
function isActive($page) { global $currentPage; return $currentPage === $page ? 'active' : ''; }
$flash = null;
if (isset($_SESSION['flash_message'])) { $flash = $_SESSION['flash_message']; unset($_SESSION['flash_message']); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="COVID-19 Information Portal - Statistics, Prevention, Symptoms, Vaccine Registration">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top" id="mainNav">
  <div class="container">
    <a class="navbar-brand" href="<?= BASE_URL ?>index.php">&#129440; COVID-19 Portal</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link <?= isActive('index.php') ?>" href="<?= BASE_URL ?>index.php"><i class="fas fa-home me-1"></i>Home</a></li>
        <li class="nav-item"><a class="nav-link <?= isActive('statistics.php') ?>" href="<?= BASE_URL ?>statistics.php"><i class="fas fa-chart-bar me-1"></i>Statistics</a></li>
        <li class="nav-item"><a class="nav-link <?= isActive('prevention.php') ?>" href="<?= BASE_URL ?>prevention.php"><i class="fas fa-shield-virus me-1"></i>Prevention</a></li>
        <li class="nav-item"><a class="nav-link <?= isActive('symptoms.php') ?>" href="<?= BASE_URL ?>symptoms.php"><i class="fas fa-heartbeat me-1"></i>Symptoms</a></li>
        <li class="nav-item"><a class="nav-link <?= isActive('vaccine_registration.php') ?>" href="<?= BASE_URL ?>vaccine_registration.php"><i class="fas fa-syringe me-1"></i>Vaccine</a></li>
        <li class="nav-item"><a class="nav-link <?= isActive('comments.php') ?>" href="<?= BASE_URL ?>comments.php"><i class="fas fa-comments me-1"></i>Discussion</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <?php if (isLoggedIn()): ?>
          <span class="text-white-50 small"><i class="fas fa-user-circle me-1"></i><?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']) ?></span>
          <a href="<?= BASE_URL ?>logout.php" class="btn btn-sm btn-outline-light"><i class="fas fa-sign-out-alt me-1"></i>Logout</a>
        <?php else: ?>
          <a href="<?= BASE_URL ?>login.php" class="btn btn-sm btn-outline-light"><i class="fas fa-sign-in-alt me-1"></i>Login</a>
          <a href="<?= BASE_URL ?>register.php" class="btn btn-sm btn-danger"><i class="fas fa-user-plus me-1"></i>Register</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<?php if ($flash): ?>
<div class="flash-message">
  <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show shadow" role="alert">
    <i class="fas fa-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
    <?= htmlspecialchars($flash['msg']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
</div>
<?php endif; ?>
