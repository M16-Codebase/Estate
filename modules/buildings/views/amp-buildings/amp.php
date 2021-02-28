<!--
	Copyright 2016 Google Inc.

	Licensed under the Apache License, Version 2.0 (the "License");
	you may not use this file except in compliance with the License.
	You may obtain a copy of the License at

	     http://www.apache.org/licenses/LICENSE-2.0

	Unless required by applicable law or agreed to in writing, software
	distributed under the License is distributed on an "AS IS" BASIS,
	WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
	See the License for the specific language governing permissions and
	limitations under the License.
	-->
<!doctype html>
<html ⚡ lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <link rel="canonical" href="<?php echo $page['canonical']; ?>">
    <link rel="shortcut icon" href="<?php echo $page['favicon']; ?>">
    <!--<meta name="robots" content="none"/>-->
    <title><?php echo $page['title']; ?></title>
    <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
    <script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>
    <script async custom-element="amp-fit-text" src="https://cdn.ampproject.org/v0/amp-fit-text-0.1.js"></script>
    <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
    <script async custom-element="amp-selector" src="https://cdn.ampproject.org/v0/amp-selector-0.1.js"></script>
    <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
    <script async custom-element="amp-accordion" src="https://cdn.ampproject.org/v0/amp-accordion-0.1.js"></script>
    <script async custom-element="amp-bind" src="https://cdn.ampproject.org/v0/amp-bind-0.1.js"></script>
    <script async custom-element="amp-lightbox-gallery"
            src="https://cdn.ampproject.org/v0/amp-lightbox-gallery-0.1.js"></script>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <style amp-boilerplate>body {
            -webkit-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            -moz-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            -ms-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            animation: -amp-start 8s steps(1, end) 0s 1 normal both
        }

        @-webkit-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }

        @-moz-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }

        @-ms-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }

        @-o-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }

        @keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }</style>
    <noscript>
        <style amp-boilerplate>body {
                -webkit-animation: none;
                -moz-animation: none;
                -ms-animation: none;
                animation: none
            }</style>
    </noscript>
    <style amp-custom>
        body {
            width: auto;
            margin: 0;
            padding: 0;
            font-family: 'Open Sans', sans-serif;
        }

        h1 {
            margin: 0;
            padding: 0.5em;
            background: white;
            box-shadow: 0px 3px 5px grey;
            font-size: 25px;
        }

        p {
            padding: 0.5em;
            margin: 0.5em;
        }

        header {
            color: #0079d2;
            font-size: 1em;
            text-align: center;
            background-color: #fff;
        }

        .wrap {
            padding: 10px 14px 0 11px;
        }

        ul {
            padding: 0 14px 0 11px;
        }

        .wrap > div p {
            padding: 0;
            margin: 10px;
        }

        .wrap > div input {
            width: 100%;
            height: 21px;
        }

        .wrap > div input.InputQuestion {
            height: 50px;
        }

        .home-button {
            margin-top: 8px;
        }

        .slide-text {
            text-align: center;
            color: #000;
        }

        .headerbar {
            height: 76px;
            z-index: 999;
            top: 0;
            width: 100%;
            display: flex;
            align-items: center;
            color: black;
            font-weight: bold;
            text-align: center;
        }

        .site-name {
            flex: 1;
            height: 76px;
            line-height: 50px;
            background: url('/asset/assets/amp/img/m16-logo2.png') no-repeat center center;
        }

        .hamburger {
            padding-left: 10px;
        }

        .sidebar {
            padding: 10px;
            margin: 0;
        }

        .sidebar > li {
            list-style: none;
            margin-bottom: 10px;
        }

        .sidebar a {
            text-decoration: none;
            color: black;
            cursor: pointer;
        }

        .close-sidebar {
            font-size: 1.5em;
            padding-left: 5px;
        }

        .raiting li {
            display: inline;
            list-style: none;
        }

        table tr td {
            padding: 5px;
        }

        .srocico {
            background: url(/asset/assets/amp/img/srocico.png) no-repeat transparent;
        }

        .placeico {
            background: url(/asset/assets/amp/img/placeico.png) no-repeat transparent;
        }

        .metroico {
            background: url(/asset/assets/amp/img/metroico.png) no-repeat transparent;
        }

        .show-more, .show-less {
            font-size: 18px;
        }

        h2 {
            padding-left: 16px;
        }

        amp-accordion table tbody {
            display: table;
            width: 100%;
            text-align: center;
        }

        amp-accordion section[expanded] .show-more {
            display: none;
            position: relative;
        }

        amp-accordion section .show-more:after {
            content: "+";
            position: absolute;
            right: 31px;
            top: 0;
            cursor: pointer;
        }

        amp-accordion section:not([expanded]) .show-less {
            display: none;
        }

        amp-accordion section .show-less:after {
            content: "-";
            position: absolute;
            right: 31px;
            top: 0;
            cursor: pointer;
        }

        amp-accordion section h3 {
            text-align: center;
        }

        amp-accordion section strong {
            padding-left: 15px;
        }

        amp-accordion table {
            padding: 7px 14px 8px 11px;
        }

        .alignH2 {
            text-align: center;
        }

        .alignH2 > h2 {
            padding-left: 0;
        }

        .ampstart-btn {
            background-color: #019cdf;
            border: none;
            display: block;
            margin: 30px auto;
            padding: 0 21px;
            height: 40px;
            border-radius: 4px;
            color: white;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            vertical-align: center;
        }
        ampstart-btn.feedback {
            margin: 30px auto 15px;
        }
        footer ul {
            text-align: center;
        }
        footer ul li {
            display: inline-block;
        }
        svg path { fill: #019cdf;}
        svg path:hover { fill: #000;}
    </style>
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script type="application/ld+json">
			{
			 "@context": "http://schema.org",
			 "@type": "Product",
			 "name": "<?php echo $page['name']; ?>",
			"description": "<?php echo $page['description']; ?>",
			 "offers": {
			   "@type": "AggregateOffer",
			   "lowPrice": "<?php echo $page['lprice']; ?>",
			   "highPrice": "<?php echo $page['hprice']; ?>",
			   "priceCurrency": "RUB"
			 },
			 "aggregateRating": {
			   "@type": "AggregateRating",
			"@id":"<?php echo $page['link']; ?>",
			   "ratingValue": "<?php echo $page['rating']; ?>",
			"reviewCount": "<?php echo $page['raters']; ?>",
			"bestRating": "5",
			"worstRating": "0"
			 }
			}


    </script>
</head>
<body>
<amp-analytics type="googleanalytics">
    <script type="application/json">
        {
            "vars": {
                "account": "UA-84755249-1"
            },
            "triggers": {
                "trackPageview": {
                    "on": "visible",
                    "request": "pageview"
                }
            }
        }
    </script>
</amp-analytics>
<amp-analytics type="metrika">
    <script type="application/json">
        {
            // Отправка параметров визита и посетителя
            "vars": {
                "counterId": "29760432",
                "yaParams": "{\"key\":\"value\",\"__ymu\":{\"user_param_key\":\"user_param_value\"}}"
            },
            // Передача триггеров
            "triggers": {
                // Точный показатель отказов
                "notBounce": {
                    "on": "timer",
                    "timerSpec": {
                        "immediate": false,
                        "interval": 15,
                        "maxTimerLength": 16
                    },
                    "request": "notBounce"
                },
                // Скроллинг страницы
                "halfScroll": {
                    "on": "scroll",
                    "scrollSpec": {
                        "verticalBoundaries": [
                            50
                        ]
                    },
                    // Отслеживание скроллинга как цели
                    "request": "reachGoal",
                    "vars": {
                        "goalId": "halfScrollGoal"
                    }
                },
                // Скроллинг страницы
                "partsScroll": {
                    "on": "scroll",
                    "scrollSpec": {
                        "verticalBoundaries": [
                            25,
                            90
                        ]
                    },
                    // Отслеживание скроллинга как цели
                    "request": "reachGoal",
                    "vars": {
                        "goalId": "partsScrollGoal"
                    }
                }
            }
        }
    </script>
</amp-analytics>
<header class="headerbar">
    <div role="button" on="tap:sidebar1.toggle" tabindex="0" class="hamburger">☰</div>
    <div class="site-name"></div>
</header>
<amp-sidebar id="sidebar1" layout="nodisplay" side="left">
    <div role="button" aria-label="close sidebar" on="tap:sidebar1.toggle" tabindex="0" class="close-sidebar">✕</div>
    <ul class="sidebar">
        <li><a href="https://m16-estate.ru/sell-appart">Продать квартиру</a></li>
        <li><a href="https://m16-estate.ru/buildings">Новостройки</a></li>
        <li><a href="https://m16-estate.ru/resale">Вторичная недвижимость</a></li>
        <li><a href="https://m16-estate.ru/residential">Загородная</a></li>
        <li><a href="https://m16-estate.ru/commercial">Коммерческая</a></li>
        <li><a href="https://m16-estate.ru/assignment">Переуступки</a></li>
        <li><a href="https://m16-estate.ru/arenda">Аренда</a></li>
        <li><a href="https://m16-estate.ru/news">Новости</a></li>
        <li><a href="https://m16-estate.ru/kontakty">Контакты</a></li>
    </ul>
</amp-sidebar>
<main>
    <h1><?php echo $page['h1']; ?></h1>
    <p>От <?php echo number_format($page['fprice'], 0, '.', ' '); ?> руб.</p>
    <amp-carousel layout="responsive" width="767" height="530" type="slides" autoplay delay="3000" controls="" loop>
        <?php foreach ($page['carousel'] as $value) { ?>
            <amp-img src="<?php echo $value; ?>" width="767" height="530" layout="responsive"></amp-img>
        <?php } ?>
    </amp-carousel>
    <ul class="raiting">
        <?php for ($i = 1; $i <= $page['rating']; $i++) { ?>
            <li>&#9733;</li>
        <?php } ?>
        <li><?php echo $page['rating']; ?> из 5</li>
    </ul>
    <div class="wrap">
        <table>
            <tbody>
            <tr>
                <td class="srocico"></td>
                <td>Срок сдачи: <?php echo $page['srok']; ?></td>
            </tr>
            <tr>
                <td class="placeico"></td>
                <td><?php echo $page['adress']; ?></td>
            </tr>
            <tr>
                <td class="metroico"></td>
                <td><?php echo $page['metro']; ?></td>
            </tr>
            <tr>
                <td>Район:</td>
                <td><?php echo $page['rayon']; ?></td>
            </tr>
            <tr>
                <td>Застройщик:</td>
                <td><?php echo $page['builder']; ?></td>
            </tr>
            </tbody>
        </table>
    </div>
    <amp-accordion animate>
        <section expanded>
            <h2>
                <span class="show-more">Описание</span>
                <span class="show-less">Описание</span>
            </h2>
            <div class="wrap">
                <?php echo $page['opisanie']; ?>
            </div>
        </section>
    </amp-accordion>
    <amp-accordion animate>
        <?php if (strlen($page['otdelka'])) { ?>
            <section>
                <h2>
                    <span class="show-more">Отделка квартир</span>
                    <span class="show-less">Отделка квартир</span>
                </h2>
                <div class="wrap">
                    <?php echo $page['otdelka']; ?>
                </div>
            </section>
        <?php } ?>
    </amp-accordion>
    <?php if (strlen($page['infra'])){ ?>
    <amp-accordion animate>
        <section>
            <h2>
                <span class="show-more">Инфраструктура</span>
                <span class="show-less">Инфраструктура</span>
            </h2>
            <div class="wrap">
                <?php echo $page['infra']; ?>
            </div>
        </section>
        <?php } ?>
    </amp-accordion>
    <?php if (strlen($page['transport'])){ ?>
    <amp-accordion animate>
        <section>
            <h2>
                <span class="show-more">Транспортная доступность</span>
                <span class="show-less">Транспортная доступность</span>
            </h2>
            <div class="wrap">
                <?php echo $page['transport']; ?>
            </div>
        </section>
        <?php } ?>
    </amp-accordion>
    <?php if (strlen($page['ipoteka'])){ ?>
    <amp-accordion animate>
        <section>
            <h2>
                <span class="show-more">Ипотека</span>
                <span class="show-less">Ипотека</span>
            </h2>
            <div class="wrap">
                <?php echo $page['ipoteka']; ?>
            </div>
        </section>
        <?php } ?>
    </amp-accordion>
    <div class="alignH2">
        <h2>Планировки</h2>
    </div>
    <amp-accordion animate>
        <?php foreach ($page['plan'] as $roomrow) { ?>
            <section>
                <h2>
                    <span class="show-more"><?php echo $roomrow['title']; ?></span>
                    <span class="show-less"><?php echo $roomrow['title']; ?></span>
                </h2>
                <table>
                    <tr>
                        <th>Площадь</th>
                        <th>Цена</th>
                        <th>Планировка</th>
                    </tr>
                    <?php foreach ($roomrow['content'] as $plan) { ?>
                        <tr>
                            <td><?php echo $plan['square']; ?> м²</td>
                            <td>от <?php echo number_format($plan['price'], 0, '.', ' '); ?> руб.</td>
                            <td>
                                <amp-img src="<?php echo $plan['img']; ?>" width="300" height="168"
                                         layout="responsive"></amp-img>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </section>
        <?php } ?>
    </amp-accordion>
    <a  class="ampstart-btn" href="https://m16-estate.ru/buildings/<?php echo $page['mainlink']; ?>#scroll-child">Посмотреть все планировки</a>
    <div>
        <div class="alignH2">
            <h2>Объекты рядом</h2>
        </div>
        <amp-carousel layout="responsive" width="767" height="600" type="slides" autoplay delay="3000" controls="" loop>
            <?php foreach ($page['near'] as $object) { ?>
                <div class="slide slide-text">
                    <a href="<?php echo $object['link']; ?>">
                        <amp-img src="<?php echo $object['img']; ?>" width="767" height="530" layout="responsive"></amp-img>
                        <amp-fit-text layout="responsive"
                                      width="767"
                                      height="50" style="color: black; padding-top: 10px;">ЖК «<?php echo $object['name']; ?>»
                        </amp-fit-text>
                    </a>
                </div>
            <?php } ?>
        </amp-carousel>
    </div>
    <hr>
    <a class="ampstart-btn" href="https://m16-estate.ru/buildings/<?php echo $page['mainlink']; ?>#askLand">Заказать обратный звонок</a>
</main>
<footer>
    <p style="font-size: 13px; text-align: center;"><strong>Тел.: <span itemprop="telephone">+7 (812) 604-30-55; 8-800-550-55-16</span></strong></p>
    <ul class="ampstart-social-follow list-reset flex justify-around items-center flex-wrap m0 mb4">
      <li class="mr2">
        <a href="https://twitter.com/m16bz" class="inline-block" target="_blank" aria-label="Link to Twitter">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="22.2" viewbox="0 0 53 49">
            <title>Twitter</title>
            <path d="M45 6.9c-1.6 1-3.3 1.6-5.2 2-1.5-1.6-3.6-2.6-5.9-2.6-4.5 0-8.2 3.7-8.2 8.3 0 .6.1 1.3.2 1.9-6.8-.4-12.8-3.7-16.8-8.7C8.4 9 8 10.5 8 12c0 2.8 1.4 5.4 3.6 6.9-1.3-.1-2.6-.5-3.7-1.1v.1c0 4 2.8 7.4 6.6 8.1-.7.2-1.5.3-2.2.3-.5 0-1 0-1.5-.1 1 3.3 4 5.7 7.6 5.7-2.8 2.2-6.3 3.6-10.2 3.6-.6 0-1.3-.1-1.9-.1 3.6 2.3 7.9 3.7 12.5 3.7 15.1 0 23.3-12.6 23.3-23.6 0-.3 0-.7-.1-1 1.6-1.2 3-2.7 4.1-4.3-1.4.6-3 1.1-4.7 1.3 1.7-1 3-2.7 3.6-4.6" class="ampstart-icon ampstart-icon-twitter"></path>
          </svg>
        </a>
      </li>
      <li class="mr2">
        <a href="https://www.facebook.com/m16group/" class="inline-block" target="_blank" aria-label="Link to Facebook">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="23.6" viewbox="0 0 56 55">
            <title>Facebook</title>
            <path d="M47.5 43c0 1.2-.9 2.1-2.1 2.1h-10V30h5.1l.8-5.9h-5.9v-3.7c0-1.7.5-2.9 3-2.9h3.1v-5.3c-.6 0-2.4-.2-4.6-.2-4.5 0-7.5 2.7-7.5 7.8v4.3h-5.1V30h5.1v15.1H10.7c-1.2 0-2.2-.9-2.2-2.1V8.3c0-1.2 1-2.2 2.2-2.2h34.7c1.2 0 2.1 1 2.1 2.2V43" class="ampstart-icon ampstart-icon-fb"></path>
          </svg>
        </a>
      </li>
      <li class="mr2">
        <a href="https://www.instagram.com/m16group/" class="inline-block" target="_blank" aria-label="Link to insta gram">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 54 54">
            <title>instagram</title>
            <path d="M27.2 6.1c-5.1 0-5.8 0-7.8.1s-3.4.4-4.6.9c-1.2.5-2.3 1.1-3.3 2.2-1.1 1-1.7 2.1-2.2 3.3-.5 1.2-.8 2.6-.9 4.6-.1 2-.1 2.7-.1 7.8s0 5.8.1 7.8.4 3.4.9 4.6c.5 1.2 1.1 2.3 2.2 3.3 1 1.1 2.1 1.7 3.3 2.2 1.2.5 2.6.8 4.6.9 2 .1 2.7.1 7.8.1s5.8 0 7.8-.1 3.4-.4 4.6-.9c1.2-.5 2.3-1.1 3.3-2.2 1.1-1 1.7-2.1 2.2-3.3.5-1.2.8-2.6.9-4.6.1-2 .1-2.7.1-7.8s0-5.8-.1-7.8-.4-3.4-.9-4.6c-.5-1.2-1.1-2.3-2.2-3.3-1-1.1-2.1-1.7-3.3-2.2-1.2-.5-2.6-.8-4.6-.9-2-.1-2.7-.1-7.8-.1zm0 3.4c5 0 5.6 0 7.6.1 1.9.1 2.9.4 3.5.7.9.3 1.6.7 2.2 1.4.7.6 1.1 1.3 1.4 2.2.3.6.6 1.6.7 3.5.1 2 .1 2.6.1 7.6s0 5.6-.1 7.6c-.1 1.9-.4 2.9-.7 3.5-.3.9-.7 1.6-1.4 2.2-.7.7-1.3 1.1-2.2 1.4-.6.3-1.7.6-3.5.7-2 .1-2.6.1-7.6.1-5.1 0-5.7 0-7.7-.1-1.8-.1-2.9-.4-3.5-.7-.9-.3-1.5-.7-2.2-1.4-.7-.7-1.1-1.3-1.4-2.2-.3-.6-.6-1.7-.7-3.5 0-2-.1-2.6-.1-7.6 0-5.1.1-5.7.1-7.7.1-1.8.4-2.8.7-3.5.3-.9.7-1.5 1.4-2.2.7-.6 1.3-1.1 2.2-1.4.6-.3 1.6-.6 3.5-.7h7.7zm0 5.8c-5.4 0-9.7 4.3-9.7 9.7 0 5.4 4.3 9.7 9.7 9.7 5.4 0 9.7-4.3 9.7-9.7 0-5.4-4.3-9.7-9.7-9.7zm0 16c-3.5 0-6.3-2.8-6.3-6.3s2.8-6.3 6.3-6.3 6.3 2.8 6.3 6.3-2.8 6.3-6.3 6.3zm12.4-16.4c0 1.3-1.1 2.3-2.3 2.3-1.3 0-2.3-1-2.3-2.3 0-1.2 1-2.3 2.3-2.3 1.2 0 2.3 1.1 2.3 2.3z" class="ampstart-icon ampstart-icon-instagram"></path>
          </svg>
        </a>
      </li>
      <li class="mr2">
        <a href="https://plus.google.com/+%D0%9C16%D0%9D%D0%B5%D0%B4%D0%B2%D0%B8%D0%B6%D0%B8%D0%BC%D0%BE%D1%81%D1%82%D1%8C" class="inline-block" target="_blank" aria-label="Link to Google Plus">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="15.5" viewbox="0 0 45 29">
            <title>Google Plus</title>
            <path d="M45 12.5h-4.1V8.4h-4.1v4.1h-4.1v4h4.1v4.1h4.1v-4.1H45v-4zm-30.7 0v4.9h8.1c-.3 2.1-2.4 6.1-8.1 6.1-4.8 0-8.8-4-8.8-9s4-9 8.8-9c2.8 0 4.7 1.2 5.7 2.2l3.9-3.8C21.4 1.6 18.2.2 14.3.2 6.4.2 0 6.6 0 14.5s6.4 14.3 14.3 14.3c8.3 0 13.8-5.8 13.8-14 0-.9-.1-1.6-.3-2.3H14.3z" class="ampstart-icon ampstart-icon-gplus"></path>
          </svg>
        </a>
      </li>
      <li class="mr2">
        <a href="mailto:mail@m16.bz" class="inline-block" aria-label="Link to E mail">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="18.4" viewbox="0 0 56 43">
            <title>email</title>
            <path d="M10.5 6.4C9.1 6.4 8 7.5 8 8.9v21.3c0 1.3 1.1 2.5 2.5 2.5h34.9c1.4 0 2.5-1.2 2.5-2.5V8.9c0-1.4-1.1-2.5-2.5-2.5H10.5zm2.1 2.5h30.7L27.9 22.3 12.6 8.9zm-2.1 1.4l16.6 14.6c.5.4 1.2.4 1.7 0l16.6-14.6v19.9H10.5V10.3z" class="ampstart-icon ampstart-icon-email"></path>
          </svg>
        </a>
      </li>
    </ul>
</footer>
</body>
</html>
