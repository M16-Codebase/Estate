<!-- стиль -->
<style>
    .consultant__container { border-top: 0 !important }
    .consultant__head { color: #009de0; font-style: italic; font-size: 14px; font-weight: 700 }
    .consultant__block-left { width: 75px; height: 75px; float: left; margin-right: 25px }
    .consultant__img { max-width: 75px; max-height: 75px; border-radius: 50%; }
    .consultant__block-right { width: 200px; height: 75px; float: left }
    .consultant__name { padding-top: 10px; margin-bottom: 0 }
    .consultant__email { color: #009de0; }
</style>
<!-- шаблон -->
<div class="complex-info-list consultant__container">
    <p class="consultant__head">Консультант на объекте</p>
    <div class="consultant__block clearfix">
        <div class="consultant__block-left">
            <img class="consultant__img" src="<?php echo $avatar ?>" alt="">
        </div>
        <div class="consultant__block-right">
            <p class="consultant__name"><?php echo $name ?></p>
            <p class="consultant__email"><?php echo $email ?></p>
        </div>
    </div>
</div>