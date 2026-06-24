<?php
// ── Site config ──────────────────────────────────────────
$site_name  = 'Logan Newell';
$site_tagline = 'Computer Science · Database Architecture · Backend Infrastructure';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($site_name) ?></title>

  <!-- Bootstrap 5 -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous"
  />

  <!-- Custom styles -->
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>

<!-- Cherry blossom canvas (fixed, behind everything) -->
<canvas id="blossom-canvas" aria-hidden="true"></canvas>

<!-- ═══════════════════════════════════════════════════════
     NAVIGATION
══════════════════════════════════════════════════════════ -->
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
      </ul>
    </div>
  </div>
</nav>


<!-- ═══════════════════════════════════════════════════════
     PAGE — RESUME
══════════════════════════════════════════════════════════ -->
<div id="page-resume">
  <div class="page-content">

    <!-- Header -->
    <div class="card-panel mb-4">
      <div class="resume-name">Logan Newell</div>
      <div class="resume-meta mt-2">
        Van Buren, AR 72956 &nbsp;·&nbsp;
        <a href="tel:4798069965">(479) 806-9965</a> &nbsp;·&nbsp;
        <a href="mailto:logan@thenewells.net">logan@thenewells.net</a> &nbsp;·&nbsp;
        <a href="mailto:lnewel00@uafs.edu">lnewel00@uafs.edu</a> &nbsp;·&nbsp;
        <a href="https://github.com/LNewell00" target="_blank" rel="noopener">github.com/LNewell00</a>
      </div>
      <p class="mt-3" style="font-size:.9rem;color:var(--muted);line-height:1.6;">
        Computer Science senior focused on database architecture and backend infrastructure.
        Gained the majority of my technical experience hands-on — either designing distributed
        systems for university projects or self-hosting containerized applications and databases
        in a personal home lab. AWS-certified and looking to apply this practical building
        experience to Data and AI internships.
      </p>
    </div>

    <!-- Certifications -->
    <div class="section-title">Certifications</div>
    <div class="section-divider"></div>
    <div class="card-panel">
      <div>
        <span class="cert-badge">
          <!-- AWS icon -->
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
          AWS — Cloud Practitioner Essentials
          <span style="color:var(--muted);font-weight:300;margin-left:.35rem;">May 22, 2026</span>
        </span>
      </div>
      <div class="mt-1">
        <span class="cert-badge">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
          AWS — Data Engineering on AWS - Foundations
          <span style="color:var(--muted);font-weight:300;margin-left:.35rem;">June 14, 2026</span>
        </span>
      </div>
    </div>

    <!-- Experience -->
    <div class="section-title mt-4">Experience</div>
    <div class="section-divider"></div>

    <div class="card-panel">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
        <div>
          <div class="job-title">Intern, Information Center</div>
          <div class="job-company">ArcBest — Fort Smith, Arkansas</div>
        </div>
        <div class="job-date">February 2024 – Present</div>
      </div>
      <ul class="styled mt-2">
        <li>Supported nationwide field operations and the 2024–2025 Navori rollout involving over 3,000 devices</li>
        <li>Provisioned SIM cards and deployed security cameras and servers for field use</li>
        <li>Resolved IT support tickets related to Navori digital media players, distribution lists, and Zebra devices</li>
        <li>Imaged and prepared desktops for deployment to MoLo and Field Support teams</li>
      </ul>
    </div>

    <div class="card-panel">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
        <div>
          <div class="job-title">Prep Cook / Line Cook / Banquet Server</div>
          <div class="job-company">The Keeter Center — Point Lookout, Missouri</div>
        </div>
        <div class="job-date">January 2022 – March 2023</div>
      </div>
      <ul class="styled mt-2">
        <li>Served as prep cook, line cook, and banquet server for a French-style catering operation</li>
        <li>One of three staff selected to host the Board of Commerce and various other high-profile occasions</li>
        <li>Assisted in setup and service for a wedding hosted at The Keeter Center</li>
        <li>Contributed to special dishes for events including Grape Fest, Fall Fest, and a senior culinary graduation party</li>
      </ul>
    </div>

    <!-- Education -->
    <div class="section-title mt-4">Education</div>
    <div class="section-divider"></div>

    <div class="card-panel">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
        <div>
          <div class="job-title">B.S. Computer Science</div>
          <div class="job-company">University of Arkansas – Fort Smith</div>
        </div>
        <div class="job-date">August 2023 – December 2026</div>
      </div>
      <p style="font-size:.82rem;color:var(--muted);margin-top:.5rem;">
        <strong style="color:var(--fg);">Relevant coursework:</strong>
        Data Structures &amp; Algorithms · Artificial Intelligence · Distributed Systems ·
        Information Retrieval · Software Engineering · Database Systems · Operating Systems ·
        Ethics &amp; Professional Practice · Discrete Mathematics II · Cyber Operations
      </p>
      <div class="mt-2">
        <span class="tag">Dean's List — Spring &amp; Fall 2025</span>
        <span class="tag">Chancellor's List — Spring 2024</span>
      </div>
    </div>

    <div class="card-panel">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
        <div>
          <div class="job-title">General Studies</div>
          <div class="job-company">University of Arkansas – Fort Smith</div>
        </div>
        <div class="job-date">August 2023 – December 2024</div>
      </div>
    </div>

    <!-- Skills -->
    <div class="section-title mt-4">Skills</div>
    <div class="section-divider"></div>

    <div class="card-panel">
      <div class="row g-3">
        <div class="col-sm-6">
          <div style="font-size:.75rem;font-weight:500;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;margin-bottom:.5rem;">Languages</div>
          <div><span class="tag">Java</span><span class="tag">PHP</span><span class="tag">SQL</span></div>
        </div>
        <div class="col-sm-6">
          <div style="font-size:.75rem;font-weight:500;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;margin-bottom:.5rem;">Databases</div>
          <div><span class="tag">MariaDB</span><span class="tag">PostgreSQL</span><span class="tag">MongoDB</span></div>
        </div>
        <div class="col-sm-6">
          <div style="font-size:.75rem;font-weight:500;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;margin-bottom:.5rem;">Tools &amp; Platforms</div>
          <div><span class="tag">Docker</span><span class="tag">Linux</span><span class="tag">Redis</span><span class="tag">phpMyAdmin</span><span class="tag">Hyper-V</span><span class="tag">IntelliJ</span><span class="tag">VS Code</span></div>
        </div>
        <div class="col-sm-6">
          <div style="font-size:.75rem;font-weight:500;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;margin-bottom:.5rem;">Soft Skills</div>
          <div><span class="tag">Office 365</span><span class="tag">Communication</span><span class="tag">Analytical Thinking</span><span class="tag">Collaboration</span><span class="tag">Adaptability</span></div>
        </div>
      </div>
    </div>

  </div><!-- /page-content -->
