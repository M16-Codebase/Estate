<?php $this->load->module('buildings')->getFilterResale(); ?>
<div class="ajax-source">
    <?php $this->load->view('themes/'.$this->defaultTheme.'/'.'resale_part', array('rows'=>$rows, 'pagination'=>$pagination)); ?>
</div>

<div class="mapto" id="reslink">
    <div class="map-city" id="resale"></div>
  <div class="map-city-text" id="resaletext">ПОИСК ВТОРИЧКИ ПО КАРТЕ</div>


    <div class="map-buildings" id="res-map" style="display:none;"></div>
</div>
<?php if(uri(2) == ''): ?>
    <br><br>
    <section class="container-fluid">
        <div class="row">
                <div class="container">
                    <?php $k = "[seo-buildings-1]"; ?>
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