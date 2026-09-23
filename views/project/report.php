<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Report - <?= htmlspecialchars($project['project_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fff; color: #000; font-family: Arial, sans-serif; padding: 20px; }
        .report-header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .section-title { background: #f0f0f0; padding: 5px 10px; border-left: 4px solid #333; font-weight: bold; margin-top: 20px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f9f9f9; width: 30%; }
        .no-print { display: block; margin-bottom: 20px; }
        @media print {
            .no-print { display: none !important; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body>
    <div class="no-print text-center">
        <button onclick="window.print()" class="btn btn-primary">Print Report</button>
        <button onclick="window.close()" class="btn btn-secondary">Close</button>
    </div>

    <div class="report-header"><h2>Squarewave Automation Technologies Pvt. Ltd.</h2>
        <h2>PROJECT REPORT</h2>
        <h4><?= htmlspecialchars($project['project_name']) ?></h4>
        <p>Generated on: <?= date('Y-m-d H:i:s') ?></p>
    </div>

    <div class="section-title">Project Details</div>
    <table>
        <tr><th>Company Name</th><td><?= htmlspecialchars($project['company_name']) ?></td></tr>
        <tr><th>Issue Date</th><td><?= htmlspecialchars($project['issue_date']) ?></td></tr>
        <tr><th>Start Date</th><td><?= htmlspecialchars($project['start_date']) ?></td></tr>
        <tr><th>Completion Date</th><td><?= htmlspecialchars($project['completed_at'] ?? 'In Progress') ?></td></tr>
        <tr><th>Progress</th><td><?= htmlspecialchars($project['progress'] ?? 0) ?>%</td></tr>
        <tr><th>PO Status</th><td><?= ($project['po_status'] ?? 0) ? 'Yes' : 'No' ?></td></tr>
        <tr><th>Tax Invoice Status</th><td><?= ($project['tax_invoice_status'] ?? 0) ? 'Yes' : 'No' ?></td></tr>
        <tr><th>Problem Statement</th><td><?= nl2br(htmlspecialchars($project['problem_statement'])) ?></td></tr>
    </table>

    <div class="section-title">Team Members</div>
    <table>
        <thead>
            <tr><th style="width: 50%;">Name</th><th>Role</th></tr>
        </thead>
        <tbody>
            <?php if(!empty($team)): ?>
                <?php foreach($team as $member): ?>
                    <tr>
                        <td><?= htmlspecialchars($member['user_name']) ?></td>
                        <td><?= htmlspecialchars($member['role']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="2">No team members assigned.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="section-title">Project Steps & Subtasks</div>
    <?php if(!empty($steps)): ?>
        <?php foreach($steps as $step): ?>
            <div style="margin-bottom: 15px; border: 1px solid #eee; padding: 10px;">
                <strong><?= htmlspecialchars($step['step_name']) ?></strong> 
                (Applicable: <?= $step['is_applicable'] ? 'Yes' : 'No' ?>, 
                Completed: <?= $step['is_completed'] ? 'Yes' : 'No' ?>)
                
                <?php 
                    $stepSubtasks = isset($subtasks[$step['id']]) ? $subtasks[$step['id']] : [];
                ?>
                <?php if(!empty($stepSubtasks)): ?>
                    <ul style="margin-top: 5px; margin-bottom: 0;">
                        <?php foreach($stepSubtasks as $st): ?>
                            <li>
                                [<?= $st['is_completed'] ? 'X' : ' ' ?>] <?= htmlspecialchars($st['task_name']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No steps defined.</p>
    <?php endif; ?>

    <div class="section-title">MOM Entries</div>
    <?php if(!empty($moms)): ?>
        <?php foreach($moms as $mom): ?>
            <div style="margin-bottom: 10px; border-bottom: 1px dashed #ccc; padding-bottom: 5px;">
                <div style="font-size: 0.85em; color: #555;">
                    <?= htmlspecialchars($mom['created_at']) ?> by <?= htmlspecialchars($mom['user_name']) ?>
                </div>
                <div><?= nl2br(htmlspecialchars($mom['content'])) ?></div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No MOM entries.</p>
    <?php endif; ?>

</body>
</html>
