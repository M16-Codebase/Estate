<section id="intcd" class="container-fluid fil-result-nov main-content interest-offer"
         style="background: transparent;"> <!--интересные предложения-->
    <div class="container">
        <div class="wrapTextCenter"><h2>Интересные предложения</h2><a href="/interest" class="blocks-in"
                                                                      style="color: #009ce0; text-decoration: none; padding-top: 0px;">показать
                больше</a></div>
        <div class="row clearfix">
            <div class="filter-result clearfix">
                <div class="contain">
                    <div class="mrow clearfix"><?php foreach ($data as $key => $value) {
                            if ($key > 3) {
                                break;
                            } ?>
                            <div class="onecol mcol-4<?php echo ($key == 3) ? ' nomargin' : ''; ?>"><a
                                    href="<?php echo $value['link']; ?>" class="object-item sm_search"
                                    data-category="SelectObject" data-label="buildings">
                                <div class="img_fil"><img class="filter-result-item-img"
                                                          data-src="<?php echo image($value['foto'], "small"); ?>"
                                                          alt="Life"><span
                                            class="signcat <?php echo $value['sign']['class']; ?>"><?php echo $value['sign']['name']; ?></span>
                                    <div class="labels"><?php if (!empty($value['mortgage'])) echo '<span class="mortgage">Ипотека</span>';// if (!empty($value['fz214']))			echo '<span class="fz214">ФЗ-214</span>'; if (!empty($value['parking']))		echo '<span class="parking">Паркинг</span>'; if (!empty($value['rassrochka']))	echo '<span class="installments">Рассрочка</span>'; if (!empty($value['otdelka']))		echo '<span class="finishing">Отделка</span>';?></div>
                                </div>
                                <div class="priceres"><?php echo $value['price']; ?></div>
                                <div class="filter-result-item-body"><p
                                            class="filter-result-item-rayon"><?php echo $value['rayon']; ?></p>
                                    <p class="filter-result-item-name"><?php echo $value['name']; ?></p><?php if (isset($value['deadline'])) { ?>
                                        <p class="filter-result-item-size"><?php echo $value['deadline']; ?></p><?php } ?>
                                    <div class="addbott">
                                        <div class="filter-result-item-address"><?php echo $value['address']; ?> <?php if (!empty($value['metro'])) { ?>
                                            <span class="filter-result-item-metro"><span><?php echo $value['metro']; ?></span><?php } ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="viewob">просмотр объекта</div>
                            </a> </div> <?php } ?></div>
                </div>
            </div>
        </div>
    </div>
</section>