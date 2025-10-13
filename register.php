<?php

use Leantime\Domain\Plugins\Services\Registration;
use AuditTrail\Controllers\UiController;
use AuditTrail\Controllers\RpcController;
use AuditTrail\Middleware\GetLanguageAssets;

return static function () {
    /** @var Registration $registration */
    $registration = app()->makeWith(Registration::class, ['pluginId' => 'AuditTrail']);

    // optional, matches template
    $registration->addMiddleware(GetLanguageAssets::class);

    // UI hooks for the task page
    $registration->listen('ui.task.tabs', [UiController::class, 'onTaskTabs']);
    $registration->listen('ui.task.tabpanes', [UiController::class, 'onTaskTabPanes']);

    // optional RPC if you want AJAX fetching later
    $registration->registerRpc('ticketHistory.getForTask', [RpcController::class, 'getForTask']);

    return $registration;
};
