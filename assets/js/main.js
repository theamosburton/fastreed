/*-Tabs-*/
$('#myTabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
});

$('.t-icon').click(function (){
  let a = $("#sidebarPosition").html();
  console.log(a);
  if(a == '0'){
    $("#sidebarPosition").html('1');
    $(".sidebar").css('left', '0');
    $('body').css('overflow','hidden');
  }else{
    $("#sidebarPosition").html('0');
    $(".sidebar").css('left', '-100vw');
    $('body').css('overflow','scroll');
  }
});

