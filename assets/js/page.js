$("#toggle-icon").click(function(){
    if($("#tb-con").css('display') == 'block'){
        $("#tb-con").hide(600);
        $("#toggle-icon").css('transform', 'rotate(45deg)')
    }else{
        $("#tb-con").show(500);
        $("#toggle-icon").css('transform', 'rotate(90deg)')
    }
});

$("#tab").click(function(){
    if($("#tb-con").css('display') == 'block'){
        $("#tb-con").hide(600);
        $("#toggle-icon").css('transform', 'rotate(45deg)')
    }else{
        $("#tb-con").show(500);
        $("#toggle-icon").css('transform', 'rotate(90deg)')
    }
});
