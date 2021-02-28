jQuery.isSubstring = function(haystack, needle) {
    return haystack.indexOf(needle) !== -1;
};
var parami = window
            .location
            .search
            .replace('?','')
            .split('&')
            .reduce(
                function(p,e){
                    var a = e.split('=');
                    p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                    return p;
                },
                {}
            );
        var pfr=0;
        var pto=0;
        var sqf=0;
        var sqt=0;
    if (typeof parami['square_from'] !== 'undefined') {
        sqf=parami['square_from'];
    }else{
        sqf=$('#fSquare-from').val();
    }

    if (typeof parami['square_to'] !== 'undefined') {
        sqt=parami['square_to'];
    }else{
        sqt=$('#fSquare-to').val();
    }

    if (typeof parami['price_from'] !== 'undefined') {
        pfr=parami['price_from'];
    }else{
        pfr=$('#fPrice-from').val();
    }

    if (typeof parami['price_to'] !== 'undefined') {
        pto=parami['price_to'];
    }else{
        pto=$('#fPrice-to').val();
    }

function initialize() {
    ymaps.ready(function () {
        var contactsMap = new ymaps.Map('map-canvas', {
            center: [59.962744, 30.289130],
            zoom: 16,
            controls: ['zoomControl', 'fullscreenControl'],
            behaviors: ["drag", "dblClickZoom"]
        });
        var contactsPlacemark = new ymaps.Placemark([59.962744, 30.289130], {
            hintContent: ''
        }, {
            iconLayout: 'default#image',
            iconImageHref: '/asset/assets/img/address-marker.png',
            iconImageSize: [28, 42],
            iconImageOffset: [-14, -42]
        });
        contactsMap.geoObjects.add(contactsPlacemark);
        contactsMap.behaviors.enable('scrollZoom');
    });
}

function initializemap() {
    ymaps.ready(function () {
        var myMap = new ymaps.Map('map-contacts', {
            center: [59.962744, 30.289130],
            zoom: 16,
            controls: ['zoomControl'],
            behaviors: ["drag", "dblClickZoom"]
        });
        myPlacemark = new ymaps.Placemark([59.962744, 30.289130], {
            hintContent: ''
        }, {
            iconLayout: 'default#image',
            iconImageHref: '/asset/assets/img/address-marker.png',
            iconImageSize: [28, 42],
            iconImageOffset: [-14, -42]
        });
        myMap.geoObjects.add(myPlacemark);
    });
}

function initmap(){

}

function initializeCompMap(){

}
function setMap() {
    var points = [];
    var myMap = new ymaps.Map('nov-map', {
            center: [30.338731, 59.941115],
            zoom: 9,
            controls: ['zoomControl', 'fullscreenControl'],
            behaviors: ["drag","dblClickZoom"]
        }),
        MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
            '<div style="color: #D6E6EB; font-weight: bold; font-size: 12px;">$[properties.geoObjects.length]</div>'),
        clusterer = new ymaps.Clusterer({
            clusterIcons: [{
                href: '/asset/assets/img/clust.png',
                size: [46, 46],
                offset: [-23, -23]
            }],
            clusterIconContentLayout: MyIconContentLayout,
            openBalloonOnClick: false,
            groupByCoordinates: false,
            clusterDisableClickZoom: false,
            clusterHideIconOnBalloonOpen: false,
            geoObjectHideIconOnBalloonOpen: false

        }),
        getPointOptions = function () {
            return {
                iconLayout: 'default#image',
                iconImageHref: '/asset/assets/img/markerb.png',
                iconImageSize: [22, 28],
                iconImageOffset: [-11, -28],
                balloonCloseButton: false,
                balloonMinWidth: 215,
                balloonMaxWidth: 215,
                balloonLayout: myBalloonLayout,
                balloonContentLayout: myBalloonContentLayout,
                balloonContentBodyLayout: myBalloonContentBodyLayout,
                balloonCloseButtonLayout: myBalloonCloseButtonLayout,
                balloonShadow: false,
                balloonOffset: [10, -226],
                balloonShadowLayout: myBalloonShadowLayout,
                balloonPanelMaxMapArea: 0

            };
        },
        geoObjects = [];
    var myBalloonLayout = ymaps.templateLayoutFactory.createClass(
        '<div class="center-balloon-wrapper">' +
        '<div class="center-balloon-content-wrapper">' +
        '$[[options.contentLayout]]' +
        '<div class="center-balloon-close">$[[options.closeButtonLayout]]</div>'+
        '</div>' +
        '<div class="center-balloon-tale-wrapper"><div class="center-balloon-tale"></div></div>' +

        '</div>',
        {
            build: function () {
                this.constructor.superclass.build.call(this);
                this._$element = $('.center-balloon-wrapper', this.getParentElement());
                this.applyElementOffset();
                this._$element.find('.center-balloon-close')
                    .on('click', $.proxy(this.onCloseClick, this));
            },
            clear: function () {
                this._$element.find('.center-balloon-close')
                    .off('click');
                this.constructor.superclass.clear.call(this);
            },
            onSublayoutSizeChange: function () {
                myBalloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments);
                if(!this._isElement(this._$element)) {
                    return;
                }
                this.applyElementOffset();
                this.events.fire('shapechange');
            },
            applyElementOffset: function () {
                this._$element.css({
                    left: -(this._$element[0].offsetWidth / 2),
                    top: -(this._$element[0].offsetHeight / 2)
                });
            },
            onCloseClick: function (e) {
                e.preventDefault();
                this.events.fire('userclose');
            },
            getShape: function () {
                if(!this._isElement(this._$element)) {
                    return myBalloonLayout.superclass.getShape.call(this);
                }
                var position = this._$element.position();
                return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
                    [position.left, position.top], [
                        position.left + this._$element[0].offsetWidth,
                        position.top + this._$element[0].offsetHeight
                    ]
                ]));
            },
            _isElement: function (element) {
                return element && element[0];
            }
        }
    );

    var myBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
        '<div class="center-balloon-content">'+
        '$[[options.contentBodyLayout]]'
        +'</div>'
    );

    var myBalloonContentBodyLayout = ymaps.templateLayoutFactory.createClass(
        '<a href="{{properties.link}}" class="filter-result-item-map sm_search" data-category="SelectObject" data-label="buildings">'+
        '    <div class="img_fil"><img class="filter-result-item-img" src="{{properties.foto}}" alt="{{properties.name}}"></div>'+
        '        <div class="priceres">'+'$[properties.price]'+'</div>'+
        '        <div class="filter-result-item-body">'+
        '            <p class="filter-result-item-rayon">{{properties.rayon}}</p>'+
        '            <p class="filter-result-item-name">{{properties.name}}</p>'+
        '            <p class="filter-result-item-size">{{properties.srok}}</p>'+
        '            <div class="addbott"><div class="filter-result-item-address">{{properties.address}}'+
        '            {% if properties.metro %}<span class="filter-result-item-metro"><span>{{properties.metro}}</span>{% endif %}</span>'+
        '        </div></div>'+
        '        </div>'+
        '    </a>');
    var myBalloonShadowLayout = ymaps.templateLayoutFactory.createClass('');

    var myBalloonCloseButtonLayout = ymaps.templateLayoutFactory.createClass(
        '<img class="closes" src="/asset/assets/img/closemark.png" >'
    );
    var param = $('.buildings-form').serialize();
    $.ajax({
        type: 'POST',
        url: '/buildings/map',
        data: {param: param},
        dataType: 'json',
        success: function(data) {
            if(data){
                $.each(data, function(i, val){
                    points.push([val.lat, val.lng]);
                    geoObjects[i] = new ymaps.Placemark([val.lat, val.lng], {
                        name: val.name,
                        price: val.price,
                        foto: val.foto,
                        address: val.address,
                        rayon: val.rayon,
                        metro: val.metro,
                        link: val.link
                    }, getPointOptions());
                });
                clusterer.add(geoObjects);
                myMap.geoObjects.add(clusterer);
                myMap.setBounds(clusterer.getBounds(), {
                    checkZoomRange: true
                });
            }
        }
    });
    ymapsTouchScroll(myMap,true,true);
}
function GenerateMapNov(){
    $(".map-city").hide();
    $(".map-city-text").hide();
    $("#nov-map").show();

    if ($("#nov-map").length > 0){
        ymaps.ready(setMap());
    }
}


function initializeComplexMap() {
    var myLatlng = new google.maps.LatLng($('#complex-map').data('lat'), $('#complex-map').data('lng'));
    var mapOptions = {
        zoom: 14,
        center: myLatlng,
        disableDefaultUI: true
    };
    var map = new google.maps.Map(document.getElementById('complex-map'), mapOptions);

    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        title: $('#complex-map').data('title')
    });
}
/*
google.maps.event.addDomListener(window, 'load', initialize);
google.maps.event.addDomListener(window, 'load', initializemap);
google.maps.event.addDomListener(window, 'load', initializeComplexMap);
google.maps.event.addDomListener(window, 'load', initializeCompMap);
*/

$(document).ready(function() {

    window.unisender = {
        url: 'https://api.unisender.com/LANG/api/METHOD?format=FORMAT&api_key=KEY',
        methodAlias: 'METHOD',
        lang: 'ru',
        format: 'json',
        api_key: '5f36zqctha3jd4na8kc1q783iq9jihdh4hnfxtfy',
        params: {},
        prepareUrl: function (method) {
            var method = method || '';
            var url = this.url.replace(/METHOD/g, method);
            url = url.replace(/LANG/g, this.lang);
            url = url.replace(/KEY/g, this.api_key);
            url = url.replace(/FORMAT/g, this.format);
            return url;
        },
        prepareParams: function (params, paramsObject) {
            paramsObject = paramsObject || {};
            params = params || {};

            $.each(params, function(param, value) {
                paramsObject[param] = value;
            });
            return paramsObject;
        },
        subscribe: function(params, callBack) {
            var method = 'subscribe';
            dataLayer.push({event:'UA-events',eventCategory:'Форма',eventAction:'Подписка'});
            this.execute(method, callBack, params);
        },
        getLists: function(callBack) {
            callBack = callBack || ef();
            var method = 'getLists';
            this.execute(method, callBack);
        },
        execute: function(method, callBack, params) {
            callBack = callBack || this.ef();
            params = params || {};
            var data = this.prepareParams(params, this.params);
            var url = this.prepareUrl(method)
            $.ajax({
                url: url,
                type: "GET",
                data: data,
                error: function(xhr, error){
                    console.debug(xhr); console.debug(error);
                },
                success: function(data) {
                    callBack(data);
                }
            });
        },
        ef: function () {}
    };

    $('.about-solist a#excursion-link').click(function(){
        window.location.href = '/excursion';
    });

    var win_w = $(window).width();
    var wib = '-1280px';
    var wibi = '-640px';
    var navit = 9;
    if (win_w < 1280){
        var wib = '-980px';
        var wibi = '-490px';
        navit = 9;
    }

    $( window ).resize(function() {
        win_w = $(window).width();
        if (win_w < 1280){
            wib = '-980px';
            wibi = '-490px';
        }
        else {
            wib = '-1280px';
            wibi = '-640px';
        }
    });
    if ($("#map-canvas").length > 0){
        initialize();
    }
    //initializemap();

    $('body').on('click', '#show_address_map', function(e){
        e.preventDefault();
        $("#footer .transparent").hide("slow");
        $("#footer .closemap").show();
    });

    $('body').on('click', '#footer .closemap', function(e){
        e.preventDefault();
        $("#footer .transparent").show("slow");
        $("#footer .closemap").hide();
    });

    $('body').on('click', '.tabc', function(e){
        e.preventDefault();
        if (!$(this).hasClass("active")){
            var id = $(this).attr("id");
            var tab = id.split("selcont-");
            $(".tabc").removeClass("active");
            $(".filter-display").removeClass("active");
            $(this).addClass("active");
            $(".filtercont #cont-"+tab[1]).addClass("active");
        }
    });

    var map_complex = null;

if ($(".mapcomplex").length > 0){

        var bounds = new google.maps.LatLngBounds();
        var lat = $(".mapcomplex").attr("data-lat");
        var lng = $(".mapcomplex").attr("data-lng");
        var myLatlng = new google.maps.LatLng(lat, lng);
        var mapOptions = {
            zoom: 13,
            disableDefaultUI: false,
            center: myLatlng,
            scrollwheel: false,
            zoomControl: true,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL
            }
        };
        var styles =
            [
                {
                    "featureType": "landscape",
                    "stylers":
                        [
                            { "color": "#f1f1f1" }
                        ]
                },
                {
                    "featureType": "water",
                    "stylers":
                        [
                            { "color": "#eae5e5" }
                        ]
                },
                {
                    "featureType": "landscape.man_made",
                    "elementType": "geometry.stroke",
                    "stylers":
                        [
                            { "color": "#444444" }
                        ]
                },
                {
                    "stylers":
                        [
                            { "lightness": 31 },
                            { "saturation": -100 },
                            { "gamma": 0.7 }
                        ]
                }
            ];
        var styledMap = new google.maps.StyledMapType(styles, {name: "Styled Map"});


        map_complex = new google.maps.Map(document.getElementById('mapcomplex'), mapOptions);
        map_complex.mapTypes.set('map_style', styledMap);
        map_complex.setMapTypeId('map_style');

        var mark = new google.maps.Marker({
            position: new google.maps.LatLng(lat, lng),
            map: map_complex,
            icon: '/asset/assets/img/markerb.png',
            title: ''
        });
    }



    //$('#mapresale-static').on('click', GenerateMap);

    $(".map-city, #resale").click(GenerateMapRes);
    $(".map-city-text, #resaletext").click(GenerateMapRes);
    $(".map-city, #residential").click(GenerateMapResidential);
    $(".map-city-text, #residentialtext").click(GenerateMapResidential);
    $(".map-city, #exclusive").click(GenerateMapExclusive);
    $(".map-city-text, #exclusivetext").click(GenerateMapExclusive);
    $(".map-city, #com").click(GenerateMapCom);
    $(".map-city-text, #comtext").click(GenerateMapCom);
    $(".map-city, #arenda").click(GenerateMapRent);
    $(".map-city-text, #arendatext").click(GenerateMapRent);
    $(".map-city, #abroad").click(GenerateMapAbroad);
    $(".map-city-text, #abroadtext").click(GenerateMapAbroad);
    $('#url-static').on('click', GenerateMap);
    $('.map-city').on('click', GenerateMapNov);
	$('.onmap').on('click', GenerateMapNov);
    $('.map-city-text').on('click', GenerateMapNov);
    $( document ).ready(function() {
		if(document.location.href=='https://m16-estate.ru/newindex'){
			//GenerateMapNov();
		}
    });
    console.log('maps');

function GenerateMapRent(){
       $(".map-city").hide();
       $(".map-city-text").hide();
       $(".map-buildings").show();

       if ($("#arenda-map").length > 0){
        ymaps.ready(function () {
            var points = [];
            var myMap = new ymaps.Map('arenda-map', {
                    center: [30.338731, 59.941115],
                    zoom: 9,
                    controls: ['zoomControl', 'fullscreenControl'],
                    behaviors: ["drag", "dblClickZoom"]
                }),
                MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div style="color: #D6E6EB; font-weight: bold; font-size: 12px;">$[properties.geoObjects.length]</div>'),
                clusterer = new ymaps.Clusterer({
                    clusterIcons: [{
                        href: '/asset/assets/img/clust.png',
                        size: [46, 46],
                        offset: [-23, -23]
                    }],
                    clusterIconContentLayout: MyIconContentLayout,
                    openBalloonOnClick: false,
                    groupByCoordinates: false,
                    clusterDisableClickZoom: false,
                    clusterHideIconOnBalloonOpen: false,
                    geoObjectHideIconOnBalloonOpen: false

                }),
                getPointOptions = function () {
                    return {
                        iconLayout: 'default#image',
                        iconImageHref: '/asset/assets/img/markerb.png',
                        iconImageSize: [22, 28],
                        iconImageOffset: [-11, -28],
                        balloonCloseButton: false,
                        balloonMinWidth: 215,
                        balloonMaxWidth: 215,
                        balloonLayout: myBalloonLayout,
                        balloonContentLayout: myBalloonContentLayout,
                        balloonContentBodyLayout: myBalloonContentBodyLayout,
                        balloonCloseButtonLayout: myBalloonCloseButtonLayout,
                        balloonShadow: false,
                        balloonOffset: [10, -226],
                        balloonShadowLayout: myBalloonShadowLayout,
                        balloonPanelMaxMapArea: 0

                    };
                },
                geoObjects = [];
            var myBalloonLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-wrapper">' +
                    '<div class="center-balloon-content-wrapper">' +
                    '$[[options.contentLayout]]' +
                    '<div class="center-balloon-close">$[[options.closeButtonLayout]]</div>'+
                    '</div>' +
                    '<div class="center-balloon-tale-wrapper"><div class="center-balloon-tale"></div></div>' +

                    '</div>',
                {
                    build: function () {
                        this.constructor.superclass.build.call(this);
                        this._$element = $('.center-balloon-wrapper', this.getParentElement());
                        this.applyElementOffset();
                        this._$element.find('.center-balloon-close')
                            .on('click', $.proxy(this.onCloseClick, this));
                    },
                    clear: function () {
                        this._$element.find('.center-balloon-close')
                            .off('click');
                        this.constructor.superclass.clear.call(this);
                    },
                    onSublayoutSizeChange: function () {
                        myBalloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments);
                        if(!this._isElement(this._$element)) {
                            return;
                        }
                        this.applyElementOffset();
                        this.events.fire('shapechange');
                    },
                    applyElementOffset: function () {
                        this._$element.css({
                            left: -(this._$element[0].offsetWidth / 2),
                            top: -(this._$element[0].offsetHeight / 2)
                        });
                    },
                    onCloseClick: function (e) {
                        e.preventDefault();
                        this.events.fire('userclose');
                    },
                    getShape: function () {
                        if(!this._isElement(this._$element)) {
                            return myBalloonLayout.superclass.getShape.call(this);
                        }
                        var position = this._$element.position();
                        return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
                            [position.left, position.top], [
                                position.left + this._$element[0].offsetWidth,
                                position.top + this._$element[0].offsetHeight
                            ]
                        ]));
                    },
                    _isElement: function (element) {
                        return element && element[0];
                    }
                }
            );

            var myBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-content">'+
                    '$[[options.contentBodyLayout]]'
                    +'</div>'
            );

            var myBalloonContentBodyLayout = ymaps.templateLayoutFactory.createClass(
                '<a href="{{properties.link}}" class="filter-result-item-map sm_search" data-category="SelectObject" data-label="buildings">'+
                    '    <div class="img_fil"><img class="filter-result-item-img" src="{{properties.foto}}" alt="{{properties.name}}"></div>'+
                    '        <div class="priceres">'+'$[properties.price]'+'</div>'+
                    '        <div class="filter-result-item-body">'+
                    '            <p class="filter-result-item-rayon">{{properties.rayon}}</p>'+
                    '            <p class="filter-result-item-name">{{properties.name}}</p>'+
                    '            <p class="filter-result-item-size">{{properties.srok}}</p>'+
                    '            <div class="addbott"><div class="filter-result-item-address">{{properties.address}}'+
                    '            {% if properties.metro %}<span class="filter-result-item-metro"><span>{{properties.metro}}</span>{% endif %}</span>'+
                    '        </div></div>'+
                    '        </div>'+
                    '    </a>');
            var myBalloonShadowLayout = ymaps.templateLayoutFactory.createClass('');

            var myBalloonCloseButtonLayout = ymaps.templateLayoutFactory.createClass(
                '<img class="closes" src="/asset/assets/img/closemark.png" >'
            );
            var param = $('.arenda-form').serialize();
            $.ajax({
                type: 'POST',
                url: '/arenda/map',
                data: {param: param},
                dataType: 'json',
                success: function(data) {
                    if(data){
                        $.each(data, function(i, val){
                            points.push([val.lat, val.lng]);
                            geoObjects[i] = new ymaps.Placemark([val.lat, val.lng], {
                                name: val.name,
                                price: val.price,
                                foto: val.foto,
                                address: val.address,
                                rayon: val.rayon,
                                metro: val.metro,
                                link: val.link
                            }, getPointOptions());
                        });
                        clusterer.add(geoObjects);
                        myMap.geoObjects.add(clusterer);
                        myMap.setBounds(clusterer.getBounds(), {
                            checkZoomRange: true
                        });
                    }
                }
            });
        });
    }
};

function GenerateMapAbroad(){
       $(".map-city").hide();
       $(".map-city-text").hide();
       $(".map-buildings").show();

       if ($("#abroad-map").length > 0){
        ymaps.ready(function () {
            var points = [];
            var myMap = new ymaps.Map('abroad-map', {
                    center: [30.338731, 59.941115],
                    zoom: 9,
                    controls: ['zoomControl', 'fullscreenControl'],
                    behaviors: ["drag", "dblClickZoom"]
                }),
                MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div style="color: #D6E6EB; font-weight: bold; font-size: 12px;">$[properties.geoObjects.length]</div>'),
                clusterer = new ymaps.Clusterer({
                    clusterIcons: [{
                        href: '/asset/assets/img/clust.png',
                        size: [46, 46],
                        offset: [-23, -23]
                    }],
                    clusterIconContentLayout: MyIconContentLayout,
                    openBalloonOnClick: false,
                    groupByCoordinates: false,
                    clusterDisableClickZoom: false,
                    clusterHideIconOnBalloonOpen: false,
                    geoObjectHideIconOnBalloonOpen: false

                }),
                getPointOptions = function () {
                    return {
                        iconLayout: 'default#image',
                        iconImageHref: '/asset/assets/img/markerb.png',
                        iconImageSize: [22, 28],
                        iconImageOffset: [-11, -28],
                        balloonCloseButton: false,
                        balloonMinWidth: 215,
                        balloonMaxWidth: 215,
                        balloonLayout: myBalloonLayout,
                        balloonContentLayout: myBalloonContentLayout,
                        balloonContentBodyLayout: myBalloonContentBodyLayout,
                        balloonCloseButtonLayout: myBalloonCloseButtonLayout,
                        balloonShadow: false,
                        balloonOffset: [10, -226],
                        balloonShadowLayout: myBalloonShadowLayout,
                        balloonPanelMaxMapArea: 0

                    };
                },
                geoObjects = [];
            var myBalloonLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-wrapper">' +
                    '<div class="center-balloon-content-wrapper">' +
                    '$[[options.contentLayout]]' +
                    '<div class="center-balloon-close">$[[options.closeButtonLayout]]</div>'+
                    '</div>' +
                    '<div class="center-balloon-tale-wrapper"><div class="center-balloon-tale"></div></div>' +

                    '</div>',
                {
                    build: function () {
                        this.constructor.superclass.build.call(this);
                        this._$element = $('.center-balloon-wrapper', this.getParentElement());
                        this.applyElementOffset();
                        this._$element.find('.center-balloon-close')
                            .on('click', $.proxy(this.onCloseClick, this));
                    },
                    clear: function () {
                        this._$element.find('.center-balloon-close')
                            .off('click');
                        this.constructor.superclass.clear.call(this);
                    },
                    onSublayoutSizeChange: function () {
                        myBalloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments);
                        if(!this._isElement(this._$element)) {
                            return;
                        }
                        this.applyElementOffset();
                        this.events.fire('shapechange');
                    },
                    applyElementOffset: function () {
                        this._$element.css({
                            left: -(this._$element[0].offsetWidth / 2),
                            top: -(this._$element[0].offsetHeight / 2)
                        });
                    },
                    onCloseClick: function (e) {
                        e.preventDefault();
                        this.events.fire('userclose');
                    },
                    getShape: function () {
                        if(!this._isElement(this._$element)) {
                            return myBalloonLayout.superclass.getShape.call(this);
                        }
                        var position = this._$element.position();
                        return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
                            [position.left, position.top], [
                                position.left + this._$element[0].offsetWidth,
                                position.top + this._$element[0].offsetHeight
                            ]
                        ]));
                    },
                    _isElement: function (element) {
                        return element && element[0];
                    }
                }
            );

            var myBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-content">'+
                    '$[[options.contentBodyLayout]]'
                    +'</div>'
            );

            var myBalloonContentBodyLayout = ymaps.templateLayoutFactory.createClass(
                '<a href="{{properties.link}}" class="filter-result-item-map sm_search" data-category="SelectObject" data-label="buildings">'+
                    '    <div class="img_fil"><img class="filter-result-item-img" src="{{properties.foto}}" alt="{{properties.name}}"></div>'+
                    '        <div class="priceres">'+'$[properties.price]'+'</div>'+
                    '        <div class="filter-result-item-body">'+
                    '            <p class="filter-result-item-rayon">{{properties.rayon}}</p>'+
                    '            <p class="filter-result-item-name">{{properties.name}}</p>'+
                    '            <p class="filter-result-item-size">{{properties.srok}}</p>'+
                    '            <div class="addbott"><div class="filter-result-item-address">{{properties.address}}'+
                    '            {% if properties.metro %}<span class="filter-result-item-metro"><span>{{properties.metro}}</span>{% endif %}</span>'+
                    '        </div></div>'+
                    '        </div>'+
                    '    </a>');
            var myBalloonShadowLayout = ymaps.templateLayoutFactory.createClass('');

            var myBalloonCloseButtonLayout = ymaps.templateLayoutFactory.createClass(
                '<img class="closes" src="/asset/assets/img/closemark.png" >'
            );
            var param = $('.abroad-form').serialize();
            $.ajax({
                type: 'POST',
                url: '/abroad/map',
                data: {param: param},
                dataType: 'json',
                success: function(data) {
                    if(data){
                        $.each(data, function(i, val){
                            points.push([val.lat, val.lng]);
                            geoObjects[i] = new ymaps.Placemark([val.lat, val.lng], {
                                name: val.name,
                                price: val.price,
                                foto: val.foto,
                                address: val.address,
                                rayon: val.rayon,
                                metro: val.metro,
                                link: val.link
                            }, getPointOptions());
                        });
                        clusterer.add(geoObjects);
                        myMap.geoObjects.add(clusterer);
                        myMap.setBounds(clusterer.getBounds(), {
                            checkZoomRange: true
                        });
                    }
                }
            });
        });
    }
};

