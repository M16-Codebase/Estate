<style> table td { word-wrap: break-word; text-align: center; } .complex-table tr { -webkit-transition: all 0.30s ease-in-out;-moz-transition: all 0.30s ease-in-out;-ms-transition: all 0.30s ease-in-out;-o-transition: all 0.30s ease-in-out; } .complex-table tr:hover { background: #F3F0F0; } .complex-slider-wrap .complex-slider-indicators li { background-size: cover; } </style>
<section class="detail-sects detail-apartment">
    <div class="contain">
                <a href="/sell-appart" class="comission inset ">
            Хотите <span>продать</span> квартиру?
        </a>
                    <div class="block-complex-intro-inner">
                        <div class="fixed-container clearfix">
                            <div class="block-complex-intro-col">
                                <h1><?php echo $rows->room; ?></h1><?=$rows->price?>
                                <a class="backto" href="<?=$rows->building_link?>"><?=$rows->building?></a>
                            </div>
                            <div class="block-right text-center">
                                <?php if (empty($rows->presentation)):?>
                                    <a href="/asset/uploads/presentations/presentation_m16.pdf" title="М16-Недвижимость" target="_blank" class="pres-link">Скачать презентацию</a>
                                <?php else: ?>
                                    <a href="<?=$rows->presentation?>" title="М16-Недвижимость" target="_blank" class="pres-link">Скачать презентацию</a>
                                <?php endif; ?>
                                <a href="#askLand" class="block-complex-intro-btn click-block-complex-intro-btn sm_search ask_popup" onclick="yaCounter29760432.reachGoal('novostroy-kvart-ost-zayav'); return true;" data-category="ButtonBuy" data-label="<?=$rows->link?>">Задать вопрос</a>
                                <div id="google_translate_element"></div><script type="text/javascript">
                                    function googleTranslateElementInit() {
                                        new google.translate.TranslateElement({pageLanguage: 'ru', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
                                    }
                                </script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                            </div>
                        </div>
                    </div>
        </div>
</section>

<section class="fixed-container imagesect-resale">

    <div class="slide-resale oneapartment">
        <img src='<?=image_resize($rows->foto,'resizebig',flase)?>'  alt="<?=$rows->img_alt?>" />
    </div>
    <div class="info_apartment">
        <dl class="complex-info-list">
            <table>
                <tr>
                    <td class="avt">
                        <img src="/asset/assets/img/placeico.png" />
                    </td>
                    <td valign="middle" itemprop="streetAddress">
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

        <dl class="complex-info-list apartment-list">
            <?php if (!empty($rows->type)):?>
                <dt>Тип дома:</dt>
                <dd><?php echo $rows->type; ?></dd>
            <?php endif; ?>
            <?php if (!empty($rows->rooms)):?>
                <dt>К-во комнат:</dt>
                <dd><?php echo $rows->rooms; ?></dd>
            <?php endif; ?>
            <?php if (!empty($rows->floor)):?>
                <dt>Этаж:</dt>
                <dd><?php echo $rows->floor; ?></dd>
            <?php endif; ?>
            <?php if (!empty($rows->square_all)):?>
                <dt>Площадь:</dt>
                <dd><?php echo $rows->square_all; ?>&nbsp;м<sup>2</sup></dd>
            <?php endif; ?>
            <?php if (!empty($rows->square_life)):?>
                <dt>Жилая:</dt>
                <dd><?php echo $rows->square_life; ?>&nbsp;м<sup>2</sup></dd>
            <?php endif; ?>
            <?php if (!empty($rows->square_cook) && (int)$rows->square_cook > 0):?>
                <dt>Кухня:</dt>
                <dd><?php echo $rows->square_cook; ?>&nbsp;м<sup>2</sup></dd>
            <?php endif; ?>
            <?php if (!empty($rows->otdelka)):?>
                <dt>Отделка:</dt>
                <dd><?php echo $rows->otdelka; ?></dd>
            <?php endif; ?>

        </dl>
        <a href="#" class="printbtn">Распечатать страницу</a>
    </div>

</section>

<?php if(!empty($rows->text)){?>
<div class="contain">
    <h2 class="complex-title">Описание</h2>
    <div class="complex-text">
        <?=$rows->text?>
    </div>
</div>
<?php } ?>

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

<div class="clearfix"></div>
<br><br>

<?php if (count($rows->yousee)>0):?>
    <section class="fil-result-nov nobgfil">
        <div class="fixed-container clearfix fixsee container">

            <p class="complex-title">Вы уже смотрели</p>
            <ul class="yousee" <?php if (count($rows->yousee)>2):?>id="yousee"<?php endif ?> >

                <?php foreach($rows->yousee as $see_key => $see_value): ?>

                    <li>
                        <a href="<?php echo $see_value['link']; ?>" class="filter-result-item sm_search" data-category="SelectObject" data-label="buildings">
                            <div class="img_fil"><img class="filter-result-item-img" src="<?php echo image($see_value['foto'], "small"); ?>" alt="<?php echo $see_value['name']; ?>"></div>
                            <div class="priceres"><?php if($see_value['price'] > 0) { echo 'от <span>'.$see_value['price'].'</span> млн. руб'; } else { echo 'по запросу'; } ?></div>
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
    <form id="land_form_ask">
        <div class="rowd">
            <textarea id="comment" data-question="Меня интересует <?php echo $rows->room; ?> в <?=$rows->building?><?php echo $rows->price_text; ?>. Расскажите, пожалуйста, мне побольше об этом объекте.">Меня интересует <?php echo $rows->room; ?> в <?=$rows->building?><?php echo $rows->price_text; ?>. Расскажите, пожалуйста, мне побольше об этом объекте.</textarea>
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

