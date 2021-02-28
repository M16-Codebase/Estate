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
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">

      <link rel="canonical" href="<?php echo $page['canonical']; ?>">
      <link rel="shortcut icon" href="<?php echo $page['favicon']; ?>">

      <title><?php echo $page['title']; ?></title>
    <script  async  custom-element = "amp-ad"  src = "https://cdn.ampproject.org/v0/amp-ad-0.1.js" ></script>
    <script async custom-element="amp-fit-text" src="https://cdn.ampproject.org/v0/amp-fit-text-0.1.js"></script>
    <script  async  custom-element = "amp-sidebar"  src = "https://cdn.ampproject.org/v0/amp-sidebar-0.1.js" > </script>
    <script  async  custom-element = "amp-carousel"  src = "https://cdn.ampproject.org/v0/amp-carousel-0.1.js"> </script>
    <script async custom-element="amp-accordion" src="https://cdn.ampproject.org/v0/amp-accordion-0.1.js"></script>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
    <style amp-custom>
      body {
        width: auto;
        margin: 0;
        padding: 0;
        font-family: "GOTHAPROREG", sans-serif;
        /*font-family: 'Open Sans', sans-serif;*/
      }
      h1 {
        margin: 0;
        padding: 0.5em;
        text-align: center;
        font-size: 22px;
        background: white;
        box-shadow: 0px 3px 5px grey;
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
      .wrapTable {
        margin: 0.5em;
        padding: 0.5em;
        overflow: scroll;
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
        background:url('https://m16-estate.ru/asset/assets/img/m16-logo.png') no-repeat center center;
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
        margin-bottom:10px;
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

      table {
        border-collapse: collapse;
      }

      table thead {
        background-color: #019cdf;
        color: white;
      }

      table tr td, table tr th {
        padding: 6px;
        border: 1px solid #019cdf;
      }
      .show-more, .show-less {
        font-size: 18px;
      }
      h2 {
        padding-left: 16px;
      }
      .i-amphtml-accordion-header {
          height: 48px;
          line-height: 48px;
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
      }
      blockquote.ampstart-pullquote {
        border-left: 6px solid #40C8F4;
        padding-left: 20px;
      }
      figure.ampstart-image-with-caption figcaption {
        background-color: #eee;
        font-style: italic;
        text-align: center;
        padding: 12px;
        font-size: 13px;
      }

      @font-face  { 
        font-family :  "GOTHAPROREG" ; 
        src :  url ( "fonts/GOTHAPROREG.OTF" ); 
      }
      }
    </style>
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script type="application/ld+json">
    {
     "@context": "http://schema.org",
     "@type": "NewsArticle",
     "mainEntityOfPage":{
       "@type":"WebPage",
       "@id":"<?php echo $page['canonical']; ?>"
     },
     "headline": "<?php echo $page['name']; ?>",
     "image": {
       "@type": "ImageObject",
       "url": "<?php echo $page['mainphoto']; ?>",
       "height": 800,
       "width": 800
     },
     "datePublished": "<?php echo $page['date']; ?>T08:00:00+08:00",
     "dateModified": "<?php echo $page['date']; ?>T09:20:00+08:00",
     "author": {
       "@type": "Person",
       "name": "<?php echo $page['author']; ?>"
     },
     "publisher": {
       "@type": "Organization",
       "name": "M16 - Недвижимость",
       "logo": {
         "@type": "ImageObject",
         "url": "https://img/m16-logo.png",
         "width": 600,
         "height": 60
       }
     },
     "description": "<?php echo $page['description']; ?>"
    }
    </script>
  </head>
  <body>
    <header class="headerbar"> <!-- начало блока навигации-->
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
    </amp-sidebar> <!-- конец блока навигации-->

    <h1><?php echo $page['title']; ?></h1>
    <figure class="ampstart-image-with-caption m0 relative mb4" style="width: 100%; padding: 0; margin: 0;"> <!-- картинка начало -->
        <amp-img src="<?php echo $page['mainphoto']; ?>" width="820" height="540" layout="responsive" class="" alt="<?php echo $page['description']; ?>"></amp-img>
        <figcaption class="h5 mt1 px3">
            <?php echo $page['description']; ?>
        </figcaption>
    </figure> <!-- картинка конец -->
    <?php echo $page['content']; ?>


  </body>
</html>
