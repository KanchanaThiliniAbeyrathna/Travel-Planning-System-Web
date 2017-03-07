$(document).ready(function(){
     $("#acceptH").on('click', function() { 
             
        var data = {acceptH : $('#acceptH').val()};
        $.ajax({
                 type: "POST",
                 url: ROOT_URL + "ajax/acceptH", 
                 data: data,
                 dataType: 'json', 
                 timeout: 3000,
                 success: function(response){ // If success

                         $('#match').html(response.id); 

                 },
                 error: function() {
                        alert('Error ');
                 }
         });
     });
 });