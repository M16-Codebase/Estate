<?php $this->load->module('buildings')->getFilterCommercial(); ?>


<div class="ajax-source">
    <?php $this->load->view('themes/'.$this->defaultTheme.'/'.'commercial_part', array('rows'=>$rows, 'pagination'=>$pagination)); ?>
</div>

<div class="mapto" id="comlink">
        <div class="map-city" id="com"></div>
  <div class="map-city-text" id="comtext">ПОИСК КОММЕРЧЕСКОЙ НЕДВИЖИМОСТИ ПО КАРТЕ</div>
    <div class="map-buildings" id="com-map" style="display: none;"></div>
</div>

<?php if(uri(2) == ''): ?>
    <br><br>

    <section class="container-fluid">
        <div class="row">
                <div class="container">
                    <?php $k = "[seo-buildings-4]"; ?>
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
