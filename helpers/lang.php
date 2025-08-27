<?php
session_start();
function lang($key) {
    $lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'vi';
    $file = __DIR__ . '/../app/lang/lang_' . $lang . '.php';
    if (file_exists($file)) {
        $langArr = include $file;
        return isset($langArr[$key]) ? $langArr[$key] : $key;
    }
    return $key;
}
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'] === 'en' ? 'en' : 'vi';
    // Chỉ giữ lại các tham số GET khác ngoài lang để tránh lặp redirect
    $params = $_GET;
    unset($params['lang']);
    $query = http_build_query($params);
    $url = strtok($_SERVER['REQUEST_URI'], '?') . ($query ? '?' . $query : '');
    header('Location: ' . $url);
    exit;
}
