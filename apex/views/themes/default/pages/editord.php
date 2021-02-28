<style>
    .author-head {
        margin: 40px auto;
        padding: 10px;
        display: flex;
        flex-direction: row;
    }
    .ava {
        width: 80px;
        height: 80px;
        border: 2px solid rgb(51, 157, 219);
        border-radius: 150%;
    }
    .header-desc {
        margin: 10px;
        padding: 10px;
        margin-left: 20px;
        margin-top: 0;
        padding-top: 0;
    }
    .author-name {
        margin-bottom: 10px;
        margin-top: 0;
        width: 100%;
        text-align: left;
        font-size: 23px;
        text-transform: uppercase;
        font-family: 'Geometria-Bold';
        color: #000;
        letter-spacing: 1px;
        line-height: 31px;
    }
    .author-occ {
        color: #4a4a4a;
        max-width: 650px;
    }
    .flexable {
        display: flex;
        flex-wrap: wrap;
    }
    .author-body {
        display: flex;
        flex-direction: column;
    }
    .univer-list {
        font-size: 1.3em;
    }
    .list3a {
        padding: 0;
        list-style: none;
        counter-reset: li;
    }
    .list3a li {
        position: relative;
        border-left: 4px solid rgb(51, 157, 219);
        padding: 16px 20px 16px 28px;
        margin: 12px 0 12px 80px;
        -webkit-transition-duration: 0.3s;
        transition-duration: 0.3s;
    }
    .list3a li:before {
        line-height: 32px;
        position: absolute;
        top: 10px;
        left: -80px;
        width: 80px;
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        color: #77AEDB;
        counter-increment: li;
        content: counter(li);
        -webkit-transition-duration: 0.3s;
        transition-duration: 0.3s;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    .list3a li:hover:before {
        color: rgb(51, 157, 219);
    }
    .list3a li:after {
        position: absolute;
        top: 26px;
        left: -40px;
        width: 60px;
        height: 60px;
        border: 8px solid rgb(51, 157, 219);
        border-radius: 50%;
        content: '';
        opacity: 0;
        -webkit-transition: -webkit-transform 0.3s, opacity 0.3s;
        -moz-transition: -moz-transform 0.3s, opacity 0.3s;
        transition: transform 0.3s, opacity 0.3s;
        -webkit-transform: translateX(-50%) translateY(-50%) scale(0.1);
        -moz-transform: translateX(-50%) translateY(-50%) scale(0.1);
        transform: translateX(-50%) translateY(-50%) scale(0.1);
        pointer-events: none;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    .list3a li:hover:after {
        opacity: 0.2;
        -webkit-transform: translateX(-50%) translateY(-50%) scale(1);
        -moz-transform: translateX(-50%) translateY(-50%) scale(1);
        transform: translateX(-50%) translateY(-50%) scale(1);
    }
    .art-title {
        font-style: italic;
    }
    .cont-title {
        font-style: italic;
    }
    hr {
        color: #000;
    }
    .mse {
        background: #f4f4f4;
    }
    .list4a li {
        position: relative;
        border-left: 4px solid rgb(51, 157, 219);
        padding: 0 0 0 8px;
        margin: 12px 0 12px 80px;
        -webkit-transition-duration: 0.3s;
        transition-duration: 0.3s;
    }
    .list4a li:before {
        line-height: 32px;
        position: absolute;
        top: 10px;
        left: -80px;
        width: 80px;
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        color: #77AEDB;
        counter-increment: li;
        content: counter(li);
        -webkit-transition-duration: 0.3s;
        transition-duration: 0.3s;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    .list4a li:after {
        position: absolute;
        top: 26px;
        left: -40px;
        width: 60px;
        height: 60px;
        border: 8px solid rgb(51, 157, 219);
        border-radius: 50%;
        content: '';
        opacity: 0;
        -webkit-transition: -webkit-transform 0.3s, opacity 0.3s;
        -moz-transition: -moz-transform 0.3s, opacity 0.3s;
        transition: transform 0.3s, opacity 0.3s;
        -webkit-transform: translateX(-50%) translateY(-50%) scale(0.1);
        -moz-transform: translateX(-50%) translateY(-50%) scale(0.1);
        transform: translateX(-50%) translateY(-50%) scale(0.1);
        pointer-events: none;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
</style>
<section class="mse">
    <div class="container">
        <div class="flexable">
            <div class="author-head">
                <div class="ava-block">
                    <img class="ava" src="/asset/authors/evtushenko.jpg">
                </div>
                <div class="header-desc">
                    <h1 class="author-name" style="
						margin-bottom: 10px;
						margin-top: 0;
						width: 100%;
						text-align: left;
						font-size: 23px;
						text-transform: uppercase;
						font-family: 'Geometria-Bold';
						color: #000;
						letter-spacing: 1px;
						line-height: 31px;
						">Никита Евтушенко</h1>
                    <div class="author-occ">
                        Lorem ipsum dolor sit amet
                    </div>
                </div>
            </div>
            <hr>
            <div style="
				width: 100%;
				">
                <div style="
					margin: 0 auto;
					max-width: 700px;
					line-height: 150%;
					font-size: 16px;
					">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                        aliqua. Mauris augue neque gravida in. Nunc vel risus commodo viverra maecenas. Accumsan tortor
                        posuere ac ut consequat. Justo donec enim diam vulputate ut. In fermentum posuere urna nec
                        tincidunt praesent semper feugiat nibh. Aenean pharetra magna ac placerat vestibulum.
                        Suspendisse faucibus interdum posuere lorem ipsum dolor sit amet consectetur. Enim nec dui nunc
                        mattis enim ut. Urna condimentum mattis pellentesque id nibh tortor. Tincidunt dui ut ornare
                        lectus sit amet. Nullam vehicula ipsum a arcu cursus vitae congue. Risus nullam eget felis eget
                        nunc lobortis mattis aliquam. Suscipit tellus mauris a diam. At augue eget arcu dictum varius
                        duis at consectetur. Viverra orci sagittis eu volutpat odio.
                    </p>
                </div>
            </div>
            <div class="author-body">
                <hr>
                <div class="art">
                    <h2 class="art-title" style="
						font-family: 'Geometria-Bold';
						font-size: 18px;
						text-transform: uppercase;
						letter-spacing: 1px;
						margin-top: 6px;
						margin-bottom: 30px;
						line-height: 30px;
						">
                        Статьи от этого автора:
                    </h2>
                    <div class="row">
                        <div class="mrow">
                            <div class="onecol mcol-4">
                                <div class="object-item sm_search"
                                     style="border: 4px solid #f3f3f3; margin-bottom:35px;">
                                    <div class="img_fil">
                                        <a href="/news/transportnaja-dostupnost-zhk-novaja-ohta-i-cvetnoj-gorod"><img
                                                    class="filter-result-item-img"
                                                    src="/asset/uploads/images/news/e29964c7b2696c55c73578f9df88e66a.jpg"></a>
                                    </div>
                                    <div style="padding: 15px 10px 0 10px;">
                                        <a style="text-decoration: none;"
                                           href="/news/transportnaja-dostupnost-zhk-novaja-ohta-i-cvetnoj-gorod">
                                            <p
                                                    class="filter-result-item-rayon">Транспортная доступность ЖК «Новая
                                                Охта» и «Цветной город» улучшилась
                                            </p>
                                        </a>
                                        <p class="filter-result-item-name">23.08.2019</p>
                                        <div class="filter-result-item-address">Новый автобусный маршрут свяжет
                                            новостройки с метро «Гражданский проспект».
                                        </div>
                                        <div class="filter-result-item-size" style="margin-top:10px;"><span>Теги:</span>
                                            <a href="/news/tag/новостройки">новостройки</a>, <a
                                                    href="/news/tag/жк новая охта">жк новая охта</a>, <a
                                                    href="/news/tag/жк цветной город">жк цветной город</a>, <a
                                                    href="/news/tag/инфраструктура">инфраструктура</a>, <a
                                                    href="/news/tag/транспорт">транспорт</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="contact">
                    <h2 class="cont-title">
                        Контакты для связи и ссылки на соцсети:
                    </h2>
                    <ul class="univer-list list3a">
                        <li>Телефон: 8-800-555-35-35</li>
                        <li>E-mail: aa@aa.aa</li>
                        <li>Instagram: instagram.com/pisos</li>
                        <li>Terrorgram: instagram.com/pisos</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

