$.post( "DATA.php", {which : 'Devices', howMuch: 10 , sequance : 'desc'}, function( data ) {
  console.log(data);
  let input = new Array();
  for(let i=0;i<data.length; i++){
    input[i] = `<tr>
								<th scope="row">${data[i].guestID}</th>
								<td>${data[i].guestDevice}</td>
								<td>${data[i].guestBrowser}</td>
								<td>${data[i].guestPlatform}</td>
								</tr>
    `;
  }

  $("#devices-rows").html(input);
  });

                

  