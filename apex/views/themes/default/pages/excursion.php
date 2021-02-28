<section class="container-fluid excursion">
	<div class="container">
		<div class="row">

			<div class="excurs-request">
				<form id="excurs-form" style="text-align: left;" _lpchecked="1">
					<input type="text" name="c1" class="input-bron exfio wd380" placeholder="ФИО">
					<input type="text" name="c2" class="input-bron exmob wd180 phoneFormat" placeholder="Мобильный телефон">
					<input type="text" name="c2" class="input-bron exemail wd180 nmgr" placeholder="Email">
					<input type="text" name="c2" class="input-bron exbron wd380" placeholder="К-во мест для брони">
					<input type="text" name="c4" class="input-bron excomm wd380" placeholder="Комментарий">
					<input type="submit" class="submit-calc" value="записаться" onclick="yaCounter29760432.reachGoal('ExcursiaZapisatsa'); _gaq.push(['_trackEvent', 'ZapisNaExcursiy', 'Click']); return true;">
				</form>
			</div>

			<?php echo $rows->text; ?>

			<!--<div id="reviews">
			   <h3>Отзывы</h3>
			</div>-->
		</div>

	</div>
</section>

<div id="askLand" class="mfp-hide white-popup-block askpopup" data-name="Зарубежная недвижимость | <?=$rows->header;?>" data-link="<?=getUrl();?>">
	<p class="ask_caption">Ответим на любой Ваш вопрос, в удобное для Вас время</p>
	<form id="land_form_ask">
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
		<div class="rowd">
			<textarea id="comment" data-question="Меня интересуют <?php echo $rows->estate; ?> «<?php echo $rows->header; ?>»">Меня интересуют <?php echo $rows->estate; ?> «<?php echo $rows->header; ?>»</textarea>
		</div>
		<label class="checkbox-block"><div class="checkboxes" id="sub_news"></div>Подписаться на новости компании</label>
		<label class="checkbox-block">
                    <div class="checkboxes active" id="agree"></div>Принимаю <a href="/privacy_policy" target="_blank">соглашение на обработку персональных данных</a>
                </label>
                <div class="rowd">
			<input type="submit" class="submit-ask" name="submit" value="отправить" onclick="yaCounter29760432.reachGoal('otpravit-zayav'); _gaq.push(['_trackEvent', 'OtpravitVoprosPoExcursii', 'Click']); return true;" />
		</div>
	</form>
</div>