<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Squarewave Automation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="glass-bg d-flex align-items-center justify-content-center" style="height: 100vh; overflow: hidden;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="glass-card p-5 text-center">
                    <img src="/assets/images/logo.png" alt="Logo" class="mb-4" style="height: 60px;" onerror="this.style.display='none';">
                    <h3 class="mb-4 text-white">Squarewave Automation</h3>
                    
                    <?php $flashError = \Core\Session::getFlash('error'); if ($flashError): ?>
                        <div class="alert alert-danger alert-dismissible fade show glass-card" style="padding: 10px;" role="alert">
                            <?= htmlspecialchars($flashError) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 12px;"></button>
                        </div>
                    <?php endif; ?>

                    <form action="/login" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                        <div class="mb-3 text-start">
                            <label for="email" class="form-label text-light">Email address</label>
                            <div class="input-group">
                                <span class="input-group-text glass-card border-0"><i class="fas fa-envelope text-white"></i></span>
                                <input type="email" class="form-control form-control-glass border-start-0" id="email" name="email" required placeholder="Enter email">
                            </div>
                        </div>
                        <div class="mb-4 text-start">
                            <label for="password" class="form-label text-light">Password</label>
                            <div class="input-group">
                                <span class="input-group-text glass-card border-0"><i class="fas fa-lock text-white"></i></span>
                                <input type="password" class="form-control form-control-glass border-start-0" id="password" name="password" required placeholder="Password">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-glass w-100 py-2 fw-bold text-uppercase shadow-sm">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

