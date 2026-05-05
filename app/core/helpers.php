<?php

function base_url($path = '') {
    $config = require __DIR__ . '/../../config/app.php';
    return rtrim($config['base_url'], '/') . '/' . ltrim($path, '/');
}

?>