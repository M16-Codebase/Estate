<link type="text/css" rel="stylesheet" href="/asset/assets/css/region.css" />

<?php if(empty($military_page)): ?>
<?php $this->load->module('buildings')->getFilterBuildings(); ?>
<?php else: ?>
    <style>
        .main-content.nopadd {display: none !important;}
        #consec,
        .container-fluid.fil-result-nov.main-content {margin-bottom: 0 !important; padding-bottom: 0 !important}
    </style>
<?php endif; ?>

<div class="main-content nopadd">
    <div class="contain">
        <a href="/sell-appart" class="comission">
            Хотите <span>продать</span> квартиру?
        </a>
    </div>
</div>

<?php if(empty($commerce_page)): ?>
<section class="container-fluid fil-result-nov main-content">
    <div class="container">
        <div class="row clearfix">

            <?php if(empty($text_top)): ?>
                <h2><?php echo $lang->md_header ?></h2>
            <?php endif; ?>

            <?php if(!empty($text_top)): ?>
                <?php echo $text_top ?>
            <?php endif; ?>

        </div>
    </div>
</section>
<?php endif; ?>

<?php if(!empty($military_page)): ?>
<section class="container-fluid fil-result-nov main-content">
    <?php echo $filters ?>
</section>

    <?php if(empty($commerce_page)): ?>
    <div class="main-content" style="padding-bottom: 0;">
        <div class="contain">
            <div class="one-about-twocols clearfix" id="consotr" style="margin-top: 0;">
                <h2 style="text-align: center;">Новостройки по военной ипотеке</h2>
                <p>
                  В Петербурге больше 40 новостроек, аккредитованных по программе военной ипотеки. Среди этих новостроек есть как небольшие, камерные проекты, так и масштабные жилые комплексы, занимающие целые жилые кварталы и микрорайоны.
                </p>

                <p>
                  «М16-Недвижимость» предлагает квартиры в десятках жилых комплексов, участвующих в программе военной ипотеки: от новостроек массового сегмента до комплексов бизнес-класса.
                </p>



             </div>
        </div>
    </div>
    <?php endif; ?>

        <?php if(!empty($commerce_page)): ?>
    <div class="main-content" style="padding-bottom: 0;">
        <div class="contain">
            <div class="one-about-twocols clearfix" id="consotr" style="margin-top: 0;">

                <?php echo shortcodes_db('commerce_text'); ?>

             </div>
        </div>
    </div>
    <?php endif; ?>
<?php endif; ?>

<section class="container-fluid fil-result-nov main-content">
    <div class="container">
        <div class="row clearfix">

            <div class="filter-result clearfix">

                <div class="contain">
                    <div class="mrow clearfix">

                        <?php if(!empty($rows)): ?>

                        <?php $k = 1; ?>
                        <?php foreach ($rows as $r):?>
                            <div class="onecol mcol-4<?php if($k == 4):?> nomargin<?php endif;?>">
                                <a href="<?=$r->link?>" class="object-item sm_search" data-category="SelectObject" data-label="buildings">
                                    <div class="img_fil">
                                        <img class="filter-result-item-img" src="<?php echo image($r->foto, "small"); ?>" alt="Life">
                                        <span class="signcat <?=$r->sign['class']?>"><?=$r->sign['name']?></span>
                                        <div class="labels">
                                            <?php
                                            if (!empty($r->mortgage))    echo '<span class="mortgage">Ипотека</span>';
                                            if (!empty($r->fz214))       echo '<span class="fz214">ФЗ-214</span>';
                                            if (!empty($r->parking))     echo '<span class="parking">Паркинг</span>';
                                            if (!empty($r->rassrochka))  echo '<span class="installments">Рассрочка</span>';
                                            if (!empty($r->otdelka))     echo '<span class="finishing">Отделка</span>';
                                            ?>
                                        </div>
                                    </div>
                                    <div class="priceres"><?=$r->price?></div>
                                    <div class="filter-result-item-body">
                                        <p class="filter-result-item-rayon"><?=$r->rayon?></p>
                                        <p class="filter-result-item-name"><?=$r->name?></p>
                                        <?php if(isset($r->deadline)):?><p class="filter-result-item-size"><?=$r->deadline?></p><?php endif;?>
                                        <div class="addbott">
                                            <div class="filter-result-item-address"><?=$r->adress?>
                                                <?php if (!empty($r->metro)):?><span class="filter-result-item-metro"><span><?=$r->metro?></span><?php endif; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="viewob">просмотр объекта</div>
                                </a>
                            </div>
                            <?php $k++; if($k > 4) { $k = 1; } ?>
                        <?php endforeach;?>

                        <?php else: ?>

                            <h3>ничего не найдено</h3>

                        <?php endif; ?>

                    </div>
                </div>

                <div class="filter-result-paging sm_selectpage" data-category="SelectPage" data-label="buildings">
                    <?php echo $pagination ?>
                    <?php if(uri(2) != 999 && !empty($pagination)): ?>
                        <br><br><a href="/<?php echo uri(1); ?>/999" rel="nofollow" class="filter-result-paging-link sm_search" data-category="ShowAll" data-label="buildings" style="margin-top: 10px; font-size: 13px; margin-left: -14px;">Показать все</a>
                    <?php endif; ?>
                </div>
            </div>



                    <?php if(!empty($commerce_page)): ?>
    <div class="main-content" style="padding-bottom: 0;">
        <div class="contain">
        <div class="seotext" style="margin-top: 0; margin-bottom: 40px;">

                <?php echo shortcodes_db('commerce_text2'); ?>

             </div>
        </div>
    </div>
    <?php endif; ?>

			<?php if(!empty($text_bottom)): ?>
			<?php echo $text_bottom ?>

            <div class="main-content">
