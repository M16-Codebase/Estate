<ul class="bx-specials-object-bottom">
    <?php foreach ($data as $key => $value):?>
        <a class="oneitembot spobject" href="<?=$value['link']?>" target="_blank">
            <div class="imgbl">
                <img src="<?php echo image($value['foto'], "medium"); ?>" alt="<?=$value['name']?>" width="400" height="240">
            </div>
            <div class="lftob">
                <p><?=$value['name']?></p>
                <p class="price"><?=$value['price']?></p>
            </div>
        </a>
    <?php endforeach; ?>
</ul>