function GenerateMapCom(){
       $(".map-city").hide();
       $(".map-city-text").hide();
       $(".map-buildings").show();

       if ($("#com-map").length > 0){
        ymaps.ready(function () {
            var points = [];
            var myMap = new ymaps.Map('com-map', {
                    center: [30.338731, 59.941115],
                    zoom: 12,
                    controls: ['zoomControl', 'fullscreenControl'],
                    behaviors: ["drag", "dblClickZoom"]
                }),
                MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div style="color: #D6E6EB; font-weight: bold; font-size: 12px;">$[properties.geoObjects.length]</div>'),
                clusterer = new ymaps.Clusterer({
                    clusterIcons: [{
                        href: '/asset/assets/img/clust.png',
                        size: [46, 46],
                        offset: [-23, -23]
                    }],
                    clusterIconContentLayout: MyIconContentLayout,
                    openBalloonOnClick: false,
                    groupByCoordinates: false,
                    clusterDisableClickZoom: false,
                    clusterHideIconOnBalloonOpen: false,
                    geoObjectHideIconOnBalloonOpen: false

                }),
                getPointOptions = function () {
                    return {
                        iconLayout: 'default#image',
                        iconImageHref: '/asset/assets/img/markerb.png',
                        iconImageSize: [22, 28],
                        iconImageOffset: [-11, -28],
                        balloonCloseButton: false,
                        balloonMinWidth: 215,
                        balloonMaxWidth: 215,
                        balloonLayout: myBalloonLayout,
                        balloonContentLayout: myBalloonContentLayout,
                        balloonContentBodyLayout: myBalloonContentBodyLayout,
                        balloonCloseButtonLayout: myBalloonCloseButtonLayout,
                        balloonShadow: false,
                        balloonOffset: [10, -226],
                        balloonShadowLayout: myBalloonShadowLayout,
                        balloonPanelMaxMapArea: 0

                    };
                },
                geoObjects = [];
            var myBalloonLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-wrapper">' +
                    '<div class="center-balloon-content-wrapper">' +
                    '$[[options.contentLayout]]' +
                    '<div class="center-balloon-close">$[[options.closeButtonLayout]]</div>'+
                    '</div>' +
                    '<div class="center-balloon-tale-wrapper"><div class="center-balloon-tale"></div></div>' +

                    '</div>',
                {
                    build: function () {
                        this.constructor.superclass.build.call(this);
                        this._$element = $('.center-balloon-wrapper', this.getParentElement());
                        this.applyElementOffset();
                        this._$element.find('.center-balloon-close')
                            .on('click', $.proxy(this.onCloseClick, this));
                    },
                    clear: function () {
                        this._$element.find('.center-balloon-close')
                            .off('click');
                        this.constructor.superclass.clear.call(this);
                    },
                    onSublayoutSizeChange: function () {
                        myBalloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments);
                        if(!this._isElement(this._$element)) {
                            return;
                        }
                        this.applyElementOffset();
                        this.events.fire('shapechange');
                    },
                    applyElementOffset: function () {
                        this._$element.css({
                            left: -(this._$element[0].offsetWidth / 2),
                            top: -(this._$element[0].offsetHeight / 2)
                        });
                    },
                    onCloseClick: function (e) {
                        e.preventDefault();
                        this.events.fire('userclose');
                    },
                    getShape: function () {
                        if(!this._isElement(this._$element)) {
                            return myBalloonLayout.superclass.getShape.call(this);
                        }
                        var position = this._$element.position();
                        return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
                            [position.left, position.top], [
                                position.left + this._$element[0].offsetWidth,
                                position.top + this._$element[0].offsetHeight
                            ]
                        ]));
                    },
                    _isElement: function (element) {
                        return element && element[0];
                    }
                }
            );

            var myBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-content">'+
                    '$[[options.contentBodyLayout]]'
                    +'</div>'
            );

            var myBalloonContentBodyLayout = ymaps.templateLayoutFactory.createClass(
                '<a href="{{properties.link}}" class="filter-result-item-map sm_search" data-category="SelectObject" data-label="buildings">'+
                    '    <div class="img_fil"><img class="filter-result-item-img" src="{{properties.foto}}" alt="{{properties.name}}"></div>'+
                    '        <div class="priceres">'+'$[properties.price]'+'</div>'+
                    '        <div class="filter-result-item-body">'+
                    '            {% if properties.rayon %}<p class="filter-result-item-rayon">{{properties.rayon}}</p>{% endif %}'+
                    '            <p class="filter-result-item-name">{{properties.name}}</p>'+
                    '            {% if properties.srok %}<p class="filter-result-item-size">{{properties.srok}}</p>{% endif %}'+
                    '            <div class="addbott"><div class="filter-result-item-address">{{properties.address}}'+
                    '            {% if properties.metro %}<span class="filter-result-item-metro"><span>{{properties.metro}}</span>{% endif %}</span>'+
                    '        </div></div>'+
                    '        </div>'+
                    '    </a>');
            var myBalloonShadowLayout = ymaps.templateLayoutFactory.createClass('');

            var myBalloonCloseButtonLayout = ymaps.templateLayoutFactory.createClass(
                '<img class="closes" src="/asset/assets/img/closemark.png" >'
            );
            var param = $('.commercial-form').serialize();
            $.ajax({
                type: 'POST',
                url: '/commercial/map',
                data: {param: param},
                dataType: 'json',
                success: function(data) {
                    if(data){
                        $.each(data, function(i, val){
                            points.push([val.lat, val.lng]);
                            geoObjects[i] = new ymaps.Placemark([val.lat, val.lng], {
                                name: val.name,
                                price: val.price,
                                foto: val.foto,
                                address: val.address,
                                rayon: val.rayon,
                                metro: val.metro,
                                link: val.link
                            }, getPointOptions());
                        });
                        clusterer.add(geoObjects);
                        myMap.geoObjects.add(clusterer);
                        myMap.setBounds(clusterer.getBounds(), {
                            checkZoomRange: true
                        });
                    }
                }
            });
        });
    }
};

 function GenerateMapExclusive(){
       $(".map-city").hide();
       $(".map-city-text").hide();
       $(".map-buildings").show();

       if ($("#exclusive-map").length > 0){
        ymaps.ready(function () {
            var points = [];
            var myMap = new ymaps.Map('exclusive-map', {
                    center: [30.338731, 59.941115],
                    zoom: 8,
                    controls: ['zoomControl', 'fullscreenControl'],
                    behaviors: ["drag", "dblClickZoom"]
                }),

                MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div style="color: #D6E6EB; font-weight: bold; font-size: 12px;">$[properties.geoObjects.length]</div>'),
                clusterer = new ymaps.Clusterer({
                    clusterIcons: [{
                        href: '/asset/assets/img/clust.png',
                        size: [46, 46],
                        offset: [-23, -23]
                    }],
                    clusterIconContentLayout: MyIconContentLayout,
                    openBalloonOnClick: false,
                    groupByCoordinates: false,
                    clusterDisableClickZoom: false,
                    clusterHideIconOnBalloonOpen: false,
                    geoObjectHideIconOnBalloonOpen: false

                }),
                getPointOptions = function () {
                    return {
                        iconLayout: 'default#image',
                        iconImageHref: '/asset/assets/img/markerb.png',
                        iconImageSize: [22, 28],
                        iconImageOffset: [-11, -28],
                        balloonCloseButton: false,
                        balloonMinWidth: 215,
                        balloonMaxWidth: 215,
                        balloonLayout: myBalloonLayout,
                        balloonContentLayout: myBalloonContentLayout,
                        balloonContentBodyLayout: myBalloonContentBodyLayout,
                        balloonCloseButtonLayout: myBalloonCloseButtonLayout,
                        balloonShadow: false,
                        balloonOffset: [10, -226],
                        balloonShadowLayout: myBalloonShadowLayout,
                        balloonPanelMaxMapArea: 0

                    };
                },

                geoObjects = [];




            var myBalloonLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-wrapper">' +
                    '<div class="center-balloon-content-wrapper">' +
                    '$[[options.contentLayout]]' +
                    '<div class="center-balloon-close">$[[options.closeButtonLayout]]</div>'+
                    '</div>' +
                    '<div class="center-balloon-tale-wrapper"><div class="center-balloon-tale"></div></div>' +

                    '</div>',
                {
                    build: function () {
                        this.constructor.superclass.build.call(this);
                        this._$element = $('.center-balloon-wrapper', this.getParentElement());
                        this.applyElementOffset();
                        this._$element.find('.center-balloon-close')
                            .on('click', $.proxy(this.onCloseClick, this));
                    },
                    clear: function () {
                        this._$element.find('.center-balloon-close')
                            .off('click');
                        this.constructor.superclass.clear.call(this);
                    },
                    onSublayoutSizeChange: function () {
                        myBalloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments);
                        if(!this._isElement(this._$element)) {
                            return;
                        }
                        this.applyElementOffset();
                        this.events.fire('shapechange');
                    },
                    applyElementOffset: function () {
                        this._$element.css({
                            left: -(this._$element[0].offsetWidth / 2),
                            top: -(this._$element[0].offsetHeight / 2)
                        });
                    },
                    onCloseClick: function (e) {
                        e.preventDefault();
                        this.events.fire('userclose');
                    },
                    getShape: function () {
                        if(!this._isElement(this._$element)) {
                            return myBalloonLayout.superclass.getShape.call(this);
                        }
                        var position = this._$element.position();
                        return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
                            [position.left, position.top], [
                                position.left + this._$element[0].offsetWidth,
                                position.top + this._$element[0].offsetHeight
                            ]
                        ]));
                    },
                    _isElement: function (element) {
                        return element && element[0];
                    }
                }
            );

            var myBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-content">'+
                    '$[[options.contentBodyLayout]]'
                    +'</div>'
            );

            var myBalloonContentBodyLayout = ymaps.templateLayoutFactory.createClass(
                '<a href="{{properties.link}}" class="filter-result-item-map sm_search" data-category="SelectObject" data-label="buildings">'+
                    '    <div class="img_fil"><img class="filter-result-item-img" src="{{properties.foto}}" alt="{{properties.name}}"></div>'+
                    '        <div class="priceres">'+'$[properties.price]'+'</div>'+
                    '        <div class="filter-result-item-body">'+
                    '            <p class="filter-result-item-rayon">{{properties.rayon}}</p>'+
                    '            <p class="filter-result-item-name">{{properties.name}}</p>'+
                    '            <p class="filter-result-item-size">{{properties.srok}}</p>'+
                    '            <div class="addbott"><div class="filter-result-item-address">{{properties.address}}'+
                    '            {% if properties.metro %}<span class="filter-result-item-metro"><span>{{properties.metro}}</span>{% endif %}</span>'+
                    '        </div></div>'+
                    '        </div>'+
                    '    </a>');
            var myBalloonShadowLayout = ymaps.templateLayoutFactory.createClass('');

            var myBalloonCloseButtonLayout = ymaps.templateLayoutFactory.createClass(
                '<img class="closes" src="/asset/assets/img/closemark.png" >'
            );
            var param = $('.exclusive-form').serialize();
            $.ajax({
                type: 'POST',
                url: '/exclusive/map',
                data: {param: param},
                dataType: 'json',
                success: function(data) {
                    if(data){
                        $.each(data, function(i, val){
                            points.push([val.lat, val.lng]);
                            geoObjects[i] = new ymaps.Placemark([val.lat, val.lng], {
                                name: val.name,
                                price: val.price,
                                foto: val.foto,
                                address: val.address,
                                rayon: val.rayon,
                                metro: val.metro,
                                link: val.link
                            }, getPointOptions());
                        });
                        clusterer.add(geoObjects);
                        myMap.geoObjects.add(clusterer);
                        myMap.setBounds(clusterer.getBounds(), {
                            checkZoomRange: true,
                            zoomMargin: 20
                        });
                    }
                }
            });
        });
    }
};
        function GenerateMapResidential(){
       $(".map-city").hide();
       $(".map-city-text").hide();
       $(".map-buildings").show();

       if ($("#resid-map").length > 0){
        ymaps.ready(function () {
            var points = [];
            var myMap = new ymaps.Map('resid-map', {
                    center: [30.338731, 59.941115],
                    zoom: 9,
                    controls: ['zoomControl', 'fullscreenControl'],
                    behaviors: ["drag", "dblClickZoom"]
                }),
                MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div style="color: #D6E6EB; font-weight: bold; font-size: 12px;">$[properties.geoObjects.length]</div>'),
                clusterer = new ymaps.Clusterer({
                    clusterIcons: [{
                        href: '/asset/assets/img/clust.png',
                        size: [46, 46],
                        offset: [-23, -23]
                    }],
                    clusterIconContentLayout: MyIconContentLayout,
                    openBalloonOnClick: false,
                    groupByCoordinates: false,
                    clusterDisableClickZoom: false,
                    clusterHideIconOnBalloonOpen: false,
                    geoObjectHideIconOnBalloonOpen: false

                }),
                getPointOptions = function () {
                    return {
                        iconLayout: 'default#image',
                        iconImageHref: '/asset/assets/img/markerb.png',
                        iconImageSize: [22, 28],
                        iconImageOffset: [-11, -28],
                        balloonCloseButton: false,
                        balloonMinWidth: 215,
                        balloonMaxWidth: 215,
                        balloonLayout: myBalloonLayout,
                        balloonContentLayout: myBalloonContentLayout,
                        balloonContentBodyLayout: myBalloonContentBodyLayout,
                        balloonCloseButtonLayout: myBalloonCloseButtonLayout,
                        balloonShadow: false,
                        balloonOffset: [10, -226],
                        balloonShadowLayout: myBalloonShadowLayout,
                        balloonPanelMaxMapArea: 0

                    };
                },
                geoObjects = [];
            var myBalloonLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-wrapper">' +
                    '<div class="center-balloon-content-wrapper">' +
                    '$[[options.contentLayout]]' +
                    '<div class="center-balloon-close">$[[options.closeButtonLayout]]</div>'+
                    '</div>' +
                    '<div class="center-balloon-tale-wrapper"><div class="center-balloon-tale"></div></div>' +

                    '</div>',
                {
                    build: function () {
                        this.constructor.superclass.build.call(this);
                        this._$element = $('.center-balloon-wrapper', this.getParentElement());
                        this.applyElementOffset();
                        this._$element.find('.center-balloon-close')
                            .on('click', $.proxy(this.onCloseClick, this));
                    },
                    clear: function () {
                        this._$element.find('.center-balloon-close')
                            .off('click');
                        this.constructor.superclass.clear.call(this);
                    },
                    onSublayoutSizeChange: function () {
                        myBalloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments);
                        if(!this._isElement(this._$element)) {
                            return;
                        }
                        this.applyElementOffset();
                        this.events.fire('shapechange');
                    },
                    applyElementOffset: function () {
                        this._$element.css({
                            left: -(this._$element[0].offsetWidth / 2),
                            top: -(this._$element[0].offsetHeight / 2)
                        });
                    },
                    onCloseClick: function (e) {
                        e.preventDefault();
                        this.events.fire('userclose');
                    },
                    getShape: function () {
                        if(!this._isElement(this._$element)) {
                            return myBalloonLayout.superclass.getShape.call(this);
                        }
                        var position = this._$element.position();
                        return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
                            [position.left, position.top], [
                                position.left + this._$element[0].offsetWidth,
                                position.top + this._$element[0].offsetHeight
                            ]
                        ]));
                    },
                    _isElement: function (element) {
                        return element && element[0];
                    }
                }
            );

            var myBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-content">'+
                    '$[[options.contentBodyLayout]]'
                    +'</div>'
            );

            var myBalloonContentBodyLayout = ymaps.templateLayoutFactory.createClass(
                '<a href="{{properties.link}}" class="filter-result-item-map sm_search" data-category="SelectObject" data-label="buildings">'+
                    '    <div class="img_fil"><img class="filter-result-item-img" src="{{properties.foto}}" alt="{{properties.name}}"></div>'+
                    '        <div class="priceres">'+'$[properties.price]'+'</div>'+
                    '        <div class="filter-result-item-body">'+
                    '            <p class="filter-result-item-rayon">{{properties.rayon}}</p>'+
                    '            <p class="filter-result-item-name">{{properties.name}}</p>'+
                    '            <p class="filter-result-item-size">{{properties.srok}}</p>'+
                    '            <div class="addbott"><div class="filter-result-item-address">{{properties.address}}'+
                    '            {% if properties.metro %}<span class="filter-result-item-metro"><span>{{properties.metro}}</span>{% endif %}</span>'+
                    '        </div></div>'+
                    '        </div>'+
                    '    </a>');
            var myBalloonShadowLayout = ymaps.templateLayoutFactory.createClass('');

            var myBalloonCloseButtonLayout = ymaps.templateLayoutFactory.createClass(
                '<img class="closes" src="/asset/assets/img/closemark.png" >'
            );
            var param = $('.residential-form').serialize();
            $.ajax({
                type: 'POST',
                url: '/residential/map',
                data: {param: param},
                dataType: 'json',
                success: function(data) {
                    if(data){
                        $.each(data, function(i, val){
                            points.push([val.lat, val.lng]);
                            geoObjects[i] = new ymaps.Placemark([val.lat, val.lng], {
                                name: val.name,
                                price: val.price,
                                foto: val.foto,
                                address: val.address,
                                rayon: val.rayon,
                                metro: val.metro,
                                link: val.link
                            }, getPointOptions());
                        });
                        clusterer.add(geoObjects);
                        myMap.geoObjects.add(clusterer);
                        myMap.setBounds(clusterer.getBounds(), {
                            checkZoomRange: true
                        });
                    }
                }
            });
        });
    }
};


        function GenerateMapRes(){
       $(".map-city").hide();
       $(".map-city-text").hide();
       $(".map-buildings").show();

       if ($("#res-map").length > 0){

        ymaps.ready(function () {
            var points = [];
            var myMap = new ymaps.Map('res-map', {
                    center: [30.338731, 59.941115],
                    zoom: 9,
                    controls: ['zoomControl', 'fullscreenControl'],
                    behaviors: ["drag", "dblClickZoom"]
                }),
                MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div style="color: #D6E6EB; font-weight: bold; font-size: 12px;">$[properties.geoObjects.length]</div>'),
                clusterer = new ymaps.Clusterer({
                    clusterIcons: [{
                        href: '/asset/assets/img/clust.png',
                        size: [46, 46],
                        offset: [-23, -23]
                    }],
                    clusterIconContentLayout: MyIconContentLayout,
                    openBalloonOnClick: false,
                    groupByCoordinates: false,
                    clusterDisableClickZoom: false,
                    clusterHideIconOnBalloonOpen: false,
                    geoObjectHideIconOnBalloonOpen: false

                }),
                getPointOptions = function () {
                    return {
                        iconLayout: 'default#image',
                        iconImageHref: '/asset/assets/img/markerb.png',
                        iconImageSize: [22, 28],
                        iconImageOffset: [-11, -28],
                        balloonCloseButton: false,
                        balloonMinWidth: 215,
                        balloonMaxWidth: 215,
                        balloonLayout: myBalloonLayout,
                        balloonContentLayout: myBalloonContentLayout,
                        balloonContentBodyLayout: myBalloonContentBodyLayout,
                        balloonCloseButtonLayout: myBalloonCloseButtonLayout,
                        balloonShadow: false,
                        balloonOffset: [10, -226],
                        balloonShadowLayout: myBalloonShadowLayout,
                        balloonPanelMaxMapArea: 0

                    };
                },
                geoObjects = [];
            var myBalloonLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-wrapper">' +
                    '<div class="center-balloon-content-wrapper">' +
                    '$[[options.contentLayout]]' +
                    '<div class="center-balloon-close">$[[options.closeButtonLayout]]</div>'+
                    '</div>' +
                    '<div class="center-balloon-tale-wrapper"><div class="center-balloon-tale"></div></div>' +

                    '</div>',
                {
                    build: function () {
                        this.constructor.superclass.build.call(this);
                        this._$element = $('.center-balloon-wrapper', this.getParentElement());
                        this.applyElementOffset();
                        this._$element.find('.center-balloon-close')
                            .on('click', $.proxy(this.onCloseClick, this));
                    },
                    clear: function () {
                        this._$element.find('.center-balloon-close')
                            .off('click');
                        this.constructor.superclass.clear.call(this);
                    },
                    onSublayoutSizeChange: function () {
                        myBalloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments);
                        if(!this._isElement(this._$element)) {
                            return;
                        }
                        this.applyElementOffset();
                        this.events.fire('shapechange');
                    },
                    applyElementOffset: function () {
                        this._$element.css({
                            left: -(this._$element[0].offsetWidth / 2),
                            top: -(this._$element[0].offsetHeight / 2)
                        });
                    },
                    onCloseClick: function (e) {
                        e.preventDefault();
                        this.events.fire('userclose');
                    },
                    getShape: function () {
                        if(!this._isElement(this._$element)) {
                            return myBalloonLayout.superclass.getShape.call(this);
                        }
                        var position = this._$element.position();
                        return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
                            [position.left, position.top], [
                                position.left + this._$element[0].offsetWidth,
                                position.top + this._$element[0].offsetHeight
                            ]
                        ]));
                    },
                    _isElement: function (element) {
                        return element && element[0];
                    }
                }
            );

            var myBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-content">'+
                    '$[[options.contentBodyLayout]]'
                    +'</div>'
            );

            var myBalloonContentBodyLayout = ymaps.templateLayoutFactory.createClass(
                '<a href="{{properties.link}}" class="filter-result-item-map sm_search" data-category="SelectObject" data-label="buildings">'+
                    '    <div class="img_fil"><img class="filter-result-item-img" src="{{properties.foto}}" alt="{{properties.name}}"></div>'+
                    '        <div class="priceres">'+'$[properties.price]'+'</div>'+
                    '        <div class="filter-result-item-body">'+
                    '            <p class="filter-result-item-rayon">{{properties.rayon}}</p>'+
                    '            <p class="filter-result-item-name">{{properties.name}}</p>'+
                    '            <p class="filter-result-item-size">{{properties.srok}}</p>'+
                    '            <div class="addbott"><div class="filter-result-item-address">{{properties.address}}'+
                    '            {% if properties.metro %}<span class="filter-result-item-metro"><span>{{properties.metro}}</span>{% endif %}</span>'+
                    '        </div></div>'+
                    '        </div>'+
                    '    </a>');
            var myBalloonShadowLayout = ymaps.templateLayoutFactory.createClass('');

            var myBalloonCloseButtonLayout = ymaps.templateLayoutFactory.createClass(
                '<img class="closes" src="/asset/assets/img/closemark.png" >'
            );
            var param = $('.resale-form').serialize();
            $.ajax({
                type: 'POST',
                url: '/resale/map',
                data: {param: param},
                dataType: 'json',
                success: function(data) {
                    if(data){
                        $.each(data, function(i, val){
                            points.push([val.lat, val.lng]);
                            geoObjects[i] = new ymaps.Placemark([val.lat, val.lng], {
                                name: val.name,
                                price: val.price,
                                foto: val.foto,
                                address: val.address,
                                rayon: val.rayon,
                                metro: val.metro,
                                link: val.link
                            }, getPointOptions());
                        });
                        clusterer.add(geoObjects);
                        myMap.geoObjects.add(clusterer);
                        myMap.setBounds(clusterer.getBounds(), {
                            checkZoomRange: true
                        });
                    }
                }
            });
        });
    }
};





     function GenerateMap(){
     $(".mapresale-static").removeClass('mapresale-static').addClass('mapresale').attr("id","mapresale");
     $(".mapresale img").hide();
     $("#url-static").hide();
     var map_resale;
        var lat = $(".mapresale").attr("data-lat");
        var lng = $(".mapresale").attr("data-lng");
        ymaps.ready(function () {
            var myMap = new ymaps.Map('mapresale', {

                center: [lat, lng], // Москва
                zoom: 12,
                controls: ['smallMapDefaultSet']
            });

            myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
                hintContent: ''
            }, {
                // Опции.
                // Необходимо указать данный тип макета.
                iconLayout: 'default#image',
                // Своё изображение иконки метки.
                iconImageHref: '/asset/assets/img/markerb.png',
                // Размеры метки.
                iconImageSize: [22, 28],
                // Смещение левого верхнего угла иконки относительно
                // её "ножки" (точки привязки).
                iconImageOffset: [-11, -28]
            });

            myMap.geoObjects.add(myPlacemark);

        });
   };

    if ($(".mapresale").length > 0){
        var map_resale;
        var lat = $(".mapresale").attr("data-lat");
        var lng = $(".mapresale").attr("data-lng");
        ymaps.ready(function () {
            var myMap = new ymaps.Map('mapresale', {

                center: [lat, lng], // Москва
                zoom: 12,
                controls: ['smallMapDefaultSet']
            });

            myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
                hintContent: ''
            }, {
                // Опции.
                // Необходимо указать данный тип макета.
                iconLayout: 'default#image',
                // Своё изображение иконки метки.
                iconImageHref: '/asset/assets/img/markerb.png',
                // Размеры метки.
                iconImageSize: [22, 28],
                // Смещение левого верхнего угла иконки относительно
                // её "ножки" (точки привязки).
                iconImageOffset: [-11, -28]
            });

            myMap.geoObjects.add(myPlacemark);

        });

    }
