<?php $this->load->module('buildings')->getFilterExclusive(); ?>

<div class="ajax-source">
	<?php $this->load->view('themes/'.$this->defaultTheme.'/'.'exclusive_part', array('rows' => $rows, 'pagination' => $pagination)); ?>
</div>
<div class="mapto" id="exlink">
    <div class="map-city" id="exclusive"></div>
  <div class="map-city-text" id="exclusivetext">ПОИСК ПО КАРТЕ</div>
	<div class="map-buildings" id="exclusive-map" style="display: none;"></div>
</div>
<?php if (uri(2) == '') { ?>
<br><br>
<section class="container-fluid">
	<div class="row">
		<div class="container">
			<?php if (uri(1) == 'buildings') { ?>
				<?php $k = "[seo-buildings-0]"; ?>
			<?php } else { ?>
				<?php $k = "[seo-buildings-9]"; ?>
			<?php } ?>
			<?php if (str_word_count($k) > 0) { ?>
				<div class="row seotext">
					<?php echo $k; ?>
				</div>
			<?php } ?>
		</div>
	</div>
</section>
<br><br>
<?php } ?>