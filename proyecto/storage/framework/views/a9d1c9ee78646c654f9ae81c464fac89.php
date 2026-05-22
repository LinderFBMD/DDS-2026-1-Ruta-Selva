<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Turismo Quillabamba'); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('app.css')); ?>">
</head>
<body>

    <nav class="nav">
        <a class="nav-logo" href="<?php echo e(route('establecimientos.index')); ?>">
            <span class="titulo">🌿 Turismo Quillabamba</span>
            <span class="subtitulo">La Ciudad del Eterno Verano</span>
        </a>
    </nav>

    <div class="main">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <footer class="footer">
        © <?php echo e(date('Y')); ?> Turismo Quillabamba — Cusco, Perú
    </footer>

</body>
</html><?php /**PATH C:\Users\linde\OneDrive\Desktop\RutaSelva\proyecto\resources\views/layouts/app.blade.php ENDPATH**/ ?>