<footer class="glass-card mt-4 p-3 d-flex justify-content-between align-items-center">
    <div class="footer-nav">
        <?php if(isset($showBackButton) && $showBackButton): ?>
            <a href="javascript:history.back()" class="btn btn-sm btn-glass"><i class="fas fa-arrow-left me-1"></i> Back</a>
        <?php endif; ?>
        <?php if(isset($footerButtons)): ?>
            <?php foreach($footerButtons as $btn): ?>
                <a href="<?= htmlspecialchars($btn['url']) ?>" class="btn btn-sm btn-glass me-2">
                    <?php if(isset($btn['icon'])): ?><i class="<?= htmlspecialchars($btn['icon']) ?> me-1"></i><?php endif; ?>
                    <?= htmlspecialchars($btn['text']) ?>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="text-light small text-end">
        &copy; <?= date('Y') ?> Squarewave Automation Technologies Private Limited.<br>All rights reserved.
    </div>
</footer>
