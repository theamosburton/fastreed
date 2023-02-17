// get anpnymous data onload
$.post( "src/anonymous.php", {which : 'guests'}, function(data){
    let today = data.today;
    let yesterday = data.yesterday;
    let growth;
    let pl;
    let arrow;
    let border;
    if(!yesterday === 0 && !today === 0){
        if(yesterday > today){
            growth = today/yesterday*100;
            growth = '-'+growth;
            growth = parseInt(growth);
            // growth = parseFloat(`${growth}`).toFixed(2);
            pl = 'loss';
            arrow = 'down'
            border = "orangered";
        }else{
            growth = yesterday/today*100;
            growth = parseInt(growth);
            // growth = parseFloat(`${growth}`).toFixed(2);
            pl = 'profit';
            arrow = 'up'
            border = "green";
        }
    }else{
        growth = 0;
        pl = 'profit';
        arrow = 'up'
        border = "green";
    }
    

    $('#devices').html(`
    <span class="number">${today}<span class="percent ${pl}">${growth}% </span><li class="${pl} rotate-${arrow} fa fa-play"></li></span>
    <span class="entity">Devices</span>
    `);
    $('#devices').css('border', `1px solid ${border}`);

});

$.post( "src/anonymous.php", {which : 'guests_sessions'}, function(data){
    let today = data.today;
    let yesterday = data.yesterday;
    let growth;
    let pl;
    let arrow;
    let border;
    if(!yesterday === 0 && !today === 0){
        if(yesterday > today){
            growth = today/yesterday*100;
            growth = '-'+growth;
            growth = parseInt(growth);
            // growth = parseFloat(`${growth}`).toFixed(2);
            pl = 'loss';
            arrow = 'down'
            border = "orangered";
        }else{
            growth = yesterday/today*100;
            growth = parseInt(growth);
            // growth = parseFloat(`${growth}`).toFixed(2);
            pl = 'profit';
            arrow = 'up';
            border = "green";
        }
    }else{
            growth = 0;
            pl = 'profit';
            arrow = 'up'
            border = "green";
    }


    $('#sessions').html(`
    <span class="number">${today}<span class="percent ${pl}">${growth}% </span><li class="${pl} rotate-${arrow} fa fa-play"></li></span>
    <span class="entity">Sessions</span>
    `);
    $('#sessions').css('border', `1px solid ${border}`);
});
