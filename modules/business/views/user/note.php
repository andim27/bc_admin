<?php

use app\components\THelper;

foreach($notes as $n) { ?>
    <li class="list-group-item note-it hover" id="note-<?= $n->id ?>" data-id="<?= $n->id ?>">
        <div class="view" data-id="<?= $n->id ?>">
            <button class="close close-note hover-action" data-id="<?= $n->id ?>" data-confirmation="<?= THelper::t('confirmation_notes_text') ?>">&times;</button>
            <div class="note-name">
                <strong>
                    <?= $n->title ?>
                </strong>
            </div>
            <span class="text-xs text-muted"><?= gmdate('d-m-Y, H:i', $n->dateCreate) ?></span>
        </div>
    </li>
<?php } ?>