<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4 class="modal-title"><?= $image ? $image->title : '' ?></h4>
        </div>
        <div class="modal-body">
            <div class="row" style="margin-bottom:25px">
                <div class="col-xs-12">
                    <?= $image ? $image->embedCode : '' ?>
                </div>
            </div>
        </div>
    </div>
</div>