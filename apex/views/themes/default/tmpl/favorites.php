
    <ul class="bx-favorites-bottom">
        <?php foreach ($data as $key => $value):?>
            <li class="favsid-<?=$value['id']?> favorites-common">
                <a class="oneitembot" href="<?=$value['link']?>" target="_blank">
                    <img src="<?php echo image($value['foto'], "small"); ?>" alt="<?=$value['name']?>" width="172" height="116">
                    <p><?=$value['name']?></p>
                    <p class="price"><?=$value['price']?></p>
                </a>

            </li>
			<li class="favsid-<?=$value['id']?> favorites-common">
			<a href="#" class="addtofavorite" data-category="buildings" data-id="<?=$value['oid']?>" data-action="delete" style="
				background-image: url(/asset/assets/img/krestik.png);
				width: 35px;
				height: 35px;
				display: block;
				position: absolute;
				top: 22px;
				left: -92px;
			"></a>
			</li>
        <?php endforeach; ?>
    </ul>