<?php /** Поиск **/ ?>
<section class="container-fluid block-novostoy-intro">
    <div class="row">
        <div class="col-xs-12 image-wrapper">
            <div class="container">
                <div class="row">
                    <p class="novostoy-title" style="width: auto; padding-left: 20px; padding-right: 20px; "><?php echo $lang->md_title; ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<br><br><br>
<section class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div class="container">
                <div class="row" style="text-align: center;">
                  <form method="post">
                      <input type="text" class="input-search" name="search" placeholder="Введите текст поиска">

                      <button class="button-sbt" type="submit">Поиск</button>
                  </form>
                </div>
            </div>
        </div>
    </div>
</section>
<br><br><br>

<section class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div class="container">
                <div class="row">
                    <div class="filter-result">
                    <?php $k = 1; ?>
                    <?php if(!empty($rows)): ?>
                    <?php foreach($rows as $r): ?>
                    <?php $f = $k/1; $s = $k/4; ?>
                        <?php if($f == 1): ?>
                        <div class="filter-result-row clearfix">
                        <?php endif; ?>
                            <a href="<?php echo $r->link; ?>" class="filter-result-item">
                                <img class="filter-result-item-img" src="<?php echo $r->foto; ?>" alt="">
                                <div class="filter-result-item-body">
                                    <p class="filter-result-item-name"><?php echo $r->name; ?></p>
                                </div>
                            </a>
                        <?php if($s == 1): ?>
                        </div>
                        <?php endif; ?>
                        <?php $k++; if($k > 4) { $k = 1; } ?>

                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="filter-result-paging">
                        <?php echo $pagination ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>