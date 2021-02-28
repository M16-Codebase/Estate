<?php
$type_building = array(2,3,6);
$rayon_array   = array();
$metro_array   = array();

$this->load->model('metro/metro_model','metro_model');
$metro		= $this->metro_model->getAll();
$inmetro	= $this->metro_model->getAllFilter(0);

$this->load->model('rayon/rayon_model','rayon_model');
$rayons	= $this->rayon_model->getAllFilter(0);
$spb	= array();
$lenobl = array();

foreach ($rayons as $v)
{
    if ($v['is_city'] == 1)
    {
        $spb[] = $v;
    }
    else
    {
        $lenobl[] = $v;
    }
}

if(!isset($k)) {
    $k = '';
}

if(isset($d_type) && is_array($k))foreach ($d_type as $k => $v)
{
    if (in_array($k, $type_building))
        unset($d_type[$k]);
}

$ray = array(26,27,11);
if(isset($d_rayon) && is_array($k)) foreach($d_rayon as $k=>$v) { if(in_array($k, $ray)) { unset($d_rayon[$k]); } }
$metro_building = array(51);
if(isset($d_metro) && is_array($k)) foreach($d_metro as $k=>$v) { if(in_array($k, $metro_building)) { unset($d_metro[$k]); } }
if(empty($params['rayon'])) { $params['rayon'] = '0'; } else {$rayon_array = explode(",", $params['rayon']);}
if(empty($params['metro'])) { $params['metro'] = '0'; } else {$metro_array = explode(",", $params['metro']);}
if(empty($params['builder'])) { $params['builder'] = '0'; $builder_array = array(); } else {$builder_array = explode(",", $params['builder']);}
if(empty($params['class'])) { $params['class'] = '0'; $class_array = array(); } else {$class_array = explode(",", $params['class']);}
if(empty($params['adress'])) { $params['adress'] = ''; }
if(empty($params['price_from'])) { $params['price_from'] = $filter->minMax['min']; }
if(empty($params['price_to'])) { $params['price_to'] = $filter->minMax['max']; }
if(empty($params['square_from'])) { $params['square_from'] = $filter->minMaxS['min']; }
if(empty($params['square_to'])) { $params['square_to'] = $filter->minMaxS['max']; }
if(empty($params['room'])) { $params['room'] = '0'; $room_array = array(); } else {$room_array = explode(",", $params['room']);}
if(empty($params['ipoteka'])) { $params['ipoteka'] = ''; }
if(empty($params['rassrochka'])) { $params['rassrochka'] = ''; }
if(empty($params['otdelka'])) { $params['otdelka'] = ''; }
if(empty($params['fz214'])) { $params['fz214'] = ''; }
if(empty($params['parking'])) { $params['parking'] = ''; }
if(empty($params['name'])) { $params['name'] = ''; }
if(empty($params['type'])) { $params['type'] = '0'; $type_array = array();} else {$type_array = explode(",", $params['type']);}
if(empty($params['srok'])) { $params['srok'] = '0'; $srok_array = array();} else {$srok_array = explode(",", $params['srok']);}

$long = 0;

if (!empty($params['metro']) || $params['square_from'] > $filter->minMaxS['min'] ||
    $params['square_to'] < $filter->minMaxS['max'] || !empty($params['ipoteka']) ||
    !empty($params['rassrochka']) || !empty($params['otdelka']) || !empty($params['fz214']) ||
    !empty($params['parking']) || !empty($params['name']) || !empty($params['class']) ||
    !empty($params['type'])) $long = 1;

