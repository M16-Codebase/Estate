$(document).ready(function() {
   
   // сервис
   $('.flexslider').flexslider({
    animation: "slide",
    animationLoop: true,
    itemWidth: 480,
    itemMargin: 20,
    slideshow: false,
    directionNav: true, 
    controlNav: true
    //slideshowSpeed: 3000   
  }); 
  
  // партнеры
  $('.flexslider-parthners').flexslider({
    animation: "slide",
    animationLoop: true,
    itemWidth: 247,
    itemMargin: 20,
    slideshow: false,
    directionNav: true, 
    controlNav: true
    //slideshowSpeed: 3000   
  });   
  
  //sliderRubrikaOn('#sliderbar', '.navigation-rubrika');                
});

// Простой слайдер
function sliderRubrikaOn($container, $navigation)
{    
    var container = $($container).children('ul'), // контейнер с слайдами
        imgs = container.find('li'),
        imgsWidth = imgs.first().width(), // высота слайда
        imgsLen = imgs.length, // количество слайдов
        allWidth = imgsWidth * imgsLen, // высота контейнера        
        current = 0, // шаг
        cr = 0,
        widthLeft = 0; // позиция первого слайда              
        
    $($navigation).click(function(){
        var dir = $(this).attr('dir');        

        if(dir === 'next') {
            ++current;            
            widthLeft -= (imgsWidth);                        
        } else {
            --current;
            widthLeft += (imgsWidth);
        }                

        if(current === 0) {
            current = imgsLen;
            widthLeft = imgsWidth - allWidth;
            
        } else if(current === imgsLen) {
            current = 1;
            widthLeft = 0;
           
        }                 
               
        nextPrevSlide(container,widthLeft);        
    });
    
    function nextPrevSlide(container, widthLeft)
    {                
        $(container).animate({
           'margin-left': widthLeft 
        }, 500);
    }
}    