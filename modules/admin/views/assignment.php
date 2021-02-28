<style>
#messagess_modal	{position: relative; width: 34px; height: 0px;}
#ajax_img_load		{position: absolute; left: 180px; top: -31px; }
</style>

<div class="block">
	<h3 class="block-title">Парсер переуступок</h3>
	<div class="with-padding">
		<div class="wrapped margin-bottom big-left-icon icon-gear">
			<h4 class="no-margin-bottom blue">Информационное сообщение</h4>
			<p class="red">После нажатия кнопки "<strong class="anthracite">Обновить переуступки</strong>", - <span>будут добавлены новые переуступки и обновлены цены по уже имеющимся.</p>
		</div>
		<br>
		<ul class="list spaced">
			<li>
				<h4 class="no-margin-bottom">Ссылка на xml</h4>
				<p class="button-height">
					<input type="text" class="input six-columns" id="link-xml" value="http://m16-estate.ru/asset/uploads/crm/assignment.xml" />
				</p>
			</li>
			<li>
				<a href="#" id="button-load-complex" class="button margin-right">Обновить переуступки</a><br>
				<div id="messagess_modal"></div>
			</li>
			<li id="result-parser"></li>
		</ul>
	</div>
</div>

<script>
$(document).ready(function(){

	$('#button-load-complex').on('click', function(){

		var loads = '<img id="ajax_img_load" src="/asset/admin/img/standard/loaders/loading32.gif"/>';
		$('#messagess_modal').html(loads).css({'display':'block'});

		$.ajax({
			type: 'POST',
			url: '/admin/assignment/ajax_process',
			dataType: 'json',
			data: {
				files: $('#link-xml').val()
			},
			success: function(data) {

				if (data.type == 'error')
				{
					$('#result-parser').html(data.msg);
				}
				else
				{
					var text = '<br />';
					text+= 'Добавлено: ' + data.add + '<br />';
					text+= 'Обновлено: ' + data.update + '<br />';
					$('#result-parser').html(text);
				}

				$('#ajax_img_load').remove();
			},
		});

		return false;
	});

});

</script>