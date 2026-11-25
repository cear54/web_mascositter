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
const characterCountFields = [
  {
    input: document.querySelector('#name'),
    remaining: document.querySelector('#name-remaining'),
  },
  {
    input: document.querySelector('#email'),
    remaining: document.querySelector('#email-remaining'),
  },
  {
    input: document.querySelector('#message'),
    remaining: document.querySelector('#message-remaining'),
  },
];

for (const field of characterCountFields) {
  if (!isCountableField(field.input) || !field.remaining) continue;

  const updateRemaining = () => updateCharacterCount(field.input, field.remaining);

  field.input.addEventListener('input', updateRemaining);
  updateRemaining();
}

function isCountableField(field) {
  return field instanceof HTMLInputElement || field instanceof HTMLTextAreaElement;
}

function updateCharacterCount(input, counter) {
  counter.textContent = String(Math.max(0, input.maxLength - input.value.length));
}

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

      if (!response.ok || result.ok !== true || typeof result.csrf_token !== 'string') {
        throw new Error(result.message || 'No pudimos enviar tu comentario. Inténtalo de nuevo.');
      }

      formStatus.textContent = result.message;
      formStatus.dataset.state = 'success';
      contactForm.reset();
      const csrfTokenInput = contactForm.querySelector('input[name="csrf_token"]');
      if (csrfTokenInput instanceof HTMLInputElement) {
        csrfTokenInput.value = result.csrf_token;
        csrfTokenInput.defaultValue = result.csrf_token;
      }
      for (const field of characterCountFields) {
        if (isCountableField(field.input) && field.remaining) {
          updateCharacterCount(field.input, field.remaining);
        }
      }
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
