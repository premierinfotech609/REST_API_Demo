<?php
    define('SITE_SUB_PATH',  '/REST_API_Demo/');
    define('SITE_URL',  'http://localhost'.SITE_SUB_PATH);
    define('SITE_PATH', $_SERVER["DOCUMENT_ROOT"] ."/");
    define('API_PATH', SITE_URL ."v1/users/");

    define("API_KEY",base64_encode('users_rest_api_demo'));
?>