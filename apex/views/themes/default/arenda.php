<?php $this->load->module('arenda')->getFilterArenda(); ?>

<div class="ajax-source">
    <?php $this->load->view('themes/'.$this->defaultTheme.'/'.'arenda_part', array('rows'=>$rows, 'pagination'=>$pagination)); ?>
</div>
<div class="mapto" id="arendalink">
    <div class="map-city" id="arenda"></div>
    <div class="map-city-text" id="arendatext">ПОИСК КВАРТИР В АРЕНДЕ ПО КАРТЕ</div>
    <div class="map-buildings" id="arenda-map" style="display: none;"></div>
</div>
<?php if(uri(2) == ''): ?>
    <br><br>
    <section class="container-fluid">
        <div class="row">
                <div class="container">
                    <?php $k = "[seo-buildings-7]"; ?>
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