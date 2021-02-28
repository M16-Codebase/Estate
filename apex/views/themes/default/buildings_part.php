<?php if(!isset($params['page'])) { $params['page'] = ''; } ?>
<section class="container-fluid fil-result-nov">
	<div>
		<div class="contain">
			    <?php if (strpos($_SERVER['REQUEST_URI'], 'buildings') == false){ ?>
<a href="/sell-appart" class="comission">
            Хотите <span>продать</span> квартиру?
        </a>
<?php } ?>
			<div class="relrow">
				<div class="main_nav_block">
					<div class="filter-result-sort">
						<a href="#" class="fastss">Быстрый поиск</a>
						<select class="sortings-filt-date">
							<option value="asc"<?php echo ($this->session->userdata('sort_price_buildings') == 'asc')?' selected="selected"':''; ?>>цена по возрастанию</option>
							<option value="desc"<?php echo ($this->session->userdata('sort_price_buildings') == 'desc')?' selected="selected"':''; ?>>цена по убыванию</option>
						</select>
						<div class="show_right_sort">
							<a href="#" class="<?php echo ($shown['plitter_buildings'] == 'list')?'chplit ':''; ?>plit plist" data-value="plit">Плитка</a>
							<a href="#" class="<?php echo ($shown['plitter_buildings'] == 'plit')?'chplit ':''; ?>lisst plist" data-value="list">Список</a>
							<a href="#novlink" class="onmap">На карте</a>
						</div>
					</div>
					<?php if ($empty) { ?>
						<div id="notFoundForm">
							<p class="head">По вашему запросу ничего не найдено</p>
							<p class="msg">Хотите, чтобы консультанты помогли подобрать лучший вариант?</p>
							<input type="text" name="phone" class="phoneFormat" placeholder="Телефон" /><button class="submit-ask">Жду звонка</button>
						</div>
					<?php } ?>
					<div class="filter-result-paging sm_selectpage headpag" data-category="SelectPage" data-label="buildings">
						<?php if (isset($params['page']) AND $params['page'] !== 'all' && !empty($pagination)) { ?>
							<?php echo $pagination; ?>
							<br><br>
							<a href="/<?php echo uri(1); ?>/?<?php echo $show_all; ?>" rel="nofollow" class="filter-result-paging-link sm_search" data-category="ShowAll" data-label="buildings" style="margin-top: 10px; font-size: 13px; margin-left: 0;">Показать все новостройки</a>
						<?php } ?>
					</div>
				</div>
				<?php $this->load->view('themes/default/tmpl/fastsearch_buildings'); ?>

				<?php if ($shown['plitter_buildings'] == 'plit') {?>
				<div class="filter-result">
					<div class="mrow clearfix">
						<?php $k = 1; ?>
						<?php foreach ($rows as $r) { ?>
						<div class="onecol mcol-4<?php echo ($k == 4)?' nomargin':''; ?>">
							<a href="<?php echo $r->link; ?>" class="object-item sm_search" data-category="SelectObject" data-label="buildings">
								<div class="img_fil">
									<img class="filter-result-item-img" src="<?php echo image($r->foto, "small"); ?>" alt="Life">
									<!--<span class="signcat <?//=$r->sign['class']?>"><?//=$r->sign['name']?></span>-->
									<div class="labels">
										<?php
										if (!empty($r->mortgage))	echo '<span class="mortgage">Ипотека</span>';
										if (!empty($r->fz214))		echo '<span class="fz214">ФЗ-214</span>';
										if (!empty($r->parking))	echo '<span class="parking">Паркинг</span>';
										if (!empty($r->rassrochka))	echo '<span class="installments">Рассрочка</span>';
										if (!empty($r->otdelka))	echo '<span class="finishing">Отделка</span>';
										?>
									</div>
								</div>
								<div class="priceres"><?php echo $r->price; ?></div>
								<div class="filter-result-item-body">
									<p class="filter-result-item-rayon"><?php echo $r->rayon; ?></p>
									<p class="filter-result-item-name"><?php echo $r->name; ?></p>
									<?php if (isset($r->srok)) { ?><p class="filter-result-item-size"><?php echo $r->srok; ?></p><?php } ?>
									<div class="addbott" style="position:relative; top:20px;">
										<div class="filter-result-item-address"><?php echo $r->adress; ?>
											<span class="filter-result-item-metro"><span><?php echo $r->metro; ?></span></span>
										</div>
									</div>
								</div>
							</a>
						</div>
						<?php if ($k++ == 4) $k = 1; ?>
						<?php } ?>
					</div>
				</div>
				<?php } else {?>
				<div class="filter-result plitter">
					<?php foreach ($rows as $r) { ?>
						<div class="filter-result-row clearfix">
							<a href="<?php echo $r->link; ?>" class="filter-result-item-plit sm_search" data-category="SelectObject" data-label="buildings">
								<div class="img_fil">
									<p class="filter-result-item-name"><?php echo $r->name; ?></p>
									<img class="filter-result-item-img" src="<?php echo image($r->foto, "medium"); ?>" alt="<?php echo $r->name; ?>">
									<div class="labels">
										<?php
										if (!empty($r->mortgage))	echo '<span class="mortgage">Ипотека</span>';
										if (!empty($r->fz214))		echo '<span class="fz214">ФЗ-214</span>';
										if (!empty($r->parking))	echo '<span class="parking">Паркинг</span>';
										if (!empty($r->rassrochka))	echo '<span class="installments">Рассрочка</span>';
										if (!empty($r->otdelka))	echo '<span class="finishing">Отделка</span>';
										?>
									</div>
								</div>
								<div class="filter-result-item-body">
									<div class="priceres"><?php echo $r->price; ?></div>
									<p class="filter-result-item-size"><?php echo $r->srok; ?></p>
									<div class="infoit">
										<div class="initem"><label>Район</label><p class="filter-result-item-rayon"><?php echo $r->rayon; ?></p></div>
										<div class="initem"><label>Метро</label><p class="filter-result-item-metro"><span><?php echo $r->metro; ?></span></p></div>
										<div class="initem"><label>Адрес</label><p class="filter-result-item-address"><?php echo $r->adress; ?></p></div>
									</div>
									<span class="plit-more">Подробнее</span>
								</div>
								<div class="viewob">просмотр новостройки</div>
							</a>
						</div>
					<?php } ?>
				</div>
				<?php }?>
				<div class="filter-result-paging sm_selectpage" data-category="SelectPage" data-label="buildings">
					<?php if ($params['page'] !== 'all' && !empty($pagination)) { ?>
						<?php echo $pagination; ?>
						<br><br>
						<a href="/<?php echo uri(1); ?>/?<?php echo $show_all; ?>" rel="nofollow" class="filter-result-paging-link sm_search" data-category="ShowAll" data-label="buildings" style="margin-top: 10px; font-size: 13px; margin-left: 0;">Показать все ЖК</a>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</section>