<style>.dates {
        color: #ccc;
    }

    .breadcrumbs li a {
        font-size: 14px;
        font-family: 'Geometria-Bold';
        color: #000;
    }

    .ya-share2 {
        padding: 0 18px 19px 0px;
    }
</style>
<section class="container-fluid block-feedback news clearfix" style="background: none; height: auto;">
    <div class="row">
        <div class="col-xs-12 image-wrapper">
            <div class="container reviews">
			
                <?php echo @$bread ?>
                <div class="row">
                    <div class="col-xs-8 222" style="text-align: left;">
                        <?php
                        echo '<div id="supertags" class="supertags" style="overflow:hidden;">';
                        foreach (explode(',', $rows->tag) as $the) {
                            if (strlen($the) < 3) {
                                continue;
                            }
                            echo '<div class="tag-placing"><a class="new-tags" href="/news/tag/' . trim($the) . '">' . trim($the) . '</a></div>';
                        }
                        ?>
                    </div>
                    <h1 style="margin-bottom: 10px; width: 100%; text-align: left; font-size: 23px; text-transform: uppercase; font-family: 'Geometria-Bold'; color: #000; letter-spacing: 1px;line-height: 31px;"><?php echo $rows->header; ?></h1>
                    <?php if ((int)$rows->author == 1) { ?>
                        <div style="display: table">
                            <div style="display: table-cell;"><p class="dates"
                                                                 style="font-family: 'Geometria-Bold'; text-align: left;"
                                                                 itemprop="datePublished"><?php echo $rows->date; ?></p>
                            </div>
                            <div style="display: table-cell; padding: 10px 0 10px 20px;">
                                <a class="author-name" style="text-decoration: none;">
                                    <span class="author-ava"
                                          style="background-image: url(/asset/authors/saenko.jpg);"></span>
                                    Александра Саенко</a>
                            </div>
                        </div>
                    <? } elseif ((int)$rows->author == 2) { ?>
                        <div style="display: table">
                            <div style="display: table-cell;"><p class="dates"
                                                                 style="font-family: 'Geometria-Bold'; text-align: left;"
                                                                 itemprop="datePublished"><?php echo $rows->date; ?></p>
                            </div>
                            <div style="display: table-cell; padding: 10px 0 10px 20px;">
                                <a class="author-name" style="text-decoration: none;">
                                    <span class="author-ava"
                                          style="background-image: url(/asset/authors/gurieva.jpg);"></span>
                                    Дарья Гурьева</a>
                            </div>
                        </div>
                    <? } else { ?>
                        <div style="display: table">
                            <div style="display: table-cell;"><p class="dates"
                                                                 style="font-family: 'Geometria-Bold'; text-align: left;"
                                                                 itemprop="datePublished"><?php echo $rows->date; ?></p>
                            </div>
                            <div style="display: table-cell; padding: 10px 0 10px 20px;">
                                <a class="author-name" style="text-decoration: none;">
                                    <span class="author-ava"
                                          style="background-image: url(/asset/authors/evtushenko.jpg);"></span>
                                    Никита Евтушенко</a>
                            </div>
                        </div>
                    <? } ?>
                    <?php echo $rows->text; ?>
                    <br>
                    <div style="display:none;">
                        <?php $this->load->module('special')->showArti(); ?>
                    </div>
                    <div <?php if ((int)$rows->cat == 1) {
                        echo 'style="display:none"';
                    } ?> id="article_rating" class="complex-rating-wrapper text-center" itemprop="aggregateRating"
                         itemscope="" itemtype="http://schema.org/AggregateRating">
                        <div id="jk-raty" class="demo">Рейтинг статьи:<br/>
                        </div>
                        <script type="text/javascript">
                        //    $('#jk-raty').rater('/asset/assets/rating/send_raty_art.php');
                        </script>
                        <meta itemprop="ratingValue" content="<?php echo (int)$rows->ratval; ?>">
                        <meta itemprop="reviewCount" content='<?php echo (int)$rows->raterval; ?>'>
                        <meta itemprop="bestRating" content="5">
                        <meta itemprop="worstRating" content="0">
                        <meta itemprop="itemreviewed" content="<?php echo $rows->header; ?>">
                    </div>
              
                    <br>
                    <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
                    <script src="//yastatic.net/share2/share.js"></script>
                    <div class="question-wrapper">
                        <!--<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,viber,telegram"
                             data-counter="" style="padding: 0;height: 50px; width: 18%; padding-top: 10px;"></div> -->
                        <div class="question-form-wrapper"><span class="question-form-wrapper__text" >Остались вопросы? Оставьте контактные данные, и наши специалисты вам перезвонят.</span>
                            <form id="askq" class="question-form">
                                  <div class="question-input__wrapper">
                                       <div class="rowd question-form__item">
                                           <input type="text" name="naemm" id="name_q" class="require question-form__input" placeholder="Имя *">
                                        </div>
                                        
                                        <div class="rowd question-form__item">
                                             <input type="text" name="phone" id="phone_q" class="require phoneFormat question-form__input"
                                           placeholder="Телефон *">
                                        </div>
                                  </div>
                               
                                <div class="rowd question-form__item" style="margin-right: 0;">
                                    <button class="submit-ask question-form__btn question-form__btn--hover" name="submit">
                                        ОТПРАВИТЬ
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>


                          <div class="form_uni_blog">
                        <form method="POST"
                              action="https://cp.unisender.com/ru/subscribe?hash=66tg63zui6dmmfoeiynq93x4b5e7mo39d35ezy7cnut5zag1zfgio"
                              name="subscribtion_form" id="podpiskanewsblog">
                            <div class="subscribe-form-item subscribe-form-item--text first_line_subsc">Последние
                                новости и полезный контент о недвижимости на вашей почте!<br> Подпишитесь на нашу
                                рассылку и еженедельно получайте дайджест интересной информации.
                            </div>
                            <div class="second_line_subsc">
                                <div class="subscribe-form-item subscribe-form-item--input-string">
                                    <input class="subscribe-form-item__control subscribe-form-item__control--input-string"
                                           type="text" name="f_4448066" value="" placeholder="Имя">
                                </div>
                                <div class="subscribe-form-item subscribe-form-item--input-email">
                                    <input class="subscribe-form-item__control subscribe-form-item__control--input-email"
                                           type="text" name="email" value="" placeholder="E-mail*">
                                </div>
                                <div class="subscribe-form-item subscribe-form-item--btn-submit">
                                    <input class="subscribe-form-item__btn subscribe-form-item__btn--btn-submit"
                                           type="submit" value="Подписаться">
                                </div>
                            </div>
                            <div class="third_line_subsc">
                                <div class="subscribe-form-item subscribe-form-item--text confidential-subscr"><span
                                            style="font-family:Arial,Helvetica,sans-serif; font-size:12px">Я согласен с <a
                                                data-cke-saved-href="/privacy_policy/" target="_blank"
                                                href="/privacy_policy/">политикой    конфиденциальности</a></span>
                                    <br>
                                </div>
                            </div>
                            <input type="hidden" name="charset" value="UTF-8">
                            <input type="hidden" name="default_list_id" value="6866094">
                            <input type="hidden" name="overwrite" value="2">
                            <input type="hidden" name="is_v5" value="1">
                        </form>
                    </div>

                    <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,viber,telegram"
                             data-counter=""></div>
                </div>
                <div class="col-xs-4">
                    <div style="padding-left:20px; text-align: left;">
                        <?php $this->load->module('news')->limits(4); ?>
                        <div class="push-area">
                            <p class="text-push">Если Вам интересны новости недвижимости в СПб, получайте информацию на
                                рабочий стол.</p>
                            <button onclick="start()" class="push-button">Подписаться на Push-уведомления</button>
                        </div>


                    </div>
                </div>
                <!--<div id="hypercomments_widget"></div>-->
                <div id="disqus_thread"></div>
                <noscript>Пожалуйста, включите JavaScript, чтобы оставлять комментарии</a></noscript>
            </div>

        </div>
        <script type="text/javascript" src="https://m16.kv1.ru/api/sender.js"></script>
        <script>
            // @TODO MOVE
            $("#askq").on("submit", function(e){
                var form = $(this);
                e.preventDefault();

                            var error = 0;
                            if (!$("#name_q").val()){
                                error = 1;
                                $("#name_q").css("border-color", '#fb4b4b');
                            } else {
                                $("#name_q").css("border-color", '#c5c4c4');
                            }
                            
                            if (!$("#phone_q").val()){
                                error = 1;
                                $("#phone_q").css("border-color", '#fb4b4b');
                            } else {
                                $("#phone_q").css("border-color", '#c5c4c4');
                            }

                if (error == 0){
                    ycrm.send({
                        'name': $("#name_q").val(),
                        'phone': $("#phone_q").val(),
                        'message': 'Остались вопросы: ' + document.location.href
                    });
                    yaCounter29760432.reachGoal('OTPRAVIT_ZAYAV_ARTICLES');
                    $.growl.notice({ title: "Сообщение:", message: "<br>Спасибо.<br> Ваши данные отправлены." });
                }
            });
        </script>
        <!--
            <script type="text/javascript">
                _hcwp = window._hcwp || [];
                _hcwp.push({widget:"Stream", widget_id: 21825});
                (function() {
                if("HC_LOAD_INIT" in window)return;
                HC_LOAD_INIT = true;
                var lang = (navigator.language || navigator.systemLanguage || navigator.userLanguage || "en").substr(0, 2).toLowerCase();
                var hcc = document.createElement("script"); hcc.type = "text/javascript"; hcc.async = true;
                hcc.src = ("https:" == document.location.protocol ? "https" : "http")+"://w.hypercomments.com/widget/hc/21825/"+lang+"/widget.js";
                var s = document.getElementsByTagName("script")[0];
                s.parentNode.insertBefore(hcc, s.nextSibling);
                })();
            </script>
            -->
        <script>
            /**
             *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
             *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
            /*
            var disqus_config = function () {
            this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
            this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
            };
            */
            (function () { // DON'T EDIT BELOW THIS LINE
                var d = document, s = d.createElement('script');
                s.src = 'https://https-m16-estate-ru.disqus.com/embed.js';
                s.setAttribute('data-timestamp', +new Date());
                (d.head || d.body).appendChild(s);
            })();
        </script>
    </div>
    </div>
    </div>
</section>