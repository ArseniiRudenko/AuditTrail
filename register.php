<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Leantime\Core\Events\EventDispatcher;
use Leantime\Plugins\AuditTrail\Controllers\UiController;


Log::info("Registering Audit Trail Plugin");
// UI hooks for the task page
EventDispatcher::add_event_listener('leantime.domain.tickets.*.ticketTabs', [UiController::class, 'onTaskTabs']);
EventDispatcher::add_event_listener('leantime.domain.tickets.*.ticketTabsContent', [UiController::class, 'onTaskTabPanes']);

View::addNamespace('AuditTrail', base_path('app/Plugins/AuditTrail/Templates'));