</div><!-- /page-resume -->


<!-- ═══════════════════════════════════════════════════════
     PAGE — PROJECTS
══════════════════════════════════════════════════════════ -->
<div id="page-projects" style="display:none;">
  <div class="page-content">

    <div class="section-title">Projects</div>
    <div class="section-divider"></div>

    <!-- Home Lab -->
    <div class="card-panel">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
        <div class="job-title">Home Lab</div>
        <div><span class="tag">Self-Hosted</span><span class="tag">Docker</span><span class="tag">PostgreSQL</span><span class="tag">Cloudflare</span></div>
      </div>
      <p style="font-size:.88rem;margin-top:.75rem;line-height:1.65;">
        Designed and deployed a personal web infrastructure using Cloudflare tunnels, Docker containers,
        and PostgreSQL. The Cloudflare tunnel handles reverse proxying without exposing the home network —
        no port forwarding required. All task data is persisted in PostgreSQL running inside a Docker container.
      </p>
      <ul class="styled mt-1">
        <li>Self-hosting AI models via <strong>Odysseus</strong> and <strong>Ollama</strong></li>
        <li>Reverse proxying and access controls securing services from external and internal traffic</li>
        <li>Multiple self-managed services: media server, file sharing, photo storage</li>
      </ul>
      <div class="mt-2">
        <a class="project-link" href="https://odysseus.lnewell.work" target="_blank" rel="noopener">↗ AI Models</a>
        <a class="project-link" href="https://lnewell.work/task-game/index.php" target="_blank" rel="noopener">↗ Chore List</a>
        <a class="project-link" href="https://grocy.lnewell.work" target="_blank" rel="noopener">↗ Pantry Manager</a>
        <a class="project-link" href="https://code.lnewell.work" target="_blank" rel="noopener">↗ Code Server</a>
        <a class="project-link" href="https://uptime-kuma.lnewell.work" target="_blank" rel="noopener">↗ Uptime Kuma</a>
        <a class="project-link" href="https://vaultwarden.lnewell.work" target="_blank" rel="noopener">↗ Vaultwarden</a>
      </div>
      <p style="font-size:.78rem;color:var(--muted);margin-top:.6rem;">
        Demo credentials for Odysseus — Username: <code>demo_user</code>
      </p>
    </div>

    <!-- TF-IDF / BM25 -->
    <div class="card-panel">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
        <div class="job-title">TF-IDF / BM25 Tokenizer</div>
        <div><span class="tag">Java</span><span class="tag">NLP</span><span class="tag">Information Retrieval</span><span class="tag">Team Project</span></div>
      </div>
      <p style="font-size:.88rem;margin-top:.75rem;line-height:1.65;">
        Implemented a tokenizer and TF-IDF scoring system for text analysis built on Java
        and Common Crawl datasets, demonstrating understanding of information retrieval and NLP
        feature extraction. BM25 ranking was integrated to improve document relevance scoring,
        alongside a query system for document retrieval.
      </p>
      <ul class="styled mt-1">
        <li>Tokenizer and TF-IDF scoring pipeline over large-scale Common Crawl data</li>
        <li>BM25 ranking integration for improved relevance</li>
        <li>Query interface for document retrieval</li>
      </ul>
    </div>

    <!-- MongoDB Sharded Cluster -->
    <div class="card-panel">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
        <div class="job-title">MongoDB Sharded Cluster</div>
        <div><span class="tag">MongoDB</span><span class="tag">openSUSE</span><span class="tag">Distributed Systems</span><span class="tag">Team Project</span></div>
      </div>
      <p style="font-size:.88rem;margin-top:.75rem;line-height:1.65;">
        Designed and deployed a distributed MongoDB cluster using sharding across four virtual machines
        connected via NAT network. Each VM hosted one shard and one config container; one VM was dedicated
        as the Mongo router. Demonstrates horizontal scaling and distributed database architecture.
      </p>
      <ul class="styled mt-1">
        <li>4-VM NAT network with shard + config container per node</li>
        <li>Dedicated Mongo router VM</li>
        <li>Horizontal scaling via sharding strategy</li>
      </ul>
    </div>

    <!-- Volunteer Packing Database -->
    <div class="card-panel">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
        <div class="job-title">Volunteer Packing Database App</div>
        <div><span class="tag">Java</span><span class="tag">SQL</span><span class="tag">MySQL</span></div>
      </div>
      <p style="font-size:.88rem;margin-top:.75rem;line-height:1.65;">
        Developed a web application using Java NetBeans and a SQL-backed MySQL database.
        The GUI was built in NetBeans with full CRUD operations and backend data handling
        for managing volunteer packing records.
      </p>
      <ul class="styled mt-1">
        <li>Java NetBeans GUI frontend</li>
        <li>MySQL database with full CRUD operations</li>
        <li>Backend data handling for volunteer record management</li>
      </ul>
    </div>

  </div>
