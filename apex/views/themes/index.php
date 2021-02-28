<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//ВКЛЮЧЕНИЕ ПОЛНЫХ ШАБЛОНОВ
$toInclude = array();
$toInclude[] = '/intest';
$toInclude[] = '/online-test';
$toInclude[] = '/online-test?complete=1';
$toInclude[] = '/sell-appart';
if (in_array(strtok($_SERVER['REQUEST_URI'], '?'), $toInclude, true)) {
    $this->load->view('themes/default/pages/' . strtok($_SERVER['REQUEST_URI'],'?'));
    exit;
}

//РЕДИРЕКТ НА УНИКАЛЬНЫЕ СТРАНИЦЫ-ОБРАБОТЧИКИ
if (strpos('|' . strtok($_SERVER['REQUEST_URI'],'?'), 'requester')) {
    include_once('/var/www/estate/data/www/m16-estate.ru/releases/20151208131140/apex/views/themes/default/pages/requester.php');
    exit;
}

//РЕДИРЕКТ НА УНИКАЛЬНЫЕ СТРАНИЦЫ-ОБРАБОТЧИКИ
if (strpos('|'.$_SERVER['REQUEST_URI'], 'json_feed')) {
    include_once('/var/www/estate/data/www/m16-estate.ru/releases/20151208131140/apex/views/themes/default/pages/json_feed.php');
    exit;
}

//ПРОВЕРКИ НА СТРАНИЦУ ДИЗАЙНА И ГЛАВНУЮ
$isDesign = false;
$isMain = false;
if (strpos(strtok($_SERVER['REQUEST_URI'],'?'), 'design')) {
    $isDesign = true;
}
if (strpos(strtok($_SERVER['REQUEST_URI'],'?'), 'newindex') or (strtok($_SERVER['REQUEST_URI'],'?') == '/')) {
    $isMain = true;
}