$(function () {
	var url = window.location.hash;
            if (url.match("#askLand")){
                $('.ask_popup').click();
            }
});
$(function () {
    var url = window.location.pathname;
            if (url.match("/o-kompanii")){
                jQuery(function($) {
            $(window).scroll(function(){
                if($(this).scrollTop()>90){
                    $('#header').addClass('top_nav');
                }
                else if ($(this).scrollTop()<90){
                    $('#header').removeClass('top_nav');
                }
            });
        });
            }
            });


    $(function () {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 200) {
                $('#back-top').fadeIn();
            } else {
                $('#back-top').fadeOut();
            }
        });
        $('#back-top a').click(function () {
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });
    });

    var ajax_timeout=false, delay_beforesend=100;

    function sendFormFilter(url, form){
        $.ajax({
            url:url,
            data: {filter: $('form.'+form+'-form').serialize()},
            method:'post',
            async: true,
            success: function(data){}
        }).done(function( data ) {
            $('form.'+form+'-form .action-filter button[type="submit"]').text('искать ('+data+')');
			//$('#prc_res').text(data);
            if ($(".bot-pops button").length > 0){
                $(".bot-pops button").text('показать ('+data+')');
            }
        });
    }

    $(".build_txt").change(function() {
        sendFormFilter('/buildings/countFind', 'buildings');
    });

    $(".assignment_txt").change(function() {
        sendFormFilter('/assignment/countFind', 'assignment');
    });

    $(".resale_txt").bind('input', function(e) {
        sendFormFilter('/resale/countFind', 'resale');
    });

    $(".elite_txt").bind('input', function(e) {
        sendFormFilter('/elite/countFind', 'elite');
    });

    $(".exclusive_txt").bind('input', function(e) {
        sendFormFilter('/exclusive/countFind', 'exclusive');
    });

    $(".resid_txt").bind('input', function(e) {
        sendFormFilter('/residential/countFind', 'residential');
    });

    $(".build-check").change(function() {
        sendFormFilter('/buildings/countFind', 'buildings');
    });

    $(".assignment-check").change(function() {
        sendFormFilter('/assignment/countFind', 'assignment');
    });

    $(".residential-check").change(function() {
        sendFormFilter('/residential/countFind', 'residential');
    });

    //$(".chk-btn")

    $(".resale-check").change(function() {
        var sels = [];
        $('.btn-primary .resale-check:checked').each(function(i,val){
            sels.push($(this).val());
        });
        if (sels.length == 0){
            $(".sel_room").val("0");
        }
        else {
            var values = sels.join(",");
            $(".sel_room").val(values);
        }
        sendFormFilter('/resale/countFind', 'resale');
    });

    $(".elite-check").change(function() {
        var sels = [];
        $('.btn-primary .elite-check:checked').each(function(i,val){
            sels.push($(this).val());
        });
        if (sels.length == 0){
            $(".sel_room").val("0");
        }
        else {
            var values = sels.join(",");
            $(".sel_room").val(values);
        }
        sendFormFilter('/elite/countFind', 'elite');
    });

    $(".exclusive-check").change(function() {
        var sels = [];
        $('.btn-primary .exclusive-check:checked').each(function(i,val){
            sels.push($(this).val());
        });
        if (sels.length == 0){
            $(".sel_room").val("0");
        }
        else {
            var values = sels.join(",");
            $(".sel_room").val(values);
        }
        sendFormFilter('/exclusive/countFind', 'exclusive');
    });

    $(".arenda-check").change(function() {
        var sels = [];
        $('.btn-primary .arenda-check:checked').each(function(i,val){
            sels.push($(this).val());
        });
        if (sels.length == 0){
            $(".sel_room").val("0");
        }
        else {
            var values = sels.join(",");
            $(".sel_room").val(values);
        }
        sendFormFilter('/arenda/countFind', 'arenda');
    });

    $(".builds-check").change(function() {
        var sels = [];
        $('.btn-primary .builds-check:checked').each(function(i,val){
            sels.push($(this).val());
        });
        if (sels.length == 0){
            $(".sel_room").val("0");
        }
        else {
            var values = sels.join(",");
            $(".sel_room").val(values);
        }
        sendFormFilter('/buildings/countFind', 'buildings');
    });

    $(".assignment-checkroom").change(function() {
        var sels = [];
        $('.btn-primary .assignment-checkroom:checked').each(function(i,val){
            sels.push($(this).val());
        });
        if (sels.length == 0){
            $(".sel_room").val("0");
        }
        else {
            var values = sels.join(",");
            $(".sel_room").val(values);
        }

        sendFormFilter('/assignment/countFind', 'assignment');
    });

    $('#yousee').bxSlider({
        minSlides: 2,
        maxSlides: 4,
        slideWidth: 320,
        slideMargin: 0,
        pager: false
    });

    $('.bx-specials-bottom').bxSlider({
        minSlides: 1,
        maxSlides: 5,
        slideWidth: 170,
        slideMargin: 86,
        nextText: '',
        prevText: '',
        pager: false,
        infiniteLoop: false
    });

    $('.bx-specials-object').bxSlider({
        minSlides: 1,
        maxSlides: 2,
        slideWidth: 400,
        slideMargin: 40,
        nextText: '',
        prevText: '',
        pager: false,
        infiniteLoop: false,
        hideControlOnEnd: true
    });

    $('.bx-specials-object-bottom').bxSlider({
        minSlides: 1,
        maxSlides: 2,
        slideWidth: 570,
        slideMargin: 20,
        nextText: '',
        prevText: '',
        pager: false,
        infiniteLoop: false
    });

    $('.bx-interest-bottom').bxSlider({
        minSlides: 1,
        maxSlides: 5,
        slideWidth: 170,
        slideMargin: 86,
        nextText: '',
        prevText: '',
        pager: false,
        infiniteLoop: false
    });

    $( "#advantage-accordion" ).accordion({heightStyle: "content"});

    var favorites_slider = $('.bx-favorites-bottom').bxSlider({
        minSlides: 1,
        maxSlides: 5,
        slideWidth: 170,
        slideMargin: 86,
        nextText: '',
        prevText: '',
        pager: false,
        infiniteLoop: false
    });

    $('.mgnfcplan').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        closeBtnInside: false,
        fixedContentPos: true,
        mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
        image: {
            verticalFit: true
        },
        zoom: {
            enabled: true,
            duration: 300 // don't foget to change the duration also in CSS
        }
    });

    var seen_slider = $('.bx-seen-bottom').bxSlider({
        minSlides: 1,
        maxSlides: 5,
        slideWidth: 170,
        slideMargin: 86,
        nextText: '',
        prevText: '',
        pager: false,
        infiniteLoop: false
    });

    $.tablesorter.addParser({
        id: 'price',
        is: function(s) {
            return false;
        },
        format: function(s) {
            var h = $('<div/>',{html:s}).find('span').text();
            return h.replace(/по запросу/,0).replace(/[^0-9]/gi, '');
        },
        type: 'numeric'
    });



    $(".sortings").tablesorter({
        headers: {
            0: {
                sorter: 'digit'
            },
            1: {
                sorter: 'digit'
            },
            2: {
                sorter: 'digit'
            },
            3: {
                sorter: 'digit'
            },
            5: {
                sorter: false
            },
            6: {
                sorter: 'price'
            }
        }
    });

    $( "#city" ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: '/ajax/getBuildings',
                dataType: "json",
                data: {
                    q: request.term
                },
                success: function( data ) {
                    response( data );
                }
            });
        },
        minLength: 3,
        select: function( event, ui ) {

        },
        open: function() {
            //$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            sendFormFilter('/buildings/countFind', 'buildings');
        }
    });

    $( "#ecity" ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: '/ajax/getBuildingsNov',
                dataType: "json",
                data: {
                    q: request.term
                },
                success: function( data ) {
                    response( data );
                }
            });
        },
        minLength: 3,
        select: function( event, ui ) {

        },
        open: function() {
            //$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            sendFormFilter('/elite/countFind', 'elite');
        }
    });

    $( "#exclusivecity" ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: '/ajax/getBuildingsExcl',
                dataType: "json",
                data: {
                    q: request.term
                },
                success: function( data ) {
                    response( data );
                }
            });
        },
        minLength: 3,
        select: function( event, ui ) {

        },
        open: function() {
            //$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            sendFormFilter('/elite/countFind', 'elite');
        }
    });

    $( "#assignmentcity" ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: '/ajax/getBuildingsAssign',
                dataType: "json",
                data: {
                    q: request.term
                },
                success: function( data ) {
                    response( data );
                }
            });
        },
        minLength: 3,
        select: function( event, ui ) {

        },
        open: function() {
            //$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            sendFormFilter('/assignment/countFind', 'assignment');
        }
    });

    $("#residcity").autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: '/ajax/getBuildingsResid',
                dataType: "json",
                data: {
                    q: request.term
                },
                success: function( data ) {
                    response( data );
                }
            });
        },
        minLength: 3,
        select: function( event, ui ) {

        },
        open: function() {
            //$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            sendFormFilter('/residential/countFind', 'residential');
        }
    });

    $( "#aadrr" ).autocomplete({
        source: function( request, response ) {
            var nam = $("#aadrr").data('name');
            $.ajax({
                url: '/ajax/getAddress',
                dataType: "json",
                data: {
                    q: request.term,
                    name: nam
                },
                success: function( data ) {
                    response( data );
                }
            });
        },
        minLength: 3,
        select: function( event, ui ) {

        },
        open: function() {
            //$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            sendFormFilter('/'+nam+'/countFind', nam);
        }
    });

    $("body").on("click", ".showmoreappt", function(e){
        e.preventDefault();
        if ($(this).hasClass('activ')){
            $(this).closest('table').find('.hidds').addClass("notshow");
            $(this).removeClass('activ');
            $(this).text('Показать все квартиры');
        }
        else {
            $(this).closest('table').find('.hidds').removeClass("notshow");
            $(this).addClass('activ');
            $(this).text('Спрятать варианты');
        }

    });

    $("body").on("click", ".titlesadv h3", function(e){
        e.preventDefault();
        var id = $(this).data("id");
        if (!$(this).hasClass('active')){
            $(".titlesadv h3").removeClass('active');
            $(".contsacc div").addClass('notshow');
            $(this).addClass('active');
            $('#contacc-'+id).removeClass('notshow');
        }
    });


    $(".sortings-filt").selectBoxIt({defaultText: "Название ЖК"});
    $(".sortings-filt-date").selectBoxIt({defaultText: "Стоимость"});

    $("body").on("change", ".sortings-filt", function(e){
        e.preventDefault();
        var sort = $(this).val();
        $.ajax({
            type: 'POST',
            url: location.href,
            dataType: 'html',
            data: {
                sortname: sort
            },
            success: function(data) {

                if(data){
                    $('.ajax-source').html(data);
                    $(".sortings-filt").selectBoxIt({defaultText: "Название ЖК"}).refresh;
                    $(".sortings-filt-date").selectBoxIt({defaultText: "Стоимость"}).refresh;

                }
            }
        });
    });

    $("body").on("change", ".sortings-filt-date", function(e){
        e.preventDefault();
        var sort = $(this).val();
        $.ajax({
            type: 'POST',
            url: location.href,
            dataType: 'html',
            data: {
                sortprice: sort
            },
            success: function(data) {

                if(data){
                    $('.ajax-source').html(data);
                    $(".sortings-filt").selectBoxIt({defaultText: "Название ЖК"}).refresh;
                    $(".sortings-filt-date").selectBoxIt({selectOption:0});
                }
            }
        });
    });

    $(".inp-phone").mask("9 (999) 999-9999");

    $('.multiselect').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Все',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Все';
            }
            else if (options.length > 1) {
                return 'Выбрано: '+options.length;
            }
            else {
                var selected = '';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.multiselect option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_metro").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_metro").val(values);
            }
        }
    });


    $('.mbuildingsmetro').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Все',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Все';
            }
            else if (options.length > 1) {
                return 'Выбрано: '+options.length;
            }
            else {
                var selected = '';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mbuildingsmetro option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_build_metro").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_build_metro").val(values);
            }
            sendFormFilter('/buildings/countFind', 'buildings');
        }
    });

    $('.mbuildingsbuilder').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Застройщик: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Застройщик: не выбрано';
            }
            else if (options.length > 0) {
                return 'Застройщик: выбрано - '+options.length;
            }
            /*else {
             var selected = '';
             options.each(function() {
             selected += $(this).text() + ', ';
             });
             return selected.substr(0, selected.length -2)
             }*/
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mbuildingsbuilder option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_build_builder").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_build_builder").val(values);
            }
            sendFormFilter('/buildings/countFind', 'buildings');
        }
    });

    $('.massignmentbuilder').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Застройщик: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Застройщик: не выбрано';
            }
            else if (options.length > 0) {
                return 'Застройщик: выбрано - '+options.length;
            }
            /*else {
             var selected = '';
             options.each(function() {
             selected += $(this).text() + ', ';
             });
             return selected.substr(0, selected.length -2)
             }*/
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.massignmentbuilder option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_build_builder").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_build_builder").val(values);
            }
            sendFormFilter('/assignment/countFind', 'assignment');
        }
    });

    $('.mresalemetro').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Все',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Все';
            }
            else if (options.length > 1) {
                return 'Выбрано: '+options.length;
            }
            else {
                var selected = '';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mresalemetro option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_resale_metro").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_resale_metro").val(values);
            }
            sendFormFilter('/resale/countFind', 'resale');
        }
    });



    /*var ib = new InfoBox();

    function createIb(marker, val){
        var boxText = document.createElement("div");

        var str = '';
        str += '<a href="'+val.link+'" class="filter-result-item-map sm_search" data-category="SelectObject" data-label="buildings">';
        str += '    <div class="img_fil"><img class="filter-result-item-img" src="'+val.foto+'" alt="'+val.name+'"></div>';
        str += '        <div class="priceres">'+val.price+'</div>';
        str += '        <div class="filter-result-item-body">';
        if (val.rayon != null)
            str += '            <p class="filter-result-item-rayon">'+val.rayon+'</p>';
        str += '            <p class="filter-result-item-name">'+val.name+'</p>';
        if (val.srok != null)
            str += '            <p class="filter-result-item-size">'+val.srok+'</p>';
        str += '            <div class="addbott"><div class="filter-result-item-address">'+val.address;
        if (val.metro != null)
            str += '            <span class="filter-result-item-metro"><span>'+val.metro+'</span></span>';
        str += '        </div></div>';
        str += '        </div>';
        str += '    </a>';
        boxText.innerHTML = str;
        var myOptions = {
            content: boxText


            ,disableAutoPan: false
            ,maxWidth: 0
            ,pixelOffset: new google.maps.Size(20, -234)
            ,zIndex: null
            ,boxStyle: {
                height: "434px"
                ,width: "305px"
            }
            ,closeBoxMargin: "10px 2px 2px 2px"
            ,closeBoxURL: "/asset/assets/img/closemark.png"
            ,infoBoxClearance: new google.maps.Size(1, 1)
            ,isHidden: false
            ,pane: "floatPane"
            ,enableEventPropagation: false
        };
        ib.setOptions(myOptions);
        ib.open(map_view, marker);
    }*/

    if ($("#lands-map").length > 0){
        ymaps.ready(function () {
            var points = [];
            var myMap = new ymaps.Map('lands-map', {
                    center: [30.338731, 59.941115],
                    zoom: 9,
                    controls: ['zoomControl', 'fullscreenControl'],
                    behaviors: ["drag", "dblClickZoom"]
                }),
                MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div style="color: #D6E6EB; font-weight: bold; font-size: 12px;">$[properties.geoObjects.length]</div>'),
                clusterer = new ymaps.Clusterer({
                    clusterIcons: [{
                        href: '/asset/assets/img/clust.png',
                        size: [46, 46],
                        offset: [-23, -23]
                    }],
                    clusterIconContentLayout: MyIconContentLayout,
                    openBalloonOnClick: false,
                    groupByCoordinates: false,
                    clusterDisableClickZoom: false,
                    clusterHideIconOnBalloonOpen: false,
                    geoObjectHideIconOnBalloonOpen: false

                }),
                getPointOptions = function () {
                    return {
                        iconLayout: 'default#image',
                        iconImageHref: '/asset/assets/img/markerb.png',
                        iconImageSize: [22, 28],
                        iconImageOffset: [-11, -28],
                        balloonCloseButton: false,
                        balloonMinWidth: 215,
                        balloonMaxWidth: 215,
                        balloonLayout: myBalloonLayout,
                        balloonContentLayout: myBalloonContentLayout,
                        balloonContentBodyLayout: myBalloonContentBodyLayout,
                        balloonCloseButtonLayout: myBalloonCloseButtonLayout,
                        balloonShadow: false,
                        balloonOffset: [10, -226],
                        balloonShadowLayout: myBalloonShadowLayout,
                        balloonPanelMaxMapArea: 0

                    };
                },
                geoObjects = [];
            var myBalloonLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-wrapper">' +
                    '<div class="center-balloon-content-wrapper">' +
                    '$[[options.contentLayout]]' +
                    '<div class="center-balloon-close">$[[options.closeButtonLayout]]</div>'+
                    '</div>' +
                    '<div class="center-balloon-tale-wrapper"><div class="center-balloon-tale"></div></div>' +

                    '</div>',
                {
                    build: function () {
                        this.constructor.superclass.build.call(this);
                        this._$element = $('.center-balloon-wrapper', this.getParentElement());
                        this.applyElementOffset();
                        this._$element.find('.center-balloon-close')
                            .on('click', $.proxy(this.onCloseClick, this));
                    },
                    clear: function () {
                        this._$element.find('.center-balloon-close')
                            .off('click');
                        this.constructor.superclass.clear.call(this);
                    },
                    onSublayoutSizeChange: function () {
                        myBalloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments);
                        if(!this._isElement(this._$element)) {
                            return;
                        }
                        this.applyElementOffset();
                        this.events.fire('shapechange');
                    },
                    applyElementOffset: function () {
                        this._$element.css({
                            left: -(this._$element[0].offsetWidth / 2),
                            top: -(this._$element[0].offsetHeight / 2)
                        });
                    },
                    onCloseClick: function (e) {
                        e.preventDefault();
                        this.events.fire('userclose');
                    },
                    getShape: function () {
                        if(!this._isElement(this._$element)) {
                            return myBalloonLayout.superclass.getShape.call(this);
                        }
                        var position = this._$element.position();
                        return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
                            [position.left, position.top], [
                                position.left + this._$element[0].offsetWidth,
                                position.top + this._$element[0].offsetHeight
                            ]
                        ]));
                    },
                    _isElement: function (element) {
                        return element && element[0];
                    }
                }
            );

            var myBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-content">'+
                    '$[[options.contentBodyLayout]]'
                    +'</div>'
            );

            var myBalloonContentBodyLayout = ymaps.templateLayoutFactory.createClass(
                '<a href="{{properties.link}}" class="filter-result-item-map sm_search" data-category="SelectObject" data-label="buildings">'+
                    '    <div class="img_fil"><img class="filter-result-item-img" src="{{properties.foto}}" alt="{{properties.name}}"></div>'+
                    '        <div class="priceres">'+'$[properties.price]'+'</div>'+
                    '        <div class="filter-result-item-body">'+
                    '            <p class="filter-result-item-rayon">{{properties.rayon}}</p>'+
                    '            <p class="filter-result-item-name">{{properties.name}}</p>'+
                    '            <p class="filter-result-item-size">{{properties.srok}}</p>'+
                    '            <div class="addbott"><div class="filter-result-item-address">{{properties.address}}'+
                    '            {% if properties.metro %}<span class="filter-result-item-metro"><span>{{properties.metro}}</span>{% endif %}</span>'+
                    '        </div></div>'+
                    '        </div>'+
                    '    </a>');
            var myBalloonShadowLayout = ymaps.templateLayoutFactory.createClass('');

            var myBalloonCloseButtonLayout = ymaps.templateLayoutFactory.createClass(
                '<img class="closes" src="/asset/assets/img/closemark.png" >'
            );
            var param = $('.land-form').serialize();
            $.ajax({
                type: 'POST',
                url: '/land/map',
                data: {param: param},
                dataType: 'json',
                success: function(data) {
                    if(data){
                        $.each(data, function(i, val){
                            points.push([val.lat, val.lng]);
                            geoObjects[i] = new ymaps.Placemark([val.lat, val.lng], {
                                name: val.name,
                                price: val.price,
                                foto: val.foto,
                                address: val.address,
                                rayon: val.rayon,
                                metro: val.metro,
                                link: val.link
                            }, getPointOptions());
                        });
                        clusterer.add(geoObjects);
                        myMap.geoObjects.add(clusterer);
                        myMap.setBounds(clusterer.getBounds(), {
                            checkZoomRange: true
                        });
                    }
                }
            });
        });

    }


    /*if ($("#res-map").length > 0){

        ymaps.ready(function () {
            var points = [];
            var myMap = new ymaps.Map('res-map', {
                    center: [30.338731, 59.941115],
                    zoom: 9,
                    controls: ['zoomControl', 'fullscreenControl'],
                    behaviors: ["drag", "dblClickZoom"]
                }),
                MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div style="color: #D6E6EB; font-weight: bold; font-size: 12px;">$[properties.geoObjects.length]</div>'),
                clusterer = new ymaps.Clusterer({
                    clusterIcons: [{
                        href: '/asset/assets/img/clust.png',
                        size: [46, 46],
                        offset: [-23, -23]
                    }],
                    clusterIconContentLayout: MyIconContentLayout,
                    openBalloonOnClick: false,
                    groupByCoordinates: false,
                    clusterDisableClickZoom: false,
                    clusterHideIconOnBalloonOpen: false,
                    geoObjectHideIconOnBalloonOpen: false

                }),
                getPointOptions = function () {
                    return {
                        iconLayout: 'default#image',
                        iconImageHref: '/asset/assets/img/markerb.png',
                        iconImageSize: [22, 28],
                        iconImageOffset: [-11, -28],
                        balloonCloseButton: false,
                        balloonMinWidth: 215,
                        balloonMaxWidth: 215,
                        balloonLayout: myBalloonLayout,
                        balloonContentLayout: myBalloonContentLayout,
                        balloonContentBodyLayout: myBalloonContentBodyLayout,
                        balloonCloseButtonLayout: myBalloonCloseButtonLayout,
                        balloonShadow: false,
                        balloonOffset: [10, -226],
                        balloonShadowLayout: myBalloonShadowLayout,
                        balloonPanelMaxMapArea: 0

                    };
                },
                geoObjects = [];
            var myBalloonLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-wrapper">' +
                    '<div class="center-balloon-content-wrapper">' +
                    '$[[options.contentLayout]]' +
                    '<div class="center-balloon-close">$[[options.closeButtonLayout]]</div>'+
                    '</div>' +
                    '<div class="center-balloon-tale-wrapper"><div class="center-balloon-tale"></div></div>' +

                    '</div>',
                {
                    build: function () {
                        this.constructor.superclass.build.call(this);
                        this._$element = $('.center-balloon-wrapper', this.getParentElement());
                        this.applyElementOffset();
                        this._$element.find('.center-balloon-close')
                            .on('click', $.proxy(this.onCloseClick, this));
                    },
                    clear: function () {
                        this._$element.find('.center-balloon-close')
                            .off('click');
                        this.constructor.superclass.clear.call(this);
                    },
                    onSublayoutSizeChange: function () {
                        myBalloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments);
                        if(!this._isElement(this._$element)) {
                            return;
                        }
                        this.applyElementOffset();
                        this.events.fire('shapechange');
                    },
                    applyElementOffset: function () {
                        this._$element.css({
                            left: -(this._$element[0].offsetWidth / 2),
                            top: -(this._$element[0].offsetHeight / 2)
                        });
                    },
                    onCloseClick: function (e) {
                        e.preventDefault();
                        this.events.fire('userclose');
                    },
                    getShape: function () {
                        if(!this._isElement(this._$element)) {
                            return myBalloonLayout.superclass.getShape.call(this);
                        }
                        var position = this._$element.position();
                        return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
                            [position.left, position.top], [
                                position.left + this._$element[0].offsetWidth,
                                position.top + this._$element[0].offsetHeight
                            ]
                        ]));
                    },
                    _isElement: function (element) {
                        return element && element[0];
                    }
                }
            );

            var myBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-content">'+
                    '$[[options.contentBodyLayout]]'
                    +'</div>'
            );

            var myBalloonContentBodyLayout = ymaps.templateLayoutFactory.createClass(
                '<a href="{{properties.link}}" class="filter-result-item-map sm_search" data-category="SelectObject" data-label="buildings">'+
                    '    <div class="img_fil"><img class="filter-result-item-img" src="{{properties.foto}}" alt="{{properties.name}}"></div>'+
                    '        <div class="priceres">'+'$[properties.price]'+'</div>'+
                    '        <div class="filter-result-item-body">'+
                    '            <p class="filter-result-item-rayon">{{properties.rayon}}</p>'+
                    '            <p class="filter-result-item-name">{{properties.name}}</p>'+
                    '            <p class="filter-result-item-size">{{properties.srok}}</p>'+
                    '            <div class="addbott"><div class="filter-result-item-address">{{properties.address}}'+
                    '            {% if properties.metro %}<span class="filter-result-item-metro"><span>{{properties.metro}}</span>{% endif %}</span>'+
                    '        </div></div>'+
                    '        </div>'+
                    '    </a>');
            var myBalloonShadowLayout = ymaps.templateLayoutFactory.createClass('');

            var myBalloonCloseButtonLayout = ymaps.templateLayoutFactory.createClass(
                '<img class="closes" src="/asset/assets/img/closemark.png" >'
            );
            var param = $('.resale-form').serialize();
            $.ajax({
                type: 'POST',
                url: '/resale/map',
                data: {param: param},
                dataType: 'json',
                success: function(data) {
                    if(data){
                        $.each(data, function(i, val){
                            points.push([val.lat, val.lng]);
                            geoObjects[i] = new ymaps.Placemark([val.lat, val.lng], {
                                name: val.name,
                                price: val.price,
                                foto: val.foto,
                                address: val.address,
                                rayon: val.rayon,
                                metro: val.metro,
                                link: val.link
                            }, getPointOptions());
                        });
                        clusterer.add(geoObjects);
                        myMap.geoObjects.add(clusterer);
                        myMap.setBounds(clusterer.getBounds(), {
                            checkZoomRange: true
                        });
                    }
                }
            });
        });
    }*/

    if ($("#assignment-map").length > 0){

        ymaps.ready(function () {
            var points = [];
            var myMap = new ymaps.Map('assignment-map', {
                    center: [30.338731, 59.941115],
                    zoom: 9,
                    controls: ['zoomControl', 'fullscreenControl'],
                    behaviors: ["drag", "dblClickZoom"]
                }),
                MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div style="color: #D6E6EB; font-weight: bold; font-size: 12px;">$[properties.geoObjects.length]</div>'),
                clusterer = new ymaps.Clusterer({
                    clusterIcons: [{
                        href: '/asset/assets/img/clust.png',
                        size: [46, 46],
                        offset: [-23, -23]
                    }],
                    clusterIconContentLayout: MyIconContentLayout,
                    openBalloonOnClick: false,
                    groupByCoordinates: false,
                    clusterDisableClickZoom: false,
                    clusterHideIconOnBalloonOpen: false,
                    geoObjectHideIconOnBalloonOpen: false

                }),
                getPointOptions = function () {
                    return {
                        iconLayout: 'default#image',
                        iconImageHref: '/asset/assets/img/markerb.png',
                        iconImageSize: [22, 28],
                        iconImageOffset: [-11, -28],
                        balloonCloseButton: false,
                        balloonMinWidth: 215,
                        balloonMaxWidth: 215,
                        balloonLayout: myBalloonLayout,
                        balloonContentLayout: myBalloonContentLayout,
                        balloonContentBodyLayout: myBalloonContentBodyLayout,
                        balloonCloseButtonLayout: myBalloonCloseButtonLayout,
                        balloonShadow: false,
                        balloonOffset: [10, -226],
                        balloonShadowLayout: myBalloonShadowLayout,
                        balloonPanelMaxMapArea: 0

                    };
                },
                geoObjects = [];
            var myBalloonLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-wrapper">' +
                    '<div class="center-balloon-content-wrapper">' +
                    '$[[options.contentLayout]]' +
                    '<div class="center-balloon-close">$[[options.closeButtonLayout]]</div>'+
                    '</div>' +
                    '<div class="center-balloon-tale-wrapper"><div class="center-balloon-tale"></div></div>' +

                    '</div>',
                {
                    build: function () {
                        this.constructor.superclass.build.call(this);
                        this._$element = $('.center-balloon-wrapper', this.getParentElement());
                        this.applyElementOffset();
                        this._$element.find('.center-balloon-close')
                            .on('click', $.proxy(this.onCloseClick, this));
                    },
                    clear: function () {
                        this._$element.find('.center-balloon-close')
                            .off('click');
                        this.constructor.superclass.clear.call(this);
                    },
                    onSublayoutSizeChange: function () {
                        myBalloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments);
                        if(!this._isElement(this._$element)) {
                            return;
                        }
                        this.applyElementOffset();
                        this.events.fire('shapechange');
                    },
                    applyElementOffset: function () {
                        this._$element.css({
                            left: -(this._$element[0].offsetWidth / 2),
                            top: -(this._$element[0].offsetHeight / 2)
                        });
                    },
                    onCloseClick: function (e) {
                        e.preventDefault();
                        this.events.fire('userclose');
                    },
                    getShape: function () {
                        if(!this._isElement(this._$element)) {
                            return myBalloonLayout.superclass.getShape.call(this);
                        }
                        var position = this._$element.position();
                        return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
                            [position.left, position.top], [
                                position.left + this._$element[0].offsetWidth,
                                position.top + this._$element[0].offsetHeight
                            ]
                        ]));
                    },
                    _isElement: function (element) {
                        return element && element[0];
                    }
                }
            );

            var myBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-content">'+
                    '$[[options.contentBodyLayout]]'
                    +'</div>'
            );

            var myBalloonContentBodyLayout = ymaps.templateLayoutFactory.createClass(
                '<a href="{{properties.link}}" class="filter-result-item-map sm_search" data-category="SelectObject" data-label="buildings">'+
                    '    <div class="img_fil"><img class="filter-result-item-img" src="{{properties.foto}}" alt="{{properties.name}}"></div>'+
                    '        <div class="priceres">'+'$[properties.price]'+'</div>'+
                    '        <div class="filter-result-item-body">'+
                    '            <p class="filter-result-item-rayon">{{properties.rayon}}</p>'+
                    '            <p class="filter-result-item-name">{{properties.name}}</p>'+
                    '            <p class="filter-result-item-size">{{properties.srok}}</p>'+
                    '            <div class="addbott"><div class="filter-result-item-address">{{properties.address}}'+
                    '            {% if properties.metro %}<span class="filter-result-item-metro"><span>{{properties.metro}}</span>{% endif %}</span>'+
                    '        </div></div>'+
                    '        </div>'+
                    '    </a>');
            var myBalloonShadowLayout = ymaps.templateLayoutFactory.createClass('');

            var myBalloonCloseButtonLayout = ymaps.templateLayoutFactory.createClass(
                '<img class="closes" src="/asset/assets/img/closemark.png" >'
            );
            var param = $('.assignment-form').serialize();
            $.ajax({
                type: 'POST',
                url: '/assignment/map',
                data: {param: param},
                dataType: 'json',
                success: function(data) {
                    if(data){
                        $.each(data, function(i, val){
                            points.push([val.lat, val.lng]);
                            geoObjects[i] = new ymaps.Placemark([val.lat, val.lng], {
                                name: val.name,
                                price: val.price,
                                foto: val.foto,
                                address: val.address,
                                rayon: val.rayon,
                                metro: val.metro,
                                link: val.link
                            }, getPointOptions());
                        });
                        clusterer.add(geoObjects);
                        myMap.geoObjects.add(clusterer);
                        myMap.setBounds(clusterer.getBounds(), {
                            checkZoomRange: true
                        });
                    }
                }
            });
        });
    }

    /*if ($("#arenda-map").length > 0){
        ymaps.ready(function () {
            var points = [];
            var myMap = new ymaps.Map('arenda-map', {
                    center: [30.338731, 59.941115],
                    zoom: 9,
                    controls: ['zoomControl', 'fullscreenControl'],
                    behaviors: ["drag", "dblClickZoom"]
                }),
                MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div style="color: #D6E6EB; font-weight: bold; font-size: 12px;">$[properties.geoObjects.length]</div>'),
                clusterer = new ymaps.Clusterer({
                    clusterIcons: [{
                        href: '/asset/assets/img/clust.png',
                        size: [46, 46],
                        offset: [-23, -23]
                    }],
                    clusterIconContentLayout: MyIconContentLayout,
                    openBalloonOnClick: false,
                    groupByCoordinates: false,
                    clusterDisableClickZoom: false,
                    clusterHideIconOnBalloonOpen: false,
                    geoObjectHideIconOnBalloonOpen: false

                }),
                getPointOptions = function () {
                    return {
                        iconLayout: 'default#image',
                        iconImageHref: '/asset/assets/img/markerb.png',
                        iconImageSize: [22, 28],
                        iconImageOffset: [-11, -28],
                        balloonCloseButton: false,
                        balloonMinWidth: 215,
                        balloonMaxWidth: 215,
                        balloonLayout: myBalloonLayout,
                        balloonContentLayout: myBalloonContentLayout,
                        balloonContentBodyLayout: myBalloonContentBodyLayout,
                        balloonCloseButtonLayout: myBalloonCloseButtonLayout,
                        balloonShadow: false,
                        balloonOffset: [10, -226],
                        balloonShadowLayout: myBalloonShadowLayout,
                        balloonPanelMaxMapArea: 0

                    };
                },
                geoObjects = [];
            var myBalloonLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-wrapper">' +
                    '<div class="center-balloon-content-wrapper">' +
                    '$[[options.contentLayout]]' +
                    '<div class="center-balloon-close">$[[options.closeButtonLayout]]</div>'+
                    '</div>' +
                    '<div class="center-balloon-tale-wrapper"><div class="center-balloon-tale"></div></div>' +

                    '</div>',
                {
                    build: function () {
                        this.constructor.superclass.build.call(this);
                        this._$element = $('.center-balloon-wrapper', this.getParentElement());
                        this.applyElementOffset();
                        this._$element.find('.center-balloon-close')
                            .on('click', $.proxy(this.onCloseClick, this));
                    },
                    clear: function () {
                        this._$element.find('.center-balloon-close')
                            .off('click');
                        this.constructor.superclass.clear.call(this);
                    },
                    onSublayoutSizeChange: function () {
                        myBalloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments);
                        if(!this._isElement(this._$element)) {
                            return;
                        }
                        this.applyElementOffset();
                        this.events.fire('shapechange');
                    },
                    applyElementOffset: function () {
                        this._$element.css({
                            left: -(this._$element[0].offsetWidth / 2),
                            top: -(this._$element[0].offsetHeight / 2)
                        });
                    },
                    onCloseClick: function (e) {
                        e.preventDefault();
                        this.events.fire('userclose');
                    },
                    getShape: function () {
                        if(!this._isElement(this._$element)) {
                            return myBalloonLayout.superclass.getShape.call(this);
                        }
                        var position = this._$element.position();
                        return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
                            [position.left, position.top], [
                                position.left + this._$element[0].offsetWidth,
                                position.top + this._$element[0].offsetHeight
                            ]
                        ]));
                    },
                    _isElement: function (element) {
                        return element && element[0];
                    }
                }
            );

            var myBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-content">'+
                    '$[[options.contentBodyLayout]]'
                    +'</div>'
            );

            var myBalloonContentBodyLayout = ymaps.templateLayoutFactory.createClass(
                '<a href="{{properties.link}}" class="filter-result-item-map sm_search" data-category="SelectObject" data-label="buildings">'+
                    '    <div class="img_fil"><img class="filter-result-item-img" src="{{properties.foto}}" alt="{{properties.name}}"></div>'+
                    '        <div class="priceres">'+'$[properties.price]'+'</div>'+
                    '        <div class="filter-result-item-body">'+
                    '            <p class="filter-result-item-rayon">{{properties.rayon}}</p>'+
                    '            <p class="filter-result-item-name">{{properties.name}}</p>'+
                    '            <p class="filter-result-item-size">{{properties.srok}}</p>'+
                    '            <div class="addbott"><div class="filter-result-item-address">{{properties.address}}'+
                    '            {% if properties.metro %}<span class="filter-result-item-metro"><span>{{properties.metro}}</span>{% endif %}</span>'+
                    '        </div></div>'+
                    '        </div>'+
                    '    </a>');
            var myBalloonShadowLayout = ymaps.templateLayoutFactory.createClass('');

            var myBalloonCloseButtonLayout = ymaps.templateLayoutFactory.createClass(
                '<img class="closes" src="/asset/assets/img/closemark.png" >'
            );
            var param = $('.arenda-form').serialize();
            $.ajax({
                type: 'POST',
                url: '/arenda/map',
                data: {param: param},
                dataType: 'json',
                success: function(data) {
                    if(data){
                        $.each(data, function(i, val){
                            points.push([val.lat, val.lng]);
                            geoObjects[i] = new ymaps.Placemark([val.lat, val.lng], {
                                name: val.name,
                                price: val.price,
                                foto: val.foto,
                                address: val.address,
                                rayon: val.rayon,
                                metro: val.metro,
                                link: val.link
                            }, getPointOptions());
                        });
                        clusterer.add(geoObjects);
                        myMap.geoObjects.add(clusterer);
                        myMap.setBounds(clusterer.getBounds(), {
                            checkZoomRange: true
                        });
                    }
                }
            });
        });
    }*/


    /*if ($("#abroad-map").length > 0){
        ymaps.ready(function () {
            var points = [];
            var myMap = new ymaps.Map('abroad-map', {
                    center: [30.338731, 59.941115],
                    zoom: 9,
                    controls: ['zoomControl', 'fullscreenControl'],
                    behaviors: ["drag", "dblClickZoom"]
                }),
                MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div style="color: #D6E6EB; font-weight: bold; font-size: 12px;">$[properties.geoObjects.length]</div>'),
                clusterer = new ymaps.Clusterer({
                    clusterIcons: [{
                        href: '/asset/assets/img/clust.png',
                        size: [46, 46],
                        offset: [-23, -23]
                    }],
                    clusterIconContentLayout: MyIconContentLayout,
                    openBalloonOnClick: false,
                    groupByCoordinates: false,
                    clusterDisableClickZoom: false,
                    clusterHideIconOnBalloonOpen: false,
                    geoObjectHideIconOnBalloonOpen: false

                }),
                getPointOptions = function () {
                    return {
                        iconLayout: 'default#image',
                        iconImageHref: '/asset/assets/img/markerb.png',
                        iconImageSize: [22, 28],
                        iconImageOffset: [-11, -28],
                        balloonCloseButton: false,
                        balloonMinWidth: 215,
                        balloonMaxWidth: 215,
                        balloonLayout: myBalloonLayout,
                        balloonContentLayout: myBalloonContentLayout,
                        balloonContentBodyLayout: myBalloonContentBodyLayout,
                        balloonCloseButtonLayout: myBalloonCloseButtonLayout,
                        balloonShadow: false,
                        balloonOffset: [10, -226],
                        balloonShadowLayout: myBalloonShadowLayout,
                        balloonPanelMaxMapArea: 0

                    };
                },
                geoObjects = [];
            var myBalloonLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-wrapper">' +
                    '<div class="center-balloon-content-wrapper">' +
                    '$[[options.contentLayout]]' +
                    '<div class="center-balloon-close">$[[options.closeButtonLayout]]</div>'+
                    '</div>' +
                    '<div class="center-balloon-tale-wrapper"><div class="center-balloon-tale"></div></div>' +

                    '</div>',
                {
                    build: function () {
                        this.constructor.superclass.build.call(this);
                        this._$element = $('.center-balloon-wrapper', this.getParentElement());
                        this.applyElementOffset();
                        this._$element.find('.center-balloon-close')
                            .on('click', $.proxy(this.onCloseClick, this));
                    },
                    clear: function () {
                        this._$element.find('.center-balloon-close')
                            .off('click');
                        this.constructor.superclass.clear.call(this);
                    },
                    onSublayoutSizeChange: function () {
                        myBalloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments);
                        if(!this._isElement(this._$element)) {
                            return;
                        }
                        this.applyElementOffset();
                        this.events.fire('shapechange');
                    },
                    applyElementOffset: function () {
                        this._$element.css({
                            left: -(this._$element[0].offsetWidth / 2),
                            top: -(this._$element[0].offsetHeight / 2)
                        });
                    },
                    onCloseClick: function (e) {
                        e.preventDefault();
                        this.events.fire('userclose');
                    },
                    getShape: function () {
                        if(!this._isElement(this._$element)) {
                            return myBalloonLayout.superclass.getShape.call(this);
                        }
                        var position = this._$element.position();
                        return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
                            [position.left, position.top], [
                                position.left + this._$element[0].offsetWidth,
                                position.top + this._$element[0].offsetHeight
                            ]
                        ]));
                    },
                    _isElement: function (element) {
                        return element && element[0];
                    }
                }
            );

            var myBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-content">'+
                    '$[[options.contentBodyLayout]]'
                    +'</div>'
            );

            var myBalloonContentBodyLayout = ymaps.templateLayoutFactory.createClass(
                '<a href="{{properties.link}}" class="filter-result-item-map sm_search" data-category="SelectObject" data-label="buildings">'+
                    '    <div class="img_fil"><img class="filter-result-item-img" src="{{properties.foto}}" alt="{{properties.name}}"></div>'+
                    '        <div class="priceres">'+'$[properties.price]'+'</div>'+
                    '        <div class="filter-result-item-body">'+
                    '            <p class="filter-result-item-rayon">{{properties.rayon}}</p>'+
                    '            <p class="filter-result-item-name">{{properties.name}}</p>'+
                    '            <p class="filter-result-item-size">{{properties.srok}}</p>'+
                    '            <div class="addbott"><div class="filter-result-item-address">{{properties.address}}'+
                    '            {% if properties.metro %}<span class="filter-result-item-metro"><span>{{properties.metro}}</span>{% endif %}</span>'+
                    '        </div></div>'+
                    '        </div>'+
                    '    </a>');
            var myBalloonShadowLayout = ymaps.templateLayoutFactory.createClass('');

            var myBalloonCloseButtonLayout = ymaps.templateLayoutFactory.createClass(
                '<img class="closes" src="/asset/assets/img/closemark.png" >'
            );
            var param = $('.abroad-form').serialize();
            $.ajax({
                type: 'POST',
                url: '/abroad/map',
                data: {param: param},
                dataType: 'json',
                success: function(data) {
                    if(data){
                        $.each(data, function(i, val){
                            points.push([val.lat, val.lng]);
                            geoObjects[i] = new ymaps.Placemark([val.lat, val.lng], {
                                name: val.name,
                                price: val.price,
                                foto: val.foto,
                                address: val.address,
                                rayon: val.rayon,
                                metro: val.metro,
                                link: val.link
                            }, getPointOptions());
                        });
                        clusterer.add(geoObjects);
                        myMap.geoObjects.add(clusterer);
                        myMap.setBounds(clusterer.getBounds(), {
                            checkZoomRange: true
                        });
                    }
                }
            });
        });
    }*/



    /*if ($("#resid-map").length > 0){
        ymaps.ready(function () {
            var points = [];
            var myMap = new ymaps.Map('resid-map', {
                    center: [30.338731, 59.941115],
                    zoom: 9,
                    controls: ['zoomControl', 'fullscreenControl'],
                    behaviors: ["drag", "dblClickZoom"]
                }),
                MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div style="color: #D6E6EB; font-weight: bold; font-size: 12px;">$[properties.geoObjects.length]</div>'),
                clusterer = new ymaps.Clusterer({
                    clusterIcons: [{
                        href: '/asset/assets/img/clust.png',
                        size: [46, 46],
                        offset: [-23, -23]
                    }],
                    clusterIconContentLayout: MyIconContentLayout,
                    openBalloonOnClick: false,
                    groupByCoordinates: false,
                    clusterDisableClickZoom: false,
                    clusterHideIconOnBalloonOpen: false,
                    geoObjectHideIconOnBalloonOpen: false

                }),
                getPointOptions = function () {
                    return {
                        iconLayout: 'default#image',
                        iconImageHref: '/asset/assets/img/markerb.png',
                        iconImageSize: [22, 28],
                        iconImageOffset: [-11, -28],
                        balloonCloseButton: false,
                        balloonMinWidth: 215,
                        balloonMaxWidth: 215,
                        balloonLayout: myBalloonLayout,
                        balloonContentLayout: myBalloonContentLayout,
                        balloonContentBodyLayout: myBalloonContentBodyLayout,
                        balloonCloseButtonLayout: myBalloonCloseButtonLayout,
                        balloonShadow: false,
                        balloonOffset: [10, -226],
                        balloonShadowLayout: myBalloonShadowLayout,
                        balloonPanelMaxMapArea: 0

                    };
                },
                geoObjects = [];
            var myBalloonLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-wrapper">' +
                    '<div class="center-balloon-content-wrapper">' +
                    '$[[options.contentLayout]]' +
                    '<div class="center-balloon-close">$[[options.closeButtonLayout]]</div>'+
                    '</div>' +
                    '<div class="center-balloon-tale-wrapper"><div class="center-balloon-tale"></div></div>' +

                    '</div>',
                {
                    build: function () {
                        this.constructor.superclass.build.call(this);
                        this._$element = $('.center-balloon-wrapper', this.getParentElement());
                        this.applyElementOffset();
                        this._$element.find('.center-balloon-close')
                            .on('click', $.proxy(this.onCloseClick, this));
                    },
                    clear: function () {
                        this._$element.find('.center-balloon-close')
                            .off('click');
                        this.constructor.superclass.clear.call(this);
                    },
                    onSublayoutSizeChange: function () {
                        myBalloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments);
                        if(!this._isElement(this._$element)) {
                            return;
                        }
                        this.applyElementOffset();
                        this.events.fire('shapechange');
                    },
                    applyElementOffset: function () {
                        this._$element.css({
                            left: -(this._$element[0].offsetWidth / 2),
                            top: -(this._$element[0].offsetHeight / 2)
                        });
                    },
                    onCloseClick: function (e) {
                        e.preventDefault();
                        this.events.fire('userclose');
                    },
                    getShape: function () {
                        if(!this._isElement(this._$element)) {
                            return myBalloonLayout.superclass.getShape.call(this);
                        }
                        var position = this._$element.position();
                        return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
                            [position.left, position.top], [
                                position.left + this._$element[0].offsetWidth,
                                position.top + this._$element[0].offsetHeight
                            ]
                        ]));
                    },
                    _isElement: function (element) {
                        return element && element[0];
                    }
                }
            );

            var myBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-content">'+
                    '$[[options.contentBodyLayout]]'
                    +'</div>'
            );

            var myBalloonContentBodyLayout = ymaps.templateLayoutFactory.createClass(
                '<a href="{{properties.link}}" class="filter-result-item-map sm_search" data-category="SelectObject" data-label="buildings">'+
                    '    <div class="img_fil"><img class="filter-result-item-img" src="{{properties.foto}}" alt="{{properties.name}}"></div>'+
                    '        <div class="priceres">'+'$[properties.price]'+'</div>'+
                    '        <div class="filter-result-item-body">'+
                    '            <p class="filter-result-item-rayon">{{properties.rayon}}</p>'+
                    '            <p class="filter-result-item-name">{{properties.name}}</p>'+
                    '            <p class="filter-result-item-size">{{properties.srok}}</p>'+
                    '            <div class="addbott"><div class="filter-result-item-address">{{properties.address}}'+
                    '            {% if properties.metro %}<span class="filter-result-item-metro"><span>{{properties.metro}}</span>{% endif %}</span>'+
                    '        </div></div>'+
                    '        </div>'+
                    '    </a>');
            var myBalloonShadowLayout = ymaps.templateLayoutFactory.createClass('');

            var myBalloonCloseButtonLayout = ymaps.templateLayoutFactory.createClass(
                '<img class="closes" src="/asset/assets/img/closemark.png" >'
            );
            var param = $('.residential-form').serialize();
            $.ajax({
                type: 'POST',
                url: '/residential/map',
                data: {param: param},
                dataType: 'json',
                success: function(data) {
                    if(data){
                        $.each(data, function(i, val){
                            points.push([val.lat, val.lng]);
                            geoObjects[i] = new ymaps.Placemark([val.lat, val.lng], {
                                name: val.name,
                                price: val.price,
                                foto: val.foto,
                                address: val.address,
                                rayon: val.rayon,
                                metro: val.metro,
                                link: val.link
                            }, getPointOptions());
                        });
                        clusterer.add(geoObjects);
                        myMap.geoObjects.add(clusterer);
                        myMap.setBounds(clusterer.getBounds(), {
                            checkZoomRange: true
                        });
                    }
                }
            });
        });
    }*/

    /*if ($("#com-map").length > 0){
        ymaps.ready(function () {
            var points = [];
            var myMap = new ymaps.Map('com-map', {
                    center: [30.338731, 59.941115],
                    zoom: 12,
                    controls: ['zoomControl', 'fullscreenControl'],
                    behaviors: ["drag", "dblClickZoom"]
                }),
                MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div style="color: #D6E6EB; font-weight: bold; font-size: 12px;">$[properties.geoObjects.length]</div>'),
                clusterer = new ymaps.Clusterer({
                    clusterIcons: [{
                        href: '/asset/assets/img/clust.png',
                        size: [46, 46],
                        offset: [-23, -23]
                    }],
                    clusterIconContentLayout: MyIconContentLayout,
                    openBalloonOnClick: false,
                    groupByCoordinates: false,
                    clusterDisableClickZoom: false,
                    clusterHideIconOnBalloonOpen: false,
                    geoObjectHideIconOnBalloonOpen: false

                }),
                getPointOptions = function () {
                    return {
                        iconLayout: 'default#image',
                        iconImageHref: '/asset/assets/img/markerb.png',
                        iconImageSize: [22, 28],
                        iconImageOffset: [-11, -28],
                        balloonCloseButton: false,
                        balloonMinWidth: 215,
                        balloonMaxWidth: 215,
                        balloonLayout: myBalloonLayout,
                        balloonContentLayout: myBalloonContentLayout,
                        balloonContentBodyLayout: myBalloonContentBodyLayout,
                        balloonCloseButtonLayout: myBalloonCloseButtonLayout,
                        balloonShadow: false,
                        balloonOffset: [10, -226],
                        balloonShadowLayout: myBalloonShadowLayout,
                        balloonPanelMaxMapArea: 0

                    };
                },
                geoObjects = [];
            var myBalloonLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-wrapper">' +
                    '<div class="center-balloon-content-wrapper">' +
                    '$[[options.contentLayout]]' +
                    '<div class="center-balloon-close">$[[options.closeButtonLayout]]</div>'+
                    '</div>' +
                    '<div class="center-balloon-tale-wrapper"><div class="center-balloon-tale"></div></div>' +

                    '</div>',
                {
                    build: function () {
                        this.constructor.superclass.build.call(this);
                        this._$element = $('.center-balloon-wrapper', this.getParentElement());
                        this.applyElementOffset();
                        this._$element.find('.center-balloon-close')
                            .on('click', $.proxy(this.onCloseClick, this));
                    },
                    clear: function () {
                        this._$element.find('.center-balloon-close')
                            .off('click');
                        this.constructor.superclass.clear.call(this);
                    },
                    onSublayoutSizeChange: function () {
                        myBalloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments);
                        if(!this._isElement(this._$element)) {
                            return;
                        }
                        this.applyElementOffset();
                        this.events.fire('shapechange');
                    },
                    applyElementOffset: function () {
                        this._$element.css({
                            left: -(this._$element[0].offsetWidth / 2),
                            top: -(this._$element[0].offsetHeight / 2)
                        });
                    },
                    onCloseClick: function (e) {
                        e.preventDefault();
                        this.events.fire('userclose');
                    },
                    getShape: function () {
                        if(!this._isElement(this._$element)) {
                            return myBalloonLayout.superclass.getShape.call(this);
                        }
                        var position = this._$element.position();
                        return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
                            [position.left, position.top], [
                                position.left + this._$element[0].offsetWidth,
                                position.top + this._$element[0].offsetHeight
                            ]
                        ]));
                    },
                    _isElement: function (element) {
                        return element && element[0];
                    }
                }
            );

            var myBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-content">'+
                    '$[[options.contentBodyLayout]]'
                    +'</div>'
            );

            var myBalloonContentBodyLayout = ymaps.templateLayoutFactory.createClass(
                '<a href="{{properties.link}}" class="filter-result-item-map sm_search" data-category="SelectObject" data-label="buildings">'+
                    '    <div class="img_fil"><img class="filter-result-item-img" src="{{properties.foto}}" alt="{{properties.name}}"></div>'+
                    '        <div class="priceres">'+'$[properties.price]'+'</div>'+
                    '        <div class="filter-result-item-body">'+
                    '            {% if properties.rayon %}<p class="filter-result-item-rayon">{{properties.rayon}}</p>{% endif %}'+
                    '            <p class="filter-result-item-name">{{properties.name}}</p>'+
                    '            {% if properties.srok %}<p class="filter-result-item-size">{{properties.srok}}</p>{% endif %}'+
                    '            <div class="addbott"><div class="filter-result-item-address">{{properties.address}}'+
                    '            {% if properties.metro %}<span class="filter-result-item-metro"><span>{{properties.metro}}</span>{% endif %}</span>'+
                    '        </div></div>'+
                    '        </div>'+
                    '    </a>');
            var myBalloonShadowLayout = ymaps.templateLayoutFactory.createClass('');

            var myBalloonCloseButtonLayout = ymaps.templateLayoutFactory.createClass(
                '<img class="closes" src="/asset/assets/img/closemark.png" >'
            );
            var param = $('.commercial-form').serialize();
            $.ajax({
                type: 'POST',
                url: '/commercial/map',
                data: {param: param},
                dataType: 'json',
                success: function(data) {
                    if(data){
                        $.each(data, function(i, val){
                            points.push([val.lat, val.lng]);
                            geoObjects[i] = new ymaps.Placemark([val.lat, val.lng], {
                                name: val.name,
                                price: val.price,
                                foto: val.foto,
                                address: val.address,
                                rayon: val.rayon,
                                metro: val.metro,
                                link: val.link
                            }, getPointOptions());
                        });
                        clusterer.add(geoObjects);
                        myMap.geoObjects.add(clusterer);
                        myMap.setBounds(clusterer.getBounds(), {
                            checkZoomRange: true
                        });
                    }
                }
            });
        });
    }*/

    if ($("#elite-map").length > 0){
        ymaps.ready(function () {
            var points = [];
            var myMap = new ymaps.Map('elite-map', {
                    center: [30.338731, 59.941115],
                    zoom: 9,
                    controls: ['zoomControl', 'fullscreenControl'],
                    behaviors: ["drag", "dblClickZoom"]
                }),
                MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div style="color: #D6E6EB; font-weight: bold; font-size: 12px;">$[properties.geoObjects.length]</div>'),
                clusterer = new ymaps.Clusterer({
                    clusterIcons: [{
                        href: '/asset/assets/img/clust.png',
                        size: [46, 46],
                        offset: [-23, -23]
                    }],
                    clusterIconContentLayout: MyIconContentLayout,
                    openBalloonOnClick: false,
                    groupByCoordinates: false,
                    clusterDisableClickZoom: false,
                    clusterHideIconOnBalloonOpen: false,
                    geoObjectHideIconOnBalloonOpen: false

                }),
                getPointOptions = function () {
                    return {
                        iconLayout: 'default#image',
                        iconImageHref: '/asset/assets/img/markerb.png',
                        iconImageSize: [22, 28],
                        iconImageOffset: [-11, -28],
                        balloonCloseButton: false,
                        balloonMinWidth: 215,
                        balloonMaxWidth: 215,
                        balloonLayout: myBalloonLayout,
                        balloonContentLayout: myBalloonContentLayout,
                        balloonContentBodyLayout: myBalloonContentBodyLayout,
                        balloonCloseButtonLayout: myBalloonCloseButtonLayout,
                        balloonShadow: false,
                        balloonOffset: [10, -226],
                        balloonShadowLayout: myBalloonShadowLayout,
                        balloonPanelMaxMapArea: 0

                    };
                },
                geoObjects = [];
            var myBalloonLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-wrapper">' +
                    '<div class="center-balloon-content-wrapper">' +
                    '$[[options.contentLayout]]' +
                    '<div class="center-balloon-close">$[[options.closeButtonLayout]]</div>'+
                    '</div>' +
                    '<div class="center-balloon-tale-wrapper"><div class="center-balloon-tale"></div></div>' +

                    '</div>',
                {
                    build: function () {
                        this.constructor.superclass.build.call(this);
                        this._$element = $('.center-balloon-wrapper', this.getParentElement());
                        this.applyElementOffset();
                        this._$element.find('.center-balloon-close')
                            .on('click', $.proxy(this.onCloseClick, this));
                    },
                    clear: function () {
                        this._$element.find('.center-balloon-close')
                            .off('click');
                        this.constructor.superclass.clear.call(this);
                    },
                    onSublayoutSizeChange: function () {
                        myBalloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments);
                        if(!this._isElement(this._$element)) {
                            return;
                        }
                        this.applyElementOffset();
                        this.events.fire('shapechange');
                    },
                    applyElementOffset: function () {
                        this._$element.css({
                            left: -(this._$element[0].offsetWidth / 2),
                            top: -(this._$element[0].offsetHeight / 2)
                        });
                    },
                    onCloseClick: function (e) {
                        e.preventDefault();
                        this.events.fire('userclose');
                    },
                    getShape: function () {
                        if(!this._isElement(this._$element)) {
                            return myBalloonLayout.superclass.getShape.call(this);
                        }
                        var position = this._$element.position();
                        return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
                            [position.left, position.top], [
                                position.left + this._$element[0].offsetWidth,
                                position.top + this._$element[0].offsetHeight
                            ]
                        ]));
                    },
                    _isElement: function (element) {
                        return element && element[0];
                    }
                }
            );

            var myBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-content">'+
                    '$[[options.contentBodyLayout]]'
                    +'</div>'
            );

            var myBalloonContentBodyLayout = ymaps.templateLayoutFactory.createClass(
                '<a href="{{properties.link}}" class="filter-result-item-map sm_search" data-category="SelectObject" data-label="buildings">'+
                    '    <div class="img_fil"><img class="filter-result-item-img" src="{{properties.foto}}" alt="{{properties.name}}"></div>'+
                    '        <div class="priceres">'+'$[properties.price]'+'</div>'+
                    '        <div class="filter-result-item-body">'+
                    '            <p class="filter-result-item-rayon">{{properties.rayon}}</p>'+
                    '            <p class="filter-result-item-name">{{properties.name}}</p>'+
                    '            <p class="filter-result-item-size">{{properties.srok}}</p>'+
                    '            <div class="addbott"><div class="filter-result-item-address">{{properties.address}}'+
                    '            {% if properties.metro %}<span class="filter-result-item-metro"><span>{{properties.metro}}</span>{% endif %}</span>'+
                    '        </div></div>'+
                    '        </div>'+
                    '    </a>');
            var myBalloonShadowLayout = ymaps.templateLayoutFactory.createClass('');

            var myBalloonCloseButtonLayout = ymaps.templateLayoutFactory.createClass(
                '<img class="closes" src="/asset/assets/img/closemark.png" >'
            );
            var param = $('.elite-form').serialize();
            $.ajax({
                type: 'POST',
                url: '/elite/map',
                data: {param: param},
                dataType: 'json',
                success: function(data) {
                    if(data){
                        $.each(data, function(i, val){
                            points.push([val.lat, val.lng]);
                            geoObjects[i] = new ymaps.Placemark([val.lat, val.lng], {
                                name: val.name,
                                price: val.price,
                                foto: val.foto,
                                address: val.address,
                                rayon: val.rayon,
                                metro: val.metro,
                                link: val.link
                            }, getPointOptions());
                        });
                        clusterer.add(geoObjects);
                        myMap.geoObjects.add(clusterer);
                        myMap.setBounds(clusterer.getBounds(), {
                            checkZoomRange: true
                        });
                    }
                }
            });
        });
    }


    /*if ($("#exclusive-map").length > 0){
        ymaps.ready(function () {
            var points = [];
            var myMap = new ymaps.Map('exclusive-map', {
                    center: [30.338731, 59.941115],
                    zoom: 9,
                    controls: ['zoomControl', 'fullscreenControl'],
                    behaviors: ["drag", "dblClickZoom"]
                }),
                MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div style="color: #D6E6EB; font-weight: bold; font-size: 12px;">$[properties.geoObjects.length]</div>'),
                clusterer = new ymaps.Clusterer({
                    clusterIcons: [{
                        href: '/asset/assets/img/clust.png',
                        size: [46, 46],
                        offset: [-23, -23]
                    }],
                    clusterIconContentLayout: MyIconContentLayout,
                    openBalloonOnClick: false,
                    groupByCoordinates: false,
                    clusterDisableClickZoom: false,
                    clusterHideIconOnBalloonOpen: false,
                    geoObjectHideIconOnBalloonOpen: false

                }),
                getPointOptions = function () {
                    return {
                        iconLayout: 'default#image',
                        iconImageHref: '/asset/assets/img/markerb.png',
                        iconImageSize: [22, 28],
                        iconImageOffset: [-11, -28],
                        balloonCloseButton: false,
                        balloonMinWidth: 215,
                        balloonMaxWidth: 215,
                        balloonLayout: myBalloonLayout,
                        balloonContentLayout: myBalloonContentLayout,
                        balloonContentBodyLayout: myBalloonContentBodyLayout,
                        balloonCloseButtonLayout: myBalloonCloseButtonLayout,
                        balloonShadow: false,
                        balloonOffset: [10, -226],
                        balloonShadowLayout: myBalloonShadowLayout,
                        balloonPanelMaxMapArea: 0

                    };
                },
                geoObjects = [];
            var myBalloonLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-wrapper">' +
                    '<div class="center-balloon-content-wrapper">' +
                    '$[[options.contentLayout]]' +
                    '<div class="center-balloon-close">$[[options.closeButtonLayout]]</div>'+
                    '</div>' +
                    '<div class="center-balloon-tale-wrapper"><div class="center-balloon-tale"></div></div>' +

                    '</div>',
                {
                    build: function () {
                        this.constructor.superclass.build.call(this);
                        this._$element = $('.center-balloon-wrapper', this.getParentElement());
                        this.applyElementOffset();
                        this._$element.find('.center-balloon-close')
                            .on('click', $.proxy(this.onCloseClick, this));
                    },
                    clear: function () {
                        this._$element.find('.center-balloon-close')
                            .off('click');
                        this.constructor.superclass.clear.call(this);
                    },
                    onSublayoutSizeChange: function () {
                        myBalloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments);
                        if(!this._isElement(this._$element)) {
                            return;
                        }
                        this.applyElementOffset();
                        this.events.fire('shapechange');
                    },
                    applyElementOffset: function () {
                        this._$element.css({
                            left: -(this._$element[0].offsetWidth / 2),
                            top: -(this._$element[0].offsetHeight / 2)
                        });
                    },
                    onCloseClick: function (e) {
                        e.preventDefault();
                        this.events.fire('userclose');
                    },
                    getShape: function () {
                        if(!this._isElement(this._$element)) {
                            return myBalloonLayout.superclass.getShape.call(this);
                        }
                        var position = this._$element.position();
                        return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
                            [position.left, position.top], [
                                position.left + this._$element[0].offsetWidth,
                                position.top + this._$element[0].offsetHeight
                            ]
                        ]));
                    },
                    _isElement: function (element) {
                        return element && element[0];
                    }
                }
            );

            var myBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-content">'+
                    '$[[options.contentBodyLayout]]'
                    +'</div>'
            );

            var myBalloonContentBodyLayout = ymaps.templateLayoutFactory.createClass(
                '<a href="{{properties.link}}" class="filter-result-item-map sm_search" data-category="SelectObject" data-label="buildings">'+
                    '    <div class="img_fil"><img class="filter-result-item-img" src="{{properties.foto}}" alt="{{properties.name}}"></div>'+
                    '        <div class="priceres">'+'$[properties.price]'+'</div>'+
                    '        <div class="filter-result-item-body">'+
                    '            <p class="filter-result-item-rayon">{{properties.rayon}}</p>'+
                    '            <p class="filter-result-item-name">{{properties.name}}</p>'+
                    '            <p class="filter-result-item-size">{{properties.srok}}</p>'+
                    '            <div class="addbott"><div class="filter-result-item-address">{{properties.address}}'+
                    '            {% if properties.metro %}<span class="filter-result-item-metro"><span>{{properties.metro}}</span>{% endif %}</span>'+
                    '        </div></div>'+
                    '        </div>'+
                    '    </a>');
            var myBalloonShadowLayout = ymaps.templateLayoutFactory.createClass('');

            var myBalloonCloseButtonLayout = ymaps.templateLayoutFactory.createClass(
                '<img class="closes" src="/asset/assets/img/closemark.png" >'
            );
            var param = $('.exclusive-form').serialize();
            $.ajax({
                type: 'POST',
                url: '/exclusive/map',
                data: {param: param},
                dataType: 'json',
                success: function(data) {
                    if(data){
                        $.each(data, function(i, val){
                            points.push([val.lat, val.lng]);
                            geoObjects[i] = new ymaps.Placemark([val.lat, val.lng], {
                                name: val.name,
                                price: val.price,
                                foto: val.foto,
                                address: val.address,
                                rayon: val.rayon,
                                metro: val.metro,
                                link: val.link
                            }, getPointOptions());
                        });
                        clusterer.add(geoObjects);
                        myMap.geoObjects.add(clusterer);
                        myMap.setBounds(clusterer.getBounds(), {
                            checkZoomRange: true
                        });
                    }
                }
            });
        });
    }*/

    if ($("#nov-map1").length > 0){
        ymaps.ready(function () {
            var points = [];
            var myMap = new ymaps.Map('nov-map', {
                    center: [30.338731, 59.941115],
                    zoom: 9,
                    controls: ['zoomControl', 'fullscreenControl'],
                    behaviors: ["drag", "dblClickZoom"]
                }),
                MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div style="color: #D6E6EB; font-weight: bold; font-size: 12px;">$[properties.geoObjects.length]</div>'),
                clusterer = new ymaps.Clusterer({
                    clusterIcons: [{
                        href: '/asset/assets/img/clust.png',
                        size: [46, 46],
                        offset: [-23, -23]
                    }],
                    clusterIconContentLayout: MyIconContentLayout,
                    openBalloonOnClick: false,
                    groupByCoordinates: false,
                    clusterDisableClickZoom: false,
                    clusterHideIconOnBalloonOpen: false,
                    geoObjectHideIconOnBalloonOpen: false

                }),
                getPointOptions = function () {
                    return {
                        iconLayout: 'default#image',
                        iconImageHref: '/asset/assets/img/markerb.png',
                        iconImageSize: [22, 28],
                        iconImageOffset: [-11, -28],
                        balloonCloseButton: false,
                        balloonMinWidth: 215,
                        balloonMaxWidth: 215,
                        balloonLayout: myBalloonLayout,
                        balloonContentLayout: myBalloonContentLayout,
                        balloonContentBodyLayout: myBalloonContentBodyLayout,
                        balloonCloseButtonLayout: myBalloonCloseButtonLayout,
                        balloonShadow: false,
                        balloonOffset: [10, -226],
                        balloonShadowLayout: myBalloonShadowLayout,
                        balloonPanelMaxMapArea: 0

                    };
                },
                geoObjects = [];
            var myBalloonLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-wrapper">' +
                    '<div class="center-balloon-content-wrapper">' +
                    '$[[options.contentLayout]]' +
                    '<div class="center-balloon-close">$[[options.closeButtonLayout]]</div>'+
                    '</div>' +
                    '<div class="center-balloon-tale-wrapper"><div class="center-balloon-tale"></div></div>' +

                    '</div>',
                {
                    build: function () {
                        this.constructor.superclass.build.call(this);
                        this._$element = $('.center-balloon-wrapper', this.getParentElement());
                        this.applyElementOffset();
                        this._$element.find('.center-balloon-close')
                            .on('click', $.proxy(this.onCloseClick, this));
                    },
                    clear: function () {
                        this._$element.find('.center-balloon-close')
                            .off('click');
                        this.constructor.superclass.clear.call(this);
                    },
                    onSublayoutSizeChange: function () {
                        myBalloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments);
                        if(!this._isElement(this._$element)) {
                            return;
                        }
                        this.applyElementOffset();
                        this.events.fire('shapechange');
                    },
                    applyElementOffset: function () {
                        this._$element.css({
                            left: -(this._$element[0].offsetWidth / 2),
                            top: -(this._$element[0].offsetHeight / 2)
                        });
                    },
                    onCloseClick: function (e) {
                        e.preventDefault();
                        this.events.fire('userclose');
                    },
                    getShape: function () {
                        if(!this._isElement(this._$element)) {
                            return myBalloonLayout.superclass.getShape.call(this);
                        }
                        var position = this._$element.position();
                        return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
                            [position.left, position.top], [
                                position.left + this._$element[0].offsetWidth,
                                position.top + this._$element[0].offsetHeight
                            ]
                        ]));
                    },
                    _isElement: function (element) {
                        return element && element[0];
                    }
                }
            );

            var myBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="center-balloon-content">'+
                    '$[[options.contentBodyLayout]]'
                    +'</div>'
            );

            var myBalloonContentBodyLayout = ymaps.templateLayoutFactory.createClass(
                '<a href="{{properties.link}}" class="filter-result-item-map sm_search" data-category="SelectObject" data-label="buildings">'+
                    '    <div class="img_fil"><img class="filter-result-item-img" src="{{properties.foto}}" alt="{{properties.name}}"></div>'+
                    '        <div class="priceres">'+'$[properties.price]'+'</div>'+
                    '        <div class="filter-result-item-body">'+
                    '            <p class="filter-result-item-rayon">{{properties.rayon}}</p>'+
                    '            <p class="filter-result-item-name">{{properties.name}}</p>'+
                    '            <p class="filter-result-item-size">{{properties.srok}}</p>'+
                    '            <div class="addbott"><div class="filter-result-item-address">{{properties.address}}'+
                    '            {% if properties.metro %}<span class="filter-result-item-metro"><span>{{properties.metro}}</span>{% endif %}</span>'+
                    '        </div></div>'+
                    '        </div>'+
                    '    </a>');
            var myBalloonShadowLayout = ymaps.templateLayoutFactory.createClass('');

            var myBalloonCloseButtonLayout = ymaps.templateLayoutFactory.createClass(
                '<img class="closes" src="/asset/assets/img/closemark.png" >'
            );
            var param = $('.buildings-form').serialize();
            $.ajax({
                type: 'POST',
                url: '/buildings/map',
                data: {param: param},
                dataType: 'json',
                success: function(data) {
                    if(data){
                        $.each(data, function(i, val){
                            points.push([val.lat, val.lng]);
                            geoObjects[i] = new ymaps.Placemark([val.lat, val.lng], {
                                name: val.name,
                                price: val.price,
                                foto: val.foto,
                                address: val.address,
                                rayon: val.rayon,
                                metro: val.metro,
                                link: val.link
                            }, getPointOptions());
                        });
                        clusterer.add(geoObjects);
                        myMap.geoObjects.add(clusterer);
                        myMap.setBounds(clusterer.getBounds(), {
                            checkZoomRange: true
                        });
                    }
                }
            });
        });
        /*
        var myLatlng = new google.maps.LatLng(59.954764, 30.290562);
        var mapOptions = {
            zoom: 10,
            center: myLatlng,
            disableDefaultUI: true,
            scrollwheel: false
        };
        var styles = [{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]}];
        var styledMap = new google.maps.StyledMapType(styles, {name: "Styled Map"});


        var map_view = new google.maps.Map(document.getElementById('nov-map'), mapOptions);
        var bounds = new google.maps.LatLngBounds();
        map_view.mapTypes.set('map_style', styledMap);
        map_view.setMapTypeId('map_style');
        $("body").on('click', ".plusz", function(e){
            e.preventDefault();
            map_view.setZoom(map_view.getZoom()+1);
        });

        $("body").on('click', ".minusz", function(e){
            e.preventDefault();
            map_view.setZoom(map_view.getZoom()-1);
        });
        var markers = [];
        initmap();
        $.ajax({
            type: 'POST',
            url: '/buildings/map',
            dataType: 'json',
            success: function(data) {

                if(data){
                    $.each(data, function(i, val){
                        var mark = new google.maps.Marker({
                            position: new google.maps.LatLng(val.lat, val.lng),
                            map: map_view,
                            icon: '/asset/assets/img/markerb.png',
                            title: 'ЖК «'+val.name+'»'
                        });
                        google.maps.event.addListener(mark, "click", function() {
                            createIb(mark, val);
                        });
                        bounds.extend(new google.maps.LatLng(val.lat, val.lng));
                        markers.push(mark);
                    });
                    map_view.fitBounds(bounds);
                    var clusterStyles = [
                        {
                            textColor: '#c1e5f5',
                            url: '/asset/assets/img/clust.png',
                            height: 46,
                            width: 46
                        },
                        {
                            textColor: '#c1e5f5',
                            url: '/asset/assets/img/clust.png',
                            height: 46,
                            width: 46
                        },
                        {
                            textColor: '#c1e5f5',
                            url: '/asset/assets/img/clust.png',
                            height: 46,
                            width: 46
                        }
                    ];
                    var mcOptions = { maxZoom: 14, styles: clusterStyles};

                    var markerCluster = new MarkerClusterer(map_view, markers,mcOptions);
                }
            }
        });*/
    }

    $('.multiselect-rayon').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Все',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Все';
            }
            else if (options.length > 1) {
                return 'Выбрано: '+options.length;
            }
            else {
                var selected = '';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.multiselect-rayon option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_rayon").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_rayon").val(values);
            }
        }
    });

    $('.multiselect-rayon-residential').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Район: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Район: не выбрано';
            }
            else if (options.length > 1) {
                return 'Район: выбрано - '+options.length;
            }
            else {
                var selected = 'Район: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.multiselect-rayon-residential option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_rayon").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_rayon").val(values);
            }
            sendFormFilter('/residential/countFind', 'residential');
        }
    });

    $('.multiselect-matherial').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Материал дома: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Материал дома: не выбрано';
            }
            else if (options.length > 1) {
                return 'Материал дома: выбрано - '+options.length;
            }
            else {
                var selected = 'Материал дома: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.multiselect-matherial option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_matherial").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_matherial").val(values);
            }
            sendFormFilter('/residential/countFind', 'residential');
        }
    });

    $('.mrayonbuildings').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Все',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Все';
            }
            else if (options.length > 1) {
                return 'Выбрано: '+options.length;
            }
            else {
                var selected = '';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mrayonbuildings option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_build_rayon").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_build_rayon").val(values);
            }
            sendFormFilter('/buildings/countFind', 'buildings');
        }
    });

    $('.mrayonresale').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Все',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Все';
            }
            else if (options.length > 1) {
                return 'Выбрано: '+options.length;
            }
            else {
                var selected = '';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mrayonresale option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_resale_rayon").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_resale_rayon").val(values);
            }
            sendFormFilter('/resale/countFind', 'resale');
        }
    });


    $('#verticali').lightSlider({
        gallery:true,
        item:1,
        controls: false,
        thumbItem:0,
        thumbMargin:10,
        slideMargin:0
    });



    $("#verticali-resale").sliderkit({
        mousewheel:false,
        keyboard:true,
        shownavitems:navit,
        panelbtnshover:false,
        delaycaptions:false,
        auto:false,
        circular:false,
        navscrollatend:false,
        navpanelautoswitch:false,
        panelclick: true
    });

    $('#bigslidecat').lightSlider({
        item:1,
        slideMargin:0,
        verticalHeight:774,
        loop:true
    });

    $('#repslide').lightSlider({
        item:1,
        slideMargin:0,
        loop:false,
        speed: 1000
    });

    $('#mainslide').lightSlider({
        item:1,
        slideMargin:0,
        loop:false,
        speed: 1000
    });

    $('.multiselect-room').multiselect({
        numberDisplayed: 2,
        buttonWidth: '114px',
        maxHeight: 400,
        nonSelectedText: 'Все',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Все';
            }
            else if (options.length > 1) {
                return 'Выбрано: '+options.length;
            }
            else {
                var selected = '';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.multiselect-room option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_room").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_room").val(values);
            }
        }
    });

    $('.mroombuildings').multiselect({
        numberDisplayed: 2,
        buttonWidth: '114px',
        maxHeight: 400,
        nonSelectedText: 'Все',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Все';
            }
            else if (options.length > 1) {
                return 'Выбрано: '+options.length;
            }
            else {
                var selected = '';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mroombuildings option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_build_room").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_build_room").val(values);
            }
            sendFormFilter('/buildings/countFind', 'buildings');
        }
    });

    $('.mbuildingstometro').multiselect({
        numberDisplayed: 2,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'До метро',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'До метро';
            }
            else if (options.length > 1) {
                return 'До метро: выбрано - '+options.length;
            }
            else {
                var selected = 'До метро: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mbuildingstometro option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_build_tometro").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_build_tometro").val(values);
            }
            sendFormFilter('/buildings/countFind', 'buildings');
        }
    });

    $('.mbuildingsclass').multiselect({
        numberDisplayed: 2,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Класс жилья: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Класс жилья: не выбрано';
            }
            else if (options.length > 1) {
                return 'Класс жилья: выбрано - '+options.length;
            }
            else {
                var selected = 'Класс жилья: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mbuildingsclass option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_build_class").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_build_class").val(values);
            }
            sendFormFilter('/buildings/countFind', 'buildings');
        }
    });

    $('.massignmentclass').multiselect({
        numberDisplayed: 2,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Класс жилья: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Класс жилья: не выбрано';
            }
            else if (options.length > 1) {
                return 'Класс жилья: выбрано - '+options.length;
            }
            else {
                var selected = 'Класс жилья: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.massignmentclass option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_build_class").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_build_class").val(values);
            }
            sendFormFilter('/assignment/countFind', 'assignment');
        }
    });

	if (typeof parami['srok'] !== 'undefined') {
		var sroks = parami['srok'].split(',');
		$(".sel_build_deadline").val(parami['srok']);
		//console.log( $(".wd235").children().eq(2).attr('style'));
		if(sroks.length > 1){
			$('button[title="Срок сдачи: не выбрано"]').value='Срок сдачи: выбрано - '+sroks.length;
		}else{
			if(sroks[0]=='1'){
				$('button[title="Срок сдачи: не выбрано"]').value='Срок сдачи: Сдан';
			}else{
				$('button[title="Срок сдачи: не выбрано"]').label='Срок сдачи: '+sroks[0];
			}
		}
	}
	var flog=1;
	
	
    $('.mdeadlinebuildings').multiselect({
        numberDisplayed: 2,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Срок сдачи: не выбрано',
        buttonText: function(options) {
			//alert('dich'+flog);
			/*if (typeof parami['srok'] !== 'undefined' && flog==1 && parami['srok'] !== '0') {
				var sroks = parami['srok'].split(',');
				if(sroks.length > 1){
					return 'Срок сдачи: выбрано - '+sroks.length;
				}else{
					return 'Срок сдачи: '+sroks[0];
				}
				//alert('dich'+flog);
				flog=0;
			}*/
            if (options.length == 0) {
                return 'Срок сдачи: не выбрано';
            }
            else if (options.length > 1) {
                return 'Срок сдачи: выбрано - '+options.length;
            }
            else {
                var selected = 'Срок сдачи: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mdeadlinebuildings option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_build_deadline").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_build_deadline").val(values);
            }
            sendFormFilter('/buildings/countFind', 'buildings');
        }
    });

    $('.mdeadlineassignment').multiselect({
        numberDisplayed: 2,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Срок сдачи: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Срок сдачи: не выбрано';
            }
            else if (options.length > 1) {
                return 'Срок сдачи: выбрано - '+options.length;
            }
            else {
                var selected = 'Срок сдачи: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mdeadlineassignment option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_build_deadline").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_build_deadline").val(values);
            }
            sendFormFilter('/assignment/countFind', 'assignment');
        }
    });

    $('.multiselect-type').multiselect({
        numberDisplayed: 2,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Все',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Все';
            }
            else if (options.length > 1) {
                return 'Выбрано: '+options.length;
            }
            else {
                var selected = '';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.multiselect-type option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_type").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_type").val(values);
            }
        }
    });

    $('.multiselect-type-elite').multiselect({
        numberDisplayed: 2,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Тип объекта: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Тип объекта: не выбрано';
            }
            else if (options.length > 0) {
                return 'Тип объекта:  выбрано - '+options.length;
            }
            /*else {
             var selected = '';
             options.each(function() {
             selected += $(this).text() + ', ';
             });
             return selected.substr(0, selected.length -2)
             }*/
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.multiselect-type-elite option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_type").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_type").val(values);
            }
            sendFormFilter('/elite/countFind', 'elite');
        }
    });

    $('.multiselect-type-exclusive').multiselect({
        numberDisplayed: 2,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Тип объекта: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Тип объекта: не выбрано';
            }
            else if (options.length > 0) {
                return 'Тип объекта:  выбрано - '+options.length;
            }
            /*else {
             var selected = '';
             options.each(function() {
             selected += $(this).text() + ', ';
             });
             return selected.substr(0, selected.length -2)
             }*/
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.multiselect-type-exclusive option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_type").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_type").val(values);
            }
            sendFormFilter('/exclusive/countFind', 'exclusive');
        }
    });

    $('.multiselect-type-residential').multiselect({
        numberDisplayed: 2,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Все',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Тип объекта: не выбрано';
            }
            else if (options.length > 0) {
                return 'Тип объекта: выбрано - '+options.length;
            }
            /*else {
             var selected = '';
             options.each(function() {
             selected += $(this).text() + ', ';
             });
             return selected.substr(0, selected.length -2)
             }*/
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.multiselect-type-residential option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_type").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_type").val(values);
            }
            sendFormFilter('/residential/countFind', 'residential');
        }
    });


    $('.mtypebuildings').multiselect({
        numberDisplayed: 2,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Все',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Тип здания: не выбрано';
            }
            else if (options.length > 1) {
                return 'Тип здания: выбрано - '+options.length;
            }
            else {
                var selected = 'Тип здания: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mtypebuildings option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_build_type").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_build_type").val(values);
            }
            sendFormFilter('/buildings/countFind', 'buildings');
        }
    });

    $('.mbankbuildings').multiselect({
        numberDisplayed: 2,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Все',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Банк: не выбрано';
            }
            else if (options.length > 1) {
                return 'Банк: выбрано - '+options.length;
            }
            else {
                var selected = 'Банк: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mbankbuildings option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_build_bank").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_build_bank").val(values);
            }
            sendFormFilter('/buildings/countFind', 'buildings');
        }
    });

    $('.mtypeassignment').multiselect({
        numberDisplayed: 2,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Все',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Тип здания: не выбрано';
            }
            else if (options.length > 1) {
                return 'Тип здания: выбрано - '+options.length;
            }
            else {
                var selected = 'Тип здания: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mtypeassignment option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_build_type").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_build_type").val(values);
            }
            sendFormFilter('/assignment/countFind', 'assignment');
        }
    });

    $('.custome-select').each(function() {
        var title = $(this).attr('title');
        if ($('option:selected', this).val() !== '') title = $('option:selected', this).text();
        $(this)
            .css({
                'z-index': 10,
                'opacity': 0,
                '-khtml-appearance': 'none'
            })
            .after('<span class="select-i">' + title + '</span>')
            .change(function() {
                val = $('option:selected', this).text();
                $(this).next().text(val);
            });
    });

    /*
     $("#price-slider").slider({
     range: true,
     min: 0,
     max: 200,
     values: [$('#fPrice-from').val(), $('#fPrice-to').val()],
     create: function(event, ui) {
     var valFrom = $(this).slider('values', 0);
     var valTo = $(this).slider('values', 1);
     $(this).find('.ui-slider-handle:first').after('<span id="price-label-from" class="range-label range-label--price range-label--price-left"></span>');
     $(this).find('.ui-slider-handle:last').after('<span id="price-label-to" class="range-label range-label--price"></span>');

     $('#price-label-from').text(valFrom + 'тыс. руб.');
     $('#price-label-to').text(valTo + 'тыс. руб.');
     },
     slide: function(event, ui) {
     var $differenceLeft = $(this).find('.range-difference--left');
     var $differenceRight = $(this).find('.range-difference--right');

     $('#price-label-from').text(ui.values[0] + ' тыс. руб.');
     $('#price-label-to').text(ui.values[1] + ' тыс. руб.');

     $('#fPrice-from').val(ui.values[0]);
     $('#fPrice-to').val(ui.values[1]);
     }
     });*/
     
    //alert(pfr);
    //alert(pto);




    var bFilter = new buildingsFilter(
        {
            costMin             :   $(".price-b").data("min"),
            costMax             :   $(".price-b").data("max"),
            costCurrentMin      :   pfr,
            costCurrentMax      :   pto,
            costSlider          :   "#price-slider",
            costInputMin        :   "#fPrice-from",
            costInputMax        :   "#fPrice-to",
            stop                :   function(){
                sendFormFilter('/buildings/countFind', 'buildings');
            }
        });

    var bFilterSquare = new buildingsFilter(
        {
            costMin             :   $(".square-b").data("min"),
            costMax             :   $(".square-b").data("max"),
            costCurrentMin      :   sqf,
            costCurrentMax      :   sqt,
            costFix             :   0,
            costSlider          :   "#square-slider",
            costInputMin        :   "#fSquare-from",
            costInputMax        :   "#fSquare-to",
            stop                :   function(){
                sendFormFilter('/buildings/countFind', 'buildings');
            }
        });

    var bFilterResale = new buildingsFilter(
        {
            class               :  'resale',
            costMin             :   $(".price-b").data("min"),
            costMax             :   $(".price-b").data("max"),
            costCurrentMin      :   $('#rPrice-from').val(),
            costCurrentMax      :   $('#rPrice-to').val(),

            costSlider          :   "#price-slider-resale",
            costInputMin        :   "#rPrice-from",
            costInputMax        :   "#rPrice-to",
            stop                :   function(){
                sendFormFilter('/resale/countFind', 'resale');
            }
        });

    var bFilterSquareResale = new buildingsFilter(
        {
            costMin             :   $(".square-b").data("min"),
            costMax             :   $(".square-b").data("max"),
            costCurrentMin      :   $('#rSquare-from').val(),
            costCurrentMax      :   $('#rSquare-to').val(),
            costFix             :   0,
            costSlider          :   "#square-slider-resale",
            costInputMin        :   "#rSquare-from",
            costInputMax        :   "#rSquare-to",
            stop                :   function(){
                sendFormFilter('/resale/countFind', 'resale');
            }
        });

    var bFilterAssignment = new buildingsFilter(
        {
            class               :  'assignment',
            costMin             :   $(".price-b").data("min"),
            costMax             :   $(".price-b").data("max"),
            costCurrentMin      :   $('#assignmentPrice-from').val(),
            costCurrentMax      :   $('#assignmentPrice-to').val(),

            costSlider          :   "#price-slider-assignment",
            costInputMin        :   "#assignmentPrice-from",
            costInputMax        :   "#assignmentPrice-to",
            stop                :   function(){
                sendFormFilter('/assignment/countFind', 'assignment');
            }
        });

    var bFilterSquareAssignment = new buildingsFilter(
        {
            costMin             :   $(".square-b").data("min"),
            costMax             :   $(".square-b").data("max"),
            costCurrentMin      :   $('#assignmentSquare-from').val(),
            costCurrentMax      :   $('#assignmentSquare-to').val(),
            costFix             :   0,
            costSlider          :   "#square-slider-assignment",
            costInputMin        :   "#assignmentSquare-from",
            costInputMax        :   "#assignmentSquare-to",
            stop                :   function(){
                sendFormFilter('/assignment/countFind', 'assignment');
            }
        });

    var bFilterResidential = new buildingsFilter(
        {
            class               :  'residential',
            costMin             :   $(".price-b").data("min"),
            costMax             :   $(".price-b").data("max"),
            costCurrentMin      :   $('#resPrice-from').val(),
            costCurrentMax      :   $('#resPrice-to').val(),
            costSlider          :   "#price-slider-residential",
            costInputMin        :   "#resPrice-from",
            costInputMax        :   "#resPrice-to",
            stop                :   function(){
                sendFormFilter('/residential/countFind', 'residential');
            }
        });

    var bFilterResidentialSquare = new buildingsFilter(
        {
            class               :  'residential',
            costMin             :   $(".square-b").data("min"),
            costMax             :   $(".square-b").data("max"),
            costCurrentMin      :   $('#resSquare-from').val(),
            costCurrentMax      :   $('#resSquare-to').val(),
            costFix             :   0,
            costSlider          :   "#square-slider-residential",
            costInputMin        :   "#resSquare-from",
            costInputMax        :   "#resSquare-to",
            stop                :   function(){
                sendFormFilter('/residential/countFind', 'residential');
            }
        });

    var bFilterResidentialSquareU = new buildingsFilter(
        {
            class               :  'residential',
            costMin             :   $(".squareu-b").data("min"),
            costMax             :   $(".squareu-b").data("max"),
            costCurrentMin      :   $('#resSquareu-from').val(),
            costCurrentMax      :   $('#resSquareu-to').val(),
            costFix             :   0,
            costSlider          :   "#squareu-slider-residential",
            costInputMin        :   "#resSquareu-from",
            costInputMax        :   "#resSquareu-to",
            stop                :   function(){
                sendFormFilter('/residential/countFind', 'residential');
            }
        });

    var bFilterSquareAbroad = new buildingsFilter(
        {
            class               :  'abroad',
            costMin             :   $(".square-b").data("min"),
            costMax             :   $(".square-b").data("max"),
            costCurrentMin      :   $('#aSquare-from').val(),
            costCurrentMax      :   $('#aSquare-to').val(),
            costFix             :   0,
            costSlider          :   "#square-slider-abroad",
            costInputMin        :   "#aSquare-from",
            costInputMax        :   "#aSquare-to",
            stop                :   function(){
                sendFormFilter('/abroad/countFind', 'abroad');
            }
        });

    var bFilterSquareAbroadLand = new buildingsFilter(
        {
            class               :  'abroad',
            costMin             :   $(".square-ba").data("min"),
            costMax             :   $(".square-ba").data("max"),
            costCurrentMin      :   $('#alSquare-from').val(),
            costCurrentMax      :   $('#alSquare-to').val(),
            costFix             :   0,
            costSlider          :   "#square-slider-abroad-land",
            costInputMin        :   "#alSquare-from",
            costInputMax        :   "#alSquare-to",
            stop                :   function(){
                sendFormFilter('/abroad/countFind', 'abroad');
            }
        });

    var bFilterAbroad = new buildingsFilter(
        {
            class               :  'abroad',
            costMin             :   $(".price-b").data("min"),
            costMax             :   $(".price-b").data("max"),
            costCurrentMin      :   $('#aPrice-from').val(),
            costCurrentMax      :   $('#aPrice-to').val(),
            costSlider          :   "#price-slider-abroad",
            costInputMin        :   "#aPrice-from",
            costInputMax        :   "#aPrice-to",
            stop                :   function(){
                sendFormFilter('/abroad/countFind', 'abroad');
            }
        });

    var bFilterElite = new buildingsFilter(
        {
            class               :  'elite',
            costMin             :   $(".price-b").data("min"),
            costMax             :   $(".price-b").data("max"),
            costCurrentMin      :   $('#ePrice-from').val(),
            costCurrentMax      :   $('#ePrice-to').val(),
            costSlider          :   "#price-slider-elite",
            costInputMin        :   "#ePrice-from",
            costInputMax        :   "#ePrice-to",
            stop                :   function(){
                sendFormFilter('/elite/countFind', 'elite');
            }

        });

    var bFilterEliteSquare = new buildingsFilter(
        {
            class               :  'elite',
            costMin             :   $(".square-b").data("min"),
            costMax             :   $(".square-b").data("max"),
            costCurrentMin      :   $('#eSquare-from').val(),
            costCurrentMax      :   $('#eSquare-to').val(),
            costFix             :   0,
            costSlider          :   "#square-slider-elite",
            costInputMin        :   "#eSquare-from",
            costInputMax        :   "#eSquare-to",
            stop                :   function(){
                sendFormFilter('/elite/countFind', 'elite');
            }

        });

    var bFilterExclusive = new buildingsFilter(
        {
            class               :  'exclusive',
            costMin             :   $(".price-b").data("min"),
            costMax             :   $(".price-b").data("max"),
            costCurrentMin      :   $('#exclPrice-from').val(),
            costCurrentMax      :   $('#exclPrice-to').val(),
            costSlider          :   "#price-slider-exclusive",
            costInputMin        :   "#exclPrice-from",
            costInputMax        :   "#exclPrice-to",
            stop                :   function(){
                sendFormFilter('/exclusive/countFind', 'exclusive');
            }

        });

    var bFilterExclusiveSquare = new buildingsFilter(
        {
            class               :  'exclusive',
            costMin             :   $(".square-b").data("min"),
            costMax             :   $(".square-b").data("max"),
            costCurrentMin      :   $('#exclSquare-from').val(),
            costCurrentMax      :   $('#exclSquare-to').val(),
            costFix             :   0,
            costSlider          :   "#square-slider-exclusive",
            costInputMin        :   "#exclSquare-from",
            costInputMax        :   "#exclSquare-to",
            stop                :   function(){
                sendFormFilter('/exclusive/countFind', 'exclusive');
            }

        });

    var bFilterCommercial = new buildingsFilter(
        {
            class               :  'commercial',
            costMin             :   $(".price-b").data("min"),
            costMax             :   $(".price-b").data("max"),
            costCurrentMin      :   $('#cPrice-from').val(),
            costCurrentMax      :   $('#cPrice-to').val(),
            costSlider          :   "#price-slider-commercial",
            costInputMin        :   "#cPrice-from",
            costInputMax        :   "#cPrice-to",
            stop                :   function(){
                sendFormFilter('/commercial/countFind', 'commercial');
            }
        });

    var bFilterCommercialSquare = new buildingsFilter(
        {
            class               :  'commercial',
            costMin             :   $(".square-b").data("min"),
            costMax             :   $(".square-b").data("max"),
            costCurrentMin      :   $('#cSquare-from').val(),
            costCurrentMax      :   $('#cSquare-to').val(),
            costFix             :   0,
            costSlider          :   "#square-slider-commercial",
            costInputMin        :   "#cSquare-from",
            costInputMax        :   "#cSquare-to",
            stop                :   function(){
                sendFormFilter('/commercial/countFind', 'commercial');
            }
        });

    var bFilterLand = new buildingsFilter(
        {
            class               :  'commercial',
            costMin             :   $(".price-b").data("min"),
            costMax             :   $(".price-b").data("max"),
            costCurrentMin      :   $('#lPrice-from').val(),
            costCurrentMax      :   $('#lPrice-to').val(),
            costSlider          :   "#price-slider-land",
            costInputMin        :   "#lPrice-from",
            costInputMax        :   "#lPrice-to",
            stop                :   function(){
                sendFormFilter('/land/countFind', 'land');
            }
        });

    var bFilterSquareLand = new buildingsFilter(
        {
            costMin             :   $(".square-b").data("min"),
            costMax             :   $(".square-b").data("max"),
            costCurrentMin      :   $('#lSquare-from').val(),
            costCurrentMax      :   $('#lSquare-to').val(),
            costFix             :   0,
            costSlider          :   "#square-slider-land",
            costInputMin        :   "#lSquare-from",
            costInputMax        :   "#lSquare-to",
            stop                :   function(){
                sendFormFilter('/land/countFind', 'land');
            }
        });

    var bFilterArenda = new buildingsFilter(
        {
            class               :  'elite',
            costMin             :   $(".price-b").data("min"),
            costMax             :   $(".price-b").data("max"),
            costCurrentMin      :   $('#arPrice-from').val(),
            costCurrentMax      :   $('#arPrice-to').val(),
            costSlider          :   "#price-slider-arenda",
            costInputMin        :   "#arPrice-from",
            costInputMax        :   "#arPrice-to",
            stop                :   function(){
                sendFormFilter('/arenda/countFind', 'arenda');
            }

        });

    var bFilterArendaSquare = new buildingsFilter(
        {
            class               :  'elite',
            costMin             :   $(".square-b").data("min"),
            costMax             :   $(".square-b").data("max"),
            costCurrentMin      :   $('#arSquare-from').val(),
            costCurrentMax      :   $('#arSquare-to').val(),
            costFix             :   0,
            costSlider          :   "#square-slider-arenda",
            costInputMin        :   "#arSquare-from",
            costInputMax        :   "#arSquare-to",
            stop                :   function(){
                sendFormFilter('/arenda/countFind', 'arenda');
            }

        });

    $(document).on('change', '.deadlinebuild', function(event) {
        event.preventDefault();
        sendFormFilter('/buildings/countFind', 'buildings');
    });




    $(document).on('click', '.js-dependent-link', function(event) {
        event.preventDefault();
        showDependent($(this));
    });

    $('body').on('click', '.plist', function(e){
        e.preventDefault();

        var plit = $(this).attr("data-value");
        if (!$(this).hasClass('chplit'))
            return false;

        $('.plist').toggleClass('chplit');
        $.ajax({
            type: 'POST',
            url: location.href,
            dataType: 'html',
            data: {
                chplit: plit
            },
            success: function(data) {

                if(data){
                    $('.ajax-source').html(data);
                    $(".sortings-filt").selectBoxIt({defaultText: "Название ЖК"}).refresh;
                    $(".sortings-filt-date").selectBoxIt({defaultText: "Стоимость"}).refresh;
                }
            }
        });
    });

    function showDependent(link) {
        var $link = link;
        var $data = $link.data('dependent');
        var $el = $('[data-dependent="' + $data + '"]');

        if (!$link.hasClass('active')) {
            $el.siblings().removeClass('active');
            $el.addClass('active');
        }
    }

    $('.click-block-complex-intro-btn').on('click', function(){
        //$.scrollTo('#scroll-child', 1300);
        return false;
    });

    $('.realty').waypoint(function(direction) {
        $('.realty .item').addClass('animated flipInY', direction !== 'Up');
    }, { offset: '90%' });

    $('.cont-first').waypoint(function(direction) {
        $(this).addClass('animated bounceInLeft', direction !== 'Up');
        $('.cont-second').addClass('animated bounceInRight', direction !== 'Up');
    }, { offset: '70%' });

    $('.anim-otz').waypoint(function(direction) {
        $(this).find('.item').addClass('animated flipInX', direction !== 'Up');
    }, { offset: '50%' });

    clickButton = '';
    zTd = {};

    /*$('table .complex-table-btn').on('click', function(e){

     window.event.stopPropagation();

     var idx = $(this).data('id'),
     form = $('.form-zakaz'),
     zName = $('.block-complex-intro-col h1').text(),
     zRow = $('.idx-'+idx).closest('tr');

     form.find('.lp-header').text('"'+zName+'"');
     zRow.find('td').each(function(i){
     if(i == 6) {
     zTd[i] = $(this).text().replace('ЗАБРОНИРОВАТЬ', '');
     } else {
     zTd[i] = $(this).html();
     }
     });

     zTd[7] = $(this).attr('href');
     zTd[8] = zName;
     zTd[9] = document.URL;

     var rooms = '';

     if ($(this).attr("data-rooms")){
     rooms = $(this).attr("data-rooms");
     }
     form.find('.rp-room').find('span').text(rooms);
     zTd[5] = $(zTd[5]).attr('href');
     var imgs = zTd[5];
     if ($(this).attr("data-img")){
     imgs = $(this).attr("data-img");
     }
     form.find('.li-img-block').find('a').attr('href', imgs);
     form.find('.li-img-block').find('img').attr('src', imgs);
     zTd[5] = imgs;
     var myimage = form.find('.li-img-block').find('img');
     var wi = myimage[0].width;
     var hi = myimage[0].height;
     myimage.removeAttr('style');
     if(wi > hi) { myimage.width('90%'); myimage.height('auto'); }

     form.find('.lp-price').text('ID: '+zTd[6]);

     clickButton = 'complex';

     var inst = $.remodal.lookup[$('[data-remodal-id=modal]').data('remodal')];
     inst.open();
     $(".remodal").attr("tabindex",1).focus();
     $(".remodal .remodal-form .inp-name").focus();
     });*/

    $('table .showplan').on('click', function(){
        window.event.stopPropagation();
        var plan = $(".form-plan");
        $('.remod .head').text($(this).attr("data-title"));
        plan.find('.li-img-block').find('img').attr('src', $(this).attr("data-img"));
        plan.find('.li-img-block').find('a').attr('href', $(this).attr("data-img"));
        var inst = $.remodal.lookup[$('[data-remodal-id=modalplan]').data('remodal')];
        inst.open();
    });

    $('.printbtn').click(function(e){
        window.print();
        e.preventDefault();
    });

    $('.want-view').on('click', function(){
        var form = $('.form-zakaz'),
            zPrice = $('.complex-info-col-left').find('.complex-title').first().find('span').text(),
            zName = $('.block-complex-intro-col h1').text();

        zName = zName.replace('<span class="price-info-resale">'+$('.block-complex-intro-col h1').text()+'</span>', '');

        if($('.complex-info-col-left').data('room') != '') {
            zTd[0] = $('.complex-info-col-left').data('room');
        } else {
            zTd[0] = '-';
        }
        zTd[5] = $('.complex-info-col-left').data('img');
        zTd[6] = zPrice;
        zTd[8] = zName;
        zTd[9] = window.location.host + '/' + document.URL;

        form.find('.lp-header').text(zName);
        form.find('.rp-room').find('span').text(zTd[0]);
        form.find('.li-img-block').find('a').attr('href', zTd[5]);
        form.find('.li-img-block').find('img').attr('src', zTd[5]);

        var myimage = form.find('.li-img-block').find('img');
        var wi = myimage[0].width;
        var hi = myimage[0].height;
        myimage.removeAttr('style');
        if(wi > hi) { myimage.width('90%'); myimage.height('auto'); }

        form.find('.lp-price').text(zTd[6]);

        clickButton = 'object';

        var inst = $.remodal.lookup[$('[data-remodal-id=modal]').data('remodal')];
        inst.open();
        return false;
    });

    $('.checkboxes').on('click', function(){
        $(this).toggleClass('active');
    });




    $('.form-zakaz .submit-remodal').on('click', function(){

        var $name = $(this).closest('.form-zakaz').find('.inp-name').val(),
            $phone = $(this).closest('.form-zakaz').find('.inp-phone').val();

        if($name === '' || $phone === '')
        {
            $.growl.error({ title: "Сообщение:", message: "<br>Заполните пожалуйста поля <br>Имя и Телефон" });
            return false
        }

        $('.inp-name').val('');
        $('.inp-phone').val('');

        fsendAjax('email', $name, $phone);

        if ($(".sm_zakaz").length > 0){
            var label = $(".sm_zakaz").attr("data-label");
            var category = 'Form.reserve';
            ga('send', 'event', category, 'Send', label);
            yaCounter26750394.reachGoal(category);
            roistat.event.send(category);
        }

        return false;
    });

    loadHash();
 
    $("body").on("click", ".changeinfo a", function(e){
        e.preventDefault();
        if (!$(this).closest("li").hasClass("active")){
            $(".changeinfo").removeClass("active");
            $(this).closest("li").addClass("active");
            var id = $(this).closest("li").attr("id");
            $(".infobl").removeClass("active");
            $(".infobl."+id).addClass("active");
        }
    });

    $("body").on("click", ".shwmp", function(e){
        e.preventDefault();
        if (!$(this).hasClass("active")){
            $(this).addClass("active");
            $(".mapcomplex").show();
            google.maps.event.trigger($("#mapcomplex")[0], 'resize');
            var lat = $(".mapcomplex").attr("data-lat");
            var lng = $(".mapcomplex").attr("data-lng");
            map_complex.setCenter(new google.maps.LatLng(lat, lng));

            $(this).text("Скрыть карту");
        }
        else {
            $(this).removeClass("active");
            $(".mapcomplex").hide();
            $(this).text("Показать на карте");
        }
    });



    $('.on-map').on('click', function(){
        var inst = $.remodal.lookup[$('[data-remodal-id=modal]').data('remodal')];
        inst.open();

        var $lat = $(this).data('lat');
        var $lng = $(this).data('lng');
        var $adr = $(this).data('adr');

        $('.remodal')./*find('.form-zakaz').*/empty().append('<div id="complex-map"></div>');
        initializeComplexMaps($lat, $lng, $adr);
        return false;
    });


    //$("a.foto_otd").fancybox();


    $('body').on('click', '.complex-table tr td:not(.planirov)', function(e){
        var notClickedParent = $(this).parent('not-clicked');
        if (typeof notClickedParent !== 'undefined') {
            return;
        }
        var href = $(this).closest('tr').attr('data-href');
        location.href = href;
        return false;
    });

    /* abroad filter */

    $('.mabroad_country').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Страна: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Страна: не выбрано';
            }
            else if (options.length > 1) {
                return 'Страна: выбрано - '+options.length;
            }
            else {
                var selected = 'Страна: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mabroad_country option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_abroad_country").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_abroad_country").val(values);
            }
            sendFormFilter('/abroad/countFind', 'abroad');
        }
    });

    $('.mabroad_city').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Город: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Город: не выбрано';
            }
            else if (options.length > 1) {
                return 'Город: выбрано - '+options.length;
            }
            else {
                var selected = '';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mabroad_city option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_abroad_city").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_abroad_city").val(values);
            }
            sendFormFilter('/abroad/countFind', 'abroad');
        }
    });

    $('.mabroad_sdelka').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Тип сделки: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Тип сделки: не выбрано';
            }
            else if (options.length > 1) {
                return 'Тип сделки: выбрано - '+options.length;
            }
            else {
                var selected = 'Тип сделки: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mabroad_sdelka option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_abroad_sdelka").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_abroad_sdelka").val(values);
            }
            sendFormFilter('/abroad/countFind', 'abroad');
        }
    });

    $('.mcommercial_sdelka').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Тип сделки: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Тип сделки: не выбрано';
            }
            else if (options.length > 1) {
                return 'Тип сделки: выбрано - '+options.length;
            }
            else {
                var selected = 'Тип сделки: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mcommercial_sdelka option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_sdelka").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_sdelka").val(values);
            }
            sendFormFilter('/commercial/countFind', 'commercial');
        }
    });

    $('.mcommercial_forwhat').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Назначение: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Назначение: не выбрано';
            }
            else if (options.length > 0) {
                return 'Назначение: выбрано - '+options.length;
            }
            /*else {
                var selected = 'Тип сделки: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }*/
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mcommercial_forwhat option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_forwhat").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_forwhat").val(values);
            }
            sendFormFilter('/commercial/countFind', 'commercial');
        }
    });

    $('.mcommercial_vid').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Вид объекта: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Вид объекта: не выбрано';
            }
            else if (options.length > 0) {
                return 'Вид объекта: выбрано - '+options.length;
            }
            /*else {
             var selected = 'Тип сделки: ';
             options.each(function() {
             selected += $(this).text() + ', ';
             });
             return selected.substr(0, selected.length -2)
             }*/
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mcommercial_vid option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_vid").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_vid").val(values);
            }
            sendFormFilter('/commercial/countFind', 'commercial');
        }
    });

    $('.mcommercial_bussines').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Действующий бизнес',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Действующий бизнес';
            }
            else if (options.length > 0) {
                return 'Действующий бизнес: '+options.length;
            }
            /*else {
             var selected = 'Тип сделки: ';
             options.each(function() {
             selected += $(this).text() + ', ';
             });
             return selected.substr(0, selected.length -2)
             }*/
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mcommercial_bussines option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_bussines").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_bussines").val(values);
            }
            sendFormFilter('/commercial/countFind', 'commercial');
        }
    });

    $('.mcommercial_type').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Тип объекта: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Тип объекта: не выбрано';
            }
            else if (options.length > 0) {
                return 'Тип объекта: выбрано - '+options.length;
            }
            /*else {
             var selected = 'Тип сделки: ';
             options.each(function() {
             selected += $(this).text() + ', ';
             });
             return selected.substr(0, selected.length -2)
             }*/
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mcommercial_type option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_type").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_type").val(values);
            }
            sendFormFilter('/commercial/countFind', 'commercial');
        }
    });

    $('.mcommercial_pay').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Оплата: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Оплата: не выбрано';
            }
            else if (options.length > 0) {
                return 'Оплата: выбрано - '+options.length;
            }
            /*else {
             var selected = 'Тип сделки: ';
             options.each(function() {
             selected += $(this).text() + ', ';
             });
             return selected.substr(0, selected.length -2)
             }*/
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mcommercial_pay option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_pay").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_pay").val(values);
            }
            sendFormFilter('/commercial/countFind', 'commercial');
        }
    });

    $('.mabroad_estate').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Тип объекта: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Тип объекта: не выбрано';
            }
            else if (options.length > 1) {
                return 'Тип объекта: выбрано - '+options.length;
            }
            else {
                var selected = 'Тип объекта: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mabroad_estate option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_abroad_estate").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_abroad_estate").val(values);
            }
            sendFormFilter('/abroad/countFind', 'abroad');
        }
    });

    $('.mabroad_rooms').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Все',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'К-во комнат: не выбрано';
            }
            else if (options.length > 1) {
                return 'К-во комнат: выбрано - '+options.length;
            }
            else {
                var selected = 'К-во комнат: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mabroad_rooms option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_abroad_rooms").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_abroad_rooms").val(values);
            }
            sendFormFilter('/abroad/countFind', 'abroad');
        }
    });

    $(".abroad_address, .square_abroad").on("change keyup paste", function(){
        setTimeout(function(){sendFormFilter('/abroad/countFind', 'abroad');},1000);
    });

    /* abroad filter end */


    /*arenda filter*/
    $('.marenda_srok').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Срок аренды: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Срок аренды: не выбрано';
            }
            else if (options.length > 1) {
                return 'Срок аренды: выбрано - '+options.length;
            }
            else {
                var selected = 'Срок аренды: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.marenda_srok option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_arenda_srok").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_arenda_srok").val(values);
            }
            sendFormFilter('/arenda/countFind', 'arenda');
        }
    });

    $('.marenda_csrok').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'К-во сроков: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'К-во сроков: не выбрано';
            }
            else if (options.length > 1) {
                return 'К-во сроков: выбрано - '+options.length;
            }
            else {
                var selected = 'К-во сроков: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.marenda_csrok option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_arenda_сsrok").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_arenda_сsrok").val(values);
            }
            sendFormFilter('/arenda/countFind', 'arenda');
        }
    });

    $('.marenda_rooms').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'К-во комнат: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'К-во комнат: не выбрано';
            }
            else if (options.length > 1) {
                return 'К-во комнат: выбрано - '+options.length;
            }
            else {
                var selected = 'К-во комнат: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.marenda_rooms option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_arenda_rooms").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_arenda_rooms").val(values);
            }
            sendFormFilter('/arenda/countFind', 'arenda');
        }
    });

    /* land filter */

    $('.mland_rayon').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Район: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Район: не выбрано';
            }
            else if (options.length > 1) {
                return 'Район: выбрано - '+options.length;
            }
            else {
                var selected = 'Район: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mland_rayon option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_land_rayon").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_land_rayon").val(values);
            }
            sendFormFilter('/land/countFind', 'land');
        }
    });

    $('.mland_sdelka').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Все',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Тип сделки: не выбрано';
            }
            else if (options.length > 1) {
                return 'Тип сделки: выбрано - '+options.length;
            }
            else {
                var selected = 'Тип сделки: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2);
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mland_sdelka option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_land_sdelka").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_land_sdelka").val(values);
            }
            sendFormFilter('/land/countFind', 'land');
        }
    });

    $('.mland_type').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Тип земли: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Тип земли: не выбрано';
            }
            else if (options.length > 0) {
                return 'Тип земли: выбрано - '+options.length;
            }
            /*else {
             var selected = '';
             options.each(function() {
             selected += $(this).text() + ', ';
             });
             return selected.substr(0, selected.length -2)
             }*/
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mland_type option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_land_type").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_land_type").val(values);
            }
            sendFormFilter('/land/countFind', 'land');
        }
    });

    $('.mland_tokad').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Расстояние до КАД',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Расстояние до КАД';
            }
            else if (options.length > 0) {
                return 'Расстояние до КАД: выбрано - '+options.length;
            }
            /*else {
             var selected = '';
             options.each(function() {
             selected += $(this).text() + ', ';
             });
             return selected.substr(0, selected.length -2)
             }*/
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mland_tokad option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_land_tokad").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_land_tokad").val(values);
            }
            sendFormFilter('/land/countFind', 'land');
        }
    });

    $('.mland_country').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Страна: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Страна: не выбрано';
            }
            else if (options.length > 1) {
                return 'Страна: выбрано - '+options.length;
            }
            else {
                var selected = 'Страна: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mland_country option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_land_country").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_land_country").val(values);
            }
            sendFormFilter('/land/countFind', 'land');
        }
    });

    $('.mland_city').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Город: не выбрано',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Город: не выбрано';
            }
            else if (options.length > 1) {
                return 'Город: выбрано - '+options.length;
            }
            else {
                var selected = 'Город: ';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mland_city option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_land_city").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_land_city").val(values);
            }
            sendFormFilter('/land/countFind', 'land');
        }
    });

    $('.mland_forwhat').multiselect({
        numberDisplayed: 1,
        buttonWidth: '235px',
        maxHeight: 400,
        nonSelectedText: 'Все',
        buttonText: function(options) {
            if (options.length == 0) {
                return 'Все';
            }
            else if (options.length > 1) {
                return 'Выбрано: '+options.length;
            }
            else {
                var selected = '';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2)
            }
        },
        onChange: function(option, checked) {
            var selectedOptions = $('.mland_forwhat option:selected');
            var sels = [];
            selectedOptions.each(function(){
                sels.push($(this).val());
            });
            if (selectedOptions.length == 0){
                $(".sel_land_forwhat").val("0");
            }
            else {
                var values = sels.join(",");
                $(".sel_land_forwhat").val(values);
            }
            sendFormFilter('/land/countFind', 'land');
        }
    });

    $(".land_address, .square_land, .distance_land").on("change keyup paste", function(){
        setTimeout(function(){sendFormFilter('/land/countFind', 'land');},1000);
    });

    /* land filter end */


    $(".area_maps area, .regions-list li").on("click", regMapClick).hover(regMapHover, regMapBlur);

    var actionArea = 0;

    function regMapHover(){
        if($(this).hasClass("disable")){
            return false;
        }
        actionArea = $(this).data("id");
        $(".area_region_" + actionArea).show();
        $(".distr_item_" + actionArea).addClass("hover");
    }

    function regMapBlur(){

        if($(this).hasClass("disable")){
            return false;
        }

        if(!$(this).hasClass("fr_select"))
        {
            var el = $(".area_region_" + actionArea);
            if(!(el.hasClass("select"))){
                el.hide();
            }
        }
        $(".distr_item_" + actionArea).removeClass("hover");
        actionArea = 0;
    }
	
	//alert(parami['rayon']);
	var cnt=0;
	var rnames=['','Адмиралтейский','Всеволожский ЛО','Выборгский','','','','Калининский','Колпинский','Красногвардейский',
	'Красносельский','','Курортный','Ломоносовский ЛО','Московский','Невский','Петроградский','Петродворцовый',
	'Приморский','','Пушкинский','','Фрунзенский','Василеостровский','Центральный','Кировский'];
	var mnames={'1':'Автово','50':'Адмиралтейская','2':'Академическая','3':'Балтийская','96':'Беговая','92':'Боровая','89':'Броневая','4':'Бухарестская','40':'Василеостровская','55':'Владимирская'
	,'53':'Выборгская','5':'Горьковская','6':'Гражданский Проспект','7':'Девяткино','8':'Елизаровская','62':'Звёздная','57':'Звенигородская',
	'10':'Комендантский проспект','11':'Крестовский о-в','12':'Купчино','13':'Ладожская','14':'Ленинский проспект','15':'Лесная','79':'Лиговский','16':'Ломоносовская',
	'46':'Маяковская','17':'Международная','18':'Московская','63':'Московские ворота','59':'Нарвская','19':'Новочеркасская',
	'60':'Обводный канал','47':'Обухово','20':'Озерки','21':'Парк Победы','22':'Парнас','23':'Петроградская','42':'Пионерская','25':'Площадь мужества','71':'Площадь Ал. Невского',
	'44':'Площадь Восстания','68':'Площадь Ленина','49':'Политехническая','28':'Проспект Большевиков','30':'Проспект Просвещения','26':'Приморская','27':'Пролетарская','67':'Проспект Ветеранов',
	'31':'Рыбацкое','33':'Спортивная','34':'Старая деревня','35':'Удельная','36':'Улица Дыбенко','37':'Фрунзенская','64':'Чёрная речка','38':'Чернышевская','39':'Чкаловская','45':'Электросила'};
	if (typeof parami['rayon'] !== 'undefined' && parami['rayon']!=='0') {
		var rayons = parami['rayon'].split(',');
		$(".sel_build_rayon").val(parami['rayon']);
		if(rayons.length > 1){
			$(".distrolist").text("Район: выбрано - "+rayons.length);
			rayons.forEach(function(actionArea) {
				var el = $(".area_region_" + actionArea).toggleClass("select");
				$(".distr_item_" + actionArea).toggleClass("select");
				if(!(el.hasClass("select")) && !el.hasClass("fr_select")){
					el.hide();
				}else{
					el.show();
				}
			});
		}else{
			var nam = rnames[rayons[0]];
			$(".distrolist").text("Район: "+nam);
		}
		
	}
	if (typeof parami['metro'] !== 'undefined' && parami['metro']!=='0') {
		var mnames={'1':'Автово','50':'Адмиралтейская','2':'Академическая','3':'Балтийская','96':'Беговая','92':'Боровая','89':'Броневая','4':'Бухарестская','40':'Василеостровская','55':'Владимирская'
		,'53':'Выборгская','5':'Горьковская','6':'Гражданский Проспект','7':'Девяткино','8':'Елизаровская','62':'Звёздная','57':'Звенигородская',
		'10':'Комендантский проспект','11':'Крестовский о-в','12':'Купчино','13':'Ладожская','14':'Ленинский проспект','15':'Лесная','79':'Лиговский','16':'Ломоносовская',
		'46':'Маяковская','17':'Международная','18':'Московская','63':'Московские ворота','59':'Нарвская','19':'Новочеркасская',
		'60':'Обводный канал','47':'Обухово','20':'Озерки','21':'Парк Победы','22':'Парнас','23':'Петроградская','42':'Пионерская','25':'Площадь мужества','71':'Площадь Ал. Невского',
		'44':'Площадь Восстания','68':'Площадь Ленина','49':'Политехническая','28':'Проспект Большевиков','30':'Проспект Просвещения','26':'Приморская','27':'Пролетарская','67':'Проспект Ветеранов',
		'31':'Рыбацкое','33':'Спортивная','34':'Старая деревня','35':'Удельная','36':'Улица Дыбенко','37':'Фрунзенская','64':'Чёрная речка','38':'Чернышевская','39':'Чкаловская','45':'Электросила'};
		var metros = parami['metro'].split(',');
		$(".sel_build_metro").val(parami['metro']);
		if(metros.length > 1){
			$(".metrolist").text("Метро: выбрано - "+metros.length);
			metros.forEach(function(actionArea) {
				$(".metro_item_" + actionArea).toggleClass("select");
			});
		}else{
			nam = mnames[metros[0]];
			$(".metrolist").text("Метро: "+nam);
		}
	}
	
	if (typeof parami['name'] !== 'undefined' && parami['name']!=='0') {
		$("#city").val(parami['name'].replace('+',' '));
	}
	
	if (typeof parami['room'] !== 'undefined' && parami['room']!=='0') {
		var roms = parami['room'].split(',');
		$(".sel_room").val(parami['room']);
		if (roms.length>1){
			roms.forEach(function(actionArea) {
				console.log("#cbk" + actionArea);
				$("#cbk" + actionArea).toggleClass("active");
			});
		}else{
			console.log($("#cbk" + roms[0]).toggleClass("active"));
		}
	}
    function regMapClick(){
        if($(this).hasClass("disable")){
            return false;
        }
        var cats = $("#metro_n_districts").data("cat");
        var selected = [];
        if(actionArea){
            var el = $(".area_region_" + actionArea).toggleClass("select");
            $(".distr_item_" + actionArea).toggleClass("select");
            if(!(el.hasClass("select")) && !el.hasClass("fr_select")){
                el.hide();
            }else{
                el.show();
            }
        }else{
            $(this).toggleClass("select");
        }
        var nam = '';
        $(".regions-list .select").each(function(){
            var id = $(this).data("id");
			//alert(id);
            nam = $(this).find('a').data("name");
            if(typeof id != "undefined"){
                selected.push(id);
            }
        });
        var s = 0;
        if (selected.length > 0)
            s = selected.join(",");
        if (selected.length > 1)
            $(".distrolist").text("Район: выбрано - "+selected.length);
        else if (selected.length == 1)
            $(".distrolist").text("Район: "+nam);
        else
            $(".distrolist").text("Район: не выбрано");
        $(".sel_build_rayon").val(s);
        sendFormFilter('/'+cats+'/countFind', cats);
        return false;
    }

    $("body").on("click", "#refresh-metro", function(e){
        e.preventDefault();
        var cats = $("#metro_n_districts").data("cat");
        $(".label-metro a").removeClass("select");
        $(".metro-list li").removeClass("select");
        $(".metrolist").text("не выбрано");
        $(".sel_build_metro").val(0);
        sendFormFilter('/'+cats+'/countFind', cats);
    });

    $("body").on("click", "#refresh-rayon", function(e){
        e.preventDefault();
        var cats = $("#metro_n_districts").data("cat");
        $(".area_maps img.area_region").hide();
        $(".area_maps img.area_region").removeClass("select");
        $(".regions-list li").removeClass("select");
        $(".distrolist").text("не выбрано");
        $(".sel_build_rayon").val(0);
        sendFormFilter('/'+cats+'/countFind', cats);
    });

    $(".bot-pops button").click(function(e){
        e.preventDefault();
        var cats = $("#metro_n_districts").data("cat");
        $("."+cats+"-form").submit();
    });

    $("body").on("click", ".label-metro a, .metro-list li", function(e){
        e.preventDefault();
        //$(this).toggleClass("select");
        var mid = $(this).data("id");
        var cats = $("#metro_n_districts").data("cat");
        $('.metro_item_'+mid).toggleClass("select");
        $('#metro_item_'+mid).toggleClass("select");
        var selected = [];
        var nam = '';
        $(".label-metro a.select").each(function(){
            var id = $(this).data("id");
            nam = $(this).text();
            if(typeof id != "undefined"){
                selected.push(id);
            }
        });
        var s = 0;
        if (selected.length > 0)
            s = selected.join(",");

        if (selected.length > 1)
            $(".metrolist").text("Метро: выбрано - "+selected.length);
        else if (selected.length == 1)
            $(".metrolist").text("Метро: "+nam);
        else
            $(".metrolist").text("Метро: не выбрано");
        $(".sel_build_metro").val(s);
        sendFormFilter('/'+cats+'/countFind', cats);
    });


    $('.transf').mouseenter(function(e){
        $(this).removeClass("show-front");
        $(this).addClass("show-left");
    });

    $('.transf').mouseleave(function(e){
        $(this).removeClass("show-left");
        $(this).addClass("show-front");
    });


   var slid=0;
    var timerId = setTimeout(function tick() {
		if(slid==0){
			$( ".eksban .conti" ).animate({right: "100%", marginRight: "0"}, 3500, "easeInExpo" );
			$( ".eksban .conti" ).animate({right: wib, marginRight: "0"}, 0, function(){
				$( ".eksban .conts" ).animate({right: "50%", marginRight: wibi}, 5000, "easeOutBack" );
			});
			slid=1;
			timerId = setTimeout(tick, 15000);
		}else{
			$( ".eksban .conts" ).animate({right: "100%", marginRight: "0"}, 3500, "easeInExpo" );
			$( ".eksban .conts" ).animate({right: wib, marginRight: "0"}, 0, function(){
				$( ".eksban .conti" ).animate({right: "50%", marginRight: wibi}, 5000, "easeOutBack" );
			});
			slid=0;
			timerId = setTimeout(tick, 15000);
		}
    }, 3000);

    $('#excurs-form').on("submit", function(e){
        e.preventDefault();
        var error = 0;
        if (!$('.exfio').val()){
            $('.exfio').css('border', '1px solid red');
            error = 1;
        }
        else {
            $('.exfio').css('border', '0');
        }

        if (!$('.exmob').val()){
            $('.exmob').css('border', '1px solid red');
            error = 1;
        }
        else {
            $('.exmob').css('border', '0');
        }
        if (error == 1){

        }
        else {
            $.ajax({
                type: 'POST',
                url: '/ajax/excursion',
                dataType: 'json',
                data: {
                    name: $('.exfio').val(),
                    phone: $('.exmob').val(),
                    email: $('.exemail').val(),
                    bron: $('.exbron').val(),
                    comment: $('.excomm').val()
                },
                success: function(data) {
                    $('.exfio').val("");
                    $('.exmob').val("");
                    $('.exemail').val("");
                    $('.exbron').val("");
                    $('.excomm').val("");
                    $.growl.notice({ title: "Сообщение:", message: "<br>Спасибо.<br> Ваши данные отправлены." });
                }
            });

        }
    });

    $('body').on('click', '.addtofavorite', function(e){
        e.preventDefault();
        var id = $(this).data("id");
        var category = $(this).data("category");
        var action = $(this).data("action");
        var elem = $(this);
		console.log(id);
		console.log(category);
		console.log(action);
        $.ajax({
            type: 'POST',
            url: '/ajax/favorites',
            dataType: 'json',
            data: {
                id: id,
                category: category,
                action: action
            },
            success: function(data) {
                if (elem.hasClass("notinf")){
                    elem.removeClass("notinf");
                    elem.data("action", "delete");
                    elem.text("Убрать из избранного");
                }
                else {
                    elem.addClass("notinf");
                    elem.data("action", "add");
                    elem.text("В избранное");
                }
                if (data.ok == 'delete'){
                    $('.favsid-'+data.id).remove();
                    favorites_slider.reloadSlider({
                        minSlides: 0,
                        maxSlides: 5,
                        slideWidth: 170,
                        slideMargin: 86,
                        nextText: '',
                        prevText: '',
                        pager: false,
                        infiniteLoop: false
                    });
                    var count = favorites_slider.getSlideCount();
                    if (count == 0){
                        $('#favoriteshide').empty();
                        $('#favoriteshide').append('<p class="nothingshow">Нет избранных объектов</p>');
                    }
                }
                if (data.ok == 'add'){
                    var str = '';
                    str += '<li class="favsid-'+data.elem.id+'">';
                    str += '    <a class="oneitembot" href="'+data.elem.link+'" target="_blank">';
                    str += '        <img src="'+data.elem.foto+'" alt="'+data.elem.name+'" width="172" height="116">';
                    str += '        <p>'+data.elem.name+'</p>';
                    str += '        <p class="price">'+data.elem.price+'</p>';
                    str += '    </a>';
                    str += '</li>';


                    if (!$('#favoriteshide .bx-wrapper').length){
                        $('#favoriteshide').empty();
                        $('#favoriteshide').append('<ul class="bx-favorites-bottom"></ul>');
                        $('.bx-favorites-bottom').append(str);
                        favorites_slider = $('.bx-favorites-bottom').bxSlider({
                            minSlides: 0,
                            maxSlides: 5,
                            slideWidth: 170,
                            slideMargin: 86,
                            nextText: '',
                            prevText: '',
                            pager: false,
                            infiniteLoop: false
                        });
                    }
                    else {
                        $('.bx-favorites-bottom').append(str);
                        favorites_slider.reloadSlider({
                            minSlides: 0,
                            maxSlides: 5,
                            slideWidth: 170,
                            slideMargin: 86,
                            nextText: '',
                            prevText: '',
                            pager: false,
                            infiniteLoop: false
                        });
                    }
                }
            }
        });
    });



    $('.popup-modal').magnificPopup({
        type: 'inline',
        preloader: false,
        closeMarkup: '<span class="mclose"></span>',
        mainClass: 'mfp-distr'
    });



    $('.ask_popup').magnificPopup({
        type: 'inline',
        preloader: false,
        mainClass: 'mfp-ask'
    });

    $('.complex-table-btn').magnificPopup({
        type: 'inline',
        preloader: false,
        mainClass: 'mfp-ask',
        callbacks: {
            beforeOpen: function() {
                var mp = $.magnificPopup.instance,
                    t = mp.st.el;
                var room = t.data('room');
                var price = t.data('price');
                var pp = '';
                if (price != '0'){
                    pp = ' стоимостью '+price+' руб';
                }
                var complex = $("#askAppartment").data('complex');
                $("#askAppartment #comment").val("Меня интересует "+room+" в ЖК «"+complex+"»"+pp+'. Расскажите, пожалуйста, мне побольше об этом объекте.');
                $("#askAppartment #comment").attr('data-question', "Меня интересует "+room+" в ЖК «"+complex+"»"+pp+'. Расскажите, пожалуйста, мне побольше об этом объекте.');
            }
        }
    });

    $('body').on('click', '.watchvideo', function(e){
        e.preventDefault();
        $(".videoc").show("slow");
    });





    $(document).on('click', '.shpopupa', function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        $('.chpopsh').removeClass('active');
        $(".popsh").hide();
        $("#s"+id).addClass('active');
        $('#pop-s'+id).show();
    });



    $(document).on('click', '.mclose', function (e) {
        e.preventDefault();
        $.magnificPopup.close();
    });

    $(document).on('click', '.chpopsh', function (e) {
        e.preventDefault();
        var id = $(this).attr("id");
        $('.chpopsh').removeClass('active');
        $(this).addClass('active');
        $(".popsh").hide();
        $('#pop-'+id).show();

    });

    $(".rtclk").on("click", function(e){
        e.preventDefault();
        if ($(this).hasClass("selsc"))
            return false;
        $('#info').hide();
        $('#routes').show();
        $('.incorn').hide();
        $('.rtcorn').show();
        $('.rtclk, .inclk').toggleClass("selsc");
    });

    $(".inclk").on("click", function(e){
        e.preventDefault();
        if ($(this).hasClass("selsc"))
            return false;
        $('#info').show();
        $('#routes').hide();
        $('.incorn').show();
        $('.rtcorn').hide();
        $('.rtclk, .inclk').toggleClass("selsc");
    });

    $('#better_call_min').datetimepicker({
        datepicker:false,
        format:'H:i',
        allowTimes:[
            '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'
        ]
    });

    $('#better_call_max').datetimepicker({
        datepicker:false,
        format:'H:i',
        allowTimes:[
            '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'
        ]
    });

    $('.refresh_filter').on("click", function(e){
        e.preventDefault();
        $('#lPrice-from').val($(".prdiv").data("min"));
        $('#lPrice-to').val($(".prdiv").data("max"));
        $(bFilterLand.options.costSlider).slider( "values", 0, bFilterLand.toSlider($(".prdiv").data("min")) );
        $(bFilterLand.options.costSlider).slider( "values", 1, bFilterLand.toSlider($(".prdiv").data("max")) );
        $('.distance_land').val("");
        $('.square_land').val("");
        $('.mland_forwhat').multiselect('deselectAll', false);
        $('.mland_forwhat').multiselect('updateButtonText');
        $('.mland_city').multiselect('deselectAll', false);
        $('.mland_city').multiselect('updateButtonText');
        $('.mland_type').multiselect('deselectAll', false);
        $('.mland_type').multiselect('updateButtonText');
        $('.mland_sdelka').multiselect('deselectAll', false);
        $('.mland_sdelka').multiselect('updateButtonText');
        $('.mland_rayon').multiselect('deselectAll', false);
        $('.mland_rayon').multiselect('updateButtonText');
        $(".sel_land_forwhat").val("0");
        $(".sel_land_city").val("0");
        $(".sel_land_type").val("0");
        $(".sel_land_sdelka").val("0");
        $(".sel_land_rayon").val("0");
        sendFormFilter('/land/countFind', 'land');
    });

    $('.refresh_filter_abroad').on("click", function(e){
        e.preventDefault();
        $('#aPrice-from').val($(".prdiv").data("min"));
        $('#aPrice-to').val($(".prdiv").data("max"));
        $(bFilterAbroad.options.costSlider).slider( "values", 0, bFilterLand.toSlider($(".prdiv").data("min")) );
        $(bFilterAbroad.options.costSlider).slider( "values", 1, bFilterLand.toSlider($(".prdiv").data("max")) );
        $('.square_abroad').val("");
        $('.abroad_address').val("");
        $('.mabroad_rooms').multiselect('deselectAll', false);
        $('.mabroad_rooms').multiselect('updateButtonText');
        $('.mabroad_country').multiselect('deselectAll', false);
        $('.mabroad_country').multiselect('updateButtonText');
        $('.mabroad_city').multiselect('deselectAll', false);
        $('.mabroad_city').multiselect('updateButtonText');
        $('.mabroad_sdelka').multiselect('deselectAll', false);
        $('.mabroad_sdelka').multiselect('updateButtonText');
        $('.mabroad_estate').multiselect('deselectAll', false);
        $('.mabroad_estate').multiselect('updateButtonText');
        $(".sel_abroad_rooms").val("0");
        $(".sel_abroad_estate").val("0");
        $(".sel_abroad_sdelka").val("0");
        $(".sel_abroad_city").val("0");
        $(".sel_abroad_country").val("0");
        sendFormFilter('/abroad/countFind', 'abroad');
    });


    var agree = $("#land_form_ask").find('#agree');
    agree.click(function() {
        if ($(this).hasClass('active')) {
            $(this).removeClass('error');
            return;
        }
    });

    $("#land_form_ask").on("submit", function(e){
        var form = $(this);
        e.preventDefault();
        var uri = $('#askLand').data('link');
        var theme = $('#askLand').data('name');
        var agree = $("#land_form_ask").find('#agree');

        var error = 0;
        if (!$("#land_form_ask #name").val()){
            error = 1;
            $("#land_form_ask #name").addClass("error");
        }
        else {
            $("#land_form_ask #name").removeClass("error");
        }
        if (!$("#land_form_ask #phone").val()){
            error = 1;
            $("#land_form_ask #phone").addClass("error");
        }
        else {
            $("#land_form_ask #phone").removeClass("error");
        }
        if (!agree.hasClass('active')) {
            agree.addClass('error');
            return;
        }

        if (error == 0){
            $.ajax({
                type: 'POST',
                url: '/ajax/askSend',
                dataType: 'json',
                data: {
                    name: $('#land_form_ask #name').val(),
                    phone: $('#land_form_ask #phone').val(),
                    email: $('#land_form_ask #email').val(),
                    better_call: $('#land_form_ask #better_call_min').val(),
                    better_call_to: $('#land_form_ask #better_call_max').val(),
                    uri: uri,
                    theme: theme,
                    comment: $('#land_form_ask #comment').val()
                },
                success: function(data) {
                    $('#land_form_ask #name').val("");
                    $('#land_form_ask #phone').val("");
                    $('#land_form_ask #email').val("");
                    $('#land_form_ask #better_call_min').val("10:00");
                    $('#land_form_ask #better_call_max').val("20:00");
                    $('#land_form_ask #comment').val($('#land_form_ask #comment').data('question'));
                    $.magnificPopup.close();
                    $.growl.notice({ title: "Сообщение:", message: "<br>Спасибо.<br> Ваши данные отправлены." });
					var eventCategory = '';
                    if (form.data('catalog') === 'apartments' ) {
                        eventCategory = '[zayav-vopros-otpravlen]';
                    }
                    if (form.data('catalog') === 'resale' ) {
                        eventCategory = 'otpravit-vse-zayav';
                    }
                    var event = {
                        eventCategory: eventCategory,
                        eventAction: 'succes_send',
                        eventLabel: 'send'
                    }
                    sendEvent(event);
				}
            });
        }
    });

    var agree = $("#land_form_ask").find('#agree1');
    agree.click(function() {
        if ($(this).hasClass('active')) {
            $(this).removeClass('error');
            return;
        }
    });
    $("#app_form_ask").on("submit", function(e){
        e.preventDefault();
        var uri = $('#askAppartment').data('link');
        var theme = $('#askAppartment').data('name');
        var error = 0;
        if (!$("#app_form_ask #name").val()){
            error = 1;
            $("#app_form_ask #name").addClass("error");
        }
        else {
            $("#app_form_ask #name").removeClass("error");
        }
        if (!$("#app_form_ask #phone").val()){
            error = 1;
            $("#app_form_ask #phone").addClass("error");
        }
        else {
            $("#app_form_ask #phone").removeClass("error");
        }
        var agree = $("#app_form_ask").find('#agree1');
        if (!agree.hasClass('active')) {
            agree.addClass('error');
            return;
        }
        if (error == 0){
            $.ajax({
                type: 'POST',
                url: '/ajax/askSend',
                dataType: 'json',
                data: {
                    name: $('#app_form_ask #name').val(),
                    phone: $('#app_form_ask #phone').val(),
                    email: $('#app_form_ask #email').val(),
                    better_call: $('#app_form_ask #better_call_min').val(),
                    better_call_to: $('#app_form_ask #better_call_max').val(),
                    uri: uri,
                    theme: theme,
                    comment: $('#app_form_ask #comment').val()
                },
                success: function(data) {
                    $('#app_form_ask #name').val("");
                    $('#app_form_ask #phone').val("");
                    $('#app_form_ask #email').val("");
                    $('#app_form_ask #better_call_min').val("10:00");
                    $('#app_form_ask #better_call_max').val("20:00");
                    $('#app_form_ask #comment').val($('#app_form_ask #comment').data('question'));
                    $.magnificPopup.close();
                    $.growl.notice({ title: "Сообщение:", message: "<br>Спасибо.<br> Ваши данные отправлены." });
					var event = {
                        eventCategory: 'otpravit-zayav',
                        eventAction: 'succes_send',
                        eventLabel: 'send'
                    }
                    sendEvent(event);
                }
            });
        }
    });

    /**
        Добавляем звездочку обязательному полю
    */
    var tir = $('#land_form_ask, #app_form_ask').find('input.require');
    $.each(tir, function(i, ti) {
        $(ti).attr('placeholder', $(ti).attr('placeholder') + ' *');
    });

    $(".show-hide").click(function(e){
        e.preventDefault();
        if ($(this).hasClass("short")){
            $(".long-filter").show();
            $(".botfilter").addClass("longs");
            $(this).text("Свернуть");
        }
        else {
            $(".long-filter").hide();
            $(".botfilter").removeClass("longs");
            $(this).text("Расширенный поиск");
        }
        $(this).toggleClass("short");
    });


    // $('.distr-list').rollbar({zIndex:900, blockGlobalScroll: false});


    //$( ".eksban .cont" ).animate({left: "50%", marginLeft: "-640px"}, 2500 );

    $(".anim-slider").animateSlider({
        autoplay	:true,
        interval	:6000,
        dots: false,
        animations 	:
        {
            0	: 	//Slide No1
            {
                span	:
                {
                    show   	  : "bounceIn",
                    hide 	  : "flipOutX",
                    delayShow : "delay1s"
                },
                ".title-bar":
                {
                    show 	  : "fadeInUpBig",
                    hide 	  : "fadeOutDownBig",
                    delayShow : "delay0-5s"
                },
                img 	:
                {
                    show   	  : "bounceInRight",
                    hide 	  : "fadeOutRightBig",
                    delayShow : "delay0-5s"
                },
                p :
                {
                    show 	  : "flipInY",
                    hide 	  : "flipOutY",
                    delayShow : "delay0-5s"
                }

            },
            1	: //Slide No2
            {

                img	:
                {
                    show 	 	: "bounceIn",
                    hide 	 	: "bounceOut",
                    delayShow 	: "delay0-4s"
                },
                ".title-bar":
                {
                    show 	 	: "fadeInRight",
                    hide 	 	: "fadeOutRight",
                    delayShow 	: "delay1-5s"
                },

                span :
                {
                    show 	 	: "bounceInDown",
                    hide 	 	: "bounceOutLeft",
                    delayShow 	: "delay2-5s"

                },
                p :
                {
                    show 	 	: "rotateIn",
                    hide 	 	: "rotateOut",
                    delayShow 	: "delay0-5s"
                }
            },
            2	: //Slide No2
            {

                img	:
                {
                    show 	  : "lightSpeedIn",
                    hide 	  : "flipOutY",
                    delayShow : "delay1-5s"
                },
                ".title-bar":
                {
                    show 	 	: "fadeInRight",
                    hide 	 	: "fadeOutRight",
                    delayShow 	: "delay0-5s"
                },

                span :
                {
                    show 	 	: "bounceInDown",
                    hide 	 	: "bounceOutLeft",
                    delayShow 	: "delay2-5s"

                },
                p :
                {
                    show 	 	: "fadeInDownBig",
                    hide 	 	: "fadeOutDownBig",
                    delayShow 	: "delay2-5s"
                }
            }
        }
    });




    var hwSlideSpeed = 700;
    var hwTimeOut = 6000;
    var hwNeedLinks = true;

    var slideNum = 0;
    var slideTime;
    slideCount = $(".soli").length;

    var animSlide = function(arrow){
        clearTimeout(slideTime);
        //$('.solcon').eq(slideNum).fadeOut(hwSlideSpeed);
        $('.solcon').eq(slideNum).removeClass('active');
        $('.consarr').removeClass('cons-'+slideNum);
        if(arrow == "next"){
            if(slideNum == (slideCount-1)){slideNum=0;}
            else{slideNum++}
        }
        else if(arrow == "prew")
        {
            if(slideNum == 0){slideNum=slideCount-1;}
            else{slideNum-=1}
        }
        else{
            slideNum = arrow;
        }
        //$('.solcon').eq(slideNum).fadeIn(hwSlideSpeed, rotator);
        $('.solcon').eq(slideNum).addClass('active', rotator);
        $('.consarr').addClass('cons-'+slideNum);
        $(".soli.selected").removeClass("selected");
        $('.soli').eq(slideNum).addClass('selected');
        rotator();
    }

    var pause = false;
    var rotator = function(){
        if(!pause){slideTime = setTimeout(function(){animSlide('next')}, hwTimeOut);}
    }

    $('.about-solist ul li').hover(
        function(){clearTimeout(slideTime); pause = true;},
        function(){pause = false; rotator();
        });

    $(".about-solist ul li").click(function(e){
        e.preventDefault();
        var goToNum = parseFloat($(this).data("id"));
        animSlide(goToNum);
    });
    rotator();

    $('.about-bxslider').bxSlider({
        slideWidth: 260,
        minSlides: 1,
        maxSlides: 5,
        slideMargin: 30,
        pager: false
    });

    $('.gifts #card').hover(
        function(){$(this).addClass('flipped');},
        function(){$(this).removeClass('flipped');
    });

    var voteslider = $('.vote-bxslider').bxSlider({
        slideWidth: 300,
        minSlides: 1,
        maxSlides: 1,
        slideMargin: 0,
        adaptiveHeight: true,
        pager: false
    });

    $('.diploms ul li:not(.bx-clone)').magnificPopup({
        delegate: 'a',
        type: 'image',
        tLoading: 'Loading image #%curr%...',
        mainClass: 'mfp-img-mobile',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0,1] // Will preload 0 - before current, and 1 after the current image
        },
        image: {
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
            titleSrc: function(item) {
                return item.el.attr('title') + '<small>M16-Недвижимость</small>';
            }
        }
    });

    $('.otzmag').magnificPopup({
        type: 'image',
        tLoading: 'Loading image #%curr%...',
        mainClass: 'mfp-img-mobile',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0,1] // Will preload 0 - before current, and 1 after the current image
        },
        image: {
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
            titleSrc: function(item) {
                return item.el.attr('title') + '<small>M16-Недвижимость</small>';
            }
        }
    });

    $(".votesend").click(function(e){
        e.preventDefault();
        var id = $(this).data("id");
        var vote_id = $(this).data("vote");
        var currentslide = voteslider.getCurrentSlide();
        $.ajax({
            type: 'POST',
            url: '/ajax/vote',
            dataType: 'json',
            data: {
                id: id,
                vote_id: vote_id
            },
            success: function(data) {
                showVoteResults(data, vote_id);
                voteslider.reloadSlider({
                    startSlide:currentslide,
                    slideWidth: 300,
                    minSlides: 1,
                    maxSlides: 1,
                    slideMargin: 0,
                    adaptiveHeight: true,
                    pager: false
                });
            }
        });
    });



    function showVoteResults(data, vote_id){
        var count = 0;
        var answers = [];

        $.each(data, function(i,val){
            count += eval(val.count);
            var one = {
                name: val.name,
                count: val.count
            };
            answers.push(one);
        });

        $('.votesdiv-'+vote_id+' .answ').remove();
        $.each(answers, function(i,val){

            var percent = Math.round((val.count / count)*100);

            var str = '<div class="resvote">';
            str += '<p>'+val.name+'<span>'+val.count+'('+percent+'%)</span></p>';
            str += '<div class="vote-line">';
            str += '<span class="colorow" style="width: '+percent+'%"></span>';
            str += '</div>';
            str += '</div>';
            $('.votesdiv-'+vote_id).append(str);
        });

    }

    $('body').on('click', '.lnktobot', function(e){
        e.preventDefault();
        var id = $(this).attr("id");
        id = id.split('-show');
        id = id[0];

        if ($(this).hasClass('active')){
            $('.botfix').removeClass('hides');
            $('.lnktobot').removeClass('active');
            $('.oneslidebot').hide();
            //$('.lt-label').css({bottom:0});
            $('body').removeClass('botshow');
        }
        else {
            $('.oneslidebot').hide();
            $('#'+id+'hide').show();
            $('.botfix').addClass('hides');
            $('.lnktobot').removeClass('active');
            $(this).addClass('active');
            //$('.lt-label').css({bottom:'266px'});
            $('body').addClass('botshow');
        }
    });

    $('body').on('click', '.closebot', function(e){
        e.preventDefault();
        $('.botfix').removeClass('hides');
        $('.lnktobot').removeClass('active');
        $('body').removeClass('botshow');
        $('.oneslidebot').hide();
        //$('.lt-label').css({bottom:0});
    });

    $('body').on('click', '.bclause', function(e){
        e.preventDefault();
        var closest = $(this).closest('.bankon');
        $('.bankon').removeClass("actpb");
        closest.addClass("actpb");
    });

    $('body').on('click', '.bp-close', function(e){
        e.preventDefault();
        $('.bankon').removeClass("actpb");
    });


    $('.show-tur').magnificPopup({
        type: 'iframe'
    });

    $('body').on('click', '.changeinblock', function(e){
        e.preventDefault();
        if ($(this).hasClass('gotointer')){
            //$('.interest-obj-show').hide();
            //$('.special-obj-show').show();
            $('.interest-obj').html('интересное');
            $('.special-obj').html('<a href="#" class="changeinblock gotospecial">спецпредложения</a>');
            $('.specinttop').addClass('showin');
        }
        else {
            $('.interest-obj').html('<a href="#" class="changeinblock gotointer">интересное</a>');
            $('.special-obj').html('спецпредложения');
            $('.specinttop').removeClass('showin');
        }
    });

    $('.gifts').magnificPopup({
        delegate: 'a',
        type: 'image',
        tLoading: 'Loading image #%curr%...',
        mainClass: 'mfp-img-mobile',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0,1] // Will preload 0 - before current, and 1 after the current image
        },
        image: {
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
            titleSrc: function(item) {
                return item.el.attr('title');
            }
        }
    });

    $("#movet-exof").click(function(e){
        e.preventDefault();
        $('body').scrollTo('#turoff', {duration:'slow', easing: 'easeOutCirc'});
    });

    $("#movet-sec").click(function(e){
        e.preventDefault();
        $('body').scrollTo('#consec', {duration:'slow', easing: 'easeOutCirc'});
    });

    $("#movet-sotr").click(function(e){
        e.preventDefault();
        $('body').scrollTo('#consotr', {duration:'slow', easing: 'easeOutCirc'});
    });

    $("#movet-command").click(function(e){
        e.preventDefault();
        $('body').scrollTo('#concommand', {duration:'slow', easing: 'easeOutCirc'});
    });

    $("#movet-about").click(function(e){
        e.preventDefault();
        $('body').scrollTo('#conabout', {duration:'slow', easing: 'easeOutCirc'});
    });

    $('body').on('click', '.likereview', function(e){
        e.preventDefault();
        var id = $(this).data("id");
        var identity = $(this).data("identity");
        var item = $(this);
        $.ajax({
            type: 'POST',
            url: '/ajax/like',
            data: {
                id: id,
                identity: identity
            },
            success: function(data) {
                item.text(data);
            }
        });
    });

    $('body').on('click', '.fastss', function(e){
        e.preventDefault();
        $('.main_nav_block').hide();
        $('.fsblock').removeClass('notshow');
    });

    $('body').on('click', '.closefs', function(e){
        e.preventDefault();
        $('.fsblock').addClass('notshow');
        $('.main_nav_block').show();
    });

    $('body').on('click', '.change_fs_res', function(e){
        e.preventDefault();
        if(!$(this).hasClass('active')){
            var id = $(this).attr("id");
            $('.change_fs_res').removeClass('active');
            $(this).addClass('active');
            $('.fslinksblock').removeClass('active');
            $('.'+id).addClass('active');
        }
    });

    $('.phoneFormat').inputmask("9 (999) 999-99-99");

});

