<?php

require_once '../app/bootstrap.php';

use App\Controllers\HomeController;
use App\Security\JsonFileIpRateLimiter;
use App\Security\ServerIpAddressResolver;
use App\Security\SessionCsrfTokenManager;

$csrfTokenManager = new SessionCsrfTokenManager();
$ipAddressResolver = new ServerIpAddressResolver();
$rateLimiter = new JsonFileIpRateLimiter();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $csrfTokenManager->issue();

    $controller = new HomeController();
    $response = $controller->index();

    if (empty($_SESSION['flash']) === false) {
        unset($_SESSION['flash']);
    }

    echo $response;
} else if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $ipAddress = $ipAddressResolver->resolve($_SERVER);

    if ($rateLimiter->isBlocked($ipAddress)) {
        http_response_code(429);
        exit("Aguarde antes de enviar novamente.");
    }

    if ($csrfTokenManager->isValidRequest($_POST) === false) {
        http_response_code(403);
        exit("Requisição inválida.");
    }

    if (!empty($_POST['website'])) {
        http_response_code(403);
        exit();
    }

    $controller = new HomeController();
    $controller->post();

    $csrfTokenManager->issue();
}

session_write_close();
