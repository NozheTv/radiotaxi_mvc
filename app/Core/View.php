<?php
namespace App\Core;

class View {
    public function render($view, $data = []) {
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        if (file_exists($viewFile)) {
            extract($data);
            require $viewFile;
        }
    }
}
