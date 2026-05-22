
<?php $__env->startSection('title', ($establecimiento->nombre ?? 'Detalle') . ' — Turismo Quillabamba'); ?>

<?php $__env->startSection('content'); ?>

    <a class="btn-volver" href="<?php echo e(route('establecimientos.index')); ?>">← Volver al listado</a>

    <h1 class="det-nombre"><?php echo e($establecimiento->nombre ?? 'Sin nombre'); ?></h1>

    <div class="det-badges">
        <?php if($establecimiento->precio_entrada): ?>
            <span class="det-badge">💰 S/ <?php echo e(number_format($establecimiento->precio_entrada, 2)); ?> entrada</span>
        <?php endif; ?>
        <?php if($establecimiento->horario_apertura && $establecimiento->horario_cierre): ?>
            <span class="det-badge">
                🕐 <?php echo e(\Carbon\Carbon::parse($establecimiento->horario_apertura)->format('H:i')); ?>

                –
                <?php echo e(\Carbon\Carbon::parse($establecimiento->horario_cierre)->format('H:i')); ?>

            </span>
        <?php endif; ?>
        <?php if($establecimiento->tiene_internet): ?>
            <span class="det-badge wifi">📶 WiFi disponible</span>
        <?php endif; ?>
    </div>

    
    <p class="seccion-titulo">Fotos</p>
    <div class="fotos-grid">
        <?php if($establecimiento->fotos->isNotEmpty()): ?>
            <?php $__currentLoopData = $establecimiento->fotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $foto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="foto-item">
                    <?php if($foto->url): ?>
                        <img src="<?php echo e($foto->url_completa); ?>" alt="Foto de <?php echo e($establecimiento->nombre); ?>">
                    <?php else: ?>
                        🌿
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            
            <div class="foto-item">🏡</div>
            <div class="foto-item">🌿</div>
            <div class="foto-item">🌄</div>
            <div class="foto-item">🌳</div>
        <?php endif; ?>
    </div>

    
    <?php if($establecimiento->descripcion): ?>
        <p class="seccion-titulo">Descripción</p>
        <p class="descripcion"><?php echo e($establecimiento->descripcion); ?></p>
    <?php endif; ?>

    
    <p class="seccion-titulo">Información</p>
    <div class="datos-grid">
        <?php if($establecimiento->precio_entrada): ?>
            <div class="dato-item">
                <span class="dato-label">💰 Precio entrada</span>
                <span class="dato-valor">S/ <?php echo e(number_format($establecimiento->precio_entrada, 2)); ?></span>
            </div>
        <?php endif; ?>
        <?php if($establecimiento->horario_apertura): ?>
            <div class="dato-item">
                <span class="dato-label">🕐 Apertura</span>
                <span class="dato-valor"><?php echo e(\Carbon\Carbon::parse($establecimiento->horario_apertura)->format('H:i')); ?></span>
            </div>
        <?php endif; ?>
        <?php if($establecimiento->horario_cierre): ?>
            <div class="dato-item">
                <span class="dato-label">🕔 Cierre</span>
                <span class="dato-valor"><?php echo e(\Carbon\Carbon::parse($establecimiento->horario_cierre)->format('H:i')); ?></span>
            </div>
        <?php endif; ?>
        <div class="dato-item">
            <span class="dato-label">📶 Internet</span>
            <span class="dato-valor"><?php echo e($establecimiento->tiene_internet ? 'Disponible' : 'No disponible'); ?></span>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\linde\OneDrive\Desktop\RutaSelva\proyecto\resources\views/establecimientos/show.blade.php ENDPATH**/ ?>