# MascoSitter

Landing page responsive para presentar la app y sus funciones. El formulario envía los comentarios a `contacto@cear54.com` mediante PHP y SMTP.

## Requisitos

- PHP 8.1 o posterior con OpenSSL habilitado.
- Composer.
- Un servidor SMTP con usuario y contraseña.

## Instalación local con XAMPP

1. Instala las dependencias desde esta carpeta:

   ```powershell
   $env:PATH = "C:\xampp\php;$env:PATH"
   composer install
   ```

2. Configura estas variables en el entorno de PHP/Apache. En XAMPP puedes declararlas en la configuración de Apache fuera de `htdocs` y reiniciar Apache:

   - `SMTP_HOST`: servidor SMTP.
   - `SMTP_PORT`: normalmente `587` para TLS o `465` para SSL.
   - `SMTP_USERNAME`: usuario SMTP.
   - `SMTP_PASSWORD`: contraseña SMTP.
   - `SMTP_FROM`: dirección autorizada para enviar desde ese servidor.
   - `SMTP_ENCRYPTION`: `tls` o `ssl` (por defecto `tls`).

   No guardes credenciales SMTP en esta carpeta ni en el repositorio.

3. Abre `http://localhost/web_mascositter/`.

El botón de Google Play usa el identificador Android de MascoSitter. App Store se muestra como próximamente y no tiene enlace activo.
