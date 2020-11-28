<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header">
        <?php echo e(trans('global.show')); ?> <?php echo e(trans('global.product.title')); ?>

    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>
                        <?php echo e(trans('global.product.fields.name')); ?>

                    </th>
                    <td>
                        <?php echo e($product->name); ?>

                    </td>
                </tr>
                <tr>
                    <th>
                        <?php echo e(trans('global.product.fields.description')); ?>

                    </th>
                    <td>
                        <?php echo $product->description; ?>

                    </td>
                </tr>
                <tr>
                    <th>
                        Actual Price
                    </th>
                    <td>
                        <?php echo e($product->price); ?>

                    </td>
                </tr>
                <tr>
                    <th>
                        Selling Price
                    </th>
                    <td>
                        <?php echo e($product->selling_price); ?>

                    </td>
                </tr>

            </tbody>
        </table>
        <div class="card mt-4">
            <div class="card-body">
                <div class="row ">
                    <div class="col-6">
                        <div style="margin-bottom: 10px;" class="row">
                            <div class="col-lg-12">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-variable">
                                    Add Product Variable
                                </button>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        Variable Quantity
                                    </th>
                                    <th>
                                        Variable Actual Price
                                    </th>
                                    <th>
                                        Variable Selling Price
                                    </th>
                                    <th>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $productVariables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$productVariable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <th>
                                        <?php echo e($productVariable->product_variable_options_name); ?>

                                    </th>
                                    <th>
                                        <?php echo e($productVariable->variable_original_price); ?>

                                    </th>
                                    <th>
                                        <?php echo e($productVariable->variable_selling_price); ?>

                                    </th>
                                    <th>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product_edit')): ?>
                                            <a class="btn btn-xs btn-info" href="<?php echo e(route('admin.products.editVariable', $productVariable->id)); ?>">
                                                <?php echo e(trans('global.edit')); ?>

                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product_delete')): ?>
                                            <form action="<?php echo e(route('admin.products.variableDestroy', $productVariable->id)); ?>" method="POST" onsubmit="return confirm('<?php echo e(trans('global.areYouSure')); ?>');" style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                                <input type="hidden" name="product_id" value="<?php echo e($productVariable->product_id); ?>">
                                                <input type="submit" class="btn btn-xs btn-danger" value="<?php echo e(trans('global.delete')); ?>">
                                            </form>
                                        <?php endif; ?>
                                    </th>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



                            </tbody>
                        </table>
                    </div>
                    <div class="col-6">
                        <div style="margin-bottom: 10px;" class="row">
                            <div class="col-lg-12">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-image">
                                    Add Product Images
                                </button>

                            </div>

                        </div>
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>
                                    Product Image
                                </th>
                                <th>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $productImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$productImage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo e($productImage->product_image_url ?? '#'); ?>" target="_blank"><?php echo e($productImage->product_image_url ?? ''); ?></a>
                                    </td>

                                    <th>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product_delete')): ?>
                                            <form action="<?php echo e(route('admin.products.imageDestroy', $productImage->id)); ?>" method="POST" onsubmit="return confirm('<?php echo e(trans('global.areYouSure')); ?>');" style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                                <input type="hidden" name="product_id" value="<?php echo e($productImage->product_id); ?>">
                                                <input type="submit" class="btn btn-xs btn-danger" value="<?php echo e(trans('global.delete')); ?>">
                                            </form>
                                        <?php endif; ?>
                                    </th>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<div class="modal fade show" id="modal-variable" style="display: none; padding-right: 16px;" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Product Variable</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="<?php echo e(route("admin.products.addVariable")); ?>" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <input hidden name="product_id" id="product_id" value="<?php echo e($product->id); ?>">
                        <div class="form-group col-4">
                            <label>Product Size</label>
                            <select class="form-control" name="product_variable_option_id" id="product_variable_option_id" >
                                <?php $__currentLoopData = $productVariableOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $productVariable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option  value="<?php echo e($productVariable->id); ?>"><?php echo e($productVariable->variable_name); ?> </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group col-4 <?php echo e($errors->has('variable_original_price') ? 'has-error' : ''); ?>">
                            <label for="variable_original_price">Actual Price*</label>
                            <input type="number" id="variable_original_price" name="variable_original_price" class="form-control" value="<?php echo e(old('variable_original_price', isset($product) ? $product->variable_original_price : '')); ?>" step="0.01" required>
                            <?php if($errors->has('variable_original_price')): ?>
                                <p class="help-block">
                                    <?php echo e($errors->first('variable_original_price')); ?>

                                </p>
                            <?php endif; ?>

                        </div>
                        <div class="form-group col-4 <?php echo e($errors->has('variable_selling_price') ? 'has-error' : ''); ?>">
                            <label for="variable_selling_price">Selling Price*</label>
                            <input type="number" id="variable_selling_price" name="variable_selling_price" class="form-control" value="<?php echo e(old('variable_selling_price', isset($product) ? $product->variable_selling_price : '')); ?>" step="0.01" required>
                            <?php if($errors->has('variable_selling_price')): ?>
                                <p class="help-block">
                                    <?php echo e($errors->first('variable_selling_price')); ?>

                                </p>
                            <?php endif; ?>

                        </div>
                        <div class="form-group col-md-4 <?php echo e($errors->has('quantity') ? 'has-error' : ''); ?>">
                            <label for="price">Quantity*</label>
                            <input type="number" id="quantity" name="quantity" required min="1" class="form-control" value="<?php echo e(old('quantity', isset($product) ? $product->quantity : '')); ?>" step="0.01">
                            <?php if($errors->has('quantity')): ?>
                                <p class="help-block">
                                    <?php echo e($errors->first('quantity')); ?>

                                </p>
                            <?php endif; ?>
                            <p class="helper-block">
                                <?php echo e(trans('global.product.fields.price_helper')); ?>

                            </p>
                        </div>
                    </div>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input class="btn btn-danger" type="submit" value="<?php echo e(trans('global.save')); ?>">
            </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade show" id="modal-image" style="display: none; padding-right: 16px;" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Product Variable</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="<?php echo e(route("admin.products.addImage")); ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <input hidden name="product_id" id="product_id" value="<?php echo e($product->id); ?>">
                        <div class="form-group <?php echo e($errors->has('product_image') ? 'has-error' : ''); ?>">
                            <label for="product_image">Category Image Url</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" accept=".jpeg,.bmp,.png,.jpg" class="custom-file-input" id="product_image" name="product_image[]" required multiple>
                                    <label class="custom-file-label" for="product_image">Choose file</label>
                                </div>
                            </div>
                            <?php if($errors->has('product_image')): ?>
                                <p class="help-block">
                                    <?php echo e($errors->first('product_image')); ?>

                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input class="btn btn-danger" type="submit" value="<?php echo e(trans('global.save')); ?>">
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.3\htdocs\Ecommerce\resources\views/admin/products/show.blade.php ENDPATH**/ ?>