<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(405, ['ok' => false, 'message' => 'Método no permitido.']);
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

if ($name === '' || utf8Length($name) > 100 || filter_var($email, FILTER_VALIDATE_EMAIL) === false || strlen($email) > 254 || $message === '' || utf8Length($message) > 2000) {
    respond(422, ['ok' => false, 'message' => 'Revisa tu nombre, correo y comentario antes de enviarlo.']);
}

$requiredSettings = ['SMTP_HOST', 'SMTP_USERNAME', 'SMTP_PASSWORD', 'SMTP_FROM'];
$settings = [];
foreach ($requiredSettings as $setting) {
    $value = getenv($setting);
    if ($value === false || trim($value) === '') {
        error_log('MascoSitter contact form is missing the ' . $setting . ' environment variable.');
        respond(503, ['ok' => false, 'message' => 'El formulario no está disponible en este momento. Inténtalo más tarde.']);
    }
    $settings[$setting] = trim($value);
}

if (!filter_var($settings['SMTP_FROM'], FILTER_VALIDATE_EMAIL)) {
    error_log('MascoSitter contact form has an invalid SMTP_FROM address.');
    respond(503, ['ok' => false, 'message' => 'El formulario no está disponible en este momento. Inténtalo más tarde.']);
}

$autoload = __DIR__ . '/vendor/autoload.php';
if (!is_file($autoload)) {
    error_log('MascoSitter contact form dependencies are missing; run composer install.');
    respond(503, ['ok' => false, 'message' => 'El formulario no está disponible en este momento. Inténtalo más tarde.']);
}
require $autoload;

$portValue = getenv('SMTP_PORT');
$port = $portValue === false || trim($portValue) === '' ? 587 : filter_var($portValue, FILTER_VALIDATE_INT);
$encryptionValue = strtolower(trim((string) (getenv('SMTP_ENCRYPTION') ?: 'tls')));
if ($port === false || $port < 1 || $port > 65535 || !in_array($encryptionValue, ['tls', 'ssl'], true)) {
    error_log('MascoSitter contact form has invalid SMTP_PORT or SMTP_ENCRYPTION settings.');
    respond(503, ['ok' => false, 'message' => 'El formulario no está disponible en este momento. Inténtalo más tarde.']);
}

$safeName = preg_replace('/[\r\n]+/', ' ', $name) ?? $name;
$safeName = trim($safeName);
$htmlName = htmlspecialchars($safeName, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$htmlMessage = nl2br(htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = $settings['SMTP_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $settings['SMTP_USERNAME'];
    $mail->Password = $settings['SMTP_PASSWORD'];
    $mail->Timeout = 15;
    $mail->SMTPSecure = $encryptionValue === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $port;
    $mail->CharSet = PHPMailer::CHARSET_UTF8;
    $mail->setFrom($settings['SMTP_FROM'], 'MascoSitter');
    $mail->addAddress('contacto@cear54.com');
    $mail->addReplyTo($email, $safeName);
    $mail->isHTML(true);
    $mail->Subject = 'Nuevo comentario desde MascoSitter';
    $mail->Body = '<p><strong>Nombre:</strong> ' . $htmlName . '</p><p><strong>Correo:</strong> ' . htmlspecialchars($email, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</p><p><strong>Comentario:</strong><br>' . $htmlMessage . '</p>';
    $mail->AltBody = "Nombre: {$safeName}\nCorreo: {$email}\n\nComentario:\n{$message}";
    $mail->send();
} catch (Exception $exception) {
    error_log('MascoSitter contact email failed: ' . $exception->getMessage());
    respond(502, ['ok' => false, 'message' => 'No pudimos enviar tu comentario. Inténtalo de nuevo más tarde.']);
}

respond(200, ['ok' => true, 'message' => '¡Gracias! Recibimos tu comentario.']);
