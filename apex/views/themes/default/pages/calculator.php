<div class="main-content bigpad">
<div class="contain clearfix">
     <h1>Калькулятор ипотеки</h1>
</div>


<div id="i-calculator" class="i-calculator container">
        <ul class="nav nav-tabs i-calculator-nav">
            <li class="active"><a href="#by-price" data-toggle="tab">По стоимости недвижимости</a></li>
            <li><a href="#by-cost" data-toggle="tab">По сумме кредита</a></li>
        </ul>

        <div class="tab-content i-calculator-body">
            <div class="tab-pane active" id="by-price">
                <div class="row">
                    <div class="col-md-9">
                        <form role="form" class="i-calculator-form">
                            <div class="form-group slider-form">
                                <label>Стоимость недвижимости</label>
                                <div class="slider-container">
                                    <div class="indicator">
                                        <input class="i-value credit_sum" type="hidden" id="i-price" value="2600000">
                                        <span class="slider-value">2 600 000</span>
                                        <span class="currency">&#8381;</span>
                                    </div>
                                    <div id="price-slider" class="slider money-slider"></div>
                                    <div class="scale">
                                        <label for="">300 тыс.</label>
                                        <label for="">2 млн.</label>
                                        <label for="">5 млн.</label>
                                        <label for="">10 млн.</label>
                                        <label for="">30+ млн.</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group slider-form">
                                <label>Первоначальный взнос</label>
                            </div>

                            <div class="form-group slider-form bottom-0">
                                <label class="radio-inline">
                                    <input name="fp-type" class="fp-type" type="radio" id="by-summ" value="1" checked>
                                    <label for="by-summ">В рублях</label>
                                </label>
                                <label class="radio-inline">
                                    <input name="fp-type" class="fp-type" type="radio" id="by-percent" value="2">
                                    <label for="by-percent">В процентах</label>
                                </label>
                            </div>

                            <div id="by-summ-slider" class="form-group slider-form">

                                <div class="slider-container">
                                    <div class="indicator">
                                        <input class="i-value i-fp-type-val-1" type="hidden" id="i-money-summ" value="600000">
                                        <span class="slider-value">600 000</span>
                                        <span class="currency">&#8381;</span>
                                    </div>
                                    <div id="first-pay" class="slider first-pay-slider" data-start="600000"></div>
                                    <div class="scale">
                                        <label for="">300 тыс.</label>
                                        <label for="">2 млн.</label>
                                        <label for="">5 млн.</label>
                                        <label for="">10 млн.</label>
                                        <label for="">30+ млн.</label>
                                    </div>
                                </div>
                            </div>


                            <div id="by-percent-slider" class="form-group slider-form" style="display: none">
                                <div class="slider-container">
                                    <div class="indicator">
                                        <input class="i-value i-fp-type-val-2" type="hidden" id="i-percent-summ" value="10">
                                        <span class="slider-value">10</span>
                                        <span class="currency">%</span>
                                    </div>
                                    <div class="slider percent-slider"></div>
                                    <div class="scale">
                                        <label for="">0%</label>
                                        <label for="">100%</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group slider-form">
                                <label>Срок кредита</label>
                                <div class="slider-container">
                                    <div class="indicator">
                                        <input class="i-value i-time-val" type="hidden" id="i-time-val" value="24">
                                        <span class="slider-value">24</span>
                                        <span class="currency">года</span>
                                    </div>
                                    <div class="slider time-slider"></div>
                                    <div class="scale">
                                        <label for="">1 год</label>
                                        <label for="">10 лет</label>
                                        <label for="">20 лет</label>
                                        <label for="">30 лет</label>
                                        <label for="">40 лет</label>
                                    </div>
                                </div>
                            </div>

                            <div id="by-percent-slider" class="form-group slider-form">
                                <label>Процентная ставка</label>
                                <div class="slider-container">
                                    <div class="indicator">
                                        <input class="i-value i-percents" type="hidden" id="i-percents" value="10">
                                        <span class="slider-value">10</span>
                                        <span class="currency">%</span>
                                    </div>
                                    <div class="slider percent-slider"></div>
                                    <div class="scale">
                                        <label for="">0%</label>
                                        <label for="">100%</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group slider-form bottom-0">
                                <label>Вид платежа</label>
                                <div class="radio">
                                    <input name="p-type" class="p-type" type="radio" id="p-type1" value="1" checked>
                                    <label for="p-type1">Аннуитетный</label>
                                    <span data-toggle="tooltip" class="calc-tooltip"
                                          title="Аннуитетный платеж – вариант
                                          ежемесячного платежа по кредиту,
                                          когда размер ежемесячного платежа
                                          остаётся постоянным на всём периоде
                                          кредитования." >i</span>
                                </div>
                                <div class="radio">
                                    <input name="p-type" class="p-type" type="radio" id="p-type2" value="2">
                                    <label for="p-type2">Дифференцированный</label>
                                    <span data-toggle="tooltip" class="calc-tooltip"
                                          title="Дифференцированный платеж –
                                          вариант ежемесячного платежа по кредиту,
                                          когда размер ежемесячного платежа по
                                          погашению кредита постепенно уменьшается
                                          к концу периода кредитования." >i</span>
                                </div>
                            </div>
                            <div>
                                <input id="calculateByPice" type="submit" class="submit-calc" value="Рассчитать"
                                style="margin: 0">
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3">
                        <div class="i-out">
                            <label class="emphasize">Предварительный расчёт</label>
                            <div class="emphasize i-out-item">
                                <label for="">Сумма кредита</label>
                                <div class="i-out-content i-out-sum">
                                    <span class="value">2 760 000</span>
                                    <span class="currency">&#8381;</span>
                                </div>
                            </div>
                            <div class="emphasize i-out-item">
                                <label for="">Ежемесячный платеж</label>
                                <div class="i-out-content i-out-month-pay ">
                                    <span class="value">134 678</span>
                                    <span class="currency">&#8381;</span>
                                </div>
                            </div>
                            <div class="emphasize i-out-item">
                                <label for="">Переплата по кредиту</label>
                                <div class="i-out-content i-out-overpayments ">
                                    <span class="value">23 345</span>
                                    <span class="currency">&#8381;</span>
                                </div>
                            </div>
                            <div class="emphasize i-out-item">
                                <label for="">Процентная ставка</label>
                                <div class="i-out-content i-out-percents">
                                    <span class="value">4</span>
                                    <span class="currency">%</span>
                                    <span data-toggle="tooltip" class="calc-tooltip"
                                          title="Устанавливается банком и зависит от банковской программы ипотеки." >i</span>
                                </div>
                            </div>
                            <p class="text-muted"><i>* Расчет параметров кредита  является предварительным</i></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="by-cost">
                <div class="row">
                    <div class="col-md-9">
                        <form role="form" class="i-calculator-form">
                            <div class="form-group slider-form">
                                <label>Сумма кредита</label>
                                <div class="slider-container">
                                    <div class="indicator">
                                        <input class="i-value credit_sum" type="hidden" id="i-price" value="2600000">
                                        <span class="slider-value">2 600 000</span>
                                        <span class="currency">&#8381;</span>
                                    </div>
                                    <div id="price-slider" class="slider money-slider"></div>
                                    <div class="scale">
                                        <label for="">300 тыс.</label>
                                        <label for="">2 млн.</label>
                                        <label for="">5 млн.</label>
                                        <label for="">10 млн.</label>
                                        <label for="">30+ млн.</label>
                                    </div>
                                </div>
                            </div>


                            <div id="by-percent-slider" class="form-group slider-form" style="display: none">
                                <div class="slider-container">
                                    <div class="indicator">
                                        <input class="i-value i-fp-type-val-2" type="hidden" id="i-percent-summ" value="10">
                                        <span class="slider-value">10</span>
                                        <span class="currency">%</span>
                                    </div>
                                    <div class="slider percent-slider"></div>
                                    <div class="scale">
                                        <label for="">0%</label>
                                        <label for="">100%</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group slider-form">
                                <label>Срок кредита</label>
                                <div class="slider-container">
                                    <div class="indicator">
                                        <input class="i-value i-time-val" type="hidden" id="i-time-val" value="24">
                                        <span class="slider-value">24</span>
                                        <span class="currency">года</span>
                                    </div>
                                    <div class="slider time-slider"></div>
                                    <div class="scale">
                                        <label for="">1 год</label>
                                        <label for="">10 лет</label>
                                        <label for="">20 лет</label>
                                        <label for="">30 лет</label>
                                        <label for="">40 лет</label>
                                    </div>
                                </div>
                            </div>

                            <div id="by-percent-slider" class="form-group slider-form">
                                <label>Процентная ставка</label>
                                <div class="slider-container">
                                    <div class="indicator">
                                        <input class="i-value i-percents" type="hidden" id="i-percents" value="10">
                                        <span class="slider-value">10</span>
                                        <span class="currency">%</span>
                                    </div>
                                    <div class="slider percent-slider"></div>
                                    <div class="scale">
                                        <label for="">0%</label>
                                        <label for="">100%</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group slider-form bottom-0">
                                <label>Вид платежа</label>
                                <div class="radio">
                                    <input name="p-type" class="p-type" type="radio" id="p-cost1" value="1" checked>
                                    <label for="p-cost1">Аннуитетный</label>
                                    <span data-toggle="tooltip" class="calc-tooltip"
                                          title="Аннуитетный платеж – вариант
                                          ежемесячного платежа по кредиту,
                                          когда размер ежемесячного платежа
                                          остаётся постоянным на всём периоде
                                          кредитования." >i</span>
                                </div>
                                <div class="radio">
                                    <input name="p-type" class="p-type" type="radio" id="p-cost2" value="2">
                                    <label for="p-cost2">Дифференцированный</label>
                                    <span data-toggle="tooltip" class="calc-tooltip"
                                          title="Дифференцированный платеж –
                                          вариант ежемесячного платежа по кредиту,
                                          когда размер ежемесячного платежа по
                                          погашению кредита постепенно уменьшается
                                          к концу периода кредитования." >i</span>
                                </div>
                            </div>
                            <div>
                                <input id="calculateByPice" type="submit" class="submit-calc" value="Рассчитать"
                                       style="margin: 0">
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3">
                        <div class="i-out">
                            <label class="emphasize">Предварительный расчёт</label>
                            <div class="emphasize i-out-item">
                                <label for="">Сумма кредита</label>
                                <div class="i-out-content i-out-sum">
                                    <span class="value">2 760 000</span>
                                    <span class="currency">&#8381;</span>
                                </div>
                            </div>
                            <div class="emphasize i-out-item">
                                <label for="">Ежемесячный платеж</label>
                                <div class="i-out-content i-out-month-pay ">
                                    <span class="value">134 678</span>
                                    <span class="currency">&#8381;</span>
                                </div>
                            </div>
                            <div class="emphasize i-out-item">
                                <label for="">Переплата по кредиту</label>
                                <div class="i-out-content i-out-overpayments ">
                                    <span class="value">23 345</span>
                                    <span class="currency">&#8381;</span>
                                </div>
                            </div>
                            <div class="emphasize i-out-item">
                                <label for="">Процентная ставка</label>
                                <div class="i-out-content i-out-percents">
                                    <span class="value">4</span>
                                    <span class="currency">%</span>
                                    <span data-toggle="tooltip" class="calc-tooltip"
                                          title="Устанавливается банком и зависит от банковской программы ипотеки." >i</span>
                                </div>
                            </div>
                            <p class="text-muted"><i>* Расчет параметров кредита  является предварительным</i></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <?php js('asset/js/ipoteka.js', false);?>



