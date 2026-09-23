<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="glass-card p-5">
                <h3 class="mb-4 text-white border-bottom border-light pb-2"><i class="fas fa-folder-plus me-2"></i> Add New Project</h3>
                
                <form action="/project/store" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                    
                    <div class="mb-3">
                        <label for="project_name" class="form-label text-light fw-bold">Project Name</label>
                        <input type="text" class="form-control form-control-glass" id="project_name" name="project_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="company_name" class="form-label text-light fw-bold">Company Name</label>
                        <input type="text" class="form-control form-control-glass" id="company_name" name="company_name" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="issue_date" class="form-label text-light fw-bold">Issue Date</label>
                            <input type="date" class="form-control form-control-glass" id="issue_date" name="issue_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label text-light fw-bold">Start Date</label>
                            <input type="date" class="form-control form-control-glass" id="start_date" name="start_date" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="problem_statement" class="form-label text-light fw-bold">Problem Statement</label>
                        <textarea class="form-control form-control-glass" id="problem_statement" name="problem_statement" rows="4" required></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="team_members" class="form-label text-light fw-bold">Select Team Members</label>
                        <select class="form-select form-select-glass" id="team_members" name="team_members[]" multiple size="4">
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?> (<?= htmlspecialchars($u['department']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-light">Hold CTRL (or CMD) to select multiple members.</small>
                    </div>
                    <div class="d-flex justify-content-end mt-4 pt-3 border-top border-light">
                        <a href="/dashboard" class="btn btn-outline-light me-2 btn-glass">Cancel</a>
                        <button type="submit" class="btn btn-glass fw-bold text-white px-4 shadow-sm" style="background: rgba(40,167,69,0.3);">
                            <i class="fas fa-save me-2"></i> Save Project
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

