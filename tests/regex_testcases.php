<?php
// ============================================================
// tests/regex_testcases.php
// Regular Expression Test Cases Report
// Software Testing & Quality Assurance Mini Project
// COVID-19 Info Portal
// ============================================================
$totalTests = 0; $passedTests = 0; $failedTests = 0; $results = [];

function testRegex($name, $pattern, $input, $expected, $id='') {
    global $totalTests, $passedTests, $failedTests, $results;
    $totalTests++;
    $actual = (bool)preg_match($pattern, $input);
    $passed = ($actual === $expected);
    if ($passed) $passedTests++; else $failedTests++;
    $results[] = ['id'=>$id?:"TC-{$totalTests}",'name'=>$name,'pattern'=>$pattern,'input'=>$input,
        'expected'=>$expected?'MATCH':'NO MATCH','actual'=>$actual?'MATCH':'NO MATCH','status'=>$passed?'PASS':'FAIL'];
}

// Patterns
$UP='/^[a-zA-Z0-9_]{3,20}$/';
$EP='/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/';
$PP='/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@#$%^&+=!]).{8,}$/';
$PH='/^[6-9]\d{9}$/';
$NP='/^[a-zA-Z\s]{2,100}$/';
$PC='/^\d{6}$/';
$CP='/^[\s\S]{10,1000}$/';
$DP='/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/';
$URLP='/^(https?:\/\/)([\da-z\.\-]+)\.([a-z\.]{2,6})([\/\w \.\-]*)*\/?$/';

// USERNAME TESTS
testRegex('Username - Valid alphanumeric',        $UP,'john123',true,'TC-U01');
testRegex('Username - Valid with underscore',     $UP,'john_doe',true,'TC-U02');
testRegex('Username - Valid uppercase',           $UP,'AdminUser',true,'TC-U03');
testRegex('Username - Valid min length 3',        $UP,'abc',true,'TC-U04');
testRegex('Username - Valid max length 20',       $UP,'abcdefghij1234567890',true,'TC-U05');
testRegex('Username - FAIL: too short 2',         $UP,'ab',false,'TC-U06');
testRegex('Username - FAIL: too long 21',         $UP,'abcdefghijklmnopqrstu',false,'TC-U07');
testRegex('Username - FAIL: space in name',       $UP,'john doe',false,'TC-U08');
testRegex('Username - FAIL: @ symbol',            $UP,'john@doe',false,'TC-U09');
testRegex('Username - FAIL: empty',               $UP,'',false,'TC-U10');

// EMAIL TESTS
testRegex('Email - Valid simple',                  $EP,'user@example.com',true,'TC-E01');
testRegex('Email - Valid subdomain',               $EP,'user@mail.example.com',true,'TC-E02');
testRegex('Email - Valid with plus sign',          $EP,'user+tag@gmail.com',true,'TC-E03');
testRegex('Email - Valid with dots',               $EP,'first.last@domain.org',true,'TC-E04');
testRegex('Email - Valid dash in domain',          $EP,'user@my-site.co.in',true,'TC-E05');
testRegex('Email - Valid .edu domain',             $EP,'student@university.edu',true,'TC-E06');
testRegex('Email - FAIL: no @ symbol',             $EP,'userexample.com',false,'TC-E07');
testRegex('Email - FAIL: no domain',               $EP,'user@',false,'TC-E08');
testRegex('Email - FAIL: no TLD',                  $EP,'user@domain',false,'TC-E09');
testRegex('Email - FAIL: double @@',               $EP,'user@@domain.com',false,'TC-E10');
testRegex('Email - FAIL: space in email',          $EP,'user @domain.com',false,'TC-E11');
testRegex('Email - FAIL: empty',                   $EP,'',false,'TC-E12');

// PASSWORD TESTS
testRegex('Password - Valid strong Admin@123',     $PP,'Admin@123',true,'TC-P01');
testRegex('Password - Valid all types',            $PP,'Test#456Pass',true,'TC-P02');
testRegex('Password - Valid long password',        $PP,'MyP@ssw0rd2024!',true,'TC-P03');
testRegex('Password - Valid min 8 chars',          $PP,'Aa1@bCdE',true,'TC-P04');
testRegex('Password - FAIL: no uppercase',        $PP,'admin@123',false,'TC-P05');
testRegex('Password - FAIL: no lowercase',        $PP,'ADMIN@123',false,'TC-P06');
testRegex('Password - FAIL: no digit',            $PP,'Admin@Pass',false,'TC-P07');
testRegex('Password - FAIL: no special char',     $PP,'Admin1234',false,'TC-P08');
testRegex('Password - FAIL: too short 7',         $PP,'Aa1@bCd',false,'TC-P09');
testRegex('Password - FAIL: only spaces',         $PP,'        ',false,'TC-P10');
testRegex('Password - FAIL: empty',               $PP,'',false,'TC-P11');
testRegex('Password - Valid with dollar sign',    $PP,'Hello$World8',true,'TC-P12');