$metro_text = (count($metro_array) == 0)?'Метро: не выбрано':'Метро: выбрано - '.count($metro_array);
$rayon_text = (count($rayon_array) == 0)?'Район: не выбрано':'Район: выбрано - '.count($rayon_array);
?>
<div class="mainhead block-novostoy-intro" style="height: 330px;">
    <div class="contain" style="z-index: 4">
        <div class="mainfilter">
            <div class="filterchoose clearfix">
                <?php echo $this->load->view(getPath(__FILE__,'/parth_filter_menu'), array('current' => 'buildings'), TRUE); ?>
            </div>
            <div class="filtercont">
                <div class="filter-display active" id="cont-1">

                    <form method="get" class="buildings-form" action="/buildings">
                        <div class="nfilter<?php if($long==1):?> showlong<?php endif; ?>">
                            <div class="short-filter clearfix">
                                <div class="lcolumn fcolumn">
                                    <div class="clearfix one-filter">
                                        <input type="text" id="city" placeholder="Введите название ЖК" name="name" value="<?php echo $params['name']; ?>" class="filter-input filter-input-large build_txt">
                                    </div>
                                    <div class="clearfix one-filter long-filter">
                                        <input type="hidden" name="rayon" value="<?php echo $params['rayon']; ?>" class="sel_build_rayon" />
                                        <a href="#metro_n_districts" class="shpopupa popup-modal distrolist" data-id="districts"><?php echo $rayon_text?></a>
                                    </div>
                                    <div class="clearfix one-filter long-filter">
                                        <input type="hidden" name="metro" value="<?php echo $params['metro']; ?>" class="sel_build_metro" />
                                        <a href="#metro_n_districts" class="shpopupa popup-modal metrolist" data-id="metro"><?php echo $metro_text?></a>
                                    </div>
                                    <!--<div class="clearfix one-filter long-filter">
										<input type="hidden" name="tometro" value="<?//=$params['metro']; ?>" class="sel_build_tometro" />
										<select class="mbuildingstometro block-right">
											<?php //foreach($filter->tometro as $k=>$r): ?>
												<option value="<?php //echo $k; ?>" <?php //if (in_array($k, $tometro_array)):?>selected="selected"<?php //endif;?>><?php //echo $r; ?></option>
											<?php //endforeach; ?>
										</select>
									</div>-->
                                </div>
                                <div class="scolumn fcolumn">
                                    <div class="clearfix one-filter-price">
                                        <label>стоимость <span>(млн руб.)</span></label>
                                        <div class="filter-slider-container">
                                            <div id="price-slider" class="filter-slider range"></div>
                                        </div>
                                        <!--<label>Найдено: <strong id="prc_res" style="font-size: 13px; color:#fa972e;">327</strong></label>-->
                                        <div class="prdiv price-b" data-min="<?php echo $filter->minMax['min']; ?>" data-max="<?php echo $filter->minMax['max']; ?>">
                                            <span class="sq_uch">от </span>
                                            <input type="text" name="price_from" id="fPrice-from" class="price_input" value="<?php echo $params['price_from']; ?>" />
                                            <span class="sq_uch"> до </span>
                                            <input type="text" name="price_to" id="fPrice-to" class="price_input" value="<?php echo $params['price_to']; ?>" />
                                        </div>
                                    </div>
                                    <div class="clearfix one-filter-price long-filter mtop30">
                                        <label>площадь <span>(м<sup>2</sup>)</span></label>
                                        <div class="filter-slider-container">
                                            <div id="square-slider" class="filter-slider range"></div>
                                        </div>
                                        <div class="prdiv square-b" data-min="<?php echo $filter->minMaxS['min']; ?>" data-max="<?php echo $filter->minMaxS['max']; ?>">
                                            <span class="sq_uch">от </span>
                                            <input type="text" name="square_from" id="fSquare-from" class="price_input" value="<?php echo $params['square_from']; ?>" />
                                            <span class="sq_uch"> до </span>
                                            <input type="text" name="square_to" id="fSquare-to" class="price_input" value="<?php echo $params['square_to']; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="lcolumn fcolumn">
                                    <div class="clearfix one-filter wd235">
                                        <input type="hidden" name="srok" value="<?php echo $params['srok']; ?>" class="sel_build_deadline" />
                                        <select class="mdeadlinebuildings block-right" multiple="multiple">
                                            <?php
                                            foreach (array(1,2018,2019,2020,2021) as $year)
                                            {
                                                $selected = ($params['srok'] == $year)?' selected="selected"':'';
                                                echo '<option value="'.$year.'"'.$selected.'>'.(($year == 1)?'Сдан':$year).'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="clearfix one-filter long-filter wd235">
                                        <input type="hidden" name="builder" value="<?php echo $params['builder']; ?>" class="sel_build_builder" />
                                        <select class="mbuildingsbuilder block-right" multiple="multiple">
                                            <?php foreach($filter->builder as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if (in_array($k, $builder_array)):?>selected="selected"<?php endif;?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="clearfix one-filter long-filter wd235">
                                        <input type="hidden" name="class" value="<?php echo $params['class']; ?>" class="sel_build_class" />
                                        <select class="mbuildingsclass block-right" multiple="multiple">
                                            <?php foreach($filter->class as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if (in_array($k, $class_array)):?>selected="selected"<?php endif;?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="clearfix one-filter long-filter wd235">
                                        <input type="hidden" name="type" value="<?php echo $params['type']; ?>" class="sel_build_type" />
                                        <select class="mtypebuildings block-right" multiple="multiple">
                                            <?php foreach($filter->type as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if (in_array($k, $type_array)):?>selected="selected"<?php endif;?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="scolumn fcolumn">
                                    <div class="clearfix one-filter rooms-filt">
                                        <input type="hidden" name="room" value="<?php echo $params['room']; ?>" class="sel_room" />
                                        <div class="btn-group chk-btn" data-toggle="buttons">
                                            <label id="cbk1" class="btn btn-primary<?php if(in_array(1, $room_array)) echo ' active'; ?>">
                                                <input id="clk1" type="checkbox" value="1" <?php if(in_array(1, $room_array)) echo 'checked'; ?> class="builds-check">Студия
                                            </label>

                                            <label id="cbk2" class="btn btn-primary<?php if(in_array(2, $room_array)) echo ' active'; ?>">
                                                <input id="clk2" type="checkbox" value="2" <?php if(in_array(2, $room_array)) echo 'checked'; ?> class="builds-check">1
                                            </label>

                                            <label id="cbk3" class="btn btn-primary<?php if(in_array(3, $room_array)) echo ' active'; ?>">
                                                <input id="clk3" type="checkbox" value="3,16" <?php if(in_array(3, $room_array)) echo 'checked'; ?> class="builds-check">2
                                            </label>
                                            <label id="cbk4" class="btn btn-primary<?php if(in_array(4, $room_array)) echo ' active'; ?>">
                                                <input id="clk4" type="checkbox" value="4,17" class="builds-check" <?php if(in_array(4, $room_array)) echo 'checked'; ?>>3
                                            </label>
                                            <label id="cbk5" class="btn btn-primary<?php if(in_array(5, $room_array)) echo ' active'; ?>">
                                                <input id="clk5" type="checkbox" value="5,18" class="builds-check" <?php if(in_array(5, $room_array)) echo 'checked'; ?>>4
                                            </label>
                                            <label id="cbk6" class="btn btn-primary<?php if(in_array(6, $room_array)) echo ' active'; ?>">
                                                <input id="clk6" type="checkbox" class="builds-check" value="6,7,32,31,21" <?php if(in_array(6, $room_array)) echo 'checked'; ?>>5+
                                            </label>
                                        </div>
                                    </div>
                                    <div class="clearfix one-filter long-filter left-al">
                                        <label for="checkbox-1" class="custom-checkbox-wrap">
                                            <input id="checkbox-1" value="1" <?php if($params['ipoteka'] == 1) echo 'checked'; ?> name="ipoteka" type="checkbox" class="build-check">
                                            <span></span>Ипотека
                                        </label>
                                        <label for="checkbox-2" class="custom-checkbox-wrap">
                                            <input id="checkbox-2" value="1" <?php if($params['rassrochka'] == 1) echo 'checked'; ?> name="rassrochka" type="checkbox" class="build-check">
                                            <span></span>рассрочка
                                        </label>
                                        <label for="checkbox-3" class="custom-checkbox-wrap">
                                            <input id="checkbox-3" value="1" <?php if($params['otdelka'] == 1) echo 'checked'; ?> name="otdelka" type="checkbox" class="build-check">
                                            <span></span>отделка
                                        </label>
                                        <label for="checkbox-4" class="custom-checkbox-wrap">
                                            <input id="checkbox-4" value="1" <?php if($params['fz214'] == 1) echo 'checked'; ?> name="fz214" type="checkbox" class="build-check">
                                            <span></span>214 фз
                                        </label>
                                        <label for="checkbox-5" class="custom-checkbox-wrap">
                                            <input id="checkbox-5" value="1" <?php if($params['parking'] == 1) echo 'checked'; ?> name="parking" type="checkbox" class="build-check">
                                            <span></span>паркинг
                                        </label>
                                    </div>
                                </div>
                            </div>
<!--noindex-->
                            <div class="botfilter<?php if($long==1):?> longs<?php endif; ?>">
                                <a href="#" id="openfilter" class="show-hide<?php if($long==0):?> short<?php endif; ?>"><?php if($long==0):?>Расширенный поиск<?php else: ?>Свернуть<?php endif; ?></a>
                                <div class="action-filter">
                                    <a href="/buildings">Сбросить</a>
                                    <button type="submit">Искать</button>
                                </div>
                            </div>
<!--/noindex-->
                        </div>

                    </form>




                </div>
            </div>
        </div>
    </div>
</div>