<?php

namespace SunlightExtend\Prettyemail;

use Sunlight\Plugin\Action\ConfigAction as BaseConfigAction;
use Sunlight\Util\ConfigurationFile;
use Sunlight\Util\Form;
use Sunlight\Util\Request;

class ConfigAction extends BaseConfigAction
{
    protected function getFields(): array
    {
        $config = $this->plugin->getConfig();

        return [
            'template' => [
                'label' => _lang('prettyemail.config.template'),
                'input' => Form::select('config[editor_mode]', $this->loadMailTemplates(), $config['template'], ['class' => 'inputsmall']),
            ],
            'use_logo' => [
                'label' => _lang('prettyemail.config.use_logo'),
                'input' => Form::input('checkbox', 'config[use_logo]', '1', ['checked' => Form::loadCheckbox('config', $config['use_logo'], 'use_logo')]),
                'type' => 'checkbox'
            ],
            'logo' => [
                'label' => _lang('prettyemail.config.logo'),
                'input' => Form::input('text', 'config[logo]', Request::post('logo', $config['logo'])),
                'type' => 'text',
            ],
            'footer' => [
                'label' => _lang('prettyemail.config.footer'),
                'input' => Form::textarea('config[footer]', Request::post('footer', $config['footer']), ['class' => 'areasmall']),
                'type' => 'text',
            ]
        ];
    }

    protected function mapSubmittedValue(ConfigurationFile $config, string $key, array $field, $value): ?string
    {
        if ($key === 'template') {
            $config[$key] = ($value === '' ? 'default' : $value);
            return null;
        }

        return parent::mapSubmittedValue($config, $key, $field, $value);
    }

    private function loadMailTemplates(): array
    {
        $templates = [];
        $files = glob(__DIR__ . DIRECTORY_SEPARATOR . "../public/templates/*.{html}", GLOB_BRACE);

        foreach ($files as $file) {
            $info = pathinfo($file);
            $templates[$info['filename']] = $info['filename'];
        }
        return $templates;
    }
}
