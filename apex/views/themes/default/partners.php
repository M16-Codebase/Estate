<div class="main-content nobgfil partnerscon">
    <div class="contain">
        <a href="/sell-appart" class="comission">
            Хотите <span>продать</span> квартиру?
        </a>
        <h2>Наши партнеры</h2>
        <div class="mrow clearfix partners-row">

            <?php $k = 1; ?>
            <?php foreach ($rows as $v):?>
            <div class="onecol mcol-5<?php if($k == 3):?> nomargin<?php endif;?>">
                <div class="partner-item">
                    <div class="partner-title">
                        <div class="logopartners">
                            <img src="<?=$v->foto?>" />
                        </div>
                        <span class="site"><?=$v->site?></span>
                    </div>
                    <div class="text-partners">
                        <?=$v->text?>
                    </div>
                </div>
            </div>
            <?php $k++; if($k > 3) { $k = 1; } ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>