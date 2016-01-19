//ANIMATE SVG
jQuery.extend( jQuery.easing,
{
	easeInOutQuad: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t + b;
		return -c/2 * ((--t)*(t-2) - 1) + b;
  }
});

function animateCircle(){
    $('svg').css('opacity', 1);
    replaceCirclesWithPaths($('svg'));
    drawSVGPaths($('svg'), 300, 600, 20);
}

function SVG(tag) {
    return document.createElementNS('http://www.w3.org/2000/svg', tag);
}

function replaceCirclesWithPaths(parentElement) {

    var circles = $(parentElement).find('circle');

    $.each(circles, function() {

        var cX = $(this).attr('cx');
        var cY = $(this).attr('cy');
        var r = $(this).attr('r');
        var r2 = parseFloat(r * 2);

        var convertedPath = 'M' + cX + ', ' + cY + ' m' + (-r) + ', 0 ' + 'a ' + r + ', ' + r + ' 0 1,0 ' + r2 + ',0 ' + 'a ' + r + ', ' + r + ' 0 1,0 ' + (-r2) + ',0 ';

        $(SVG('path'))
        .attr('d', convertedPath)
        .attr('fill', $(this).attr('fill'))
        .attr('stroke', $(this).attr('stroke'))
        .attr('stroke-width', $(this).attr('stroke-width'))
        .insertAfter(this);

    });

    $(circles).remove();
}

function hideSVGPaths(parentElement) {

    var paths = $(parentElement).find('path');

    //for each PATH..
    $.each( paths, function() {

        //get the total length
        var totalLength = this.getTotalLength();

        //set PATHs to invisible
        $(this).css({
            'stroke-dashoffset': totalLength,
            'stroke-dasharray': totalLength + ' ' + totalLength
        });
    });
}

function drawSVGPaths(_parentElement, _timeMin, _timeMax, _timeDelay) {


    var paths = $(_parentElement).find('path');

    //for each PATH..
    $.each( paths, function(i) {

        //get the total length
        var totalLength = this.getTotalLength();


        //set PATHs to invisible
        $(this).css({
            'stroke-dashoffset': totalLength,
            'stroke-dasharray': totalLength + ' ' + totalLength
        });

        //animate
        $(this).delay(_timeDelay*i).animate({
            'stroke-dashoffset': 0
        }, {
            duration: Math.floor(Math.random() * _timeMax) + _timeMin
            ,easing: 'easeInOutQuad'
        });
    });
}

function getTextColor(rgb) {
    
    var parts = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/),
        hex = "#" + ("0" + parseInt(parts[1],10).toString(16)).slice(-2) + ("0" + parseInt(parts[2],10).toString(16)).slice(-2) + ("0" + parseInt(parts[3],10).toString(16)).slice(-2);
    
   if((0.2125*parseInt(hex.substr(1,2), 16) + 0.7154*parseInt(hex.substr(3,2), 16) + 0.0721*parseInt(hex.substr(5,2), 16)) <= 128)
      return "#FFFFFF";
   else 
      return "#000000";
}

$(document).ready(function(){
    
    $('.arrowReturn').mouseover(animateCircle);
    $('.arrowReturn').mouseout(function(){
        $('svg').css('opacity', 0);
    });
});