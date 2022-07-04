<?php
// DATE
/**
 * @param string $date
 * @param string $format
 * @return string
 * @throws Exception
 */
function date_fmt(string $date = 'now', string $format = 'd/m/Y H\hi'): string
{
    return (new DateTime($date))->format($format);
}

/**
 * @param string $date
 * @return string
 * @throws Exception
 */
function date_fmt_br(string $date = 'now'): string
{
    return (new DateTime($date))->format(DATE_BR);
}

/**
 * @param string $date
 * @return string
 * @throws Exception
 */
function date_fmt_app(string $date = 'now'): string
{
    return (new DateTime($date))->format(DATE_APP);
}

// USER
/**
 * @param int $len
 * @return string
 * @throws Exception
 */
function token(int $len = 10): string
{
    return base64_encode(random_bytes($len)) . time() . uniqid();
}

/**
 * @return mixed|null[][]
 */
function message_flash()
{
    if (!empty($_SESSION['flash'])) {
        $message = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $message;
    }
    return array('flash' => ['msg' => null, 'type' => null]);
}

// NAV
/**
 * @param string|null $path
 * @return string
 */
function url(string $path = null)
{
    if ($path && $path != "/") {
        return URL_BASE . $path;
    }
    return URL_BASE;
}

/**
 * @param string|null $url
 */
function redirect(string $url = null): void
{
    header("HTTP/1.1 302 Found");

    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $url = URL_BASE . $url;
    } elseif (!$url || $url == '/') {
        $url = URL_BASE;
    }
    header("Location: {$url}");
    exit;
}

// NUMBER
/**
 * @param string $phone
 * @return string
 */
function phone(string $phone): string
{
    $format = '';
    if (strlen($phone) == 10) {
        $format .= '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 4) . '-' . substr($phone, 6);
    } elseif (strlen($phone) == 11) {
        $format = '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 5) . '-' . substr($phone, 7);
    }
    return $format;
}
