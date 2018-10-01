$(document).ready(function() {
  $('#unlink-button').click(function(){
    $.post('../php/server/unlink_all_accounts.php', function(result) {
      $('#test').html(result);
    });
  });

})
