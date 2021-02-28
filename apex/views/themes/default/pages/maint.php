<div id="google_translate_element"></div><script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'ru', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<?php
$this->load->model('metro/metro_model','metro_model');
$metro = $this->metro_model->getAll();
$inmetro = $this->metro_model->getAllFilter(0);

?>
    <div class="mainhead block-novostoy-intro">
        <ul class="nov-slide" id="bigslidecat">
            <li><img src="/asset/assets/img/newban.jpg"></li>
        </ul>
        <div class="contain">

            <?php //$this->load->module('buildings')->getFilterBuildingsTest(); ?>
        </div>
    </div>
    <div class="main-content">
        <div class="contain">
            <div class="mrow clearfix">
                <div class="onecol mcol-4">
                    <div class="main-speach">
                        <img src="/asset/assets/img/malofeev-main.jpg" />
                        <div class="speach">
                            <span>Доверие и честность - </span>
                            <p>
                                основа долгосрочных отношений с Клиентами — главная составляющая нашей положительной репутации.
                            </p>
                            <p>
                                Компания «М16-Недвижимость» делает ставку на самое высокое  качество оказания услуг, на сервис европейского уровня при сохранении приемлемого уровня цен.
                            </p>
                            <span class="speach-foot">
                                Учредитель компании
                            </span>
                            <span class="uchredit">
                                вячеслав малафеев
                            </span>
                            <span class="zenitfc speach-foot">
                                голкипер ФК
                            </span>
                        </div>
                    </div>
                </div>
                <div class="onecol mcol-8">
                    <?php $this->load->module('interest')->showMain(); ?>
                </div>
            </div>
        </div>
        <?php $this->load->module('special')->showMain(); ?>
    </div>




<div class="clearfix"></div>
<a class="popup-modal" href="#metro_n_districts">открыть</a>
<div id="metro_n_districts" class="mfp-hide white-popup-block distromet">
    <div class="tabs-popup">
        <a href="#" class="chpopsh" id="sdistricts">Район</a>
        <a href="#" class="chpopsh active" id="smetro">Метро</a>
    </div>
    <div class="close-popup"></div>
    <div class="mapmetrotest popsh active" id="pop-smetro">

        <?php foreach($metro as $value):?>
            <div class="label-metro" style="top: <?=$value['top']?>px; left: <?=$value['left']?>px; <?php if ($value['width'] > 0):?>width:<?=$value['width']?>px<?php endif;?>">
                <?php if(in_array($value['id'], $inmetro)):?>
                <a href="#" data-id="<?=$value['id']?>"><?=$value['label']?></a>
                <?php else: ?>
                <span><?=$value['label']?></span>
                <?php endif;?>
            </div>
        <?php endforeach; ?>
        <div class="bot-pops">
            <a href="#">Сбросить</a>
        </div>
    </div>
    <div class="districts-popup popsh" id="pop-sdistricts">
        <div class="area_maps clearfix">
            <?php
                $this->load->model('rayon/rayon_model','rayon_model');
                $rayons = $this->rayon_model->getAll();
                $spb = array(); $lenobl = array();
                foreach ($rayons as $v){
                    if ($v['is_city'] == 1)
                        $spb[] = $v;
                    else
                        $lenobl[] = $v;
                }
            ?>
            <img src="/asset/assets/img/districts.jpg" alt="" usemap="#map">
            <map name="map">
                <?php foreach($rayons as $value):?>
                <area shape="poly" data-id="<?=$value['id']?>" alt="<?=$value['name']?>" title="<?=$value['name']?>" coords="<?=$value['poly']?>" />
                <?php endforeach; ?>
            </map>
            <?php foreach($rayons as $value):?>
            <img src="/asset/assets/img/d<?=$value['id']?>.png" usemap="#map" class="area_region area_region_<?=$value['id']?>" style="display: none;">
            <?php endforeach; ?>
        </div>
        <div class="distr-list">
            <?php if(count($spb > 0)):?>
            <?php $half = (int)ceil(count($spb) / 2);?>
            <h3>Санкт-Петербург</h3>
            <ul>
            <?php foreach($spb as $k => $value):?>
            <?php if($k == $half):?>
            </ul>
            <ul>
            <?php endif;?>
                <li data-id="<?=$value['id']?>" class="distr_item_<?=$value['id']?>"><a href="#"><?=$value['name']?> район</a></li>
            <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <?php if(count($lenobl > 0)):?>
            <?php $half = (int)ceil(count($lenobl) / 2);?>
            <h3>Ленинградская область</h3>
            <ul>
            <?php foreach($lenobl as $k => $value):?>
            <?php if($k == $half):?>
            </ul>
            <ul>
            <?php endif;?>
                <li data-id="<?=$value['id']?>" class="distr_item_<?=$value['id']?>"><a href="#"><?=$value['name']?></a></li>
            <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

