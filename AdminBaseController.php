<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminBaseController extends BaseController
{
    protected function view($file, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . '/../../Views/' . $file . '.php';
        $layout = __DIR__ . '/../../Views/admin/layout.php';
        if (!file_exists($viewFile)) {
            echo "View not found: $viewFile";
            return;
        }
        require $layout;
    }
}
