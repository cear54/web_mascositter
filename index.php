<?php
declare(strict_types=1);

session_set_cookie_params([
    'httponly' => true,
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'samesite' => 'Lax',
]);
session_start();

$_SESSION['contact_csrf'] = bin2hex(random_bytes(32));
$contactToken = htmlspecialchars($_SESSION['contact_csrf'], ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#17251e">
  <meta name="description" content="Organiza los datos, rutinas y cuidados de tu mascota con MascoSitter.">
  <title>MascoSitter | El cuidado de tu mascota, más fácil</title>
  <link rel="icon" href="assets/images/mascositter-logo.gif" type="image/gif">
  <link rel="stylesheet" href="assets/css/styles.css">
  <script src="assets/js/site.js" defer></script>
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <a class="brand" href="#inicio" aria-label="MascoSitter, inicio">
        <img src="assets/images/mascositter-logo.gif" alt="MascoSitter">
      </a>
      <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-nav" aria-label="Abrir menú">
        <span></span><span></span>
      </button>
      <nav class="site-nav" id="site-nav" aria-label="Navegación principal">
        <a href="#funciones">Funciones</a>
        <a href="#cuidadores">Cuidadores</a>
        <a href="#app">La app</a>
        <a class="nav-cta" href="#contacto">Contacto <span aria-hidden="true">↗</span></a>
      </nav>
    </div>
  </header>

  <main>
    <section class="hero section" id="inicio">
      <div class="container hero-grid">
        <div class="hero-copy">
          <p class="eyebrow"><span class="eyebrow-dot"></span> Para quienes son parte de la familia</p>
          <h1>Todo su cuidado,<br><span>en un solo lugar.</span></h1>
          <p class="hero-description">Organiza la información y las rutinas de tu mascota, y ten a la mano esos detalles que hacen su día más feliz.</p>
          <div class="hero-actions">
            <a class="button button-dark" href="https://play.google.com/store/apps/details?id=com.cear54.mascositter" target="_blank" rel="noopener noreferrer">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.2 2.8 15 12 4.2 21.2a1.8 1.8 0 0 1-.7-1.5V4.3c0-.6.3-1.1.7-1.5Zm1.5-.8 10.8 9.2 3.2-2.7L7.8 1.3c-.7-.4-1.5-.2-2.1.7Zm10.8 12.8L5.7 24c.6.9 1.4 1.1 2.1.7l11.9-7.2-3.2-2.7Zm4.2-3.5-2.8-1.7-3.4 2.9 3.4 2.9 2.8-1.7c1-.6 1-1.8 0-2.4Z"/></svg>
              <span><small>Consíguela en</small> Google Play</span>
            </a>
            <span class="button button-store-disabled" aria-label="App Store, próximamente">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16.4 12.7c0-2.1 1.7-3.1 1.8-3.2a3.9 3.9 0 0 0-3.1-1.7c-1.3-.1-2.5.8-3.2.8s-1.7-.8-2.8-.8a4.2 4.2 0 0 0-3.5 2.1c-1.5 2.6-.4 6.4 1.1 8.5.7 1 1.5 2 2.5 2 1 0 1.3-.6 2.6-.6s1.6.6 2.7.6 1.8-1 2.4-1.9a8 8 0 0 0 1.1-2.2 3.5 3.5 0 0 1-1.6-3.6ZM14.3 6.4A3.8 3.8 0 0 0 15.2 3a3.8 3.8 0 0 0-2.6 1.3 3.5 3.5 0 0 0-.9 3.3 3.2 3.2 0 0 0 2.6-1.2Z"/></svg>
              <span><small>Disponible pronto en</small> App Store <b>PRÓXIMAMENTE</b></span>
            </span>
          </div>
          <p class="hero-note">Una forma más sencilla de estar al pendiente de quien más te quiere.</p>
        </div>
        <div class="hero-visual" aria-label="Vista de la aplicación MascoSitter">
          <div class="hero-orbit orbit-one"></div>
          <div class="hero-orbit orbit-two"></div>
          <div class="hero-paw paw-one" aria-hidden="true">✦</div>
          <div class="hero-paw paw-two" aria-hidden="true">✳</div>
          <div class="phone-frame hero-phone">
            <img src="assets/images/mascotas.jpg" alt="Pantalla de MascoSitter con el perfil de una mascota" fetchpriority="high">
          </div>
          <div class="floating-note">
            <span class="note-icon" aria-hidden="true">♡</span>
            <span><strong>Un lugar para cada detalle</strong><small>Sus cuidados, a tu manera</small></span>
          </div>
        </div>
      </div>
      <div class="hero-bottom container">
        <span>HECHA PARA SUS GRANDES Y PEQUEÑOS MOMENTOS</span>
        <a href="#funciones" aria-label="Descubre las funciones">↓</a>
      </div>
    </section>

    <section class="section features-section" id="funciones">
      <div class="container">
        <div class="section-heading">
          <p class="eyebrow"><span class="eyebrow-dot"></span> Su mundo, bien organizado</p>
          <h2>Todo lo que necesita,<br><span>sin perder de vista lo importante.</span></h2>
          <p>Guarda lo esencial y acompaña sus cuidados desde una sola app.</p>
        </div>
        <div class="feature-grid">
          <article class="feature-card feature-card-green">
            <div class="feature-icon" aria-hidden="true">
              <svg viewBox="0 0 48 48"><path d="M24 39s-15-8.8-15-19a8.5 8.5 0 0 1 15-5.5A8.5 8.5 0 0 1 39 20c0 10.2-15 19-15 19Z"/><path d="M18 25h12m-6-6v12"/></svg>
            </div>
            <h3>Su perfil, a su medida</h3>
            <p>Registra sus datos y mantén su información importante siempre a mano.</p>
            <span class="feature-number">01</span>
          </article>
          <article class="feature-card feature-card-lilac">
            <div class="feature-icon" aria-hidden="true">
              <svg viewBox="0 0 48 48"><path d="M8 11h32M12 7v8m24-8v8M10 19h28v21H10z"/><path d="m17 29 4 4 10-10"/></svg>
            </div>
            <h3>Rutinas que dan tranquilidad</h3>
            <p>Organiza sus comidas y paseos con horarios que se adaptan a su semana.</p>
            <span class="feature-number">02</span>
          </article>
          <article class="feature-card feature-card-blue">
            <div class="feature-icon" aria-hidden="true">
              <svg viewBox="0 0 48 48"><path d="M24 41s16-8 16-21a9 9 0 0 0-16-5 9 9 0 0 0-16 5c0 13 16 21 16 21Z"/><path d="M24 17v12m-6-6h12"/></svg>
            </div>
            <h3>Salud y recordatorios</h3>
            <p>Lleva el seguimiento de vacunas, baños y otros cuidados importantes.</p>
            <span class="feature-number">03</span>
          </article>
          <article class="feature-card feature-card-peach">
            <div class="feature-icon" aria-hidden="true">
              <svg viewBox="0 0 48 48"><path d="M9 10h30v22H22l-9 7v-7H9z"/><path d="M17 19h14m-14 6h9"/></svg>
            </div>
            <h3>Recuerdos para siempre</h3>
            <p>Guarda fotos, historias y momentos especiales de su vida juntos.</p>
            <span class="feature-number">04</span>
          </article>
          <article class="feature-card feature-card-yellow">
            <div class="feature-icon" aria-hidden="true">
              <svg viewBox="0 0 48 48"><path d="M12 36c7-2 10-7 12-15 1 8 4 13 12 15M24 21V8m-6 7 6-7 6 7"/><path d="M10 39h28"/></svg>
            </div>
            <h3>Su historia, en un vistazo</h3>
            <p>Consulta su historial de cuidados y ten más contexto cuando lo necesites.</p>
            <span class="feature-number">05</span>
          </article>
          <article class="feature-card feature-card-pink">
            <div class="feature-icon" aria-hidden="true">
              <svg viewBox="0 0 48 48"><circle cx="24" cy="24" r="15"/><path d="M24 15v10l7 4"/><path d="M13 8 8 13m27-5 5 5"/></svg>
            </div>
            <h3>Alertas para el día a día</h3>
            <p>Programa recordatorios para que las tareas importantes no se te pasen.</p>
            <span class="feature-number">06</span>
          </article>
          <article class="feature-card feature-card-lilac">
            <div class="feature-icon" aria-hidden="true">
              <svg viewBox="0 0 48 48"><path d="M24 7 28 18l11-5-5 11 11 4-11 4 5 11-11-5-4 11-4-11-11 5 5-11-11-4 11-4-5-11 11 5z"/></svg>
            </div>
            <h3>Un espacio a tu estilo</h3>
            <p>Personaliza la experiencia con temas de color y fondos para la app.</p>
            <span class="feature-number">07</span>
          </article>
          <article class="feature-card feature-card-green">
            <div class="feature-icon" aria-hidden="true">
              <svg viewBox="0 0 48 48"><path d="M10 15h28v24H10zM15 15V9h18v6m-18 9h18m-18 6h12"/><path d="m31 34 3 3 6-7"/></svg>
            </div>
            <h3>Opciones para cada necesidad</h3>
            <p>Consulta los planes y las funciones disponibles para familias y cuidadores.</p>
            <span class="feature-number">08</span>
          </article>
        </div>
      </div>
    </section>

    <section class="section routine-section" id="app">
      <div class="container routine-grid">
        <div class="routine-visual">
          <div class="phone-frame routine-phone">
            <img src="assets/images/rutinas.jpg" alt="Opciones de cuidado para una mascota en MascoSitter" loading="lazy">
          </div>
          <div class="routine-sticker"><img src="assets/images/vacuna-mascota.gif" alt="" loading="lazy"><span>Sus cuidados,<br>en orden</span></div>
          <div class="routine-spark" aria-hidden="true">✳</div>
        </div>
        <div class="routine-copy">
          <p class="eyebrow"><span class="eyebrow-dot"></span> A su ritmo</p>
          <h2>De la comida de hoy<br><span>a sus próximos cuidados.</span></h2>
          <p>MascoSitter reúne herramientas sencillas para que acompañes sus hábitos y tengas presentes sus fechas importantes.</p>
          <ul class="check-list">
            <li><span aria-hidden="true">✓</span> Horarios y seguimiento de comidas y paseos</li>
            <li><span aria-hidden="true">✓</span> Vacunas, baños y recordatorios personalizables</li>
            <li><span aria-hidden="true">✓</span> Fotos, recuerdos e historial de cuidados</li>
          </ul>
          <a class="text-link" href="https://play.google.com/store/apps/details?id=com.cear54.mascositter" target="_blank" rel="noopener noreferrer">Descubre MascoSitter en Google Play <span aria-hidden="true">↗</span></a>
        </div>
      </div>
    </section>

    <section class="section caregivers-section" id="cuidadores">
      <div class="container caregiver-card">
        <div class="caregiver-copy">
          <p class="eyebrow eyebrow-light"><span class="eyebrow-dot"></span> También te acompaña</p>
          <h2>Encuentra apoyo<br>para sus paseos y cuidados.</h2>
          <p>Explora perfiles de cuidadores, busca por ubicación o correo y contacta desde la app. Si ofreces paseos o cuidados, puedes registrarte para recibir solicitudes según las opciones de tu plan.</p>
          <a class="button button-light" href="https://play.google.com/store/apps/details?id=com.cear54.mascositter" target="_blank" rel="noopener noreferrer">Conoce la app <span aria-hidden="true">↗</span></a>
        </div>
        <div class="caregiver-art">
          <div class="caregiver-blob"></div>
          <img src="assets/images/cuidar-mascotas.gif" alt="Ilustración de cuidado de mascotas" loading="lazy">
          <div class="caregiver-caption"><span aria-hidden="true">✦</span> Un poco de ayuda<br>hace una gran diferencia</div>
        </div>
      </div>
    </section>

    <section class="section screenshots-section">
      <div class="container">
        <div class="section-heading screenshots-heading">
          <p class="eyebrow"><span class="eyebrow-dot"></span> Así se vive MascoSitter</p>
          <h2>Una app cercana,<br><span>pensada para su día a día.</span></h2>
        </div>
        <div class="screenshots-grid">
          <figure class="screenshot-card screenshot-card-tall">
            <img src="assets/images/menu-app.jpg" alt="Menú de MascoSitter con opciones para organizar mascotas y cuidadores" loading="lazy">
            <figcaption><span>01</span> Todo empieza aquí</figcaption>
          </figure>
          <figure class="screenshot-card screenshot-card-offset">
            <img src="assets/images/comidas.jpg" alt="Agenda semanal y registro de comidas de una mascota" loading="lazy">
            <figcaption><span>02</span> Sus rutinas, a tu manera</figcaption>
          </figure>
          <figure class="screenshot-card screenshot-card-tall">
            <img src="assets/images/mascotas.jpg" alt="Perfil de una mascota registrada en MascoSitter" loading="lazy">
            <figcaption><span>03</span> Cada mascota es única</figcaption>
          </figure>
        </div>
      </div>
    </section>

    <section class="section download-section">
      <div class="container download-card">
        <div class="download-mark" aria-hidden="true">♡</div>
        <p class="eyebrow eyebrow-light"><span class="eyebrow-dot"></span> Empieza hoy</p>
        <h2>Haz espacio para<br>más momentos juntos.</h2>
        <p>Descarga MascoSitter y organiza sus cuidados desde tu celular.</p>
        <div class="download-actions">
          <a class="button button-light" href="https://play.google.com/store/apps/details?id=com.cear54.mascositter" target="_blank" rel="noopener noreferrer">Descargar en Google Play <span aria-hidden="true">↗</span></a>
          <span class="app-store-note">App Store <span>Próximamente</span></span>
        </div>
      </div>
    </section>

    <section class="section contact-section" id="contacto">
      <div class="container contact-grid">
        <div class="contact-copy">
          <p class="eyebrow"><span class="eyebrow-dot"></span> Estamos para escucharte</p>
          <h2>¿Tienes algo<br><span>que contarnos?</span></h2>
          <p>Déjanos un comentario, una pregunta o una idea. Nos encantará leerte.</p>
          <a class="contact-email" href="mailto:contacto@cear54.com">contacto@cear54.com <span aria-hidden="true">↗</span></a>
        </div>
        <form class="contact-form" id="contact-form" action="contact.php" method="post">
          <input type="hidden" name="csrf_token" value="<?= $contactToken ?>">
          <div class="form-trap" aria-hidden="true">
            <label for="website">No llenar este campo</label>
            <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
          </div>
          <label for="name">Tu nombre</label>
          <input id="name" name="name" type="text" autocomplete="name" maxlength="100" placeholder="¿Cómo te llamas?" required>
          <label for="email">Tu correo</label>
          <input id="email" name="email" type="email" autocomplete="email" maxlength="254" placeholder="nombre@correo.com" required>
          <label for="message">Tu comentario</label>
          <textarea id="message" name="message" rows="4" maxlength="2000" placeholder="Cuéntanos en qué podemos ayudarte..." required></textarea>
          <div class="form-bottom">
            <p>Al enviar, aceptas nuestra <a href="https://cear54.github.io/mascositter-privacy/" target="_blank" rel="noopener noreferrer">política de privacidad</a>.</p>
            <button class="button button-dark" type="submit"><span class="submit-label">Enviar comentario</span><span class="submit-arrow" aria-hidden="true">↗</span></button>
          </div>
          <p class="form-status" id="form-status" role="status" aria-live="polite"></p>
        </form>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container footer-main">
      <a class="brand footer-brand" href="#inicio" aria-label="MascoSitter, inicio">
        <img src="assets/images/mascositter-logo.gif" alt="MascoSitter">
      </a>
      <p>Más cerca de sus cuidados, más cerca de ellos.</p>
      <nav aria-label="Enlaces legales">
        <a href="https://cear54.github.io/mascositter-privacy/" target="_blank" rel="noopener noreferrer">Privacidad</a>
        <a href="https://cear54.github.io/mascositter-privacy/eliminar-cuenta/" target="_blank" rel="noopener noreferrer">Eliminar cuenta</a>
        <a href="mailto:contacto@cear54.com">Contacto</a>
      </nav>
    </div>
    <div class="container footer-bottom">
      <span>© <?= date('Y') ?> MascoSitter</span>
      <a href="#inicio">Volver arriba ↑</a>
    </div>
  </footer>
</body>
</html>
