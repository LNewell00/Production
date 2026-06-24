/**
 * Single-page navigation — shows/hides sections without reloading.
 * URL hash (#resume, #projects, #transcript, #about, #documents) drives active state.
 */
(function () {
  const pages = ['resume', 'projects', 'transcript', 'about', 'documents'];

  function showPage(id) {
    pages.forEach(p => {
      const el = document.getElementById('page-' + p);
      if (el) el.style.display = p === id ? 'block' : 'none';
    });
    document.querySelectorAll('.nav-link[data-page]').forEach(a => {
      a.classList.toggle('active', a.dataset.page === id);
    });
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  document.querySelectorAll('.nav-link[data-page]').forEach(a => {
    a.addEventListener('click', e => {
      e.preventDefault();
      const page = a.dataset.page;
      history.pushState(null, '', '#' + page);
      showPage(page);
      
      /* collapse mobile navbar */
      const toggler = document.querySelector('.navbar-collapse');
      if (toggler && toggler.classList.contains('show')) {
        toggler.classList.remove('show');
      }
    });
  });

  /* honour hash on load */
  const hash = location.hash.replace('#', '');
  showPage(pages.includes(hash) ? hash : 'resume');

  window.addEventListener('popstate', () => {
    const h = location.hash.replace('#', '');
    showPage(pages.includes(h) ? h : 'resume');
  });

  /* ── Cleaned Dark Mode Cookie Toggle ── */
  const darkToggle = document.getElementById('dark-toggle');
  const htmlEl = document.documentElement;

  if (darkToggle) {
    darkToggle.addEventListener('click', () => {
      // 1. Determine next theme state based on Bootstrap data attribute
      const currentTheme = htmlEl.getAttribute('data-bs-theme') || 'light';
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

      // 2. Set client side updates instantly
      htmlEl.setAttribute('data-bs-theme', newTheme);
      darkToggle.innerHTML = newTheme === 'dark' ? '☀️' : '🌙';

      // 3. Write value to document cookie
      const maxAge = 365 * 24 * 60 * 60; 
      document.cookie = `theme=${newTheme}; max-age=${maxAge}; path=/; SameSite=Lax`;
    });
  }
})();