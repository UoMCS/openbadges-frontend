<?php

define('OPEN_BADGES_BACKEND_URL', 'http://localhost:8080');

if (!defined('OPEN_BADGES_DEBUG_MODE'))
{
  define('OPEN_BADGES_DEBUG_MODE', false);
}

define('TWIG_TEMPLATES_DIR', __DIR__ . '/templates');
