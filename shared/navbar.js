
(function () {

    function ready(fn) {
        if (document.readyState !== 'loading') fn();
        else document.addEventListener('DOMContentLoaded', fn);
    }

    ready(function () {
        const hamburger  = document.getElementById('ff-hamburger');
        const mobileMenu = document.getElementById('ff-mobile-menu');

        if (!hamburger || !mobileMenu) return; 
        hamburger.addEventListener('click', function (e) {
            e.stopPropagation();
            const isOpen = mobileMenu.classList.toggle('ff-open');
            hamburger.classList.toggle('ff-open', isOpen);
            hamburger.setAttribute('aria-expanded', String(isOpen));
        });

        mobileMenu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                mobileMenu.classList.remove('ff-open');
                hamburger.classList.remove('ff-open');
                hamburger.setAttribute('aria-expanded', 'false');
            });
        });

        document.addEventListener('click', function (e) {
            const navbar = document.querySelector('.ff-navbar');
            if (navbar && !navbar.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.remove('ff-open');
                hamburger.classList.remove('ff-open');
                hamburger.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                mobileMenu.classList.remove('ff-open');
                hamburger.classList.remove('ff-open');
                hamburger.setAttribute('aria-expanded', 'false');
            }
        });
    });
})();