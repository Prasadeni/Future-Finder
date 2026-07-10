

const body = document.body;
const loadingScreen = document.querySelector('.loading-screen');
const navLinks = document.querySelector('.nav-links');
const menuToggle = document.querySelector('.menu-toggle');
const themeToggle = document.querySelector('#theme-toggle');
const backToTop = document.querySelector('.back-to-top');
const cookieBanner = document.querySelector('.cookie-banner');
const cookieBtn = document.querySelector('#accept-cookies');
const faqItems = document.querySelectorAll('.faq-item');

// Loading screen
window.addEventListener('load', () => {
  setTimeout(() => {
    loadingScreen?.classList.add('hidden');
  }, 700);
});

// Mobile navigation
menuToggle?.addEventListener('click', () => {
  navLinks?.classList.toggle('open');
});

// Close menu on link click
document.querySelectorAll('.nav-links a').forEach((link) => {
  link.addEventListener('click', () => navLinks?.classList.remove('open'));
});

// Dark mode toggle
const savedTheme = localStorage.getItem('futurefinder-theme');
if (savedTheme === 'dark') {
  body.classList.add('dark');
}

themeToggle?.addEventListener('click', () => {
  body.classList.toggle('dark');
  const isDark = body.classList.contains('dark');
  localStorage.setItem('futurefinder-theme', isDark ? 'dark' : 'light');
});

// Scroll reveal
const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.16 });

document.querySelectorAll('.scroll-reveal').forEach((item) => observer.observe(item));

// Back to top button
window.addEventListener('scroll', () => {
  if (window.scrollY > 500) {
    backToTop?.classList.add('show');
  } else {
    backToTop?.classList.remove('show');
  }
});

backToTop?.addEventListener('click', () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
});

// Cookie banner
const cookieAccepted = localStorage.getItem('futurefinder-cookies');
if (cookieAccepted) {
  cookieBanner?.classList.add('hidden');
}

cookieBtn?.addEventListener('click', () => {
  localStorage.setItem('futurefinder-cookies', 'accepted');
  cookieBanner?.classList.add('hidden');
});

// FAQ accordion
faqItems.forEach((item) => {
  const button = item.querySelector('.faq-question');
  button?.addEventListener('click', () => {
    item.classList.toggle('open');
  });
});

// Button ripple effect
document.querySelectorAll('.btn').forEach((button) => {
  button.addEventListener('click', (event) => {
    const ripple = document.createElement('span');
    ripple.style.position = 'absolute';
    ripple.style.borderRadius = '50%';
    ripple.style.background = 'rgba(255,255,255,0.3)';
    ripple.style.transform = 'scale(0)';
    ripple.style.animation = 'pulse 0.6s linear';
    ripple.style.left = `${event.offsetX}px`;
    ripple.style.top = `${event.offsetY}px`;
    ripple.style.width = '20px';
    ripple.style.height = '20px';
    button.appendChild(ripple);
    setTimeout(() => ripple.remove(), 600);
  });
});

// Smooth active nav state
const sections = document.querySelectorAll('section[id]');
const navAnchors = Array.from(document.querySelectorAll('.nav-links a'));
window.addEventListener('scroll', () => {
  let current = '';
  sections.forEach((section) => {
    const top = section.offsetTop - 120;
    if (window.scrollY >= top) {
      current = section.getAttribute('id');
    }
  });
  navAnchors.forEach((anchor) => {
    anchor.classList.toggle('active', anchor.getAttribute('href') === `#${current}`);
  });
});

// Roadmap download button
const roadmapDownload = document.querySelector('[data-download-roadmap]');
roadmapDownload?.addEventListener('click', () => {
  const content = 'Future Finder Roadmap\nPrepared for success in your chosen career.';
  const blob = new Blob([content], { type: 'text/plain' });
  const link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = 'future-finder-roadmap.txt';
  link.click();
});
