namespace App\Services;

use Predis\Client;

class CacheService {
private Client $redis;

public function __construct() {
$this->redis = new Client([
'scheme' => 'tcp',
'host' => $_ENV['REDIS_HOST'] ?? '127.0.0.1',
'port' => $_ENV['REDIS_PORT'] ?? 6379,
]);
}

public function remember(string $key, int $ttl, callable $callback) {
$cached = $this->redis->get($key);

if ($cached !== null) {
return unserialize($cached);
}

$value = $callback();
$this->redis->setex($key, $ttl, serialize($value));

return $value;
}
}