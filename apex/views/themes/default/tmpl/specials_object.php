<ul class="bx-specials-object">
    <?php foreach ($data as $key => $value):?>
        <?php if (($key+1) % 2 != 0):?>
        <li>
        <?php endif; ?>
            <a class="oneitembot spobject" href="<?=$value['link']?>" target="_blank">
                <img src="<?php echo image($value['foto'], "small"); ?>" alt="<?=$value['name']?>" width="172" height="116">
                <div class="lftob">
                    <p><?=$value['name']?></p>
                    <p class="price"><?=$value['price']?></p>
                </div>
            </a>
        <?php if (($key+1) % 2 == 0 || ($key+1) == count($data)):?>
        </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>