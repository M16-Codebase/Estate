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
                        <h1><?php echo $rows->header; ?></h1><?=$rows->price?>
                        <a class="backto" href="<?=$refer?>">Назад в раздел</a>
                    </div>
                    <div class="block-right text-center">
                        <?php if (empty($rows->presentation)):?>
                            <a style="display:none;" href="/asset/uploads/presentations/presentation_m16.pdf" title="М16-Недвижимость" target="_blank" class="pres-link">Скачать презентацию</a>
                        <?php else: ?>
                            <a style="display:none;" href="<?=$rows->presentation?>" title="М16-Недвижимость" target="_blank" class="pres-link">Скачать презентацию</a>
                        <?php endif; ?>
                        <a href="#askLand" class="block-complex-intro-btn click-block-complex-intro-btn sm_search ask_popup" onclick="yaCounter29760432.reachGoal('vse-ostavit-zayav'); _gaq.push(['_trackEvent', 'ZadatVoprosVezde', 'Click']); return true;" data-category="ButtonBuy" data-label="<?=$rows->link?>">Задать вопрос</a>
                        <!--
                        <div id="google_translate_element"></div><script type="text/javascript">
                            function googleTranslateElementInit() {
                                new google.translate.TranslateElement({pageLanguage: 'ru', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
                            }
                        </script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                        -->
                        <?php if(!empty($in_favorites)):?>
                            <a href="#" class="addtofavorite" data-category="arenda" data-id="<?php echo $rows->id; ?>" data-action="delete">Убрать из избранного</a>
                        <?php else: ?>
                            <a href="#" class="addtofavorite notinf" data-category="arenda" data-id="<?php echo $rows->id; ?>" data-action="add">В избранное</a>
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
                                    <li><a href="#" rel="nofollow" title="<?=$rows->foto['alt'][$k]?>"><img src="<?=image($v,'bigsliderthumb')?>" width="114px" height="114px" alt="<?=$rows->img_alt?>" /></a></li>
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
        <?php if(!empty($rows->map_lng)): ?>
        <div class="mapresale-static" id="mapresale-static" data-lat="<?=$rows->map_lat?>" data-lng="<?=$rows->map_lng?>">
            <img src="https://static-maps.yandex.ru/1.x/?ll=<?=$rows->map_lng?>,<?=$rows->map_lat?>&size=440,440&z=13&l=map&pt=<?=$rows->map_lng?>,<?=$rows->map_lat?>,round">
            <a href="#" onclick="return false" id="url-static"> открыть интерактивную карту</a>
</div><?php endif; ?>
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

            <?php if(!empty($rows->infrastructure)): ?><li class="changeinfo" id="infrastructura"><a href="#">Дом и инфраструктура</a></li><?php endif; ?>
            <?php if(!empty($rows->planirovka)): ?><li class="changeinfo" id="otdelka"><a href="#">Планировка</a></li><?php endif; ?>
            <?php if(!empty($rows->vid)): ?><li class="changeinfo" id="transportdostup"><a href="#">Планировка</a></li><?php endif; ?>
        </ul>
    </div>
    <div class="fixed-container clearfix">
        <div class="complex-info-col-left">

            <dl class="complex-info-list">
                <table>
                    <tr>
                        <td class="avt">
                            <img src="/asset/assets/img/placeico.png" />
                        </td>
                        <td valign="middle">
                            <?php echo $rows->rayon; ?><br>
                            <?php echo $rows->address; ?>
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

            <dl class="complex-info-list reslist">
            <?php
                function rooms_toStr($num){
                    switch($num){
                        case 1:
                            return 'Однокомнатная';
                        break;
                        case 2:
                            return 'Двухкомнатная';
                        break;
                        case 3:
                            return 'Трехкомнатная';
                        break;
                        case 4:
                            return 'Четырехкомнатная';
                        break;
                        case 5:
                            return 'Пятикомнатная';
                        break;
                        case 'Студия':
                        case 'студия':
                            return 'Студия';
                        break;
                    }
                }
                ?>
                <?php if(!empty($rows->rooms)): ?>
                    <dt>Комнат:</dt>
                    <dd><?=rooms_toStr($rows->rooms); ?>&nbsp;</dd>
                <?php endif; ?>
                <?php if(!empty($rows->srok)): ?>
                    <dt>Срок сдачи:</dt>
                    <dd><?php echo $rows->srok; ?>&nbsp;</dd>
                <?php endif; ?>
                <?php if(!empty($rows->floor)): ?>
                    <dt>Этаж:</dt>
                    <dd><?php echo $rows->floor; ?>&nbsp;</dd>
                <?php endif; ?>
                <?php if(!empty($rows->square)): ?>
                    <dt>Общая пл.:</dt>
                    <dd><?php echo $rows->square; ?>&nbsp;м<sup>2</sup></dd>
                <?php endif; ?>
                <?php if(!empty($rows->square_life)): ?>
                    <dt>Жилая пл.:</dt>
                    <dd><?php echo $rows->square_life; ?>&nbsp;м<sup>2</sup></dd>
                <?php endif; ?>
                <?php if(!empty($rows->square_cook)): ?>
                    <dt>Пл. кухни:</dt>
                    <dd><?php echo $rows->square_cook; ?>&nbsp;м<sup>2</sup></dd>
                <?php endif; ?>
                <?php if(!empty($rows->type_id)): ?>
                    <dt>Тип дома:</dt>
                    <dd><?php echo $rows->type_id; ?>&nbsp;</dd>
                <?php endif; ?>
                <?php if(!empty($rows->prodazha)): ?>
                    <dt>Тип продажи:</dt>
                    <dd><?php echo $rows->prodazha; ?>&nbsp;</dd>
                <?php endif; ?>
                <?php if(!empty($rows->otdelkaid)): ?>
                    <dt>Отделка:</dt>
                    <dd><?php echo $rows->otdelkaid; ?>&nbsp;</dd>
                <?php endif; ?>
                <?php if(!empty($rows->planirovka)): ?>
                    <dt>Планировка:</dt>
                    <dd><?php echo $rows->planirovka; ?>&nbsp;</dd>
                <?php endif; ?>
                <?php if(!empty($rows->okna)): ?>
                    <dt>Окна:</dt>
                    <dd><?php echo $rows->okna; ?>&nbsp;</dd>
                <?php endif; ?>
                <?php if(!empty($rows->vhod)): ?>
                    <dt>Вход:</dt>
                    <dd><?php echo $rows->vhod; ?>&nbsp;</dd>
                <?php endif; ?>
                <?php if(!empty($rows->ipoteka) && $rows->ipoteka != 'Нет'): ?>
                    <dt>Ипотека:</dt>
                    <dd><?php echo $rows->ipoteka; ?>&nbsp;</dd>
                <?php endif; ?>
                <?php if(!empty($rows->object)): ?>
                    <dt>Объект:</dt>
                    <dd><?php echo $rows->object; ?>&nbsp;</dd>
                <?php endif; ?>
            </dl>

            <?php echo fetch_consultant(); ?>

            <?php if(!empty($rows->builder)): ?>
                <div class="builderinfo">
                    <img src="<?=$rows->builder['image'];?>" />
                    <b>Застройщик:</b><br>
                    <?=$rows->builder['name'];?>
                </div>
            <? endif; ?>


            <?php if(is_admins()): ?><p><a class="complex-title" style="font-size: 14px; padding: 6px; margin-top: 10px;" href="/arenda/admin/index/edit/<?php echo $rows->id; ?>" target="_blank">Редактировать в админке</a></p><?php endif; ?>
        </div>
        <div class="complex-info-col-right">
            <div class="active infobl descrip">
                <p class="complex-title">Об объекте</p>
                <div class="complex-text"><?php echo $rows->text; ?></div>
            </div>
            <?php if(!empty($rows->otdelka) || count($rows->foto_otdelka['foto']) > 0): ?>
                <div class="infobl otdelka">
                    <p class="complex-title">Планировка</p>
                    <div class="complex-text"><?php echo $rows->otdelka; ?></div>
                    <?php if(count($rows->foto_otdelka['foto']) > 0): ?>
                        <ul class="otd_foto">
                            <?php foreach($rows->foto_otdelka['foto'] as $k=>$v): ?>
                                <li><a href="<?=$v?>" class="foto_otd"><img src="<?=$v?>" width="100px" height="100px" /></a></li>
                            <?php endforeach; ?>
                        </ul>

                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if(!empty($rows->infrastructure)): ?>
                <div class="infobl infrastructura">
                    <p class="complex-title">Инфраструктура</p>
                    <div class="complex-text"><?php echo $rows->infrastructure; ?></div>
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
<br><br>

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
                                <div class="addbott">
                                    <div class="filter-result-item-address"><?=$see_value['address'];?>
                                        <span class="filter-result-item-metro"><span><?=$see_value['metro'];?></span></span>
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




<div id="askLand" class="mfp-hide white-popup-block askpopup" data-name="Аренда | <?=$rows->header;?>" data-link="<?=getUrl();?>">
    <p class="ask_caption">Ответим на ваш вопрос, в течение ближайших 5 минут, либо в любое удобное для вас время</p>
    <form id="land_form_ask">
        <div class="rowd">
            <textarea id="comment" data-question="Меня интересует объект <?php echo $rows->header; ?>. Расскажите, пожалуйста, мне побольше об этом объекте.">Меня интересует объект <?php echo $rows->header; ?>. Расскажите, пожалуйста, мне побольше об этом объекте.</textarea>
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
            <input type="submit" class="submit-ask" name="submit" value="отправить" onclick="yaCounter29760432.reachGoal('otpravit-zayav'); _gaq.push(['_trackEvent', 'OtpravitVoprosRent', 'Click']); return true;" />
        </div>
    </form>
</div>
