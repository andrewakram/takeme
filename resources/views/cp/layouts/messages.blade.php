<?php if(session()->has('insert_message')): ?>
<div class="alert alert-success dark alert-dismissible fade show col-lg-3" role="alert">
    <i class="icon-thumb-up"></i>
    <b>
        <?php echo e(session()->get('insert_message')); ?>
    </b>
    <button class="close" type="button" data-dismiss="alert" aria-label="Close" >
        <span aria-hidden="true">×</span>
    </button>
</div>
<?php endif; ?>

@if($errors->any())
    <div class="alert alert-danger dark alert-dismissible fade show col-lg-3" role="alert">
        <i class="icon-thumb-down"></i>
        <b>
            @if ($errors)
                <?php echo "من فضلك اكمل ادخال البيانات المطلوبة !"; ?>
            @endif
        </b>
        <button class="close" type="button" data-dismiss="alert" aria-label="Close" data-original-title="" title="">
            <span aria-hidden="true">×</span>
        </button>
    </div>
@endif
