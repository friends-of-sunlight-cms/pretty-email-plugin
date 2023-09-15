<?php

namespace SunlightExtend\Prettyemail;

use Sunlight\Plugin\Action\ConfigAction as BaseConfigAction;
use Sunlight\Util\ConfigurationFile;
use Sunlight\Util\Form;

class ConfigAction extends BaseConfigAction
{
    protected function getFields(): array
    {
        $config = $this->plugin->getConfig();

        return [
            'template' => [
                'label' => _lang('prettyemail.config.template'),
                'input' => _buffer(function () use ($config) { ?>
                    <select name="config[editor_mode]" class="inputsmall">
                        <?php foreach ($this->loadMailTemplates() as  $template):?>
                            <option value="<?= _e($template) ?>" <?= Form::selectOption($config['template'] === $template) ?>><?= _e($template) ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php }),
            ],
            'use_logo' => [
                'label' => _lang('prettyemail.config.use_logo'),
                'input' => '<input type="checkbox" name="config[use_logo]" value="1"' . Form::activateCheckbox($config['use_logo'])  . '>',
                'type' => 'checkbox'
            ],
            'logo' => [
                'label' => _lang('prettyemail.config.logo'),
                'input' => '<input type="text" name="config[logo]" value="' . Form::restorePostValue('logo', $config['logo'], false) . '">',
                'type' => 'text',
            ],
            'footer' => [
                'label' => _lang('prettyemail.config.footer'),
                'input' => '<textarea name="config[footer]" class="areasmall">' . Form::restorePostValue('footer', $config['footer'], false) . '</textarea>',
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
