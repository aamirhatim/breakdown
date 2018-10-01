$(document).ready(function() {
  $('#unlink-button').click(function(){
    $.post('../php/server/remove_account.php', function(result) {
      $('#test').html(result);
    });
  });

})
