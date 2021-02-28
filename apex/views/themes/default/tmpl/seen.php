
<ul class="bx-seen-bottom">
    <?php foreach ($data as $key => $value):?>
        <li class="favsid-<?=$value['id']?>">
            <a class="oneitembot" href="<?=$value['link']?>" target="_blank">
                <img src="<?php echo image($value['foto'], "small"); ?>" alt="<?=$value['name']?>" width="172" height="116">
                <p><?=$value['name']?></p>
                <p class="price"><?=$value['price']?></p>
            </a>

        </li>
    <?php endforeach; ?>
</ul>