<div class="contain clearfix">
    <div class="left-parth-calc" style="float: none; width: auto; text-align: center;">
        <h2>Калькулятор военной ипотеки</h2>
        <p>Если вам нужно рассчитать кредит, воспользуйтесь нашим калькулятором военной ипотеки</p>
        <form id="calc3">
            <div class="calcs" style="margin: 0 auto;">
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
            <button type="button" class="submit-calc" style="margin-top: 20px;" onclick="calc3()">рассчитать</button><br><br>
            <table class="raschet">
            </table>
            <div class="total">
                <span class="title-price">Общая сумма</span><span class="summ-total"></span>
            </div>
        </form>
    </div>

</div>

<div class="main-content" style="padding-bottom: 0;">


            <div class="contain">
        <div class="seotext">

<div class="lftseo">
<h2 style="text-align: center; color: #000; font-size: 20px;">Суть военной ипотеки</h2>

<p style="text-align: justify;">Если вы – военнослужащий и проходите службу по контракту, то вам полагается недвижимость при поддержке государства. Такая система покупки жилья получила название накопительно-ипотечная система или военная ипотека.</p>

<p style="text-align: justify;">Чтобы купить квартиру по военной ипотеке, вам нужно быть участником накопительно-ипотечной системы. Это дает вам право получать военные накопления от государства. Затем вы подаете рапорт о получении целевого жилищного займа (ЦЖЗ) и заявку в банк, попутно выбирая квартиру. В случае одобрения вы заключаете с банком договор и покупаете квартиру.</p>

<p style="text-align: justify;">Кажется, все просто? Однако на деле процесс оформления ипотеки для военных довольно трудоемкий. Вам придется отпрашиваться со службы, ввязываться в бюрократические проволочки и долго ждать ответа от военных ведомств. А в случае неправильного оформления документов в предоставлении кредита и вовсе могут отказать.</p>

</div>

<div class="rghtseo">
<h2 style="text-align: center; color: #000; font-size: 20px;">Военная ипотека от «М16»!</h2>

<p style="text-align: justify;">Военная ипотека – это отличная возможность приобрести недвижимость в Северной столице на выгодных условиях.</p>

<p style="text-align: justify;">Доверьте покупку нового жилья профессионалам из «М16-недвижимость»! Мы работаем с крупнейшими застройщиками Северной столицы, среди которых – несколько десятков компаний, аккредитованных по программе военной ипотеки. </p>

<p style="text-align: justify;">В нашей базе недвижимости всегда есть квартиры по военной ипотеке. Это просторные и светлые квартиры, расположенные в лучших новостройках города. Главное преимущество нашего агентства: мы предлагаем квартиры по ценам застройщика!</p>

<p style="text-align: justify;">Мы также сотрудничаем с надежными банками, работающими с программой ипотеки для военнослужащих. Мы поможем выбрать кредитную программу в соответствии с вашими желаниями и возможностями! </p>
</div>
            <div class="clearfix"></div>
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


			<?php endif; ?>

        </div>
    </div>
</section>





<div id="askLand" class="mfp-hide white-popup-block askpopup" data-name="Новостройки | <?=$rows->header;?>" data-link="<?=getUrl();?>">
    <p class="ask_caption">Ответим на ваш вопрос, в течение ближайших 5 минут, либо в любое удобное для вас время</p>
    <form id="land_form_ask">
        <div class="rowd">
            <textarea id="comment" data-question="" placeholder="Вопрос"></textarea>
        </div>
        <div class="rowd">
            <input type="text" name="name" id="name" class="require" placeholder="Имя" />
        </div>
        <div class="rowd">
            <input type="text" name="phone" id="phone" class="require phoneFormat" placeholder="Телефон" />
        </div>
        <div class="rowd">
            <input type="text" name="email" id="email" placeholder="Email" />
        </div>
        <div class="rowd">
            Удобное время для звонка:  с <input type="text" name="better_call" class="timetocall" id="better_call_min" value="10:00" readonly /> до <input type="text" name="better_call" class="timetocall" id="better_call_max" value="20:00" readonly />
        </div>
        <label class="checkbox-block"><div class="checkboxes" id="sub_news"></div>Подписаться на новости компании</label>
        <label class="checkbox-block">
            <div class="checkboxes active" id="agree"></div>Принимаю <a href="/privacy_policy" target="_blank">соглашение на обработку персональных данных</a>
        </label>
        
        <div class="rowd">
            <input type="submit" class="submit-ask" name="submit" value="отправить" />
        </div>
    </form>
</div>



<script>

            
function scrollTo(id)
{
    $('html, body').animate({
        scrollTop: $(id).offset().top
    }, 500);

}
</script>