

<?php

$ray = array(26,27);
foreach($d_rayon as $k=>$v) { if(in_array($k, $ray)) { unset($d_rayon[$k]); } }

$rayon_array = array();
$metro_array = array();

$this->load->model('metro/metro_model','metro_model');
$metro = $this->metro_model->getAll();
$inmetro = $this->metro_model->getAllFilter(4);

$this->load->model('rayon/rayon_model','rayon_model');
$rayons = $this->rayon_model->getAllFilter(4);
$spb = array(); $lenobl = array();
foreach ($rayons as $v){
    if ($v['is_city'] == 1)
        $spb[] = $v;
    else
        $lenobl[] = $v;
}

if(empty($params['rayon'])) { $params['rayon'] = '0'; } else {$rayon_array = explode(",", $params['rayon']);}
if(empty($params['metro'])) { $params['metro'] = '0'; } else {$metro_array = explode(",", $params['metro']);}
if(empty($params['sdelka'])) { $params['sdelka'] = '0'; } else {$sdelka_array = explode(",", $params['sdelka']);}
if(empty($params['forwhat'])) { $params['forwhat'] = '0'; } else {$forwhat_array = explode(",", $params['forwhat']);}
if(empty($params['bussines'])) { $params['bussines'] = '0'; } else {$bussines_array = explode(",", $params['bussines']);}
if(empty($params['pay'])) { $params['pay'] = '0'; } else {$pay_array = explode(",", $params['pay']);}
if(empty($params['vid'])) { $params['vid'] = '0'; } else {$pay_array = explode(",", $params['vid']);}
if(empty($params['price_from'])) { $params['price_from'] = $filter->minMax['min']; }
if(empty($params['price_to'])) { $params['price_to'] = $filter->minMax['max']; }
if(empty($params['square_from'])) { $params['square_from'] = $filter->minMaxS['min']; }
if(empty($params['square_to'])) { $params['square_to'] = $filter->minMaxS['max']; }
if(empty($params['adress'])) { $params['adress'] = ''; }
if(empty($params['name'])) { $params['name'] = ''; }
if(count($metro_array) == 0){$metro_text = 'Метро: не выбрано';} else {$metro_text = 'Метро: выбрано - '.count($metro_array);}
if(count($rayon_array) == 0){$rayon_text = 'Район: не выбрано';} else {$rayon_text = 'Район: выбрано - '.count($rayon_array);}

$long = 0;

if (!empty($params['adress']) || !empty($params['type']) || !empty($params['sdelka']) ||
    !empty($params['pay']) || !empty($params['metro']) || !empty($params['vid']) ||
    !empty($params['forwhat']) || !empty($params['bussines']) || !empty($params['name'])) $long = 1;
?>


