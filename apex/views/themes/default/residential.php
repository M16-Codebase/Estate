
                    <?php $this->load->module('buildings')->getFilterResidential(); ?>


<div class="ajax-source">
    <?php $this->load->view('themes/'.$this->defaultTheme.'/'.'residential_part', array('rows'=>$rows, 'pagination'=>$pagination)); ?>
</div>
<div class="mapto" id="reslink">
    <div class="map-city" id="residential"></div>
  <div class="map-city-text" id="residentialtext">ПОИСК ЗАГОРОДНОЙ НЕДВИЖИМОСТИ ПО КАРТЕ</div>
    <div class="map-buildings" id="resid-map" style="display: none;"></div>
</div>
<?php if(uri(2) == ''): ?>
    <br><br>

    <section class="container-fluid">
        <div class="row">
                <div class="container">
                    <?php $k = "[seo-buildings-2]"; ?>
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