$(document).ready(function() {
  // Unlink single account button
  $('.unlink-account-button').click(function() {
    $.post('../php/server/unlink_accounts.php', {remove_type: '1', bank_id: this.id}, function(result) {
      $('#test').html(result);
    });
  });

  // Account toggle button
  $('.account-toggle-button').click(function() {
      $.post('../php/server/get_bank_account_status.php', {bank_account_id: this.id}, function(result) {
          var status_update;
          var data = JSON.parse(result);

          if(data['status'] == 1) {
              status_update = 0;
              $('#'+data['bank_id']).html('Show Account');
          } else {
              status_update = 1;
              $('#'+data['bank_id']).html('Hide Account');
          }

          $.post('../php/server/toggle_account.php', {bank_id: data['bank_id'], status: status_update}, function(result) {
              $('#test').html(result);
          });
      });
  });

  // Show account button
  $('.show-account-button').click(function() {
     $.post('../php/server/toggle_account.php', {bank_id: this.id, status: 1}, function(result) {
         $('#test').html(result);
     });
  });

  // Unlink all accounts button
  $('#unlink-all-button').click(function(){
    $.post('../php/server/unlink_accounts.php', {remove_type: '2'}, function(result) {
      $('#test').html(result);
    });
  });

  // Link account button
  $('#link-button').on('click', function() {
      handler.open();
  });

  // Link update button
  $('#link-update-button').on('click', function() {
      link_update_handler.open();
  });

  // PLAID SCRIPT TO LINK ACCOUNTS//
  var handler = Plaid.create({
    selectAccount: false,
    clientName: 'Plaid Quickstart',
    env: 'sandbox',
    key: 'e9c860997945f73948b878031b4e66',
    product: ['transactions'],
    // Optional – use webhooks to get transaction and error updates
    webhook: 'http://www.budget.aamirhatim.com/php/webhooks.php',
    onLoad: function() {
    // Optional, called when Link loads
    },
    onSuccess: function(public_token, metadata) {
    // Send the public_token to your app server.
    // The metadata object contains info about the institution the
    // user selected and the account ID or IDs, if the
    // Select Account view is enabled.
    $.post('../php/server/save_token.php', {token: public_token, meta: metadata}, function(result) {
      $('#test').html(result);
      console.log(result);
    });
    // console.log(public_token);
    console.log(metadata);

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

  // PLAID UPDATE CREDENTIALS
  // Initialize Link with the token parameter
  // set to the generated public_token for the Item
  var link_update_handler = Plaid.create({
    env: 'sandbox',
    clientName: 'Client Name',
    key: 'e9c860997945f73948b878031b4e66',
    product: ['transactions'],
    token: 'GENERATED_PUBLIC_TOKEN',
    onSuccess: function(public_token, metadata) {
      // You do not need to repeat the /item/public_token/exchange
      // process when a user uses Link in update mode.
      // The Item's access_token has not changed.
    },
    onExit: function(err, metadata) {
      // The user exited the Link flow.
      if (err != null) {
        // The user encountered a Plaid API error prior
        // to exiting.
      }
      // metadata contains the most recent API request ID and the
      // Link session ID. Storing this information is helpful
      // for support.
    }
  });

  // // Trigger the authentication view
  // document.getElementById('linkButton').onclick = function() {
  //   // Link will automatically detect the institution ID
  //   // associated with the public token and present the
  //   // credential view to your user.
  //   link_update_handler.open();
  // };

});

// Function to populate bank account template card
function create_account_card(bank_name, institution, bank_id, status) {
  var location = document.querySelector('#accounts-container');
  var template = document.querySelector('#account-card-template');
  template.content.querySelector('.bank-account-name').innerHTML = bank_name;
  template.content.querySelector('.bank-institution').innerHTML = institution;
  template.content.querySelector('.account-toggle-button').id = bank_id;

  var clone = document.importNode(template.content, true);
  location.appendChild(clone);

  if(status == 1) {
      $('#'+bank_id).html('Hide Account');
  } else {
      $('#'+bank_id).html('Show Account');
  }
};
