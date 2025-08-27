<?php
// Path helper for development vs production
function getBasePath() {
    // Check if running on PHP development server
    if (php_sapi_name() === 'cli-server') {
        return '';
    } else {
        // Running on Apache/production - get directory name dynamically
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $basePath = dirname($scriptName);
        return $basePath === '/' ? '' : $basePath;
    }
}

function url($path = '') {
    return getBasePath() . $path;
}

function asset($path) {
    return getBasePath() . '/' . ltrim($path, '/');
}

function getActionUrl($controller = '', $action = '', $params = []) {
    $url = getBasePath() . '/index.php';
    
    $queryParams = [];
    if ($controller) {
        $queryParams['controller'] = $controller;
    }
    if ($action) {
        $queryParams['action'] = $action;
    }
    
    $queryParams = array_merge($queryParams, $params);
    
    if (!empty($queryParams)) {
        $url .= '?' . http_build_query($queryParams);
    }
    
    return $url;
}
?>
