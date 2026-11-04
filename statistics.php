<?php
require_once 'includes/config.php';
$pageTitle = 'COVID-19 Statistics';
try {
    $allStats = $pdo->query("SELECT * FROM covid_stats ORDER BY total_cases DESC")->fetchAll();
} catch(Exception $e) { $allStats = []; }
$chartLabels = array_column($allStats,'country');
$chartTotal  = array_map('intval', array_column($allStats,'total_cases'));
$chartRecov  = array_map('intval', array_column($allStats,'recovered'));
$chartDeaths = array_map('intval', array_column($allStats,'deaths'));
$india = array_values(array_filter($allStats, fn($r) => $r['country']==='India'))[0] ?? null;
$weekLabels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
$weekNew    = [4521,3982,5102,4389,6021,5543,4890];
include 'includes/header.php';
?>
<div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold display-5">COVID-19 Statistics</h2>
        <p class="lead text-muted">Real-time COVID-19 data overview</p>
    </div>
    <!-- Table -->
    <div class="card border-0 shadow rounded-4 mb-5">
        <div class="card-header bg-dark text-white fw-bold rounded-top-4 py-3"><i class="fas fa-table me-2"></i>Country-wise Data</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 stats-table">
                    <thead><tr><th>Country</th><th class="text-primary">Total Cases</th><th class="text-warning">Active</th><th class="text-success">Recovered</th><th class="text-danger">Deaths</th><th style="color:#6f42c1">Vaccinated</th><th>Date</th></tr></thead>
                    <tbody>
                        <?php foreach($allStats as $row): ?>
                        <tr><td><strong><?= htmlspecialchars($row['country']) ?></strong></td>
                            <td class="text-primary fw-bold"><?= number_format($row['total_cases']) ?></td>
                            <td class="text-warning fw-bold"><?= number_format($row['active_cases']) ?></td>
                            <td class="text-success fw-bold"><?= number_format($row['recovered']) ?></td>
                            <td class="text-danger fw-bold"><?= number_format($row['deaths']) ?></td>
                            <td style="color:#6f42c1" class="fw-bold"><?= number_format($row['vaccinated']) ?></td>
                            <td class="text-muted small"><?= date('d M Y',strtotime($row['stat_date'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Charts -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8"><div class="card border-0 shadow rounded-4 h-100">
            <div class="card-header bg-primary text-white fw-bold rounded-top-4 py-3"><i class="fas fa-chart-bar me-2"></i>Cases Comparison</div>
            <div class="card-body"><canvas id="barChart" height="250"></canvas></div>
        </div></div>
        <div class="col-lg-4"><div class="card border-0 shadow rounded-4 h-100">
            <div class="card-header bg-warning text-dark fw-bold rounded-top-4 py-3"><i class="fas fa-chart-pie me-2"></i>India Distribution</div>
            <div class="card-body d-flex align-items-center justify-content-center"><canvas id="pieChart" height="220"></canvas></div>
        </div></div>
    </div>
    <div class="row g-4"><div class="col-12"><div class="card border-0 shadow rounded-4">
        <div class="card-header bg-success text-white fw-bold rounded-top-4 py-3"><i class="fas fa-chart-line me-2"></i>Weekly Trend (Demo)</div>
        <div class="card-body"><canvas id="lineChart" height="100"></canvas></div>
    </div></div></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const labels = <?= json_encode($chartLabels) ?>;
const total  = <?= json_encode($chartTotal)  ?>;
const recov  = <?= json_encode($chartRecov)  ?>;
const deaths = <?= json_encode($chartDeaths) ?>;
const wl = <?= json_encode($weekLabels) ?>;
const wd = <?= json_encode($weekNew)    ?>;

new Chart(document.getElementById('barChart'),{type:'bar',data:{labels,datasets:[
    {label:'Total Cases',data:total,backgroundColor:'rgba(13,110,253,.7)',borderColor:'#0d6efd',borderWidth:2},
    {label:'Recovered',  data:recov, backgroundColor:'rgba(25,135,84,.7)',  borderColor:'#198754', borderWidth:2},
    {label:'Deaths',     data:deaths,backgroundColor:'rgba(220,53,69,.7)',  borderColor:'#dc3545', borderWidth:2},
]},options:{responsive:true,plugins:{legend:{position:'top'}},scales:{y:{beginAtZero:true,ticks:{callback:v=>v>=1e6?(v/1e6).toFixed(1)+'M':v.toLocaleString()}}}}});

<?php if($india): ?>
new Chart(document.getElementById('pieChart'),{type:'doughnut',data:{
    labels:['Active','Recovered','Deaths'],
    datasets:[{data:[<?= intval($india['active_cases']) ?>,<?= intval($india['recovered']) ?>,<?= intval($india['deaths']) ?>],
    backgroundColor:['#fd7e14','#198754','#dc3545'],borderWidth:3,borderColor:'#fff'}]
},options:{responsive:true,plugins:{legend:{position:'bottom'}}}});
<?php endif; ?>

new Chart(document.getElementById('lineChart'),{type:'line',data:{labels:wl,datasets:[{
    label:'New Cases',data:wd,fill:true,backgroundColor:'rgba(25,135,84,.15)',
    borderColor:'#198754',borderWidth:3,tension:.4,pointRadius:6,pointBackgroundColor:'#198754'}]
},options:{responsive:true,scales:{y:{beginAtZero:true}}}});
</script>
<?php include 'includes/footer.php'; ?>
