<?php
$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
?>
<nav class="navbar navbar-expand-md">
  <div class="container-fluid px-3">
    <a class="navbar-brand" href="#resume"><?= htmlspecialchars($site_name) ?></a>

    <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#mainNav"
      aria-controls="mainNav"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto gap-1">
        <li class="nav-item">
          <a class="nav-link" data-page="resume"     href="#resume">     Resume</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-page="projects"   href="#projects">   Projects</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-page="transcript" href="#transcript"> Transcript</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-page="about"      href="#about">      About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-page="documents" href="#documents">Documents</a>
        </li>
        <li class="nav-item">
          <button id="dark-toggle" class="nav-link border-0 bg-transparent" aria-label="Toggle dark mode">
            <?= $theme === 'dark' ? '☀️' : '🌙' ?>
          </button>
        </li>
      </ul>
    </div>
  </div>
</nav>