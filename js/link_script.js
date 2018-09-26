$(document).ready(function(){
  (function($) {
    var handler = Plaid.create({
        clientName: 'Plaid Quickstart',
        env: 'sandbox',
        key: 'e9c860997945f73948b878031b4e66',
        product: ['transactions'],
        // Optional – use webhooks to get transaction and error updates
        webhook: 'https://requestb.in',
        onLoad: function() {
        // Optional, called when Link loads
        },
        onSuccess: function(public_token, metadata) {
        // Send the public_token to your app server.
        // The metadata object contains info about the institution the
        // user selected and the account ID or IDs, if the
        // Select Account view is enabled.
        $.post('/get_access_token', {
            public_token: public_token,
        });
        // document.write(public_token);
        // Run AJAX call to add token to server
        var xhttp = XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
              document.getElementById("test").innerHTML = this.responseText;
         }
        };
        xhttp.open('POST', 'save_token.php?token=' + public_token, true);
        xhttp.send();
        },
        onExit: function(err, metadata) {
        // The user exited the Link flow.
        if (err != null) {
            // The user encountered a Plaid API error prior to exiting.
        }
        // metadata contains information about the institution
        // that the user selected and the most recent API request IDs.
        // Storing this information can be helpful for support.
        },
        onEvent: function(eventName, metadata) {
        // Optionally capture Link flow events, streamed through
        // this callback as your users connect an Item to Plaid.
        // For example:
        // eventName = "TRANSITION_VIEW"
        // metadata  = {
        //   link_session_id: "123-abc",
        //   mfa_type:        "questions",
        //   timestamp:       "2017-09-14T14:42:19.350Z",
        //   view_name:       "MFA",
        // }
        }
    });

    $('#link-button').on('click', function(e) {
        handler.open();
    });
    })(jQuery);
})