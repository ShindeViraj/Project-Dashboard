<nav id="sidebar" class="glass-sidebar active">
    <div class="p-4 pt-3">
        <div class="text-center mb-4">
            <img src="/assets/images/logo.png" alt="Squarewave Logo" style="max-width: 100%; height: auto; max-height: 80px;" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'200\' height=\'60\'%3E%3Crect width=\'200\' height=\'60\' fill=\'%23ccc\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'16\' fill=\'%23333\'%3ESquarewave%3C/text%3E%3C/svg%3E';">
        </div>
        
        <div class="user-info text-center mb-4 pb-3 border-bottom border-light">
            <h5 class="text-white mb-1"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User Name') ?></h5>
            <p class="text-light small mb-0"><?= htmlspecialchars($_SESSION['user_department'] ?? 'Department') ?></p>
            <span class="badge glass-card mt-1 px-3 py-1"><?= htmlspecialchars($_SESSION['user_role'] ?? 'Role') ?></span>
        </div>

        <ul class="list-unstyled components mb-5">
            <li>
                <a href="/dashboard"><i class="fas fa-chart-line me-2"></i> Master Dashboard</a>
            </li>
            <li>
                <a href="/dashboard/completed"><i class="fas fa-check-circle me-2"></i> Completed Projects</a>
            </li>
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <li>
                    <a href="#adminSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fas fa-cog me-2"></i> Administration</a>
                    <ul class="collapse list-unstyled" id="adminSubmenu">
                        <li>
                            <a href="/admin/users">Admin Dashboard</a>
                        </li>
                        <li>
                            <a href="/admin/steps">Admin Steps</a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>
        </ul>

        <div class="sidebar-footer text-center w-100 position-absolute bottom-0 p-3 pb-4 start-0">
            <a href="/logout" class="btn btn-glass btn-block w-100 text-danger" style="border-color: rgba(220,53,69,0.5);">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>
    </div>
</nav>
