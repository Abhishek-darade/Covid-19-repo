<?php
require_once 'includes/config.php';
if (isLoggedIn()) redirect('index.php');
$pageTitle = 'Register - COVID-19 Portal';
$errors = []; $success = '';
$patterns = [
    'username'  => '/^[a-zA-Z0-9_]{3,20}$/',
    'email'     => '/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/',
    'password'  => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@#$%^&+=!]).{8,}$/',
    'phone'     => '/^[6-9]\d{9}$/',
    'full_name' => '/^[a-zA-Z\s]{2,50}$/',
];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = sanitize($_POST['username'] ?? '');
    $email     = sanitize($_POST['email']    ?? '');
    $password  = $_POST['password']          ?? '';
    $confirm   = $_POST['confirm_password']  ?? '';
    $full_name = sanitize($_POST['full_name']?? '');
    $phone     = sanitize($_POST['phone']    ?? '');
    if (!preg_match($patterns['full_name'], $full_name)) $errors['full_name'] = 'Full name: letters and spaces only (2-50 chars).';
    if (!preg_match($patterns['username'],  $username))  $errors['username']  = 'Username: 3-20 chars, letters/numbers/underscore.';
    if (!preg_match($patterns['email'],     $email))     $errors['email']     = 'Enter a valid email address.';
    if (!preg_match($patterns['phone'],     $phone))     $errors['phone']     = 'Enter valid 10-digit Indian mobile (starts 6-9).';
    if (!preg_match($patterns['password'],  $password))  $errors['password']  = 'Password: min 8 chars with uppercase, lowercase, digit, special (@#$%^&+=!).';
    if ($password !== $confirm)                          $errors['confirm']   = 'Passwords do not match.';
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username=? OR email=?");
        $stmt->execute([$username, $email]);
        if ($stmt->rowCount() > 0) $errors['duplicate'] = 'Username or email already exists.';
    }
    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $pdo->prepare("INSERT INTO users (username,email,password,full_name,phone) VALUES(?,?,?,?,?)")
            ->execute([$username, $email, $hashed, $full_name, $phone]);
        $success = 'Registration successful! You can now login.';
    }
}
include 'includes/header.php';
?>
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header text-center bg-danger text-white rounded-top-4 py-3">
          <h3 class="mb-0"><i class="fas fa-user-plus me-2"></i>Create Account</h3>
        </div>
        <div class="card-body p-4">
          <?php if ($success): ?><div class="alert alert-success"><i class="fas fa-check-circle me-2"></i><?= $success ?> <a href="login.php" class="btn btn-sm btn-success ms-3">Login Now</a></div><?php endif; ?>
          <?php if (isset($errors['duplicate'])): ?><div class="alert alert-danger"><?= $errors['duplicate'] ?></div><?php endif; ?>
          <form method="POST" id="registerForm" novalidate>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="full_name" id="full_name" class="form-control <?= isset($errors['full_name'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($_POST['full_name']??'') ?>" placeholder="Enter full name">
                <div class="invalid-feedback"><?= $errors['full_name']??'' ?></div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                <input type="text" name="username" id="username" class="form-control <?= isset($errors['username'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($_POST['username']??'') ?>" placeholder="Choose username">
                <div class="invalid-feedback"><?= $errors['username']??'' ?></div>
                <small class="text-muted">3-20 chars, letters/numbers/underscore</small>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" class="form-control <?= isset($errors['email'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($_POST['email']??'') ?>" placeholder="your@email.com">
                <div class="invalid-feedback"><?= $errors['email']??'' ?></div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Mobile Number <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">+91</span>
                  <input type="tel" name="phone" id="phone" class="form-control <?= isset($errors['phone'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($_POST['phone']??'') ?>" placeholder="9876543210" maxlength="10">
                </div>
                <div class="invalid-feedback d-block"><?= $errors['phone']??'' ?></div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="password" name="password" id="password" class="form-control <?= isset($errors['password'])?'is-invalid':'' ?>" placeholder="Create strong password">
                  <button class="btn btn-outline-secondary" type="button" onclick="togglePwd('password')"><i class="fas fa-eye"></i></button>
                </div>
                <div class="invalid-feedback d-block"><?= $errors['password']??'' ?></div>
                <div id="pwdStrength" class="mt-1"></div>
                <small class="text-muted">Min 8: uppercase, lowercase, number, special char</small>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="password" name="confirm_password" id="confirm_password" class="form-control <?= isset($errors['confirm'])?'is-invalid':'' ?>" placeholder="Repeat password">
                  <button class="btn btn-outline-secondary" type="button" onclick="togglePwd('confirm_password')"><i class="fas fa-eye"></i></button>
                </div>
                <div class="invalid-feedback d-block"><?= $errors['confirm']??'' ?></div>
              </div>
              <div class="col-12">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                  <label class="form-check-label" for="terms">I agree to <a href="#">Terms &amp; Conditions</a></label>
                </div>
              </div>
              <div class="col-12"><button type="submit" class="btn btn-danger w-100 py-2"><i class="fas fa-user-plus me-2"></i>Register Now</button></div>
            </div>
          </form>
          <hr><div class="text-center">Already have an account? <a href="login.php" class="text-danger fw-semibold">Login Here</a></div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
const regexPatterns = {
    username: /^[a-zA-Z0-9_]{3,20}$/,
    email:    /^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/,
    password: /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@#$%^&+=!]).{8,}$/,
    phone:    /^[6-9]\d{9}$/,
    full_name:/^[a-zA-Z\s]{2,50}$/
};
function validateField(id, regex, msg) {
    const el = document.getElementById(id);
    const valid = regex.test(el.value.trim());
    el.classList.toggle('is-invalid', !valid);
    el.classList.toggle('is-valid', valid);
    return valid;
}
document.getElementById('password').addEventListener('input', function() {
    const v = this.value, s = document.getElementById('pwdStrength');
    let score = [/[A-Z]/,/[a-z]/,/\d/,/[@#$%^&+=!]/,/.{8}/].filter(r=>r.test(v)).length;
    const labels = ['','Very Weak','Weak','Fair','Strong','Very Strong'];
    const colors = ['','danger','warning','info','primary','success'];
    s.innerHTML = score ? `<div class="progress" style="height:6px"><div class="progress-bar bg-${colors[score]}" style="width:${score*20}%"></div></div><small class="text-${colors[score]}">${labels[score]}</small>` : '';
});
document.getElementById('registerForm').addEventListener('submit', function(e) {
    let valid = true;
    valid &= validateField('full_name', regexPatterns.full_name, '');
    valid &= validateField('username',  regexPatterns.username, '');
    valid &= validateField('email',     regexPatterns.email, '');
    valid &= validateField('phone',     regexPatterns.phone, '');
    valid &= validateField('password',  regexPatterns.password, '');
    if (document.getElementById('password').value !== document.getElementById('confirm_password').value) {
        document.getElementById('confirm_password').classList.add('is-invalid'); valid = false;
    }
    if (!document.getElementById('terms').checked) { alert('Accept Terms & Conditions.'); valid = false; }
    if (!valid) e.preventDefault();
});
function togglePwd(id) { const e=document.getElementById(id); e.type = e.type==='password'?'text':'password'; }
</script>
<?php include 'includes/footer.php'; ?>
