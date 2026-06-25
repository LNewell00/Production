<?php
// ── Site config ──────────────────────────────────────────
$site_name  = 'Logan Newell';
$site_tagline = 'Computer Science · Database Architecture · Backend Infrastructure';
$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';

$host = getenv('DB_HOST') ?: 'postgres';
$name = getenv('DB_NAME') ?: 'postgres';
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

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
<script>
// 1. Maintain or assign an immutable session token for this browser tab
if (!sessionStorage.getItem('site_session_id')) {
    sessionStorage.setItem('site_session_id', crypto.randomUUID());
}
const sessionID = sessionStorage.getItem('site_session_id');

// 2. Base payload submission engine
function logEvent(eventType, targetName) {
    const data = new URLSearchParams({
        session_id: sessionID,
        event_type: eventType,
        target_name: targetName,
        referrer: document.referrer,
        screen_w: window.innerWidth,
        screen_h: window.innerHeight
    });

    fetch('assets/php/log_visit.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: data.toString()
    }).catch(err => console.error("Tracking connection dropped or blocked", err));
}

// 3. Track entry milestone
document.addEventListener("DOMContentLoaded", () => {
    logEvent('page_view', 'index');
});

// 4. Track intentional navigation clicks 
document.querySelectorAll('.nav-link, #nav-menu a').forEach(link => {
    link.addEventListener('click', () => {
        const destination = link.getAttribute('href') || link.innerText;
        logEvent('nav_click', destination.replace('#', ''));
    });
});

// 5. Track file downloads targeting local PDFs
document.querySelectorAll('a[href*=".pdf"]').forEach(pdfLink => {
    pdfLink.addEventListener('click', () => {
        const fileUrl = pdfLink.getAttribute('href');
        const fileName = fileUrl.substring(fileUrl.lastIndexOf('/') + 1);
        logEvent('pdf_download', fileName);
    });
});
</script>



</body>
</html>