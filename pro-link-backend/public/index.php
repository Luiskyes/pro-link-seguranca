<?php

declare(strict_types=1);

use App\Core\Auth;
use App\Core\Router;

// Cabecalhos defensivos contra classes comuns de ataques no navegador.
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Permissions-Policy: camera=(), microphone=(), geolocation=()");
header("Content-Security-Policy: default-src 'self'; img-src 'self' data: https:; style-src 'self' 'unsafe-inline'; script-src 'self'; frame-ancestors 'none'; base-uri 'self'; form-action 'self'");

// Front Controller: unico ponto de entrada de toda a aplicacao web.
require dirname(__DIR__) . '/vendor/autoload.php';

// Configuração de CORS (Cross-Origin Resource Sharing) para SPA
// $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
// header("Access-Control-Allow-Origin: $origin");
// header("Access-Control-Allow-Credentials: true");
// header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Carrega variaveis do .env para $_ENV (safeLoad nao quebra se o arquivo nao existir).
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

// Nao exponha stack traces/detalhes internos em producao.
$isProduction = (($_ENV['APP_ENV'] ?? 'production') === 'production');
ini_set('display_errors', $isProduction ? '0' : '1');
ini_set('display_startup_errors', $isProduction ? '0' : '1');
error_reporting(E_ALL);

// Dispara o carregamento do _config.php (timezone, charset, PATHs e config geral).
config('app');

// CORS restrito ao cliente configurado; nunca reflete origens arbitrarias.
$allowedOrigin = (string) config('app.frontend_origin');
$requestOrigin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($requestOrigin !== '' && hash_equals($allowedOrigin, $requestOrigin)) {
    header('Access-Control-Allow-Origin: ' . $allowedOrigin);
    header('Access-Control-Allow-Credentials: true');
    header('Vary: Origin');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, X-CSRF-Token, X-Requested-With');
}

// Trata preflight somente depois de aplicar os cabecalhos CORS permitidos.
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Sessao necessaria para autenticacao (Request::user()) e token CSRF.
// Cookie httpOnly configurado antes de abrir a sessao (Anexo I, item 8.5-a).
Auth::configureSessionCookie();
session_start();

// Registra as rotas definidas em routes/web.php no Router.
$router = new Router();
require dirname(__DIR__) . '/routes/web.php';

// Despacha a requisicao atual: Router -> Middlewares -> Controller.
$router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);
