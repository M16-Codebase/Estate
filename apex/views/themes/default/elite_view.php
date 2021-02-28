<style>
    .resale-complex { padding: 25px !important; }
    .resale-complex .item { height: auto !important; }
    .resale-complex .complex-slider-indicators li { background-size: cover; }
</style>

<section class="detail-sects">
    <div class="row">
        <div class="col-xs-12 image-wrappers">
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
                                    <a href="/asset/uploads/presentations/presentation_m16.pdf" title="М16-Недвижимость" target="_blank" class="pres-link">Скачать презентацию</a>
                                <?php else: ?>
                                    <a href="<?=$rows->presentation?>" title="М16-Недвижимость" target="_blank" class="pres-link">Скачать презентацию</a>
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
                                    <a href="#" class="addtofavorite" data-category="elite" data-id="<?php echo $id; ?>" data-action="delete">Убрать из избранного</a>
                                <?php else: ?>
                                    <a href="#" class="addtofavorite notinf" data-category="elite" data-id="<?php echo $id; ?>" data-action="add">В избранное</a>
                                <?php endif;?>
                            </div>
                        </div>
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
                        <?php if(!empty($rows->tour)): ?>
                            <div class="sliderkit-panel">
                                <iframe src="<?=$rows->tour?>" frameborder="0" width="822" height="530"></iframe>
                                <span class="plazhka"></span>
                            </div>
                        <?php endif; ?>
                        <?php foreach($rows->foto['foto'] as $k=>$v): ?>
                            <div class="sliderkit-panel">
                                <img src="<?=image_resize($v,'resizebig')?>" alt="<?=$rows->foto['alt'][$k]?>" />
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
                                <?php if(!empty($rows->tour)): ?>
                                    <li class="panoramali"><a href="#" rel="nofollow" title=""><img src="<?=image($rows->mainfoto,'bigsliderthumb')?>" width="114px" height="114px" alt="" /><span class="panoramacap"></span></a></li>
                                <?php endif; ?>
                                <?php foreach($rows->foto['foto'] as $k=>$v): ?>
                                    <li><a href="#" rel="nofollow" title="<?=$rows->foto['alt'][$k]?>"><img src="<?=image($v,'bigsliderthumb')?>" width="114px" height="114px" alt="<?=$rows->foto['alt'][$k]?>" /></a></li>
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


<section class="complex-info infnov">
    <div class="infohead">
        <ul>
            <li class="active changeinfo" id="descrip"><a href="#">Описание</a></li>
            <?php if(!empty($rows->otdelka)): ?><li class="changeinfo" id="otdelka"><a href="#">Отделка</a></li><?php endif; ?>
            <?php if(!empty($rows->infrastruct)): ?><li class="changeinfo" id="infrastructura"><a href="#">Дом и инфраструктура</a></li><?php endif; ?>
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

                <?php if(!empty($rows->room)): ?>
                    <dt>Комнат:</dt>
                    <dd><?php echo $rows->room; ?>&nbsp;</dd>
                <?php endif; ?>
                <?php if(!empty($rows->floor)): ?>
                    <dt>Этаж:</dt>
                    <dd><?php echo $rows->floor; ?>&nbsp;</dd>
                <?php endif; ?>
                <?php if(!empty($rows->square_all)): ?>
                    <dt>Общая пл.:</dt>
                    <dd><?php echo $rows->square_all; ?>&nbsp;м<sup>2</sup></dd>
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
            </dl>

            <?php if(!empty($rows->builder)): ?>
                <div class="builderinfo">
                    <img src="<?=$rows->builder['image'];?>" />
                    <b>Застройщик:</b><br>
                    <?=$rows->builder['name'];?>
                </div>
            <? endif; ?>
            <?php if(is_admins()): ?><p><a class="complex-title" style="font-size: 14px; border: 1px solid; padding: 3px 6px; border-bottom: 0; border-top: 0;" href="/buildings/admin/index/edit/<?php echo $id; ?>" target="_blank">Редактировать в админке</a></p><?php endif; ?>
        </div>
        <div class="complex-info-col-right">
            <div class="active infobl descrip">
                <p class="complex-title">Об объекте</p>
                <div class="complex-text"><?php echo $rows->text; ?></div>
            </div>

            <?php if(!empty($rows->otdelka)): ?>
                <div class="infobl otdelka">
                    <p class="complex-title">Отделка</p>
                    <div class="complex-text"><?php echo $rows->otdelka; ?></div>
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
                        Бесплатное проживание в апарт-отеле до 90 дней.
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

<?php if (count($rows->yousee)>0):?>
    <section class="fil-result-nov nobgfil">
        <div class="fixed-container clearfix fixsee">
            <p class="complex-title">Вы уже смотрели</p>
            <ul class="yousee" <?php if (count($rows->yousee)>2):?>id="yousee"<?php endif ?> >
                <?php foreach($rows->yousee as $see_key => $see_value): ?>
                    <li class="onecol mcol-4">
                        <a href="<?php echo $see_value['link']; ?>" class="object-item resale-item sm_search" data-category="SelectObject" data-label="buildings">
                            <div class="img_fil"><img class="filter-result-item-img" src="<?php echo image($see_value['foto'], "small"); ?>" alt="<?php echo $see_value['name']; ?>"></div>
                            <div class="priceres"><?php if($see_value['price'] > 0) { echo 'от <span>'.$see_value['price'].'</span> млн. руб'; } else { echo 'по запросу'; } ?></div>
                            <div class="filter-result-item-body">
                                <p class="filter-result-item-rayon"><?php echo $see_value['rayon']; ?></p>
                                <p class="filter-result-item-name"><?php echo $see_value['name']; ?></p>
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


<div id="askLand" class="mfp-hide white-popup-block askpopup" data-name="Элитная недвижимость | <?=$rows->header;?>" data-link="<?=getUrl();?>">
    <p class="ask_caption">Ответим на ваш вопрос, в течение ближайших 5 минут, либо в любое удобное для вас время</p>
    <form id="land_form_ask">
        <div class="rowd">
            <textarea id="comment" data-question="Меня интересует квартира по адресу «<?php echo $rows->header; ?>»<?php echo $rows->price_text; ?>. Расскажите, пожалуйста, мне побольше об этом объекте.">Меня интересует квартира по адресу «<?php echo $rows->header; ?>»<?php echo $rows->price_text; ?>. Расскажите, пожалуйста, мне побольше об этом объекте.</textarea>
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
            <input type="submit" class="submit-ask" name="submit" value="отправить" onclick="yaCounter29760432.reachGoal('otpravit-zayav'); _gaq.push(['_trackEvent', 'OtpravitVoprosElite', 'Click']); return true;" />
        </div>
    </form>
</div>
