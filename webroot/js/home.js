function switchReliability(){
    var filter = $('.filter');
    $('.owl-carousel').removeClass('active').addClass('disables');

    switch($(this).attr('data-reliability')){
        case '0': //filterAll
            filter.removeClass('styleReliability styleRacy styleFun');
            $(this).addClass('styleAll');
            $('.owl-carousel[data-reliability="0"]').removeClass('disables').addClass('active');
            break;

        case '3': //filterReliability
            filter.removeClass('styleAll styleRacy styleFun');
            $(this).addClass('styleReliability');

            $('.owl-carousel[data-reliability="3"]').removeClass('disables').addClass('active');
            $("#sliderFiable").owlCarousel();
            
            var prevFiable = $('.owl-carousel.active .owl-controls .owl-buttons .owl-prev').addClass('prevFiable'),
                nextFiable = $('.owl-carousel.active .owl-controls .owl-buttons .owl-next').addClass('nextFiable');
            
            prevFiable.click(controlsPrev);
            nextFiable.click(controlsNext);
            break;

        case '2': //filterRacy
            filter.removeClass('styleAll styleReliability styleFun');
            $(this).addClass('styleRacy');

            $('.owl-carousel[data-reliability="2"]').removeClass('disables').addClass('active');
            $("#sliderOse").owlCarousel();
            
            var prevOse = $('.owl-carousel.active .owl-controls .owl-buttons .owl-prev').addClass('prevOse'),
                nextOse = $('.owl-carousel.active .owl-controls .owl-buttons .owl-next').addClass('nextOse');
            
            prevOse.click(controlsPrev);
            nextOse.click(controlsNext);
            break;

        case '1': //filterFun
            filter.removeClass('styleAll styleReliability styleRacy');
            $(this).addClass('styleFun');

            $('.owl-carousel[data-reliability="1"]').removeClass('disables').addClass('active');
            $("#sliderFun").owlCarousel();
            
            var prevFun = $('.owl-carousel.active .owl-controls .owl-buttons .owl-prev').addClass('prevFun'),
                nextFun = $('.owl-carousel.active .owl-controls .owl-buttons .owl-next').addClass('nextFun');
            
            prevFun.click(controlsPrev);
            nextFun.click(controlsNext);
            break;
    }
}

function controlsPrev(){
    var cardR = $('.owl-carousel.active .owl-item .singleProno .cardMatch.floatR');
    cardR.parent().parent().parent().prev().children('.singleProno').children('a').children('.cardMatch').addClass('floatR');
    cardR.removeClass('floatR');
};

function controlsNext(){
    var cardR = $('.owl-carousel.active .owl-item .singleProno .cardMatch.floatR');
    cardR.parent().parent().parent().next().children('.singleProno').children('a').children('.cardMatch').addClass('floatR');
    cardR.removeClass('floatR');
}

$(document).ready(function(){
    /* Launch slider prono simple of day */
     $("#sliderAll").owlCarousel();
    
    var prev = $('.owl-carousel.active .owl-controls .owl-buttons .owl-prev'),
        next = $('.owl-carousel.active .owl-controls .owl-buttons .owl-next');

    $('.filter').click(switchReliability);
    prev.click(controlsPrev);
    next.click(controlsNext);
});