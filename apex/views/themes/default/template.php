   <?php if(uri(1) == 'kontakty'): ?>
        <div class="eksban" onclick="window.location.href='/excursion'">
        <div class="cont conti">
            <div class="backtex">
                <p class="bbtop"><span class="orcolor">бесплатные</span> автобусные экскурсии по новостройкам</p>
                <p class="bbbot">Санкт-Петербурга и Ленинградской области!</p>
                <p class="bbdate">Каждые <span>выходные!</span></p>
                <a href="/excursion">Подробнее</a>
            </div>
            <div class="autobus"></div>
        </div>
		<div class="cont conts" style="right: -1280px; margin-right: 0px;">
            <div class="testsss"></div>
        </div>
    </div>

    <?php else:?>
   <?php
       $isMobile=0;
       if ( stripos( $_SERVER["HTTP_USER_AGENT"], 'android' ) or stripos( $_SERVER["HTTP_USER_AGENT"], 'iPhone' ) ) {
           $isMobile = 1;
       }
       if ( stripos( $_SERVER["HTTP_USER_AGENT"], 'iPad' ) ) {
           $isIpad   = 1;
           $isMobile = 1;
       }?>
   <?php if(!$isMobile){?>

  
      <div class="header-top-banner-wrapper">
		<div class="header-top-banner">
			<div class="header-top-banner__slider" id="light-slider">
                [top_banners]
			</div>
			<div class="header-top-banner__arrows">
				<div class="header-top-arrows__back header-top-arrows" id="back-slide">
					<img src="/asset/img/tb/back-arrow.png" alt="">
				</div>
				<div class="header-top-arrows__next header-top-arrows" id="next-slide">
					<img src="/asset/img/tb/next-arrow.png" alt="">
				</div>
			</div>
		</div>
	</div>
    <?php if(!empty($_GET['old_banner'])){ ?>
   

    <div class="eksban">
        <div class="cont conti" onclick="window.location.href='/excursion'">
            <div class="backtex">
                <p class="bbtop"><span class="orcolor">бесплатные</span> автобусные экскурсии по новостройкам</p>
                <p class="bbbot">Санкт-Петербурга и Ленинградской области!</p>
                <p class="bbdate">Каждые <span>выходные!</span></p>
                <a href="/excursion">Подробнее</a>
            </div>
            <div class="autobus"></div>
        </div>
		<div class="cont conts" style="right: -1280px; margin-right: 0px;" onclick="window.location.href='/online-test'">
            <div class="testsss"></div>
        </div>
    </div>
    <?php } ?>
    <?}?>
        <?php endif; ?>

<div id="header" class="clearfix">
    <div class="contain">
        <div class="logo">
            <a href="/"><img src="/asset/assets/img/m16-logo.png" /></a>
        </div>
        <div class="address">
			<a href="tel:+78126888885" class="calltracker">+7 (812) 688-88-85</a>
            <a href="tel:88005505516" class="calltracker">8-800-550-55-16</a>
            <p><a href="/kontakty">Большая Зеленина, д.18</a></p>
        </div>
        <div class="mobile-burger">
            <span class="mobile-burger__line"></span>
            <span class="mobile-burger__line"></span>
            <span class="mobile-burger__line"></span>
        </div>
        <ul class="menu header-menu__list">
            <?php echo $menu['header']; ?>
        </ul>
    </div>