<!--
<div class="contain clearfix">
    <div class="left-parth-calc">
        <h2>Ипотечный калькулятор</h2>
        <form id="calc3">
            <div class="calcs">
                <div class="mrow">
                    <label>Сумма кредита</label>
                    <input type="text" name="c1" class="input-bron" placeholder="">
                    <span>руб</span>
                </div>
                <div class="mrow">
                    <label>Годовая процентная ставка</label>
                    <input type="text" name="c3" class="input-bron" placeholder="">
                    <span>%</span>
                </div>
                <div class="mrow">
                    <label>Период погашения кредита</label>
                    <input type="text" name="c4" class="input-bron" placeholder="">
                    <span>мес.</span>
                </div>
            </div>
            <button type="button" class="submit-calc" onclick="calc3()">рассчитать</button><br><br>
            <table class="raschet">
            </table>
            <div class="total">
                <span class="title-price">Общая сумма</span><span class="summ-total"></span>
            </div>
        </form>
    </div>
    <div class="right-parth-calc">
        <h2>Инвестиционный калькулятор</h2>
        <form id="calc2">
            <div class="calcs">
                <div class="mrow">
                    <label>Стоимость объекта</label>
                    <input type="text" name="c1" class="input-bron" placeholder="">
                    <span>руб</span>
                </div>
                <div class="mrow">
                    <label>Ежемесячный арендный доход</label>
                    <input type="text" name="c2" class="input-bron" placeholder="">
                    <span>руб</span>
                </div>
                <div class="mrow">
                    <label class="tworows">Ежегодная индексация арендного дохода</label>
                    <input type="text" name="c3" class="input-bron" placeholder="">
                    <span>%</span>
                </div>
            </div>
                <button type="button" class="submit-calc" onclick="calc()">рассчитать</button>

                <div class="okup"></div>

        </form>
    </div>-->
