jQuery.fn.rater = function(url, options) {
    if (url == null) return;
    var settings = {
        url: url,
        maxvalue: 5,
        curvalue: 0
    };
    if (options) {
        jQuery.extend(settings, options)
    };
    jQuery.extend(settings, {
        cancel: (settings.maxvalue > 1) ? true : false
    });
    var container = jQuery(this);
    jQuery.extend(container, {
        averageRating: settings.curvalue,
        url: settings.url
    });
    if (!settings.style || settings.style == null || settings.style == 'basic') {
        var raterwidth = settings.maxvalue * 25;
        var ratingparent = '<ul id="starr" class="star-rating" style="width:' + raterwidth + 'px">'
    }
    if (settings.style == 'small') {
        var raterwidth = settings.maxvalue * 10;
        var ratingparent = '<ul class="star-rating small-star" style="width:' + raterwidth + 'px">'
    }
    if (settings.style == 'inline') {
        var raterwidth = settings.maxvalue * 10;
        var ratingparent = '<span class="inline-rating"><ul class="star-rating small-star" style="width:' + raterwidth + 'px">'
    }
    container.append(ratingparent);
    var starWidth, starIndex, listitems = '';
    var curvalueWidth = Math.floor(100 / settings.maxvalue * settings.curvalue);
    for (var i = 0; i <= settings.maxvalue; i++) {
        if (i == 0) {
            listitems += '<li class="current-rating" style="width:' + curvalueWidth + '%;">' + settings.curvalue + '/' + settings.maxvalue + '</li>'
        } else {
            starWidth = Math.floor(100 / settings.maxvalue * i);
            starIndex = (settings.maxvalue - i) + 2;
            listitems += '<li class="star"><a id="ratlink" href="#' + i + '" title="' + i + '/' + settings.maxvalue + '" style="width:' + starWidth + '%;z-index:' + starIndex + '">' + i + '</a></li>'
        }
    }
    container.find('.star-rating').append(listitems);
    if (settings.maxvalue > 1) {
        container.append('<span class="star-rating-result"></span>')
    }


    jQuery.post(container.url, {
                "url": document.location.href
            }, function(response) {
                console.log(response);
                jQuery(container).find('.star-rating').children('.current-rating').css({
                width: (response) + '%'
            });
                $('#rtval').attr('content',response/20);
            });
    var tekuri=(document.location.href.substr(document.location.href.lastIndexOf('/') + 1));
    console.log(tekuri);
    if(typeof getCookie(tekuri) == "undefined"){
        console.log('set');
        document.cookie = tekuri+"=0";
    }else{
        if(getCookie(tekuri)==1){
            $( "#starr" ).attr("id", "starrr");
            container.children('.star-rating-result').html('Вы уже оценили');
        }
    }



    var stars = jQuery(container).find('.star-rating').children('.star');
    stars.click(function() {
        if(getCookie(tekuri)==1){
            return false
        }
        console.log(getCookie(tekuri));
        document.cookie = tekuri+"=1";
        $( "#starr" ).attr("id", "starrr");
        console.log(getCookie(tekuri));
        if (settings.maxvalue == 1) {
            settings.curvalue = (settings.curvalue == 0) ? 1 : 0;
            jQuery(container).find('.star-rating').children('.current-rating').css({
                width: (settings.curvalue * 100) + '%'
            });
            jQuery.post(container.url, {
                "rating": settings.curvalue
            });
            return false
        } else {
            settings.curvalue = stars.index(this) + 1;
            raterValue = jQuery(this).children('a')[0].href.split('#')[1];
            jQuery.post(container.url, {
                "rating": raterValue,
                "uri": document.location.href
            }, function(response) {
                container.children('.star-rating-result').html(response);
                jQuery(container).find('.star-rating').children('.current-rating').css({
                width: (settings.curvalue*20) + '%'});
            });
            return false
        }
        return true
    });
    return this
}
function getCookie(name) {
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}