<?php

if (! defined('LARAVEL_START')) {
    exit(0);
}

use App\Enums\DataTypeEnum;
use App\Http\Requests\Admin\UpdatePluginSettingsRequest;
use App\Models\Option;
use App\Models\Plugin;
use App\Repositories\PluginRepository;
use Illuminate\Support\Facades\DB;

$options = [
    'username',
    'password',
    'number',
    'verification_pattern_id',
    'order_paid_pattern_id',
    'order_shipped_pattern_id',
    'order_paid_pattern_args',
    'order_shipped_pattern_args',
];

// Setting Options
PluginRepository::singleton()->addAction(
    hookName: "plugin[{$plugin->name}]__settings__options",
    callback: function () use ($plugin, $options) {
        return Option::where('prefix', $plugin->name)->whereIn('name', $options)->get();
    }
);

if (request()->routeIs('admin.plugins.plugin.settings.*')) {
    // Validation Rules
    PluginRepository::singleton()->addAction(
        hookName: "plugin[{$plugin->name}]__settings__validation_rules",
        callback: function () {
            return [
                'username' => ['required', 'string'],
                'password' => ['required', 'string'],
                'number'   => ['required', 'string'],
                'verification_pattern_id'    => ['required', 'string'],
                'order_paid_pattern_id'      => ['required', 'string'],
                'order_shipped_pattern_id'   => ['required', 'string'],
                'order_paid_pattern_args'    => ['required', 'string'],
                'order_shipped_pattern_args' => ['required', 'string'],
            ];
        }
    );

    // Update
    PluginRepository::singleton()->addAction(
        hookName: "plugin[{$plugin->name}]__settings__update",
        callback: function (UpdatePluginSettingsRequest $request, Plugin $plugin) use ($options) {
            $arr = array_map(function ($option) use ($request, $plugin) {
                return [
                    'prefix' => $plugin->name,
                    'name'   => $option,
                    'value'  => $request->input($option),
                    'type'   => DataTypeEnum::String,
                ];
            }, $options);

            DB::transaction(function () use ($arr) {
                Option::upsert($arr, uniqueBy: ['prefix', 'name'], update: ['value']);
            });
        }
    );
}
