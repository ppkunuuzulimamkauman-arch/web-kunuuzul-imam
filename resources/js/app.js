import './bootstrap';
import '@hotwired/turbo';

// Navigasi persisten: Turbo Drive mengganti <body> tanpa reload dokumen,
// jadi CSS/JS tidak dimuat ulang dan navbar tidak berkedip.
// Elemen #mainnav dipertahankan via data-turbo-permanent di layout.

// ---- Dropdown navbar (delegasi global, aman dipanggil berulang) ----
function closeMainDrops() {
  document.querySelectorAll('.maindrop').forEach((d) => d.classList.add('hidden'));
}
window.toggleMainDrop = function (id, e) {
  if (e) e.stopPropagation();
  document.querySelectorAll('.maindrop').forEach((d) => {
    if (d.id !== id) d.classList.add('hidden');
  });
  document.getElementById(id)?.classList.toggle('hidden');
};

if (!window.__navDelegated) {
  window.__navDelegated = true;
  document.addEventListener('click', (e) => {
    if (!e.target.closest?.('#mainnav')) closeMainDrops();
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeMainDrops();
      mnavUncheck();
    }
  });
}

// ---- Drawer HP: toggle murni CSS (checkbox); JS hanya sinkron penanda aktif ----
function mnavUncheck() {
  const c = document.getElementById('mnavToggle');
  if (c) c.checked = false;
}
function syncMnavActive() {
  const list = document.querySelector('.mnav-list');
  if (!list) return;
  const items = [...list.querySelectorAll('li')].filter((li) => li.querySelector(':scope > a'));
  const curFull = window.location.href.split('#')[0];
  const curPath = window.location.pathname;
  let best = null;
  let bestPath = null;
  items.forEach((li) => {
    li.classList.remove('active');
    const a = li.querySelector(':scope > a');
    if (a.href.split('#')[0] === curFull) {
      best = li;
      return;
    }
    try {
      if (!bestPath && new URL(a.href).pathname === curPath) bestPath = li;
    } catch (_) {}
  });
  const chosen = best || bestPath || items[0];
  chosen?.classList.add('active');
  // Tandai grup dropdown yang berisi link aktif + buka details-nya
  list.querySelectorAll('.mnav-group').forEach((g) => {
    const hasActive = !!g.querySelector('li.active');
    g.classList.toggle('has-active', hasActive);
    const det = g.querySelector('details');
    if (det && hasActive) det.open = true;
  });
}

// ---- ToTop + penanda menu aktif ----
// Catatan anti-kedip: navbar selalu solid putih (nav-solid dikunci di HTML),
// JANGAN toggle nav-solid/nav-transparent di sini — pergantian class saat
// turbo:load memicu repaint + transisi background yang terlihat berkedip.
function syncNavState() {
  const toTop = document.getElementById('toTop');
  if (toTop) {
    const show = window.scrollY > 600;
    toTop.classList.toggle('hidden', !show);
    toTop.classList.toggle('flex', show);
  }
  document.querySelectorAll('#mainnav .nav-link').forEach((a) => {
    try {
      const href = a.getAttribute('href');
      a.classList.toggle('active', href === window.location.pathname);
    } catch (_) {}
  });
  // Kunci navbar solid, tidak peduli posisi scroll (desain sekarang selalu putih).
  const nav = document.getElementById('mainnav');
  if (nav) {
    nav.classList.add('nav-solid');
    nav.classList.remove('nav-transparent');
  }
}
if (!window.__scrollBound) {
  window.__scrollBound = true;
  window.addEventListener('scroll', syncNavState, { passive: true });
}

// ---- Reveal on scroll (re-observe tiap halaman baru) ----
let revealIO = null;
function initReveal() {
  if (revealIO) revealIO.disconnect();
  const els = document.querySelectorAll('.js .reveal:not(.in), html:not(.js) .reveal');
  if (!('IntersectionObserver' in window)) {
    els.forEach((el) => el.classList.add('in'));
    return;
  }
  revealIO = new IntersectionObserver(
    (entries) => {
      entries.forEach((en) => {
        if (en.isIntersecting) {
          en.target.classList.add('in');
          revealIO.unobserve(en.target);
        }
      });
    },
    { threshold: 0.12 },
  );
  els.forEach((el) => revealIO.observe(el));
}

// ---- Bersihkan timer/slider halaman lama sebelum Turbo render halaman baru ----
document.addEventListener('turbo:before-render', () => {
  try {
    if (window.__heroRAF) cancelAnimationFrame(window.__heroRAF);
  } catch (_) {}
  window.__heroRAF = null;
  try {
    if (window.__featTimer) clearInterval(window.__featTimer);
  } catch (_) {}
  window.__featTimer = null;
  closeMainDrops();
  mnavUncheck();
});

document.addEventListener('turbo:load', () => {
  syncNavState();
  syncMnavActive();
  initReveal();
  // JANGAN window.scrollTo({top:0}) di sini — Turbo Drive sudah mengatur scroll
  // otomatis. Pemanggilan manual setelah render menyebabkan lompatan/kedip.
});

document.addEventListener('DOMContentLoaded', () => {
  syncNavState();
  syncMnavActive();
  initReveal();
  // Ukur ulang setelah semua resource (font/gambar) selesai dimuat.
  if (!window.__mnavLoadBound) {
    window.__mnavLoadBound = true;
    window.addEventListener('load', syncMnavActive);
    try {
      document.fonts?.ready.then(() => syncMnavActive());
    } catch (_) {}
  }
});