function initializeComplexMaps($lat, $lng, $adr) {
    var myLatlng = new google.maps.LatLng($lat, $lng);
    var mapOptions = {
        zoom: 14,
        center: myLatlng,
        disableDefaultUI: true
    };
    var map = new google.maps.Map(document.getElementById('complex-map'), mapOptions);

    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        title: $adr
    });
}


function fsendAjax($status, $name, $phone)
{
    if($('#ch-1').hasClass('active')) { zTd[10] = '100% оплата'; }
    if($('#ch-2').hasClass('active')) { zTd[11] = 'Ипотека или рассрочка'; }
    if($('#ch-3').hasClass('active')) { zTd[12] = 'Подписаться на новости компании'; }

    $.ajax({
        type: 'POST',
        url: '/sendmail',
        dataType: 'json',
        data: {
            status: $status,
            name: $name,
            phone: $phone,
            but: clickButton,
            dt: zTd
        },
        success: function(data) {
            $.growl.notice({ title: "Сообщение:", message: "<br>Спасибо.<br> Ваши данные отправлены." });
            var inst = $.remodal.lookup[$('[data-remodal-id=modal]').data('remodal')];
            inst.close();
        }
    });
}

function loadHash()
{
    var hash = document.location.hash;
    room = null;
    if (hash)
    {
        globalUrl = hash.replace(/^#/, '');
        if($.isSubstring(globalUrl, "plan")){
            $('table').find('.'+globalUrl).first().click();
        }
        else {
            if($('table .complex-table-btn').length > 0) {
                $('table').find('.idx-'+globalUrl).first().click();
            } else if($('.want-view').length > 0) {
                $('.want-view').click();
            }
        }
    }
}

/*function scrolls() {
 $(window).scroll(function() {
 var $dc = parseInt($(document).height()) - parseInt($(window).height());
 var startH = $(window).scrollTop();
 $('#scrollsPosition').text(startH);
 if (startH >= '0') {
 $('.realty').find('.item').each(function(){
 $(this).addClass('animated flipInY');
 });
 } else {

 }
 });
 }   */

function addMyClass($selector, $direction, $offset, $class) {
    $($selector).waypoint(function(direction) {
        $(this).addClass($class, direction !== $direction);
    }, {
        offset: $offset
    });
}


// Проверка мыла
function isValidEmailAddress(emailAddress)
{
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
}


$(document).ready(function(){

    $('#notFoundForm button').on('click', function(){

        var phone = $('#notFoundForm [name="phone"]');

        phone.removeClass('error');

        if (phone.val().split('_').join('').length != 17)
        {
            phone.addClass('error');
        }
        else
        {
            var roomObj = $('form.buildings-form').find('input[name="room"]').next();

            var room = [];
            $('.rooms-filt label.active').each(function(indx){
                room.push($(this).text().trim());
            });

            var param = []
            $('label.custom-checkbox-wrap input:checked').each(function(indx){
                param.push($(this).parent().text().trim());
            });

            $.ajax
            ({
                url: '/ajax/send_request/',
                data: {
                    filter: $('form.buildings-form').serializeArray(),
                    room:   room,
                    param:  param,
                    phone:  phone.val()
                },
                method:'post',
                async: true,
                success: function(data){
                    var result = $.parseJSON(data);
                    if (result.ok == true)
                    {
                        phone.val('');
                        $.growl.notice({ title: "Сообщение:", message: "<br />Ваш запрос успешно отправлен." });
                        // $('#notFoundForm').hide(300);
                    }
                }
            });
        }

        return false;
    });
	
	
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
	
	
	

});

 $('#military-video').click(function(){
        video = '<iframe src="'+ $(this).attr('data-video') +'" width="630" height="354"></iframe>';
        $(this).replaceWith(video);
    });
