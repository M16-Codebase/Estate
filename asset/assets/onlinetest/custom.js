

$(function() {
  $.mask.definitions['~'] = '[+-]';
  $('.phonemask').mask('+7 (999) 999-9999');
});


var params = getAllUrlParams().page;

if(/_/.test(params)) {

    params2 = params.split('_');
    var index = params2.indexOf("");
    if (index >= 0) {
        params2.splice(index, 1);
    }
    $(params2).each(function () {
        var param = this + '_';

        switch (param) {
            case 'ekonom_':
                $(".badges").append("<div class=\"badge\">Р­РєРѕРЅРѕРј-РєР»Р°СЃСЃ</div>");
                break;

            case 'comfort_':
                $(".badges").append("<div class=\"badge\">РљРѕРјС„РѕСЂС‚-РєР»Р°СЃСЃ</div>");
                break;

            case 'business_':
                $(".badges").append("<div class=\"badge\">Р‘РёР·РЅРµСЃ-РєР»Р°СЃСЃ</div>");
                break;

            case 'premium_':
                $(".badges").append("<div class=\"badge\">РџСЂРµРјРёСѓРј-РєР»Р°СЃСЃ</div>");
                break;


            case 'forinvestition_':
                $(".badges").append("<div class=\"badge\">Р”Р»СЏ РёРЅРІРµСЃС‚РёС†РёР№</div>");
                break;

            case 'forlive_':
                $(".badges").append("<div class=\"badge\">Р”Р»СЏ Р¶РёР·РЅРё</div>");
                break;

            default:
                console.log('default');
        }

    });

}

var update_url = 0;
if(update_url == 0) {
    console.log('url udated');

    var get_p = getAllUrlParams();
    console.log(get_p);

    $.each(get_p, function(key, value) {
        console.log('url page udate '+"key="+key);
        if(key == 'utm_term' || key == 'utm_content' || key == 'utm_campaign' || key == 'utm_medium' || key == 'utm_source' || key == '_openstat')
            return;

        var get_parameter = getAllUrlParams()[key];

        if(key == 'page') {

            $('.next_step').each(function () {

                console.log('url page udate '+"page="+get_parameter);

                var new_page = $(this).attr('href').replace("page=", "page="+get_parameter);
                $(this).attr('href', new_page);
            });

        }else {
            $('.next_step').each(function () {
                var new_page = $(this).attr('href')+"&"+key+"="+get_parameter;
                $(this).attr('href', new_page);
            });
        }

    });
    update_url += 1;

}else {
    console.log('url not updated');
}
function getAllUrlParams(url) {

    // get query string from url (optional) or window
    var queryString = url ? url.split('?')[1] : window.location.search.slice(1);

    // we'll store the parameters here
    var obj = {};

    // if query string exists
    if (queryString) {

        // stuff after # is not part of query string, so get rid of it
        queryString = queryString.split('#')[0];

        // split our query string into its component parts
        var arr = queryString.split('&');

        for (var i=0; i<arr.length; i++) {
            // separate the keys and the values
            var a = arr[i].split('=');

            // in case params look like: list[]=thing1&list[]=thing2
            var paramNum = undefined;
            var paramName = a[0].replace(/\[\d*\]/, function(v) {
                paramNum = v.slice(1,-1);
                return '';
            });

            // set parameter value (use 'true' if empty)
            var paramValue = typeof(a[1])==='undefined' ? true : a[1];

            // (optional) keep case consistent
            paramName = paramName.toLowerCase();
            paramValue = paramValue.toLowerCase();

            // if parameter name already exists
            if (obj[paramName]) {
                // convert value to array (if still string)
                if (typeof obj[paramName] === 'string') {
                    obj[paramName] = [obj[paramName]];
                }
                // if no array index number specified...
                if (typeof paramNum === 'undefined') {
                    // put the value on the end of the array
                    obj[paramName].push(paramValue);
                }
                // if array index number specified...
                else {
                    // put the value at that index number
                    obj[paramName][paramNum] = paramValue;
                }
            }
            // if param name doesn't exist yet, set it
            else {
                obj[paramName] = paramValue;
            }
        }
    }

    return obj;
}