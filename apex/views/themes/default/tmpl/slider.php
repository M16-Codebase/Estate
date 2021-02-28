<div id="intro-flat-slider" class="carousel slide" data-ride="carousel">
    <?php if(!empty($rows)): ?>
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <?php foreach($rows as $k=>$r): ?>
        <li data-target="#intro-flat-slider" data-slide-to="<?php echo $k; ?>" class="<?php if($k == 0): ?>active<?php endif; ?>"></li>
        <?php endforeach; ?>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        <?php foreach($rows as $k=>$r): ?>
        <div class="item <?php if($k == 0): ?>active<?php endif; ?>">
            <a href="<?php echo $r->link; ?>"><img src="<?php echo $r->foto; ?>" alt="<?php echo $r->name; ?>"></a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>