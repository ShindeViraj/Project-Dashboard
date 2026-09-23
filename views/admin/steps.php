<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="mb-0 text-white">
                <span class="badge glass-card me-2 fs-5"><?= isset($steps) ? count($steps) : 0 ?></span>
                Manage Step Templates
            </h3>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <button type="button" class="btn btn-glass text-nowrap" data-bs-toggle="modal" data-bs-target="#addStepModal">
                <i class="fas fa-plus me-1"></i> Add New Step Template
            </button>
        </div>
    </div>

    <div class="glass-card p-4">
        <p class="text-light mb-4">These step templates are automatically added to every newly created project.</p>
        
        <div class="table-responsive">
            <table class="table table-glass table-hover w-100 mb-0">
                <thead>
                    <tr>
                        <th width="10%">Order</th>
                        <th>Step Name</th>
                        <th width="15%">Is Default</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($steps) && !empty($steps)): ?>
                        <?php foreach($steps as $step): ?>
                            <tr>
                                <td class="text-center fw-bold text-info"><?= $step['step_order'] ?></td>
                                <td class="fw-bold"><?= htmlspecialchars($step['step_name']) ?></td>
                                <td>
                                    <?php if($step['is_default']): ?>
                                        <span class="badge bg-success px-2 py-1"><i class="fas fa-check me-1"></i> Yes</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary px-2 py-1">No</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(!$step['is_default']): ?>
                                        <form action="/admin/steps/delete/<?= $step['id'] ?>" method="POST" class="confirm-submit" data-confirm-msg="Are you sure you want to delete this step template?">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                            <input type="hidden" name="id" value="<?= $step['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-outline-secondary disabled" title="System default steps cannot be deleted"><i class="fas fa-lock"></i></button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-light">No step templates found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4 text-center">
        <a href="/dashboard" class="btn btn-glass px-4 py-2"><i class="fas fa-chart-line me-2"></i> Go To Master Dashboard</a>
    </div>
</div>

<!-- Add Step Modal -->
<div class="modal fade" id="addStepModal" tabindex="-1" aria-labelledby="addStepModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content glass-card border-0" style="background: rgba(40, 40, 70, 0.9);">
            <div class="modal-header border-bottom border-light">
                <h5 class="modal-title text-white" id="addStepModalLabel">Add New Step Template</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/admin/steps/add" method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-light">Step Name</label>
                        <input type="text" name="step_name" class="form-control form-control-glass text-white" required placeholder="e.g., Final Review">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-light">Display Order</label>
                        <input type="number" name="step_order" class="form-control form-control-glass text-white" required min="1" value="<?= isset($steps) ? count($steps) + 1 : 1 ?>">
                        <small class="text-muted">Higher numbers appear lower in the list.</small>
                    </div>
                </div>
                <div class="modal-footer border-top border-light">
                    <button type="button" class="btn btn-outline-light btn-glass" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-glass text-white">Save Template</button>
                </div>
            </form>
        </div>
    </div>
</div>

