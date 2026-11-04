<?php
require_once 'includes/config.php';
$pageTitle = 'COVID-19 Info Portal - Stay Safe Stay Informed';
try {
    $stmt = $pdo->prepare("SELECT * FROM covid_stats WHERE country='India' ORDER BY stat_date DESC LIMIT 1");
    $stmt->execute(); $stats = $stmt->fetch();
} catch (Exception $e) { $stats = ['total_cases'=>0,'active_cases'=>0,'recovered'=>0,'deaths'=>0,'vaccinated'=>0]; }
try {
    $cs = $pdo->query("SELECT c.*,u.username,u.full_name FROM comments c JOIN users u ON c.user_id=u.id ORDER BY c.created_at DESC LIMIT 3");
    $recentComments = $cs->fetchAll();
} catch (Exception $e) { $recentComments = []; }
include 'includes/header.php';
?>
<!-- HERO -->
<section class="hero-section">
  <div class="container" style="position:relative;z-index:2;">
    <div class="hero-badge">&#129440; COVID-19 INFORMATION HUB</div>
    <h1>Stay Informed.<br>Stay <span style="color:#e63946;">Safe.</span></h1>
    <p class="lead">Your trusted source for COVID-19 statistics, prevention, symptoms &amp; vaccine registration in India.</p>
    <div class="d-flex gap-3 justify-content-center flex-wrap">
      <a href="statistics.php" class="btn btn-light btn-lg px-5 fw-semibold"><i class="fas fa-chart-bar me-2"></i>View Statistics</a>
      <a href="vaccine_registration.php" class="btn btn-danger btn-lg px-5 fw-semibold"><i class="fas fa-syringe me-2"></i>Register for Vaccine</a>
    </div>
  </div>
</section>

<!-- TICKER -->
<div class="ticker-wrap">
  <div class="ticker-move">
    &#128680; LIVE: India Cases: <?= formatNumber($stats['total_cases']) ?> &nbsp;|&nbsp;
    Active: <?= formatNumber($stats['active_cases']) ?> &nbsp;|&nbsp;
    Recovered: <?= formatNumber($stats['recovered']) ?> &nbsp;|&nbsp;
    Deaths: <?= formatNumber($stats['deaths']) ?> &nbsp;|&nbsp;
    Vaccinated: <?= formatNumber($stats['vaccinated']) ?> &nbsp;|&nbsp;
    Helpline: 1075 &nbsp;|&nbsp; Stay Home. Wear Mask. Get Vaccinated.
    &nbsp;&nbsp;&nbsp;&nbsp;
    &#128680; LIVE: India Cases: <?= formatNumber($stats['total_cases']) ?> &nbsp;|&nbsp;
    Active: <?= formatNumber($stats['active_cases']) ?>&nbsp;|&nbsp;Deaths: <?= formatNumber($stats['deaths']) ?>
  </div>
</div>

<!-- STATS -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="section-heading">India COVID-19 Statistics</h2>
    <div class="row g-4">
      <div class="col-6 col-md-3"><div class="stat-card total"><div class="stat-icon">&#127777;</div><div class="stat-number"><?= formatNumber($stats['total_cases']) ?></div><div class="text-muted mt-1">Total Cases</div></div></div>
      <div class="col-6 col-md-3"><div class="stat-card active"><div class="stat-icon">&#9888;</div><div class="stat-number"><?= formatNumber($stats['active_cases']) ?></div><div class="text-muted mt-1">Active Cases</div></div></div>
      <div class="col-6 col-md-3"><div class="stat-card recovered"><div class="stat-icon">&#9989;</div><div class="stat-number"><?= formatNumber($stats['recovered']) ?></div><div class="text-muted mt-1">Recovered</div></div></div>
      <div class="col-6 col-md-3"><div class="stat-card deaths"><div class="stat-icon">&#128148;</div><div class="stat-number"><?= formatNumber($stats['deaths']) ?></div><div class="text-muted mt-1">Deaths</div></div></div>
    </div>
    <div class="row g-4 mt-1"><div class="col-12 col-md-4 offset-md-4"><div class="stat-card vaccinated"><div class="stat-icon">&#128137;</div><div class="stat-number"><?= formatNumber($stats['vaccinated']) ?></div><div class="text-muted mt-1">Total Vaccinated</div></div></div></div>
    <div class="text-center mt-3"><a href="statistics.php" class="btn btn-outline-primary"><i class="fas fa-chart-bar me-2"></i>Full Statistics & Charts</a></div>
  </div>
