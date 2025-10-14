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
                    <th></th>
                    <th style="width:160px;">User</th>
                    <th>Field</th>
                    <th>Changed to</th>
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
                    if (($changeType === 'effort' || $changeType === 'storypoints') && $rawValue !== '' && isset($effortLabels[$rawValue])){
                        $displayValue = $effortLabels[$rawValue];
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
                            <a href="<?= BASE_URL ?>/users/editUser/<?= (int)$row['userId'] ?>" title="View user" >
                                <img alt="User" src="<?= BASE_URL ?>/api/users?profileImage=<?= (int)$row['userId'] ?>&v=<?= time() ?>" style="width:24px;height:24px;border-radius:50%;object-fit:contain;"/>
                            </a>
                        </td>
                        <td>
                            <?php if (!empty($row['userId'])) { ?>
                                <?php
                                $userFull = trim(($row['userFirstname'] ?? '') . ' ' . ($row['userLastname'] ?? ''));
                                if ($userFull === '') {
                                    $userFull = $row['userUsername'] ?? ('User #'.(int)$row['userId']);
                                }
                                ?>
                                <?= htmlspecialchars($userFull) ?>
                            <?php } else { ?>
                                <em>System</em>
                            <?php } ?>
                        </td>
                        <td><?= htmlspecialchars(ucfirst($changeType)) ?></td>
                        <td>
                            <?php if ($displayValue !== '') { ?>
                                <span><?= htmlspecialchars($displayValue) ?></span>
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
