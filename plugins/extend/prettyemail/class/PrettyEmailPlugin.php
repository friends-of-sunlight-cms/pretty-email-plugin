<?php

namespace SunlightExtend\Prettyemail;

use Sunlight\Core;
use Sunlight\Plugin\Action\PluginAction;
use Sunlight\Plugin\ExtendPlugin;
use Sunlight\Router;
use Sunlight\Settings;

class PrettyEmailPlugin extends ExtendPlugin
{
    public function onSend(array $args): void
    {
        // plugin config
        $cfg = $this->getConfig();

        // load template
        $templateFile = __DIR__ . DIRECTORY_SEPARATOR . '../resources/templates/' . $cfg->offsetGet('template') . '.html';
        $template = file_get_contents($templateFile);

        $logo = "<h1>" . Settings::get('title') . "</h1>";
        if ($cfg->offsetGet('use_logo')) {
            $logoPath = Router::path($cfg->offsetGet('logo'), ['absolute' => true]);
            $logo = '<img src="' . $logoPath . '" alt="Logo ' . Settings::get('title') . '" border="0">';
        }

        // prepare replacement
        $replacement = [
            '%site%' => Settings::get('title'),
            '%logo%' => $logo,
            '%domain%' => Core::getBaseUrl()->buildAbsolute(),
            '%subject%' => $args['subject'],
            '%content%' => $args['message'],
            '%footer%' => (!empty($cfg->offsetGet('footer'))
                ? $cfg->offsetGet('footer') . '<br>'
                : ''),
            '%year%' => date('Y'),
            '%copyright%' => _lang('prettyemail.copyright')
        ];

        $args['message'] = strtr($template, $replacement);// replace
        $args['headers']['Content-Type'] = 'text/html; charset=UTF-8';
        $args['result'] = null; // only the message is edited, the message is still sent by the system
    }
}
