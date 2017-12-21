<?php

use app\components\THelper;
?>
<div class="header b-b clearfix panel panel-default"><?=THelper::t('youtube_player')?></div>
<div class="form-group">
  <div class="col-sm-12">
    <form method="get" style="position: relative">
      <textarea name="player" id="editor" class="form-control" cols="30" rows="5"><?=$texts?></textarea>
      <a class="btn btn-default btn-sm" title="Save" data-save_id="<?=$id ?>" id="save"><i class="fa fa-floppy-o"></i></a>
      <label id="success"><?=THelper::t('saved_successfully')?></label>
    </form>
  </div>
</div>

