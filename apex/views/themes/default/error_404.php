<?php /** Шаблон 404 ошибки **/ ?>
<section class="container-fluid block-novostoy-intro">
    <div class="row">
        <div class="col-xs-12 image-wrapper">
            <div class="container">
                <div class="row">
                    <p class="novostoy-title" style="width: 800px; height: auto; line-height: 130%; margin-top: 100px; padding: 20px;"><?php echo $data['langLine']->apex_404_header; ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="darken" data-stellar-background-ratio="0.6">
    <div class="container">
          <div class="row">

                <div style="text-align: center; display: block; width: 100%; "><?php echo $data['langLine']->apex_404_text; ?></div>

          </div>
    </div>
</section>