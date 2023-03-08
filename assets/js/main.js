/*-Tabs-*/
$('#myTabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
});

$('.t-icon').click(function (){
  let a = $("#sidebarPosition").html();
  if(a == '0'){
    $("#sidebarPosition").html('1');
    $(".sidebar").css('left', '0');
    $('body').css('overflow','hidden');
    $('.side-menu-name').css('display','inline');
    $('.s-tabs').css('padding','15px');
    
    $('.s-tabs').css('justify-content','flex-start');
    $('.s-tabs').css('display','block');
    $('.profile-tab').css('display','flex');
  }else{
    $("#sidebarPosition").html('0');
    $(".sidebar").css('left', '-100vw');
    $('body').css('overflow','scroll');
  }
});

$('.t-icon-lg').click(function (){
  let a = $("#sidebarPositionLg").html();
  if(a == '0'){
    $('.s-tabs').css('padding','15px');
    
    $('.s-tabs').css('justify-content','flex-start');
    
   
    $("#sidebarPositionLg").html('1');
    $("#center-block").removeAttr('col-md-8');
    $("#center-block").attr('class', 'content col-md-6 col-sm-12 col-xs-12 order-2');

    $("#side-block").removeAttr('col-md-1');
    $("#side-block").attr('class','content sidebar col-md-3 col-sm-12 col-xs-12 order-3');

    $('.s-tabs').css('display','block');
    $('.profile-tab').css('display','flex');
    $('.side-menu-name').css('display','inline');
    
  }else{
    $('.s-tabs').css('justify-content','center');
    $('.s-tabs').css('padding','25px 5px');
    
    $('.side-menu-name').css('display','none');
    $("#sidebarPositionLg").html('0');
    $("#side-block").removeAttr('col-md-3');
    $("#side-block").attr('class','content sidebar col-md-1 col-sm-12 col-xs-12 order-3');

    $("#center-block").removeAttr('col-md-6');
    $("#center-block").attr('class', 'content col-md-8 col-sm-12 col-xs-12 order-2');

    $('.s-tabs').css('display','flex');



    
  }
});

