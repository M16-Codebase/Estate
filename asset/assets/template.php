    <div class="eksban" onclick="window.location.href='/excursion'">
        <div class="cont">

            <div class="backtex">
                <p class="bbtop"><span class="orcolor">бесплатные</span> автобусные экскурсии по новостройкам</p>
                <p class="bbbot">Санкт-Петербурга и Ленинградской области!</p>
                <p class="bbdate">Каждые <span>выходные!</span></p>
                <a href="/excursion">Подробнее</a>
            </div>
            <div class="autobus"></div>
        </div>
    </div>

<div id="header" class="clearfix">
    <div class="contain">
        <div class="logo">
            <a href="/"><img src="/asset/assets/img/m16-logo.png" /></a>
        </div>
        <div class="address">
            <p>(812) 688-88-85</p>
            <p>8-800-550-55-16</p>
            <p><a href="/kontakty">Малый пр. П. С., д. 16</a></p>
        </div>
        <ul class="menu">
            <?php echo $menu['header']; ?>
        </ul>
    </div>
</div>
<div id="content">
    <?php echo $template; ?>
    <?php if(uri(1) != 'partners'): ?>
    <section class="container block-partner">
        <div class="row">
            <div class="col-xs-12">
                <?php $this->load->module('parthner')->index(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <?php if(uri(1) == 'kontakty'): ?>


        <section class="block-feedback hidden">
            <div class="">
                <div class="image-wrapper <?php if(uri(1) == ''): ?>anim-otz<?php endif; ?>">
                    <div class="contain">
                        <div class="">
                            <div class="col-lg-offset-1 col-lg-5 reviews col-xs-6">
                                <div class="h1"><?php echo $langLine->page_3; ?></div>
                                <div class="item <?php if(uri(1) == ''): ?>animations<?php endif; ?>">
                                    <?php $this->load->module('otzuv')->limit(); ?>
                                </div>
                                <a href="/otzuv" class="btn btn-m16-1">Написать отзыв</a>
                            </div>
                            <div class="col-lg-5 news col-xs-6">
                                <div class="h1"><?php echo $langLine->page_4; ?></div>
                                <div class="item <?php if(uri(1) == ''): ?>animations<?php endif; ?>">
                                    <?php $this->load->module('news')->limit(); ?>
                                </div>
                                <div class="subscribe">
                                    <form>
                                        <input class="email btn-like" type="text" id="emailmailchimp" placeholder="Введите email, чтобы подписаться"><!--
                                --><input class="submit btn btn-m16-1" id="submitmailchimp" type="submit" value=" ">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php else:?>
        <?php if(!uri(1) || uri(1) == 'partners'): ?>
            <section class="block-feedback">
                <div class="">
                    <div class="image-wrapper anim-otz">
                        <div class="contain">
                            <div class="">
                                <div class="col-lg-offset-1 col-lg-5 reviews col-xs-6">
                                    <div class="h1"><?php echo $langLine->page_3; ?></div>
                                    <div class="item animations">
                                        <?php $this->load->module('otzuv')->limit(); ?>
                                    </div>
                                    <a href="/otzuv" class="btn btn-m16-1">Написать отзыв</a>
                                </div>
                                <div class="col-lg-5 news col-xs-6">
                                    <div class="h1"><?php echo $langLine->page_4; ?></div>
                                    <div class="item animations">
                                        <?php $this->load->module('news')->limit(); ?>
                                    </div>
                                    <div class="subscribe">
                                        <form>
                                            <input class="email btn-like" type="text" id="emailmailchimp" placeholder="Введите email, чтобы подписаться"><!--
                                        --><input class="submit btn btn-m16-1" id="submitmailchimp" type="submit" value=" ">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    <?php endif; ?>
</div>
<div id="footer">
    <div class="footer-container">
        <div class="footermap" id="map-contacts"></div>
        <div class="closemap"></div>
        <div class="transparent">
            <div class="contain">
                <div class="mrow clearfix">
                    <div class="onecol mcol-4">
                        <div class="address" id="show_address_map">
                            Малый пр. П. С.,<br>
                            д.<span>16</span>, 2 этаж
                        </div>
                    </div>
                    <div class="onecol mcol-4">
                        <div class="phones">
                            <span>+7 (812) 688-88-85</span>
                            &nbsp;8-800-550-55-16
                        </div>
                        <div class="email">
                            <a href="mailto:mail@m16.bz">mail@m16.bz</a>
                        </div>
                    </div>
                    <div class="onecol mcol-4 socs">
                        <div class="socials">
                            <ul>
                                <li><a href="https://www.facebook.com/m16bz" target="_blank" class="fb" rel="nofollow"></a></li>
                                <li><a href="https://vk.com/m16group" target="_blank" class="vk" rel="nofollow"></a></li>
                                <li><a href="https://twitter.com/m16bz" target="_blank" class="tw" rel="nofollow"></a></li>
                                <li><a href="https://ok.ru/group/54832562634771" target="_blank" class="od" rel="nofollow"></a></li>
                                <li><a href="https://www.instagram.com/m16group/" target="_blank" class="in" rel="nofollow"></a></li>
                                <li><a href="https://plus.google.com/+М16Недвижимость" target="_blank" class="gp" rel="nofollow"></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="onecol mcol-4 copyr">

                        
                    </div>
                </div>
                <div class="mrow offert clearfix">
                    <div class="onecol mcol-8">
                        <p>Настоящий сайт и представленные на нем материалы носят исключительно информационный характер и ни при каких условиях не являются публичной офертой, определяемой положениями Статьи 437 Гражданского кодекса РФ.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php if(uri(1)): ?>
    <div class="remodal" data-remodal-id="modal" data-remodal-options="hashTracking: false">
        <div class="form-zakaz">
            <div class="left-parth">
                <p class="lp-header"></p>
                <div class="li-img-block" style="overflow: hidden;">
                    <a href="/asset/img/foto.png" class="fancybox"><img src="/asset/img/foto.png" alt="" /></a>
                </div>
                <p class="lp-price"></p>
            </div>
            <div class="right-parth">
                <p class="rp-room">Количество комнат: <span>1</span></p>
                <p class="rp-header">заявка на осмотр</p>
                <form class="remodal-form">
                    <input type="text" class="input-bron inp-name" placeholder="Ваше имя" />
                    <input type="text" class="input-bron inp-phone" placeholder="Телефон" />
                    <label class="checkbox-block"><div class="checkboxes" id="ch-1"></div> 100% оплата</label>
                    <label class="checkbox-block"><div class="checkboxes" id="ch-2"></div> Ипотека или рассрочка</label>
                    <label class="checkbox-block"><div class="checkboxes" id="ch-3"></div> Подписаться на новости компании</label>
                    <button type="button" class="submit-remodal">отправить</button>
                </form>
            </div>
        </div>
    </div>
    <div class="remodal remod" data-remodal-id="modalplan" data-remodal-options="hashTracking: false">
        <div class="head"></div>
        <div class="form-plan">
            <div class="left-parth">
                <p class="lp-header"></p>
                <div class="li-img-block" style="overflow: hidden;">
                    <a href="/asset/img/foto.png" class="fancybox"><img src="/asset/img/foto.png" alt="" /></a>
                </div>
                <p class="lp-price"></p>
            </div>
            <div class="right-parth"></div>
        </div>
    </div>
<?php endif; ?>
<p id="back-top"><a href="#"></a></p>
<div class="botfix">
    <div class="linksbot">
            <div class="onebfix callsblock">
                <div class="lnktobot" id="calls-show">
                    <a href="#" class="callus skypecall"></a>
                    <a href="#" class="callus vibercall"></a>
                    <a href="#" class="callus whatsapcall"></a>
                </div>
            </div>
            <div class="onebfix">
                <?php
                $sessn = $this->session->userdata('seen');
                ?>
                <div class="lnktobot" id="alreadysee-show"><span class="lftcrnr"></span><span class="titb">Недавно просмотренные</span><span class="rghtcrnr"></div>
            </div>
            <div class="onebfix">
                <div class="lnktobot" id="specials-show"><span class="lftcrnr"></span><span class="titb">Спецпредложения</span><span class="rghtcrnr"></div>
            </div>

            <?php
                $this->load->helper('cookie');
                $fav = $this->input->cookie('favorites', true);
                ?>
            <div class="onebfix favm">
                <div class="lnktobot" id="favorites-show"><span class="lftcrnr"></span><i></i><span class="titb">Избранное</span><span class="rghtcrnr"></div>
            </div>
            <div class="onebfix favs">
                <div class="phonebot" id=""><span class="lftcrnr"></span><span class="titb">8 800 550-55-16<span>звонок бесплатный</span></span><span class="rghtcrnr"></div>
            </div>

        <span class="closebot"></span>
    </div>
    <div class="hidebot">
        <div class="contain">
            <div id="callshide" class="oneslidebot">
                <div class="bottomcalls">
                    <div class="oncall clearfix">
                        <div class="leftoc">
                            <img src="/asset/assets/img/skypebig.png">
                            <a href="skype:m16estate?chat">m16estate</a>
                        </div>
                        <div class="rghttoc">
                            Вы можете бесплатно связаться с нами в Skype. Наши специалисты ответят на ваши вопросы, проведут видеоконсультацию, групповые переговоры и дистанционную презентацию объекта.

                        </div>
                    </div>
                    <div class="oncall clearfix">
                        <div class="leftoc secondc">
                            <img src="/asset/assets/img/whatsappbig.png">
                            <img src="/asset/assets/img/viberbig.png">
                            <span>8 (921) 999 99 16</span>
                        </div>
                        <div class="rghttoc">
                            Вы можете бесплатно связаться с нами в WhatsApp и Viber. Наши специалисты быстро предоставят актуальную информацию по любому объекту или услуге в любое удобное для вас время.
                        </div>
                    </div>
                </div>
            </div>
            <div id="alreadyseehide" class="oneslidebot">
                <?php if(!empty($sessn) && count($sessn)>0){?>
                    <?php $this->load->module('favorites')->showSeen(); ?>
                <?php } else { ?>
                    <p class="nothingshow">Нет просмотренных объектов</p>
                <?php }?>
            </div>
            <div id="specialshide" class="oneslidebot">
                <?php $this->load->module('special')->showHide(); ?>
            </div>
            <div id="interestshide" class="oneslidebot">
                <?php $this->load->module('interest')->showHide(); ?>
            </div>
            <div id="favoriteshide" class="oneslidebot">
                <?php if(!empty($fav)){ ?>
                    <?php $this->load->module('favorites')->showHide(); ?>
                <?php } else {?>
                    <p class="nothingshow">Нет избранных объектов</p>
                <?php }?>
            </div>
        </div>
    </div>
</div>