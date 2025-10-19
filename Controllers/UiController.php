<?php

namespace Leantime\Plugins\AuditTrail\Controllers;

use Illuminate\Support\Facades\Log;
use Leantime\Core\Controller\Controller;
use Leantime\Domain\Projects\Services\Projects as ProjectService;
use Leantime\Domain\Tickets\Services\Tickets as TicketService;
use Leantime\Plugins\AuditTrail\Repositories\AuditTrailRepository;

class UiController extends Controller
{
    private AuditTrailRepository $auditTrailRepository;

    private TicketService $ticketService;

    private ProjectService $projectService;

    public function init(TicketService $ticketService, ProjectService $projectService): void
    {
        $this->auditTrailRepository = new AuditTrailRepository();
        $this->ticketService = $ticketService;
        $this->projectService = $projectService;
    }

    public function onTaskTabs($payload): void
    {
        echo "<li><a href='#tickethistory'><span class='fa fa-history'></span> History</a></li>";
    }

    public function onTaskTabPanes($payload)
    {
        $taskId = $payload['ticket']->id;
        $projectId = $payload['ticket']->projectId;

        $history = [];
        if ($taskId !== null) {
            try {
                $history = $this->auditTrailRepository->getAuditTrail((int) $taskId);
            } catch (\Throwable $e) {
                Log::error('Failed to load audit trail history: ' . $e->getMessage(), ['taskId' => $taskId]);
            }
        } else {
            Log::warning('Audit Trail Plugin: taskId missing in payload for history tab');
        }

        $payload['history'] = $history;
        $payload['taskId'] = $taskId; // ensure available to view
        $payload['effortLabels'] = $this->ticketService->getEffortLabels();
        $payload['statusLabels'] = $this->ticketService->getStatusLabels($projectId);
        $payload['projectNames'] = $this->projectService->getProjectNames();
        Log::info(json_encode($payload));

        Log::info('Audit Trail Plugin Tab Pane Loaded for Ticket ID: ' . json_encode([
            'taskId' => $taskId,
            'historyCount' => count($history),
        ]));

        echo view('AuditTrail::history', $payload)->render();
    }
}
