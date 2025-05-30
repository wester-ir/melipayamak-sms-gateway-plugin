<?php

if (! defined('LARAVEL_START')) {
    exit(0);
}

use App\Enums\DataTypeEnum;
use App\Models\Option;
use App\Services\Core\Plugin\Bases\BasePluginSetup;

class PluginSetup extends BasePluginSetup
{
    public function install(): void
    {
        $options = [
            'username' => '',
            'password' => '',
            'number'   => '',
            'verification_pattern_id'    => '',
            'order_paid_pattern_id'      => '',
            'order_fulfilled_pattern_id'   => '',
            'order_paid_pattern_args'    => '',
            'order_fulfilled_pattern_args' => '',
        ];

        $prefix = $this->plugin->name;

        foreach ($options as $key => $value) {
            if (Option::where(['prefix' => $prefix, 'name' => $key])->doesntExist()) {
                Option::create([
                    'prefix' => $prefix,
                    'name'   => $key,
                    'value'  => $value,
                    'type'   => DataTypeEnum::String,
                ]);
            }
        }
    }

    public function activate(): void
    {}

    public function deactivate(): void
    {}

    public function uninstall(): void
    {}
};
