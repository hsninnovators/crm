<?php $footerText = app_setting($this->pdo, 'footer_text', 'Powered by White Label CRM'); ?>
    </main>
  </div>
</div>
<footer class="text-center p-3 small border-top"><?= e($footerText) ?></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>window.APP_BASE_PATH = '<?= e(app_base_path()) ?>';</script>
<script src="<?= e(app_url('/assets/js/app.js')) ?>"></script>
</body>
</html>
