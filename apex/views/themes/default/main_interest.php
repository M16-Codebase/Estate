<div class="onecol mcol-8">
	<div class="mrow clearfix">
		<p class="inter_predl">интересные предложения</p>
		<a href="/interest" class="blocks-in">показать больше</a>
		<?php foreach ($data as $key => $value) { ?>
			<?php if ($key == 0) { ?>
				<div class="onecol mcol-6">
					<a href="<?php echo $value['link']; ?>" class="object-item sm_search" data-category="SelectObject" data-label="buildings" >
						<div class="img_fil">
							<img class="filter-result-item-img" width="632" height="356" src="<?php echo image($value['foto'], "pmedium"); ?>" alt="Life">
							<span class="signcat <?php echo $value['sign']['class']; ?>"><?php echo $value['sign']['name']; ?></span>
							<div class="labels">
								<?php
								if (!empty($value['mortgage']))		echo '<span class="mortgage">Ипотека</span>';
								// if (!empty($value['fz214']))			echo '<span class="fz214">ФЗ-214</span>';
								// if (!empty($value['parking']))		echo '<span class="parking">Паркинг</span>';
								// if (!empty($value['rassrochka']))	echo '<span class="installments">Рассрочка</span>';
								// if (!empty($value['otdelka']))		echo '<span class="finishing">Отделка</span>';
								?>
							</div>
						</div>
						<div class="filter-result-item-body">
							<div class="maininterestbot">
								<p class="filter-result-item-name"><?php echo $value['name']; ?></p>
								<p class="filter-result-item-rayon"><?php echo $value['rayon']; ?></p>
							</div>
							<div class="price"><?php echo $value['price']; ?></div>
						</div>
					</a>
				</div>
			<?php } else { ?>
				<?php if ($key == 5) { ?>
							</div>
						</div>
					</div>
					<div class="mrow clearfix">
				<?php } ?>
				<div class="onecol mcol-4<?php echo ($key == 1 || $key == 4)?' nomargin':''; ?>">

					<a href="<?php echo $value['link']; ?>" class="object-item sm_search" data-category="SelectObject" data-id="<?php echo $value['id']; ?>" data-label="buildings">
						<div class="img_fil">
							<img class="filter-result-item-img" width="308" height="198" src="<?php echo image($value['foto'], "small"); ?>" alt="Life">
							<span class="signcat <?php echo $value['sign']['class']; ?>"><?php echo $value['sign']['name']; ?></span>
							<div class="labels">
								<?php
								if (!empty($value['mortgage']))		echo '<span class="mortgage">Ипотека</span>';
								// if (!empty($value['fz214']))			echo '<span class="fz214">ФЗ-214</span>';
								// if (!empty($value['parking']))		echo '<span class="parking">Паркинг</span>';
								// if (!empty($value['rassrochka']))	echo '<span class="installments">Рассрочка</span>';
								// if (!empty($value['otdelka']))		echo '<span class="finishing">Отделка</span>';
								?>
							</div>
						</div>
						<div class="priceres"><?php echo $value['price']; ?></div>
						<div class="filter-result-item-body">
							<p class="filter-result-item-rayon"><?php echo $value['rayon']; ?></p>
							<p class="filter-result-item-name"><?php echo $value['name']; ?></p>
							<?php if (isset($value['deadline'])) { ?><p class="filter-result-item-size"><?php echo $value['deadline']; ?></p><?php } ?>
							<div class="addbott">
								<div class="filter-result-item-address"><?php echo $value['address']; ?>
									<?php if (!empty($value['metro'])) { ?><span class="filter-result-item-metro"><span><?php echo $value['metro']; ?></span><?php } ?></span>
								</div>
							</div>
						</div>
						<div class="viewob">просмотр объекта</div>
					</a>
				</div>
			<?php } ?>
		<?php } ?>

