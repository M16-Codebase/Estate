<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Онлайн-тест по выбору квартиры в Санкт-Петербурге</title>
    <link href="https://m16-estate.ru/asset/assets/onlinetest/bootstrap.min.css" rel="stylesheet">
    <link href="https://m16-estate.ru/asset/assets/onlinetest/style.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="https://m16-estate.ru/favicon.ico" rel="shortcut icon" type="image/x-icon">
    <script src="https://m16-estate.ru/asset/assets/onlinetest/jque.js"></script>
    <script src="https://m16-estate.ru/asset/assets/onlinetest/boots.js"></script>
    <script src="https://m16-estate.ru/asset/assets/onlinetest/masked.js"></script>
    <script src="https://m16-estate.ru/asset/assets/onlinetest/custom.js"></script>
    <script>
        var parami = window.location.search.replace('?', '').split('&').reduce(function(p, e) {
            var a = e.split('=');
            p[decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
            return p;
        }, {});
        $(document).ready(function() {
            if(typeof parami['complete'] !== 'undefined') {
                $('#b1').attr('class', 'step_test');
                $('#s2h').attr('style', 'display:full');
                $('#s1h').attr('style', 'display:none');
                $('#stp4').attr('style', 'display:full');
                $('#stp1').attr('style', 'display:none');
            }
        });
        var b1 = '';
        var b2 = ''

        function st2(foor,elem) {
            elem.preventDefault();
            b1 = foor;
            $('#stp2').attr('style', 'display:full');
            $('#stp1').attr('style', 'display:none');
        }

        function st3(foor,elem) {
            elem.preventDefault();
            b2 = foor;
            console.log(b1);
            console.log(b2);
            $('#b1').attr('class', 'step_test');
            $('#s2h').attr('style', 'display:full');
            $('#s1h').attr('style', 'display:none');
            $('#stp3').attr('style', 'display:full');
            $('#stp2').attr('style', 'display:none');
            $('div.b1').html(b1);
            $('div.b2').html(b2);
        }
    </script>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-W5BPP8N');
    </script>
    <!-- End Google Tag Manager -->
</head>

<body id="b1" class="step1 step1_v2" style="overflow-x: hidden;">
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W5BPP8N" height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
<header id="s1h" class="header sidebar">
    <div class="name"><a href="https://m16-estate.ru">M16-недвижимость</a></div>
    <div class="contacts"> <a class="phone" href="tel:88005505516">8-800-550-55-16</a>
        <!--        <a class="callback" href="javascript:void(0);" data-toggle="modal" data-target="#callback">Перезвоните мне</a>--></div>
    <h1 class="section-heading">Выберите класс квартир</h1> </header>
<header id="s2h" class="header" style="display:none">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 hidden-sm hidden-xs">
                <div class="name"><a style="text-decoration: none;" href="https://m16-estate.ru">M16-недвижимость</a></div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-4"> </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-8 text-right">
                <div class="contacts"> <a class="phone" href="tel:">8-800-550-55-16</a>
                    <!--            <a class="callback" href="javascript:void(0);" data-toggle="modal" data-target="#callback">Перезвоните мне</a>--></div>
            </div>
        </div>
    </div>
</header>
<div id="stp1" class="content">
    <section class="start">
        <div class="start-wrapper">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <a class="next_step" href="#" onclick="st2('Эконом',event);"> <span>Эконом</span>
                        <div class="next">Далее</div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <a class="next_step" href="#" onclick="st2('Комфорт',event);"> <span>Комфорт</span>
                        <div class="next">Далее</div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <a class="next_step" href="#" onclick="st2('Бизнес',event);"> <span>Бизнес</span>
                        <div class="next">Далее</div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <a class="next_step" href="#" onclick="st2('Премиум',event);"> <span>Премиум</span>
                        <div class="next">Далее</div>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
<div id="stp2" class="content" style="display:none">
    <section class="start">
        <div class="start-wrapper">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"> <span class="with-bg bg1"></span>
                    <a class="next_step" href="#" onclick="st3('Для жизни',event);"> <span>Для жизни</span>
                        <div class="next">Далее</div>
                    </a>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"> <span class="with-bg bg2"></span>
                    <a class="next_step" href="#" onclick="st3('Для инвестиций',event);"> <span>Для инвестиций</span>
                        <div class="next">Далее</div>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
<div id="stp3" class="content" style="display:none">
    <section class="test">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                    <div class="badges">
                        <div class="badge b1">Эконом-класс</div>
                        <div class="badge b2">Для жизни</div>
                    </div>
                    <div class="section-heading"><span>ШАГ 3</span>: За 1 минуту пройдите тест и <span class="underline">получите подборку</span> подходящих Вам квартир в Санкт-Петербурге</div>
                    <div class="advantages">
                        <div class="advantage"> Автоматически анализируем все предложения застройщиков и порталов недвижимости <span class="number">01</span> </div>
                        <div class="advantage"> Показываем расчёт условий по ипотеке и рассрочке напрямую от банков <span class="number">02</span> </div>
                        <div class="advantage"> Вы можете удалённо забронировать квартиру и записаться на просмотр <span class="number">03</span> </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                    <a class="start-btn" onclick="open_test()">
                        <!--  onclick="Marquiz.showModal('5b0591d5d2a6220040e0f0a1')" -->
                        <div class="btn btn-default btn-blue shake">Начать тест</div>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section class="steps-nav">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                    <div class="step">
                        <div class="heading">Шаг 1</div>
                        <div class="desc">Тип жилья</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                    <div class="step active">
                        <div class="heading">Шаг 2</div>
                        <div class="desc">Тест “Какое жилье вам нужно”</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                    <div class="step">
                        <div class="heading">Шаг 3</div>
                        <div class="desc">Подборка подходящих вам квартир</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div id="stp4" class="content" style="display:none">
    <section class="test">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                    <div class="section-heading"><span>РЕЗУЛЬТАТЫ ТЕСТА ОТПРАВЛЕНЫ НА ОБРАБОТКУ</span></div> <span style="color: white; font-size: 25px; font-weight: bold;">В ближайшее время с Вами свяжется специалист для уточнения параметров подходящей квартиры.<br><a href="https://m16-estate.ru">Вернутся на сайт</a></span> </div>
            </div>
        </div>
    </section>
    <section class="steps-nav">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                    <div class="step">
                        <div class="heading">Шаг 1</div>
                        <div class="desc">Тип жилья</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                    <div class="step active">
                        <div class="heading">Шаг 2</div>
                        <div class="desc">Тест “Какое жилье вам нужно”</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                    <div class="step">
                        <div class="heading">Шаг 3</div>
                        <div class="desc">Подборка подходящих вам квартир</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function(d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter29760432 = new Ya.Metrika({
                    id: 29760432,
                    clickmap: true,
                    trackLinks: true,
                    accurateTrackBounce: true,
                    webvisor: true,
                    trackHash: true
                });
            } catch(e) {}
        });
        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function() {
                n.parentNode.insertBefore(s, n);
            };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";
        if(w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else {
            f();
        }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript>
    <div><img src="https://mc.yandex.ru/watch/29760432" style="position:absolute; left:-9999px;" alt="" /></div>
</noscript>
<!-- /Yandex.Metrika counter -->
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-84755249-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'UA-84755249-1');
</script>
<form action="https://m16-estate.ru/intest" method="post" id="send_test_data">
    <input type="hidden" id="money_class" name="money_class">
    <input type="hidden" id="target" name="target">
</form>
<script>
    function open_test() {
        $('#money_class').val(b1);
        $('#target').val(b2);
        $("#send_test_data").submit();
        //window.location.href = 'https://m16-estate.ru/intest/?b1=' + b1 + '&b2=' + b2;
    }
</script>
</body>

</html>

<?php exit; ?>