<div class="mainhead block-novostoy-intro" style="background: url('/asset/assets/img/commercialwide.jpg') no-repeat top center">
    <div class="contain">
        <div class="mainfilter">
            <div class="filterchoose clearfix">
                <?php echo $this->load->view(getPath(__FILE__,'/parth_filter_menu'), array('current' => 'commercial'), TRUE); ?>
            </div>
            <div class="filtercont">
                <div class="filter-display active" id="cont-1">

                    <form method="get" class="commercial-form" action="/commercial">
                        <div class="nfilter<?php if($long==1):?> showlong<?php endif; ?>">
                            <div class="short-filter clearfix">
                                <div class="scolumn fcolumn">
                                    <div class="clearfix one-filter-price">
                                        <label>стоимость <span>(млн.руб)</span></label>
                                        <div class="filter-slider-container">
                                            <div id="price-slider-commercial" class="filter-slider range"></div>
                                        </div>
                                        <div class="prdiv price-b" data-min="<?php echo $filter->minMax['min']; ?>" data-max="<?php echo $filter->minMax['max']; ?>">
                                            <span class="sq_uch">от </span>
                                            <input type="text" name="price_from" id="cPrice-from" class="price_input" value="<?php echo $params['price_from']; ?>" />
                                            <span class="sq_uch"> до </span>
                                            <input type="text" name="price_to" id="cPrice-to" class="price_input" value="<?php echo $params['price_to']; ?>" />
                                        </div>
                                    </div>
                                    <div class="clearfix one-filter long-filter mtop10 wd235">
                                        <input type="hidden" name="sdelka" value="<?=$params['sdelka']?>" class="sel_sdelka" />
                                        <select class="mcommercial_sdelka" multiple="multiple">
                                            <?php foreach($filter->sdelka as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if($params['sdelka'] && in_array($k, $sdelka_array)) echo 'selected'; ?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="clearfix one-filter long-filter mtop37 wd235">
                                        <input type="hidden" name="pay" value="<?=$params['pay']?>" class="sel_pay" />
                                        <select class="mcommercial_pay" multiple="multiple">
                                            <?php foreach($filter->pay as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if($params['pay'] && in_array($k, $pay_array)) echo 'selected'; ?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="lcolumn fcolumn">
                                    <div class="clearfix one-filter-price">
                                        <label>площадь <span>(м<sup>2</sup>)</span></label>
                                        <div class="filter-slider-container">
                                            <div id="square-slider-commercial" class="filter-slider range"></div>
                                        </div>
                                        <div class="prdiv square-b" data-min="<?php echo $filter->minMaxS['min']; ?>" data-max="<?php echo $filter->minMaxS['max']; ?>">
                                            <span class="sq_uch">от </span>
                                            <input type="text" name="square_from" id="cSquare-from" class="price_input" value="<?php echo $params['square_from']; ?>" />
                                            <span class="sq_uch"> до </span>
                                            <input type="text" name="square_to" id="cSquare-to" class="price_input" value="<?php echo $params['square_to']; ?>" />
                                        </div>
                                    </div>
                                    <div class="clearfix one-filter long-filter mtop10 wd235">
                                        <input type="hidden" name="forwhat" value="<?=$params['forwhat']?>" class="sel_forwhat" />
                                        <select class="mcommercial_forwhat" multiple="multiple">
                                            <?php foreach($filter->forwhat as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if($params['forwhat'] && in_array($k, $forwhat_array)) echo 'selected'; ?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="clearfix one-filter long-filter mtop37 wd235">
                                        <input type="hidden" name="bussines" value="<?=$params['bussines']?>" class="sel_bussines" />
                                        <select class="mcommercial_bussines" multiple="multiple">
                                            <?php foreach($filter->bussines as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if($params['bussines'] && in_array($k, $bussines_array)) echo 'selected'; ?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="lcolumn fcolumn">
                                    <div class="clearfix one-filter">
                                        <input type="hidden" name="rayon" value="<?=$params['rayon']?>" class="sel_build_rayon" />
                                        <a href="#metro_n_districts" class="shpopupa popup-modal distrolist" data-id="districts"><?=$rayon_text?></a>
                                    </div>
                                    <div class="clearfix one-filter long-filter mtop37">
                                        <input type="hidden" name="metro" value="<?=$params['metro']?>" class="sel_build_metro" />
                                        <a href="#metro_n_districts" class="shpopupa popup-modal metrolist" data-id="metro"><?=$metro_text?></a>
                                    </div>
                                    <div class="clearfix one-filter long-filter mtop37">
                                        <input type="text" placeholder="Адрес" name="adress" value="<?php echo $params['adress'];?>" class="filter-input resale_txt" id="aadrr" data-name="commercial">
                                    </div>
                                </div>
                                <div class="scolumn fcolumn">
                                    <div class="clearfix one-filter long-filter wd235">
                                        <input type="hidden" name="type" value="<?=$params['type']?>" class="sel_type" />
                                        <select class="mcommercial_type" multiple="multiple">
                                            <?php foreach($filter->type as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if($params['vid'] && in_array($k, $type_array)) echo 'selected'; ?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="clearfix one-filter long-filter mtop37 wd235">
                                        <input type="hidden" name="vid" value="<?=$params['vid']?>" class="sel_vid" />
                                        <select class="mcommercial_vid" multiple="multiple">
                                            <?php foreach($filter->vid as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if($params['vid'] && in_array($k, $vid_array)) echo 'selected'; ?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="clearfix one-filter long-filter mtop37">
                                        <input type="text" name="name" placeholder="ЖК" value="<?=$params['name']?>" class="filter-input" id="ccity" />
                                    </div>
                                </div>
                            </div>
<!--noindex-->
                            <div class="botfilter<?php if($long==1):?> longs<?php endif; ?>">
                                <a href="#" class="show-hide<?php if($long==0):?> short<?php endif; ?>"><?php if($long==0):?>Расширенный поиск<?php else: ?>Свернуть<?php endif; ?></a>
                                <div class="action-filter">
                                    <a href="/commercial">Сбросить</a>
                                    <button type="submit">Искать</button>
                                </div>
                            </div>
<!--/noindex-->
                        </div>

                    </form>

<!--noindex-->
                    <div id="metro_n_districts" class="mfp-hide white-popup-block distromet" data-cat="commercial">
                        <div class="tabs-popup">
                            <a href="#" class="chpopsh" id="sdistricts">Район</a>
                            <a href="#" class="chpopsh active" id="smetro">Метро</a>
                        </div>
                        <div class="close-popup"></div>
                        <div class="mapmetro popsh active" id="pop-smetro">
                            <?php foreach($metro as $value):?>
                                <div class="label-metro" style="top: <?=$value['top']?>px; left: <?=$value['left']?>px; <?php if ($value['width'] > 0):?>width:<?=$value['width']?>px;<?php endif;?> <?php if (!empty($value['align'])):?>text-align:<?=$value['align']?>;<?php endif;?> <?php if (!empty($value['letter_spacing'])):?>letter-spacing:<?=$value['letter_spacing']?>px;<?php endif;?>">
                                    <?php if(in_array($value['id'], $inmetro)):?>
                                        <a href="#" data-id="<?=$value['id']?>" id="metro_item_<?=$value['id']?>" class="<?php if(in_array($value['id'], $metro_array)):?>select <?php endif;?><?php if (!empty($value['class'])):?><?=$value['class']?><?php endif;?>"><?=$value['label']?></a>
                                    <?php else: ?>
                                        <span><?=$value['label']?></span>
                                    <?php endif;?>
                                </div>
                            <?php endforeach; ?>
                            <div class="distr-list metro-list">
                                <div class="inner-metro">
                                    <?php if(count($metro > 0)):?>
                                        <?php $half = (int)ceil(count($metro) / 2);?>
                                        <ul>
                                        <?php foreach($metro as $k => $value):?>
                                            <?php if($k == $half):?>
                                                </ul>
                                                <ul>
                                            <?php endif;?>
                                            <li data-id="<?=$value['id']?>" class="metro_item_<?=$value['id']?><?php if(in_array($value['id'], $metro_array)):?> select<?php endif;?>">
                                                <?php if(in_array($value['id'], $inmetro)):?>
                                                    <a href="#"><?=$value['name']?></a>
                                                <?php else: ?>
                                                    <span><?=$value['name']?></span>
                                                <?php endif;?>
                                            </li>
                                        <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="bot-pops">
                                <a href="#" class="clearmetros" id="refresh-metro">Сбросить</a>
                                <button>Показать</button>
                            </div>
                        </div>
                        <div class="districts-popup popsh" id="pop-sdistricts">
                            <div class="area_maps clearfix">
                                <?php

                                ?>
                                <img src="/asset/assets/img/map-distr.jpg" alt="" usemap="#map">
                                <map name="map">
                                    <?php foreach($rayons as $value):?>
                                        <area shape="poly" data-id="<?=$value['id']?>" alt="<?=$value['name']?>" title="<?=$value['name']?>" coords="<?=$value['poly']?>" />
                                    <?php endforeach; ?>
                                </map>
                                <?php foreach($rayons as $value):?>
                                    <img src="/asset/assets/img/d<?php echo $value['id']; ?>.png" width="532" height="672" usemap="#map" class="area_region area_region_<?php echo $value['id']; ?>" style="display: <?php echo (in_array($value['id'], $rayon_array))?'block':'none'; ?>;">
                                    
                                <?php endforeach; ?>
                            </div>
                            <div class="distr-list regions-list">
                                <?php if(count($spb > 0)):?>
                                    <div class="clearfix">
                                    <?php $half = (int)ceil(count($spb) / 2);?>
                                    <p class="h3">Санкт-Петербург</p>
                                    <ul>
                                    <?php foreach($spb as $k => $value):?>
                                        <?php if($k == $half):?>
                                            </ul>
                                            <ul>
                                        <?php endif;?>
                                        <li data-id="<?=$value['id']?>" class="distr_item_<?=$value['id']?><?php if(in_array($value['id'], $rayon_array)):?> select<?php endif;?>"><a href="#" data-name="<?=$value['name']?>"><?=$value['name']?> район</a></li>
                                    <?php endforeach; ?>
                                    </ul>
                                    </div>
                                <?php endif; ?>
                                <?php if(count($lenobl > 0)):?>
                                    <div class="clearfix">
                                    <?php $half = (int)ceil(count($lenobl) / 2);?>
                                    <p class="h3">Ленинградская область</p>
                                    <ul>
                                    <?php foreach($lenobl as $k => $value):?>
                                        <?php if($k == $half):?>
                                            </ul>
                                            <ul>
                                        <?php endif;?>
                                        <li data-id="<?=$value['id']?>" class="distr_item_<?=$value['id']?><?php if(in_array($value['id'], $rayon_array)):?> select<?php endif;?>"><a href="#" data-name="<?=$value['name']?>"><?=$value['name']?> район</a></li>
                                    <?php endforeach; ?>
                                    </ul>
                                    </div>
                                <?php endif; ?>
                                <div class="bot-pops">
                                    <a href="#" class="clearmetros"  id="refresh-rayon">Сбросить</a>
                                    <button>Показать</button>
                                </div>
                            </div>

                        </div>
                    </div>
<!--/noindex-->
                </div>
            </div>
        </div>
    </div>
</div>