</section>

<!-- ABOUT -->
<section class="py-5">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-md-7">
        <h2 class="fw-bold mb-4">What is COVID-19?</h2>
        <p class="text-muted mb-3">COVID-19 is an infectious disease caused by the SARS-CoV-2 coronavirus, first identified in December 2019 in Wuhan, China. The WHO declared a global pandemic in March 2020. The virus spreads mainly through respiratory droplets when an infected person coughs, sneezes, or talks.</p>
        <p class="text-muted mb-4">Most infected people experience mild to moderate symptoms and recover without special treatment. However, older people and those with underlying conditions are at higher risk of serious illness. Vaccination is the most effective way to protect yourself.</p>
        <div class="row g-3">
          <div class="col-6"><div class="d-flex align-items-center gap-2 bg-light p-3 rounded"><i class="fas fa-virus text-danger fs-4"></i><div><strong>Virus</strong><br><small class="text-muted">SARS-CoV-2</small></div></div></div>
          <div class="col-6"><div class="d-flex align-items-center gap-2 bg-light p-3 rounded"><i class="fas fa-globe text-primary fs-4"></i><div><strong>Pandemic</strong><br><small class="text-muted">Since March 2020</small></div></div></div>
          <div class="col-6"><div class="d-flex align-items-center gap-2 bg-light p-3 rounded"><i class="fas fa-wind text-info fs-4"></i><div><strong>Spread</strong><br><small class="text-muted">Respiratory Droplets</small></div></div></div>
          <div class="col-6"><div class="d-flex align-items-center gap-2 bg-light p-3 rounded"><i class="fas fa-syringe text-success fs-4"></i><div><strong>Vaccine</strong><br><small class="text-muted">Available & Effective</small></div></div></div>
        </div>
      </div>
      <div class="col-md-5 text-center">
        <div class="covid-img-placeholder"><span>&#129440;</span></div>
        <small class="text-muted d-block mt-2">SARS-CoV-2 Coronavirus</small>
      </div>
    </div>
  </div>
</section>

<!-- PREVENTION -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="section-heading">How to Prevent COVID-19?</h2>
    <div class="row g-4">
      <?php foreach([
        ['fa-head-side-mask','primary','Wear a Mask','Always wear a mask covering mouth and nose in public. N95 masks offer best protection.'],
        ['fa-hands-wash','info','Wash Hands','Wash with soap for 20 seconds. Use 60%+ alcohol sanitizer when soap unavailable.'],
        ['fa-people-arrows','success','Social Distance','Keep 6 feet from others. Avoid crowded, poorly ventilated places.'],
        ['fa-syringe','danger','Get Vaccinated','Both doses + booster. Vaccination is the most effective protection.'],
        ['fa-ban','warning','Avoid Crowds','Skip gatherings. Prefer outdoor activities with mask.'],
        ['fa-house-medical','secondary','Self Isolate','Stay home if sick. Get tested. Notify contacts. Isolate 5+ days.'],
      ] as [$icon,$color,$title,$desc]): ?>
      <div class="col-md-4"><div class="card h-100 border-0 shadow-sm rounded-3 prevention-card text-center"><div class="card-body p-4">
        <div class="d-inline-flex align-items-center justify-content-center bg-<?= $color ?> text-white rounded-circle mb-3" style="width:60px;height:60px;font-size:1.5rem;"><i class="fas <?= $icon ?>"></i></div>
        <h6 class="fw-bold"><?= $title ?></h6><p class="text-muted small mb-0"><?= $desc ?></p>
      </div></div></div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4"><a href="prevention.php" class="btn btn-primary"><i class="fas fa-shield-virus me-2"></i>All Prevention Tips</a></div>
  </div>
