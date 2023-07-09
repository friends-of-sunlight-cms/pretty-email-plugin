<?php

namespace SunlightExtend\Prettyemail;

use Fosc\Feature\Plugin\Config\FieldGenerator;
use Sunlight\Plugin\Action\ConfigAction as BaseConfigAction;
use Sunlight\Util\ConfigurationFile;

class ConfigAction extends BaseConfigAction
{
    protected function getFields(): array
    {
        $config = $this->plugin->getConfig();

        $langPrefix = "%p:prettyemail.config";

        $gen = new FieldGenerator($this->plugin);
        $gen->generateField('template', $langPrefix, '%select', [
            'class' => 'inputsmall',
            'select_options' => $this->loadMailTemplates(),
            'select_default' => 'default',
        ])
            ->generateField('use_logo', $langPrefix, '%checkbox', [
                'after' => '<small>' . _lang('prettyemail.config.use_logo.hint') . '</small>'
            ])
            ->generateField('logo', $langPrefix, '%text', [
                'after' => '<small>' . _lang('prettyemail.config.logo.hint') . '</small>'
            ])
            ->generateField('footer', $langPrefix, _buffer(function () use ($config) { ?>
                <textarea name="config[footer]" class="areasmall" class="areamedium"><?= $config->offsetGet('footer') ?></textarea>
            <?php }), [], 'text');

        return $gen->getFields();
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
