<?php if(!empty($rows)): ?>
<?php foreach($rows as $r): ?>
<div class="right-block">
    <a href="<?php echo $r->link; ?>"><img src="<?php echo image_resize($r->foto, "resizenews", false); ?>" alt="<?php echo $r->name; ?>"></a>
    <a class="all" href="/news">Все новости</a>
</div>
<h3><a style="color: #1d1d1d;" href="<?php echo $r->link; ?>"><?php echo $r->name; ?></a></h3>
<p class="date"><?php echo $r->date; ?></p>
<div class="text"><?php echo $r->text; ?></div>
<?php endforeach; ?>
<?php endif; ?>