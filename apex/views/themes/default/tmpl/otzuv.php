<div class="container">
    <a href="/sell-appart" class="comission inset row">
            Хотите <span>продать</span> квартиру?
        </a>
</div>
<section class="reviews-cont clearfix">
    <div class="contain">
        <h1>Отзывы наших клиентов</h1>
        <div class="reviews-list">
            <div class="reviews-search clearfix">
                <form action="/otzuv/">
                    <input type="text" name="search" placeholder="Поиск по отзывам" value="<?=$search?>" />
                    <button type="submit">&nbsp;</button>
                </form>
            </div>
            <?php foreach($rows as $k=>$r): ?>
            <div class="onereview">
                <div class="review-header">
                    <p><?php echo $r->name; ?>:</p>
                    <span class="date-review"><?php echo $r->date; ?></span>
                </div>
                <div class="review-body clearfix">
                    <div class="text-body">
                        <?php if ($r->audio_count):?>
                        <p class="author">Аудио-отзыв</p>
                        <?php endif; ?>
                         <?php if ($r->photo_count && !empty($r->foto)):?>
                        <?php foreach($r->foto['foto'] as $k=>$v): ?>
                        <a href="<?=$v?>" class="otzmag"><img style="float: left; width: 100px; margin-right: 25px;" src="<?=image_resize($v,'rpreview',false)?>" /></a>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <div class="text">
                            <?php echo $r->text; ?>
                       
                        </div>
                        <?php if ($r->audio_count && !empty($r->audio)):?>
                            <?php foreach($r->audio as $k=>$v): ?>
                                <audio controls="controls">
                                    <source src="/asset/uploads/reviews/temp/<?=$v['name']?>" type="<?=$v['type']?>">
                                    Your browser does not support the audio element.
                                </audio>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if (count($r->tags)>0):?>
                            <div class="review-tags">
                            <?php foreach($r->tags as $kt=>$rt): ?>
                                <?=$rt?>
                            <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="action-body">
                        <a href="#" class="likereview" data-id="<?php echo $r->id; ?>" data-identity="reviews"><?=$r->likes?></a>
                        <a href="<?php echo $r->link; ?>#disqus_thread" class="actico commentsreview" title="">0</a>
                        <?php if ($r->audio_count):?>
                        <a href="<?php echo $r->link; ?>" class="actico audioreview"><?php echo $r->audio_count; ?></a>
                        <?php else: ?>
                        <span class="actico audioreview">0</span>
                        <?php endif; ?>
                        <?php if ($r->photo_count):?>
                            <a href="<?php echo $r->link; ?>" class="actico photoreview"><?php echo $r->photo_count; ?></a>
                        <?php else: ?>
                            <span class="actico photoreview">0</span>
                        <?php endif; ?>
                        <?php if ($r->video_count):?>
                            <a href="<?php echo $r->link; ?>#videoshow" class="actico videoreview"><?php echo $r->video_count; ?></a>
                        <?php else: ?>
                            <span class="actico videoreview">0</span>
                        <?php endif; ?>

                    </div>
                </div>
				
            </div>
			
            <?php endforeach; ?>
			<div class="filter-result-paging sm_selectpage" data-category="SelectPage" data-label="otzuv">
					<?php if ($params['page'] !== 'all' && !empty($pagination) && strpos($_SERVER['REQUEST_URI'],'tag')==false && strpos($_SERVER['REQUEST_URI'],'search')==false) { 
					$pagination=str_replace('<a class="filter-result-paging-link" href="/otzuv/36">36</a>','',$pagination);
					if(strpos($pagination,'37')){
						$pagination=str_replace('<a class="filter-result-paging-link" href="/otzuv/36">></a>','',$pagination);
					}
					$pagination=str_replace('<a class="filter-result-paging-link" href="/otzuv/37">37</a>','',$pagination);
					?>
						<?php 						
						echo $pagination;
						//echo get_class($pagination);
						?>
						<br><br>
						<a href="/<?php echo uri(1); ?>/?<?php echo $show_all; ?>" rel="nofollow" class="filter-result-paging-link sm_search" data-category="ShowAll" data-label="otzuv" style="margin-top: 10px; font-size: 13px; margin-left: 0;">Показать все</a>
					<?php } ?>
				</div>
        </div>
		

        <div class="reviews-bar">

            <form id="send-review"  method="post">

                <p>Поделитесь своим мнением!</p>
                <input type="text" name="c1" placeholder="Ваше имя" />
                <input type="text" name="c2" placeholder="Email" />
                <input type="text" name="manager" placeholder="Имя и фамилия менеджера" />
                <textarea placeholder="Текст отзыва" name="c3"></textarea>
                <div class="attach-review">
                    <!--<a href="#" class="atta photoatt">Прикрепить фото</a>
                    <a href="#" class="atta videoatt">Прикрепить видео</a>
                    <a href="#" class="atta audioatt">Прикрепить аудио</a>-->
                </div>
                <button type="submit" >опубликовать отзыв</button>

            </form>

            <div class="vote-bar">
                <ul class="vote-bxslider">
                    <?php foreach ($votes as $k => $v){?>
                    <li>
                        <div class="one-vote votesdiv-<?=$v['id'];?>">
                            <p class="quest"><?=$v['name']?></p>
                            <?php if(empty($v['abs'])):?>

                            <div class="answ">
                                <?php foreach ($v['answers'] as $ka => $va):?>
                                    <a href="#" class="votesend" data-id="<?=$va['id']?>" data-vote="<?=$va['vote_id']?>"><?=$va['name']?></a>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                                <?php foreach ($v['answers'] as $ka => $va):?>
                                    <div class="resvote">
                                        <?php $percent = round($va['count']/$v['count']*100); ?>
                                        <p><?=$va['name']?><span><?=$va['count']?> (<?=$percent?>%)</span></p>
                                        <div class="vote-line">
                                            <span class="colorow" style="width: <?=$percent?>%"></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </li>
                    <?php } ?>
            </div>

            <div class="mostcomment">
                <div class="title-mostc">
                    Самые обсуждаемые отзывы
                </div>
                <div class="body-mostc">
                    <?php foreach($mostcomment as $k=>$v):?>
                    <div class="onemc">
                        <p><?=$v['name']?></p>
                        <p class="txtcomm"><?=$v['text']?></p>
                        <div class="bot-onecomm">
                            <a href="<?php echo $v['link']; ?>#disqus_thread" class="actico commentsreview" title="">0</a>
                            <a href="<?php echo $v['link']; ?>" class="gotoview">Перейти к отзыву</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
		


