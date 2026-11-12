const menuToggle = document.querySelector('.menu-toggle');
const siteNav = document.querySelector('.site-nav');

if (menuToggle && siteNav) {
  menuToggle.addEventListener('click', () => {
    const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';
    menuToggle.setAttribute('aria-expanded', String(!isOpen));
    menuToggle.setAttribute('aria-label', isOpen ? 'Abrir menú' : 'Cerrar menú');
    siteNav.classList.toggle('is-open', !isOpen);
  });

  siteNav.addEventListener('click', (event) => {
    if (event.target instanceof HTMLAnchorElement) {
      menuToggle.setAttribute('aria-expanded', 'false');
      menuToggle.setAttribute('aria-label', 'Abrir menú');
      siteNav.classList.remove('is-open');
    }
  });
}

const contactForm = document.querySelector('#contact-form');
const formStatus = document.querySelector('#form-status');

if (contactForm instanceof HTMLFormElement && formStatus) {
  contactForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    if (!contactForm.reportValidity()) return;

    const submitButton = contactForm.querySelector('button[type="submit"]');
    const submitLabel = contactForm.querySelector('.submit-label');
    if (!(submitButton instanceof HTMLButtonElement) || !submitLabel) return;

    submitButton.disabled = true;
    submitLabel.textContent = 'Enviando...';
    formStatus.textContent = '';
    formStatus.removeAttribute('data-state');

    try {
      const response = await fetch(contactForm.action, {
        method: 'POST',
        body: new FormData(contactForm),
        headers: { Accept: 'application/json' },
      });
      const result = await response.json();

      if (!response.ok || result.ok !== true) {
        throw new Error(result.message || 'No pudimos enviar tu comentario. Inténtalo de nuevo.');
      }

      formStatus.textContent = result.message;
      formStatus.dataset.state = 'success';
      contactForm.reset();
    } catch (error) {
      formStatus.textContent = error instanceof Error
        ? error.message
        : 'No pudimos enviar tu comentario. Inténtalo de nuevo.';
      formStatus.dataset.state = 'error';
    } finally {
      submitButton.disabled = false;
      submitLabel.textContent = 'Enviar comentario';
    }
  });
}
