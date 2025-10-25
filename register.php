<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Leantime\Core\Events\EventDispatcher;
use Leantime\Domain\Plugins\Services\Registration;
use Leantime\Plugins\AuditTrail\Controllers\UiController;

// UI hooks for the task page
EventDispatcher::add_event_listener('leantime.domain.tickets.*.ticketTabs', [UiController::class, 'onTaskTabs']);
EventDispatcher::add_event_listener('leantime.domain.tickets.*.ticketTabsContent', [UiController::class, 'onTaskTabPanes']);

View::addNamespace('AuditTrail', base_path('app/Plugins/AuditTrail/Templates'));

$reg = new Registration("AuditTrail");
$reg->registerLanguageFiles();
