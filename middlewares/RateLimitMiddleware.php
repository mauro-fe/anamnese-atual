class RateLimitMiddleware {
private static $attempts = [];
private const MAX_ATTEMPTS = 60;
private const TIME_WINDOW = 60; // segundos

public static function check($identifier): bool {
$now = time();
$key = md5($identifier);

// Limpa tentativas antigas
self::$attempts[$key] = array_filter(
self::$attempts[$key] ?? [],
fn($time) => $now - $time < self::TIME_WINDOW ); if (count(self::$attempts[$key])>= self::MAX_ATTEMPTS) {
    return false;
    }

    self::$attempts[$key][] = $now;
    return true;
    }
    }