// PHONE TESTS
testRegex('Phone - Valid starts with 9',           $PH,'9876543210',true,'TC-PH01');
testRegex('Phone - Valid starts with 8',           $PH,'8765432109',true,'TC-PH02');
testRegex('Phone - Valid starts with 7',           $PH,'7654321098',true,'TC-PH03');
testRegex('Phone - Valid starts with 6',           $PH,'6543210987',true,'TC-PH04');
testRegex('Phone - FAIL: starts with 5',          $PH,'5987654321',false,'TC-PH05');
testRegex('Phone - FAIL: starts with 0',          $PH,'0987654321',false,'TC-PH06');
testRegex('Phone - FAIL: 9 digits',               $PH,'987654321',false,'TC-PH07');
testRegex('Phone - FAIL: 11 digits',              $PH,'98765432100',false,'TC-PH08');
testRegex('Phone - FAIL: letters in number',      $PH,'98765ABCDE',false,'TC-PH09');
testRegex('Phone - FAIL: empty',                  $PH,'',false,'TC-PH10');

// NAME TESTS
testRegex('Name - Valid simple name',              $NP,'John Doe',true,'TC-N01');
testRegex('Name - Valid single name',              $NP,'Rahul',true,'TC-N02');
testRegex('Name - Valid Indian full name',         $NP,'Ramesh Kumar Sharma',true,'TC-N03');
testRegex('Name - Valid min 2 chars',              $NP,'AB',true,'TC-N04');
testRegex('Name - FAIL: contains numbers',        $NP,'John2 Doe',false,'TC-N05');
testRegex('Name - FAIL: special chars',           $NP,'John@Doe',false,'TC-N06');
testRegex('Name - FAIL: only 1 char',             $NP,'A',false,'TC-N07');
testRegex('Name - FAIL: empty',                   $NP,'',false,'TC-N08');

// PINCODE TESTS
testRegex('Pincode - Valid 6 digits',              $PC,'400001',true,'TC-PC01');
testRegex('Pincode - Valid all zeros',             $PC,'000000',true,'TC-PC02');
testRegex('Pincode - FAIL: 5 digits',             $PC,'40000',false,'TC-PC03');
testRegex('Pincode - FAIL: 7 digits',             $PC,'4000011',false,'TC-PC04');
testRegex('Pincode - FAIL: letter inside',        $PC,'4000A1',false,'TC-PC05');
testRegex('Pincode - FAIL: empty',                $PC,'',false,'TC-PC06');
testRegex('Pincode - FAIL: special chars',        $PC,'400-001',false,'TC-PC07');

// COMMENT TESTS
testRegex('Comment - Valid 12 chars',              $CP,'Hello World!',true,'TC-C01');
testRegex('Comment - Valid with special chars',    $CP,'COVID-19 is dangerous. Stay safe!',true,'TC-C02');
testRegex('Comment - Valid 1000 chars',            $CP,str_repeat('A',1000),true,'TC-C03');
testRegex('Comment - Valid multiline',             $CP,"Line 1\nLine 2\nLine 3",true,'TC-C04');
testRegex('Comment - FAIL: 9 chars too short',    $CP,'Hi there!',false,'TC-C05');
testRegex('Comment - FAIL: 1001 chars too long',  $CP,str_repeat('A',1001),false,'TC-C06');
testRegex('Comment - FAIL: empty',                $CP,'',false,'TC-C07');

// DATE TESTS
testRegex('Date - Valid 2024-01-15',               $DP,'2024-01-15',true,'TC-D01');
testRegex('Date - Valid 2023-12-31',               $DP,'2023-12-31',true,'TC-D02');
testRegex('Date - Valid leap year',                $DP,'2024-02-29',true,'TC-D03');
testRegex('Date - FAIL: month 13',                $DP,'2024-13-01',false,'TC-D04');
testRegex('Date - FAIL: day 32',                  $DP,'2024-01-32',false,'TC-D05');
testRegex('Date - FAIL: wrong format DD-MM',      $DP,'15-01-2024',false,'TC-D06');
testRegex('Date - FAIL: no dashes',               $DP,'20240115',false,'TC-D07');
testRegex('Date - FAIL: empty',                   $DP,'',false,'TC-D08');

