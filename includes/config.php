<?php
/**
 * kiuixshop - core configuration and helpers
 * Edit the DB_* values below (or set the KIUIX_DB_* environment variables).
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('DB_HOST', getenv('KIUIX_DB_HOST') ?: 'localhost');
define('DB_USER', getenv('KIUIX_DB_USER') ?: 'root');
define('DB_PASS', getenv('KIUIX_DB_PASS') ?: '');
define('DB_NAME', getenv('KIUIX_DB_NAME') ?: 'kiuixshop');

// Filesystem root of the site and the URL path it is served from
// (works at the domain root or inside a sub-folder such as /kiuixshop).
define('ROOT_PATH', str_replace('\\', '/', dirname(__DIR__)));
$docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', rtrim(realpath($_SERVER['DOCUMENT_ROOT']) ?: '', '/\\')) : '';
$base = ($docRoot !== '' && strpos(ROOT_PATH, $docRoot) === 0) ? substr(ROOT_PATH, strlen($docRoot)) : '';
define('BASE_URL', rtrim($base, '/'));

mysqli_report(MYSQLI_REPORT_OFF);
$conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    http_response_code(500);
    die('<h2 style="font-family:sans-serif;padding:40px">Database connection failed.<br><small>Check the DB settings in includes/config.php and import database.sql.</small></h2>');
}
mysqli_set_charset($conn, 'utf8mb4');

/** Get a value from the settings table (cached per request). */
function getSetting($key, $default = '')
{
    global $conn;
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        $res = mysqli_query($conn, "SELECT setting_key, setting_value FROM settings");
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $cache[$row['setting_key']] = $row['setting_value'];
            }
        }
    }
    return (isset($cache[$key]) && $cache[$key] !== '') ? $cache[$key] : $default;
}

/** Trim + strip tags for plain-text input. Output must still go through e(). */
function sanitize($value)
{
    return trim(strip_tags((string)$value));
}

/** HTML-escape for output. */
function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/** Build a URL inside the site. */
function url($path = '')
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/** Format a price using the configured currency symbol. */
function formatPrice($amount)
{
    return e(getSetting('currency_symbol', '৳')) . number_format((float)$amount, 2);
}

/** wa.me link (digits only number) with a prefilled message. */
function waLink($text = '')
{
    $number = preg_replace('/\D+/', '', getSetting('whatsapp_number', ''));
    return 'https://wa.me/' . $number . ($text !== '' ? '?text=' . rawurlencode($text) : '');
}

/** CSRF helpers */
function csrfToken()
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrfField()
{
    return '<input type="hidden" name="csrf" value="' . e(csrfToken()) . '">';
}

function csrfValid()
{
    return isset($_POST['csrf'], $_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string)$_POST['csrf']);
}

/** URL-safe slug */
function slugify($text)
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    return $text !== '' ? $text : 'category-' . substr(md5($text . microtime()), 0, 6);
}
