<div class="notshow fsblock clearfix">
    <div class="fsleft">
        <span class="fssearch">Быстрый поиск</span>
        <a href="#" class="change_fs_res active" id="metrofs">По станциям метро</a>
        <a href="#" class="change_fs_res" id="rayonfs">По районам</a>
    </div>
    <div class="fsrght">
        <div class="metrofs clearfix fslinksblock active">
            <?php
			if(!isset($link)) { $link = ''; }
            $this->load->model('metro/metro_model','metro_model');
            $metro = $this->metro_model->getAllFastFilter(1);
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
                        <?php if($link !== $v['link']): ?><a href="/resale/<?=$v['link']?>"><?php endif;?><?=$v['name']?><?php if($link !== $v['link']): ?></a><?php endif;?>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php } ?>
        </div>
        <div class="rayonfs clearfix fslinksblock">
            <?php
            $this->load->model('rayon/rayon_model','rayon_model');
            $metro = $this->rayon_model->getAllFastFilter(1);
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
                        <?php if($link !== $v['link']): ?><a href="/resale/<?=$v['link']?>"><?php endif;?><?=$v['name']?><?php if($link !== $v['link']): ?></a><?php endif;?>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php } ?>
        </div>
    </div>
    <a href="#" class="closefs"></a>
</div>