<?php
    use \app\components\THelper;
    $this->title = strtoupper(THelper::t('company_name'));
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?=THelper::t('workset')?></h3>
    <small><?=THelper::t('welcome_back_noteman')?></small>
</div>
<section class="panel panel-default">
    <div class="row m-l-none m-r-none bg-light lter">
        <div class="col-sm-6 col-md-3 padder-v b-r b-light">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                      <i class="fa fa-circle fa-stack-2x text-info"></i>
                      <i class="fa fa-male fa-stack-1x text-white"></i>
                    </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong>52,000</strong></span>
                <small class="text-muted text-uc"><?=THelper::t('new_robots');?></small>
            </a>
        </div>
        <div class="col-sm-6 col-md-3 padder-v b-r b-light lt">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                      <i class="fa fa-circle fa-stack-2x text-warning"></i>
                      <i class="fa fa-bug fa-stack-1x text-white"></i>
                      <span class="easypiechart pos-abt" data-percent="100" data-line-width="4" data-track-Color="#fff" data-scale-Color="false" data-size="50" data-line-cap='butt' data-animate="2000" data-target="#bugs" data-update="3000"></span>
                    </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong id="bugs">468</strong></span>
                <small class="text-muted text-uc"><?=THelper::t('bugs_intruded');?></small>
            </a>
        </div>
        <div class="col-sm-6 col-md-3 padder-v b-r b-light">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                      <i class="fa fa-circle fa-stack-2x text-danger"></i>
                      <i class="fa fa-fire-extinguisher fa-stack-1x text-white"></i>
                      <span class="easypiechart pos-abt" data-percent="100" data-line-width="4" data-track-Color="#f5f5f5" data-scale-Color="false" data-size="50" data-line-cap='butt' data-animate="3000" data-target="#firers" data-update="5000"></span>
                    </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong id="firers">359</strong></span>
                <small class="text-muted text-uc"><?=THelper::t('extinguishers_read')?></small>
            </a>
        </div>
        <div class="col-sm-6 col-md-3 padder-v b-r b-light lt">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                      <i class="fa fa-circle fa-stack-2x icon-muted"></i>
                      <i class="fa fa-clock-o fa-stack-1x text-white"></i>
                    </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong>31:50</strong></span>
                <small class="text-muted text-uc"><?=THelper::t('left_to_exit')?></small>
            </a>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-md-8">
        <section class="panel panel-default">
            <header class="panel-heading font-bold"><?=THelper::t('statistics')?></header>
            <div class="panel-body">
                <div id="flot-1ine" style="height:210px"></div>
            </div>
            <footer class="panel-footer bg-white no-padder">
                <div class="row text-center no-gutter">
                    <div class="col-xs-3 b-r b-light">
                        <span class="h4 font-bold m-t block">5,860</span>
                        <small class="text-muted m-b block"><?=THelper::t('orders')?></small>
                    </div>
                    <div class="col-xs-3 b-r b-light">
                        <span class="h4 font-bold m-t block">10,450</span>
                        <small class="text-muted m-b block"><?=THelper::t('sellings')?></small>
                    </div>
                    <div class="col-xs-3 b-r b-light">
                        <span class="h4 font-bold m-t block">21,230</span>
                        <small class="text-muted m-b block"><?=THelper::t('items')?></small>
                    </div>
                    <div class="col-xs-3">
                        <span class="h4 font-bold m-t block">7,230</span>
                        <small class="text-muted m-b block"><?=THelper::t('customers')?></small>
                    </div>
                </div>
            </footer>
        </section>
    </div>
    <div class="col-md-4">
        <section class="panel panel-default">
            <header class="panel-heading font-bold"><?=THelper::t('data_graph')?></header>
            <div class="bg-light dk wrapper">
                <span class="pull-right"><?=THelper::t('friday')?></span>
                      <span class="h4">$540<br>
                        <small class="text-muted">+1.05(2.15%)</small>
                      </span>
                <div class="text-center m-b-n m-t-sm">
                    <div class="sparkline" data-type="line" data-height="65" data-width="100%" data-line-width="2" data-line-color="#dddddd" data-spot-color="#bbbbbb" data-fill-color="" data-highlight-line-color="#fff" data-spot-radius="3" data-resize="true" values="280,320,220,385,450,320,345,250,250,250,400,380"></div>
                    <div class="sparkline inline" data-type="bar" data-height="45" data-bar-width="6" data-bar-spacing="6" data-bar-color="#65bd77">10,9,11,10,11,10,12,10,9,10,11,9,8</div>
                </div>
            </div>
            <div class="panel-body">
                <div>
                    <span class="text-muted"><?=THelper::t('total')?>:</span>
                    <span class="h3 block">$2500.00</span>
                </div>
                <div class="line pull-in"></div>
                <div class="row m-t-sm">
                    <div class="col-xs-4">
                        <small class="text-muted block"><?=THelper::t('market')?></small>
                        <span>$1500.00</span>
                    </div>
                    <div class="col-xs-4">
                        <small class="text-muted block"><?=THelper::t('referal')?></small>
                        <span>$600.00</span>
                    </div>
                    <div class="col-xs-4">
                        <small class="text-muted block"><?=THelper::t('affiliate')?></small>
                        <span>$400.00</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <h4 class="m-t-none"><?=THelper::t('todos')?></h4>
        <ul class="list-group gutter list-group-lg list-group-sp sortable">
            <li class="list-group-item box-shadow">
                <a href="#" class="pull-right" data-dismiss="alert">
                    <i class="fa fa-times icon-muted"></i>
                </a>
                      <span class="pull-left media-xs">
                        <i class="fa fa-sort icon-muted fa m-r-sm"></i>
                        <a  href="#todo-1" data-toggle="class:text-lt text-success" class="active">
                            <i class="fa fa-square-o fa-fw text"></i>
                            <i class="fa fa-check-square-o fa-fw text-active text-success"></i>
                        </a>
                      </span>
                <div class="clear text-success text-lt" id="todo-1">
                    <?=THelper::t('browser_compatibility')?>
                </div>
            </li>
            <li class="list-group-item box-shadow">
                <a href="#" class="pull-right" data-dismiss="alert">
                    <i class="fa fa-times icon-muted"></i>
                </a>
                      <span class="pull-left media-xs">
                        <i class="fa fa-sort icon-muted fa m-r-sm"></i>
                        <a  href="#todo-2" data-toggle="class:text-lt text-danger">
                            <i class="fa fa-square-o fa-fw text"></i>
                            <i class="fa fa-check-square-o fa-fw text-active text-danger"></i>
                        </a>
                      </span>
                <div class="clear" id="todo-2">
                    <?=THelper::t('looking_for_more_example_templates')?>
                </div>
            </li>
            <li class="list-group-item box-shadow">
                <a href="#" class="pull-right" data-dismiss="alert">
                    <i class="fa fa-times icon-muted"></i>
                </a>
                      <span class="pull-left media-xs">
                        <i class="fa fa-sort icon-muted fa m-r-sm"></i>
                        <a  href="#todo-3" data-toggle="class:text-lt">
                            <i class="fa fa-square-o fa-fw text"></i>
                            <i class="fa fa-check-square-o fa-fw text-active text-success"></i>
                        </a>
                      </span>
                <div class="clear" id="todo-3">
                    <?=THelper::t('customizing components')?>
                </div>
            </li>
            <li class="list-group-item box-shadow">
                <a href="#" class="pull-right" data-dismiss="alert">
                    <i class="fa fa-times icon-muted"></i>
                </a>
                      <span class="pull-left media-xs">
                        <i class="fa fa-sort icon-muted fa m-r-sm"></i>
                        <a  href="#todo-4" data-toggle="class:text-lt">
                            <i class="fa fa-square-o fa-fw text"></i>
                            <i class="fa fa-check-square-o fa-fw text-active text-success"></i>
                        </a>
                      </span>
                <div class="clear" id="todo-4">
                    <?=THelper::t('the_fastest_way_to_get_started')?>
                </div>
            </li>
            <li class="list-group-item box-shadow">
                <a href="#" class="pull-right" data-dismiss="alert">
                    <i class="fa fa-times icon-muted"></i>
                </a>
                      <span class="pull-left media-xs">
                        <i class="fa fa-sort icon-muted fa m-r-sm"></i>
                        <a  href="#todo-5" data-toggle="class:text-lt">
                            <i class="fa fa-square-o fa-fw text"></i>
                            <i class="fa fa-check-square-o fa-fw text-active text-success"></i>
                        </a>
                      </span>
                <div class="clear" id="todo-5">
                    <?=THelper::t('html5_doctype_required')?>
                </div>
            </li>
            <li class="list-group-item box-shadow">
                <a href="#" class="pull-right" data-dismiss="alert">
                    <i class="fa fa-times icon-muted"></i>
                </a>
                      <span class="pull-left media-xs">
                        <i class="fa fa-sort icon-muted fa m-r-sm"></i>
                        <a  href="#todo-6" data-toggle="class:text-lt">
                            <i class="fa fa-square-o fa-fw text"></i>
                            <i class="fa fa-check-square-o fa-fw text-active text-success"></i>
                        </a>
                      </span>
                <div class="clear" id="todo-6">
                    <?=THelper::t('lesscss_compiling')?>
                </div>
            </li>
        </ul>
    </div>
    <div class="col-md-4">
        <section class="panel b-light">
            <header class="panel-heading bg-primary dker no-border"><strong><?=THelper::t('calendar')?></strong></header>
            <div id="calendar" class="bg-primary m-l-n-xxs m-r-n-xxs"></div>
            <div class="list-group">
                <a href="#" class="list-group-item text-ellipsis">
                    <span class="badge bg-danger">7:30</span>
                    <?=THelper::t('meet_a_friend')?>
                </a>
                <a href="#" class="list-group-item text-ellipsis">
                    <span class="badge bg-success">9:30</span>
                    <?=THelper::t('have_a_kick_off_meeting')?>
                </a>
                <a href="#" class="list-group-item text-ellipsis">
                    <span class="badge bg-light">19:30</span>
                    <?=THelper::t('milestone_release')?>
                </a>
            </div>
        </section>
    </div>
