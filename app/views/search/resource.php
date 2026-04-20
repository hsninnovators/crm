<h3 class="mb-3 text-capitalize"><?= e(str_replace('_',' ', $table)) ?></h3>
<div class="card">
  <div class="card-body">
    <form class="row g-2 mb-3" method="get">
      <div class="col-md-4"><input class="form-control" name="q" value="<?= e($search) ?>" placeholder="Filter"></div>
      <div class="col-md-2"><button class="btn btn-secondary">Search</button></div>
    </form>
    <div class="table-responsive">
      <table class="table table-striped table-sm"><thead><tr><th>ID</th><th>Data</th></tr></thead><tbody>
      <?php foreach($rows as $r): ?><tr><td><?= (int)$r['id'] ?></td><td><?= e(json_encode($r)) ?></td></tr><?php endforeach; ?>
      </tbody></table>
    </div>
    <div class="d-flex gap-2">
      <?php if($page>1): ?><a class="btn btn-outline-secondary btn-sm" href="?page=<?= $page-1 ?>&q=<?= urlencode($search) ?>">Prev</a><?php endif; ?>
      <a class="btn btn-outline-secondary btn-sm" href="?page=<?= $page+1 ?>&q=<?= urlencode($search) ?>">Next</a>
    </div>
  </div>
</div>
<?php if ($table === 'leads'): ?>
<form method="post" action="/leads/convert" class="mt-3">
  <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
  <input name="lead_id" class="form-control mb-2" placeholder="Lead ID">
  <button class="btn btn-primary">Convert Lead to Customer</button>
</form>
<?php endif; ?>
<?php if ($table === 'attendance'): ?>
<div class="mt-3 d-flex gap-2">
<form method="post" action="/attendance/clock"><input type="hidden" name="action" value="in"><button class="btn btn-success">Clock In</button></form>
<form method="post" action="/attendance/clock"><input type="hidden" name="action" value="out"><button class="btn btn-danger">Clock Out</button></form>
</div>
<?php endif; ?>
