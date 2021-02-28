<div id="maincontent" class="maincontent" style="z-index: 0;">
    <div class="mainScreen-wrap mainScreen-background">
        <div style="display: table; width: 100%; background: rgba(0,0,0,0.5); z-index: 1; height: 100%;">
            <div id="filterplace" class="mainhead block-novostoy-intro" style="display: table-cell; vertical-align: bottom;">
                <h1 style="font-family: 'Geometria'; left: calc(50% - 388px);">Вся недвижимость Санкт-Петербурга и Ленобласти</h1>
                <?php $this->load->module('buildings')->getNewFilterBuildings(); ?>
            </div>
        </div>
    </div>
    <?php $this->load->module('interest')->showNewMain(); ?>
    <?php $this->load->module('special')->showNewMain();?>
    <div id="load_content">

    </div>
</div>
<video src="/asset/assets/php/loaderr.mp4" autoplay="autoplay" loop="loop" style="margin: 0 auto; display: block; margin-bottom: 20px; margin-top: 5px;" id="loadervidos"></video>
<script>
    var n1main='<?php $this->load->module('news')->nlimit(0, 0);?>';
    var n2main='<?php $this->load->module('news')->nlimit(1, 0);?>';
    var n3main='<?php $this->load->module('news')->nlimit(0, 1);?>';
    var n4main='<?php $this->load->module('news')->nlimit(1, 1);?>';
</script>
<script src="/asset/assets/js/scripti.js"></script>