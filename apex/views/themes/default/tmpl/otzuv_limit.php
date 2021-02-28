<?php foreach($rows as $r): ?>
<div class="text">
    <p>
        <?php
            echo $r->text;
            if (isset($r->link)) {
                echo "<a href=\"/otzuv/{$r->link}/\">далее</a>";
            }
        ?>
    </p>
</div>
<p class="author"><?php echo $r->name; ?></p>
<?php endforeach; ?>
<a class="all" href="/otzuv" style="color: #1c0b01; text-decoration: underline; font-weight: 700; font-size: 16px; position: absolute; right: 17px; bottom: 8px;">Все отзывы</a>