</div>
<div id="content">
    <?php echo $template; ?>
    <?php if(((uri(1) != 'partners') || (!strpos($_SERVER['REQUEST_URI'], 'ewindex'))) and !($_SERVER['REQUEST_URI']=='/')): ?>
    <section class="container block-partner" style="display: <?php if(strpos($_SERVER['REQUEST_URI'],'ewindex')){ echo 'none'; }else{ echo 'block'; }?>">
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
        <?php if((!uri(1) || uri(1) == 'partners') and !($_SERVER['REQUEST_URI']=='/')): ?>
            <section class="block-feedback">
                <div class="">
                    <div class="image-wrapper anim-otz">
                        <div class="contain">
                            <div class="">
                                <div class="col-lg-offset-1 col-lg-5 reviews col-xs-6">
                                    <div class="h1"><?php echo $langLine->page_3; ?></div>
                                    <div class="item animations">
                                        <?php $this->load->module('otzuv')->limits(); ?>
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
   <?php if((!uri(1) or uri(1) != 'newindex') and !($_SERVER['REQUEST_URI']=='/')): ?>
<div id="footer">
    <div class="footer-container">
        <div class="footermap" id="map-contacts"></div>
        <div class="closemap"></div>
        <div class="transparent">
            <div class="contain">
                <div class="mrow clearfix">
                    <div class="onecol mcol-4">
                        <div class="address" id="show_address_map">
                            Большая Зеленина, д.<span>18</span>
                        </div>
                    </div>
                    <div class="onecol mcol-4">
                        <div class="phones">
							<p><a href="tel:+78126888885" class="calltracker">+7 (812) 688-88-85</a></p>
							&nbsp;<a href="tel:88005505516" class="calltracker">8-800-550-55-16</a>
                        </div>
						&nbsp;
                        <div class="email">
                            <a href="mailto:mail@m16.bz">mail@m16.bz</a>
                        </div>
                    </div>
                    <div class="onecol mcol-4 socs">
                        <div class="socials">
                            <ul>
                                <li><a href="https://www.facebook.com/m16group" target="_blank" class="fb" rel="nofollow"></a></li>
                                <li><a href="https://vk.com/m16group" target="_blank" class="vk" rel="nofollow"></a></li>
                                <li><a href="https://twitter.com/m16bz" target="_blank" class="tw" rel="nofollow"></a></li>
                                <li><a href="https://ok.ru/group/54832562634771" target="_blank" class="od" rel="nofollow"></a></li>
                                <li><a href="https://www.instagram.com/m16group/" target="_blank" class="in" rel="nofollow"></a></li>
                                <li><a href="https://plus.google.com/+М16Недвижимость" target="_blank" class="gp" rel="nofollow"></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="onecol mcol-4 copyr">
                        <a style="color:#fff;text-decoration:underline;font-size: 14px;font-family: 'Geometria';" href="/privacy_policy/">Политика конфиденциальности</a>
                        <div style="color:#fff;line-height: 22px;font-family: 'Geometria';">© АНВМ М16 2014-2019.<br>Все права защищены.</div>
                    </div>
                </div>
                <div class="mrow offert clearfix">
                    <div class="onecol mcol-8">
                        <!--noindex-->
                        <?php /* <p>Настоящий сайт и представленные на нем материалы носят исключительно информационный
                                    характер и ни при каких условиях не являются публичной офертой, определяемой
                                    положениями Статьи 437 Гражданского кодекса РФ.<br>
                                    Мы собираем и храним файлы cookies. Файлы cookies не собирают и не хранят никакую
                                    личную информацию о Вас. Используя этот сайт, Вы даёте свое согласие на
                                    использование cookies. Чтобы отказаться от использования cookie Вам надо немедленно
                                    закрыть наш сайт.<br>
                                    Более подробно ознакомиться с информацией об использовании файлов cookies, а также
                                    нашей Политикой защиты персональных данных Вы можете <a
                                            href="/privacy_policy">здесь</a>.
                                </p> */ ?>
                        <a href="/privacy_policy/"><img src="/asset/img/private.svg"></a>
                        <!--/noindex-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
   <?php else:?>
   <div></div>
   <?php endif; ?>