</div><!-- /page-projects -->


<!-- ═══════════════════════════════════════════════════════
     PAGE — TRANSCRIPT
══════════════════════════════════════════════════════════ -->
<div id="page-transcript" style="display:none;">
  <div class="page-content">

    <div class="section-title">Transcript</div>
    <div class="section-divider"></div>

    <div class="transcript-container" style="margin-bottom: 1.5rem;">
      <iframe 
        src="pages/transcript.pdf#navpanes=0" 
        width="100%" 
        height="650px" 
        style="border: 1px solid rgba(0,0,0,0.1); border-radius: 8px; display: block;"
        >
        </iframe>
    </div>

    <div style="text-align: center; margin-bottom: 2rem;">
      <a href="pages/transcript.pdf" target="_blank" style="display: inline-block; padding: 0.5rem 1rem; background: var(--muted); color: #fff; text-decoration: none; border-radius: 4px; font-size: 0.85rem; font-weight: 500;">
        Open Transcript in New Tab ↗
      </a>
    </div>

    <div class="card-panel mt-3">
      <div style="font-size:.82rem;font-weight:500;color:var(--muted);letter-spacing:.07em;text-transform:uppercase;margin-bottom:.75rem;">Completed / In-Progress Coursework</div>
      <div>
        <span class="tag">Data Structures &amp; Algorithms</span>
        <span class="tag">Artificial Intelligence</span>
        <span class="tag">Distributed Systems</span>
        <span class="tag">Information Retrieval</span>
        <span class="tag">Software Engineering</span>
        <span class="tag">Database Systems</span>
        <span class="tag">Operating Systems</span>
        <span class="tag">Ethics &amp; Professional Practice</span>
        <span class="tag">Discrete Mathematics II</span>
        <span class="tag">Cyber Operations</span>
      </div>
      <p style="font-size:.8rem;color:var(--muted);margin-top:1rem;">
        Dean's List — Spring &amp; Fall 2025 &nbsp;·&nbsp; Chancellor's List — Spring 2024
      </p>
    </div>

  </div>
