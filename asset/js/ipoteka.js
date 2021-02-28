$(function() {
    var icalc = $('.i-calculator')
        , calcForm = icalc.find('form')
        , priseSlider = $('#price-slider')
    ;

    $('.money-slider').slider({
        range: "min",
        min: 0,
        max: 1000,
        value: 301,
        change: function (a, b) { },
        create: function (a, b) { },
        stop: function (event, ui)
        {
            var result = iCalculate(ui.value);
            var slider = $(ui.handle).parent('div.money-slider');
            var cont = slider.parent('div.slider-container');
            var inp = $(cont).find('input.i-value').val(result);
        },
        slide: function (event, ui)
        {
            var result = iCalculate(ui.value);
            var slider = $(ui.handle).parent('div.money-slider');
            var cont = slider.parent('div.slider-container');
            var chi = $(cont).find('span.slider-value').text(moneyFormat(result));
        }
    });

    $('.first-pay-slider').slider({
        range: "min",
        min: 0,
        max: 1000,
        value: 44,
        change: function (a, b) { },
        create: function (a, b) { },
        stop: function (event, ui)
        {
            var result = iCalculate(ui.value);
            var slider = $(ui.handle).parent('div.first-pay-slider');
            var cont = slider.parent('div.slider-container');
            var inp = $(cont).find('input.i-value').val(result);
        },
        slide: function (event, ui)
        {
            var result = iCalculate(ui.value);
            var slider = $(ui.handle).parent('div.first-pay-slider');
            var cont = slider.parent('div.slider-container');
            var chi = $(cont).find('span.slider-value').text(moneyFormat(result));
        }
    });

    $('input[name=fp-type]').change(function () {
        var val = $(this).val();
        var bySumm = $('#by-summ-slider');
        var byPercent = $('#by-percent-slider');
        if (val == 1) {
            bySumm.show();
            byPercent.hide();
        }
        if (val == 2) {
            bySumm.hide();
            byPercent.show();
        }
    });


    $('.percent-slider').slider({
        range: "min",
        min: 0,
        max: 100,
        value: 10,
        change: function (a, b) { },
        create: function (a, b) { },
        stop: function (event, ui)
        {
            var slider = $(ui.handle).parent('div.percent-slider');
            var cont = slider.parent('div.slider-container');
            var inp = $(cont).find('input.i-value').val(ui.value);
        },
        slide: function (event, ui)
        {
            var slider = $(ui.handle).parent('div.percent-slider');
            var cont = slider.parent('div.slider-container');
            var chi = $(cont).find('span.slider-value').text(ui.value);
        }
    });

    $('.time-slider').slider({
        range: "min",
        min: 1,
        max: 40,
        value: 24,
        change: function (a, b) { },
        create: function (a, b) { },
        stop: function (event, ui)
        {
            var slider = $(ui.handle).parent('div.time-slider');
            var cont = slider.parent('div.slider-container');
            var inp = $(cont).find('input.i-value').val(ui.value);
        },
        slide: function (event, ui)
        {
            var slider = $(ui.handle).parent('div.time-slider');
            var cont = slider.parent('div.slider-container');
            var chi = $(cont).find('span.slider-value').text(ui.value);
            var currency = cont.find('.currency');
            var cur = '';
            if (ui.value == 1 || ui.value == 21 || ui.value == 31) {
                cur = 'год';
            } else if ((ui.value >= 2 && ui.value <=4) || (ui.value >= 22 && ui.value <=24)
            || (ui.value >= 32 && ui.value <=34)) {
                cur = 'года';
            }  else if ((ui.value >= 5 && ui.value <=20) || (ui.value >= 25 && ui.value <=30)
            || (ui.value >= 35 && ui.value <=40)) {
                cur = 'лет';
            }
            currency.text(cur);
        }
    });

    $('[data-toggle="tooltip"]').tooltip();


    var byPriceBtn = $('input#calculateByPice');

    //byPriceBtn.click();

    var active_c = $('.i-calculator-body').find('.tab-pane.active').find('input#calculateByPice');
    calcInit(active_c);


    function calcInit(btn) {

        if (btn.length == 0) return;

        var form = $(btn).parents('.i-calculator-form');
        var curTab = form.parents('.tab-pane.active');
        var out = curTab.find('.i-out');

        var type = Number.parseInt(form.find('input.p-type:checked').val()),
            period = form.find('.i-time-val').val() * 12,
            summStr = form.find('.credit_sum').val(),
            summ = Number(summStr),
            pers = Number.parseInt(form.find('.i-percents').val()),
            i = pers / (12*100),
            pp = 0, medPaysum = 0,
            start_sum_type = Number.parseInt(form.find('input.fp-type:checked').val());

        var start_sum = form.find('.i-fp-type-val-'+start_sum_type).val();

        if (!isNaN(start_sum) && !isNaN(start_sum_type)) {
            switch (start_sum_type) {
                case '1':
                    start_sum = Number.parseInt(start_sum);
                    break;
                case '2':
                    start_sum = Math.round(summ * start_sum_input / 100);
                    break;
            }
        } else {
            start_sum = 0;
        }


        if (start_sum > summ) {
            out.find('.i-out-sum>.value').text(moneyFormat(0));
            out.find('.i-out-month-pay>.value').text(0);
            out.find('.i-out-overpayments>.value').text(0);
            out.find('.i-out-percents>.value').text(pers);
            return;
        }
        summ = Math.round(summ - start_sum);

        var res = calculateCredit(type, period, summ, pers);


        out.find('.i-out-sum>.value').text(moneyFormat(summ));
        out.find('.i-out-month-pay>.value').text(res.payments);
        out.find('.i-out-overpayments>.value').text(res.overpayments);
        out.find('.i-out-percents>.value').text(pers);
    }

    function calculateCredit(type, period, summ, pers)
    {
        var i = pers / (12*100),
            pp = 0, medPaysum = 0;

        var result = {
            'overpayments': '',
            'payments': ''
        };

        if (type === 1) {

            var ii = Math.pow((1 + i), period);
            var monthPay = summ * (i * ii) / (ii - 1); // платеж
            monthPay = Math.round(monthPay);
            var ps = monthPay * period;
            var s = Math.round(ps - summ);
            result.overpayments = moneyFormat(s);
            result.payments = moneyFormat(monthPay);

        } else if (type === 2) {

            var medPaysum = summ / period;
            var pays = [];
            for (var k = 0; k < period; k++) {
                var dd = (summ - (medPaysum * k))*i;
                var monthPay = medPaysum + dd;
                pays.push(monthPay);
            }
            ps = 0;
            for (var j = 0; j < pays.length; j++) {
                ps +=  pays[j];
            }
            var fn = Math.round(pays[0]);
            var ln = Math.round(pays[pays.length-1]);

            monthPay = moneyFormat(fn) + '...' + moneyFormat(ln);
            var s = Math.round(ps - summ);
            result.overpayments = moneyFormat(s);
            result.payments = monthPay;
        }

        return result;
    }

    byPriceBtn.on('click', function (e) {
        e.preventDefault();
        calcInit($(this));
    });

});

function sliderOperate(event, ui)
{
    var result = iCalculate(ui.value);
    var slider = $(ui.handle).parent('div#price-slider');
    var cont = slider.parent('div.slider-container');
    var chi = $(cont).find('span.slider-value').text(result);
}

function moneyFormat(s)
{
    s = s + "";
    return s.replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
}

function iCalculate(val)
{
    var result = 0;
    var scV = 0;
    if (val <= 250) {
        scV = 1700000/250;
        result = Math.round((val*scV) + 300000);
    }
    else if (val > 250 && val <= 500) {
        scV = 3000000/250;
        result = Math.round((val*scV) - 1000000);
    }
    else if (val > 500 && val <= 750) {
        scV = 5000000/250;
        result = Math.round((val*scV) - 5000000);
    }
    else if (val > 750 && val <= 1000) {
        scV = 20000000/250;
        result = Math.round((val*scV) - 50000000);
    }
    if (result === 2600000) {
        console.log(val);
    }
    return result;
}

function scalable(val)
{
    return val * 1000;
}
