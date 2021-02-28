<style> table td { word-wrap: break-word; text-align: center; } .complex-table tr { -webkit-transition: all 0.30s ease-in-out;-moz-transition: all 0.30s ease-in-out;-ms-transition: all 0.30s ease-in-out;-o-transition: all 0.30s ease-in-out; } .complex-table tr:hover { background: #F3F0F0; } .complex-slider-wrap .complex-slider-indicators li { background-size: cover; } </style>
<section class="detail-sects">

            <div class="container">
                <div class="row">
                    <a href="/sell-appart" class="comission inset">
            Хотите <span>продать</span> квартиру?
        </a>
                    <div class="block-complex-intro-inner">
                        <div class="fixed-container clearfix">
                            <div class="block-complex-intro-col">
                                <?php if (isset($rows->h1)): ?>
                                    <h1><?= $rows->h1; ?></h1>
                                <?php else: ?>
                                    <h1>Жилой комплекс «<?= $rows->header; ?>»</h1>
                                <?php endif; ?>
                                <?= $rows->price ?>

                                <a class="backto" href="<?=$refer?>">Назад в раздел</a>
                            </div>
                            <div class="block-right text-center">
                                <?php if (empty($rows->presentation)):?>
                                <a style="display:none;" href="/asset/uploads/presentations/presentation_m16.pdf" title="М16-Недвижимость" target="_blank" class="pres-link">Скачать презентацию</a>
                                <?php else: ?>
                                    <a style="display:none;" href="<?=$rows->presentation?>" title="М16-Недвижимость" target="_blank" class="pres-link">Скачать презентацию</a>
                                <?php endif; ?>

                                <a href="#askLand" class="block-complex-intro-btn click-block-complex-intro-btn sm_search ask_popup" onclick="return true;" data-category="ButtonBuy" data-label="<?=$rows->link?>">Задать вопрос</a>
                                <!--
                                <div id="google_translate_element"></div><script type="text/javascript">
                                    function googleTranslateElementInit() {
                                        new google.translate.TranslateElement({pageLanguage: 'ru', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
                                    }
                                </script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                                -->
                                <?php if(!empty($in_favorites)):?>
                                    <a href="#" class="addtofavorite" data-category="buildings" data-id="<?php echo $rows->id; ?>" data-action="delete">Убрать из избранного</a>
                                <?php else: ?>
                                    <a href="#" class="addtofavorite notinf" data-category="buildings" data-id="<?php echo $rows->id; ?>" data-action="add">В избранное</a>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

</section>
<section class="fixed-container imagesect-resale clearfix">
    <?php if(!empty($rows->foto['foto'][0])): ?>
        <div class="slide-resale">
            <?php if(count($rows->foto['foto'])>1): ?>
                <div class="sliderkit photosgallery-captions" id="verticali-resale">
                    <div class="sliderkit-panels">
                        <div class="sliderkit-btn sliderkit-go-btn sliderkit-go-prev"><a rel="nofollow" href="#" title="Previous"><span>Previous</span></a></div>
                        <div class="sliderkit-btn sliderkit-go-btn sliderkit-go-next"><a rel="nofollow" href="#" title="Next"><span>Next</span></a></div>
                        <?php foreach($rows->foto['foto'] as $k=>$v): ?>
                            <div class="sliderkit-panel">
                                <img src="<?=image_resize($v,'resizebig')?>" alt="<?=$rows->img_alt?>" />
                                <div class="sliderkit-panel-textbox">
                                    <?php if($rows->foto['alt'][$k] != ''):?>
                                        <div class="sliderkit-panel-text">
                                            <div class="desc-foto"><?=$rows->foto['alt'][$k]?></div>
                                        </div>
                                        <div class="sliderkit-panel-overlay"></div>
                                    <?php endif;?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="sliderkit-nav">
                        <div class="sliderkit-nav-clip">
                            <ul>
                                <?php foreach($rows->foto['foto'] as $k=>$v): ?>
                                    <li><a href="#" rel="nofollow" title="<?=$rows->foto['alt'][$k]?>"><img src="<?=image($v,'bigsliderthumb')?>" width="114px" height="114px" alt="<?=$rows->img_alt?>"  /></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="sliderkit-btn sliderkit-nav-btn sliderkit-nav-prev"><a rel="nofollow" href="#" title="Previous line"><span>Previous line</span></a></div>
                        <div class="sliderkit-btn sliderkit-nav-btn sliderkit-nav-next"><a rel="nofollow" href="#" title="Previous line"><span>Previous line</span></a></div>
                    </div>
                </div>
            <?php else:?>
                <div class="mainfotoap" style="background: url('<?=image_resize($rows->foto['foto'][0],'resizebig')?>') no-repeat center center; background-size: 100%;" ></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="mapto compl-mapto">
        <div class="mapresale-static" id="mapresale-static" data-lat="<?=$rows->map_lat?>" data-lng="<?=$rows->map_lng?>">
            <img src="https://static-maps.yandex.ru/1.x/?ll=<?=$rows->map_lng?>,<?=$rows->map_lat?>&size=440,440&z=13&l=map&pt=<?=$rows->map_lng?>,<?=$rows->map_lat?>,round">
            <a href="#" onclick="return false" id="url-static"> открыть интерактивную карту</a>
</div>
        <!--<div class="ctrls">
            <a href="#" class="plusz">+</a>
            <a href="#" class="minusz">-</a>
        </div>-->
    </div>
</section>

<section class="complex-info infnov">
    <div class="infohead">
        <ul>
            <li class="active changeinfo" id="descrip"><a href="#">Описание</a></li>
            <?php if(!empty($rows->otdelka)): ?><li class="changeinfo" id="otdelka"><a href="#">Отделка квартир</a></li><?php endif; ?>
            <?php if(!empty($rows->infrastruct)): ?><li class="changeinfo" id="infrastructura"><a href="#">Инфраструктура</a></li><?php endif; ?>
            <?php if(!empty($rows->transport)): ?><li class="changeinfo" id="transportdostup"><a href="#">Транспортная доступность</a></li><?php endif; ?>
            <?php if(!empty($rows->ipoteka_text) || !empty($rows->banks_list)): ?><li class="changeinfo" id="mortgage"><a href="#">Ипотека</a></li><?php endif; ?>
			<?php if(!empty($rows->youtube)): ?><li class="changeinfo" id="video"><a href="#videolink">Видео</a></li><?php endif; ?>
		</ul>
    </div>
    <div class="fixed-container clearfix">
        <div class="complex-info-col-left">

            <dl class="complex-info-list">
                <table>
                    <tr>
                        <td class="avt">
                            <img src="/asset/assets/img/srocico.png" />
                        </td>
                        <td valign="middle">
                            Срок сдачи: <?php echo $rows->srok; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="avt">
                            <img src="/asset/assets/img/placeico.png" />
                        </td>
                        <td valign="middle" itemprop="streetAddress">
                            <?php echo $rows->adress; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="avt">
                            <img src="/asset/assets/img/metroico.png" />
                        </td>
                        <td valign="middle">
                            <?php echo $rows->metro; ?>
                        </td>
                    </tr>
                </table>

            </dl>

            <dl class="complex-info-list">

                <dt>Район:</dt>
                <dd itemprop="addressLocality"><?php echo $rows->rayon; ?>&nbsp;</dd>
                <dt>Тип дома:</dt>
                <dd><?php echo $rows->type; ?>&nbsp;</dd>
                <?php if(!empty($rows->ipoteka) && $rows->ipoteka != 'Нет'): ?>
                    <dt>Ипотека:</dt>
                    <dd><?php echo $rows->ipoteka; ?>&nbsp;</dd>
                <?php endif; ?>
                <?php if(uri(1) == 'elite'): ?>
                    <?php if(!empty($rows->vid)): ?>
                        <dt>С видом:</dt>
                        <dd><?php echo $rows->vid; ?>&nbsp;</dd>
                    <?php endif; ?>
                    <?php if(!empty($rows->club)): ?>
                        <dt>Клубный дом:</dt>
                        <dd><?php echo $rows->club; ?>&nbsp;</dd>
                    <?php endif; ?>
                <?php endif; ?>
            </dl>
            <?php if(!empty($rows->builder)): ?>

<!--                <div class="builderinfo" style="border: 1px solid #cbcbcb; margin: 0; border-top: none; padding: 10px; width: 360px;">
                    <!-- <img src="<?=$rows->builder['image'];?>" />-->
 <!--                    <b>Застройщик:</b>&nbsp;
                    <?=$rows->builder['name'];?>
                </div>
                -->


            <? endif; ?>
            <?php if(is_admins()): ?><p><a class="complex-title" style="font-size: 14px; border: 1px solid; padding: 3px 6px; border-bottom: 0; border-top: 0;" href="/buildings/admin/index/edit/<?php echo $id; ?>" target="_blank">Редактировать в админке</a></p><?php endif; ?>
                    <?php
                    $pricc=array();
                     foreach($rows->appartments as $tableID => $val){
                       foreach($val as $vasl){
                        $pricc[]=(int)(str_replace(' ','',$vasl['price']));
                        }
                    }
                    //echo'<pre>';print_r($pricc);echo'</pre>';
                   // echo max($pricc);
            ?>
            <div class="complex-info-list" style="border-top: none">
                <div itemprop="Product" itemscope="" itemtype="http://schema.org/Product">
                    <meta itemprop="name" content="ЖК <?= $rows->header ?>">
                    <meta itemprop="description" content="<?= strip_tags($rows->text) ?>">
                    <div itemprop="offers" itemscope="" itemtype="http://schema.org/AggregateOffer">
                        <meta itemprop="lowPrice" content="<?= $rows->dig_price; ?>">
                        <meta itemprop="highPrice" content="<?= (int) max($pricc); ?>">
                        <meta itemprop="priceCurrency" content="RUB">
                    </div>
                    <div id="complex_rating" class="complex-rating-wrapper" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
                        <dl>
                            <dt>Рейтинг ЖК:</dt>
                            <dd>
                                <div id="jk-raty" class="demo" style="padding-left: 20px; text-align: center;"></div>
                            </dd>
     
                            <style>#starr {margin: 0px}</style>
                            <meta itemprop="ratingValue" content='<?= (int) $rows->ratval; ?>'>
                            <meta itemprop="reviewCount" content='<?= (int) $rows->raterval; ?>'>
                            <meta itemprop="bestRating" content="5">
                            <meta itemprop="worstRating" content="0">
                        </dl>
                    </div>
                </div>
            </div>

        </div>
        <div class="complex-info-col-right">
            <div class="active infobl descrip">
                <h2 class="complex-title">О комплексе</h2>
                <div class="complex-text"><?php echo $rows->text; ?></div>
            </div>
            <?php if(!empty($rows->otdelka)): ?>
            <div class="infobl otdelka">
                <h2 class="complex-title">Отделка квартир</h2>
                <div class="complex-text"><?php echo $rows->otdelka; ?></div>
                <?php if(count($rows->foto_otdelka['foto']) > 0): ?>
                    <ul class="otd_foto">
                        <?php foreach($rows->foto_otdelka['foto'] as $k=>$v): ?>
                            <li><a href="<?=$v?>" rel="galap" class="fancybox"><img src="<?=$v?>" width="100px" height="100px" /></a></li>
                        <?php endforeach; ?>
                    </ul>

                <?php endif; ?>
            </div>
            <?php endif; ?>
            <?php if(!empty($rows->infrastruct)): ?>
            <div class="infobl infrastructura">
                <h2 class="complex-title">Инфраструктура</h2>
                <div class="complex-text"><?php echo $rows->infrastruct; ?></div>
            </div>
            <?php endif; ?>
            <?php if(!empty($rows->transport)): ?>
            <div class="infobl transportdostup">
                <h2 class="complex-title">Транспортная доступность</h2>
                <div class="complex-text"><?php echo $rows->transport; ?></div>
            </div>
            <?php endif; ?>
            <?php if(!empty($rows->ipoteka_text) || !empty($rows->banks_list)): ?>
                <div class="infobl mortgage">
                    <h2 class="complex-title">Ипотека</h2>
                    <div class="complex-text">
                        <?php echo $rows->ipoteka_text; ?>
                        <?php if(!empty($rows->banks_list) && count($rows->banks_list) > 0){ ?>
                            <div class="banks_list clearfix">
                            <?php foreach($rows->banks_list as $k=>$v) { ?>
                                <div class="bankon">
                                    <div class="showingbank">
                                        <div class="img_bank"><img src="<?=$v['logo']?>" /></div>
                                        <div class="bank_name">
                                            <span><?=$v['name']?></span>
                                            <?php if (!empty($rows->banks_programms[$v['id']]) && count($rows->banks_programms[$v['id']]) > 0){ ?>
                                             <a href="#" class="bclause">условия</a>
                                             <?php } ?>
                                        </div>
                                    </div>
                                    <?php if (!empty($rows->banks_programms[$v['id']]) && count($rows->banks_programms[$v['id']]) > 0){ ?>

                                        <div class="banks_programms">
                                            <table class="bprogramms">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            Программа
                                                        </th>
                                                        <th>
                                                            Срок кредита, лет
                                                        </th>
                                                        <th>
                                                            Первый взнос от, %
                                                        </th>
                                                        <th>
                                                            Процентная ставка, % годовых
                                                        </th>
                                                        <th>
                                                            Сумма кредита, мин. руб
                                                        </th>
                                                        <th>
                                                            Сумма кредита, макс. руб
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($rows->banks_programms[$v['id']] as $kb=>$vb) { ?>
                                                        <tr>
                                                            <td>
                                                                <?=$vb['name']?>
                                                            </td>
                                                            <td>
                                                                <?=$vb['period']?>
                                                            </td>
                                                            <td>
                                                                <?=$vb['down_payment']?>
                                                            </td>
                                                            <td>
                                                                <?=$vb['percent']?>
                                                            </td>
                                                            <td>
                                                                <?=$vb['total_min']?>
                                                            </td>
                                                            <td>
                                                                <?=$vb['total_max']?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <span class="bp-close"></span>
                                        </div>
                                        <span class="bkline"></span>
                                    <?php } ?>

                                </div>
                            <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

</section>
<!--
<section class="fixed-container long-cont">
    <img src="/asset/assets/img/m16malofeev.jpg">
</section>
-->
<div class="contain clearfix">
    <div class="lft-object">
        <div class="specinttop">
            <p class="complex-title"><span class="special-obj">Спецпредложения</span><span class="arrtoint"></span><span class="interest-obj"><a href="#" class="changeinblock gotointer">интересное</a></span></p>
            <div class="overhid">
                <div class="blocktopshow">
				
                    <div class="chbl interest-obj-show"><?php 
					$this->load->module('special')->showObject($rows->id); ?></div>
                    <div class="chbl special-obj-show">
					<?php $this->load->module('interest')->showObjectPg($rows->id); ?>
					</div>
					
                </div>
            </div>
        </div>
        <?php $l = $ll = 1; ?>
        <?php if(!empty($rows->appartments)): ?>
        <?php ksort($rows->appartments); ?>
        <section class="complex-order" id="scroll-child">
            <div class="complex-order-header clearfix">
                <ul class="complex-order-switcher">
                    <li class="js-dependent-link active" data-dependent="table-all"><a href="#">Все</a></li>
                    <?php foreach($rows->appartments as $tableID => $tableForeach): ?>
                    <li class="js-dependent-link <?php //if($l == 1) { echo 'active'; } $l++; ?>" data-dependent="table-<?php echo $tableID; ?>"><a href="#" class="sm_search" data-category="Type" data-label="<?=$rows->link?>"><?php echo $room[$tableID]; ?></a></li>
                    <?php endforeach; ?>
                </ul>
                <p class="complex-title">Квартиры и планировки</p>
                <a href="/calculator" class="complex-calc-btn sm_search" data-category="Calculator" data-label="<?=$rows->link?>">ИПОТЕЧНЫЙ КАЛЬКУЛЯТОР</a>
            </div>
            <div>

                <table class="tablesorter complex-table table table-bordered active sortings" data-dependent="table-all">
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th style="width: 90px;">Комнат</th>
                            <th style="width: 80px;">Этаж</th>
                            <th>Площадь</th>
                            <th>Отделка</th>
                            <th>Планировка</th>
                            <th>Стоимость</th>
                        </tr>
                    </thead>
                    <?php $cntl = 0; ?>
                    <?php foreach($rows->appartments as $tableID => $tableForeach):
                            $notClicked = '';
                        if (empty($val['link'])) {
                            $notClicked = 'not-clicked';
                        }?>
                    <?php foreach($tableForeach as $val): ?>
                    <tr data-href="/buildings/<?=$rows->link?>/<?php echo $val['link']; ?>"
                        class="<?=$notClicked?><?php if($cntl>11):?> hidds notshow<?php endif;?>">
                        <td><?php echo $val['id']; ?></td>
                        <td><?php echo $val['room_id']; ?></td>
                        <td><?php echo $val['floor']; ?></td>
                        <td><?php echo $val['square_all']; ?></td>
                        <td><?php echo $val['otdelka_id']; ?></td>
                        <td class="planirov"><a href="<?php echo $val['main_foto']; ?>" class="fancybox"><img src="<?=image_resize($val['main_foto'],'plan', false)?>" alt="" /></a></td>
                        <td class="planirov">от <span><?php echo $val['price']; ?></span> руб.<br><a href="#askAppartment" data-id="<?php echo $val['id']; ?>" class="complex-table-btn idx-<?php echo $val['id']; ?> sm_search" data-category="Reserve" data-rooms="<?php echo $val['room_id']; ?>" data-room="<?php echo $val['room']; ?>" data-img="<?php echo $val['main_foto']; ?>" data-price="<?php echo $val['price']; ?>" data-label="<?=$rows->link?>" onclick="yaCounter29760432.reachGoal('novostroy-kvart-ost-zayav'); _gaq.push(['_trackEvent', 'ZadatVoprosKvartiraZHK', 'Click']); return true;">ЗАДАТЬ ВОПРОС</a></td>
                    </tr>
                    <?php $cntl++; ?>
                    <?php endforeach; ?>
                    <?php endforeach; ?>
                    <?php if($cntl>11):?>
                    <tfoot>
                        <tr class="notbrd"><td colspan="6" class="planirov"><a href="#" class="showmoreappt">Показать все квартиры</a></td></tr>
                    </tfoot>
                    <?php endif; ?>
                </table>


                <?php foreach($rows->appartments as $tableID => $tableForeach): ?>
                <table class="tablesorter sortings complex-table table table-bordered <?php //if($ll == 1) { echo 'active'; } $ll++; ?>" data-dependent="table-<?php echo $tableID; ?>">
                    <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th style="width: 90px;">Комнат</th>
                        <th style="width: 80px;">Этаж</th>
                        <th>Площадь</th>
                        <th>Отделка</th>
                        <th>Планировка</th>
                        <th>Стоимость</th>
                    </tr>
                    </thead>
                    <?php $cntl = 0; ?>
                    <?php foreach($tableForeach as $val): ?>
                    <tr data-href="/buildings/<?=$rows->link?>/<?php echo $val['link']; ?>" class="<?php if($cntl>11):?>hidds notshow<?php endif; ?>">
                        <td><?php echo $val['id']; ?></td>
                        <td><?php echo $val['room_id']; ?></td>
                        <td><?php echo $val['floor']; ?></td>
                        <td><?php echo $val['square_all']; ?></td>
                        <td><?php echo $val['otdelka_id']; ?></td>
                        <td class="planirov"><a href="<?=$val['main_foto']?>" class="fancybox"><img src="<?=image_resize($val['main_foto'],'plan', false)?>" alt="" /></a></td>
                        <td class="planirov">от <span><?php echo $val['price']; ?></span> руб.<br><a href="#askAppartment" data-id="<?php echo $val['id']; ?>" class="complex-table-btn idx-<?php echo $val['id']; ?> sm_search" data-category="Reserve" data-rooms="<?php echo $val['room_id']; ?>" data-room="<?php echo $val['room']; ?>" data-img="<?php echo $val['main_foto']; ?>" data-price="<?php echo $val['price']; ?>" data-label="<?=$rows->link?>" onclick="yaCounter29760432.reachGoal('novostroy-kvart-ost-zayav'); _gaq.push(['_trackEvent', 'ZadatVoprosKvartiraZHK', 'Click']); return true;">ЗАДАТЬ ВОПРОС</a></td>
                    </tr>
                    <?php $cntl++; ?>

                    <?php endforeach; ?>
                    <?php if($cntl>11):?>
                    <tfoot>
                        <tr class="notbrd"><td colspan="6" class="planirov"><a href="#" class="showmoreappt">Показать все квартиры</a></td></tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
        <input type="hidden" class="sm_zakaz" data-label="<?=$rows->link?>">
    </div>
    <!--noindex-->
    <div class="rght-object">
        <div class="gift-choose">
            фирменный подарок<br>
            от вячеслава малафеева!<br>
            <a href="/gifts">выбрать</a>
        </div>
        <div class="advantage-block">
            <div class="title-advan">
                Наши преимущества
            </div>
            <?php $rand = rand(1, 13); ?>
            <div class="vmalf" style="background: #FFF url('/asset/uploads/images/managers/random/<?=$rand?>.jpg') no-repeat left top;"></div>
            <div id="advantage-accordion">
                <h3><span>1</span>Индивидуальный подход и большой выбор объектов</h3>
                <div>
                    <p>
                        Более 50 тысяч предложений на первичном и вторичном рынке.
                    </p>
                    <p>
                        Ипотечные программы от 10 ведущих банков.
                    </p>
                    <p>
                        Высокий уровень сервиса и индивидуальный подход.
                    </p>
                    <p>
                        Предоставление информации удобным способом: SMS, электронная почта, социальные сети, WhatsApp, Viber, Skype.
                    </p>
                </div>
                <h3><span>2</span>Гарантии<br>
                    и безопасность</h3>
                <div>
                    <p>
                        Полное правовое сопровождение сделки и проверка юридической чистоты объекта.
                    </p>
                    <p>
                        Тщательное соблюдение стандартов работы.
                    </p>
                    <p>
                        Безупречная репутация и известный бренд компании.
                    </p>
                </div>
                <h3><span>3</span>Cервис<br>
                    и комфорт
                </h3>
                <div>
                    <p>
                        Бесплатное проживание в апарт-отеле.
                    </p>
                    <p>
                        Предпродажная подготовка вашей квартиры за счет компании.
                    </p>
                    <p>
                        Профессиональная фотосъемка вашей недвижимости.
                    </p>
                    <p>
                        Предоставление водителей на комфортных автомобилях бизнес-класса для показов объектов, сопровождения на сделку, встречи в аэропорту или вокзале иногородних клиентов.
                    </p>
                </div>
                <h3><span>4</span>Гибкие условия<br>
                    сделок и уникальные<br>
                    предложения</h3>
                <div>
                    <p>
                        Недвижимость в новостройках по ценам застройщиков, без комиссии.
                    </p>
                    <p>
                        Срочный выкуп квартир на вторичном рынке.
                    </p>
                    <p>
                        Выкуп квартиры у застройщика в пользу клиента.
                    </p>
                    <p>
                        Кредитование  под залог квартиры.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!--/noindex-->
</div>


<?php if (count($rows->likeas)>0):?>
    <section class="fil-result-nov likeas">
        <div class="fixed-container clearfix fixsee">
            <p class="complex-title">Похожие объекты</p>
            <ul class="yousee" <?php if (count($rows->likeas)>2):?>id="yousee"<?php endif ?> >
                <?php foreach($rows->likeas as $see_key => $see_value): ?>
                    <li class="onecol mcol-4">
                        <a href="<?php echo $see_value['link']; ?>" class="object-item sm_search" data-category="SelectObject" data-label="buildings">
                            <div class="priceres"><?=$see_value['price']?></div>
                            <div class="filter-result-item-body">
                                <p class="filter-result-item-rayon"><?php echo $see_value['rayon']; ?></p>
                                <p class="filter-result-item-name"><?php echo $see_value['name']; ?></p>
                                <p class="filter-result-item-size"><?php echo $see_value['srok']; ?></p>
                                <div class="addbott">
                                    <div class="filter-result-item-address"><?php echo $see_value['adress']; ?>
                                        <span class="filter-result-item-metro"><span><?=$see_value['metro']?></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="viewob">просмотр объекта</div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

        </div>
    </section>
<?php endif; ?>


<div id="askLand" class="mfp-hide white-popup-block askpopup" data-name="Новостройки | <?=$rows->header;?>" data-link="<?=getUrl();?>">
    <p class="ask_caption">Ответим на ваш вопрос, в течение ближайших 5 минут, либо в любое удобное для вас время</p>
    <form id="land_form_ask"  data-catalog="apartments">
        <div class="rowd">
            <textarea id="comment" data-question="Меня интересует ЖК «<?=$rows->header;?>». Расскажите, пожалуйста, мне побольше об этом объекте.">Меня интересует ЖК «<?=$rows->header;?>». Расскажите, пожалуйста, мне побольше об этом объекте.</textarea>
        </div>
        <div class="rowd">
            <input type="text" name="name" id="name" class="require" placeholder="Имя" />
        </div>
        <div class="rowd">
            <input type="text" name="phone" id="phone" class="require phoneFormat" placeholder="Телефон" />
        </div>
        <div class="rowd">
            <input type="text" name="email" id="email" placeholder="Email" />
        </div>
        <div class="rowd">
            Удобное время для звонка:  с <input type="text" name="better_call" class="timetocall" id="better_call_min" value="10:00" readonly /> до <input type="text" name="better_call" class="timetocall" id="better_call_max" value="20:00" readonly />
        </div>

        <label class="checkbox-block"><div class="checkboxes" id="sub_news"></div>Подписаться на новости компании</label>
        <label class="checkbox-block">
            <div class="checkboxes active" id="agree"></div>Принимаю <a href="/privacy_policy/" target="_blank">соглашение на обработку персональных данных</a>
        </label>
        <div class="rowd">
            <input type="submit" class="submit-ask" name="submit" value="отправить" />
        </div>
    </form>
</div>


<div id="askSale" class="mfp-hide white-popup-block askpopup" data-name="Новостройки | <?=$rows->header;?>" data-link="<?=getUrl();?>">
    <p class="ask_caption text-center">Свяжемся с Вами в удобное для Вас время и расскажем все подробности об акции</p>
    <form id="land_form_ask">
        <div class="rowd">
            <textarea id="comment" data-question="Расскажите, пожалуйста, мне побольше об акции в ЖК «<?=$rows->header;?>».">Расскажите, пожалуйста, мне побольше об акции в ЖК «<?=$rows->header;?>».</textarea>
        </div>
        <div class="rowd">
            <input type="text" name="name" id="name" class="require" placeholder="Имя" />
        </div>
        <div class="rowd">
            <input type="text" name="phone" id="phone" class="require phoneFormat" placeholder="Телефон" />
        </div>
        <div class="rowd">
            <input type="text" name="email" id="email" placeholder="Email" />
        </div>
        <div class="rowd">
            Удобное время для звонка:  с <input type="text" name="better_call" class="timetocall" id="better_call_min" value="10:00" readonly /> до <input type="text" name="better_call" class="timetocall" id="better_call_max" value="20:00" readonly />
        </div>
        <label class="checkbox-block"><div class="checkboxes" id="sub_news"></div>Подписаться на новости компании</label>
        <label class="checkbox-block"><div class="checkboxes active" id="agree"></div>Принимаю <a href="/privacy_policy/" target="_blank">соглашение на обработку персональных данных</a></label>
        <input type="hidden" value="05fa7144fd39051c2b3e0e512f357239" name="oauth_token">
        <div class="rowd">
            <input type="submit" class="submit-ask" name="submit" value="отправить" />
        </div>
    </form>
</div>


<div id="askAppartment" class="mfp-hide white-popup-block askpopup" data-name="Новостройки | <?=$rows->header;?>" data-link="<?=getUrl();?>" data-complex="<?=$rows->header;?>" >
    <p class="ask_caption">Ответим на ваш вопрос, в течение ближайших 5 минут, либо в любое удобное для вас время</p>
    <form id="app_form_ask">
        <div class="rowd">
            <textarea id="comment" data-question="Меня интересует ЖК «<?=$rows->header;?>». Расскажите, пожалуйста, мне побольше об этом объекте.">Меня интересует ЖК «<?=$rows->header;?>». Расскажите, пожалуйста, мне побольше об этом объекте.</textarea>
        </div>
        <div class="rowd">
            <input type="text" name="name" id="name" class="require" placeholder="Имя" />
        </div>
        <div class="rowd">
            <input type="text" name="phone" id="phone" class="require phoneFormat" placeholder="Телефон" />
        </div>
        <div class="rowd">
            <input type="text" name="email" id="email" placeholder="Email" />
        </div>
        <div class="rowd">
            Удобное время для звонка:  с <input type="text" name="better_call" class="timetocall" id="better_call_min" value="10:00" readonly /> до <input type="text" name="better_call" class="timetocall" id="better_call_max" value="20:00" readonly />
        </div>

        <label class="checkbox-block"><div class="checkboxes" id="sub_news"></div>Подписаться на новости компании</label>
        <label class="checkbox-block"><div class="checkboxes active" id="agree1"></div>Принимаю <a href="/privacy_policy/" target="_blank">соглашение на обработку персональных данных</a></label>
        <div class="rowd">
            <input type="submit" class="submit-ask" name="submit" value="отправить" onclick="yaCounter29760432.reachGoal('otpravit-zayav'); _gaq.push(['_trackEvent', 'OtpravitVoprosKvartiraZHK', 'Click']); return true;" />
        </div>
    </form>
</div>
<div id="videolink">
<?php echo $rows->youtube; ?>
</div>


<?


/*
 * в контроллере метод вью урл=билдингс добавлен код для добавления файла скрипта
 *
 *
 */
?>
<style>
    #ny_popup .modal-content {
        background: url('/asset/css/images/ny_popup.jpg');
        width: 100%;
        height: 499px;
        background-size: cover;
        position: relative;
        border-radius: 0;
        border: 0 !important;
    }

    #ny_popup .modal-content .close {
        background: url(/asset/css/images/ny_popup_close.png);
        width: 50px;
        height: 50px;
        background-size: cover;
    }

    #ny_popup .modal-content .phone1>a {
        position: absolute;
        bottom: 101px;
        left: 28%;
        background: url(/asset/css/images/ny_popup_phone.png);
        background-size: cover;
        width: 44%;
        height: 35px;
    }

    .modal-backdrop.in {
        filter: alpha(opacity=50);
        opacity: 0.7;
    }

    #ny_popup .modal-content .promo{
        position: absolute;
        bottom: 27px;
        height: 41px;
        width: 36%;
        border: 3px solid #000;
        left: 32%;
    }


    #ny_popup .modal-content .promo>p{
        height: 100%;
        margin: 0;
        text-align: center;
        text-transform: uppercase;
        font-size: 3rem;
        font-weight: bold;
        line-height: 3.5rem;
    }

    #ny_popup .modal-content .know{
        position: absolute;
        bottom: 67px;
        font-weight: bold;
        left: 7%;
    }
</style>

<!-- Modal -->
<div class="modal fade" id="ny_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
            <div class="phone1">
                <a href="tel:88005505516"></a>
            </div>
            <p class="know">
                Узнайте больше у менеджеров по продажам, сообщив следующий код:
            </p>
            <div class="promo">
                <p id="promo"></p>
            </div>
        </div>
    </div>
</div>
