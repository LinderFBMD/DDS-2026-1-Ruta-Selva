
<?php $__env->startSection('title', 'Quintas — Turismo Quillabamba'); ?>

<?php $__env->startSection('content'); ?>

    <h1 class="pagina-titulo">Quintas y lugares turísticos</h1>
    <p class="pagina-sub">Explora los mejores establecimientos de Quillabamba</p>

    <?php if($establecimientos->isEmpty()): ?>
        <div class="vacio">
            <span class="icono">🌿</span>
            <p>No hay establecimientos registrados aún.</p>
        </div>
    <?php else: ?>
        <div class="lista-quintas">
            <?php $__currentLoopData = $establecimientos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $est): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="fila-quinta">

                    
                    <div class="fila-foto">
                        <?php if($est->portada && $est->portada->url): ?>
                            
                            <img src="<?php echo e($est->portada->url_completa); ?>" alt="<?php echo e($est->nombre); ?>">
                        <?php else: ?>
                            🏡
                        <?php endif; ?>
                    </div>

                    
                    <div class="fila-info">
                        <a class="fila-nombre" href="<?php echo e(route('establecimientos.show', $est)); ?>">
                            <?php echo e($est->nombre ?? 'Sin nombre'); ?>

                        </a>

                        <?php if($est->descripcion): ?>
                            <p class="fila-desc"><?php echo e($est->descripcion); ?></p>
                        <?php endif; ?>

                        <div class="fila-meta">
                            <?php if($est->precio_entrada): ?>
                                <span>💰 S/ <?php echo e(number_format($est->precio_entrada, 2)); ?></span>
                            <?php endif; ?>
                            <?php if($est->horario_apertura && $est->horario_cierre): ?>
                                <span>🕐 <?php echo e(\Carbon\Carbon::parse($est->horario_apertura)->format('H:i')); ?> – <?php echo e(\Carbon\Carbon::parse($est->horario_cierre)->format('H:i')); ?></span>
                            <?php endif; ?>
                            <?php if($est->tiene_internet): ?>
                                <span class="badge-wifi">📶 WiFi</span>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="paginacion">
            <?php echo e($establecimientos->links()); ?>

        </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\linde\OneDrive\Desktop\RutaSelva\proyecto\resources\views/establecimientos/index.blade.php ENDPATH**/ ?>