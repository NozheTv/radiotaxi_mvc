<?php
namespace App\Core;

class Controller {
    public function model($model) {
        $modelFile = __DIR__ . '/../Models/' . $model . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            $modelFull = "App\\Models\\" . $model;
            return new $modelFull();
        }
        return null;
    }

    public function view($view, $data = []) {
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        if (file_exists($viewFile)) {
            extract($data);
            require_once $viewFile;
        }
    }
}
