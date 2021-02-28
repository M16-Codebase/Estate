<div class="notshow fsblock clearfix">
    <div class="fsleft">
        <span class="fssearch">Быстрый поиск</span>
        <a href="#" class="change_fs_res active" id="rayonfs">По районам</a>
        <a href="#" class="change_fs_res" id="typefs">По типу недвижимости</a>
    </div>
    <div class="fsrght">

        <div class="rayonfs clearfix fslinksblock active">
            <?php
            $this->load->model('rayon/rayon_model','rayon_model');
            $metro = $this->rayon_model->getAllFastFilter(9);
            $cm = count($metro);
            if ($cm > 0){
                $km = 0;
                $half = ($cm > 5) ? (int)ceil(($cm) / 5) : 1;
                ?>
                <ul>
                <?php foreach ($metro as $k => $v): ?>
                    <?php if($km == $half){ $km = 0; ?>
                        </ul>
                        <ul>
                    <?php  } $km++; ?>
                    <li>
                        <?php if($link !== $v['link']): ?><a href="/exclusive/<?=$v['link']?>"><?php endif;?><?=$v['name']?><?php if($link !== $v['link']): ?></a><?php endif;?>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php } ?>
        </div>
        <div class="typefs clearfix fslinksblock">
            <?php
            $this->load->model('elite_type/elite_type_model','elite_type');
            $metro = $this->elite_type->getAllFastFilter(9);
            $cm = count($metro);
            if ($cm > 0){
                $km = 0;
                $half = ($cm > 5) ? (int)ceil(($cm) / 5) : 1;
                ?>
                <ul>
                <?php foreach ($metro as $k => $v): ?>
                    <?php if($km == $half){ $km = 0; ?>
                        </ul>
                        <ul>
                    <?php  } $km++; ?>
                    <li>
                        <?php if($link !== $v['link']): ?><a href="/exclusive/<?=$v['link']?>"><?php endif;?><?=$v['name']?><?php if($link !== $v['link']): ?></a><?php endif;?>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php } ?>
        </div>
    </div>
    <a href="#" class="closefs"></a>
</div>