$(document).ready(function() {
  // Unlink single account button
  $('.unlink-account-button').click(function() {
    $.post('../php/server/unlink_accounts.php', {remove_type: '1', bank_id: this.id}, function(result) {
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
  $('#link-button').on('click', function(e) {
      handler.open();
  });

  // PLAID SCRIPT TO LINK ACCOUNTS//
  var handler = Plaid.create({
    selectAccount: true,
    clientName: 'Plaid Quickstart',
    env: 'sandbox',
    key: 'e9c860997945f73948b878031b4e66',
    product: ['transactions', 'identity'],
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
    });
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
});

// Function to populate bank account template card
function create_account_card(bank_name, institution, bank_id) {
  var location = document.querySelector('#accounts-container');
  var template = document.querySelector('#account-card-template');
  template.content.querySelector('.bank-account-name').innerHTML = bank_name;
  template.content.querySelector('.bank-institution').innerHTML = institution;
  template.content.querySelector('button').id = bank_id;

  var clone = document.importNode(template.content, true);
  location.appendChild(clone);
};
