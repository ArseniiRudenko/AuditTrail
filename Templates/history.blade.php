<?php
// History tab pane for ticket modal
// Expects: $history (array of rows), $taskId (int|null)
$taskId = $taskId ?? null;
$priorityMap = ['1' => 'Critical', '2' => 'High', '3' => 'Medium', '4' => 'Low', '5' => 'Lowest'];
?>
<div id="tickethistory" class="tw-p-md">
    <h3 class="tw-mt-0 tw-mb-sm"><span class="fa fa-history"></span> History</h3>

    <?php if (empty($history)) { ?>
        <p class="tw-text-gray-500 tw-italic">No history entries yet.</p>
    <?php } else { ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-condensed">
                <thead>
                <tr>
                    <th style="width:180px;">When</th>
                    <th style="width:160px;">User</th>
                    <th>Field</th>
                    <th>New value</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($history as $row) { ?>
                    <?php
                    $changeType = $row['changeType'] ?? '';
                    $rawValue = $row['changeValue'] ?? '';
                    $displayValue = $rawValue;
                    if ($changeType === 'priority' && $rawValue !== '' && isset($priorityMap[$rawValue])) {
                        $displayValue = $priorityMap[$rawValue];
                    }
                    if($changeType === 'storypoints')
                        $changeType= 'effort';
                    if ($changeType === 'effort' && $rawValue !== '' && isset($effortLabels[$rawValue])){
                        $displayValue = $effortLabels[$rawValue];
                    }
                    if( $changeType === 'editors' ) {
                        $changeType = 'assignee';
                    }
                    if($changeType === 'status' && $rawValue !== '' && isset($statusLabels[$rawValue])) {
                        $displayValue = $statusLabels[$rawValue]['name'];
                    }

                    if($changeType === 'project' && $rawValue !== '' && isset($projectNames[$rawValue])) {
                        $displayValue  = $projectNames[$rawValue];
                    }
                    ?>
                    <tr>
                        <td data-order="<?= htmlspecialchars($row['dateModified']) ?>">
                            <?php if (!empty($row['dateModified'])) { ?>
                                <?= format($row['dateModified'])->date(); ?>
                                <?= format($row['dateModified'])->time(); ?>
                            <?php } else { ?>
                                <em>—</em>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if (!empty($row['userId'])) { ?>
                                <?php
                                $userFull = trim(($row['userFirstname'] ?? '') . ' ' . ($row['userLastname'] ?? ''));
                                if ($userFull === '') {
                                    $userFull = $row['userUsername'] ?? ('User #'.(int)$row['userId']);
                                }
                                ?>

                                <a href="<?= BASE_URL ?>/users/editUser/<?= (int)$row['userId'] ?>" title="View user"  class="cr-resizer-horisontal tw-flex tw-items-center tw-gap-2">
                                <img alt="User" src="<?= BASE_URL ?>/api/users?profileImage=<?= (int)$row['userId'] ?>&v=<?= time() ?>" style="width:24px;height:24px;border-radius:50%;"/>
                                <div>  <?= htmlspecialchars($userFull) ?></div>
                                </a>

                            <?php } else { ?>
                                <em>System</em>
                            <?php } ?>
                        </td>
                        <td><?= htmlspecialchars(ucfirst($changeType)) ?></td>
                        <td>
                            <?php if ($displayValue !== '') { ?>
                                <?php if ($changeType === 'assignee' && is_numeric($rawValue) && (int)$rawValue > 0) {
                                    $assigneeId = (int)$rawValue;
                                    $userFull = trim(($row['valueFirstname'] ?? '') . ' ' . ($row['valueLastname'] ?? ''));
                                    if ($userFull === '') {
                                        $userFull = $row['valueUsername'] ?? ('User #'.(int)$row['userId']);
                                    }
                                    ?>
                                    <a href="<?= BASE_URL ?>/users/editUser/<?= $assigneeId ?>" title="View user">
                                        <span><?= htmlspecialchars($userFull) ?></span>
                                    </a>
                                    <?php }elseif ($changeType === 'commit') {
                                         $parts = explode("||",$rawValue);
                                    ?>
                                    <a href="<?=$parts[0]?>" title="View commit">
                                        <span><?= htmlspecialchars($parts[1]) ?></span>
                                    </a>
                                <?php } else { ?>
                                    <span><?= htmlspecialchars($displayValue) ?></span>
                                <?php } ?>
                            <?php } else { ?>
                                <em>—</em>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>

    <div class="tw-mt-sm tw-text-right tw-text-xs tw-text-gray-500">
        <?php if ($taskId !== null) { ?>
            Showing <?= count($history) ?> change<?= count($history) === 1 ? '' : 's' ?> for ticket #<?= (int)$taskId ?>.
        <?php } ?>
    </div>
</div>
