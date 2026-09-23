<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="mb-0 text-white">
                <span class="badge glass-card me-2 fs-5"><?= isset($users) ? count($users) : 0 ?></span>
                Users Management
            </h3>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0 d-flex justify-content-md-end align-items-center">
            <div class="input-group w-50 me-3">
                <span class="input-group-text glass-card border-0"><i class="fas fa-search text-white"></i></span>
                <input type="text" id="tableSearch" class="form-control form-control-glass border-start-0" placeholder="Search users...">
            </div>
            <button type="button" class="btn btn-glass text-nowrap" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-plus me-1"></i> Add New User
            </button>
        </div>
    </div>

    <div class="glass-card p-4">
        <div class="table-responsive">
            <table class="table table-glass table-hover w-100 mb-0">
                <thead>
                    <tr>
                        <th width="8%">Sr.No</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Role</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($users) && !empty($users)): ?>
                        <?php $i = 1; foreach($users as $u): ?>
                            <tr>
                                <td class="text-center"><?= $i++ ?></td>
                                <td class="fw-bold"><?= htmlspecialchars($u['name']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><?= htmlspecialchars($u['department']) ?></td>
                                <td>
                                    <span class="badge <?= $u['role'] === 'admin' ? 'bg-danger' : 'glass-card' ?> px-2 py-1">
                                        <?= htmlspecialchars(ucfirst($u['role'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($u['id'] != $_SESSION['user_id']): ?>
                                        <form action="/admin/users/delete/<?= $u['id'] ?>" method="POST" class="confirm-submit" data-confirm-msg="Are you sure you want to delete this user?">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted small">Current</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-light">No users found.</td>
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

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content glass-card border-0" style="background: rgba(40, 40, 70, 0.9);">
            <div class="modal-header border-bottom border-light">
                <h5 class="modal-title text-white" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/admin/users/add" method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-light">Full Name</label>
                        <input type="text" name="name" class="form-control form-control-glass text-white" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-light">Email Address</label>
                        <input type="email" name="email" class="form-control form-control-glass text-white" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-light">Password</label>
                        <input type="password" name="password" class="form-control form-control-glass text-white" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-light">Department</label>
                        <input type="text" name="department" class="form-control form-control-glass text-white" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-light">Role</label>
                        <select name="role" class="form-select form-select-glass text-white" required>
                            <option value="user" style="color:#000;">User</option>
                            <option value="admin" style="color:#000;">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top border-light">
                    <button type="button" class="btn btn-outline-light btn-glass" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-glass text-white">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

