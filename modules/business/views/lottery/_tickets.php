<?php foreach ($tickets as $ticket) { ?>
    <p><span class="text-<?= $ticket->x2 ? 'success' : 'danger'?>"><b><?= $ticket->ticket ?></b></span></p>
<?php } ?>