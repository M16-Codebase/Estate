<?php $this->load->module('land')->getFilterLand(); ?>

<div class="ajax-source">
    <?php $this->load->view('themes/'.$this->defaultTheme.'/'.'land_part', array('rows'=>$rows, 'pagination'=>$pagination)); ?>
</div>

<div class="mapto">
    <div class="map-buildings" id="lands-map"></div>
</div>

<?php if(uri(2) == ''): ?>
    <br><br>
    <section class="container-fluid">
        <div class="row">
                <div class="container">
                    <?php $k = "[seo-buildings-6]"; ?>
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