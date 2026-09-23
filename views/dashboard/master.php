<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="mb-0 text-white">
                <span class="badge glass-card me-2 fs-5"><?= isset($projects) ? count($projects) : 0 ?></span>
                Projects
            </h3>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0 d-flex justify-content-md-end align-items-center">
            <div class="input-group w-50 me-3">
                <span class="input-group-text glass-card border-0"><i class="fas fa-search text-white"></i></span>
                <input type="text" id="tableSearch" class="form-control form-control-glass border-start-0" placeholder="Search projects...">
            </div>
            <a href="/project/create" class="btn btn-glass text-nowrap"><i class="fas fa-plus me-1"></i> Add New Project</a>
        </div>
    </div>

    <div class="glass-card p-4">
        <div class="table-responsive">
            <table class="table table-glass table-hover w-100">
                <thead>
                    <tr>
                        <th width="8%">Sr.No</th>
                        <th>Project Name</th>
                        <th width="15%">Start Date</th>
                        <th width="25%">Progress</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($projects) && !empty($projects)): ?>
                        <?php $i = 1; foreach($projects as $project): ?>
                            <tr onclick="window.location='/project/<?= htmlspecialchars($project['id']) ?>'" style="cursor: pointer;">
                                <td class="text-center"><?= $i++ ?></td>
                                <td class="fw-bold"><?= htmlspecialchars($project['project_name']) ?></td>
                                <td><?= htmlspecialchars($project['start_date']) ?></td>
                                <td>
                                    <div class="progress progress-glass mt-1">
                                        <div class="progress-bar" role="progressbar" style="width: <?= htmlspecialchars($project['progress'] ?? 0) ?>%;" aria-valuenow="<?= htmlspecialchars($project['progress'] ?? 0) ?>" aria-valuemin="0" aria-valuemax="100"><?= htmlspecialchars($project['progress'] ?? 0) ?>%</div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-light">No active projects found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4 text-center">
        <a href="/dashboard/completed" class="btn btn-glass px-4 py-2"><i class="fas fa-check-double me-2"></i> Go To Completed Projects</a>
    </div>
</div>
