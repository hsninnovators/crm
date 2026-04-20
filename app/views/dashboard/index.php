<h3>Dashboard</h3>
<div class="row g-3 mb-3">
<?php foreach($stats as $k=>$v): ?>
  <div class="col-md-2"><div class="card"><div class="card-body"><small><?= e(ucfirst($k)) ?></small><h4><?= (int)$v ?></h4></div></div></div>
<?php endforeach; ?>
</div>
<div class="row">
  <div class="col-md-6"><canvas id="financeChart"></canvas></div>
  <div class="col-md-6">
    <div class="card"><div class="card-header">Task Progress</div><div class="card-body">
      <?php foreach($recentTasks as $t): ?><div class="mb-2"><strong><?= e($t['title']) ?></strong>
      <div class="progress"><div class="progress-bar" style="width:<?= (int)$t['progress'] ?>%"><?= (int)$t['progress'] ?>%</div></div></div><?php endforeach; ?>
    </div></div>
  </div>
</div>
<script>
new Chart(document.getElementById('financeChart'), {type:'doughnut', data:{labels:['Income','Expense'], datasets:[{data:[<?= (float)($finance['income']??0) ?>,<?= (float)($finance['expense']??0) ?>]}]}});
</script>
