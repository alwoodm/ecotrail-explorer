(function () {
    const form = document.getElementById('contactForm');
    const themeToggle = document.getElementById('themeToggle');
    const storageKey = 'et-theme';

    function resolveTheme() {
        const applied = document.documentElement.getAttribute('data-bs-theme');
        return applied === 'dark' ? 'dark' : 'light';
    }

    function setTheme(nextTheme) {
        document.documentElement.setAttribute('data-bs-theme', nextTheme);
        try {
            localStorage.setItem(storageKey, nextTheme);
        } catch (error) {
            /* ignore storage failures */
        }
        if (themeToggle) {
            const label = nextTheme === 'dark' ? 'Przełącz na jasny tryb' : 'Przełącz na ciemny tryb';
            themeToggle.setAttribute('aria-label', label);
            themeToggle.setAttribute('title', label);
            themeToggle.setAttribute('aria-pressed', nextTheme === 'dark' ? 'true' : 'false');
        }
    }

    window.addEventListener('pageshow', function () {
        if (form) {
            form.reset();
        }
    });

    if (form) {
        form.addEventListener('submit', function (event) {
            const captchaResponse = form.querySelector('textarea[name="h-captcha-response"]');
            if (!captchaResponse || !captchaResponse.value) {
                event.preventDefault();
                alert('Potwierdź weryfikację hCaptcha przed wysłaniem formularza.');
            }
        });
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            const nextTheme = resolveTheme() === 'dark' ? 'light' : 'dark';
            setTheme(nextTheme);
        });
    }

    setTheme(resolveTheme());
})();
