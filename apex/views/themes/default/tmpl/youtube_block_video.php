<section class="complex-info infnov withbg">
<div id="video-schema" itemprop="video" itemscope itemtype="http://schema.org/VideoObject">
<div id="video-schema-meta">
<meta itemprop="description" content="<?php echo $desc ?>"/>
<meta itemprop="duration" content="T1M21S"/>
<link itemprop="url" href="https://www.youtube.com/embed/<?php echo $video ?>"/>
<link itemprop="thumbnailUrl" href="https://i.ytimg.com/vi_webp/l7XdzV6lh_Y/sddefault.webp"/>
<meta itemprop="name" content="Жилой комплекс «<?= $rows->header; ?>»"/>
<meta itemprop="uploadDate" content="2018-12-18"/>
<meta itemprop="isFamilyFriendly" content="true"/>
<span itemprop="thumbnail" itemscope itemtype="http://schema.org/ImageObject" >
<meta itemprop="width" content="640"/>
<meta itemprop="height" content="360"/>
</span>
</div>
	<div class="fixed-container clearfix paddtop">
		<div class="complex-info-col-left">
			<p class="complex-title">Видео о комплексе</p>
			<?php echo $desc ?>
		</div>
		<div class="complex-info-col-right">
			<?php if(!empty($video)): ?>
			<iframe width="640" height="360" src="https://www.youtube.com/embed/<?php echo $video ?>?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
			<?php endif; ?>
		</div>
	</div>
</div>
</section>