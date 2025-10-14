<?php

namespace Leantime\Plugins\AuditTrail\Controllers;

use Illuminate\Support\Facades\Log;
use Leantime\Domain\Tickets\Services\Tickets as TicketService;
use Leantime\Plugins\AuditTrail\Repositories\AuditTrailRepository;

class UiController{

    private AuditTrailRepository $auditTrailRepository;
    private TicketService $ticketService;

    public function __construct() {
        $this->auditTrailRepository = new AuditTrailRepository();
        $this->ticketService = app(TicketService::class);
    }


    public function onTaskTabs($payload): void {
       echo "<li><a href='#tickethistory'><span class='fa fa-history'></span> History</a></li>";
    }

    public function onTaskTabPanes($payload) {
        // Support both legacy ['taskId'=>X] and new ['ticket'=>object/array]
        $taskId = null;
        if (isset($payload['taskId'])) {
            $taskId = $payload['taskId'];
        } elseif (isset($payload['ticket'])) {
            // Ticket can be object or array
            if (is_object($payload['ticket']) && isset($payload['ticket']->id)) {
                $taskId = $payload['ticket']->id;
            } elseif (is_array($payload['ticket']) && isset($payload['ticket']['id'])) {
                $taskId = $payload['ticket']['id'];
            }
        }

        $history = [];
        if ($taskId !== null) {
            try {
                $history = $this->auditTrailRepository->getAuditTrail((int)$taskId);
            } catch (\Throwable $e) {
                Log::error('Failed to load audit trail history: '.$e->getMessage(), ['taskId' => $taskId]);
            }
        } else {
            Log::warning('Audit Trail Plugin: taskId missing in payload for history tab');
        }

        $payload['history'] = $history;
        $payload['taskId'] = $taskId; // ensure available to view
        $payload['effortLabels'] = $this->ticketService->getEffortLabels();
        Log::info('Audit Trail Plugin Tab Pane Loaded for Ticket ID: '.json_encode([
            'taskId' => $taskId,
            'historyCount' => count($history),
        ]));

        echo view("AuditTrail::history", $payload)->render();

    }

}
