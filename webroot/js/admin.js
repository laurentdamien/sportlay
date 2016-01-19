var tabColor = ['#F44336',
                '#D50000',
                '#1A237E',
                '#3F51B5',
                '#2196F3',
                '#673AB7',
                '#1B5E20',
                '#2E7D32',
                '#FFFF00',
                '#FF9800',
                '#FF5722',
                '#795548',
                '#000000'];

$(document).ready(function(){
    var selectChampionships = $('.contentPronostic form select[name="championship"]'),
        selectMatchs = $('.contentPronostic form select[name="match"]'),
        buttonEditMatch = $('.listMatch table>tbody>tr p.editMatch'),
        buttonUpdateAdvice = $('.listAdvices table>tbody>tr p.editAdvice');
    
    selectChampionships.change(selectChampionship);
    selectMatchs.change(selectMatch);
    buttonEditMatch.click(editMatch);
    buttonUpdateAdvice.click(updateAdvice);
    
    $('input[type="checkbox"]').change(function(){
        if($(this).prop('checked') == false)
            $(this).parent().find($('input[type="hidden"]')).val('');
        else
            $(this).parent().find($('input[type="hidden"]')).val('true'); 
    });
    
    $('div.contentEditResult input').change(function(){
        $('div.contentEditResult input[name="'+ $(this).attr('name') +'"]').val($(this).val());
    });
    
    $('.listMatch table>tbody>tr>td>p.idMatch').click(function(){
        $('.listMatch table>tbody>tr').find('.editMatch').addClass('hide');
        $(this).parent().parent().find('.editMatch').toggleClass('hide');
    });
    
    $('.listAdvices table>tbody>tr>td>p.idAdvice').click(function(){
        $('.listAdvices table>tbody>tr').find('.editAdvice').addClass('hide');
        $(this).parent().parent().find('.editAdvice').toggleClass('hide');
    });
});

function selectChampionship(){
    var championship = $(this).val();
    
    cleanForm();

    $.ajax({
        url: './admin/listMatch',
        data: {championship: championship},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            var select = $("#selectMatch");

            select.empty();
            select.append('<option value="default">Choix du match</option>');

            $.each(json.matchs, function (key, value) {
                select.append('<option value="' + value["matchday"] + '" data-date="' + value["date"] + '" data-home="' + value["homeTeamName"] + '" data-away="' + value["awayTeamName"] + '">' + value["matchday"] + '. ' + value["homeTeamName"] + ' - ' + value["awayTeamName"]  + '</option>');
            });
        }
    });
}

