isMobile = {
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
console.log(parseInt($(window).scrollTop()) + '---' + ($(document).height() - $(window).height()));
if (isMobile.any()) {
    document.addEventListener('DOMContentLoaded', function () {
        $.ajax({
            type: "POST",
            data: 'page=222',
            url: 'https://m16-estate.ru/asset/assets/php/mainparts.php',
            success: function (data) {
                $('#maincontent').append(data);
            },
            error: function (data) {
                console.log(data);
            }
        });
        $('#intcd').show();
        $('#spcd').show();
        setTimeout(function () {
            $('#loadervidos').hide();
            setSldr();
            setNews();
            setDpl();
        }, 2000);
    });
} else {
    setTimeout(function () {
        $('#intcd').fadeIn();
    }, 100);
    setTimeout(function () {
        $('#spcd').fadeIn();
    }, 100);
    $(window).on('load', function () {
        var page =2;
        $(window).scroll(function () {
            //console.log($(document).height() - $(window).height()-parseInt($(window).scrollTop()));
            if ((parseInt($(window).scrollTop()) == $(document).height() - $(window).height()) ||
                (parseInt($(window).scrollTop()) == $(document).height() - $(window).height() + 1) ||
                (parseInt($(window).scrollTop()) == $(document).height() - $(window).height() - 1)){
                if (page == 0) {
                    //
                } else {
                    if (page == 1) {
                        //
                    } else {
						structelem=0;
						console.log(page);
                        $.ajax({
                            type: "POST",
                            data: 'page=' + page,
                            url: '/asset/assets/php/mainparts.php',
                            success: function (data) {
                                $('#maincontent').append(data);
                            },
                            error: function (data) {
                                console.log(data);
                            }
                        });
                        if (page == 2) {
							shElem('div2');
							shElem('div3');
                        }
                        if (page == 3) {
							shElem('div4');
                        }
                        if (page == 4) {
                            setSldr();
                        }
                        if (page == 5) {
							setDpl();
                        }
                        if (page == 6) {
                            setNews();
							shElem('div7');
                        }
                        if (page == 7) {
							mapgen();
                        }
                        if (page == 8) {
							shElem('div9');
							$('#loadervidos').fadeOut();
                        }
                        page++;
                    }
                }
            }
        });
    });
}
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
function shElem(id){
	var tid=setInterval(function (){
	   if (document.getElementById(id)) {
		   $('#'+id).fadeIn();
		 clearInterval(tid);
	   }
	}, 500);
}

function setDpl() {
	var tid=setInterval(function (){
	   if (document.getElementById('div6')) {
			jQuery('.about-bxslider').bxSlider();
			$('.bx-prev').css('overflow', 'hidden');
		 clearInterval(tid);
	   }
	}, 700);
}

function setSldr() {
    var tid=setInterval(function (){
        if (document.getElementById('div5')) {
            var slider = $('#lightSlider').lightSlider({
                item: 1,
                adaptiveHeight: true,
                autoWidth: true,
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
            clearInterval(tid);
        }
    }, 300);

}

function setNews() {
	var tid=setInterval(function (){
	   if (document.getElementById('div7')) {
			document.getElementById('ne00').innerHTML = n1main;
			document.getElementById('ne10').innerHTML = n2main;
			document.getElementById('ne01').innerHTML = n3main;
			document.getElementById('ne11').innerHTML = n4main;
		 clearInterval(tid);
	   }
	}, 500);
}