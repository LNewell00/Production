<?php
// ── Site config ──────────────────────────────────────────
$site_name  = 'Logan Newell';
$site_tagline = 'Computer Science · Database Architecture · Backend Infrastructure';
$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($site_name) ?></title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous"
  >

  <!-- Custom styles -->
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>

<!-- Cherry blossom canvas (fixed, behind everything) -->
<canvas id="blossom-canvas" aria-hidden="true"></canvas>

<!-- ═══════════════════════════════════════════════════════
     NAVIGATION
══════════════════════════════════════════════════════════ -->
<?php include 'assets/php/nav.php'; ?>

<!-- ═══════════════════════════════════════════════════════
     PAGE — RESUME
══════════════════════════════════════════════════════════ -->
<?php include 'pages/resume.php'; ?>
<!-- /page-resume -->

<!-- ═══════════════════════════════════════════════════════
     PAGE — PROJECTS
══════════════════════════════════════════════════════════ -->
<?php include 'pages/projects.php'; ?>
<!-- /page-projects -->

<!-- ═══════════════════════════════════════════════════════
     PAGE — TRANSCRIPT
══════════════════════════════════════════════════════════ -->
<?php include 'pages/transcript.php'; ?>
<!-- /page-transcript -->

<!-- ═══════════════════════════════════════════════════════
     PAGE — ABOUT
══════════════════════════════════════════════════════════ -->
<?php include 'pages/about.php'; ?>
<!-- /page-about -->

<!-- ═══════════════════════════════════════════════════════
     PAGE — DOCUMENTS
══════════════════════════════════════════════════════════ -->
<?php include 'pages/documents.php'; ?>
<!-- /page-document -->

<!-- ── Footer ──────────────────────────────────────────── -->
<footer>
  <?= htmlspecialchars($site_name) ?> · <?= date('Y') ?>
</footer>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous">
</script>

<!-- Site scripts -->
<script src="assets/js/blossoms.js"></script>
<script src="assets/js/nav.js?v=2"></script>

</body>
</html>