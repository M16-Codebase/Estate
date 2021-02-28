<?php /** Шаблон для модулей **/ ?>
<div class="container">
    <a href="/sell-appart" class="comission inset row">
            Хотите <span>продать</span> квартиру?
        </a>
</div>
<section class="container-fluid block-feedback clearfix" style="background: none; height: auto;">
    <a href="/sell-appart" class="comission inset row">
            Хотите <span>продать</span> квартиру?
        </a>
    <div class="row">
        <div class="col-xs-12 image-wrapper">
            <div class="container reviews">
                <h1><?php echo $lang->md_title; ?></h1>
                <div class="row">
                    <div class="col-xs-12">
                        <?php echo $rows->text; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>