        </div><!-- /.container-fluid -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<footer class="main-footer text-center py-2" style="font-size:0.85rem;">
    <strong><?= APP_NAME ?></strong> &copy; <?= date('Y') ?> Admin Panel
</footer>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<?php if (isset($extraScripts)) echo $extraScripts; ?>
</div><!-- ./wrapper -->
</body>
</html>
