

  $(document).ready(readyFun);

  $('#rows').change(function() {
    filter();
  });
  $('#order').change(function() {
    filter();
  });

  $('#filter-button').click(applyFilter);

function readyFun(){
  $.post( "data/DATA.php", {which : 'Devices', howMuch: 5 , sequance : 'desc'}, function( data ) {
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
  filter();
  }


function applyFilter(){
  let rows = $('#rows').find(":selected").val();
  let order = $('#order').find(":selected").val();
  let range = $('#range').find(":selected").val();
  $.post( "data/Filtered.php", {which : 'Devices', howMuch : rows, sequance: order, range: range}, function(data){
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


function filter(){
  $.post( "data/Records.php", {whichRec : 'guests', alias : ''}, function(data){
    // $no = data.rows;
    let x = $('#rows').find(":selected").val();
    let diffRows = parseInt(x);
    let totalRecords = data.rows;
    if(totalRecords < diffRows){
      $("#range").html(`<option value="1,${totalRecords}">1-${totalRecords}</option>`);
    }else{
      let group =Math.trunc(totalRecords/diffRows);
      let input = new Array();
      let diff = diffRows;
      let lower = 1;
      let upper = diffRows;
      for(let i =0; i<group; i++){
        input[i] = `<option value="${lower},${upper}">${lower}-${upper}</option>`;
        lower = upper;
        upper = upper+diff;
      }
      let newLastElement = `<option value="${lower},${totalRecords}">${lower}-${totalRecords}</option>`;
      const array = input.concat([newLastElement]);

      let order = $('#order').find(":selected").val();
      let finialArray;
      if(order == 'desc'){
        finialArray = array.reverse();
      }else{
        finialArray = array;
      }
      
      $("#range").html(array);
    }
  });
}


                

  