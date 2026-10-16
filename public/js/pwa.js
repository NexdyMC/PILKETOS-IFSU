/**
 * PWA Client Script: Service Worker Registration & Install Prompt Handler
 */

let deferredInstallPrompt = null;

// Register Service Worker
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker
      .register('/sw.js')
      .then((registration) => {
        console.log('[PWA] Service Worker registered with scope:', registration.scope);
      })
      .catch((err) => {
        console.warn('[PWA] Service Worker registration failed:', err);
      });
  });
}

// Listen for beforeinstallprompt event (Android / Desktop Chrome)
window.addEventListener('beforeinstallprompt', (e) => {
  // Prevent default mini-infobar on mobile
  e.preventDefault();
  deferredInstallPrompt = e;
  console.log('[PWA] beforeinstallprompt event captured');

  // Show custom install prompt banner if exists or create one
  showInstallBanner();

  // If any button with data-pwa-install or id="btn-install-pwa" exists, show it
  document.querySelectorAll('[data-pwa-install], #btn-install-pwa').forEach((btn) => {
    btn.style.display = 'inline-flex';
    btn.addEventListener('click', promptPwaInstall);
  });
});

// Function to trigger native installation dialog
window.promptPwaInstall = async function () {
  if (!deferredInstallPrompt) {
    console.log('[PWA] Install prompt not available or already installed');
    return;
  }

  deferredInstallPrompt.prompt();
  const { outcome } = await deferredInstallPrompt.userChoice;
  console.log(`[PWA] User response to install prompt: ${outcome}`);

  if (outcome === 'accepted') {
    hideInstallBanner();
  }
  deferredInstallPrompt = null;
};

// Listen for appinstalled event
window.addEventListener('appinstalled', () => {
  console.log('[PWA] Aplikasi Pilketos berhasil diinstal!');
  hideInstallBanner();
  deferredInstallPrompt = null;
});

// UI Banner Helper
function showInstallBanner() {
  if (sessionStorage.getItem('pwa_banner_dismissed')) return;
  if (document.getElementById('pwa-install-banner')) return;

  const banner = document.createElement('div');
  banner.id = 'pwa-install-banner';
  banner.className = 'fixed bottom-4 left-4 right-4 sm:left-auto sm:right-6 sm:w-96 z-50 bg-slate-900/95 backdrop-blur-md text-white p-4 rounded-2xl shadow-2xl border border-slate-700/80 flex items-center justify-between gap-3 transition-all transform duration-300 animate-slide-up';
  
  banner.innerHTML = `
    <div class="flex items-center gap-3 min-w-0">
      <img src="/icons/icon-96x96.png" alt="Icon Pilketos" class="w-12 h-12 rounded-xl object-contain bg-slate-800 p-1 shrink-0 border border-slate-700">
      <div class="truncate">
        <h4 class="font-bold text-sm text-white">Install Aplikasi</h4>
        <p class="text-xs text-slate-300 truncate">Pasang E-Voting di HP untuk akses lebih cepat</p>
      </div>
    </div>
    <div class="flex items-center gap-2 shrink-0">
      <button onclick="promptPwaInstall()" class="px-3.5 py-1.5 bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-bold text-xs rounded-xl transition-all shadow-md active:scale-95">
        Install
      </button>
      <button onclick="dismissInstallBanner()" class="p-1.5 text-slate-400 hover:text-white rounded-lg transition-colors" title="Tutup">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
  `;

  document.body.appendChild(banner);
}

function hideInstallBanner() {
  const banner = document.getElementById('pwa-install-banner');
  if (banner) {
    banner.remove();
  }
}

window.dismissInstallBanner = function() {
  hideInstallBanner();
  sessionStorage.setItem('pwa_banner_dismissed', 'true');
};