// URL TESTS
testRegex('URL - Valid http',                      $URLP,'http://www.example.com',true,'TC-URL01');
testRegex('URL - Valid https',                     $URLP,'https://covid19.gov.in',true,'TC-URL02');
testRegex('URL - Valid with path',                 $URLP,'https://www.who.int/covid-19',true,'TC-URL03');
testRegex('URL - FAIL: no protocol',              $URLP,'www.example.com',false,'TC-URL04');
testRegex('URL - FAIL: ftp protocol',             $URLP,'ftp://example.com',false,'TC-URL05');
testRegex('URL - FAIL: empty',                    $URLP,'',false,'TC-URL06');
testRegex('URL - FAIL: space in URL',             $URLP,'https://example .com',false,'TC-URL07');

$passRate = $totalTests > 0 ? round(($passedTests/$totalTests)*100,1) : 0;

// Save Test Run to Database (Audit Log)
$savedToDb = false;
$savedRunId = null;
try {
    require_once __DIR__ . '/../includes/config.php';
    if (isset($pdo)) {
        // 1. Insert Summary Record
        $stmt = $pdo->prepare("INSERT INTO test_runs (total_tests, passed_tests, failed_tests, pass_rate, executed_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$totalTests, $passedTests, $failedTests, $passRate, 'Automated STQA Runner']);
        $savedRunId = $pdo->lastInsertId();

        // 2. Insert Detailed Test Cases
        $detailStmt = $pdo->prepare("INSERT INTO test_case_details (run_id, test_id, test_name, test_pattern, input_value, expected_result, actual_result, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($results as $r) {
            $detailStmt->execute([
                $savedRunId,
                $r['id'],
                $r['name'],
                $r['pattern'],
                $r['input'],
                $r['expected'],
                $r['actual'],
                $r['status']
            ]);
        }
        $savedToDb = true;
    }
} catch (Exception $e) {
    // If DB has any temporary issue, testing report still displays gracefully
    $savedToDb = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regex Test Report - COVID-19 Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{background:#f0f2f5;font-family:'Segoe UI',sans-serif;}
        .report-header{background:linear-gradient(135deg,#1a1a2e,#0f3460);color:#fff;padding:50px 0 40px;}
        .stat-pill{background:rgba(255,255,255,.1);border-radius:12px;padding:16px 24px;text-align:center;}
        .section-title{background:#1a1a2e;color:#fff;padding:12px 20px;border-radius:8px;margin:30px 0 15px;}
        .status-pass{color:#198754;font-weight:700;} .status-fail{color:#dc3545;font-weight:700;}
        code{background:#2d2d2d;color:#f8f8f2;padding:2px 8px;border-radius:4px;font-size:.8rem;}
        .progress{height:25px;border-radius:8px;} .progress-bar{font-size:.95rem;font-weight:600;}
    </style>
</head>
<body>
<div class="report-header">
  <div class="container text-center">
    <div class="mb-3" style="font-size:3rem;">&#129514;</div>
    <h1 class="fw-bold">Regular Expression Test Cases Report</h1>
    <p class="mb-4" style="color:rgba(255,255,255,.8);">COVID-19 Info Portal &mdash; Software Testing &amp; QA Mini Project</p>
    <div class="row g-3 justify-content-center">
      <div class="col-auto"><div class="stat-pill"><div style="font-size:2.2rem;font-weight:800;"><?= $totalTests ?></div><div>Total Tests</div></div></div>
      <div class="col-auto"><div class="stat-pill" style="background:rgba(25,135,84,.3)"><div style="font-size:2.2rem;font-weight:800;color:#6effa6;"><?= $passedTests ?></div><div>Passed &#9989;</div></div></div>
      <div class="col-auto"><div class="stat-pill" style="background:rgba(220,53,69,.3)"><div style="font-size:2.2rem;font-weight:800;color:#ff8a8a;"><?= $failedTests ?></div><div>Failed &#10060;</div></div></div>
      <div class="col-auto"><div class="stat-pill" style="background:rgba(13,110,253,.3)"><div style="font-size:2.2rem;font-weight:800;color:#80c8ff;"><?= $passRate ?>%</div><div>Pass Rate</div></div></div>
    </div>
  </div>
</div>
<div class="container my-5">
  <?php if ($savedToDb): ?>
  <div class="alert alert-success d-flex align-items-center justify-content-between shadow-sm rounded-3 mb-4" role="alert">
    <div>
      <i class="fas fa-database me-2 fs-5"></i>
      <strong>Database Audit Log Saved:</strong> This execution report was successfully logged into MySQL table <code>test_runs</code> (Run ID: <strong>#<?= $savedRunId ?></strong>) and <code>test_case_details</code>.
    </div>
    <span class="badge bg-success px-3 py-2">MySQL Logged ✅</span>
  </div>
  <?php endif; ?>

  <!-- Progress -->
  <div class="card border-0 shadow rounded-4 mb-4"><div class="card-body p-4">
    <div class="d-flex justify-content-between mb-2"><strong>Overall Pass Rate</strong><span><?= $passedTests ?>/<?= $totalTests ?> passed</span></div>
    <div class="progress"><div class="progress-bar <?= $passRate>=80?'bg-success':($passRate>=50?'bg-warning':'bg-danger') ?>" style="width:<?= $passRate ?>%"><?= $passRate ?>%</div></div>
  </div></div>

  <?php
  $sections=[
    'TC-U'  =>['1. Username Validation',         'fa-user',    $UP],
    'TC-E'  =>['2. Email Validation',            'fa-envelope',$EP],
    'TC-P'  =>['3. Password Validation',         'fa-lock',    $PP],
    'TC-PH' =>['4. Phone Number Validation',     'fa-phone',   $PH],
    'TC-N'  =>['5. Full Name Validation',        'fa-id-card', $NP],
    'TC-PC' =>['6. Pincode Validation',          'fa-map-pin', $PC],
    'TC-C'  =>['7. Comment Length Validation',   'fa-comment', $CP],
    'TC-D'  =>['8. Date Format Validation',      'fa-calendar',$DP],
    'TC-URL'=>['9. URL Format Validation',       'fa-globe',   $URLP],
  ];
  foreach ($sections as $prefix=>[$title,$icon,$pattern]):
    $sr = array_values(array_filter($results,fn($r)=>strpos($r['id'],$prefix)===0));
    if(!$sr) continue;
    $sp = count(array_filter($sr,fn($r)=>$r['status']==='PASS'));
  ?>
  <div class="section-title d-flex justify-content-between">
    <span><i class="fas <?= $icon ?> me-2"></i><?= $title ?></span>
    <div><span class="badge bg-success me-1"><?= $sp ?> PASS</span><span class="badge bg-danger"><?= count($sr)-$sp ?> FAIL</span></div>
  </div>
  <div class="alert alert-secondary mb-3 py-2"><strong>Regex Pattern:</strong> <code><?= htmlspecialchars($pattern) ?></code></div>
  <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
    <div class="table-responsive"><table class="table table-hover mb-0">
      <thead class="table-dark"><tr><th>Test ID</th><th>Test Name</th><th>Input</th><th>Expected</th><th>Actual</th><th>Status</th></tr></thead>
      <tbody>
        <?php foreach($sr as $r): ?>
        <tr class="<?= $r['status']==='FAIL'?'table-danger':'' ?>">
          <td><code><?= htmlspecialchars($r['id']) ?></code></td>
          <td><?= htmlspecialchars($r['name']) ?></td>
          <td><code title="<?= htmlspecialchars($r['input']) ?>"><?= htmlspecialchars(strlen($r['input'])>35?substr($r['input'],0,35).'...':($r['input']===''?'(empty)':$r['input'])) ?></code></td>
          <td><span class="badge <?= $r['expected']==='MATCH'?'bg-primary':'bg-secondary' ?>"><?= $r['expected'] ?></span></td>
          <td><span class="badge <?= $r['actual']==='MATCH'?'bg-primary':'bg-secondary' ?>"><?= $r['actual'] ?></span></td>
          <td class="status-<?= strtolower($r['status']) ?>"><?= $r['status']==='PASS'?'&#9989; PASS':'&#10060; FAIL' ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table></div>
  </div>
  <?php endforeach; ?>

  <!-- Summary -->
  <div class="card border-0 shadow rounded-4 bg-dark text-white mt-4"><div class="card-body p-4 text-center">
    <h4 class="fw-bold mb-3">&#128203; Test Summary</h4>
    <div class="row g-3">
      <div class="col-md-3"><div class="p-3 rounded" style="background:rgba(255,255,255,.1)"><h3 class="fw-bold"><?= $totalTests ?></h3><div class="text-muted">Total</div></div></div>
      <div class="col-md-3"><div class="p-3 rounded" style="background:rgba(25,135,84,.3)"><h3 class="fw-bold text-success"><?= $passedTests ?></h3><div class="text-muted">Passed</div></div></div>
      <div class="col-md-3"><div class="p-3 rounded" style="background:rgba(220,53,69,.3)"><h3 class="fw-bold text-danger"><?= $failedTests ?></h3><div class="text-muted">Failed</div></div></div>
      <div class="col-md-3"><div class="p-3 rounded" style="background:rgba(13,110,253,.3)"><h3 class="fw-bold text-primary"><?= $passRate ?>%</h3><div class="text-muted">Pass Rate</div></div></div>
    </div>
    <p class="mt-3 text-muted small">Executed: <?= date('d F Y h:i A') ?> | PHP <?= PHP_VERSION ?></p>
  </div></div>
  <div class="text-center mt-4"><a href="../index.php" class="btn btn-primary"><i class="fas fa-home me-2"></i>Back to Portal</a></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