<?php //if(uri(1)): ?>
<?php if(false): ?>
    <div class="remodal" data-remodal-id="modal" data-remodal-options="hashTracking: false" style="display: <?php //if(strpos($_SERVER['REQUEST_URI'],'ewindex')){ echo 'none'; }else{ echo 'block'; }?>">
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
    <div class="remodal remod" data-remodal-id="modalplan" data-remodal-options="hashTracking: false" style="display: <?php //if(strpos($_SERVER['REQUEST_URI'],'ewindex')){ echo 'none'; }else{ echo 'block'; }?>">
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
<?php   if(!strpos($_SERVER['REQUEST_URI'], 'newindex') or !( $_SERVER['REQUEST_URI']=='/')){?>
<p id="back-top"><a href="#"></a></p>
<div class="botfix" style="display: <?php if(strpos($_SERVER['REQUEST_URI'],'ewindex') or $_SERVER['REQUEST_URI']=='/'){ echo 'none'; }else{ echo 'block'; }?>">
    <div class="linksbot">

            <?php
                $this->load->helper('cookie');
                $fav = $this->input->cookie('favorites', true);
                ?>
            <div class="onebfix favm" <?php if(empty($fav)){ ?> style="display:none" <?php }?>>
                <div class="lnktobot" id="favorites-show"><span class="lftcrnr"></span><i></i><span class="titb">Избранное</span><span class="rghtcrnr"></div>
            </div>


        <span class="closebot"></span>
    </div>
    <div class="hidebot">
        <div class="contain">
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
<? } ?>
<?php if (isset($MR) && !empty($MR)) : //микроразметка ?>
       <!-- MR -->
   <script type="application/ld+json">
       <?=$MR?>
   </script>
   <?php endif; ?>
   
   <!--
    <div class="banner-img fadeIn" style=" z-index: 999;position:fixed; left: 0;
    right:0; bottom:0; display: none; width 18%; width: min-content; height: min-content;">
       <a href="/news/rosselhozbank-snizil-stavku-po-semejnoj-ipoteke-do-47">
           <img src="/asset/assets/img/credit_banner.jpg"
                style="height: 120px; border-radius: 15px; margin: 0 0 25px 10px;
                box-shadow: rgba(75, 113, 153, 0.36) 0px 0px 30px;">
       </a>
       <img src="/asset/assets/img/cancel.svg" class="banner-img__close" style="width: 30px;height: 30px; position: absolute; right: -30px; top: -20px; cursor: pointer;" >
   </div>
   <div class="banner-img-min" style=" z-index: 999;position:fixed; left: 0;
    right:0; bottom:0; display: none; width 18%; width: min-content; height: min-content;">
       <a href="#" onclick="event.preventDefault();">
           <img src="/asset/assets/img/banner-expand.png"
                style="height: 50px; margin: 0 0 0 0;
                box-shadow: rgba(75, 113, 153, 0.36) 0px 0px 30px; border-radius: 16px 16px 5px 5px;">
       </a>
   </div>
   -->
   <script>
       function get_cookie ( cookie_name )
       {
           var results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );
 
           if ( results )
               return ( unescape ( results[2] ) );
           else
               return null;
       }
       function attend() {
           $('.banner-img').toggleClass('bounce');
       }
       function disable(){
           $('.banner-img').removeClass('fadeIn');
       }
 
       window.onload = function () {
           if(get_cookie( "banner_hidden" )=='1') {
               $('.banner-img-min').fadeIn();
           }else{
               setTimeout($('.banner-img').fadeIn(), 4000);
               setTimeout(disable, 8000);
               setInterval(attend, 7000);
           }
       };
       $('.banner-img__close').click(function() {
           $('.banner-img').fadeOut();
           $('.banner-img-min').fadeIn();
           document.cookie = "banner_hidden=1; expires=01/01/2020 00:00:00";
       });
       $('.banner-img-min').click(function() {
           $('.banner-img-min').fadeOut();
           $('.banner-img').fadeIn();
           document.cookie = "banner_hidden=0; expires=01/01/2020 00:00:00";
       });
   </script>
