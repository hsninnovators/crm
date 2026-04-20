<h3>Global Search Results</h3>
<?php foreach($results as $table => $rows): ?>
<div class="card mb-3"><div class="card-header text-capitalize"><?= e($table) ?></div>
  <ul class="list-group list-group-flush">
  <?php foreach($rows as $r): ?><li class="list-group-item">#<?= (int)$r['id'] ?> - <?= e($r['label']) ?></li><?php endforeach; ?>
  </ul>
</div>
<?php endforeach; ?>
