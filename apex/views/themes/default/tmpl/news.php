<section class="container-fluid block-feedback news clearfix" style="background: none; height: auto;">

    <div class="row">
        <div class="col-xs-12 image-wrapper">
            <div class="container reviews">

                <h1 class="h1">Новости</h1>
				
				<?php $this->view('themes/default/tmpl/list_ncategory') ?>
				<?php $this->view('themes/default/tmpl/list_tags')?>
				</div>
				<p></p>
				<?php if(!strpos('|'.$_SERVER['REQUEST_URI'],'tag')){?>
				<div style="margin:0 auto;  text-align:center;">
				<a href="#supertags" class="moretags" style="height: 48px;
					padding: 0 20px;
					color:  #019cdf;
					text-transform: uppercase;
					text-decoration: none;
					font-size: 14px;
					letter-spacing: 1px;
					margin: 10px;
					">
					Ещё теги</a>
				</div>
				<p></p>
				<?php } ?>

                <div class="row">
                    <div class="mrow">
                    <?php $k = 1; ?>
                    <?php foreach($rows as $r): ?>
                    <div class="onecol mcol-4<?php echo ($k == 4)?' nomargin':''; ?>">
							<div class="object-item sm_search" style="border: 4px solid #f3f3f3; margin-bottom:35px;">
								<div class="img_fil">
									<a href="<?php echo $r->link; ?>"><img class="filter-result-item-img" src="<?php echo $r->foto; ?>"></a>
								</div>
								<div style="padding: 15px 10px 0 10px;">
									<a style="text-decoration: none;" href="<?php echo $r->link; ?>"><p class="filter-result-item-rayon"><?php echo $r->name; ?></p></a>
									<p class="filter-result-item-name"><?php echo $r->date; ?></p>
									<div class="filter-result-item-address"><?php echo $r->text; ?></div>
									<?php if(!empty($r->tag)): ?>
									<div class="filter-result-item-size" style="margin-top:10px;"><span>Теги:</span> <?php echo $r->tag; ?></div>
									<?php endif; ?>
								</div>
							</div>
						</div>
                        <?php if ($k++ == 4) $k = 1; ?>
                        <?php endforeach;?>
                        
                    </div>
               </div>
            </div>
            <div class="filter-result-paging">
                <?php echo $pagination ?>
            </div>
        </div>
    </div>
</section>
<style>
.filter-result-item-size {
	color: #a3a2a1 !important;}
.filter-result-item-size span {
color: #000;}
.filter-result-item-size a { color: #ccc; 
font-style: italic;
    font-size: 13px;
    font-weight: 400;
    color: #a3a2a1;
	}
	.onecol.mcol-4 .object-item .viewob {
		background: #fff;
		left: 0;
		width: 100%;
	}
	</style>
