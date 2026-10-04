/**
 * sidebar.js — sidebar mobile + navigasi AJAX (SPA) untuk panel admin.
 * Lokasi: public/assets/js/sidebar.js
 * Dimuat di layout/admin/app.blade.php (sebelum </body>).
 */

lucide.createIcons();
AOS.init({ duration: 550, once: true, offset: 40 });

// Buka/tutup sidebar mobile
function toggleSidebar(open) {
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');
  if (!sidebar || !overlay) return;
  if (open) {
    sidebar.classList.add('open');
    overlay.classList.remove('hidden');
  } else {
    sidebar.classList.remove('open');
    overlay.classList.add('hidden');
  }
}

// Skeleton loading (efek pulse) selagi konten baru diambil
function loadingSkeleton() {
  return `
    <div class="p-4 space-y-6 sm:p-8 animate-pulse">
      <div class="w-56 h-6 rounded-lg bg-slate-200"></div>
      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="h-28 bg-slate-200 rounded-2xl"></div>
        <div class="h-28 bg-slate-200 rounded-2xl"></div>
        <div class="h-28 bg-slate-200 rounded-2xl"></div>
        <div class="h-28 bg-slate-200 rounded-2xl"></div>
      </div>
      <div class="h-64 bg-slate-200 rounded-3xl"></div>
    </div>`;
}

function escapeHtml(str) {
  const div = document.createElement('div');
  div.textContent = str;
  return div.innerHTML;
}

function updateTopbar(title, breadcrumbArr) {
  const titleEl = document.getElementById('pageTitleText');
  const crumbEl = document.getElementById('breadcrumbText');
  if (titleEl && title) titleEl.textContent = title;
  if (crumbEl && Array.isArray(breadcrumbArr) && breadcrumbArr.length) {
    crumbEl.innerHTML = breadcrumbArr.map(function (c, i) {
      const sep = i > 0 ? '<i data-lucide="chevron-right" class="w-3 h-3 shrink-0"></i>' : '';
      const cls = i === breadcrumbArr.length - 1 ? 'text-primary-600 font-medium' : '';
      return sep + '<span class="truncate ' + cls + '">' + escapeHtml(c) + '</span>';
    }).join('');
    lucide.createIcons();
  }
}

// Bandingkan berdasarkan pathname supaya URL absolut (route()) tetap cocok
function samePath(a, b) {
  try {
    return new URL(a, window.location.origin).pathname === new URL(b, window.location.origin).pathname;
  } catch (e) {
    return a === b;
  }
}

function setActiveLink(url) {
  document.querySelectorAll('.ajax-link').forEach(function (link) {
    const isActive = samePath(link.getAttribute('href'), url);
    link.classList.toggle('active', isActive);
    link.classList.toggle('text-slate-300', !isActive);
  });
}

// <script> hasil innerHTML tidak dieksekusi otomatis, jadi dibuat ulang
function executeInlineScripts(container) {
  container.querySelectorAll('script').forEach(function (oldScript) {
    const newScript = document.createElement('script');
    Array.from(oldScript.attributes).forEach(function (attr) {
      newScript.setAttribute(attr.name, attr.value);
    });
    newScript.textContent = oldScript.textContent;
    oldScript.replaceWith(newScript);
  });
}

async function loadContent(url, push, title, breadcrumb) {
  const main = document.getElementById('main-content');
  if (!main) return;
  main.innerHTML = loadingSkeleton();

  try {
    const sep = url.indexOf('?') !== -1 ? '&' : '?';
    const res = await fetch(url + sep + 'ajax=1', {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    if (!res.ok) throw new Error('HTTP ' + res.status);
    const html = await res.text();

    main.innerHTML = html;
    executeInlineScripts(main);
    lucide.createIcons();
    AOS.refresh();
    updateTopbar(title, breadcrumb);
    setActiveLink(url);

    if (push) {
      window.history.pushState({ url: url, title: title, breadcrumb: breadcrumb }, '', url);
    }
    window.scrollTo({ top: 0, behavior: 'instant' });
  } catch (err) {
    main.innerHTML = '<div class="p-8 text-sm text-center text-red-600">Gagal memuat halaman. Silakan coba lagi.</div>';
    console.error('SPA load error:', err);
  }
}

document.addEventListener('click', function (e) {
  const link = e.target.closest('.ajax-link');
  if (!link) return;
  e.preventDefault();

  const url = link.getAttribute('href');
  const title = link.dataset.title || '';
  const breadcrumb = (link.dataset.breadcrumb || '')
    .split(',')
    .map(function (s) { return s.trim(); })
    .filter(Boolean);

  loadContent(url, true, title, breadcrumb);
  toggleSidebar(false);
});

window.addEventListener('popstate', function (e) {
  const state = e.state;
  const url = (state && state.url) ? state.url : window.location.pathname + window.location.search;
  const title = (state && state.title) ? state.title : '';
  const breadcrumb = (state && state.breadcrumb) ? state.breadcrumb : [];
  loadContent(url, false, title, breadcrumb);
});

// State awal halaman (dibaca dari atribut data di <body>, diisi oleh layout)
(function () {
  const body = document.body;
  window.history.replaceState(
    {
      url: window.location.pathname + window.location.search,
      title: body.dataset.pageTitle || document.title,
      breadcrumb: (body.dataset.pageBreadcrumb || '')
        .split(',')
        .map(function (s) { return s.trim(); })
        .filter(Boolean)
    },
    '',
    window.location.href
  );
})();