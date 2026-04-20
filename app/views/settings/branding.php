<h3>White-Label Brand Settings</h3>
<form method="post" action="<?= e(app_url('/settings/branding')) ?>" enctype="multipart/form-data" class="card card-body">
  <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
  <div class="row g-2">
    <div class="col-md-6"><label>Company Name</label><input class="form-control" name="company_name" value="<?= e(app_setting($this->pdo,'company_name','')) ?>"></div>
    <div class="col-md-6"><label>Email Brand Name</label><input class="form-control" name="email_brand_name" value="<?= e(app_setting($this->pdo,'email_brand_name','')) ?>"></div>
    <div class="col-md-6"><label>Header Text</label><input class="form-control" name="header_text" value="<?= e(app_setting($this->pdo,'header_text','')) ?>"></div>
    <div class="col-md-6"><label>Footer Text</label><input class="form-control" name="footer_text" value="<?= e(app_setting($this->pdo,'footer_text','')) ?>"></div>
    <div class="col-md-3"><label>Logo</label><input type="file" class="form-control" name="logo"></div>
    <div class="col-md-3"><label>Favicon</label><input type="file" class="form-control" name="favicon"></div>
    <div class="col-md-3"><label>Login Background</label><input type="file" class="form-control" name="login_background"></div>
    <div class="col-md-3"><label>Email Logo</label><input type="file" class="form-control" name="email_logo"></div>
  </div>
  <button class="btn btn-primary mt-3">Save Branding</button>
</form>