<br>
<br>
<div class="contain clearfix" style="width:900px;">
<p>Мы предлагаем воспользоваться специальным ипотечным калькулятором, чтобы рассчитать онлайн размер ежемесячного платежа, график и итоговую сумму кредита.
Полученный результат поможет вам выбрать оптимальный банк и самую удобную программу, а также распланировать график платежей.</p>

<!--<div align="center"><img alt="Кредитный калькулятор | М16-недвижимость - продажа квартир по выгодным ценам" title="Кредитный калькулятор | М16-недвижимость - продажа квартир по выгодным ценам" src="/asset/assets/img/ipoteka_calculator.jpg" style="width: 600px; height: 400px;"></div>
<br />-->
<h2>Что нужно при работе с кредитным калькулятором ипотеки?</h2>

<p>Чтобы посчитать ипотеку, заполните все графы калькулятора: стоимость квартиры (предварительно вычтете сумму первоначального платежа), ставку, по которой банк выдает средства, и срок, в течение которого вы рассчитываете вернуть заем, в месячном эквиваленте. </p>

<p>Подчеркиваем, что данный калькулятор дает примерные расчеты по ипотеке, банки СПб могут взимать дополнительную комиссию, что скажется на сумме кредита. Узнать окончательный результат вам помогут ипотечные менеджеры агентства недвижимости «М16».</p>
<p>
</p>
<br>
    <div style="text-align: center;">
        <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
        <script src="//yastatic.net/share2/share.js"></script>
        <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,viber,telegram"></div>
    </div>
