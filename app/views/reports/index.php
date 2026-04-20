<h3>Reports</h3>
<div class="row g-3">
  <div class="col-md-4"><div class="card"><div class="card-header">Tasks</div><ul class="list-group list-group-flush"><?php foreach($tasks as $t): ?><li class="list-group-item"><?= e($t['status']) ?>: <?= (int)$t['total'] ?></li><?php endforeach; ?></ul></div></div>
  <div class="col-md-4"><div class="card"><div class="card-header">Finance</div><ul class="list-group list-group-flush"><?php foreach($finance as $f): ?><li class="list-group-item"><?= e($f['type']) ?>: <?= (float)$f['amount'] ?></li><?php endforeach; ?></ul></div></div>
  <div class="col-md-4"><div class="card"><div class="card-header">Time Logs</div><ul class="list-group list-group-flush"><?php foreach($timeLogs as $l): ?><li class="list-group-item">User <?= (int)$l['user_id'] ?>: <?= (float)$l['hours'] ?>h</li><?php endforeach; ?></ul></div></div>
</div>
