<!-- Custom toast component -->
<div class="my-toast show">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <span><?= htmlspecialchars($_SESSION['error']); ?></span>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php elseif (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <span><?= htmlspecialchars($_SESSION['success']); ?></span>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
</div>