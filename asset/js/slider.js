var buildingsFilter = function (options)
{
    var options=$.extend(
        {
            class               :   'buildings',
            costMin             :   0,
            costMax             :   10000,
            costCurrentMin      :   1000,
            costCurrentMax      :   5000,
            costSlider          :   null,
            costInputMin        :   null,
            costInputMax        :   null,
            costStep			:	0.01,
            costFix             :   1,
            costTextMin         :   null,
            costTextMax         :   null,
            stop                : function (){}
        }, options);

    this.options = options;


    this.init = function ()
    {
        var that=this;


        if(that.options.costSlider==null)
            alert('Comments "costSlider" not configured');

        if(that.options.costInputMin==null)
            alert('Comments "costInputMin" not configured');

        if(that.options.costInputMax==null)
            alert('Comments "costInputMax" not configured');

        $( that.options.costSlider).slider(
            {
                range: true,
                step: 0.01,
                min: that.toSlider(that.options.costMin),
                max: that.toSlider(that.options.costMax),
                values: [ that.toSlider(that.options.costCurrentMin), that.toSlider(that.options.costCurrentMax )],

                create: function( event, ui )
                {
                    //$(this).find('.ui-slider-handle:first').after('<span id="'+that.options.class+'-price-label-from" class="range-label range-label--price range-label--price-left"></span>');
                    //$(this).find('.ui-slider-handle:last').after('<span id="'+that.options.class+'-price-label-to" class="range-label range-label--price"></span>');
                    //$('#'+that.options.class+'-price-label-from').text(that.options.costCurrentMin + ' млн. руб.');
                    //$('#'+that.options.class+'-price-label-to').text(that.options.costCurrentMax + ' млн. руб.');
                },

                slide: function( event, ui )
                {
                    $( that.options.costInputMin ).val( that.fromSlider(ui.values[ 0 ] ));
                    $( that.options.costInputMax ).val( that.fromSlider(ui.values[ 1 ] ));
                    //$('#'+that.options.class+'-price-label-from').text( that.fromSlider(ui.values[ 0 ] ) + ' млн. руб.');
                    //$('#'+that.options.class+'-price-label-to').text( that.fromSlider(ui.values[ 1 ] ) + ' млн. руб.');

                },
                stop:function( event, ui )
                {
                    //var val	=	ui.value;
                    //this.setupSliderData(val);
                    that.options.stop();
                }
            });
        $( that.options.costInputMin ).val( that.fromSlider($( that.options.costSlider ).slider( "values", 0 )));
        $( that.options.costInputMax ).val( that.fromSlider($( that.options.costSlider ).slider( "values", 1 )));

        $( that.options.costInputMax ).change(function()
        {
            if (eval($(this).val())<=eval($( that.options.costInputMin ).val()))
            {
                $(that.options.costSlider).slider( "values", 1, that.toSlider(eval($( that.options.costInputMin ).val())+1) );
                $(this).val(eval($( that.options.costInputMin ).val())+1);
            }
            else
                $(that.options.costSlider).slider( "values", 1, that.toSlider($(this).val()));

            //that.options.stop();
        });

        $( that.options.costInputMin ).change(function()
        {
            if (eval($(this).val())>=eval($( that.options.costInputMax ).val()))
            {
                $(that.options.costSlider).slider( "values", 0, that.toSlider(eval($( that.options.costInputMax ).val())-1) );
                $(this).val(eval($( that.options.costInputMax ).val())-1);
            }
            else
                $(that.options.costSlider).slider( "values", 0, that.toSlider($(this).val()) );

            //that.options.stop();
        });

    };

    this.fromSlider=function(value)
    {
        if(value==0)
            return 0;
        var z = (Math.pow(3, value)).toFixed(this.options.costFix);
        var x = parseInt(z);
        var y = z - x;
        if (y == 0){
            return x;
        }
        else {
            return z;
        }
    }

    this.toSlider=function(value)
    {
        if(value==0)
            return 0;

        return Math.log(value) / Math.log(3);
    }

    this.init();
}


var headerBannerSlider = $("#light-slider").lightSlider({
    item: 1,
    slideMove: 1,
    autoWidth: true,
    loop:true,
    speed: 600,
    pause: 5000,
    pauseOnHover: true,
    auto: true,
    useCSS: true,
    pager: false,
    controls: false,
    onBeforeStart: function (el) { $('.header-top-banner').css('opacity',1) },

  });
  
  var timeout;
  $('.header-top-banner-wrapper').on('mouseenter', function () {
    headerBannerSlider.pause();
    clearTimeout(timeout);
  });
  $('.header-top-banner-wrapper').on('mouseleave', function () {
    timeout = setTimeout(function () {
      headerBannerSlider.play();
    }, 2000);

  });

  $('#back-slide').click(function () {
    headerBannerSlider.goToPrevSlide();
    headerBannerSlider.pause();
  });
  $('#next-slide').click(function () {
    headerBannerSlider.goToNextSlide();
    headerBannerSlider.pause();
  });

  var isDragging = false;
  $(".header-top-banner__item .top-banner-item__info,.header-top-banner__item .top-banner-item__img")
    .mousedown(function() {
        $(window).mousemove(function() {
            isDragging = true;
            $(window).unbind("mousemove");
        });
    })
    .mouseup(function() {
        var wasDragging = isDragging;
        isDragging = false;
        $(window).unbind("mousemove");
        if (!wasDragging) {
            window.location.href=$(this).closest('.header-top-banner__item').data('href');
        }
    });