</section>

<!-- SYMPTOMS -->
<section class="py-5">
  <div class="container">
    <h2 class="section-heading">Know the Symptoms</h2>
    <div class="row g-4">
      <div class="col-md-6">
        <div class="card border-0 shadow rounded-4 h-100">
          <div class="card-header bg-warning text-dark fw-bold rounded-top-4 py-3"><i class="fas fa-thermometer me-2"></i>Common Symptoms</div>
          <div class="card-body">
            <?php foreach(['&#127777; Fever or Chills','&#128568; Dry Cough','&#128168; Shortness of Breath','&#128547; Fatigue','&#129314; Headache / Body Aches','&#128067; Loss of Taste or Smell','&#129310; Nausea / Vomiting','&#128556; Sore Throat'] as $s): ?>
            <div class="d-flex align-items-center mb-2"><?= $s ?></div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card border-0 shadow rounded-4 h-100">
          <div class="card-header bg-danger text-white fw-bold rounded-top-4 py-3"><i class="fas fa-ambulance me-2"></i>Emergency Symptoms &mdash; Call 112</div>
          <div class="card-body">
            <div class="alert alert-danger"><strong>Seek emergency care immediately if you experience:</strong></div>
            <?php foreach(['Trouble Breathing / Gasping','Chest Pain or Pressure','Confusion or inability to stay awake','Bluish lips, face or fingertips','Very High Fever above 104°F','Severe Neurological Symptoms'] as $s): ?>
            <div class="d-flex align-items-center mb-2"><i class="fas fa-exclamation-circle text-danger me-2"></i><?= $s ?></div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="text-center mt-4"><a href="symptoms.php" class="btn btn-warning text-dark fw-semibold"><i class="fas fa-heartbeat me-2"></i>Learn More About Symptoms</a></div>
  </div>
</section>

<!-- RECENT COMMENTS -->
<?php if (!empty($recentComments)): ?>
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="section-heading">Community Discussion</h2>
    <div class="row g-4">
      <?php foreach ($recentComments as $c): ?>
      <div class="col-md-4"><div class="card border-0 shadow-sm h-100 comment-card"><div class="card-body p-4">
        <div class="d-flex align-items-center gap-2 mb-3">
          <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:40px;height:40px;flex-shrink:0;"><?= strtoupper(substr($c['username'],0,1)) ?></div>
          <div><strong><?= htmlspecialchars($c['full_name']) ?></strong><br><small class="text-muted"><?= date('d M Y',strtotime($c['created_at'])) ?></small></div>
        </div>
        <p class="text-muted small mb-0">"<?= htmlspecialchars(substr($c['comment'],0,120)) ?><?= strlen($c['comment'])>120?'...':'' ?>"</p>
      </div></div></div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4"><a href="comments.php" class="btn btn-outline-primary"><i class="fas fa-comments me-2"></i>Join the Discussion</a></div>
  </div>
</section>
<?php endif; ?>

<!-- CTA -->
<section class="py-5">
  <div class="container">
    <div class="cta-banner">
      <div style="font-size:4rem;" class="mb-3">&#128137;</div>
      <h2 class="fw-bold mb-3">Get Vaccinated Today!</h2>
      <p class="mb-4" style="color:rgba(255,255,255,.85);max-width:600px;margin:0 auto 1.5rem;">
        COVID-19 vaccines are safe, free, and the most effective protection available. Book your slot now.
      </p>
      <a href="vaccine_registration.php" class="btn btn-danger btn-lg px-5"><i class="fas fa-syringe me-2"></i>Register for Vaccine</a>
      <div class="mt-3"><small style="color:rgba(255,255,255,.6);">&#127968; 10,000+ Centers | &#9989; Free | &#128222; Helpline: 1075</small></div>
    </div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
