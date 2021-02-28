<?php

$this->load->model('admin/module_model','chart');
$r = $this->chart->chart();

$line1 = $line2 = $line3 = array();

if(!empty($r))
{
    foreach($r as $k=>$d)
    {
        $dat = explode('-',$k);
        $k = $dat[1].'-'.$dat[2].'-'.$dat[0];

        $line1[] = $d[0];
        $line2[] = $d[1];
        $line3[] = $d[2];
        
        $date1[] = $dat[2].'.'.$dat[1]; 
    }
}

if(empty($line1) and empty($line2) and empty($line3))
{
    $line1[] = $line2[] = $line3[] = 1;
    $date1[] = date('d.m');    
    
}

krsort($date1);
krsort($line1);
krsort($line2);
krsort($line3);

$lines1 = implode(',',$line1);
$lines2 = implode(',',$line2);
$lines3 = implode(',',$line3);
$dates1 = '"'.implode('","',$date1).'"';

?>
<br />
<div class="dashboard no-padding-top">  
    <div class="mid-padding-left">    
    <!-- Данные главной страницы --> 		                        
       <hgroup id="main-title" class="thin relative">
    		<div class="columns">
                <h1><?php echo $alang->admin_main; ?> <a class="absolute-left icon-link orange hidden" target="_blank" style="left: 48%; top: 40px; font-size: 20px; line-height: 24px;" href="/design-element">Элементы дизайна</a></h1>
        		<h2><a style="font-size: 20px;" target="_blank" class="icon-link hidden" href="/tmp">Верстка</a></h2>                              
            </div>          
    	</hgroup>      		
    <!-- #Данные главной страницы -->            
    </div>
</div> 
<br />
<div class="dashboard no-padding-bottom relative">
   <div class="columns">
    <div class="six-columns">
        <div class="margin-bottom big-left-icon icon-info-round " style="line-height: 140%">
			<h4 class="mid-padding-top">Подсказка для визуального редактора</h4>
			<strong class="orange">Enter</strong> - абзац (полуторный интервал)<br />
            <strong class="orange">Shift + Enter</strong> - неразрывный абзац (обычный интервал)<br />
            Также не забываем использовать <strong class="orange">Стили</strong>         
            
		</div>
    </div>
    <div class="three-columns align-left">        
        <img onclick="location.href='<?php echo site_url(); ?>'" class="absolute-left cursor-pointer hidden" style="left: 48%; top: 35px;" src="/asset/img/logo2.png" height="60" />
    </div>
    <div class="three-columns align-left">
        <h3 class="float-right thin"><?php echo date('d').' '.get_months(date('m')); ?> <br /> <?php echo get_days(date('w')); ?></h3>
    </div>
   </div>
   
    
    
</div>
<br />
<div class="dashboard hidden">

	<div class="columns" style="background: url(/asset/admin/img/effects/dashboard-bottom-shadow.png) no-repeat center bottom;">

		<div class="nine-columns twelve-columns-mobile example-plot" id="demo-chart"></div>

		<div class="three-columns twelve-columns-mobile new-row-mobile">
			
            <?php             
                /*$this->db->where('status', '0');
                $rastanovkazayavka = $this->db->count_all_results('rastanovkazayavka');
                
                $this->db->where('status', '0');
                $treningzayavka = $this->db->count_all_results('treningzayavka');                
                
                $this->db->where('tematika_id', '0');
                $this->db->where('banned', '0');
                $vopros = $this->db->count_all_results('vopros');
                                    
                $this->db->where('banned', '1');
                $comment = $this->db->count_all_results('comment'); */           
            ?>
            
            <ul class="stats split-on-mobile">
				<li><a href="/rastanovkazayavka/admin/index"><strong><?php echo $rastanovkazayavka; ?></strong> новых<br /> заявок на расстановки</a></li>
                <li><a href="/treningzayavka/admin/index"><strong><?php echo $treningzayavka; ?></strong> новых<br /> заявок на тренинг</a></li>
                <li><a href="/vopros/admin/index"><strong><?php echo $vopros; ?></strong> новых<br /> вопросов</a></li>					
                <li><a href="/comment/admin/index"><strong><?php echo $comment; ?></strong> новых<br /> комментариев</a></li>
			</ul>
            
		</div>

	</div>

</div>     




<script src="//www.google.com/jsapi"></script>
<script>        
$(document).ready(function(){ 
    $.template.init();
});

	/*
	 * This script is dedicated to building and refreshing the demo chart
	 * Remove if not needed
	 */

	// Demo chart
	var chartInit = false,
		drawVisitorsChart = function()
		{
			// Create our data table.
			var data = new google.visualization.DataTable();
			var raw_data = [['Расстановка', <?php echo $lines1; ?>],
							['Тренинг', <?php echo $lines2; ?>],
							['Бесплатная расстановка',<?php echo $lines3; ?>]];

			var months = [<?php echo $dates1; ?>];

			data.addColumn('string', 'Day');
			for (var i = 0; i < raw_data.length; ++i)
			{
				data.addColumn('number', raw_data[i][0]);
			}

			data.addRows(months.length);

			for (var j = 0; j < months.length; ++j)
			{
				data.setValue(j, 0, months[j]);
			}
			for (var i = 0; i < raw_data.length; ++i)
			{
				for (var j = 1; j < raw_data[i].length; ++j)
				{
					data.setValue(j-1, i+1, raw_data[i][j]);
				}
			}

			// Create and draw the visualization.
			// Learn more on configuration for the LineChart: http://code.google.com/apis/chart/interactive/docs/gallery/linechart.html
			var div = $('#demo-chart'),
				divWidth = div.width();
			new google.visualization.LineChart(div.get(0)).draw(data, {
				title: 'Последние 20 дней заявок',
				width: divWidth,
				height: $.template.mediaQuery.is('mobile') ? 180 : 300,
				legend: 'right',
				yAxis: {title: '(thousands)'},
				backgroundColor: ($.template.ie7 || $.template.ie8) ? '#494C50' : 'transparent',	// IE8 and lower do not support transparency
				legendTextStyle: { color: 'white' },
				titleTextStyle: { color: 'white' },
				hAxis: {
					textStyle: { color: 'white' }
				},
				vAxis: {
					textStyle: { color: 'white' },
					baselineColor: '#666666'
				},
				chartArea: {
					top: 55,
					left: 30,
					width: divWidth-40
				},
				legend: 'bottom'
			});

			// Ready
			chartInit = true;
		};

	// Load the Visualization API and the piechart package.
	google.load('visualization', '1', {
		'packages': ['corechart']
	});

	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(drawVisitorsChart);

	// Watch for block resizing
	//$('#demo-chart').widthchange(drawVisitorsChart);

	// Respond.js hook (media query polyfill)
	$(document).on('respond-ready', drawVisitorsChart);

</script>
