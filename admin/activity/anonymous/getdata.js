

  $(document).ready(readyFun);



  $('#rows').change(function() {
    filter();
  });
  $('#order').change(function() {
    filter();
  });
  var dateRange;

  $('#dateRange').change(function(){
    filter();
   });


  $('#filter-button').click(applyFilter);

function readyFun(){

  $.post( "data/DATA.php", {which : 'Devices', howMuch: 10 , sequance : 'desc'}, function( data ) {
    let input = new Array();
    for(let i=0;i<data.length; i++){
      input[i] = `<tr>
                  <th>${data[i].sno}</th>
                  <td>${data[i].guestID}</td>
                  
                  <td>${data[i].guestDevice}</td>
                  <td>${data[i].guestBrowser}</td>
                  <td>${data[i].guestPlatform}</td>
                  </tr>
      `;
    }
    $(".activity-tables").css('display', 'block');
    $(".loader").hide();
    $("#devices-rows").html(input);
    $('.table-bordered').css('filter','blur(0px)')
  });
  filter();
  }


function applyFilter(){
  let rows = $('#rows').find(":selected").val();
  let order = $('#order').find(":selected").val();
  let range = $('#range').find(":selected").val();
  let  dateRange = $('#dateRange').val();
  $.post( "data/Filtered.php", {which : 'Devices', howMuch : rows, sequance: order, range: range, date :dateRange}, function(data){
    let input = new Array();
    for(let i=0;i<data.length; i++){
      input[i] = `<tr>
                  <th>${data[i].sno}</th>
                  <td>${data[i].guestID}</td>
                  
                  <td>${data[i].guestDevice}</td>
                  <td>${data[i].guestBrowser}</td>
                  <td>${data[i].guestPlatform}</td>
                  </tr>
      `;
    }
    $("#devices-rows").html(input);
  });
}
function isValidDate(d) {
  return d instanceof Date && !isNaN(d);
}

       



function filter(){
  let  dateRange = $('#dateRange').val();
  if(isNaN(dateRange)){
    alias = `Where tdate = '${dateRange}'`;
  }else{
    alias = '';
  }
  $.post( "data/Records.php", {whichRec : 'guests', alias : alias}, function(data){
    // $no = data.rows;
    let x = $('#rows').find(":selected").val();
    let diffRows = parseInt(x);
    let totalRecords = data.rows;
    let order = $('#order').find(":selected").val();
    if(totalRecords < diffRows){
      if (order == 'desc') {
        $("#range").html(`<option value="1,${totalRecords}">${totalRecords}-1</option>`);
      } else {
        $("#range").html(`<option value="1,${totalRecords}">1-${totalRecords}</option>`);
      }
      
    }else{
      let group =Math.trunc(totalRecords/diffRows);
      let input = new Array();
      let diff = diffRows;
      let lower = 1;
      let upper = diffRows;
      let array, finalArray;
      
      if(order == 'desc'){
        for(let i =0; i<group; i++){
          input[i] = `<option value="${lower},${upper}">${upper}-${lower}</option>`;
          lower = upper;
          upper = upper+diff;
        }

        let newLastElement = `<option value="${lower},${totalRecords}">${totalRecords}-${lower}</option>`;
        array = input.concat([newLastElement]);
        finalArray = array.reverse();
      }else{
        for(let i =0; i<group; i++){
          input[i] = `<option value="${lower},${upper}">${lower}-${upper}</option>`;
          lower = upper;
          upper = upper+diff;
        }
        let newLastElement = `<option value="${lower},${totalRecords}">${lower}-${totalRecords}</option>`;
        array = input.concat([newLastElement]);
        finalArray = array;
      }

      $("#range").html(finalArray);
    }
  });
}

  