<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;

final class SecurityController
{
    // Entrega o token da sessao para clientes SPA. O token continua vinculado
    // ao cookie de sessao HttpOnly e deve voltar no cabecalho X-CSRF-Token.
    public function csrfToken(Request $request): void
    {
        Response::json(['csrf_token' => csrf_token()]);
    }
}
