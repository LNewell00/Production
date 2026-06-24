/**
 * Single-page navigation — shows/hides sections without reloading.
 * URL hash (#resume, #projects, #transcript, #about, #documents) drives active state.
 */
(function () {
  // 1. Array defined as lowercase 'pages'
  const pages = ['resume', 'projects', 'transcript', 'about', 'documents'];

  function showPage(id) {
    // 2. FIXED: Changed from PAGES to pages
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
  // 3. FIXED: Changed from PAGES to pages
  showPage(pages.includes(hash) ? hash : 'resume');

  window.addEventListener('popstate', () => {
    const h = location.hash.replace('#', '');
    // 4. FIXED: Changed from PAGES to pages
    showPage(pages.includes(h) ? h : 'resume');
  });
})();