</div>

</div>

<script>
function calc() {
    var c1 = $('form#calc2 input[name=c1]').val();
    var c2 = $('form#calc2 input[name=c2]').val();
    var c3 = $('form#calc2 input[name=c3]').val();
    var c6 = $('form#calc2 input[name=c6]');
    var c7 = $('form#calc2 input[name=c7]');

    $.ajax({
        type: 'POST',
        url: '/ajax/ajax_calc',
        dataType: 'json',
        data: {
            c1: c1,
            c2: c2,
            c3: c3
        },
        success: function(data) {
            $('.okup').html('Срок окупаемости '+data.year+' '+data.dohod);
            //c7.val(data.year);
            //c6.val(data.dohod);
        }
    });
}

function calc2() {
    var c1 = $('form#calc input[name=c1]').val();
    var c2 = $('form#calc input[name=c2]').val();
    var c3 = $('form#calc input[name=c3]').val();
    var c4 = $('form#calc input[name=c4]').val();
    var c5 = $('form#calc input[name=c5]');
    var c6 = $('form#calc input[name=c6]');
    var c7 = $('form#calc input[name=c7]');
    var c8 = $('form#calc input[name=c8]');

    $.ajax({
        type: 'POST',
        url: '/ajax/ajax_calc2',
        dataType: 'json',
        data: {
            c1: c1,
            c2: c2,
            c3: c3,
            c4: c4
        },
        success: function(data) {
            c5.val(data.suma);
            c6.val(data.platezh);
            c7.val(data.pkr);
            c8.val(data.s);
        }
    });
}

function calc3() {
    var c1 = $('form#calc3 input[name=c1]').val();
    var c2 = $('form#calc3 input[name=c2]').val();
    var c3 = $('form#calc3 input[name=c3]').val();
    var c4 = $('form#calc3 input[name=c4]').val();
    var c5 = $('form#calc3 input[name=c5]');
    var c6 = $('form#calc3 input[name=c6]');
    var c7 = $('form#calc3 input[name=c7]');
    var c8 = $('form#calc3 input[name=c8]');

    $.ajax({
        type: 'POST',
        url: '/ajax/ajax_calc_anuit',
        dataType: 'json',
        data: {
            c1: c1,
            c2: c2,
            c3: c3,
            c4: c4
        },
        success: function(data) {
            $(".raschet").empty();
            $(".raschet").append("<thead><tr><td class='firstt'>Месяц</td><td class='secondt'>Остаток ссудной задолженности</td><td>Проценты</td><td>Ссудная задолженность</td><td>Платеж</td></tr></thead>");
            $.each(data.items, function(i, val){
                $(".raschet").append("<tr><td class='firstt'>"+val.month+"</td><td class='secondt'>"+val.dolg+"</td><td>"+val.proc+"</td><td>"+val.ssud+"</td><td>"+val.formonth+"</td></tr>");
            });

            $(".summ-total").text(data.total+' руб.');
            if (data.total)
                $('.total').show();
        }
    });
}
</script>
