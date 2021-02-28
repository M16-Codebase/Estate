<?php $this->load->module('buildings')->getFilterElite(); ?>

<div class="ajax-source">
    <?php $this->load->view('themes/'.$this->defaultTheme.'/'.'elite_part', array('rows'=>$rows, 'pagination'=>$pagination)); ?>
</div>
<div class="mapto">
    <div class="map-buildings" id="elite-map"></div>
</div>
<?php if(uri(2) == ''): ?>
    <br><br>

    <section class="container-fluid">
        <div class="row">

                <div class="container">
                    <?php if(uri(1) == 'buildings'): ?>
                        <?php $k = "[seo-buildings-0]"; ?>
                    <?php else: ?>
                        <?php $k = "[seo-buildings-3]"; ?>
                    <?php endif; ?>
                    <?php if(str_word_count($k) > 0): ?>
                        <div class="row seotext">
                            <?php echo $k; ?>
                        </div>
                    <?php endif; ?>
                </div>

        </div>
    </section>

    <br><br>
<?php endif; ?>