$("#tab").click(function(){
    if($("#tb-con").css('display') == 'block'){
        $("#tb-con").hide(600);
        $("#toggle-icon").css('transform', 'rotate(45deg)')
    }else{
        $("#tb-con").show(500);
        $("#toggle-icon").css('transform', 'rotate(90deg)')
    }
});

$('#tlogup').click(function(){
    $('.signup-div').css('display', 'flex');
    $('.login-div').css('display', 'none');
});

$('#tlogin').click(function(){
    $('.signup-div').css('display', 'none');
    $('.login-div').css('display', 'flex')
});

