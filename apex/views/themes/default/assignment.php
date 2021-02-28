<?php $this->load->module('buildings')->getFilterAssignment(); ?>
<div class="ajax-source">
	<?php $this->load->view('themes/'.$this->defaultTheme.'/'.'assignment_part', array('rows' => $rows, 'pagination' => $pagination)); ?>
</div>
<!--<div class="mapto">
	<div class="map-buildings" id="assignment-map"></div>
</div>-->
<?php if (uri(2) == '') { ?>
	<br><br>
	<section class="container-fluid">
		<div class="row">
			<div class="container">
				<?php $k = "[seo-buildings-8]"; ?>
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