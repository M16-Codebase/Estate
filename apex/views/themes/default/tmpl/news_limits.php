<?php if(!empty($rows)): ?>
<?php foreach($rows as $r): ?>
<h3><a href="<?php echo $r->link; ?>" style="font-size: 14px; text-transform: uppercase; font-family: 'Geometria-Bold'; color: #a3a2a1; letter-spacing: 1px; transition: 0.2s;"><?php echo $r->name; ?></a></h3>
<p class="dates"><?php echo $r->date; ?></p>
<?php endforeach; ?>
<?php endif; ?>