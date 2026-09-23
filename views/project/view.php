<?php
$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
$isLeader = isset($project['leader_id']) && $project['leader_id'] == $_SESSION['user_id'];
$isLeaderOrAdmin = $isAdmin || $isLeader;
?>

<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-white"><?= htmlspecialchars($project['project_name']) ?></h3>
        <div>
            <a href="/project/<?= htmlspecialchars($project['id']) ?>/report" target="_blank" class="btn btn-glass me-2"><i class="fas fa-print me-1"></i> Generate Report</a>
            <a href="/dashboard" class="btn btn-glass"><i class="fas fa-home me-1"></i> Main Dashboard</a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-4 mb-4">
            <!-- Project Info -->
            <div class="glass-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center border-bottom border-light pb-2 mb-3">
                    <h5 class="text-white mb-0">Project Details</h5>
                    <?php if($_SESSION['user_role'] === 'admin'): ?>
                        <form action="/project/<?= htmlspecialchars($project['id']) ?>/delete" method="POST" class="confirm-submit" data-confirm-msg="Are you sure you want to completely delete this project and all its data? This cannot be undone.">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i> Delete Project</button>
                        </form>
                    <?php endif; ?>
                </div>
                <p class="mb-1 text-light small">Company Name</p>
                <p class="text-white fw-bold mb-3"><?= htmlspecialchars($project['company_name']) ?></p>
                
                <p class="mb-1 text-light small">Issue Date</p>
                <p class="text-white fw-bold mb-3"><?= htmlspecialchars($project['issue_date']) ?></p>
                
                <p class="mb-1 text-light small">Start Date</p>
                <p class="text-white fw-bold mb-3"><?= htmlspecialchars($project['start_date']) ?></p>
                
                <p class="mb-1 text-light small">Problem Statement</p>
                <div class="p-3 glass-card-dark text-white rounded small mb-3">
                    <?= nl2br(htmlspecialchars($project['problem_statement'])) ?>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
    <p class="mb-0 text-light small">Overall Progress</p>
    <span class="text-white fw-bold small"><?= htmlspecialchars($project['progress'] ?? 0) ?>%</span>
</div>
<div class="progress progress-glass mb-1">
    <div class="progress-bar" role="progressbar" style="width: <?= htmlspecialchars($project['progress'] ?? 0) ?>%;" aria-valuenow="<?= htmlspecialchars($project['progress'] ?? 0) ?>" aria-valuemin="0" aria-valuemax="100"></div>
