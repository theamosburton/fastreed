$.post( "DATA.php", {which : 'Devices', howMuch: 25 , sequance : 'asc'}, function( data ) {
  let input = new Array();
  for(let i=0;i<data.length; i++){
    input[i] = `<tr>
                <th>${i+1}</th>
								<td>${data[i].guestID}</td>
                
								<td>${data[i].guestDevice}</td>
								<td>${data[i].guestBrowser}</td>
								<td>${data[i].guestPlatform}</td>
								</tr>
    `;
  }

  $("#devices-rows").html(input);
  });

  $(document).ready(function(){
    let x = $('#order').find(":selected").val();
    $.post( "Records.php", {whichRec : 'guests', alias : ''}, function(data){
      console.log(data.rows);
    });
  });

                

  