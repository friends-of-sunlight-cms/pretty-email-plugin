<?php

use Sunlight\Core;
use Sunlight\Router;
use Sunlight\Settings;

return function (array $args) {
    // plugin config
    $config = $this->getConfig();

    // load template
    $templateFile = __DIR__ . DIRECTORY_SEPARATOR . '../resources/templates/' . $config['template'] . '.html';
    $template = file_get_contents($templateFile);

    $logo = "<h1>" . Settings::get('title') . "</h1>";
    if ($config['use_logo']) {
        $logoPath = Router::path($config['logo'], ['absolute' => true]);
        $logo = '<img src="' . _e($logoPath) . '" alt="Logo ' . Settings::get('title') . '" border="0">';
    }

    // prepare replacement
    $replacement = [
        '%site%' => Settings::get('title'),
        '%logo%' => $logo,
        '%domain%' => Core::getBaseUrl()->buildAbsolute(),
        '%subject%' => $args['subject'],
        '%content%' => $args['message'],
        '%footer%' => (!empty($config['footer'])
            ? $config['footer'] . '<br>'
            : ''),
        '%year%' => date('Y'),
        '%copyright%' => _lang('prettyemail.copyright')
    ];

    $args['message'] = strtr($template, $replacement);// replace
    $args['headers']['Content-Type'] = 'text/html; charset=UTF-8';
    $args['result'] = null; // only the message is edited, the message is still sent by the system
};