</div>
                </div>

                <?php if ($isAdmin): ?>
                    <form action="/project/<?= htmlspecialchars($project['id']) ?>/toggle-status" method="POST" enctype="multipart/form-data" class="mb-2">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                        
                        <div class="mb-3">
                            <div class="form-check form-switch mb-1">
                                <input class="form-check-input" type="checkbox" id="po_status" name="po_status" value="1" <?= ($project['po_status'] ?? 0) ? 'checked' : '' ?>>
                                <label class="form-check-label text-light" for="po_status">PO Status</label>
                            </div>
                            <?php if(!empty($project['po_document_path'])): ?>
                                <a href="<?= htmlspecialchars($project['po_document_path']) ?>" target="_blank" class="small text-info ms-4 d-block"><i class="fas fa-file-pdf"></i> View PO Document</a>
                            <?php endif; ?>
                            <input type="file" name="po_document" class="form-control form-control-sm form-control-glass ms-4 mt-1" style="width: 80%; font-size: 0.75rem;" accept=".pdf,.png,.jpg,.jpeg">
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch mb-1">
                                <input class="form-check-input" type="checkbox" id="tax_invoice_status" name="tax_invoice_status" value="1" <?= ($project['tax_invoice_status'] ?? 0) ? 'checked' : '' ?>>
                                <label class="form-check-label text-light" for="tax_invoice_status">Tax Invoice Status</label>
                            </div>
                            <?php if(!empty($project['tax_invoice_document_path'])): ?>
                                <a href="<?= htmlspecialchars($project['tax_invoice_document_path']) ?>" target="_blank" class="small text-info ms-4 d-block"><i class="fas fa-file-pdf"></i> View Tax Invoice</a>
                            <?php endif; ?>
                            <input type="file" name="tax_invoice_document" class="form-control form-control-sm form-control-glass ms-4 mt-1" style="width: 80%; font-size: 0.75rem;" accept=".pdf,.png,.jpg,.jpeg">
                        </div>
                        
                        <button type="submit" class="btn btn-sm btn-glass text-white w-100 mt-2">Save Status & Documents</button>
                    </form>
                <?php else: ?>
                    <div class="mb-2">
                        <p class="mb-1 text-light small">
                            PO Status: <span class="badge <?= ($project['po_status'] ?? 0) ? 'bg-success' : 'bg-secondary' ?>"><?= ($project['po_status'] ?? 0) ? 'Yes' : 'No' ?></span>
                            <?php if(!empty($project['po_document_path'])): ?>
                                <a href="<?= htmlspecialchars($project['po_document_path']) ?>" target="_blank" class="ms-2 text-info"><i class="fas fa-file-pdf"></i></a>
                            <?php endif; ?>
                        </p>
                        <p class="mb-1 text-light small">
                            Tax Invoice: <span class="badge <?= ($project['tax_invoice_status'] ?? 0) ? 'bg-success' : 'bg-secondary' ?>"><?= ($project['tax_invoice_status'] ?? 0) ? 'Yes' : 'No' ?></span>
                            <?php if(!empty($project['tax_invoice_document_path'])): ?>
                                <a href="<?= htmlspecialchars($project['tax_invoice_document_path']) ?>" target="_blank" class="ms-2 text-info"><i class="fas fa-file-pdf"></i></a>
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endif; ?>

                <?php if ($isAdmin && ($project['progress'] ?? 0) == 100 && empty($project['completed_at'])): ?>
                    <div class="mt-4 border-top border-light pt-3">
                        <form action="/project/<?= htmlspecialchars($project['id']) ?>/complete" method="POST" class="confirm-submit" data-confirm-msg="Are you sure you want to mark this project as completed?">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                            <button type="submit" class="btn btn-success w-100 shadow-sm"><i class="fas fa-check-circle me-1"></i> Mark Project Complete</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Team Members -->
            <div class="glass-card p-4 mb-4">
                <h5 class="text-white border-bottom border-light pb-2 mb-3">Project Team</h5>
                <ul class="list-group list-group-flush mb-3 rounded overflow-hidden">
                    <?php if(!empty($team)): ?>
                        <?php foreach($team as $member): ?>
                            <li class="list-group-item bg-transparent text-white border-light d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-user-circle me-2"></i> <?= htmlspecialchars($member['name'] ?? 'User') ?>
                                    <span class="badge glass-card ms-1" style="font-size: 0.6rem;"><?= !empty($member['is_leader']) ? 'Leader' : 'Member' ?></span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <?php if($isLeaderOrAdmin && empty($member['is_leader'])): ?>
                                        <form action="/project/<?= htmlspecialchars($project['id']) ?>/team/<?= $member['id'] ?>/toggle-access" method="POST" class="me-3 m-0">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" onchange="this.form.submit()" <?= !empty($member['can_edit']) ? 'checked' : '' ?>>
                                                <label class="form-check-label text-light" style="font-size: 0.8rem;">Can Edit</label>
                                            </div>
                                        </form>
                                        <form action="/project/<?= htmlspecialchars($project['id']) ?>/team/<?= $member['id'] ?>/remove" method="POST" class="m-0 confirm-submit" data-confirm-msg="Remove member?">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                            <button type="submit" class="btn btn-sm btn-link text-danger p-0"><i class="fas fa-trash"></i></button>
                                        </form>
                                    <?php elseif (empty($member['is_leader'])): ?>
                                        <span class="text-light" style="font-size: 0.8rem;"><?= !empty($member['can_edit']) ? 'Can Edit' : 'Read Only' ?></span>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item bg-transparent text-light border-0">No team members assigned.</li>
                    <?php endif; ?>
                </ul>

                <?php if($isLeaderOrAdmin): ?>
                    <form action="/project/<?= htmlspecialchars($project['id']) ?>/team/add" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                        <div class="input-group input-group-sm mb-2">
                            <select name="user_id" class="form-select form-select-glass" required>
                                <option value="" selected disabled>Select user...</option>
                                <?php 
                                $userModel = new \Models\User();
                                $allUsers = $userModel->findAll();
                                if(!empty($allUsers)): foreach($allUsers as $u): ?>
                                    <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?> (<?= htmlspecialchars($u['department']) ?>)</option>
                                <?php endforeach; endif; ?>
                            </select>
                            <button type="submit" class="btn btn-glass text-success"><i class="fas fa-plus"></i></button>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="can_edit" id="can_edit_new" value="1">
                            <label class="form-check-label text-light" for="can_edit_new">Allow user to edit project information</label>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
            
            <!-- MOM Section -->
            <div class="glass-card p-4">
                <h5 class="text-white border-bottom border-light pb-2 mb-3">MOM Entries</h5>
                <div class="mb-3" style="max-height: 200px; overflow-y: auto;">
                    <?php if(!empty($momEntries)): ?>
                        <?php foreach($momEntries as $mom): ?>
                            <div class="glass-card-dark p-2 mb-2 rounded">
                                <div class="d-flex justify-content-between">
                                    <small class="text-info fw-bold"><?= htmlspecialchars($mom['created_at']) ?></small>
                                    <small class="text-light"><?= htmlspecialchars($mom['author_name'] ?? 'User') ?></small>
                                </div>
                                <p class="mb-0 text-white small mt-1"><?= nl2br(htmlspecialchars($mom['content'])) ?></p>
                                <?php if(!empty($mom['attachment_path'])): ?>
                                    <div class="mt-2 text-end">
                                        <a href="<?= htmlspecialchars($mom['attachment_path']) ?>" target="_blank" class="small text-info text-decoration-none"><i class="fas fa-paperclip"></i> View Attachment</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-light small">No MOM entries.</p>
                    <?php endif; ?>
                </div>
                
                <?php if($isLeaderOrAdmin): ?>
                    <form action="/project/<?= htmlspecialchars($project['id']) ?>/mom" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                        <textarea class="form-control form-control-glass mb-2 text-white" name="content" rows="2" placeholder="Add MOM note..." required></textarea>
                        <input type="file" name="mom_attachment" class="form-control form-control-sm form-control-glass mb-2" accept=".pdf,.png,.jpg,.jpeg">
                        <button type="submit" class="btn btn-sm btn-glass w-100">Add MOM</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Column: Steps -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="text-white mb-0">Project Steps</h4>
            </div>
            
            <?php if(isset($steps) && !empty($steps)): ?>
                <?php foreach($steps as $step): ?>
                    <div class="glass-card mb-3 p-3 <?= ($step['is_applicable'] && $step['is_completed']) ? 'border-success' : '' ?>" style="border-width: 2px;">
                        <div class="row align-items-center">
                            <div class="col-md-5">
                                <h5 class="text-white mb-1"><?= htmlspecialchars($step['step_name']) ?></h5>
                            </div>
                            <div class="col-md-7 d-flex justify-content-md-end gap-3 align-items-center">
                                <?php if($isLeaderOrAdmin): ?>
                                    <form action="/project/<?= htmlspecialchars($project['id']) ?>/step/<?= $step['id'] ?>/toggle" method="POST" class="d-flex align-items-center m-0">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                        
                                        <div class="form-check form-switch me-3">
                                            <input class="form-check-input" type="checkbox" id="app_<?= $step['id'] ?>" name="is_applicable" value="1" <?= $step['is_applicable'] ? 'checked' : '' ?> onchange="this.form.submit()">
                                            <label class="form-check-label text-light small" for="app_<?= $step['id'] ?>">Applicable</label>
                                        </div>
                                        
                                        <?php if($step['is_applicable']): ?>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="comp_<?= $step['id'] ?>" name="is_completed" value="1" <?= $step['is_completed'] ? 'checked' : '' ?> onchange="this.form.submit()">
                                                <label class="form-check-label text-light small" for="comp_<?= $step['id'] ?>">Completed</label>
                                            </div>
                                        <?php endif; ?>
                                    </form>
                                <?php else: ?>
                                    <div>
                                        <span class="badge <?= $step['is_applicable'] ? 'bg-info' : 'bg-secondary' ?> me-2">
                                            <?= $step['is_applicable'] ? 'Applicable' : 'N/A' ?>
                                        </span>
                                        <?php if($step['is_applicable']): ?>
                                            <span class="badge <?= $step['is_completed'] ? 'bg-success' : 'bg-warning text-dark' ?>">
                                                <?= $step['is_completed'] ? 'Completed' : 'Pending' ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <button class="btn btn-sm btn-glass text-white" type="button" data-bs-toggle="collapse" data-bs-target="#subtasks_<?= $step['id'] ?>">
                                    <i class="fas fa-list"></i> Sub-tasks
                                </button>
                            </div>
                        </div>

                        <!-- Subtasks Collapse -->
                        <div class="collapse mt-3" id="subtasks_<?= $step['id'] ?>">
                            <div class="glass-card-dark p-3 rounded">
                                <?php 
                                    $stepSubtasks = isset($subtasks[$step['id']]) ? $subtasks[$step['id']] : [];
                                ?>
                                
                                <ul class="list-group list-group-flush mb-3 rounded">
                                    <?php if(!empty($stepSubtasks)): ?>
                                        <?php foreach($stepSubtasks as $st): ?>
                                            <li class="list-group-item bg-transparent text-white border-light d-flex justify-content-between align-items-center py-2 px-1">
                                                <div class="d-flex align-items-center">
                                                    <?php if($isLeaderOrAdmin): ?>
                                                        <form action="/project/<?= htmlspecialchars($project['id']) ?>/subtask/<?= $st['id'] ?>/toggle" method="POST" class="m-0 me-2">
                                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                                            <input type="checkbox" class="form-check-input m-0" <?= $st['is_completed'] ? 'checked' : '' ?> onchange="this.form.submit()">
                                                        </form>
                                                    <?php else: ?>
                                                        <i class="fas <?= $st['is_completed'] ? 'fa-check-square text-success' : 'fa-square text-secondary' ?> me-2"></i>
                                                    <?php endif; ?>
                                                    
                                                    <span class="<?= $st['is_completed'] ? 'text-decoration-line-through text-muted' : '' ?>">
                                                        <?= htmlspecialchars($st['task_description']) ?>
                                                    </span>
                                                </div>
                                                
                                                <?php if($isLeaderOrAdmin): ?>
                                                    <form action="/project/<?= htmlspecialchars($project['id']) ?>/subtask/<?= $st['id'] ?>/delete" method="POST" class="m-0 confirm-submit">
                                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                                        <button type="submit" class="btn btn-sm text-danger p-0"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="list-group-item bg-transparent text-light border-0 py-1 px-1 small">No sub-tasks.</li>
                                    <?php endif; ?>
                                </ul>

                                <?php if($isLeaderOrAdmin && $step['is_applicable']): ?>
                                    <form action="/project/<?= htmlspecialchars($project['id']) ?>/step/<?= $step['id'] ?>/subtask" method="POST" class="d-flex">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                        <input type="text" name="task_name" class="form-control form-control-sm form-control-glass me-2" placeholder="New sub-task..." required>
                                        <button type="submit" class="btn btn-sm btn-glass text-success"><i class="fas fa-plus"></i></button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-warning glass-card text-white">No steps defined for this project.</div>
            <?php endif; ?>

            <?php if($isLeaderOrAdmin): ?>
                <div class="glass-card p-3 mt-4" style="border-width: 2px; border-style: dashed;">
                    <h6 class="text-white mb-2"><i class="fas fa-plus-circle me-1"></i> Add Custom Step</h6>
                    <form action="/project/<?= htmlspecialchars($project['id']) ?>/step/add" method="POST" class="d-flex">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                        <input type="text" name="step_name" class="form-control form-control-sm form-control-glass me-2" placeholder="Enter new step name..." required>
                        <button type="submit" class="btn btn-sm btn-glass text-success text-nowrap">Add Step</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>




