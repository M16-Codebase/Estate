<?php
$type_building = array(2,3,6);
$rayon_array   = array();
$metro_array   = array();

$this->load->model('metro/metro_model','metro_model');
$metro		= $this->metro_model->getAll();
$inmetro	= $this->metro_model->getAllFilter(8);

$this->load->model('rayon/rayon_model','rayon_model');
$rayons	= $this->rayon_model->getAllFilter(8);
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

foreach ($d_type as $k => $v)
{
	if (in_array($k, $type_building))
		unset($d_type[$k]);
}

$ray = array(26,27,11);
foreach ($d_rayon as $k => $v)
{
	if (in_array($k, $ray)) unset($d_rayon[$k]);
}

$metro_building = array(51);
foreach ($d_metro as $k => $v)
{
	if (in_array($k, $metro_building)) unset($d_metro[$k]);
}


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
if(empty($params['otdelka'])) { $params['otdelka'] = ''; }
if(empty($params['fz214'])) { $params['fz214'] = ''; }
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

<div class="mainhead block-novostoy-intro" style="background: url('/asset/assets/img/assignmentwide.jpg') no-repeat top center">
	<div class="contain">
		<div class="mainfilter">
			<div class="filterchoose clearfix">
				<?php echo $this->load->view(getPath(__FILE__,'/parth_filter_menu'), array('current' => 'assignment'), TRUE); ?>
			</div>
			<div class="filtercont">
				<div class="filter-display active" id="cont-1">

					<form method="get" class="assignment-form" action="/assignment">
						<div class="nfilter<?php if($long==1):?> showlong<?php endif; ?>">
							<div class="short-filter clearfix">
								<div class="scolumn fcolumn">
									<div class="clearfix one-filter-price">
										<label>стоимость <span>(млн руб.)</span></label>
										<div class="filter-slider-container">
											<div id="price-slider-assignment" class="filter-slider range"></div>
										</div>
										<div class="prdiv price-b" data-min="<?php echo $filter->minMax['min']; ?>" data-max="<?php echo $filter->minMax['max']; ?>">
											<span class="sq_uch">от </span>
											<input type="text" name="price_from" id="assignmentPrice-from" class="price_input" value="<?php echo $params['price_from']; ?>" />
											<span class="sq_uch"> до </span>
											<input type="text" name="price_to" id="assignmentPrice-to" class="price_input" value="<?php echo $params['price_to']; ?>" />
										</div>
									</div>
									<div class="clearfix one-filter-price long-filter mtop30">
										<label>площадь <span>(м<sup>2</sup>)</span></label>
										<div class="filter-slider-container">
											<div id="square-slider-assignment" class="filter-slider range"></div>
										</div>
										<div class="prdiv square-b" data-min="<?php echo $filter->minMaxS['min']; ?>" data-max="<?php echo $filter->minMaxS['max']; ?>">
											<span class="sq_uch">от </span>
											<input type="text" name="square_from" id="assignmentSquare-from" class="price_input" value="<?php echo $params['square_from']; ?>" />
											<span class="sq_uch"> до </span>
											<input type="text" name="square_to" id="assignmentSquare-to" class="price_input" value="<?php echo $params['square_to']; ?>" />
										</div>
									</div>
								</div>
								<div class="lcolumn fcolumn">
									<div class="clearfix one-filter">
										<input type="hidden" name="rayon" value="<?php echo $params['rayon']; ?>" class="sel_build_rayon" />
										<a href="#metro_n_districts" class="shpopupa popup-modal distrolist" data-id="districts"><?php echo $rayon_text?></a>
									</div>
									<div class="clearfix one-filter long-filter">
										<input type="hidden" name="metro" value="<?php echo $params['metro']; ?>" class="sel_build_metro" />
										<a href="#metro_n_districts" class="shpopupa popup-modal metrolist" data-id="metro"><?php echo $metro_text?></a>
									</div>
									<div class="clearfix one-filter long-filter">
										<input type="text" id="assignmentcity" placeholder="ЖК" name="name" value="<?php echo $params['name']; ?>" class="filter-input filter-input-large assignment_txt">
									</div>
								</div>
								<div class="lcolumn fcolumn">
									<div class="clearfix one-filter wd235">
										<input type="hidden" name="srok" value="<?php echo $params['srok']; ?>" class="sel_build_deadline" />
										<select class="mdeadlineassignment block-right" multiple="multiple">
											<?php
											foreach (array(1,2015,2016,2017,2018) as $year)
											{
												$selected = ($params['srok'] == $year)?' selected="selected"':'';
												echo '<option value="'.$year.'"'.$selected.'>'.(($year == 1)?'Сдан':$year).'</option>';
											}
											?>
										</select>
									</div>
									<div class="clearfix one-filter long-filter wd235">
										<input type="hidden" name="builder" value="<?php echo $params['builder']; ?>" class="sel_build_builder" />
										<select class="massignmentbuilder block-right" multiple="multiple">
											<?php foreach($filter->builder as $k=>$r): ?>
												<option value="<?php echo $k; ?>" <?php if (in_array($k, $builder_array)):?>selected="selected"<?php endif;?>><?php echo $r; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="clearfix one-filter long-filter wd235">
										<input type="hidden" name="class" value="<?php echo $params['class']; ?>" class="sel_build_class" />
										<select class="massignmentclass block-right" multiple="multiple">
											<?php foreach($filter->class as $k=>$r): ?>
												<option value="<?php echo $k; ?>" <?php if (in_array($k, $class_array)):?>selected="selected"<?php endif;?>><?php echo $r; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="clearfix one-filter long-filter wd235">
										<input type="hidden" name="type" value="<?php echo $params['type']; ?>" class="sel_build_type" />
										<select class="mtypeassignment block-right" multiple="multiple">
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
											<label class="btn btn-primary<?php if(in_array(1, $room_array)) echo ' active'; ?>">
												<input type="checkbox" value="1" <?php if(in_array(1, $room_array)) echo 'checked'; ?> class="assignment-checkroom">Студия
											</label>

											<label class="btn btn-primary<?php if(in_array(2, $room_array)) echo ' active'; ?>">
												<input type="checkbox" value="2" <?php if(in_array(2, $room_array)) echo 'checked'; ?> class="assignment-checkroom">1
											</label>

											<label class="btn btn-primary<?php if(in_array(3, $room_array)) echo ' active'; ?>">
												<input type="checkbox" value="3,16" <?php if(in_array(3, $room_array)) echo 'checked'; ?> class="assignment-checkroom">2
											</label>
											<label class="btn btn-primary<?php if(in_array(4, $room_array)) echo ' active'; ?>">
												<input type="checkbox" value="4,17" class="assignment-checkroom" <?php if(in_array(4, $room_array)) echo 'checked'; ?>>3
											</label>
											<label class="btn btn-primary<?php if(in_array(5, $room_array)) echo ' active'; ?>">
												<input type="checkbox" value="5,18" class="assignment-checkroom" <?php if(in_array(5, $room_array)) echo 'checked'; ?>>4
											</label>
											<label class="btn btn-primary<?php if(in_array(6, $room_array)) echo ' active'; ?>">
												<input type="checkbox" class="assignment-checkroom" value="6,7,32,31,21" <?php if(in_array(6, $room_array)) echo 'checked'; ?>>5+
											</label>
										</div>
									</div>
									<div class="clearfix one-filter long-filter left-al">
										<label for="checkbox-1" class="custom-checkbox-wrap">
											<input id="checkbox-1" value="1" <?php if($params['ipoteka'] == 1) echo 'checked'; ?> name="ipoteka" type="checkbox" class="assignment-check">
											<span></span>Ипотека
										</label>
										<label for="checkbox-4" class="custom-checkbox-wrap">
											<input id="checkbox-4" value="1" <?php if($params['fz214'] == 1) echo 'checked'; ?> name="fz214" type="checkbox" class="assignment-check">
											<span></span>214 фз
										</label>
										<label for="checkbox-3" class="custom-checkbox-wrap">
											<input id="checkbox-3" value="1" <?php if($params['otdelka'] == 1) echo 'checked'; ?> name="otdelka" type="checkbox" class="assignment-check">
											<span></span>отделка
										</label>
									</div>
								</div>
							</div>
