<header class="glass-card mb-4 p-3 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
        <button type="button" id="sidebarCollapse" class="btn btn-glass me-3">
            <i class="fas fa-bars"></i>
        </button>
        <h2 class="h4 mb-0 m-0 text-white"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></h2>
    </div>
    <div class="datetime-display text-white">
        <i class="far fa-clock me-1"></i> <span id="liveClock">Loading...</span>
    </div>
</header>
