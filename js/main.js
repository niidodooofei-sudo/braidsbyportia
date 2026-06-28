const header    = document.getElementById('site-header');
const navToggle = document.getElementById('nav-toggle');
const mainNav   = document.getElementById('main-nav');

// Sticky header (only adds 'scrolled' on the home page — inner pages start solid via PHP class)
window.addEventListener('scroll', () => {
  if (!header.classList.contains('always-solid')) {
    header.classList.toggle('scrolled', window.scrollY > 60);
  }
}, { passive: true });

// Mobile nav toggle
navToggle?.addEventListener('click', () => {
  const isOpen = navToggle.classList.toggle('open');
  mainNav.classList.toggle('open', isOpen);
  navToggle.setAttribute('aria-expanded', isOpen);
  document.body.style.overflow = isOpen ? 'hidden' : '';
});

mainNav?.querySelectorAll('.nav-link').forEach(link => {
  link.addEventListener('click', () => {
    navToggle.classList.remove('open');
    mainNav.classList.remove('open');
    navToggle.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  });
});

// Prefix all internal absolute links when site runs in a subdirectory
(function () {
  const base = window.APP_BASE || '';
  if (!base) return;
  document.querySelectorAll('a[href^="/"]').forEach(a => {
    const h = a.getAttribute('href');
    if (!h.startsWith(base)) a.setAttribute('href', base + h);
  });
})();

// Scroll reveal
const revealEls = document.querySelectorAll('.reveal');
if (revealEls.length) {
  const io = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
      if (entry.isIntersecting) {
        setTimeout(() => entry.target.classList.add('visible'), i * 60);
        io.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
  revealEls.forEach(el => io.observe(el));
}
