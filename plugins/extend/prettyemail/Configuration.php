<?php

namespace SunlightExtend\Prettyemail;

use Sunlight\Plugin\Action\ConfigAction;
use Sunlight\Util\ConfigurationFile;
use Sunlight\Util\Form;

class Configuration extends ConfigAction
{
    protected function getFields(): array
    {
        $config = $this->plugin->getConfig();

        return [
            'template' => [
                'label' => _lang('prettyemail.cfg.template'),
                'input' => $this->createSelect('template', $this->loadMailTemplates(), 'default'),
            ],
            'use_logo' => [
                'label' => _lang('prettyemail.cfg.use_logo'),
                'input' => '<input type="checkbox" name="config[use_logo]" value="1"'
                    . Form::activateCheckbox($config->offsetGet('use_logo')) . '><br>'
                    . '<small>' . _lang('prettyemail.cfg.use_logo.hint') . '</small>'
                ,
                'type' => 'checkbox'
            ],
            'logo' => [
                'label' => _lang('prettyemail.cfg.logo'),
                'input' => '<input type="text" name="config[logo]" value="' . $config->offsetGet('logo') . '" class="inputmedium"><br>'
                    . '<small>' . _lang('prettyemail.cfg.logo.hint') . '</small>'
                ,
                'type' => 'text'
            ],
            'footer' => [
                'label' => _lang('prettyemail.cfg.footer'),
                'input' => '<textarea name="config[footer]" class="areasmall" class="areamedium">'
                    . $config->offsetGet('footer')
                    . '</textarea>',
                'type' => 'text'
            ],
        ];
    }

    protected function mapSubmittedValue(ConfigurationFile $config, string $key, array $field, $value): ?string
    {
        switch ($key) {
            case 'template':
                $config[$key] = ($value === '' ? 'default' : $value);
                return null;
        }

        return parent::mapSubmittedValue($config, $key, $field, $value);
    }

    private function loadMailTemplates(): array
    {
        $templates = [];
        $files = glob(__DIR__ . DIRECTORY_SEPARATOR . "Resources/templates/*.{html}", GLOB_BRACE);

        foreach ($files as $file) {
            $info = pathinfo($file);
            $templates[$info['filename']] = $info['filename'];
        }
        return $templates;
    }

    private function createSelect(string $name, array $options, $default): string
    {
        $result = "<select name='config[" . $name . "]' class='inputmedium'>";
        foreach ($options as $k => $v) {
            $result .= "<option value='" . $v . "'" . ($default == $v ? " selected" : "") . ">" . $k . "</option>";
        }
        $result .= "</select>";
        return $result;
    }

}
