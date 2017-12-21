<?php
    use app\components\THelper;
?>
<?php if ($image) { ?>
    <li id="instruction-li" style="display:none;">
        <div class="m-t m-l">
            <a href="/business/default/instruction?c=<?= $currentController ?>&a=<?= $currentAction ?>&m=<?= $currentModule ?>" data-toggle="ajaxModal" class="dropdown-toggle btn btn-xs btn-blue-one show-instruction" title="<?= THelper::t('video_instruction') ?>"><i class="fa fa-video-camera"></i></a>
        </div>
    </li>
<?php } ?>
<script>
    $(document).ready(function() {
        $('#instruction-li').show();
    });
</script>
