<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$type_building = array(2,3,6);
foreach($d_type as $k => $v) { if(in_array($k, $type_building)) { unset($d_type[$k]); } }

$ray = array(26,27,11);
foreach($d_rayon as $k => $v) { if(in_array($k, $ray)) { unset($d_rayon[$k]); } }
$metro_building = array(51);
foreach($d_metro as $k => $v) { if(in_array($k, $metro_building)) { unset($d_metro[$k]); } }

if (empty($params['rayon']))       { $params['rayon'] = '0'; } else {$rayon_array = explode(",", $params['rayon']);}
if (empty($params['metro']))       { $params['metro'] = '0'; } else {$metro_array = explode(",", $params['metro']);}
if (empty($params['adress']))      { $params['adress'] = ''; }
if (empty($params['price_from']))  { $params['price_from'] = 0; }
if (empty($params['price_to']))    { $params['price_to'] = 200; }
if (empty($params['square_from'])) { $params['square_from'] = ''; }
if (empty($params['square_to']))   { $params['square_to'] = ''; }
if (empty($params['room']))        { $params['room'] = '0'; } else {$room_array = explode(",", $params['room']);}
if (empty($params['ipoteka']))     { $params['ipoteka'] = ''; }
if (empty($params['rassrochka']))  { $params['rassrochka'] = ''; }
if (empty($params['name']))        { $params['name'] = ''; }
if (empty($params['type']))        { $params['type'] = '0'; } else {$type_array = explode(",", $params['type']);}
if (empty($params['srok']))        { $params['srok'] = ''; }
?>

<?php $this->load->module('buildings')->getFilterBuildings(); ?>
<div class="ajax-source">
	<?php $this->load->view('themes/'.$this->defaultTheme.'/'.'buildings_part', array('rows'=>$rows, 'pagination'=>$pagination)); ?>
</div>
<div class="mapto" id="novlink">
	<div class="map-city"></div>
  <div class="map-city-text">ПОИСК НОВОСТРОЕК ПО КАРТЕ</div>


	<div class="map-buildings" id="nov-map" style="display:none;"></div>
</div>

<?php if(uri(2) == '') { ?>
	<br><br>
	<?php $k = (uri(1) == 'buildings')?"[seo-buildings-0]":"[seo-buildings-3]"; ?>
	<?php if(str_word_count($k) > 0) { ?>
	<div class="contain">
		<div class="seotext">
			<?php echo $k; ?>
			<div class="clearfix"></div>
		</div>
	</div>
	<?php } ?>
	<br><br>
<?php } ?>