<!--
<br><br><br>

<div class="clearfix otzuv-form">
<div class="left-parth-calc">
<h2 style="font-family: Intro;">Добавить отзыв</h2>
<form id="otzuv-form" style="text-align: left;">
<label></label>
<input type="text" name="c1" class="input-bron" placeholder="Ваше имя">
<br>
<label></label>
<input type="text" name="c2" class="input-bron" placeholder="Ваш email">
<br>
<label></label>
<textarea class="input-bron" placeholder="Ваш отзыв" name="c3"></textarea>
<button type="button" class="submit-calc" onclick="sendOtzuv()">отправить</button><br><br>
</form>
</div>
</div>-->
</div>
</section>
<?php ini_set('display_errors', 1) ?>
<?php js('asset/js/otzuv.js', false);?>


<script type="text/javascript">
    _hcwp = window._hcwp || [];
    _hcwp.push({widget:"Bloggerstream", widget_id: 21825, selector:  ".commentsreview", label: "{%COUNT%}"});
    (function() {
        if("HC_LOAD_INIT" in window)return;
        HC_LOAD_INIT = true;
        var lang = (navigator.language || navigator.systemLanguage || navigator.userLanguage ||  "en").substr(0, 2).toLowerCase();
        var hcc = document.createElement("script"); hcc.type = "text/javascript"; hcc.async = true;
        hcc.src = ("https:" == document.location.protocol ? "https" : "http")+"://w.hypercomments.com/widget/hc/21825/"+lang+"/widget.js";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hcc, s.nextSibling);
    })();
</script>