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
</div>