?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <!-- СЕО БЛОК -->
    <?php
    if (strpos(strtok($_SERVER['REQUEST_URI'],'?'), 'editor') && isset($_GET['name'])) {
        $author = $this->load->module('news')->getAuthor($_GET['name']);
        if(strlen($author[0]['name'])<5){
            header('Location: http://m16-estate.ru/editors');
        }
        echo ('<title>Редактор - '.$author[0]['name'].'</title>');
    }
    echo $headSeo; ?>
    <?php if (isset($OG)): ?>
        <meta property="og:type" content="<?= $OG['type'] ?>">
        <meta property="og:site_name" content="М16-Недвижимость">
        <meta property="og:title" content="<?= $OG['title'] ?>">
        <meta property="og:description" content="<?= $OG['description'] ?>">
        <meta property="og:url" content="<?= $OG['url'] ?>">
        <meta property="og:locale" content="ru_RU">
        <meta property="og:image" content="<?= $site_url . $OG['image'] ?>">
        <meta property="og:image:width" content="<?= $OG['width'] ?>">
        <meta property="og:image:height" content="<?= $OG['height'] ?>">
    <?php endif; ?>

    <?php if (isset($article_published)): ?>
        <meta property="article:published_time" content="<?= $article_published ?>"/>
    <?php endif; ?>
    <meta name="google-site-verification" content="BIBhQH5hN_EKvxZNnecv7-Mp8JQOaUuC8e8kbCxXFP8"/>
    <meta name='yandex-verification' content='5c162d80a56d090d'/>
    <meta name='yandex-verification' content='4718ed3687fd6bbc'/>
    <meta name='yandex-verification' content='582b3a9ae452577a'/>
    <meta name="google-site-verification" content="Xdb3omx6RSDeaprYjZo-NQIz-zm6P4nAbSK8F4lW2QM"/>
    <meta name="mailru-verification" content="edbbec56537e1d95"/>

    <link href="/asset/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/asset/assets/maincss.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link type="text/css" rel="stylesheet" href="/asset/assets/lightslider/css/lightSlider.css"/>
    <link href="/asset/css/jquery-ui.css" rel="stylesheet">
    <?php if ($isMain) { ?>
        <link rel="stylesheet" href="/asset/assets/css/ani.css">
    <?php } ?>
    <link href="/asset/css/magnific-popup.css" rel="stylesheet">
    <?php if ($isMain) { ?>
        <link href="/asset/assets/stylesmain.min.css?v=<?= $ver ?>" rel="stylesheet">
        <style>
            .blocks-in {
                top: 11px;
                float: right;
                text-decoration: none;
                color: #009ce0;
                font-size: 13px;
                line-height: 13px;
                text-transform: uppercase;
                letter-spacing: 1px;
                border-bottom: 1px solid rgba(0, 156, 224, 0.6);
                font-family: 'Geometria-Bold';
            }

            #content {
                padding-bottom: 0px;
            }
            .top-banner-item__img img{
                opacity: 1;
                transition: opacity 0.3s;
            }

            .top-banner-item__img img[data-src] {
                opacity: 0;
            }

        </style>
        <script>
            window.userIP = "<?=$_SERVER['REMOTE_ADDR']?>";
        </script>
        <link href="/asset/assets/css/newstyle.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <? } ?>
    <?php if ($isDesign) { ?>

        <link href="/asset/assets/des_maincss.css?v=<?= $ver ?>" rel="stylesheet">
        <link href="/asset/assets/lightbox2-master/src/css/lightbox.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
              integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
              crossorigin="anonymous">

        <!--
        <link href="/asset/assets/style.css?v=<?= $ver ?>" rel="stylesheet">-->
        <link href="/asset/plugins/growl/growl.css" rel="stylesheet">
        <link rel="stylesheet" href="/asset/plugins/remodal/remodal.css">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">

        <link href="/asset/css/jquery-ui.css" rel="stylesheet">
        <link href="/asset/css/selectit.css" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="/asset/assets/light-gallery/css/lightGallery.css"/>
        <link type="text/css" rel="stylesheet" href="/asset/assets/lightslider/css/lightSlider.css"/>
        <!--
        <link href="/asset/css/styles.css" rel="stylesheet">-->

        <style>
            @import url("https://m16-estate.ru/asset/assets/design-page/css/clean.css");
            @import url("https://m16-estate.ru/asset/assets/design-page/css/fonts.css");
            @import url("https://m16-estate.ru/asset/assets/design-page/css/header.css");
            @import url("https://m16-estate.ru/asset/assets/design-page/css/styles.css");
            @import url("https://m16-estate.ru/asset/assets/design-page/css/media.css");
            @import url("https://m16-estate.ru/asset/assets/design-page/css/owl.carousel.css");
            @import url("https://m16-estate.ru/asset/assets/design-page/css/owl.theme.default.css");
        </style>
    <? } ?>

    <?php if (!$isDesign and !$isMain) { ?>
        <script src="//api-maps.yandex.ru/2.1/?load=package.standard&lang=ru_RU" type="text/javascript"></script>
        <script src="/asset/assets/js/ymapsDisDrag.js"></script>
        <link href="/asset/plugins/growl/growl.css" rel="stylesheet">
        <link rel="stylesheet" href="/asset/plugins/remodal/remodal.css">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">

        <link href="/asset/css/jquery-ui.css" rel="stylesheet">
        <link href="/asset/css/selectit.css" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="/asset/assets/light-gallery/css/lightGallery.css"/>
        <link type="text/css" rel="stylesheet" href="/asset/assets/lightslider/css/lightSlider.css"/>
        <link href="/asset/css/styles.css" rel="stylesheet">
        <link href="/asset/assets/styles.css?v=<?= $ver ?>" rel="stylesheet">
        <link href="/asset/assets/rating/rater.css" rel="stylesheet">


        <link href="/asset/css/bootstrap-multiselect.css" rel="stylesheet">
        <link href="/asset/css/jquery.bxslider.css" rel="stylesheet">
        <link href="/asset/css/magnific-popup.css" rel="stylesheet">
        <link href="/asset/css/jquery.datetimepicker.css" rel="stylesheet">
        <link href="/asset/css/jquery.animateSlider.css" rel="stylesheet">
        <!-- Slider Kit styles -->
        <link rel="stylesheet" type="text/css" href="/asset/assets/jquery-sliderkit/css/sliderkit-core.css"
              media="screen, projection"/>
        <link rel="stylesheet" type="text/css" href="/asset/assets/jquery-sliderkit/css/sliderkit-demos.css"
              media="screen, projection"/>
        <link type="text/css" rel="stylesheet" href="/asset/assets/rollbar/jquery.rollbar.css"/>
    <? } ?>
    <?php $this->load->view('template/google'); ?>

    <link href="/asset/css/new.css" rel="stylesheet">
    <?php $this->load->view('template/meta'); // выводим мета теги из настроек
    ?>


    <?php if (strpos('|' . $_SERVER['REQUEST_URI'], '/tag/')) {
        echo '<meta name="robots" content="noindex,follow" />';
    } ?>
    <?php $uril = explode('/', $_SERVER['REQUEST_URI']);
    $noSelector = false;
    if(count($uril)>2) {
        if ($this->load->module('buildings')->isBuilding($uril[2]) != 0) {
            $noSelector = true;
        }
    }
    if (
        /* Stas костыль amp page rule */
        strpos($_SERVER['REQUEST_URI'], 'buildings/')
        && count(explode('/', $_SERVER['REQUEST_URI'])) > 1
        && $uril[2] != '0' && $uril[2] != '1' && $uril[2] != '2'
        && $uril[2] != '3' && $uril[2] != '4' && $uril[2] != '5'
        && $uril[2] != '6' && $uril[2] != '7' && $uril[2] != '8'
        && $uril[2] != '9' && $noSelector): ?>
        <link rel="amphtml" href="https://m16-estate.ru<?php echo $_SERVER['REQUEST_URI']; ?>/amp">
    <?php endif ?>
    <style>
        .author-ava {
            width: 38px;
            height: 38px;
            background-position: center;
            -webkit-background-size: cover;
            background-size: cover;
            display: inline-block;
            vertical-align: middle;
            margin-right: 6px;
            border-radius: 50%;
        }
    </style>
    <script>
        window.userIP = "<?=$_SERVER['REMOTE_ADDR']?>";
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
<?php $this->load->view('template/tagmanager_noscript'); ?>
<script src="/asset/vendor/jquery-2.2.5.min.js"></script>