<!--noindex-->
							<div class="botfilter<?php if($long==1):?> longs<?php endif; ?>">
								<a href="#" class="show-hide<?php if($long==0):?> short<?php endif; ?>"><?php if($long==0):?>Расширенный поиск<?php else: ?>Свернуть<?php endif; ?></a>
								<div class="action-filter">
									<a href="/assignment">Сбросить</a>
									<button type="submit">Искать</button>
								</div>
							</div>
<!--/noindex-->
						</div>

					</form>
<!--noindex-->
					<div id="metro_n_districts" class="mfp-hide white-popup-block distromet" data-cat="assignment">
						<div class="tabs-popup">
							<a href="#" class="chpopsh" id="sdistricts">Район</a>
							<a href="#" class="chpopsh active" id="smetro">Метро</a>
						</div>
						<div class="close-popup"></div>
						<div class="mapmetro popsh active" id="pop-smetro">
							<?php foreach ($metro as $value) { ?>
								<div class="label-metro" style="top: <?php echo $value['top']; ?>px; left: <?php echo $value['left']; ?>px; <?php if ($value['width'] > 0) { ?>width:<?php echo $value['width']; ?>px;<?php } ?> <?php if (!empty($value['align'])) { ?>text-align:<?php echo $value['align']; ?>;<?php } ?> <?php if (!empty($value['letter_spacing'])) { ?>letter-spacing:<?php echo $value['letter_spacing'];?>px;<?php } ?>">
									<?php if (in_array($value['id'], $inmetro)) { ?>
										<a href="#" data-id="<?php echo $value['id']; ?>" id="metro_item_<?php echo $value['id']; ?>" class="<?php if(in_array($value['id'], $metro_array)):?>select <?php endif;?><?php if (!empty($value['class'])) { ?><?php echo $value['class']; ?><?php } ?>"><?php echo $value['label']; ?></a>
									<?php } else { ?>
										<span><?php echo $value['label']; ?></span>
									<?php } ?>
								</div>
							<?php } ?>
							<div class="distr-list metro-list">
								<div class="inner-metro">
									<?php if (count($metro > 0)) { ?>
										<?php $half = (int)ceil(count($metro) / 2);?>
										<ul>
										<?php foreach($metro as $k => $value) { ?>
											<?php if ($k == $half) { ?>
												</ul>
												<ul>
											<?php } ?>
											<li data-id="<?php echo $value['id']; ?>" class="metro_item_<?php echo $value['id']; ?><?php if (in_array($value['id'], $metro_array)) { ?> select<?php } ?>">
												<?php if (in_array($value['id'], $inmetro)) { ?>
													<a href="#"><?php echo $value['name']; ?></a>
												<?php } else { ?>
													<span><?php echo $value['name']; ?></span>
												<?php } ?>
											</li>
										<?php } ?>
										</ul>
									<?php } ?>
								</div>
							</div>
							<div class="bot-pops">
								<a href="#" class="clearmetros" id="refresh-metro">Сбросить</a>
								<button>Показать</button>
							</div>
						</div>
						<div class="districts-popup popsh" id="pop-sdistricts">
							<div class="area_maps clearfix">
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
									<div class="clearfix"></div>
								<?php endif; ?>

								<?php if(count($lenobl > 0)):?>
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
								<?php endif; ?>
								<div class="bot-pops">
									<a href="#" class="clearmetros" id="refresh-rayon">Сбросить</a>
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