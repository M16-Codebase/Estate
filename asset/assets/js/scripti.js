var isMobile = {
    Android: function () {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function () {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function () {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function () {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function () {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function () {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};
function mapgen(){
    var tid=setInterval(function (){
        if (document.getElementById('novlink')) {
            GenerateMapNov();
            setTimeout(function () {
                $('#novlink').fadeIn();
            }, 500);
            clearInterval(tid);
        }
    }, 1500);

}

function setDpl() {
    var tid=setInterval(function (){
        if (document.getElementById('div6')) {
            jQuery('.about-bxslider').bxSlider();
            $('.bx-prev').css('overflow', 'hidden');
            $('#div6').fadeIn();
            clearInterval(tid);
        }
    }, 600);
}

function setSldr() {
    let tid=setInterval(function (){
        if (document.getElementById('div5')) {
            var slider = $('#lightSlider').lightSlider({
                item: 1,
                adaptiveHeight: true,
                //autoWidth: true,
                slideMargin: 35,
                onSliderLoad: function () {
                    $('#lightSlider').removeClass('cS-hidden');
                }
            });
            $('#preview').click(function () {
                slider.goToPrevSlide();
            });
            $('#next').click(function () {
                slider.goToNextSlide();
            });
            $('#div5').fadeIn(5000);
            clearInterval(tid);
        }
    }, 300);

}

function setNews() {
    var tid = setInterval(function () {
        if (document.getElementById('div7')) {
            document.getElementById('ne00').innerHTML = n1main;
            document.getElementById('ne10').innerHTML = n2main;
            document.getElementById('ne01').innerHTML = n3main;
            document.getElementById('ne11').innerHTML = n4main;
            clearInterval(tid);
        }
    }, 500);
}
$(window).load(function() {
    let mobileDefinition=false;
    if(window.screen.availWidth<900){
        mobileDefinition=true;
    }
    if (isMobile.any() || mobileDefinition) {
        $('#load_content').load('/asset/assets/main_page/mobile_content.php', function() {
            setSldr();
            setDpl();
            setNews();
            mapgen();
        });
    }else {
        $('#load_content').load('/asset/assets/main_page/pc_content.php', function () {
            setSldr();
            setDpl();
            setNews();
            mapgen();
        });
    }
});


