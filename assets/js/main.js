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
  let isRightSideBar = $('#rightsidebar').html();
  isRightSideBar = (isRightSideBar === 'true')
  if(a == '0'){
    $('.s-tabs').css('padding','15px');
    
    $('.s-tabs').css('justify-content','flex-start');
    $("#center-block").removeAttr('col-md-8');
    $("#sidebarPositionLg").html('1');
    
  
    if(isRightSideBar){
      $("#center-block").attr('class', 'content col-md-6 col-sm-12 col-xs-12');

    $("#side-block").removeAttr('col-md-1');
    $("#side-block").attr('class','content sidebar col-md-3 col-sm-12 col-xs-12');
    }else{
      $("#center-block").attr('class', 'content col-md-9 col-sm-12 col-xs-12');

    $("#side-block").removeAttr('col-md-1');
    $("#side-block").attr('class','content sidebar col-md-3 col-sm-12 col-xs-12');
    }

    $('.s-tabs').css('display','block');
    $('.profile-tab').css('display','flex');
    $('.side-menu-name').css('display','inline');
    
  }else{
    $('.s-tabs').css('justify-content','center');
    $('.s-tabs').css('padding','25px 5px');
    $('.profile-tab').css('padding','10px 0px');
    $('.side-menu-name').css('display','none');
    $("#sidebarPositionLg").html('0');
    $("#side-block").removeAttr('col-md-3');
    

    if (isRightSideBar) {
      $("#side-block").attr('class','content sidebar col-md-1 col-sm-12 col-xs-12');

    $("#center-block").removeAttr('col-md-6');
    $("#center-block").attr('class', 'content col-md-8 col-sm-12 col-xs-12');

    $('.s-tabs').css('display','flex');
    }else{
      $("#side-block").attr('class','content sidebar col-md-1 col-sm-12 col-xs-12');

    $("#center-block").removeAttr('col-md-9');
    $("#center-block").attr('class', 'content col-md-11 col-sm-12 col-xs-12');
    }
    

    $('.s-tabs').css('display','flex');



    
  }
});

