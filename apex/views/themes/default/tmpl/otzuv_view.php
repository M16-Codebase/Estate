<div class="container">
    <a href="/sell-appart" class="comission inset row">
            Хотите <span>продать</span> квартиру?
        </a>
</div>
<section class="reviews-cont clearfix">
    <div class="contain">
        <h1><?=$header1?></h1>
        <div class="review-view clearfix">
            <div class="head-view">
                <a href="/otzuv" class="backtoreviews">Вернуться к списку</a>
                <div class="info-view">
                    <span class="date-view"><?=$rows->date?></span>
                    <p class="author"><?php echo $rows->header; ?></p>
                </div>
                <div class="action-view">
                    <a href="#comments" class="actico commentsreview">0</a>
                    <?php if ($rows->audio_count):?>
                        <a href="#" class="actico audioreview"><?=$rows->audio_count?></a>
                    <?php else: ?>
                        <span class="actico audioreview">0</span>
                    <?php endif; ?>
                    <?php if ($rows->photo_count):?>
                        <a href="#" class="actico photoreview"><?=$rows->photo_count?></a>
                    <?php else: ?>
                        <span class="actico photoreview">0</span>
                    <?php endif; ?>
                    <?php if ($rows->video_count):?>
                        <a href="#" class="actico videoreview"><?=$rows->video_count?></a>
                    <?php else: ?>
                        <span class="actico videoreview">0</span>
                    <?php endif; ?>
                </div>
                <a href="#" class="likereview"  data-id="<?php echo $rows->id; ?>" data-identity="reviews"><?=$rows->likes?></a>
            </div>
            <div class="text-view">
                <div class="media-block">
                    <?php if ($rows->video_count):?>
                        <?php foreach($rows->video['video'] as $k=>$v): ?>
                            <iframe width="440" height="270" src="https://www.youtube.com/embed/<?=$v?>" frameborder="0" allowfullscreen></iframe>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if ($rows->photo_count):?>
                        <?php foreach($rows->foto['foto'] as $k=>$v): ?>
                        <a href="<?=$v?>" class="otzmag"><img src="<?=image_resize($v,'rpreview',false)?>" /></a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if ($rows->audio_count && !empty($rows->audio)):?>
                        <?php foreach($rows->audio as $k=>$v): ?>
                            <audio controls="controls">
                                <source src="/asset/uploads/reviews/temp/<?=$v['name']?>" type="<?=$v['type']?>">
                                Your browser does not support the audio element.
                            </audio>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <?php echo $rows->text; ?>
            </div>
        </div>
        <div id="hypercomments_widget"></div>
    </div>
</section>