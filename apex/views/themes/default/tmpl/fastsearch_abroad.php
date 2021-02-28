<div class="notshow fsblock clearfix">
    <div class="fsleft">
        <span class="fssearch">Быстрый поиск</span>
        <a href="#" class="change_fs_res active" id="metrofs">По странам</a>
        <a href="#" class="change_fs_res" id="rayonfs">По типу объекта</a>
    </div>
    <div class="fsrght">
        <div class="metrofs clearfix fslinksblock active">
            <?php
            $this->load->model('abroad_country/abroad_country_model','abroad_country');
            $metro = $this->abroad_country->getAllFastFilter();
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
                        <?php if($link !== $v['link']): ?><a href="/abroad/<?=$v['link']?>"><?php endif;?><?=$v['name']?><?php if($link !== $v['link']): ?></a><?php endif;?>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php } ?>
        </div>
        <div class="rayonfs clearfix fslinksblock">
            <?php
            $this->load->model('abroad_estate/abroad_estate_model','abroad_estate_model');
            $metro = $this->abroad_estate->getAllFastFilter();
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
                        <?php if($link !== $v['link']): ?><a href="/abroad/<?=$v['link']?>"><?php endif;?><?=$v['name']?><?php if($link !== $v['link']): ?></a><?php endif;?>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php } ?>
        </div>
    </div>
    <a href="#" class="closefs"></a>
</div>