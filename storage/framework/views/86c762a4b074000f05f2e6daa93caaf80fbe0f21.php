<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?php echo e(__('messages.banner_celebrities')); ?></h2>
        <a href="<?php echo e(route('banner-celebrities.create')); ?>" class="btn btn-primary">
            <?php echo e(__('messages.add_new')); ?>

        </a>
    </div>


    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?php echo e(__('messages.id')); ?></th>
                        <th><?php echo e(__('messages.photo')); ?></th>
                        <th><?php echo e(__('messages.celebrity')); ?></th>
                        <th><?php echo e(__('messages.created_at')); ?></th>
                        <th><?php echo e(__('messages.actions')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $bannerCelebrities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($banner->id); ?></td>
                            <td>
                                <img src="<?php echo e(asset('assets/admin/uploads/' . $banner->photo)); ?>" 
                                     alt="Banner" 
                                     style="width: 100px; height: 60px; object-fit: cover;">
                            </td>
                            <td><?php echo e($banner->celebrity->name ?? __('messages.none')); ?></td>
                            <td><?php echo e($banner->created_at->format('Y-m-d')); ?></td>
                            <td>
                                <a href="<?php echo e(route('banner-celebrities.edit', $banner)); ?>" 
                                   class="btn btn-sm btn-warning">
                                    <?php echo e(__('messages.edit')); ?>

                                </a>
                                <form action="<?php echo e(route('banner-celebrities.destroy', $banner)); ?>" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('<?php echo e(__('messages.confirm_delete')); ?>')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <?php echo e(__('messages.delete')); ?>

                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center"><?php echo e(__('messages.no_data')); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center">
                <?php echo e($bannerCelebrities->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\storati\resources\views/admin/banner_celebrities/index.blade.php ENDPATH**/ ?>