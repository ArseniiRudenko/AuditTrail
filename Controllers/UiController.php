<?php

namespace Leantime\Plugins\AuditTrail\Controllers;

use Illuminate\Support\Facades\Log;
use Leantime\Core\Controller\Controller;

class UiController extends Controller {

    public static function onTaskTabs($payload): void {
       echo "<li><a href='#tickethistory'><span class='fa fa-history'></span> History</a></li>";
    }

    public static function onTaskTabPanes($payload) {
        Log::info('Audit Trail Plugin Tab Pane Loaded for Ticket ID: '.json_encode($payload));

        return view("AuditTrail::history", $payload)->render();

    }

}
