$(document).ready(function(){
     $("#myTextField").on('keyup', function() { // everytime keyup event
             var input = $(this).val(); // We take the input value
             if ( input.length >= 1 ) { // Minimum characters = 2 (you can change)
                     $('#match').html('<img src="' + window.loader + '" />'); // Loader icon apprears in the <div id="match"></div>
                     var data = {input: input}; // We pass input argument in Ajax
                     $.ajax({
                             type: "POST",
                             url: ROOT_URL + "ajax/update", // call the php file ajax/tuto-autocomplete.php (check the routine we defined)
                             data: data, // Send dataFields var
                             dataType: 'json', // json method
                             timeout: 3000,
                             success: function(response){ // If success

                                     $('#match').html(response.placesList); // Return data (UL list) and insert it in the <div id="match"></div>
                                     $('#matchList li').on('click', function() { // When click on an element in the list
                                             $('#myTextField').val($(this).text()); // Update the field with the new element
                                             $('#match').text(''); // Clear the <div id="match"></div>
                                             $('#placeslist').hide();
                                             //$('#searchplace').show();
                                     }); 

                             },
                             error: function() { // if error
                                     $('#match').text('No matches');
                             }
                     });
             } else {
                     $('#match').text(''); // If less than 2 characters, clear the <div id="match"></div>
             }
     });
 });