<!-- ГЛАВНЫЙ КОНТЕНТ -->

<?php if ($isDesign) { ?>
    <?php $this->load->view('themes/default/pages/design'); ?>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,600,300,700,800&subset=cyrillic-ext,latin'
          rel='stylesheet' type='text/css'>
<? } else {

    ?>
    <?php $this->load->view($themes . $templates); ?>
<? } ?>
<?php if (!$isDesign and !$isMain) { ?>
    <script src="/asset/assets/rating/jquery.rater.packed.js"></script>
    <script>
        $(document).ready(function(){
            $('#article_rating #jk-raty').rater('/asset/assets/rating/send_raty_art.php');
            $('#complex_rating #jk-raty').rater('/asset/assets/rating/send_raty.php');
        });
    </script>
    <div id="footer">
        <!--футер-->
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
                                <p><a href="tel:+78126059017" class="calltracker">+7 (812) 605-90-17</a></p>
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
                                    <li><a href="https://www.facebook.com/m16group" target="_blank" class="fb"
                                           rel="nofollow" style="text-decoration: line-through !important;"></a></li>
                                    <li><a href="https://vk.com/m16group" target="_blank" class="vk" rel="nofollow"
                                           style="text-decoration: line-through !important;"></a></li>
                                    <li><a href="https://twitter.com/m16bz" target="_blank" class="tw" rel="nofollow"
                                           style="text-decoration: line-through !important;"></a></li>
                                    <li><a href="https://ok.ru/group/54832562634771" target="_blank" class="od"
                                           rel="nofollow" style="text-decoration: line-through !important;"></a></li>
                                    <li><a href="https://www.instagram.com/m16group/" target="_blank" class="in"
                                           rel="nofollow" style="text-decoration: line-through !important;"></a></li>
                                    <li><a href="https://plus.google.com/+М16Недвижимость" target="_blank" class="gp"
                                           rel="nofollow" style="text-decoration: line-through !important;"></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="onecol mcol-4 copyr">
                            <a style="color:#fff;text-decoration:underline;" href="/privacy_policy">Политика
                                конфиденциальности</a>
                        </div>
                    </div>
                    <div class="mrow offert clearfix">
                        <div class="onecol mcol-8">
                            <!--noindex-->
                            <noindex class="NoIndex_clr_bg_txt_and_img">
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
                            </noindex>
                            <!--/noindex-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
            $("#back-top a[href^='#']").click(function () {
                var _href = $(this).attr("href");
                $("html, body").animate({scrollTop: $(_href).offset().top + "px"});
                return false;
            });
        });
        $(document).ready(function () {
            var ifclose = 0;
            $(".moretags").click(function () {
                if (ifclose == 0) {
                    $(".supertags").animate({
                        height: "100%"
                    }, 5, "linear", function () {
                        console.log(document.getElementById('supertags').offsetHeight);
                        ifclose = 1;
                        $(".moretags").text("Меньше тегов");
                    });
                } else {
                    $(".supertags").animate({
                        height: "78px"
                    }, 5, "linear", function () {
                        ifclose = 0;
                        $(".moretags").text("Ещё теги");
                    });
                }

            });
        });
    </script>
