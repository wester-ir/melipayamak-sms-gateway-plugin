<?php

if (! defined('LARAVEL_START')) {
    exit(0);
}

if (! class_exists('WK_MELIPAYAMAK_SMS_GATEWAY') && ! function_exists('WK_MELIPAYAMAK_SMS_GATEWAY_INIT')) {
    // Add translations
    Lang::addNamespace('MelipayamakSMSGateway', realpath( __DIR__ .'/lang/'));

    // Require the class
    require_once('class.php');

    function WK_MELIPAYAMAK_SMS_GATEWAY_INIT(): array
    {
        return ['melipayamak' => WK_MELIPAYAMAK_SMS_GATEWAY::class];
    }

    // Gateways
    pluginRepository()->addAction(
        hookName: 'sms_gateways',
        callback: 'WK_MELIPAYAMAK_SMS_GATEWAY_INIT',
    );
}
