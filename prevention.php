<?php
require_once 'includes/config.php';
$pageTitle = 'Prevention Tips - COVID-19 Portal';
include 'includes/header.php';
$tips = [
    ['fa-head-side-mask','primary','Wear a Mask','Always wear a properly fitted mask in public, especially indoors. N95/KN95 masks offer the best protection. Cover both mouth and nose completely.'],
    ['fa-hands-wash','info','Wash Your Hands','Wash hands with soap and water for at least 20 seconds frequently throughout the day. Use 60%+ alcohol hand sanitizer when soap is not available.'],
    ['fa-people-arrows','success','Social Distancing','Maintain at least 6 feet (2 meters) from others. Avoid crowded, poorly ventilated areas. COVID-19 spreads through respiratory droplets in the air.'],
    ['fa-syringe','danger','Get Vaccinated','Vaccination is the most effective protection against COVID-19. Get both doses and booster doses as recommended. Vaccines are safe, effective, and free.'],
    ['fa-ban','warning','Avoid Crowded Places','Avoid large gatherings, parties, or crowded events. If unavoidable, ensure proper ventilation and wear a high-quality mask throughout.'],
    ['fa-house-medical','secondary','Self-Isolate When Sick','If you feel unwell with COVID symptoms, stay home immediately. Get tested, notify close contacts, and follow health authority isolation guidelines.'],
    ['fa-wind','primary','Improve Ventilation','Open windows and doors to improve air circulation. Use air purifiers with HEPA filters. Prefer outdoor settings for meetings and activities.'],
    ['fa-eye','info','Avoid Touching Face','Avoid touching your eyes, nose, and mouth, especially with unwashed hands. Viruses can enter the body through these mucous membranes.'],
    ['fa-utensils','success','Safe Food Practices','Wash fruits and vegetables thoroughly. Prefer home-cooked meals. Use contactless delivery. Avoid sharing utensils or food with others.'],
];
?>
<div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold display-5">COVID-19 Prevention Tips</h2>
        <p class="lead text-muted">Simple steps that can protect you and save lives</p>
    </div>
    <div class="row g-4">
        <?php foreach($tips as [$icon,$color,$title,$desc]): ?>
        <div class="col-md-4"><div class="card h-100 border-0 shadow-sm rounded-3 prevention-card text-center"><div class="card-body p-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-<?= $color ?> text-white rounded-circle mb-3" style="width:70px;height:70px;"><i class="fas <?= $icon ?> fs-3"></i></div>
            <h5 class="fw-bold"><?= $title ?></h5>
            <p class="text-muted"><?= $desc ?></p>
        </div></div></div>
        <?php endforeach; ?>
    </div>
    <div class="row mt-5"><div class="col-12"><div class="card border-0 bg-primary text-white rounded-4"><div class="card-body p-4 text-center">
        <h4 class="fw-bold"><i class="fas fa-syringe me-2"></i>Best Prevention: Get Vaccinated!</h4>
        <p class="mb-3">Vaccination reduces risk of severe illness by up to 95%. It is safe, free, and available across India.</p>
        <a href="vaccine_registration.php" class="btn btn-light fw-semibold px-4"><i class="fas fa-calendar-check me-2"></i>Book Vaccine Slot</a>
    </div></div></div></div>
</div>
<?php include 'includes/footer.php'; ?>
