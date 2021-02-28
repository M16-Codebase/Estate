<?php $asd = 'getFilterAbroad'; $this->load->module('abroad')->$asd(); ?>

    <div class="ajax-source">
        <?php $this->load->view('themes/'.$this->defaultTheme.'/'.'abroad_part', array('rows'=>$rows, 'pagination'=>$pagination)); ?>
    </div>
    <div class="mapto" id="abroadlink">
        <div class="map-city" id="abroad"></div>
  <div class="map-city-text" id="abroadtext">ПОИСК ПО КАРТЕ</div>
        <div class="map-buildings" id="abroad-map" style="display: none;"></div>
    </div>
<?php if(uri(2) == ''): ?>
    <br><br>

    <section class="container-fluid">
        <div class="row">

                <div class="container">
                    <?php $k = "[seo-buildings-5]"; ?>
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