</div>
<div>
    <div class="btn-group m-b" data-toggle="buttons">
        <label class="btn btn-sm btn-default active">
            <input type="radio" name="options" id="option1"> <?=THelper::t('timeline')?>
        </label>
        <label class="btn btn-sm btn-default">
            <input type="radio" name="options" id="option2"> <?=THelper::t('activity')?>
        </label>
    </div>
    <section class="comment-list block">
        <article id="comment-id-1" class="comment-item">
                    <span class="fa-stack pull-left m-l-xs">
                      <i class="fa fa-circle text-info fa-stack-2x"></i>
                      <i class="fa fa-play-circle text-white fa-stack-1x"></i>
                    </span>
            <section class="comment-body m-b-lg">
                <header>
                    <a href="#"><strong>John smith</strong></a> shared a <a href="#" class="text-info">video</a> to you
                        <span class="text-muted text-xs">
                          24 minutes ago
                        </span>
                </header>
                <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi id neque quam.</div>
            </section>
        </article>
        <!-- .comment-reply -->
        <article id="comment-id-2" class="comment-reply">
            <article class="comment-item">
                <a class="pull-left thumb-sm">
                    <img src="images/avatar_default.png" class="img-circle">
                </a>
                <section class="comment-body m-b-lg">
                    <header>
                        <a href="#"><strong>John smith</strong></a>
                          <span class="text-muted text-xs">
                            26 minutes ago
                          </span>
                    </header>
                    <div> Morbi id neque quam. Aliquam.</div>
                </section>
            </article>
            <article class="comment-item">
                <a class="pull-left thumb-sm">
                    <img src="images/avatar.jpg" class="img-circle">
                </a>
                <section class="comment-body m-b-lg">
                    <header>
                        <a href="#"><strong>Mike</strong></a>
                          <span class="text-muted text-xs">
                            26 minutes ago
                          </span>
                    </header>
                    <div>Good idea.</div>
                </section>
            </article>
        </article>
        <!-- / .comment-reply -->
        <article id="comment-id-2" class="comment-item">
                    <span class="fa-stack pull-left m-l-xs">
                      <i class="fa fa-circle text-danger fa-stack-2x"></i>
                      <i class="fa fa-file-o text-white fa-stack-1x"></i>
                    </span>
            <section class="comment-body m-b-lg">
                <header>
                    <a href="#"><strong>John Doe</strong></a>
                        <span class="text-muted text-xs">
                          1 hour ago
                        </span>
                </header>
                <div>Lorem ipsum dolor sit amet, consecteter adipiscing elit.</div>
            </section>
        </article>
        <article id="comment-id-2" class="comment-item">
                    <span class="fa-stack pull-left m-l-xs">
                      <i class="fa fa-circle text-success fa-stack-2x"></i>
                      <i class="fa fa-check text-white fa-stack-1x"></i>
                    </span>
            <section class="comment-body m-b-lg">
                <header>
                    <a href="#"><strong>Jonathan</strong></a> completed a task
                        <span class="text-muted text-xs">
                          1 hour ago
                        </span>
                </header>
                <div>Consecteter adipiscing elit.</div>
            </section>
        </article>
    </section>
    <a href="#" class="btn btn-default btn-sm m-b"><i class="fa fa-plus icon-muted"></i> more</a>
</div>
