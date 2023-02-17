// get anpnymous data onload
$.post( "src/anonymous.php", {which : 'guests'}, function(data){
    let today = data.today;
    let yesterday = data.yesterday;
    let growth;
    let pl;
    let arrow;
    if(yesterday > today){
        growth = today/yesterday*100;
        growth = '-'+growth;
        growth = parseFloat(`${growth}`).toFixed(2);
        pl = 'loss';
        arrow = 'down'
    }else{
        growth = yesterday/today*100;
        growth = parseFloat(`${growth}`).toFixed(2);
        pl = 'profit';
        arrow = 'up'
    }

    $('#devices').html(`
    <span class="number">${today}<span class="percent ${pl}">${growth}% </span><li class="${pl} rotate-${arrow} fa fa-play"></li></span>
    <span class="entity">Devices</span>
    `);

});

$.post( "src/anonymous.php", {which : 'guests_sessions'}, function(data){
    let today = data.today;
    let yesterday = data.yesterday;
    let growth;
    let pl;
    let arrow;
    if(!yesterday === 0){
        if(yesterday > today){
            growth = today/yesterday*100;
            growth = '-'+growth;
            growth = parseFloat(`${growth}`).toFixed(2);
            pl = 'loss';
            arrow = 'down'
        }else{
            growth = yesterday/today*100;
            growth = parseFloat(`${growth}`).toFixed(2);
            pl = 'profit';
            arrow = 'up'
        }
    }else{
        growth = 0;
            pl = 'profit';
            arrow = 'up'
    }


    $('#sessions').html(`
    <span class="number">${today}<span class="percent ${pl}">${growth}% </span><li class="${pl} rotate-${arrow} fa fa-play"></li></span>
    <span class="entity">Sessions</span>
    `);
});
