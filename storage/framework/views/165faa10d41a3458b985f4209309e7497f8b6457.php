<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?php echo e(__('messages.Products')); ?></h3>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product-add')): ?>
                        <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> <?php echo e(__('messages.Add_Product')); ?>

                        </a>
                    <?php endif; ?>
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo e(__('messages.Image')); ?></th>
                                    <th><?php echo e(__('messages.Name')); ?></th>
                                    <th><?php echo e(__('messages.Price')); ?></th>
                                    <th><?php echo e(__('messages.Discount')); ?></th>
                                    <th><?php echo e(__('messages.Category')); ?></th>
                                    <th><?php echo e(__('messages.Brand')); ?></th>
                                    <th><?php echo e(__('messages.Shop')); ?></th>
                                    <th><?php echo e(__('messages.Actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration + ($products->currentPage() - 1) * $products->perPage()); ?></td>
                                        <td>
                                            <?php if($product->images->first()): ?>
                                                <img src="<?php echo e(asset('assets/admin/uploads/'. $product->images->first()->photo)); ?>" 
                                                     alt="<?php echo e(app()->getLocale() == 'ar' ? $product->name_ar : $product->name_en); ?>" 
                                                     class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 60px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo e(app()->getLocale() == 'ar' ? $product->name_ar : $product->name_en); ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <?php echo e(Str::limit(app()->getLocale() == 'ar' ? $product->description_ar : $product->description_en, 50)); ?>

                                            </small>
                                        </td>
                                        <td>
                                            <span class="fw-bold">$<?php echo e(number_format($product->price, 2)); ?></span>
                                            <?php if($product->price_after_discount): ?>
                                                <br>
                                                <small class="text-success">
                                                    <?php echo e(__('messages.After_Discount')); ?>: $<?php echo e(number_format($product->price_after_discount, 2)); ?>

                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($product->discount_percentage): ?>
                                                <span class="badge bg-success"><?php echo e($product->discount_percentage); ?>%</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><?php echo e(__('messages.No_Discount')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($product->category): ?>
                                                <?php echo e(app()->getLocale() == 'ar' ? $product->category->name_ar : $product->category->name_en); ?>

                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(__('messages.No_Category')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($product->brand): ?>
                                                <?php echo e(app()->getLocale() == 'ar' ? $product->brand->name_ar : $product->brand->name_en); ?>

                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(__('messages.No_Brand')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($product->shop): ?>
                                                <?php echo e(app()->getLocale() == 'ar' ? $product->shop->name_ar : $product->shop->name_en); ?>

                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(__('messages.No_Shop')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('products.show', $product)); ?>" 
                                                   class="btn btn-sm btn-info" title="<?php echo e(__('messages.View')); ?>">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product-edit')): ?>
                                                    <a href="<?php echo e(route('products.edit', $product)); ?>" 
                                                       class="btn btn-sm btn-warning" title="<?php echo e(__('messages.Edit')); ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                              
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="9" class="text-center"><?php echo e(__('messages.No_Products_Found')); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        <?php echo e($products->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\storati\resources\views/admin/products/index.blade.php ENDPATH**/ ?>