<? } ?>
<div class="cookie-attention" id="cookie-attention-widget" style="display: none;">
    <div class="cookie-attention-holder">
        <div class="cookie-attention-content cont-w">
            <div class="cookie-attention-wrapper">
                <div class="cookie-attention-body">
                    <div class="cookie-attention-text">

                        <div class="cookie-attention-text">
                            Сайт <strong>m16-estate.ru</strong> использует файлы «cookie» и
                            системы аналитики для персонализации сервисов и повышения удобства пользования
                            веб-сайтом. Продолжая просмотр сайта, вы разрешаете их использование. С подробной
                            информации о сборе персональных данных можно ознакомится <a
                                    href="https://m16-estate.ru/privacy_policy">тут</a>.
                        </div>

                    </div>
                    <div class="cookie-attention-ui"
                         onclick="set_cookie('privacy_confirm', '1', 2021, 12, 30 ); $('#cookie-attention-widget').fadeOut();">
                        <input class="cookie-attention-choice __positive js-cookie-attention-choice"
                               id="cookie-attention-positive" type="radio" value="positive">
                        <label class="cookie-attention-presence" for="cookie-attention-positive">
                            Хорошо
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/asset/js/swal.js"></script>
<script src="/asset/vendor/jquery-ui.min.js"></script>
<script src="/asset/plugins/growl/growl.js"></script>
<script src="/asset/vendor/bootstrap/js/bootstrap.min.js"></script>

<script src="/asset/js/jquery.scrollTo.min.js"></script>
<script src="/asset/js/waypoints.min.js"></script>
<script src="/asset/js/bootstrap-multiselect.js"></script>
<script src="/asset/js/jquery.mask.js"></script>
<script src="/asset/js/selectit.js"></script>
<script src="/asset/js/jquery.tablesorter.js"></script>
<script src="/asset/js/jquery.bxslider.js"></script>
<script src="/asset/js/jquery.magnific-popup.js"></script>
<script src="/asset/assets/light-gallery/js/lightGallery.min.js"></script>
<script src="/asset/assets/lightslider/js/jquery.lightSlider.min.js"></script>
<script src="/asset/js/jquery.datetimepicker.js"></script>
<script src="/asset/js/inputmask.js"></script>
<script src="/asset/js/jquery.inputmask.js"></script>
<script src="/asset/js/slider.js"></script>

<script type="text/javascript"
        src="/asset/assets/jquery-sliderkit/js/sliderkit/jquery.sliderkit.1.9.2.pack.js"></script>
<script type="text/javascript"
        src="/asset/assets/jquery-sliderkit/js/sliderkit/addons/sliderkit.delaycaptions.1.1.pack.js"></script>
<script type="text/javascript"
        src="/asset/assets/jquery-sliderkit/js/sliderkit/addons/sliderkit.counter.1.0.pack.js"></script>
<script type="text/javascript"
        src="/asset/assets/jquery-sliderkit/js/sliderkit/addons/sliderkit.timer.1.0.pack.js"></script>
<script type="text/javascript"
        src="/asset/assets/jquery-sliderkit/js/sliderkit/addons/sliderkit.imagefx.1.0.pack.js"></script>
