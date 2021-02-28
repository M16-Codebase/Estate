<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
if($_POST['page']=='2'){
    echo ('<div id="content" style="padding-bottom: 0px;">
    <section id="div2" class="container-fluid block-front-realty_types about" style="background: url(/asset/assets/img/sales/background-grey.png); display: none;">
        <!--Чем мы занимакмся-->
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="wrapTextCenter">
                        <h2>Наши направления</h2>
                    </div>
                    <div class="col-xs-10 realty line">
                        <div class="item animations firsting animated flipInY" style="">
                            <a href="/buildings">
                                <img src="https://m16-estate.ru/asset/assets/img/buildingsimg.jpg">
                                <div class="opac"></div>
                                <span class="buildspan">Новостройки</span>
                            </a>
                        </div>
                        <!--<div class="item animations firsting animated flipInY" style="">
                            <a href="/exclusive">
                                <img src="https://m16-estate.ru/asset/assets/img/resaleimg.jpg">
                                <div class="opac"></div>
                                <span class="commspan">Эксклюзивная</span>
                            </a>
                        </div>-->
                        <div class="item animations firsting animated flipInY" style="">
                            <a href="/commercial">
                                <img src="https://m16-estate.ru/asset/assets/img/commercialimg.jpg">
                                <div class="opac"></div>
                                <span class="commspan">Коммерческая</span>
                            </a>
                        </div>
                        <div class="item animations firsting animated flipInY" style="">
                            <a href="/assignment">
                                <img src="https://m16-estate.ru/asset/assets/img/assignmentimg.jpg">
                                <div class="opac"></div>
                                <span class="commspan">Переуступки</span>
                            </a>
                        </div>
                        <div class="item animations seconding animated flipInY" style="">
                            <a href="/resale">
                                <img src="https://m16-estate.ru/asset/assets/img/vtorichka.jpg">
                                <div class="opac"></div>
                                <span class="resalespan">Вторичная</span>
                            </a>
                        </div>
                        <div class="item animations seconding animated flipInY" style="">
                            <a href="/residential">
                                <img src="https://m16-estate.ru/asset/assets/img/residentialimg.jpg">
                                <div class="opac"></div>
                                <span class="residspan">Загородная</span>
                            </a>
                        </div>
                        <div class="item animations seconding margin-fix animated flipInY" style="">
                            <a href="/arenda">
                                <img src="https://m16-estate.ru/asset/assets/img/arendaimg.jpg">
                                <div class="opac"></div>
                                <span class="arendaspan">Аренда</span>
                            </a>
                        </div>
                        <!--<div class="item animations seconding margin-fix animated flipInY" style="">
                            <a href="#">
                                <img src="https://m16-estate.ru/asset/assets/img/otdelka.jpeg">
                                <div class="opac"></div>
                                <span class="worldspan">Дизайн и ремонт</span>
                            </a>
                        </div>-->
                    </div>
                </div>
                <div class="contain">
                    <div class="seotext">
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>');
	echo(' <section id="div3" class="container-fluid block-front-realty_types services" style="background: url(/asset/assets/img/sales/background-grey.png); display: none;">
        <!--Услуги-->
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="wrapTextCenter">
                        <h2>Услуги</h2>
                    </div>
                    <div class="col-xs-12 realty">
                        <div class="item animations firsting animated flipInY" style="">
                            <a href="/sell-appart">
                                <img src="https://m16-estate.ru/asset/assets/img/buildingsimg.jpg">
                                <div class="opac"></div>
                                <span class="buildspan">Продажа квартир</span>
                            </a>
                        </div>
                        <div class="item animations firsting animated flipInY" style="">
                            <a href="/regionalnym-klientam">
                                <img src="https://m16-estate.ru/asset/assets/img/regions-usluga.jpg">
                                <div class="opac"></div>
                                <span class="commspan">Региональным клиентам</span>
                            </a>
                        </div>
                        <div class="item animations firsting animated flipInY" style="">
                            <a href="/military">
                                <img src="https://m16-estate.ru/asset/assets/img/military-usluga.jpg">
                                <div class="opac"></div>
                                <span class="commspan">Военная ипотека</span>
                            </a>
                        </div>
                        <div class="item animations firsting margin-fix animated flipInY" style="">
                            <a href="/excursion">
                                <img src="https://m16-estate.ru/asset/assets/img/bus-usluga.jpg">
                                <div class="opac"></div>
                                <span class="commspan">Бесплатные автобусные экскурсии</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="contain">
                    <div class="seotext">
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </section><div class="clearfix"></div>');
}
elseif ($_POST['page']=='3'){
    echo('<section class="container-fluid m16 advantages" id="div4" style="display: none;">
        <div class="row">
            <div class="wrapTextCenter">
                <h2>Работайте с М16</h2>
            </div>
            <div class="container">
                <div>
                    <div style="background: url(/asset/assets/img/reputation.png) no-repeat top center/58px; width: 23%;"> 
                        <p>Безупречная репутация компании</p>
                    </div>
                    <div style="background: url(/asset/assets/img/lawyers.png) no-repeat top center/58px; width: 23%;">
                        <p>Собственный юридический отдел, профессиональное сопровождение сделок</p>
                    </div>
                    <div style="background: url(/asset/assets/img/home.png) no-repeat top center/58px; width: 23%;">
                        <p>Квартира в зачет / Flat Trade-In</p>
                    </div>
                    <div style="background: url(/asset/assets/img/sold.png) no-repeat top center/58px; width: 23%;">
                        <p>Короткие сроки продажи Вашей недвижимости</p>
                    </div>
                </div>
                <div>
                    <div style="background: url(/asset/assets/img/cart.png) no-repeat top center/58px; width: 31%;">
                        <p>Принцип &laquo;одного окна&raquo; &mdash; от продажи и покупки недвижимости до оформления дизайн-проекта и ремонта</p>
                    </div>
                    <div style="background: url(/asset/assets/img/head.png) no-repeat top center/43px; width: 31%;">
                        <p>Индивидуальные экскурсии с менеджером по интересующим Вас объектам</p>
                    </div>
                    <div style="background: url(/asset/assets/img/key.png) no-repeat top center/45px; width: 31%;">
                        <p>Предпродажная подготовка: клининг, профессиональная фото- и видеосъемка, 3D-тур, максимальный охват рекламных площадок</p>
                    </div>
                </div>
            </div>
        </div>
    </section>');
}
elseif ($_POST['page']=='4'){
    echo ('<section id="div5" class="container-fluid feedback" style="background: url(/asset/assets/img/sales/background-grey.png); display: none;">
        <div class="wrapTextCenter">
            <h2>Отзывы наших клиентов</h2>
        </div>
        <div class="responive">
            <div id="preview"></div>
            <div class="wrapSlider">
                            <ul id="lightSlider" class="cS-hidden">
                                <li>
                                    <iframe width="450" height="300" src="https://www.youtube.com/embed/9jVIYRqtd_w" frameborder="0" allowfullscreen=""></iframe>

                                    <p>
                                        <strong>28.07.2017</strong>
                                    </p>
                                    <p>
                                        <strong style="font-size: 15px;">СЕМЬЯ БАРКОВЫХ</strong>
                                    </p>
                                    <p style="font-size: 15px;">«Наша работа состояла в том, чтобы продать комнату в коммунальной квартире. Комната в не очень хорошем состоянии. Работа была выполнена очень быстро, мы очень довольны. Деньги уже в кармане. Сотрудничество было очень приятным, мы получили массу удовольствия при совместной работе. Мы очень довольны и счастливы!<br>
                                            Этого человека (Александра Минакова) мы и хотели поблагодарить. Александр выполнил свою работу на пять с плюсом!»
                                    </p>
                                </li>
                                <li>
                                    <a href="https://m16-estate.ru/asset/uploads/reviews/temp/9c3c90177e7e58be03846447ed12ebe2.jpeg" class="otzmag">
                                        <img src="https://m16-estate.ru/asset/uploads/reviews/temp/rpreview/9c3c90177e7e58be03846447ed12ebe2.jpeg">
                                    </a>
                                    <p>
                                        <strong>29.12.2018</strong>
                                    </p>
                                    <p>
                                        <strong style="font-size: 15px;">ЕКАТЕРИНА И ГЕОРГИЙ ПЕТРОВЫ</strong>
                                    </p>
                                    <p style="font-size: 15px;">
                                        «Хотим выразить огромную благодарность Макаренко Марине Васильевне за безупречную организацию, с нашей стороны сделки покупки квартиры в Пушкине. Марина представляла сторону продавца. Мы были очень довольны, что Марина подробно вникала, помогала с подготовкой документов и для нашей стороны - покупателя.  Марина оказалась очень чутким, внимательным и не конфликтным человеком и профессионалом своего дела. Сделка была организована и продумана на высоком уровне. Марина  все документы собрала заранее и записала в банк, к нотариусу и МФЦ, что позволило быстро без долгого ожидания провести всю сделку.
                                    </p> 
                                    <p style="font-size: 15px;">Нас забрала комфортная машина от М16 со внимательным водителем, который нас возил и сопровождал куда было необходимо.
                                    </p>
                                    <p style="font-size: 15px;">Спасибо , Вам Марина за четкую и профессиональную работу! Очень было приятно с Вами работать и обязательно будем рекомендовать Вас , как профессионала и Вашу Компанию «М16 Недвижимость», как надежную и проверенную!»
                                    </p>
                                </li>
                                <li>
                                    <a href="https://m16-estate.ru/asset/uploads/reviews/temp/cbeb4173bc4eddb28017c981e464a62a.JPG" class="otzmag">
                                        <img src="https://m16-estate.ru/asset/uploads/reviews/temp/rpreview/cbeb4173bc4eddb28017c981e464a62a.JPG">
                                    </a>
                                    <p>
                                        <strong>12.03.2018</strong>
                                    </p>
                                    <p>
                                        <strong style="font-size: 15px;">СЕРГЕЙ КУДРЯВЦЕВ</strong>
                                    </p>
                                    <p style="font-size: 15px;">
                                        «Нужно было продать двухкомнатную квартиру в Новом Девяткино. В этом деле нам помог риелтор Александр Минаков. Квартира была выставлена в конце сентября, а в январе ипотечная сделка успешно завершилась. Благодарю компанию «М16» и Александра Минакова за внимательную и четкую работу!»
                                    </p>
                                </li>
                                <li>
                                    <a href="https://m16-estate.ru/asset/uploads/reviews/temp/a39bf1a99589dbba554e2e1e80fe6af3.jpeg" class="otzmag">
                                        <img src="https://m16-estate.ru/asset/uploads/reviews/temp/rpreview/a39bf1a99589dbba554e2e1e80fe6af3.jpeg">
                                    </a>
                                    <p>
                                        <strong>08.12.2017</strong>
                                    </p>
                                    <p>
                                        <strong style="font-size: 15px;">ЕЛЕНА</strong>
                                    </p>
                                    <p style="font-size: 15px;">
                                        «Очень довольны работой М16, поэтому опишу всё с начала, думаю, многим будет полезно.
                                                В М16 обратилась к менеджеру Макаренко Марине с просьбой помочь продать квартиру, которую почти три года пыталась продать самостоятельно.</p>
                                    <p style="font-size: 15px;">
                                        Почему М16? Это агентство очень активно работает на рынке недвижимости. Видно это по всем видам рекламы. Да и личности у руководства стоят такие, что не подведут. Не в их стиле.
                                    </p>
                                    <p style="font-size: 15px;">
                                        Почему Марина? А просто посмотрите отзывы клиентов агентства.
                                    </p>
                                    <p style="font-size: 15px;">
                                        Тут выяснилось (и обоснованно), я ошибалась с оценкой стоимости моей квартиры. С рекламой тоже мои возможности оказались весьма ограниченными. А Марина - профессионал в своем деле, плюс энергичная, интеллигентная, с широким кругозором.
                                    </p>
                                    <p style="font-size: 15px;">
                                        И квартиру продали без лишних нервов, пользуясь банковским аккредитивом. На всех этапах чувствовали юридическую поддержку и участие от личного специалиста в деле регистрации документов. Свою работу М16 оценили весьма приемлемо.
                                    </p>
                                    <p style="font-size: 15px;">
                                        Самое интересное было со встречной покупкой. Понравился нам очень непростой вариант с точки зрения надежности. Опять Марина на высоте. Спокойно разобралась, привлекла юридическую службу М16, вышла на специалиста по нашему узкому вопросу. До последнего момента поддерживала морально.
                                    </p>
                                    <p style="font-size: 15px;">
                                        Очень довольна. И дело сделала, и познакомилась с прекрасными людьми.»
                                    </p>
                                </li>
                                <li>
                                    <a href="https://m16-estate.ru/asset/uploads/reviews/temp/299801fc080501f1e5657cfe0f50eb6a.PNG" class="otzmag">
                                        <img src="https://m16-estate.ru/asset/uploads/reviews/temp/rpreview/299801fc080501f1e5657cfe0f50eb6a.PNG">
                                    </a>
                                    <p>
                                        <strong>11.11.2017</strong>
                                    </p>
                                    <p>
                                        <strong style="font-size: 15px;">ЕЛЕНА</strong>
                                    </p>
                                    <p style="font-size: 15px;">
                                        «Благодарю сотрудников М16 и менеджера Ерофееву Марию Александровну за отличную работу, за хорошее отношение,всем советую работать с этой компанией,выражаю благодарность водителям Дмитрию и Константину,за внимание и отношение к своим клиентам!!!»
                                    </p>
                                </li>
                                <li>
                                    <a href="https://m16-estate.ru/asset/uploads/reviews/temp/b18aec05f5819d5ebdc151e0454d8e9d.jpeg" class="otzmag">
                                        <img src="https://m16-estate.ru/asset/uploads/reviews/temp/b18aec05f5819d5ebdc151e0454d8e9d.jpeg">
                                    </a>
                                    <p>
                                        <strong>07.12.2018</strong>
                                    </p>
                                        <p>
                                            <strong style="font-size: 15px;">ЕЛЕНА ЛОМАКИНА</strong>
                                        </p>
                                    <p style="font-size: 15px;">
                                        «Выражаю огромную благодарность сотрудникам вашего агентства Александру Минакову и Екатерине Михайловой за высокий профессионализм, оперативность в работе и личную порядочность при проведении сделки по купле-продаже квартиры.»
                                    </p>
                                </li>
                            </ul>
        </div>
        <div id="next"></div>
    </section><!--Конец блока отзывы-->');
}
elseif ($_POST['page']=='5'){
    echo (' <!--Дипломы и сертификаты-->
        <div id="div6" class="diploms" style="padding-top: 81px; margin-top: 0; display: none;">
            <div class="contain">
                <div class="one-about-twocols clearfix">
                	  <div class="wrapTextCenter">
                        <h2>Дипломы и сертификаты</h2>
                    </div>
                    <ul class="about-bxslider">
                        <li><a href="/asset/assets/img/serfic1.jpg"><img src="/asset/assets/img/serfi1.jpg" class="grey" /><img src="/asset/assets/img/serfi1.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic2.jpg"><img src="/asset/assets/img/serfi2.jpg" class="grey" /><img src="/asset/assets/img/serfi2.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic3.jpg"><img src="/asset/assets/img/serfi3.jpg" class="grey" /><img src="/asset/assets/img/serfi3.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic4.jpg"><img src="/asset/assets/img/serfi4.jpg" class="grey" /><img src="/asset/assets/img/serfi4.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic5.jpg"><img src="/asset/assets/img/serfi5.jpg" class="grey" /><img src="/asset/assets/img/serfi5.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic6.jpg"><img src="/asset/assets/img/serfi6.jpg" class="grey" /><img src="/asset/assets/img/serfi6.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic7.jpg"><img src="/asset/assets/img/serfi7.jpg" class="grey" /><img src="/asset/assets/img/serfi7.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic8.jpg"><img src="/asset/assets/img/serfi8.jpg" class="grey" /><img src="/asset/assets/img/serfi8.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic9.jpg"><img src="/asset/assets/img/serfi9.jpg" class="grey" /><img src="/asset/assets/img/serfi9.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic10.jpg"><img src="/asset/assets/img/serfi10.jpg" class="grey" /><img src="/asset/assets/img/serfi10.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic11.jpg"><img src="/asset/assets/img/serfi11.jpg" class="grey" /><img src="/asset/assets/img/serfi11.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic12.jpg"><img src="/asset/assets/img/serfi12.jpg" class="grey" /><img src="/asset/assets/img/serfi12.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic13.jpg"><img src="/asset/assets/img/serfi13.jpg" class="grey" /><img src="/asset/assets/img/serfi13.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic14.jpg"><img src="/asset/assets/img/serfi14.jpg" class="grey" /><img src="/asset/assets/img/serfi14.jpg" class="original" /></a></li>
                    </ul>
                </div>
            </div>
        </div>
    <!--Конец блока дипломы и сертификаты-->');
}
elseif ($_POST['page']=='6'){
    echo ('<section id="div7" class="block-feedback" style="display: none">
        <!--новости и аналитика-->
        <div class="">
            <div class="image-wrapper anim-otz">
                <div class="contain">
                    <div class="row">
                        <div class="col-lg-5 col-lg-offset-1 news left col-xs-6">
                            <div><h2>Последние новости</h2></div>
                            <div id="ne00" class="item animations animated flipInX" style="">
                                
                            </div>
                        </div>
                        <div class="col-lg-5 news col-xs-6">
                            <div style="border-left: 1px solid #009ce0; margin-left: -15px;"><h2>Статьи и аналитика</h2></div>
                            <div id="ne10" class="item animations animated flipInX" style="">
                                
                            </div>
                        </div>
                        <div class="col-lg-5 col-lg-offset-1 news col-xs-6">
                            <div id="ne01"  class="item animations animated flipInX" style="">
                                
                            </div>
                            <div class="wrapTextCenter">
                                <a href="/news/cat/novosti">Смотреть все новости</a>
                            </div>
                        </div>
                        <div class="col-lg-5 news col-xs-6">
                            <div id="ne11"  class="item animations animated flipInX" style="">
                            
                            </div>
                            <div class="wrapTextCenter">
                                <a href="/news/cat/stati">Смотреть все статьи</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>');
}
elseif ($_POST['page']=='7'){
    echo ('<div class="mapto" style="display: none;" id="novlink">
        <!--картo-->
        <div class="wrapTextCenter">
          <h2>Поиск новостроек по карте</h2>
        </div>
        <div class="map-buildings" id="nov-map" style="display:none;"></div>
    </div>');
}
elseif ($_POST['page']=='8'){
    echo ('<div class="contain" id="div9" style="display: none;">
        <!--Описание-->
        <div class="seotext m16">
		        <div class="wrapTextCenter">
		          <h2>Агентство недвижимости «М16»</h2>
		        </div>
            <div class="lftseomain" style="width: 620px;">
                <!--<img src="https://m16-estate.ru/asset/uploads/images/logo-m16.png" alt="Логотип М16-Недвижимость - агентство недвижимости полного цикла" title="">-->
                <!--<img src="https://m16-estate.ru/asset/assets/img/malafeev-main-seo-1.jpg" alt="Логотип М16-Недвижимость - агентство недвижимости полного цикла" title="">-->
                <img src="https://m16-estate.ru/asset/assets/img/malafeev-main.jpg" alt="Вячеслав Малафеев, агентство недвижимости М16" title="" style="width: 338px; float: left; margin-right: 25px;">
                <div class="speach" style="background-color: #F7F7F7; height: 478px;">
                        <p>
                            Привычка быть лидером помогает мне эффективно управлять командой профессионалов и оправдывать оказанное доверие!
                        </p>
                        <p>
                            Отличная ориентация на рынке, безупречный сервис и эксклюзивные услуги, а также постоянное развитие позволяют «М16-Недвижимость» быстро и продуктивно решать задачи наших клиентов.
                        </p>
                            <span class="speach-foot">
                                Учредитель компании
                            </span>
                            <span class="uchredit">
                                вячеслав малафеев
                            </span>
                            <span class="zenitfc speach-foot">
                                Легенда ФК
                            </span>
                </div>
            </div>
            <div class="rghtseo">
                <!--<h2 style="text-align: center; margin-top: 0; color: black">Кто мы</h2>-->
                <p>«М16-Недвижимость» основана вратарем ФК «Зенит» Вячеславом Малафеевым. География работы — Санкт-Петербург&nbsp;и Ленинградская область.</p>
                <p style="text-align: justify;">Мы предлагаем комплексное обслуживание в сфере купли-продажи и аренды жилья и коммерческой недвижимости.</p>
                <h2 style="text-align: center; color: black">Услуги компании</h2>
                <ul>
                    <li style="text-align: justify;">юридическое сопровождение сделок (проверяем юридическую чистоту объекта, контролируем оформление документов);</li>
                    <li style="text-align: justify;">альтернативную продажу по системе trade-in;</li>
                    <li style="text-align: justify;">срочный выкуп недвижимости;</li>
                    <li style="text-align: justify;">апартаменты для иногородних клиентов на время подбора недвижимости и заключения сделки.</li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <section id="pfooter"class="container block-partner" style="padding-bottom: 285px; display: none;">
        <div class="row">
            <div class="col-xs-12">
                <div class="item"><a><img src="https://m16-estate.ru/asset/uploads/images/partners/grey/616f0abc41f0462031b2933925ae88ea.png" alt="Застройщик ЭталонЛенСпецСМУ"></a></div>
                <div class="item"><a><img src="https://m16-estate.ru/asset/uploads/images/partners/grey/de9c4dc727cdc7176f6ad73619c0dcc8.png" alt="Новстройки Pioneer"></a></div>
                <div class="item"><a><img src="https://m16-estate.ru/asset/uploads/images/partners/grey/8e4529f9f0b8c5085c04ce25a0457623.png" alt="Новостройик ЮИТ ДОМ в Санкт-Петербурге"></a></div>
                <div class="item"><a><img src="https://m16-estate.ru/asset/uploads/images/partners/grey/6a2ad76e19abb947d6612837ac89643b.png" alt="Квартиры от застройщика «ПСК»"></a></div>
                <div class="item"><a><img src="https://m16-estate.ru/asset/uploads/images/partners/grey/ef4905b1f1c9068013650143383a55c1.png" alt="Новостройки от надежного застройщика LSR"></a></div>
                <div class="item"><a><img src="https://m16-estate.ru/asset/uploads/images/partners/grey/e488e1997e3a1d8a98da6cd223d5ab3e.png" alt="Квартиры от RBI СПб"></a></div>
            </div>
        </div>
        <div class="clearfix"></div>
    </section>');
	echo ('<div id="footer" style="display: none;">
    <!--футер-->
    <div class="footer-container" >
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
                                <li><a href="https://www.facebook.com/m16group" target="_blank" class="fb" rel="nofollow" style="text-decoration: line-through !important;"></a></li>
                                <li><a href="https://vk.com/m16group" target="_blank" class="vk" rel="nofollow" style="text-decoration: line-through !important;"></a></li>
                                <li><a href="https://twitter.com/m16bz" target="_blank" class="tw" rel="nofollow" style="text-decoration: line-through !important;"></a></li>
                                <li><a href="https://ok.ru/group/54832562634771" target="_blank" class="od" rel="nofollow" style="text-decoration: line-through !important;"></a></li>
                                <li><a href="https://www.instagram.com/m16group/" target="_blank" class="in" rel="nofollow" style="text-decoration: line-through !important;"></a></li>
                                <li><a href="https://plus.google.com/+М16Недвижимость" target="_blank" class="gp" rel="nofollow" style="text-decoration: line-through !important;"></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="onecol mcol-4 copyr">
                        <a style="color:#fff;text-decoration:underline;" href="/privacy_policy">Политика конфиденциальности</a>
                    </div>
                </div>
                <div class="mrow offert clearfix">
                    <div class="onecol mcol-8">
                        <!--noindex-->
                        <noindex class="NoIndex_clr_bg_txt_and_img">
                            <p>Настоящий сайт и представленные на нем материалы носят исключительно информационный характер и ни при каких условиях не являются публичной офертой, определяемой положениями Статьи 437 Гражданского кодекса РФ.<br>
                                Мы собираем и храним файлы cookies. Файлы cookies не собирают и не хранят никакую личную информацию о Вас. Используя этот сайт, Вы даёте свое согласие на использование cookies. Чтобы отказаться от использования cookie Вам надо немедленно закрыть наш сайт.<br>
                                Более подробно ознакомиться с информацией об использовании файлов cookies, а также нашей Политикой защиты персональных данных Вы можете <a href="/privacy_policy">здесь</a>.
                            </p>
                        </noindex>
                        <!--/noindex-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>');
}
elseif ($_POST['page']=='222') {
    echo ('<div id="content-1" style="padding-bottom: 0px;">
    <section id="div2" class="container-fluid block-front-realty_types about" style="background: url(/asset/assets/img/sales/background-grey.png); ">
        <!--Чем мы занимакмся-->
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="wrapTextCenter">
                        <h2>Наши направления</h2>
                    </div>
                    <div class="col-xs-10 realty line">
                        <div class="item animations firsting animated flipInY" style="">
                            <a href="/buildings">
                                <img src="https://m16-estate.ru/asset/assets/img/buildingsimg.jpg">
                                <div class="opac"></div>
                                <span class="buildspan">Новостройки</span>
                            </a>
                        </div>
                        <!--<div class="item animations firsting animated flipInY" style="">
                            <a href="/exclusive">
                                <img src="https://m16-estate.ru/asset/assets/img/resaleimg.jpg">
                                <div class="opac"></div>
                                <span class="commspan">Эксклюзивная</span>
                            </a>
                        </div>-->
                        <div class="item animations firsting animated flipInY" style="">
                            <a href="/commercial">
                                <img src="https://m16-estate.ru/asset/assets/img/commercialimg.jpg">
                                <div class="opac"></div>
                                <span class="commspan">Коммерческая</span>
                            </a>
                        </div>
                        <div class="item animations firsting animated flipInY" style="">
                            <a href="/assignment">
                                <img src="https://m16-estate.ru/asset/assets/img/assignmentimg.jpg">
                                <div class="opac"></div>
                                <span class="commspan">Переуступки</span>
                            </a>
                        </div>
                        <div class="item animations seconding animated flipInY" style="">
                            <a href="/resale">
                                <img src="https://m16-estate.ru/asset/assets/img/vtorichka.jpg">
                                <div class="opac"></div>
                                <span class="resalespan">Вторичная</span>
                            </a>
                        </div>
                        <div class="item animations seconding animated flipInY" style="">
                            <a href="/residential">
                                <img src="https://m16-estate.ru/asset/assets/img/residentialimg.jpg">
                                <div class="opac"></div>
                                <span class="residspan">Загородная</span>
                            </a>
                        </div>
                        <div class="item animations seconding margin-fix animated flipInY" style="">
                            <a href="/arenda">
                                <img src="https://m16-estate.ru/asset/assets/img/arendaimg.jpg">
                                <div class="opac"></div>
                                <span class="arendaspan">Аренда</span>
                            </a>
                        </div>
                        <!--<div class="item animations seconding margin-fix animated flipInY" style="">
                            <a href="#">
                                <img src="https://m16-estate.ru/asset/assets/img/otdelka.jpeg">
                                <div class="opac"></div>
                                <span class="worldspan">Дизайн и ремонт</span>
                            </a>
                        </div>-->
                    </div>
                </div>
                <div class="contain">
                    <div class="seotext">
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>');
    echo(' <section id="div3" class="container-fluid block-front-realty_types services" style="background: url(/asset/assets/img/sales/background-grey.png); ">
        <!--Услуги-->
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="wrapTextCenter">
                        <h2>Услуги</h2>
                    </div>
                    <div class="col-xs-12 realty">
                        <div class="item animations firsting animated flipInY" style="">
                            <a href="/sell-appart">
                                <img src="https://m16-estate.ru/asset/assets/img/buildingsimg.jpg">
                                <div class="opac"></div>
                                <span class="buildspan">Продажа квартир</span>
                            </a>
                        </div>
                        <div class="item animations firsting animated flipInY" style="">
                            <a href="/regionalnym-klientam">
                                <img src="https://m16-estate.ru/asset/assets/img/regions-usluga.jpg">
                                <div class="opac"></div>
                                <span class="commspan">Региональным клиентам</span>
                            </a>
                        </div>
                        <div class="item animations firsting animated flipInY" style="">
                            <a href="/military">
                                <img src="https://m16-estate.ru/asset/assets/img/military-usluga.jpg">
                                <div class="opac"></div>
                                <span class="commspan">Военная ипотека</span>
                            </a>
                        </div>
                        <div class="item animations firsting margin-fix animated flipInY" style="">
                            <a href="/excursion">
                                <img src="https://m16-estate.ru/asset/assets/img/bus-usluga.jpg">
                                <div class="opac"></div>
                                <span class="commspan">Бесплатные автобусные экскурсии</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="contain">
                    <div class="seotext">
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </section><div class="clearfix"></div>');
    echo('<section class="container-fluid m16 advantages" id="div4">
        <div class="row">
            <div class="wrapTextCenter">
                <h2>Работайте с М16</h2>
            </div>
            <div class="container">
                <div>
                    <div style="background: url(/asset/assets/img/reputation.png) no-repeat top center/58px; width: 23%;"> 
                        <p>Безупречная репутация компании</p>
                    </div>
                    <div style="background: url(/asset/assets/img/lawyers.png) no-repeat top center/58px; width: 23%;">
                        <p>Собственный юридический отдел, профессиональное сопровождение сделок</p>
                    </div>
                    <div style="background: url(/asset/assets/img/home.png) no-repeat top center/58px; width: 23%;">
                        <p>Квартира в зачет / Flat Trade-In</p>
                    </div>
                    <div style="background: url(/asset/assets/img/sold.png) no-repeat top center/58px; width: 23%;">
                        <p>Короткие сроки продажи Вашей недвижимости</p>
                    </div>
                </div>
                <div>
                    <div style="background: url(/asset/assets/img/cart.png) no-repeat top center/58px; width: 31%;">
                        <p>Принцип &laquo;одного окна&raquo; &mdash; от продажи и покупки недвижимости до оформления дизайн-проекта и ремонта</p>
                    </div>
                    <div style="background: url(/asset/assets/img/head.png) no-repeat top center/43px; width: 31%;">
                        <p>Индивидуальные экскурсии с менеджером по интересующим Вас объектам</p>
                    </div>
                    <div style="background: url(/asset/assets/img/key.png) no-repeat top center/45px; width: 31%;">
                        <p>Предпродажная подготовка: клининг, профессиональная фото- и видеосъемка, 3D-тур, максимальный охват рекламных площадок</p>
                    </div>
                </div>
            </div>
        </div>
    </section>');
    echo ('<section id="div5" class="container-fluid feedback" style="background: url(/asset/assets/img/sales/background-grey.png);">
        <div class="wrapTextCenter">
            <h2>Отзывы наших клиентов</h2>
        </div>
        <div class="responive">
            <div id="preview"></div>
            <div class="wrapSlider">
                            <ul id="lightSlider" class="cS-hidden">
                                <li>
                                    <iframe width="450" height="300" src="https://www.youtube.com/embed/9jVIYRqtd_w" frameborder="0" allowfullscreen=""></iframe>

                                    <p>
                                        <strong>28.07.2017</strong>
                                    </p>
                                    <p>
                                        <strong style="font-size: 15px;">СЕМЬЯ БАРКОВЫХ</strong>
                                    </p>
                                    <p style="font-size: 15px;">«Наша работа состояла в том, чтобы продать комнату в коммунальной квартире. Комната в не очень хорошем состоянии. Работа была выполнена очень быстро, мы очень довольны. Деньги уже в кармане. Сотрудничество было очень приятным, мы получили массу удовольствия при совместной работе. Мы очень довольны и счастливы!<br>
                                            Этого человека (Александра Минакова) мы и хотели поблагодарить. Александр выполнил свою работу на пять с плюсом!»
                                    </p>
                                </li>
                                <li>
                                    <a href="https://m16-estate.ru/asset/uploads/reviews/temp/b56ff8faefa06de75a778d5292f752c4.jpg" class="otzmag">
                                        <img src="https://m16-estate.ru/asset/uploads/reviews/temp/rpreview/b56ff8faefa06de75a778d5292f752c4.jpg">
                                    </a>
                                    <p>
                                        <strong>28.07.2017</strong>
                                    </p>
                                    <p>
                                        <strong style="font-size: 15px;">СЕМЬЯ БАРКОВЫХ</strong>
                                    </p>
                                    <p style="font-size: 15px;">«Наша работа состояла в том, чтобы продать комнату в коммунальной квартире. Комната в не очень хорошем состоянии. Работа была выполнена очень быстро, мы очень довольны. Деньги уже в кармане. Сотрудничество было очень приятным, мы получили массу удовольствия при совместной работе. Мы очень довольны и счастливы!<br>
                                                Этого человека (Александра Минакова) мы и хотели поблагодарить. Александр выполнил свою работу на пять с плюсом!»
                                    </p>
                                </li>
                                <li>
                                    <a href="https://m16-estate.ru/asset/uploads/reviews/temp/9c3c90177e7e58be03846447ed12ebe2.jpeg" class="otzmag">
                                        <img src="https://m16-estate.ru/asset/uploads/reviews/temp/rpreview/9c3c90177e7e58be03846447ed12ebe2.jpeg">
                                    </a>
                                    <p>
                                        <strong>29.12.2018</strong>
                                    </p>
                                    <p>
                                        <strong style="font-size: 15px;">ЕКАТЕРИНА И ГЕОРГИЙ ПЕТРОВЫ</strong>
                                    </p>
                                    <p style="font-size: 15px;">
                                        «Хотим выразить огромную благодарность Макаренко Марине Васильевне за безупречную организацию, с нашей стороны сделки покупки квартиры в Пушкине. Марина представляла сторону продавца. Мы были очень довольны, что Марина подробно вникала, помогала с подготовкой документов и для нашей стороны - покупателя.  Марина оказалась очень чутким, внимательным и не конфликтным человеком и профессионалом своего дела. Сделка была организована и продумана на высоком уровне. Марина  все документы собрала заранее и записала в банк, к нотариусу и МФЦ, что позволило быстро без долгого ожидания провести всю сделку.
                                    </p> 
                                    <p style="font-size: 15px;">Нас забрала комфортная машина от М16 со внимательным водителем, который нас возил и сопровождал куда было необходимо.
                                    </p>
                                    <p style="font-size: 15px;">Спасибо , Вам Марина за четкую и профессиональную работу! Очень было приятно с Вами работать и обязательно будем рекомендовать Вас , как профессионала и Вашу Компанию «М16 Недвижимость», как надежную и проверенную!»
                                    </p>
                                </li>
                                <li>
                                    <a href="https://m16-estate.ru/asset/uploads/reviews/temp/cbeb4173bc4eddb28017c981e464a62a.JPG" class="otzmag">
                                        <img src="https://m16-estate.ru/asset/uploads/reviews/temp/rpreview/cbeb4173bc4eddb28017c981e464a62a.JPG">
                                    </a>
                                    <p>
                                        <strong>12.03.2018</strong>
                                    </p>
                                    <p>
                                        <strong style="font-size: 15px;">СЕРГЕЙ КУДРЯВЦЕВ</strong>
                                    </p>
                                    <p style="font-size: 15px;">
                                        «Нужно было продать двухкомнатную квартиру в Новом Девяткино. В этом деле нам помог риелтор Александр Минаков. Квартира была выставлена в конце сентября, а в январе ипотечная сделка успешно завершилась. Благодарю компанию «М16» и Александра Минакова за внимательную и четкую работу!»
                                    </p>
                                </li>
                                <li>
                                    <a href="https://m16-estate.ru/asset/uploads/reviews/temp/a39bf1a99589dbba554e2e1e80fe6af3.jpeg" class="otzmag">
                                        <img src="https://m16-estate.ru/asset/uploads/reviews/temp/rpreview/a39bf1a99589dbba554e2e1e80fe6af3.jpeg">
                                    </a>
                                    <p>
                                        <strong>08.12.2017</strong>
                                    </p>
                                    <p>
                                        <strong style="font-size: 15px;">ЕЛЕНА</strong>
                                    </p>
                                    <p style="font-size: 15px;">
                                        «Очень довольны работой М16, поэтому опишу всё с начала, думаю, многим будет полезно.
                                                В М16 обратилась к менеджеру Макаренко Марине с просьбой помочь продать квартиру, которую почти три года пыталась продать самостоятельно.</p>
                                    <p style="font-size: 15px;">
                                        Почему М16? Это агентство очень активно работает на рынке недвижимости. Видно это по всем видам рекламы. Да и личности у руководства стоят такие, что не подведут. Не в их стиле.
                                    </p>
                                    <p style="font-size: 15px;">
                                        Почему Марина? А просто посмотрите отзывы клиентов агентства.
                                    </p>
                                    <p style="font-size: 15px;">
                                        Тут выяснилось (и обоснованно), я ошибалась с оценкой стоимости моей квартиры. С рекламой тоже мои возможности оказались весьма ограниченными. А Марина - профессионал в своем деле, плюс энергичная, интеллигентная, с широким кругозором.
                                    </p>
                                    <p style="font-size: 15px;">
                                        И квартиру продали без лишних нервов, пользуясь банковским аккредитивом. На всех этапах чувствовали юридическую поддержку и участие от личного специалиста в деле регистрации документов. Свою работу М16 оценили весьма приемлемо.
                                    </p>
                                    <p style="font-size: 15px;">
                                        Самое интересное было со встречной покупкой. Понравился нам очень непростой вариант с точки зрения надежности. Опять Марина на высоте. Спокойно разобралась, привлекла юридическую службу М16, вышла на специалиста по нашему узкому вопросу. До последнего момента поддерживала морально.
                                    </p>
                                    <p style="font-size: 15px;">
                                        Очень довольна. И дело сделала, и познакомилась с прекрасными людьми.»
                                    </p>
                                </li>
                                <li>
                                    <a href="https://m16-estate.ru/asset/uploads/reviews/temp/299801fc080501f1e5657cfe0f50eb6a.PNG" class="otzmag">
                                        <img src="https://m16-estate.ru/asset/uploads/reviews/temp/rpreview/299801fc080501f1e5657cfe0f50eb6a.PNG">
                                    </a>
                                    <p>
                                        <strong>11.11.2017</strong>
                                    </p>
                                    <p>
                                        <strong style="font-size: 15px;">ЕЛЕНА</strong>
                                    </p>
                                    <p style="font-size: 15px;">
                                        «Благодарю сотрудников М16 и менеджера Ерофееву Марию Александровну за отличную работу, за хорошее отношение,всем советую работать с этой компанией,выражаю благодарность водителям Дмитрию и Константину,за внимание и отношение к своим клиентам!!!»
                                    </p>
                                </li>
                                <li>
                                    <a href="https://m16-estate.ru/asset/uploads/reviews/temp/b18aec05f5819d5ebdc151e0454d8e9d.jpeg" class="otzmag">
                                        <img src="https://m16-estate.ru/asset/uploads/reviews/temp/b18aec05f5819d5ebdc151e0454d8e9d.jpeg">
                                    </a>
                                    <p>
                                        <strong>07.12.2018</strong>
                                    </p>
                                        <p>
                                            <strong style="font-size: 15px;">ЕЛЕНА ЛОМАКИНА</strong>
                                        </p>
                                    <p style="font-size: 15px;">
                                        «Выражаю огромную благодарность сотрудникам вашего агентства Александру Минакову и Екатерине Михайловой за высокий профессионализм, оперативность в работе и личную порядочность при проведении сделки по купле-продаже квартиры.»
                                    </p>
                                </li>
                            </ul>
        </div>
        <div id="next"></div>
    </section><!--Конец блока отзывы-->');
    echo (' <!--Дипломы и сертификаты-->
        <div id="div6" class="diploms" style="padding-top: 81px; margin-top: 0;">
            <div class="contain">
                <div class="one-about-twocols clearfix">
                	  <div class="wrapTextCenter">
                        <h2>Дипломы и сертификаты</h2>
                    </div>
                    <ul class="about-bxslider">
                        <li><a href="/asset/assets/img/serfic1.jpg"><img src="/asset/assets/img/serfi1.jpg" class="grey" /><img src="/asset/assets/img/serfi1.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic2.jpg"><img src="/asset/assets/img/serfi2.jpg" class="grey" /><img src="/asset/assets/img/serfi2.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic3.jpg"><img src="/asset/assets/img/serfi3.jpg" class="grey" /><img src="/asset/assets/img/serfi3.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic4.jpg"><img src="/asset/assets/img/serfi4.jpg" class="grey" /><img src="/asset/assets/img/serfi4.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic5.jpg"><img src="/asset/assets/img/serfi5.jpg" class="grey" /><img src="/asset/assets/img/serfi5.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic6.jpg"><img src="/asset/assets/img/serfi6.jpg" class="grey" /><img src="/asset/assets/img/serfi6.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic7.jpg"><img src="/asset/assets/img/serfi7.jpg" class="grey" /><img src="/asset/assets/img/serfi7.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic8.jpg"><img src="/asset/assets/img/serfi8.jpg" class="grey" /><img src="/asset/assets/img/serfi8.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic9.jpg"><img src="/asset/assets/img/serfi9.jpg" class="grey" /><img src="/asset/assets/img/serfi9.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic10.jpg"><img src="/asset/assets/img/serfi10.jpg" class="grey" /><img src="/asset/assets/img/serfi10.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic11.jpg"><img src="/asset/assets/img/serfi11.jpg" class="grey" /><img src="/asset/assets/img/serfi11.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic12.jpg"><img src="/asset/assets/img/serfi12.jpg" class="grey" /><img src="/asset/assets/img/serfi12.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic13.jpg"><img src="/asset/assets/img/serfi13.jpg" class="grey" /><img src="/asset/assets/img/serfi13.jpg" class="original" /></a></li>
                        <li><a href="/asset/assets/img/serfic14.jpg"><img src="/asset/assets/img/serfi14.jpg" class="grey" /><img src="/asset/assets/img/serfi14.jpg" class="original" /></a></li>
                    </ul>
                </div>
            </div>
        </div>
    <!--Конец блока дипломы и сертификаты-->');
    echo ('<section id="div7" class="block-feedback">
        <!--новости и аналитика-->
        <div class="">
            <div class="image-wrapper anim-otz">
                <div class="contain">
                    <div class="row">
                        <div class="col-lg-5 col-lg-offset-1 news left col-xs-6">
                            <div><h2>Последние новости</h2></div>
                            <div id="ne00" class="item animations animated flipInX" style="">
                                
                            </div>
                        </div>
                        <div class="col-lg-5 news col-xs-6">
                            <div style="border-left: 1px solid #009ce0; margin-left: -15px;"><h2>Статьи и аналитика</h2></div>
                            <div id="ne10" class="item animations animated flipInX" style="">
                                
                            </div>
                        </div>
                        <div class="col-lg-5 col-lg-offset-1 news col-xs-6">
                            <div id="ne01"  class="item animations animated flipInX" style="">
                                
                            </div>
                            <div class="wrapTextCenter">
                                <a href="/news/cat/novosti">Смотреть все новости</a>
                            </div>
                        </div>
                        <div class="col-lg-5 news col-xs-6">
                            <div id="ne11"  class="item animations animated flipInX" style="">
                            
                            </div>
                            <div class="wrapTextCenter">
                                <a href="/news/cat/stati">Смотреть все статьи</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>');
    echo ('<div class="contain" id="div9">
        <!--Описание-->
        <div class="seotext m16">
		        <div class="wrapTextCenter">
		          <h2>Агентство недвижимости «М16»</h2>
		        </div>
            <div class="lftseomain" style="width: 620px;">
                <!--<img src="https://m16-estate.ru/asset/uploads/images/logo-m16.png" alt="Логотип М16-Недвижимость - агентство недвижимости полного цикла" title="">-->
                <!--<img src="https://m16-estate.ru/asset/assets/img/malafeev-main-seo-1.jpg" alt="Логотип М16-Недвижимость - агентство недвижимости полного цикла" title="">-->
                <img src="https://m16-estate.ru/asset/assets/img/malafeev-main.jpg" alt="Вячеслав Малафеев, агентство недвижимости М16" title="" style="width: 338px; float: left; margin-right: 25px;">
                <div class="speach" style="background-color: #F7F7F7; height: 478px;">
                        <p>
                            Привычка быть лидером помогает мне эффективно управлять командой профессионалов и оправдывать оказанное доверие!
                        </p>
                        <p>
                            Отличная ориентация на рынке, безупречный сервис и эксклюзивные услуги, а также постоянное развитие позволяют «М16-Недвижимость» быстро и продуктивно решать задачи наших клиентов.
                        </p>
                            <span class="speach-foot">
                                Учредитель компании
                            </span>
                            <span class="uchredit">
                                вячеслав малафеев
                            </span>
                            <span class="zenitfc speach-foot">
                                Легенда ФК
                            </span>
                </div>
            </div>
            <div class="rghtseo">
                <!--<h2 style="text-align: center; margin-top: 0; color: black">Кто мы</h2>-->
                <p>«М16-Недвижимость» основана вратарем ФК «Зенит» Вячеславом Малафеевым. География работы — Санкт-Петербург&nbsp;и Ленинградская область.</p>
                <p style="text-align: justify;">Мы предлагаем комплексное обслуживание в сфере купли-продажи и аренды жилья и коммерческой недвижимости.</p>
                <h2 style="text-align: center; color: black">Услуги компании</h2>
                <ul>
                    <li style="text-align: justify;">юридическое сопровождение сделок (проверяем юридическую чистоту объекта, контролируем оформление документов);</li>
                    <li style="text-align: justify;">альтернативную продажу по системе trade-in;</li>
                    <li style="text-align: justify;">срочный выкуп недвижимости;</li>
                    <li style="text-align: justify;">апартаменты для иногородних клиентов на время подбора недвижимости и заключения сделки.</li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <!--
    <section class="container block-partner" style="padding-bottom: 250px;">
        <div class="row">
            <div class="col-xs-12">
                <div class="item"><a><img src="https://m16-estate.ru/asset/uploads/images/partners/grey/616f0abc41f0462031b2933925ae88ea.png" alt="Застройщик ЭталонЛенСпецСМУ"></a></div>
                <div class="item"><a><img src="https://m16-estate.ru/asset/uploads/images/partners/grey/de9c4dc727cdc7176f6ad73619c0dcc8.png" alt="Новстройки Pioneer"></a></div>
                <div class="item"><a><img src="https://m16-estate.ru/asset/uploads/images/partners/grey/8e4529f9f0b8c5085c04ce25a0457623.png" alt="Новостройик ЮИТ ДОМ в Санкт-Петербурге"></a></div>
                <div class="item"><a><img src="https://m16-estate.ru/asset/uploads/images/partners/grey/6a2ad76e19abb947d6612837ac89643b.png" alt="Квартиры от застройщика «ПСК»"></a></div>
                <div class="item"><a><img src="https://m16-estate.ru/asset/uploads/images/partners/grey/ef4905b1f1c9068013650143383a55c1.png" alt="Новостройки от надежного застройщика LSR"></a></div>
                <div class="item"><a><img src="https://m16-estate.ru/asset/uploads/images/partners/grey/e488e1997e3a1d8a98da6cd223d5ab3e.png" alt="Квартиры от RBI СПб"></a></div>
            </div>
        </div>
        <div class="clearfix"></div>
    </section> -->');
    echo ('<div id="footer" >
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
                                <li><a href="https://www.facebook.com/m16group" target="_blank" class="fb" rel="nofollow" style="text-decoration: line-through !important;"></a></li>
                                <li><a href="https://vk.com/m16group" target="_blank" class="vk" rel="nofollow" style="text-decoration: line-through !important;"></a></li>
                                <li><a href="https://twitter.com/m16bz" target="_blank" class="tw" rel="nofollow" style="text-decoration: line-through !important;"></a></li>
                                <li><a href="https://ok.ru/group/54832562634771" target="_blank" class="od" rel="nofollow" style="text-decoration: line-through !important;"></a></li>
                                <li><a href="https://www.instagram.com/m16group/" target="_blank" class="in" rel="nofollow" style="text-decoration: line-through !important;"></a></li>
                                <li><a href="https://plus.google.com/+М16Недвижимость" target="_blank" class="gp" rel="nofollow" style="text-decoration: line-through !important;"></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="onecol mcol-4 copyr">
                        <a style="color:#fff;text-decoration:underline;" href="/privacy_policy">Политика конфиденциальности</a>
                    </div>
                </div>
                <div class="mrow offert clearfix">
                    <div class="onecol mcol-8">
                        <!--noindex-->
                        <noindex class="NoIndex_clr_bg_txt_and_img">
                            <p>Настоящий сайт и представленные на нем материалы носят исключительно информационный характер и ни при каких условиях не являются публичной офертой, определяемой положениями Статьи 437 Гражданского кодекса РФ.<br>
                                Мы собираем и храним файлы cookies. Файлы cookies не собирают и не хранят никакую личную информацию о Вас. Используя этот сайт, Вы даёте свое согласие на использование cookies. Чтобы отказаться от использования cookie Вам надо немедленно закрыть наш сайт.<br>
                                Более подробно ознакомиться с информацией об использовании файлов cookies, а также нашей Политикой защиты персональных данных Вы можете <a href="/privacy_policy">здесь</a>.
                            </p>
                        </noindex>
                        <!--/noindex-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>');
}
elseif($_POST['page']=='333'){
    ?>
<div id="content" style="padding-bottom: 0px;">
    <section id="div2" class="container-fluid block-front-realty_types about" style="background: url(/asset/assets/img/sales/background-grey.png); ">
        <!--Чем мы занимакмся-->
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="wrapTextCenter">
                        <h2>Наши направления</h2> </div>
                    <div class="col-xs-10 realty line">
                        <div class="item animations firsting animated flipInY" style="">
                            <a href="/buildings"> <img src="https://m16-estate.ru/asset/assets/img/buildingsimg.jpg">
                                <div class="opac"></div> <span class="buildspan">Новостройки</span> </a>
                        </div>
                        <!--<div class="item animations firsting animated flipInY" style="">
                            <a href="/exclusive">
                                <img src="https://m16-estate.ru/asset/assets/img/resaleimg.jpg">
                                <div class="opac"></div>
                                <span class="commspan">Эксклюзивная</span>
                            </a>
                        </div>-->
                        <div class="item animations firsting animated flipInY" style="">
                            <a href="/commercial"> <img src="https://m16-estate.ru/asset/assets/img/commercialimg.jpg">
                                <div class="opac"></div> <span class="commspan">Коммерческая</span> </a>
                        </div>
                        <div class="item animations firsting animated flipInY" style="">
                            <a href="/assignment"> <img src="https://m16-estate.ru/asset/assets/img/assignmentimg.jpg">
                                <div class="opac"></div> <span class="commspan">Переуступки</span> </a>
                        </div>
                        <div class="item animations seconding animated flipInY" style="">
                            <a href="/resale"> <img src="https://m16-estate.ru/asset/assets/img/vtorichka.jpg">
                                <div class="opac"></div> <span class="resalespan">Вторичная</span> </a>
                        </div>
                        <div class="item animations seconding animated flipInY" style="">
                            <a href="/residential"> <img src="https://m16-estate.ru/asset/assets/img/residentialimg.jpg">
                                <div class="opac"></div> <span class="residspan">Загородная</span> </a>
                        </div>
                        <div class="item animations seconding margin-fix animated flipInY" style="">
                            <a href="/arenda"> <img src="https://m16-estate.ru/asset/assets/img/arendaimg.jpg">
                                <div class="opac"></div> <span class="arendaspan">Аренда</span> </a>
                        </div>
                        <!--<div class="item animations seconding margin-fix animated flipInY" style="">
                            <a href="#">
                                <img src="https://m16-estate.ru/asset/assets/img/otdelka.jpeg">
                                <div class="opac"></div>
                                <span class="worldspan">Дизайн и ремонт</span>
                            </a>
                        </div>--></div>
                </div>
                <div class="contain">
                    <div class="seotext">
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="div3" class="container-fluid block-front-realty_types services" style="background: url(/asset/assets/img/sales/background-grey.png); ">
        <!--Услуги-->
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="wrapTextCenter">
                        <h2>Услуги</h2> </div>
                    <div class="col-xs-12 realty">
                        <div class="item animations firsting animated flipInY" style="">
                            <a href="/sell-appart"> <img src="https://m16-estate.ru/asset/assets/img/buildingsimg.jpg">
                                <div class="opac"></div> <span class="buildspan">Продажа квартир</span> </a>
                        </div>
                        <div class="item animations firsting animated flipInY" style="">
                            <a href="/regionalnym-klientam"> <img src="https://m16-estate.ru/asset/assets/img/regions-usluga.jpg">
                                <div class="opac"></div> <span class="commspan">Региональным клиентам</span> </a>
                        </div>
                        <div class="item animations firsting animated flipInY" style="">
                            <a href="/military"> <img src="https://m16-estate.ru/asset/assets/img/military-usluga.jpg">
                                <div class="opac"></div> <span class="commspan">Военная ипотека</span> </a>
                        </div>
                        <div class="item animations firsting margin-fix animated flipInY" style="">
                            <a href="/excursion"> <img src="https://m16-estate.ru/asset/assets/img/bus-usluga.jpg">
                                <div class="opac"></div> <span class="commspan">Бесплатные автобусные экскурсии</span> </a>
                        </div>
                    </div>
                </div>
                <div class="contain">
                    <div class="seotext">
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="clearfix"></div>
    <section class="container-fluid m16 advantages" id="div4" style="">
        <div class="row">
            <div class="wrapTextCenter">
                <h2>Работайте с М16</h2> </div>
            <div class="container">
                <div class="advantages-line__wrapper">
                    <div style="background: url(/asset/assets/img/reputation.png) no-repeat top center/58px; width: 23%;">
                        <p>Безупречная репутация компании</p>
                    </div>
                    <div style="background: url(/asset/assets/img/lawyers.png) no-repeat top center/58px; width: 23%;">
                        <p>Собственный юридический отдел, профессиональное сопровождение сделок</p>
                    </div>
                    <div style="background: url(/asset/assets/img/home.png) no-repeat top center/58px; width: 23%;">
                        <p>Квартира в зачет / Flat Trade-In</p>
                    </div>
                    <div style="background: url(/asset/assets/img/sold.png) no-repeat top center/58px; width: 23%;">
                        <p>Короткие сроки продажи Вашей недвижимости</p>
                    </div>
                </div>
                <div class="advantages-line__wrapper">
                    <div style="background: url(/asset/assets/img/cart.png) no-repeat top center/58px; width: 31%;">
                        <p>Принцип &laquo;одного окна&raquo; &mdash; от продажи и покупки недвижимости до оформления дизайн-проекта и ремонта</p>
                    </div>
                    <div style="background: url(/asset/assets/img/head.png) no-repeat top center/43px; width: 31%;">
                        <p>Индивидуальные экскурсии с менеджером по интересующим Вас объектам</p>
                    </div>
                    <div style="background: url(/asset/assets/img/key.png) no-repeat top center/45px; width: 31%;">
                        <p>Предпродажная подготовка: клининг, профессиональная фото- и видеосъемка, 3D-тур, максимальный охват рекламных площадок</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="div5" class="container-fluid feedback" style="background: url(/asset/assets/img/sales/background-grey.png); ">
        <div class="wrapTextCenter">
            <h2>Отзывы наших клиентов</h2> </div>
        <div class="responive">
            <div id="preview"></div>
            <div class="wrapSlider">
                <ul id="lightSlider" class="cS-hidden">
                    <li>
                        <iframe width="450" height="300" src="https://www.youtube.com/embed/9jVIYRqtd_w" frameborder="0" allowfullscreen=""></iframe>
                        <p> <strong>28.07.2017</strong> </p>
                        <p> <strong style="font-size: 15px;">СЕМЬЯ БАРКОВЫХ</strong> </p>
                        <p style="font-size: 15px;">«Наша работа состояла в том, чтобы продать комнату в коммунальной квартире. Комната в не очень хорошем состоянии. Работа была выполнена очень быстро, мы очень довольны. Деньги уже в кармане. Сотрудничество было очень приятным, мы получили массу удовольствия при совместной работе. Мы очень довольны и счастливы!
                            <br> Этого человека (Александра Минакова) мы и хотели поблагодарить. Александр выполнил свою работу на пять с плюсом!» </p>
                    </li>
                    <li>
                        <a href="https://m16-estate.ru/asset/uploads/reviews/temp/9c3c90177e7e58be03846447ed12ebe2.jpeg" class="otzmag"> <img src="https://m16-estate.ru/asset/uploads/reviews/temp/rpreview/9c3c90177e7e58be03846447ed12ebe2.jpeg"> </a>
                        <p> <strong>29.12.2018</strong> </p>
                        <p> <strong style="font-size: 15px;">ЕКАТЕРИНА И ГЕОРГИЙ ПЕТРОВЫ</strong> </p>
                        <p style="font-size: 15px;"> «Хотим выразить огромную благодарность Макаренко Марине Васильевне за безупречную организацию, с нашей стороны сделки покупки квартиры в Пушкине. Марина представляла сторону продавца. Мы были очень довольны, что Марина подробно вникала, помогала с подготовкой документов и для нашей стороны - покупателя. Марина оказалась очень чутким, внимательным и не конфликтным человеком и профессионалом своего дела. Сделка была организована и продумана на высоком уровне. Марина все документы собрала заранее и записала в банк, к нотариусу и МФЦ, что позволило быстро без долгого ожидания провести всю сделку. </p>
                        <p style="font-size: 15px;">Нас забрала комфортная машина от М16 со внимательным водителем, который нас возил и сопровождал куда было необходимо. </p>
                        <p style="font-size: 15px;">Спасибо , Вам Марина за четкую и профессиональную работу! Очень было приятно с Вами работать и обязательно будем рекомендовать Вас , как профессионала и Вашу Компанию «М16 Недвижимость», как надежную и проверенную!» </p>
                    </li>
                    <li>
                        <a href="https://m16-estate.ru/asset/uploads/reviews/temp/cbeb4173bc4eddb28017c981e464a62a.JPG" class="otzmag"> <img src="https://m16-estate.ru/asset/uploads/reviews/temp/rpreview/cbeb4173bc4eddb28017c981e464a62a.JPG"> </a>
                        <p> <strong>12.03.2018</strong> </p>
                        <p> <strong style="font-size: 15px;">СЕРГЕЙ КУДРЯВЦЕВ</strong> </p>
                        <p style="font-size: 15px;"> «Нужно было продать двухкомнатную квартиру в Новом Девяткино. В этом деле нам помог риелтор Александр Минаков. Квартира была выставлена в конце сентября, а в январе ипотечная сделка успешно завершилась. Благодарю компанию «М16» и Александра Минакова за внимательную и четкую работу!» </p>
                    </li>
                    <li>
                        <a href="https://m16-estate.ru/asset/uploads/reviews/temp/a39bf1a99589dbba554e2e1e80fe6af3.jpeg" class="otzmag"> <img src="https://m16-estate.ru/asset/uploads/reviews/temp/rpreview/a39bf1a99589dbba554e2e1e80fe6af3.jpeg"> </a>
                        <p> <strong>08.12.2017</strong> </p>
                        <p> <strong style="font-size: 15px;">ЕЛЕНА</strong> </p>
                        <p style="font-size: 15px;"> «Очень довольны работой М16, поэтому опишу всё с начала, думаю, многим будет полезно. В М16 обратилась к менеджеру Макаренко Марине с просьбой помочь продать квартиру, которую почти три года пыталась продать самостоятельно.</p>
                        <p style="font-size: 15px;"> Почему М16? Это агентство очень активно работает на рынке недвижимости. Видно это по всем видам рекламы. Да и личности у руководства стоят такие, что не подведут. Не в их стиле. </p>
                        <p style="font-size: 15px;"> Почему Марина? А просто посмотрите отзывы клиентов агентства. </p>
                        <p style="font-size: 15px;"> Тут выяснилось (и обоснованно), я ошибалась с оценкой стоимости моей квартиры. С рекламой тоже мои возможности оказались весьма ограниченными. А Марина - профессионал в своем деле, плюс энергичная, интеллигентная, с широким кругозором. </p>
                        <p style="font-size: 15px;"> И квартиру продали без лишних нервов, пользуясь банковским аккредитивом. На всех этапах чувствовали юридическую поддержку и участие от личного специалиста в деле регистрации документов. Свою работу М16 оценили весьма приемлемо. </p>
                        <p style="font-size: 15px;"> Самое интересное было со встречной покупкой. Понравился нам очень непростой вариант с точки зрения надежности. Опять Марина на высоте. Спокойно разобралась, привлекла юридическую службу М16, вышла на специалиста по нашему узкому вопросу. До последнего момента поддерживала морально. </p>
                        <p style="font-size: 15px;"> Очень довольна. И дело сделала, и познакомилась с прекрасными людьми.» </p>
                    </li>
                    <li>
                        <a href="https://m16-estate.ru/asset/uploads/reviews/temp/299801fc080501f1e5657cfe0f50eb6a.PNG" class="otzmag"> <img src="https://m16-estate.ru/asset/uploads/reviews/temp/rpreview/299801fc080501f1e5657cfe0f50eb6a.PNG"> </a>
                        <p> <strong>11.11.2017</strong> </p>
                        <p> <strong style="font-size: 15px;">ЕЛЕНА</strong> </p>
                        <p style="font-size: 15px;"> «Благодарю сотрудников М16 и менеджера Ерофееву Марию Александровну за отличную работу, за хорошее отношение,всем советую работать с этой компанией,выражаю благодарность водителям Дмитрию и Константину,за внимание и отношение к своим клиентам!!!» </p>
                    </li>
                    <li>
                        <a href="https://m16-estate.ru/asset/uploads/reviews/temp/b18aec05f5819d5ebdc151e0454d8e9d.jpeg" class="otzmag"> <img src="https://m16-estate.ru/asset/uploads/reviews/temp/b18aec05f5819d5ebdc151e0454d8e9d.jpeg"> </a>
                        <p> <strong>07.12.2018</strong> </p>
                        <p> <strong style="font-size: 15px;">ЕЛЕНА ЛОМАКИНА</strong> </p>
                        <p style="font-size: 15px;"> «Выражаю огромную благодарность сотрудникам вашего агентства Александру Минакову и Екатерине Михайловой за высокий профессионализм, оперативность в работе и личную порядочность при проведении сделки по купле-продаже квартиры.» </p>
                    </li>
                </ul>
            </div>
            <div id="next"></div>
    </section>
    <!--Конец блока отзывы-->
    <div id="div6" class="diploms" style="padding-top: 81px; margin-top: 0; ">
        <div class="contain">
            <div class="one-about-twocols clearfix">
                <div class="wrapTextCenter">
                    <h2>Дипломы и сертификаты</h2> </div>
                <ul class="about-bxslider">
                    <li>
                        <a href="/asset/assets/img/serfic1.jpg"><img src="/asset/assets/img/serfi1.jpg" class="grey" /><img src="/asset/assets/img/serfi1.jpg" class="original" /></a>
                    </li>
                    <li>
                        <a href="/asset/assets/img/serfic2.jpg"><img src="/asset/assets/img/serfi2.jpg" class="grey" /><img src="/asset/assets/img/serfi2.jpg" class="original" /></a>
                    </li>
                    <li>
                        <a href="/asset/assets/img/serfic3.jpg"><img src="/asset/assets/img/serfi3.jpg" class="grey" /><img src="/asset/assets/img/serfi3.jpg" class="original" /></a>
                    </li>
                    <li>
                        <a href="/asset/assets/img/serfic4.jpg"><img src="/asset/assets/img/serfi4.jpg" class="grey" /><img src="/asset/assets/img/serfi4.jpg" class="original" /></a>
                    </li>
                    <li>
                        <a href="/asset/assets/img/serfic5.jpg"><img src="/asset/assets/img/serfi5.jpg" class="grey" /><img src="/asset/assets/img/serfi5.jpg" class="original" /></a>
                    </li>
                    <li>
                        <a href="/asset/assets/img/serfic6.jpg"><img src="/asset/assets/img/serfi6.jpg" class="grey" /><img src="/asset/assets/img/serfi6.jpg" class="original" /></a>
                    </li>
                    <li>
                        <a href="/asset/assets/img/serfic7.jpg"><img src="/asset/assets/img/serfi7.jpg" class="grey" /><img src="/asset/assets/img/serfi7.jpg" class="original" /></a>
                    </li>
                    <li>
                        <a href="/asset/assets/img/serfic8.jpg"><img src="/asset/assets/img/serfi8.jpg" class="grey" /><img src="/asset/assets/img/serfi8.jpg" class="original" /></a>
                    </li>
                    <li>
                        <a href="/asset/assets/img/serfic9.jpg"><img src="/asset/assets/img/serfi9.jpg" class="grey" /><img src="/asset/assets/img/serfi9.jpg" class="original" /></a>
                    </li>
                    <li>
                        <a href="/asset/assets/img/serfic10.jpg"><img src="/asset/assets/img/serfi10.jpg" class="grey" /><img src="/asset/assets/img/serfi10.jpg" class="original" /></a>
                    </li>
                    <li>
                        <a href="/asset/assets/img/serfic11.jpg"><img src="/asset/assets/img/serfi11.jpg" class="grey" /><img src="/asset/assets/img/serfi11.jpg" class="original" /></a>
                    </li>
                    <li>
                        <a href="/asset/assets/img/serfic12.jpg"><img src="/asset/assets/img/serfi12.jpg" class="grey" /><img src="/asset/assets/img/serfi12.jpg" class="original" /></a>
                    </li>
                    <li>
                        <a href="/asset/assets/img/serfic13.jpg"><img src="/asset/assets/img/serfi13.jpg" class="grey" /><img src="/asset/assets/img/serfi13.jpg" class="original" /></a>
                    </li>
                    <li>
                        <a href="/asset/assets/img/serfic14.jpg"><img src="/asset/assets/img/serfi14.jpg" class="grey" /><img src="/asset/assets/img/serfi14.jpg" class="original" /></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!--Конец блока дипломы и сертификаты-->
    <section id="div7" class="block-feedback">
        <!--новости и аналитика-->
        <div class="">
            <div class="image-wrapper anim-otz">
                <div class="contain">
                    <div class="row">
                        <div class="col-lg-5 col-lg-offset-1 news left col-xs-6">
                            <div>
                                <h2>Последние новости</h2></div>
                            <div id="ne00" class="item animations animated flipInX" style=""> </div>
                        </div>
                        <div class="col-lg-5 news col-xs-6">
                            <div style="border-left: 1px solid #009ce0; margin-left: -15px;">
                                <h2>Статьи и аналитика</h2></div>
                            <div id="ne10" class="item animations animated flipInX" style=""> </div>
                        </div>
                        <div class="col-lg-5 col-lg-offset-1 news col-xs-6">
                            <div id="ne01" class="item animations animated flipInX" style=""> </div>
                            <div class="wrapTextCenter"> <a href="/news/cat/novosti">Смотреть все новости</a> </div>
                        </div>
                        <div class="col-lg-5 news col-xs-6">
                            <div id="ne11" class="item animations animated flipInX" style=""> </div>
                            <div class="wrapTextCenter"> <a href="/news/cat/stati">Смотреть все статьи</a> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="mapto" style="" id="novlink">
        <!--картo-->
        <div class="wrapTextCenter">
            <h2>Поиск новостроек по карте</h2> </div>
        <div class="map-buildings" id="nov-map"></div>
    </div>
    <div class="contain" id="div9" style="">
        <!--Описание-->
        <div class="seotext m16">
            <div class="wrapTextCenter">
                <h2>Агентство недвижимости «М16»</h2> </div>
            <div class="lftseomain" style="width: 620px;">
                <!--<img src="https://m16-estate.ru/asset/uploads/images/logo-m16.png" alt="Логотип М16-Недвижимость - агентство недвижимости полного цикла" title="">-->
                <!--<img src="https://m16-estate.ru/asset/assets/img/malafeev-main-seo-1.jpg" alt="Логотип М16-Недвижимость - агентство недвижимости полного цикла" title="">--><img src="https://m16-estate.ru/asset/assets/img/malafeev-main.jpg" alt="Вячеслав Малафеев, агентство недвижимости М16" title="" style="width: 338px; float: left; margin-right: 25px;">
                <div class="speach" style="background-color: #F7F7F7; height: 478px;">
                    <p> Привычка быть лидером помогает мне эффективно управлять командой профессионалов и оправдывать оказанное доверие! </p>
                    <p> Отличная ориентация на рынке, безупречный сервис и эксклюзивные услуги, а также постоянное развитие позволяют «М16-Недвижимость» быстро и продуктивно решать задачи наших клиентов. </p> <span class="speach-foot">
                        Учредитель компании
                    </span> <span class="uchredit">
                        вячеслав малафеев
                    </span> <span class="zenitfc speach-foot">
                        Легенда ФК
                    </span> </div>
            </div>
            <div class="rghtseo">
                <!--<h2 style="text-align: center; margin-top: 0; color: black">Кто мы</h2>-->
                <p>«М16-Недвижимость» основана вратарем ФК «Зенит» Вячеславом Малафеевым. География работы — Санкт-Петербург&nbsp;и Ленинградская область.</p>
                <p style="text-align: justify;">Мы предлагаем комплексное обслуживание в сфере купли-продажи и аренды жилья и коммерческой недвижимости.</p>
                <h2 style="text-align: center; color: black">Услуги компании</h2>
                <ul>
                    <li style="text-align: justify;">юридическое сопровождение сделок (проверяем юридическую чистоту объекта, контролируем оформление документов);</li>
                    <li style="text-align: justify;">альтернативную продажу по системе trade-in;</li>
                    <li style="text-align: justify;">срочный выкуп недвижимости;</li>
                    <li style="text-align: justify;">апартаменты для иногородних клиентов на время подбора недвижимости и заключения сделки.</li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <section id="pfooter" class="container block-partner" style="padding-bottom: 285px; ">
        <div class="row">
            <div class="col-xs-12">
                <div class="item">
                    <a><img src="https://m16-estate.ru/asset/uploads/images/partners/grey/616f0abc41f0462031b2933925ae88ea.png" alt="Застройщик ЭталонЛенСпецСМУ"></a>
                </div>
                <div class="item">
                    <a><img src="https://m16-estate.ru/asset/uploads/images/partners/grey/de9c4dc727cdc7176f6ad73619c0dcc8.png" alt="Новстройки Pioneer"></a>
                </div>
                <div class="item">
                    <a><img src="https://m16-estate.ru/asset/uploads/images/partners/grey/8e4529f9f0b8c5085c04ce25a0457623.png" alt="Новостройик ЮИТ ДОМ в Санкт-Петербурге"></a>
                </div>
                <div class="item">
                    <a><img src="https://m16-estate.ru/asset/uploads/images/partners/grey/6a2ad76e19abb947d6612837ac89643b.png" alt="Квартиры от застройщика «ПСК»"></a>
                </div>
                <div class="item">
                    <a><img src="https://m16-estate.ru/asset/uploads/images/partners/grey/ef4905b1f1c9068013650143383a55c1.png" alt="Новостройки от надежного застройщика LSR"></a>
                </div>
                <div class="item">
                    <a><img src="https://m16-estate.ru/asset/uploads/images/partners/grey/e488e1997e3a1d8a98da6cd223d5ab3e.png" alt="Квартиры от RBI СПб"></a>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </section>
    <div id="footer" style="bottom: -20px;">
        <!--футер-->
        <div class="footer-container">
            <div class="footermap" id="map-contacts"></div>
            <div class="closemap"></div>
            <div class="transparent">
                <div class="contain">
                    <div class="mrow clearfix">
                        <div class="onecol mcol-4">
                            <div class="address" id="show_address_map"> Большая Зеленина, д.<span>18</span> </div>
                        </div>
                        <div class="onecol mcol-4">
                            <div class="phones">
                                <p><a href="tel:+78126059017" class="calltracker">+7 (812) 605-90-17</a></p> &nbsp;<a href="tel:88005505516" class="calltracker">8-800-550-55-16</a> </div> &nbsp;
                            <div class="email"> <a href="mailto:mail@m16.bz">mail@m16.bz</a> </div>
                        </div>
                        <div class="onecol mcol-4 socs">
                            <div class="socials">
                                <ul>
                                    <li>
                                        <a href="https://www.facebook.com/m16group" target="_blank" class="fb" rel="nofollow" style="text-decoration: line-through !important;"></a>
                                    </li>
                                    <li>
                                        <a href="https://vk.com/m16group" target="_blank" class="vk" rel="nofollow" style="text-decoration: line-through !important;"></a>
                                    </li>
                                    <li>
                                        <a href="https://twitter.com/m16bz" target="_blank" class="tw" rel="nofollow" style="text-decoration: line-through !important;"></a>
                                    </li>
                                    <li>
                                        <a href="https://ok.ru/group/54832562634771" target="_blank" class="od" rel="nofollow" style="text-decoration: line-through !important;"></a>
                                    </li>
                                    <li>
                                        <a href="https://www.instagram.com/m16group/" target="_blank" class="in" rel="nofollow" style="text-decoration: line-through !important;"></a>
                                    </li>
                                    <li>
                                        <a href="https://plus.google.com/+М16Недвижимость" target="_blank" class="gp" rel="nofollow" style="text-decoration: line-through !important;"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="onecol mcol-4 copyr"> <a style="color:#fff;text-decoration:underline;" href="/privacy_policy">Политика конфиденциальности</a> </div>
                    </div>
                    <div class="mrow offert clearfix">
                        <div class="onecol mcol-8">
                            <!--noindex-->
                            <noindex class="NoIndex_clr_bg_txt_and_img">
                                <p>Настоящий сайт и представленные на нем материалы носят исключительно информационный характер и ни при каких условиях не являются публичной офертой, определяемой положениями Статьи 437 Гражданского кодекса РФ.
                                    <br> Мы собираем и храним файлы cookies. Файлы cookies не собирают и не хранят никакую личную информацию о Вас. Используя этот сайт, Вы даёте свое согласие на использование cookies. Чтобы отказаться от использования cookie Вам надо немедленно закрыть наш сайт.
                                    <br> Более подробно ознакомиться с информацией об использовании файлов cookies, а также нашей Политикой защиты персональных данных Вы можете <a href="/privacy_policy">здесь</a>. </p>
                            </noindex>
                            <!--/noindex-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?
}
?>