</div><!-- /page-transcript -->


<!-- ═══════════════════════════════════════════════════════
     PAGE — ABOUT
══════════════════════════════════════════════════════════ -->
<div id="page-about" style="display:none;">
  <div class="page-content">

    <div class="section-title">About Me</div>
    <div class="section-divider"></div>

    <div class="card-panel d-flex gap-4 flex-wrap align-items-start">
      <div class="about-avatar" aria-hidden="true">LN</div>
      <div style="flex:1;min-width:220px;">
        <p style="font-size:.95rem;line-height:1.75;color:var(--fg);">
          Hey, I'm Logan — a Computer Science senior at the University of Arkansas – Fort Smith,
          with a focus on database architecture and backend infrastructure. I learn best by building:
          whether that's spinning up a distributed MongoDB cluster for a class project or running a
          self-hosted home lab with Docker, PostgreSQL, and Cloudflare tunnels in my spare time.
        </p>
        <p style="font-size:.95rem;line-height:1.75;color:var(--fg);margin-top:.9rem;">
          I hold two AWS certifications — Cloud Practitioner Essentials and Data Engineering on AWS
          Foundations — and I'm actively looking for Data and AI internships where I can apply this
          hands-on infrastructure experience to real problems.
        </p>
        <p style="font-size:.95rem;line-height:1.75;color:var(--fg);margin-top:.9rem;">
          Outside of tech, I spent time as a prep and line cook at The Keeter Center — which taught
          me that precision, collaboration under pressure, and adapting on the fly translate well to
          pretty much any environment.
        </p>
      </div>
    </div>

    <div class="card-panel mt-3">
      <div style="font-size:.75rem;font-weight:500;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;margin-bottom:.75rem;">Contact</div>
      <div style="font-size:.9rem;line-height:2;">
        📧 <a href="mailto:logan@thenewells.net" style="color:var(--purple);text-decoration:none;">logan@thenewells.net</a><br>
        📧 <a href="mailto:lnewel00@uafs.edu" style="color:var(--purple);text-decoration:none;">lnewel00@uafs.edu</a><br>
        📱 <a href="tel:4798069965" style="color:var(--purple);text-decoration:none;">(479) 806-9965</a><br>
        🐙 <a href="https://github.com/LNewell00" target="_blank" rel="noopener" style="color:var(--purple);text-decoration:none;">github.com/LNewell00</a><br>
        🌐 <a href="https://lnewell.work" target="_blank" rel="noopener" style="color:var(--purple);text-decoration:none;">lnewell.work</a>
      </div>
    </div>

  </div>
</div><!-- /page-about -->


<!-- ── Footer ──────────────────────────────────────────── -->
<footer>
  <?= htmlspecialchars($site_name) ?> · <?= date('Y') ?>
</footer>


<!-- Bootstrap JS -->
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmVSQ5VKQqPHxNfTViDsEPXiD7U"
  crossorigin="anonymous"
></script>

<!-- Site scripts -->
<script src="assets/js/blossoms.js"></script>
<script src="assets/js/nav.js"></script>

</body>
</html>