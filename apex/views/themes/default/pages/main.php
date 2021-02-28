<?php $this->load->module('buildings')->getFilterBuildings(); ?>

<div class="main-content nopadd">
    <div class="contain">
        <a href="/sell-appart" class="comission inset">
            Хотите <span>продать</span> квартиру?
        </a>
    </div>
</div>
<div class="main-content">

    <div class="contain">

        <div class="mrow clearfix">

            <div class="onecol mcol-4">
                <div class="main-speach">
                    <a href="/o-kompanii"><img src="/asset/assets/img/malafeev-main.jpg" alt="Вячеслав Малафеев, агентство недвижимости М16" /></a>
                    <div class="speach">
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
            </div>

                <?php $this->load->module('interest')->showMain(); ?>

        </div>
    </div>
    <?php $this->load->module('special')->showMain(); ?>
</div>

<div class="clearfix"></div>

<?php $k = "[main-seo]"; ?>
<?php if(str_word_count($k) > 0): ?>
<div class="contain">
    <div class="seotext">
    <?php echo $k; ?>
        <div class="clearfix"></div>
    </div>
</div>

<?php endif; ?>

