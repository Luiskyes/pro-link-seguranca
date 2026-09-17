<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

// Protecao contra CSRF em requisicoes que alteram estado (POST).
class CsrfMiddleware
{
    // Compara o token enviado no formulario com o token da sessao (comparacao segura).
    public function handle(Request $request): void
    {
        if ($request->server['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $token = $request->input('_csrf')
            ?? ($request->server['HTTP_X_CSRF_TOKEN'] ?? null);

        if (!hash_equals($_SESSION['_csrf_token'] ?? '', (string) $token)) {
            Response::json(['message' => 'Token CSRF inválido ou ausente.'], 419);
            exit;
        }
    }
}
