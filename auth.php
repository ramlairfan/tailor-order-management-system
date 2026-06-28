<?php

require_once __DIR__ . '/session.php';

if (!isset($_SESSION['user_id'])) {
    // Find the login.php at the project root regardless of caller depth
    $script   = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']);
    $docRoot  = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    // Walk up directories from current script until login.php is found
    $dir = dirname($script);
    $loginPath = '';
    for ($i = 0; $i < 5; $i++) {
        if (file_exists($dir . '/login.php')) {
            // Convert absolute path to URL path
            $urlPath = str_replace($docRoot, '', $dir) . '/login.php';
            $loginPath = $urlPath;
            break;
        }
        $dir = dirname($dir);
    }
    if ($loginPath === '') {
        $loginPath = '/login.php';
    }
    header("Location: " . $loginPath);
    exit;
}
