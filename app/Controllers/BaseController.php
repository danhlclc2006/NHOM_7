<?php

namespace App\Controllers;

class BaseController
{
    protected function view($file, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . '/../Views/' . $file . '.php';
        if (!file_exists($viewFile)) {
            echo "View not found: $viewFile";
            return;
        }
        require __DIR__ . '/../Views/layout.php';
    }
    protected function redirect($url)
    {
        $url = BASE_URL . $url;
        header('Location: ' . $url);
        exit;
    }
    protected function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
