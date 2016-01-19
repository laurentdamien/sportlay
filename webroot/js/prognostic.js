function toggleButton(){
    var target = $(this),
        close = target.attr('data-close'),
        open = target.attr('data-open');
    
    if(target.hasClass('collapsed'))
        target.text(open);
    else
        target.text(close);
}

function showMoreMatchs(){
    var target = $(this),
        nbMatch = target.attr('data-match'),
        matchs = target.parent().parent().find('table');
    
    matchs.find('.oldMatch[data-match="'+nbMatch+'"]').css('display', 'table-row');
    
    nbMatch = parseInt(nbMatch)+1;
    
    target.attr('data-match', nbMatch);
    
    if(matchs.find('.oldMatch[data-match="'+nbMatch+'"]').length == 0){
        target.removeClass('show').addClass('hide');
    }
}

function fixedTicket(){
    var position = $('#detailsInfosTicket').offset().top,
        widthDiv = $('#listTicket').outerWidth(),
        scrollWindow = $(window).scrollTop(),
        widthWindow = $(window).width();
    
    if (scrollWindow >= (position - 40) && widthWindow > 768) {
        $('#listTicket').addClass('posFixed');
        $('#listTicket').css('width', widthDiv+'px');
        $('#detailsMatchTicket').removeClass('floatL').addClass('floatR');
    }else{
        $('#listTicket').removeClass('posFixed');
        $('#listTicket').css('width', '');
        $('#detailsMatchTicket').removeClass('floatR').addClass('floatL');
    }
}

function changeMatch(){
    var nbMatch = $(this).attr('data-match');
    
    window.location.assign('./match'+nbMatch);
}

$(document).ready(function(){
    
    $('.rankingFull #headingRanking a').click(toggleButton);
    $('.statsFull #headingStats a').click(toggleButton);
    
    $('.rankingFull .rankingFullDetails .table>tbody>tr.teamMatch').each(function(){
        var newColor = getTextColor($(this).css('background-color'));
        $(this).find('td').css('color', newColor);
    });
    
    $('.moreOldMatchs').click(showMoreMatchs);
    
    $('.ticket').scroll(fixedTicket);
    $('.ticket .ticketDay #listTicket .matchTicket').click(changeMatch);
});