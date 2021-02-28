<style>
    .resale-complex { padding: 25px !important; }
    .resale-complex .item { height: auto !important; }
    .resale-complex .complex-slider-indicators li { background-size: cover; }
</style>

<section class="detail-sects">

    <div class="container">
        <a href="/sell-appart" class="comission inset row">
            Хотите <span>продать</span> квартиру?
        </a>
        <div class="row">

            <div class="block-complex-intro-inner">
                <div class="fixed-container clearfix">
                    <div class="block-complex-intro-col">

                        <?php if (isset($rows->h1)): ?>
                            <h1><?= $rows->h1; ?></h1>
                        <?php else: ?>
                            <h1><?= $rows->header; ?></h1>
                        <?php endif; ?>
                        <?=$rows->price?>
                        
                        <a class="backto" href="/<?=uri(1)?>">Назад в раздел</a>
                    </div>
                    <div class="block-right text-center">
                        <?php if (empty($rows->presentation)):?>
                            <a style="display:none;" href="/asset/uploads/presentations/presentation_m16.pdf" title="М16-Недвижимость" target="_blank" class="pres-link">Скачать презентацию</a>
                        <?php else: ?>
                            <a style="display:none;" href="<?=$rows->presentation?>" title="М16-Недвижимость" target="_blank" class="pres-link">Скачать презентацию</a>
                        <?php endif; ?>
                        <a href="#askLand" class="block-complex-intro-btn click-block-complex-intro-btn sm_search ask_popup" onclick="yaCounter29760432.reachGoal('vse-ostavit-zayav'); return true;" data-category="ButtonBuy" data-label="<?=$rows->link?>">Задать вопрос</a>
                        <div id="google_translate_element"></div><script type="text/javascript">
                            function googleTranslateElementInit() {
                                new google.translate.TranslateElement({pageLanguage: 'ru', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
                            }
                        </script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                        <?php if(!empty($in_favorites)):?>
                            <a href="#" class="addtofavorite" data-category="residential" data-id="<?php echo $id; ?>" data-action="delete">Убрать из избранного</a>
                        <?php else: ?>
                            <a href="#" class="addtofavorite notinf" data-category="residential" data-id="<?php echo $id; ?>" data-action="add">В избранное</a>
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
                                <img src="<?=image_resize($v,'resizebig')?>" alt="<?=$rows->img_alt?>"  />
                                <div class="sliderkit-panel-textbox">
                                    <?php if($rows->foto['alt'][$k] != ''):?>
                                        <div class="sliderkit-panel-text">
                                            <h4><?=$rows->foto['alt'][$k]?></h4>
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
        <div class="mapresale" id="mapresale" data-lat="<?=$rows->map_lat?>" data-lng="<?=$rows->map_lng?>"></div>
        <!--<div class="ctrls">
            <a href="#" class="plusz">+</a>
            <a href="#" class="minusz">-</a>
        </div>-->
    </div>
</section>
<?php if (!empty($rows->cottages) && !empty($rows->houses) && count($rows->cottages) > 0):?>
    <div class="cottages-list">
        <div class="filter-result">

            <div class="contain">
                <p class="complex-title">Готовые дома</p>
                <div class="mrow clearfix">
                    <?php foreach($rows->cottages as $cottages_key => $cottages_value): ?>
                        <?php if($cottages_value['is_house']==1):?>
                            <div class="onecol mcol-4">
                                <a href="<?=$cottages_value['link']?>" class="object-item sm_search" data-category="SelectObject" data-label="buildings">
                                    <div class="img_fil">
                                        <img class="filter-result-item-img" src="<?php echo image($cottages_value['foto'], "small", true); ?>" alt="<?php echo $cottages_value['name']; ?>">
                                    </div>
                                    <div class="priceres"><?=$cottages_value['price']?></div>
                                    <div class="filter-result-item-body">
                                        <p class="filter-result-item-name"><?php echo $cottages_value['name']; ?></p>
                                        <?php if(!empty($cottages_value['land_square'])):?><p class="filter-result-item-size">Участок: <?=$cottages_value['land_square']?> сот</p><?php endif; ?>
                                        <?php if(!empty($cottages_value['house_square'])):?><p class="filter-result-item-size">Площадь дома: <?=$cottages_value['house_square']?> м<sup>2</sup></p><?php endif; ?>
                                        <div class="addbott">
                                            <div class="filter-result-item-address"></div>
                                        </div>
                                    </div>
                                    <div class="viewob">просмотр объекта</div>
                                </a>
                            </div>
                        <?php endif; ?>

                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<section class="complex-info infnov">
    <div class="infohead">
        <ul>
            <li class="active changeinfo" id="descrip"><a href="#">Описание</a></li>
            <?php if(!empty($rows->location)): ?><li class="changeinfo" id="otdelka"><a href="#">Расположение</a></li><?php endif; ?>
            <?php if (!empty($rows->cottages) && count($rows->cottages) > 0):?><li class="changeinfo" id="grounds"><a href="#">Участки</a></li><?php endif; ?>
            <?php if(!empty($rows->infrastruct)): ?><li class="changeinfo" id="infrastructura"><a href="#">Инфраструктура</a></li><?php endif; ?>
            <?php if(!empty($rows->plan) || (!empty($rows->plan_photo)&&$rows->plan_photo!='/asset/img/logo-M16.png')): ?><li class="changeinfo" id="plan"><a href="#">Генплан</a></li><?php endif; ?>
        </ul>
    </div>
    <div class="fixed-container clearfix cottages-container">
        <div class="complex-info-col-left">

            <dl class="complex-info-list reslist">

                <?php if(!empty($rows->rayon)): ?>
                    <dt>Район:</dt>
                    <dd><?php echo $rows->rayon; ?>&nbsp;</dd>
                <?php endif; ?>
                <?php if(!empty($rows->cottages_square)): ?>
                    <dt>Общая площадь:</dt>
                    <dd><?php echo $rows->cottages_square; ?></dd>
                <?php endif; ?>
                <?php if(!empty($rows->cottages_count)): ?>
                    <dt>К-во участков:</dt>
                    <dd><?php echo $rows->cottages_count; ?></dd>
                <?php endif; ?>
                <?php if(!empty($rows->cottages_square_land)): ?>
                    <dt>Пл. участков:</dt>
                    <dd><?php echo $rows->cottages_square_land; ?></dd>
                <?php endif; ?>
                <?php if(!empty($rows->cottages_vodoem)): ?>
                    <dt>Водоем:</dt>
                    <dd><?php echo $rows->cottages_vodoem; ?></dd>
                <?php endif; ?>


            </dl>

            <?php if(is_admins()): ?><p><a class="complex-title" style="font-size: 14px; border: 1px solid; padding: 3px 6px; border-bottom: 0; border-top: 0;" href="/buildings/admin/index/edit/<?php echo $id; ?>" target="_blank">Редактировать в админке</a></p><?php endif; ?>
        </div>
        <div class="complex-info-col-right">
            <div class="active infobl descrip">
                <p class="complex-title">Об объекте</p>
                <div class="complex-text"><?php echo $rows->text; ?></div>
            </div>
            <?php if(!empty($rows->location)): ?>
                <div class="infobl otdelka">
                    <p class="complex-title">Расположение</p>
                    <div class="complex-text"><?php echo $rows->location; ?></div>
                </div>
            <?php endif; ?>
            <?php if (!empty($rows->cottages) && count($rows->cottages) > 0):?>
                <div class="infobl grounds">
                    <div class="complex-text">
                        <table class="cottages-ground-list">
                            <thead>
                                <tr>
                                    <th width="80">№<br>участка</th>
                                    <th width="120">S участка<br>(сот.)</th>
                                    <th>Фото</th>
                                    <th>Дом</th>
                                    <th>Стоимость</th>
                                </tr>
                            </thead>
                        <?php foreach($rows->cottages as $cottages_key => $cottages_value): ?>
                            <tr>
                                <td><?=$cottages_value['identity']?></td>
                                <td><?=$cottages_value['land_square']?></td>
                                <td><?php if (!empty($cottages_value['foto'])):?><a href="<?=$cottages_value['foto']?>" class="fancybox"><img src="<?=image_resize($cottages_value['foto'],'plan', false)?>" alt="" /></a><?php endif; ?></td>
                                <td>
                                    <?php if($cottages_value['is_house'] == 1):?>
                                        <a href="<?=$cottages_value['link']?>">Просмотр</a>
                                    <?php elseif($cottages_value['is_house'] == 0):?>
                                        -
                                    <?php endif; ?>

                                </td>
                                <td><?=$cottages_value['price']?></td>
                            </tr>
                        <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
            <?php if(!empty($rows->plan) || (!empty($rows->plan_photo)&&$rows->plan_photo!='/asset/img/logo-M16.png')): ?>
                <div class="infobl plan">
                    <p class="complex-title">Генплан поселка</p>
                    <div class="complex-text">
                        <?php echo $rows->plan; ?>
                        <?php if((!empty($rows->plan_photo)&&$rows->plan_photo!='/asset/img/logo-M16.png')){ ?>
                            <a href="<?=$rows->plan_photo?>" class="mgnfcplan"><img class="genplan" src="<?php echo image($rows->plan_photo, "medium", false); ?>"></a>
                        <?php } ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if(!empty($rows->infrastruct)): ?>
                <div class="infobl infrastructura">
                    <p class="complex-title">Инфраструктура</p>
                    <div class="complex-text"><?php echo $rows->infrastruct; ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<div class="contain clearfix">
    <div class="advantage-block horizontal clearfix">
        <div class="title-advan">
            Наши преимущества
            <div class="gift-choose">
                фирменный подарок
                от вячеслава малафеева!<br>
                <a href="/gifts">выбрать</a>
            </div>
        </div>
        <div class="vmalf"></div>
        <div id="">
            <div class="titlesadv">
                <h3 class="active" data-id="1"><span>1</span>Индивидуальный подход и<br>большой выбор объектов</h3>

                <h3 data-id="2"><span>2</span>Гарантии<br>
                    и безопасность</h3>

                <h3 data-id="3"><span>3</span>Cервис<br>
                    и комфорт
                </h3>

                <h3 data-id="4"><span>4</span>Гибкие условия
                    сделок и<br>уникальные
                    предложения</h3>
            </div>
            <div class="contsacc">
                <div id="contacc-1">
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
                <div class="notshow" id="contacc-2">
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
                <div class="notshow" id="contacc-3">
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
                <div class="notshow" id="contacc-4">
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
</div>

<div class="clearfix"></div>






<div id="askLand" class="mfp-hide white-popup-block askpopup" data-name="Загородная недвижимость | <?=$rows->header;?>" data-link="<?=getUrl();?>">
    <p class="ask_caption">Ответим на ваш вопрос, в течение ближайших 5 минут, либо в любое удобное для вас время</p>
    <form id="land_form_ask">
        <div class="rowd">
            <textarea id="comment" data-question="Меня интересует объект «<?=$rows->header;?>», который находится по адресу <?php echo $rows->adress; ?><?php echo $rows->price_text; ?>. Расскажите, пожалуйста, мне побольше об этом объекте.">Меня интересует объект «<?=$rows->header;?>», который находится по адресу <?php echo $rows->adress; ?><?php echo $rows->price_text; ?>. Расскажите, пожалуйста, мне побольше об этом объекте.</textarea>
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
        <div class="rowd">
            <input type="submit" class="submit-ask" name="submit" value="отправить" onclick="yaCounter29760432.reachGoal('otpravit-zayav'); return true;" />
        </div>
    </form>
</div>
