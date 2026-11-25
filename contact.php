<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function respond(int $status, array $body): never
{
    http_response_code($status);
    echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function utf8Length(string $value): int
{
    $count = preg_match_all('/./us', $value, $matches);
    return $count === false ? PHP_INT_MAX : $count;
}

function failContactRateLimitStorage($handle, string $reason): never
{
    if (is_resource($handle)) {
        flock($handle, LOCK_UN);
        fclose($handle);
    }

    error_log('MascoSitter contact rate limiter failed: ' . $reason);
    respond(503, ['ok' => false, 'message' => 'El formulario no está disponible en este momento. Inténtalo más tarde.']);
}

function enforceContactRateLimit(): void
{
    $clientIp = $_SERVER['REMOTE_ADDR'] ?? '';
    if (!is_string($clientIp) || filter_var($clientIp, FILTER_VALIDATE_IP) === false) {
        error_log('MascoSitter contact rate limiter received an invalid client IP.');
        respond(503, ['ok' => false, 'message' => 'El formulario no está disponible en este momento. Inténtalo más tarde.']);
    }

    $rateLimitPath = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR)
        . DIRECTORY_SEPARATOR
        . 'mascositter-contact-' . hash('sha256', $clientIp) . '.json';
    $rateLimitFile = fopen($rateLimitPath, 'c+');
    if ($rateLimitFile === false) {
        failContactRateLimitStorage($rateLimitFile, 'could not open rate limit state.');
    }
    if (!flock($rateLimitFile, LOCK_EX)) {
        failContactRateLimitStorage($rateLimitFile, 'could not lock rate limit state.');
    }
    if (!rewind($rateLimitFile)) {
        failContactRateLimitStorage($rateLimitFile, 'could not read rate limit state.');
    }

    $storedAttempts = stream_get_contents($rateLimitFile);
    if ($storedAttempts === false) {
        failContactRateLimitStorage($rateLimitFile, 'could not read rate limit state.');
    }

    $attempts = [];
    if ($storedAttempts !== '') {
        $decodedAttempts = json_decode($storedAttempts, true);
        if (!is_array($decodedAttempts) || json_last_error() !== JSON_ERROR_NONE) {
            failContactRateLimitStorage($rateLimitFile, 'rate limit state is invalid.');
        }
        foreach ($decodedAttempts as $timestamp) {
            if (!is_int($timestamp)) {
                failContactRateLimitStorage($rateLimitFile, 'rate limit state contains invalid timestamps.');
            }
        }
        $attempts = array_values($decodedAttempts);
    }

    $now = time();
    $attempts = array_values(array_filter(
        $attempts,
        static fn (int $timestamp): bool => $timestamp > $now - 3600
    ));

    if (count($attempts) >= 5) {
        flock($rateLimitFile, LOCK_UN);
        fclose($rateLimitFile);
        respond(429, ['ok' => false, 'message' => 'Has enviado demasiados mensajes. Inténtalo de nuevo más tarde.']);
    }

    $attempts[] = $now;
    $encodedAttempts = json_encode($attempts);
    if (
        $encodedAttempts === false
        || !ftruncate($rateLimitFile, 0)
        || !rewind($rateLimitFile)
        || fwrite($rateLimitFile, $encodedAttempts) !== strlen($encodedAttempts)
        || !fflush($rateLimitFile)
    ) {
        failContactRateLimitStorage($rateLimitFile, 'could not write rate limit state.');
    }

    flock($rateLimitFile, LOCK_UN);
    fclose($rateLimitFile);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(405, ['ok' => false, 'message' => 'Método no permitido.']);
}

if (!in_array($_SERVER['HTTP_ORIGIN'] ?? '', [
    'https://mascositter.cear54.com',
    'http://localhost',
    'http://127.0.0.1',
], true)) {
    respond(403, ['ok' => false, 'message' => 'Origen de la solicitud no permitido.']);
}

if ((int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 12000) {
    respond(413, ['ok' => false, 'message' => 'El comentario es demasiado largo.']);
}

session_set_cookie_params([
    'httponly' => true,
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'samesite' => 'Lax',
]);
session_start();

$submittedToken = $_POST['csrf_token'] ?? '';
$sessionToken = $_SESSION['contact_csrf'] ?? '';
if (!is_string($submittedToken) || !is_string($sessionToken) || $sessionToken === '' || !hash_equals($sessionToken, $submittedToken)) {
    respond(403, ['ok' => false, 'message' => 'La sesión del formulario venció. Actualiza la página e inténtalo de nuevo.']);
}

$honeypot = $_POST['website'] ?? '';
if (is_string($honeypot) && trim($honeypot) !== '') {
    respond(400, ['ok' => false, 'message' => 'No se pudo procesar el comentario.']);
}

$name = trim((string) ($_POST['name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));

if ($name === '' || utf8Length($name) > 100 || filter_var($email, FILTER_VALIDATE_EMAIL) === false || strlen($email) > 150 || $message === '' || utf8Length($message) > 350) {
    respond(422, ['ok' => false, 'message' => 'Revisa tu nombre, correo y comentario antes de enviarlo.']);
}

$safeName = preg_replace('/[\r\n]+/', ' ', $name) ?? $name;
$safeName = trim($safeName);
$body = "Nombre: {$safeName}\nCorreo: {$email}\n\nComentario:\n{$message}\n";
$headers = implode("\r\n", [
    'From: MascoSitter <no-reply@cear54.com>',
    "Reply-To: {$email}",
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'Content-Transfer-Encoding: 8bit',
]);

enforceContactRateLimit();

if (!mail('contacto@cear54.com', 'Nuevo comentario desde MascoSitter', $body, $headers, '-f no-reply@cear54.com')) {
    error_log('MascoSitter contact email failed: PHP mail() returned false.');
    respond(502, ['ok' => false, 'message' => 'No pudimos enviar tu comentario. Inténtalo de nuevo más tarde.']);
}

$nextContactToken = bin2hex(random_bytes(32));
$_SESSION['contact_csrf'] = $nextContactToken;
respond(200, [
    'ok' => true,
    'message' => '¡Gracias! Recibimos tu comentario.',
    'csrf_token' => $nextContactToken,
]);
