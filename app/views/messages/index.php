<h3>Messaging</h3>
<div class="row">
  <div class="col-md-8">
    <div class="card"><div class="card-body" style="height:420px;overflow:auto;" id="chatBox">
      <?php foreach(array_reverse($rows) as $m): ?>
        <div class="mb-2"><strong>#<?= (int)$m['sender_id'] ?></strong>: <?= e($m['message']) ?> <small class="text-muted"><?= e($m['created_at']) ?></small></div>
      <?php endforeach; ?>
    </div></div>
  </div>
  <div class="col-md-4">
    <form method="post" action="/messages/send" class="card card-body">
      <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
      <label>Receiver ID</label><input class="form-control mb-2" name="receiver_id" value="1">
      <label>Group message?</label><select class="form-control mb-2" name="is_group"><option value="0">No</option><option value="1">Yes</option></select>
      <textarea class="form-control mb-2" name="message" required></textarea>
      <button class="btn btn-primary">Send</button>
    </form>
  </div>
</div>
