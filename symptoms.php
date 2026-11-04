<?php
require_once 'includes/config.php';
$pageTitle = 'Symptoms - COVID-19 Portal';
include 'includes/header.php';
$common = [
    ['fa-thermometer-half','danger','Fever or Chills','Temperature above 100.4°F (38°C)'],
    ['fa-lungs','primary','Dry Cough','Continuous persistent dry cough'],
    ['fa-wind','info','Shortness of Breath','Difficulty breathing or breathlessness'],
    ['fa-tired','secondary','Fatigue','Extreme tiredness and weakness'],
    ['fa-head-side-virus','warning','Headache / Body Aches','Persistent headache or muscle pain'],
    ['fa-nose','success','Loss of Taste/Smell','Sudden anosmia or ageusia'],
    ['fa-stomach','danger','Nausea / Vomiting','Gastrointestinal disturbances'],
    ['fa-head-side-cough','primary','Sore Throat','Throat irritation, pain or dryness'],
];
$serious = [
    'Trouble breathing or gasping for air',
    'Persistent pain or pressure in the chest',
    'New confusion or inability to stay awake',
    'Bluish lips, face or fingernails (cyanosis)',
    'Severe abdominal pain',
    'High fever above 104°F that does not reduce',
    'Blood in urine, stool or cough',
    'Severe neurological symptoms or seizures',
];
?>
<div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold display-5">Know the Symptoms</h2>
        <p class="lead text-muted">Early recognition can save lives</p>
    </div>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow rounded-4 h-100">
                <div class="card-header bg-warning text-dark fw-bold rounded-top-4 py-3"><i class="fas fa-exclamation-triangle me-2"></i>Common Symptoms</div>
                <div class="card-body p-4">
                    <?php foreach($common as [$icon,$color,$name,$desc]): ?>
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-<?= $color ?> text-white me-3 d-flex align-items-center justify-content-center" style="width:42px;height:42px;flex-shrink:0;"><i class="fas <?= $icon ?>"></i></div>
                        <div><strong><?= $name ?></strong><br><small class="text-muted"><?= $desc ?></small></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow rounded-4 h-100">
                <div class="card-header bg-danger text-white fw-bold rounded-top-4 py-3"><i class="fas fa-ambulance me-2"></i>Emergency Symptoms - Call 112</div>
                <div class="card-body p-4">
                    <div class="alert alert-danger"><strong>Seek emergency care IMMEDIATELY if you notice:</strong></div>
                    <?php foreach($serious as $s): ?>
                    <div class="d-flex align-items-center mb-3"><i class="fas fa-exclamation-circle text-danger me-2 fs-5"></i><span><?= $s ?></span></div>
                    <?php endforeach; ?>
                    <div class="alert alert-warning mt-3"><i class="fas fa-phone me-2"></i><strong>Emergency:</strong> Dial 112 or go to nearest hospital</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4"><div class="col-12"><div class="card border-0 bg-primary text-white rounded-4"><div class="card-body p-4 text-center">
        <h4 class="fw-bold"><i class="fas fa-vial me-2"></i>When Should You Get Tested?</h4>
        <p class="mb-3">Get tested if: you have symptoms, you were in close contact with a positive case, or you are returning from high-risk areas.</p>
        <div class="row g-3">
            <div class="col-md-4"><div class="bg-white text-primary rounded p-3 fw-semibold"><i class="fas fa-clock me-2"></i>PCR Test: 24-48 hrs</div></div>
            <div class="col-md-4"><div class="bg-white text-primary rounded p-3 fw-semibold"><i class="fas fa-bolt me-2"></i>Rapid Antigen: 15-30 min</div></div>
            <div class="col-md-4"><div class="bg-white text-primary rounded p-3 fw-semibold"><i class="fas fa-home me-2"></i>Home Kit: At pharmacies</div></div>
        </div>
    </div></div></div></div>
</div>
<?php include 'includes/footer.php'; ?>
