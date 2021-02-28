var Main = {
  gmap: $("#map"),
  maps: function() {
    if (Main.gmap.length) {
      Main.loadMapsScript();
    }
  },
  loadMapsScript: function() {
    ymaps.ready(init);
    var gmap, myPlacemark;

    function init() {
      gmap = new ymaps.Map("map", {
        center: [59.962744, 30.289130],
        zoom: 16
      });

      myPlacemark = new ymaps.Placemark([59.962744, 30.289130], {
        hintContent: "Агентсво недвижимости",
        balloonContent: "M16"
      });

      gmap.geoObjects.add(myPlacemark);
    }
  },
  scrollMenu: function() {
    if (!$(".navbar").hasClass("inverted")) {
      $(window).scroll(function(e) {
        if ($(window).scrollTop() > 128) {
          $(".navbar").addClass("inverted");
          $(".navbar-main-toggle").addClass("inverted-menu");
          $(".navbar-toggle").addClass("inverted-menu");
          $(".header-logo-image").attr("src", "https://m16-estate.ru/asset/assets/sell-appart/img/logo-white-copy@2x.png");
        } else {
          $(".navbar").removeClass("inverted");
          $(".navbar-main-toggle").removeClass("inverted-menu");
          $(".navbar-toggle").addClass("inverted-menu");
          $(".header-logo-image").attr("src", "https://m16-estate.ru/asset/assets/sell-appart/img/logo-white.png");
        }
      });
    }
  },
  selectRooms: function() {
    $("#free-cost-check  .room").click(function() {
      var self = $(this);
      if (self.hasClass("active")) {
        self.removeClass("active");
      } else self.addClass("active");
    });
  },
  toggleMenu: function() {
    if ($(".navbar-collapse.collapse").hasClass("in")) {
      $(".navbar").addClass("opened-menu");
    } else {
      $(".navbar").removeClass("opened-menu");
    }
  }
};

$(document).ready(function() {
  Main.maps();
  Main.scrollMenu();
  Main.toggleMenu();
  Main.selectRooms();

  // Плавный скрол, стрельнул на https://css-tricks.com/snippets/jquery/smooth-scrolling/
  $('a[href*="#"]')
  // Remove links that don't actually link to anything
  .not('[href="#"]')
  .not('[href="#0"]')
  .click(function(event) {
    // On-page links
    if (
      location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
      && 
      location.hostname == this.hostname
    ) {
      // Figure out element to scroll to
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      // Does a scroll target exist?
      if (target.length) {
        // Only prevent default if animation is actually gonna happen
        event.preventDefault();
        $('html, body').animate({
          scrollTop: target.offset().top
        }, 1000, function() {
          // Callback after animation
          // Must change focus!
          var $target = $(target);
          $target.focus();
          if ($target.is(":focus")) { // Checking if the target was focused
            return false;
          } else {
            $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
            $target.focus(); // Set focus again
          };
        });
      }
    }
  });
});
