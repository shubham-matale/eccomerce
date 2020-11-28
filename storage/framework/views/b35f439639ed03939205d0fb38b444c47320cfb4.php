<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header">
        <?php echo e(trans('global.create')); ?> Masala Ingradient
    </div>

    <div class="card-body">
        <form action="<?php echo e(route("admin.masalaIngradients.store")); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="form-group col-md-3 <?php echo e($errors->has('name') ? 'has-error' : ''); ?>">
                    <label for="name"><?php echo e(trans('global.product.fields.name')); ?>*</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo e(old('name', isset($product) ? $product->name : '')); ?>">
                    <?php if($errors->has('name')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('name')); ?>

                        </p>
                    <?php endif; ?>
                    <p class="helper-block">
                        <?php echo e(trans('global.product.fields.name_helper')); ?>

                    </p>
                </div>
                <div class="form-group col-md-3 <?php echo e($errors->has('hindiText') ? 'has-error' : ''); ?>">
                    <label for="hindiText">Hindi Text*</label>
                    <input type="text" id="hindiText" name="hindiText" class="form-control" value="<?php echo e(old('hindiText', isset($languageData) ? $languageData->hindiText : '')); ?>">
                    <?php if($errors->has('hindiText')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('hindiText')); ?>

                        </p>
                    <?php endif; ?>
                    <p class="helper-block">
                        <?php echo e(trans('global.product.fields.name_helper')); ?>

                    </p>
                </div>
                <div class="form-group col-md-3 <?php echo e($errors->has('marathiText') ? 'has-error' : ''); ?>">
                    <label for="marathiText">Marathi Text*</label>
                    <input type="text" id="marathiText" name="marathiText" class="form-control" value="<?php echo e(old('marathiText', isset($languageData) ? $languageData->marathiText : '')); ?>">
                    <?php if($errors->has('marathiText')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('marathiText')); ?>

                        </p>
                    <?php endif; ?>
                    <p class="helper-block">
                        <?php echo e(trans('global.product.fields.name_helper')); ?>

                    </p>
                </div>
                <div class="form-group col-md-3 <?php echo e($errors->has('price') ? 'has-error' : ''); ?>">
                    <label for="price">Price per kg*</label>
                    <input type="number" id="price" name="price" class="form-control" value="<?php echo e(old('price', isset($product) ? $product->price : '')); ?>">
                    <?php if($errors->has('price')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('price')); ?>

                        </p>
                    <?php endif; ?>
                    <p class="helper-block">
                        <?php echo e(trans('global.product.fields.name_helper')); ?>

                    </p>
                </div>





            </div>


            <div>
                <input class="btn btn-danger" type="submit" value="<?php echo e(trans('global.save')); ?>">
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.3\htdocs\Ecommerce\resources\views/admin/masalaIngradients/create.blade.php ENDPATH**/ ?>