<script type="text/javascript" src="/asset/assets/rollbar/jquery.rollbar.min.js"></script>
<script src="/asset/js/jquery.animateSlider.min.js"></script>
<script src="/asset/js/modernizr.js"></script>
<script src="/asset/js/sendmetrics.js?v=<?= $ver ?>"></script>
<script src="/asset/assets/scripts.min.js?v=<?= $ver ?>"></script>
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=vYh7SOTS3584G9PiW2TMS1HoBZRFvneZeVu1XcJLOdW1oDy*8SuVBzigYJRiAiWvoB4AHgEW6c3B57b5Gd5WkFElh85rPO3wky64RtzS446iz0bNR87ONzwIF15MuPcVxZEG7KY/eaLpZlI1kfNlwamzWwwkzzAsgzhLZLLkCKw-&pixel_id=1000044741';</script>
<!-- <script charset="UTF-8" src="//cdn.sendpulse.com/28edd3380a1c17cf65b137fe96516659/js/push/99238fcb7e7736cea437d0e05926a1d7_1.js" async></script> -->
<!-- Google Tag Manager -->
<?php
$isMobile=0;
if ( stripos( $_SERVER["HTTP_USER_AGENT"], 'android' ) or stripos( $_SERVER["HTTP_USER_AGENT"], 'iPhone' ) ) {
    $isMobile = 1;
}
if ( stripos( $_SERVER["HTTP_USER_AGENT"], 'iPad' ) ) {
    $isIpad   = 1;
    $isMobile = 1;
}
if(!$isMobile){?>
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-W5BPP8N');</script>
    <!-- End Google Tag Manager -->
    <script src="https://cdn.viapush.com/cdn/v1/sdks/viapush.js" async></script>
    <script>
        var fstl=false;
        if(Notification.permission === 'granted'){
            var ViaPush = window.ViaPush || [];
            ViaPush.push([
                "init",
                {
                    appId: "c5b35cb6-3d9b-7306-21d4-2dd96b7a642b",
                    autoReg: false,}]);
            fstl=true;
        }
        function start(){
            if(fstl==false){
                var ViaPush = window.ViaPush || [];
                ViaPush.push([
                    "init",
                    {
                        appId: "c5b35cb6-3d9b-7306-21d4-2dd96b7a642b",
                        autoReg: false,}]);
            }
        }
    </script>
<?}?>
<!-- Facebook Pixel Code
<script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '675487852858861');
    fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
               src="https://www.facebook.com/tr?id=675487852858861&ev=PageView&noscript=1"
    /></noscript>
End Facebook Pixel Code -->
<div style="display: none;">
    <!-- Код тега ремаркетинга Google -->
    <script type="text/javascript">
        /* <![CDATA[ */
        var google_conversion_id = 969017426;
        var google_custom_params = window.google_tag_params;
        var google_remarketing_only = true;
        /* ]]> */
    </script>
    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
    </script>
    <script>
        function get_cookie(cookie_name) {
            var results = document.cookie.match('(^|;) ?' + cookie_name + '=([^;]*)(;|$)');
            if (results)
                return (unescape(results[2]));
            else
                return null;
        }

        setTimeout(function () {
            if (get_cookie('privacy_confirm') != 1) {
                $('#cookie-attention-widget').css('display', 'block');
            }
        }, 5000);

        function set_cookie(name, value, exp_y, exp_m, exp_d, path, domain, secure) {
            let cookie_string = name + "=" + escape(value);

            if (exp_y) {
                let expires = new Date(exp_y, exp_m, exp_d);
                cookie_string += "; expires=" + expires.toGMTString();
            }

            if (path)
                cookie_string += "; path=" + escape(path);

            if (domain)
                cookie_string += "; domain=" + escape(domain);

            if (secure)
                cookie_string += "; secure";

            document.cookie = cookie_string;
        }
        [].forEach.call(document.querySelectorAll('img[data-src]'),    function(img) {
            img.setAttribute('src', img.getAttribute('data-src'));
            img.onload = function() {
                img.removeAttribute('data-src');
            };
        });
    </script>

    <noscript>
        <div style="display:inline;">
            <img height="1" width="1" style="border-style:none;" alt=""
                 src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/969017426/?value=0&amp;guid=ON&amp;script=0"/>
        </div>
    </noscript>
</div>
<?php /** Подгрузка скриптов **/
/** эти файлы скриптов и плагины подгружается всегда */
$load_js[] = array();
$loadPlugin[] = array();
echo loadPlugin($loadPlugin); // выводим подключенные плагины
echo js_files($load_js); // js скрипты

$this->load->view('template/rambler'); // выводим рамблер топ 100
$this->load->view('template/fb'); // выводим рамблер топ 100
$this->load->view('template/vk'); // выводим рамблер топ 100
?>

<!-- JAVASCRIPT FROM ACTION -->
[JS]
<!-- END JAVASCRIPT FROM ACTION -->


<?php $this->load->view('template/yandex'); ?>

<?php $this->load->view('template/roistat'); // выводим гугл аналитик
?>
</body>
</html>