function selectMatch(){
    cleanForm();
    
    var championship = $('.contentPronostic form select[name="championship"]').val(),
        matchDay = $('input[name="matchDay"]'),
        matchDate = $('input[name="matchDate"]'),
        homeTeam = $('input[name="homeTeam"]'),
        awayTeam = $('input[name="awayTeam"]'),
        day = $(this).val(),
        date = $(this).find('option:selected').attr('data-date'),
        home = $(this).find('option:selected').attr('data-home'),
        away = $(this).find('option:selected').attr('data-away'),
        homeColor = $('select[name="homeColor"] option[value="default"]'),
        awayColor = $('select[name="awayColor"] option[value="default"]'),
        myProno = $('select[name="myProno"]');
   
    matchDay.val(day);
    matchDate.val(date);
    homeTeam.val(home);
    awayTeam.val(away);
    homeColor.text("Couleur de "+home);
    awayColor.text("Couleur de "+away);
    myProno.find('option[value="1"]').text(home);
    myProno.find('option[value="2"]').text(away);
    
    if(!$('select.selectColors').parent('div').hasClass('selectColors')){
        for(var i=0; i<tabColor.length; i++){
            $('<option value="'+ tabColor[i] +'" data-class="color-'+ tabColor[i] +'"></option>').appendTo($('.selectColors'));
        }

        (function() {
            [].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {	
                new SelectFx(el);
            } );
        })();

        $('.cs-options li').each(function(){
           $(this).css('backgroundColor', $(this).attr('data-value')); 
        });
    }else{
        $('select.selectColors[name="homeColor"]').parent('div.selectColors').find('span.cs-placeholder').text("Couleur de "+home).css('background-color','#FFFFFF');
        $('select.selectColors[name="awayColor"]').parent('div.selectColors').find('span.cs-placeholder').text("Couleur de "+away).css('background-color','#FFFFFF');
    }

    $.ajax({
        url: './admin/listMatch',
        data: {championship: championship,
               home: home,
               away: away,
               date: date},
        dataType: 'json',
        type: 'post',
        success: function (data) {
            var homePennant = $('input[name="homePennant"]'),
                awayPennant = $('input[name="awayPennant"]'),
                homeQuotation = $('input[name="quotationHome"]'),
                drawQuotation = $('input[name="quotationDraw"]'),
                awayQuotation = $('input[name="quotationAway"]'),
                selectHome = $('select.selectColors[name="homeColor"]'),
                selectAway = $('select.selectColors[name="awayColor"]'),
                listError = $('ul.listError');
            
            listError.empty();
            
            if(data.colorHomeTeam != ''){
                selectHome.find('option').attr('selected', '');

                selectHome.find('option[data-class="color-'+ data.colorHomeTeam +'"]').attr('selected', 'selected');

                selectHome.parent('div.selectColors').find('span.cs-placeholder').text('').css('background-color', data.colorHomeTeam);
            }
            
            if(data.colorAwayTeam != ''){
                selectAway.find('option').attr('selected', '');

                selectAway.find('option[data-class="color-'+ data.colorAwayTeam +'"]').attr('selected', 'selected');

                selectAway.parent('div.selectColors').find('span.cs-placeholder').text('').css('background-color', data.colorAwayTeam);
            }
            
            if(data.homePennant != '')
                homePennant.val(data.homePennant);
            else
                listError.append('<li>Fannion domicile indisponible.</li>');
            
            if(data.awayPennant != '')
                awayPennant.val(data.awayPennant);
            else
                listError.append('<li>Fannion extérieur indisponible.</li>');
            
            if(data.odds != ''){
                homeQuotation.val(data.odds[0][0]);
                drawQuotation.val(data.odds[1][0]);
                awayQuotation.val(data.odds[2][0]);
            }else{
                listError.append('<li>Côtes indisponibles.</li>');
            }
        }
    });
}

function cleanForm(){
    var matchDay = $('input[name="matchDay"]'),
        matchDate = $('input[name="matchDate"]'),
        homeTeam = $('input[name="homeTeam"]'),
        awayTeam = $('input[name="awayTeam"]'),
        homePennant = $('input[name="homePennant"]'),
        awayPennant = $('input[name="awayPennant"]'),
        homeQuotation = $('input[name="quotationHome"]'),
        drawQuotation = $('input[name="quotationDraw"]'),
        awayQuotation = $('input[name="quotationAway"]');
    
    matchDay.val('');
    matchDate.val('');
    homeTeam.val('');
    awayTeam.val('');
    homePennant.val('');
    awayPennant.val('');
    homeQuotation.val('');
    drawQuotation.val('');
    awayQuotation.val('');
}

function editMatch(){
    var target = $(this),
        id = target.attr('data-id');
    
    $('#formEditMatch input[name="editIdMatch"]').val(id);

    target.parents('tr').find('.editMatch').each(function(){
        $('#formEditMatch input[name="'+$(this).attr('name')+'"]').val($(this).val());
        
        if($(this).attr('name') == 'editTicket'){
            $('#formEditMatch input[type="submit"]').trigger('click');
            return false;
        }
    });
}

function updateAdvice(){
    var target = $(this),
        id = target.attr('data-id');
    
    $('#formUpdateAdvice input[name="editIdAdvice"]').val(id);

    target.parents('tr').find('.editAdvice').each(function(){
        $('#formUpdateAdvice input[name="'+$(this).attr('name')+'"]').val($(this).val());
        
        if($(this).attr('name') == 'editAdvicePublished'){
            $('#formUpdateAdvice input[type="submit"]').trigger('click');
            return false;
        }
    });
}