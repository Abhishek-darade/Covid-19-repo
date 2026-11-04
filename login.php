<?php
require_once 'includes/config.php';
if (isLoggedIn()) redirect('index.php');
$pageTitle = 'Login - COVID-19 Portal';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $usernameRegex = '/^[a-zA-Z0-9_.@]{3,100}$/';
    if (!preg_match($usernameRegex, $username)) {
        $error = 'Invalid username format.';
    } elseif (empty($password)) {
        $error = 'Password is required.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username=? OR email=? LIMIT 1");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['username']  = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role']      = $user['role'];
            $_SESSION['flash_message'] = ['type'=>'success','msg'=>'Welcome back, '.$user['full_name'].'!'];
            redirect('index.php');
        } else { $error = 'Invalid username or password.'; }
    }
}
include 'includes/header.php';
?>
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header text-center bg-primary text-white rounded-top-4 py-3">
          <h3 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i>Login</h3>
          <p class="mb-0 small">COVID-19 Info Portal</p>
        </div>
        <div class="card-body p-4">
          <?php if ($error): ?><div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i><?= $error ?></div><?php endif; ?>
          <form method="POST" id="loginForm" novalidate>
            <div class="mb-3">
              <label class="form-label fw-semibold">Username or Email</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username or email" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
              </div>
              <div class="invalid-feedback" id="usernameError"></div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Password</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePass"><i class="fas fa-eye"></i></button>
              </div>
              <div class="invalid-feedback" id="passwordError"></div>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2"><i class="fas fa-sign-in-alt me-2"></i>Login</button>
          </form>
          <hr>
          <div class="text-center">Don't have an account? <a href="register.php" class="text-primary fw-semibold">Register Here</a></div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    let valid = true;
    const username = document.getElementById('username').value;
    if (!/^[a-zA-Z0-9_.@]{3,100}$/.test(username)) {
        document.getElementById('username').classList.add('is-invalid');
        document.getElementById('usernameError').textContent = 'Username must be 3-100 alphanumeric characters.';
        valid = false;
    }
    if (!valid) e.preventDefault();
});
document.getElementById('togglePass').addEventListener('click', function() {
    const p = document.getElementById('password');
    p.type = p.type === 'password' ? 'text' : 'password';
    this.querySelector('i').classList.toggle('fa-eye');
    this.querySelector('i').classList.toggle('fa-eye-slash');
});
</script>
<?php include 'includes/footer.php'; ?>
