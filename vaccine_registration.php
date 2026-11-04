<?php
require_once 'includes/config.php';
$pageTitle = 'Vaccine Registration - COVID-19 Portal';
$errors = []; $success = '';
$patterns = [
    'full_name'=>'/^[a-zA-Z\s]{2,100}$/',
    'email'    =>'/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/',
    'phone'    =>'/^[6-9]\d{9}$/',
    'pincode'  =>'/^\d{6}$/',
    'city'     =>'/^[a-zA-Z\s]{2,50}$/',
    'state'    =>'/^[a-zA-Z\s]{2,50}$/',
];
$vaccines = ['Covishield','Covaxin','Sputnik V','Johnson & Johnson'];
$slots    = ['09:00 AM','11:00 AM','02:00 PM','04:00 PM'];

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $d = [
        'full_name'   =>sanitize($_POST['full_name']??''),
        'email'       =>sanitize($_POST['email']??''),
        'phone'       =>sanitize($_POST['phone']??''),
        'dob'         =>sanitize($_POST['dob']??''),
        'address'     =>sanitize($_POST['address']??''),
        'city'        =>sanitize($_POST['city']??''),
        'state'       =>sanitize($_POST['state']??''),
        'pincode'     =>sanitize($_POST['pincode']??''),
        'vaccine_type'=>sanitize($_POST['vaccine_type']??''),
        'slot_date'   =>sanitize($_POST['slot_date']??''),
        'slot_time'   =>sanitize($_POST['slot_time']??''),
    ];
    if (!preg_match($patterns['full_name'],$d['full_name'])) $errors['full_name']='Full name: letters and spaces only.';
    if (!preg_match($patterns['email'],$d['email']))         $errors['email']='Invalid email address.';
    if (!preg_match($patterns['phone'],$d['phone']))         $errors['phone']='Valid 10-digit mobile required.';
    if (!preg_match($patterns['pincode'],$d['pincode']))     $errors['pincode']='Pincode must be exactly 6 digits.';
    if (!preg_match($patterns['city'],$d['city']))           $errors['city']='Invalid city name.';
    if (!preg_match($patterns['state'],$d['state']))         $errors['state']='Invalid state name.';
    if (empty($d['dob'])) { $errors['dob']='DOB is required.'; }
    else { $dob=new DateTime($d['dob']); $now=new DateTime(); if($now->diff($dob)->y<18) $errors['dob']='Must be 18+.'; }
    if (strlen(trim($d['address']))<10)                      $errors['address']='Complete address required (min 10 chars).';
    if (!in_array($d['vaccine_type'],$vaccines))             $errors['vaccine_type']='Select a valid vaccine.';
    if (empty($d['slot_date'])||strtotime($d['slot_date'])<time()) $errors['slot_date']='Select a future date.';
    if (!in_array($d['slot_time'],$slots))                   $errors['slot_time']='Select a valid time slot.';

    if (empty($errors)) {
        $pdo->prepare("INSERT INTO registrations (full_name,email,phone,dob,address,city,state,pincode,vaccine_type,slot_date,slot_time) VALUES(?,?,?,?,?,?,?,?,?,?,?)")
            ->execute(array_values($d));
        $regId = $pdo->lastInsertId();
        $success = "Registration successful! ID: <strong>#REG{$regId}</strong>. Confirmation will be sent to {$d['email']}.";
    }
}
include 'includes/header.php';
?>
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-9">
      <div class="text-center mb-5">
        <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle mb-3" style="width:70px;height:70px;font-size:2rem;"><i class="fas fa-syringe"></i></div>
        <h2 class="fw-bold">Vaccine Registration Form</h2>
        <p class="text-muted">Fill all details carefully to book your vaccination slot.</p>
      </div>
      <?php if($success): ?><div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i><?= $success ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4 p-md-5">
          <form method="POST" id="vaccineForm" novalidate>
            <h5 class="fw-bold text-primary mb-3 border-bottom pb-2"><i class="fas fa-user me-2"></i>Personal Information</h5>
            <div class="row g-3 mb-4">
              <div class="col-md-6"><label class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="full_name" class="form-control <?= isset($errors['full_name'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($_POST['full_name']??'') ?>" placeholder="As per Aadhaar">
                <div class="invalid-feedback"><?= $errors['full_name']??'' ?></div></div>
              <div class="col-md-6"><label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                <input type="date" name="dob" class="form-control <?= isset($errors['dob'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($_POST['dob']??'') ?>" max="<?= date('Y-m-d',strtotime('-18 years')) ?>">
                <div class="invalid-feedback"><?= $errors['dob']??'' ?></div></div>
              <div class="col-md-6"><label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control <?= isset($errors['email'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($_POST['email']??'') ?>" placeholder="your@email.com">
                <div class="invalid-feedback"><?= $errors['email']??'' ?></div></div>
              <div class="col-md-6"><label class="form-label">Mobile <span class="text-danger">*</span></label>
                <div class="input-group"><span class="input-group-text">+91</span>
                <input type="tel" name="phone" class="form-control <?= isset($errors['phone'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($_POST['phone']??'') ?>" placeholder="9876543210" maxlength="10"></div>
                <div class="invalid-feedback d-block"><?= $errors['phone']??'' ?></div></div>
            </div>
            <h5 class="fw-bold text-primary mb-3 border-bottom pb-2"><i class="fas fa-map-marker-alt me-2"></i>Address Details</h5>
            <div class="row g-3 mb-4">
              <div class="col-12"><label class="form-label">Full Address <span class="text-danger">*</span></label>
                <textarea name="address" rows="2" class="form-control <?= isset($errors['address'])?'is-invalid':'' ?>" placeholder="House No, Street, Area..."><?= htmlspecialchars($_POST['address']??'') ?></textarea>
                <div class="invalid-feedback"><?= $errors['address']??'' ?></div></div>
              <div class="col-md-4"><label class="form-label">City <span class="text-danger">*</span></label>
                <input type="text" name="city" class="form-control <?= isset($errors['city'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($_POST['city']??'') ?>" placeholder="Mumbai">
                <div class="invalid-feedback"><?= $errors['city']??'' ?></div></div>
              <div class="col-md-4"><label class="form-label">State <span class="text-danger">*</span></label>
                <input type="text" name="state" class="form-control <?= isset($errors['state'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($_POST['state']??'') ?>" placeholder="Maharashtra">
                <div class="invalid-feedback"><?= $errors['state']??'' ?></div></div>
              <div class="col-md-4"><label class="form-label">Pincode <span class="text-danger">*</span></label>
                <input type="text" name="pincode" class="form-control <?= isset($errors['pincode'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($_POST['pincode']??'') ?>" placeholder="400001" maxlength="6">
                <div class="invalid-feedback"><?= $errors['pincode']??'' ?></div></div>
            </div>
            <h5 class="fw-bold text-primary mb-3 border-bottom pb-2"><i class="fas fa-calendar-check me-2"></i>Vaccine & Slot</h5>
            <div class="row g-3 mb-4">
              <div class="col-md-4"><label class="form-label">Vaccine <span class="text-danger">*</span></label>
                <select name="vaccine_type" class="form-select <?= isset($errors['vaccine_type'])?'is-invalid':'' ?>">
                  <option value="">-- Select --</option>
                  <?php foreach($vaccines as $v): ?><option value="<?= $v ?>" <?= (($_POST['vaccine_type']??'')===$v)?'selected':'' ?>><?= $v ?></option><?php endforeach; ?>
                </select><div class="invalid-feedback"><?= $errors['vaccine_type']??'' ?></div></div>
              <div class="col-md-4"><label class="form-label">Slot Date <span class="text-danger">*</span></label>
                <input type="date" name="slot_date" class="form-control <?= isset($errors['slot_date'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($_POST['slot_date']??'') ?>" min="<?= date('Y-m-d',strtotime('+1 day')) ?>">
                <div class="invalid-feedback"><?= $errors['slot_date']??'' ?></div></div>
              <div class="col-md-4"><label class="form-label">Slot Time <span class="text-danger">*</span></label>
                <select name="slot_time" class="form-select <?= isset($errors['slot_time'])?'is-invalid':'' ?>">
                  <option value="">-- Select Time --</option>
                  <?php foreach($slots as $s): ?><option value="<?= $s ?>" <?= (($_POST['slot_time']??'')===$s)?'selected':'' ?>><?= $s ?></option><?php endforeach; ?>
                </select><div class="invalid-feedback"><?= $errors['slot_time']??'' ?></div></div>
            </div>
            <div class="d-grid"><button type="submit" class="btn btn-success btn-lg"><i class="fas fa-syringe me-2"></i>Book Vaccination Slot</button></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
