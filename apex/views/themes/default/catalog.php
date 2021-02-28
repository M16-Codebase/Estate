<section id="blogIntro" class="dark parallax">
    <div class="container">

        <!--heading-->
        <div class="sectionHeader">

            <div class="row">
                <div class="col-md-12">
                    <img class="img-responsive separator" src="/asset/img/separator.png" alt="Separator">
                </div>
            </div>

            <div class="row sectionHeaderText text-center">
                <div class="col-md-12">
                    <h1 class="wow slideInDown"><?php echo $rows->header; ?></h1>
                </div>
            </div>

        </div>
        <!--end heading-->

    </div>
</section>

<?php echo $filters; ?>

<section id="objects" class="white"></section>

<?php echo $complex; ?>

<?php if(!empty($rows->text)): ?>
[textSeparator]
<section class="darken" data-stellar-background-ratio="0.6">
    <div class="container">
          <div class="row">

                <?php echo $rows->text; ?>

          </div>
    </div>
</section>
<?php endif; ?>