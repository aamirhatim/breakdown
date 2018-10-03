$(document).ready(function() {
})

function create_account_card(bank_name, institution) {
  var location = document.querySelector('#accounts-container');
  var template = document.querySelector('#account-card-template');
  template.content.querySelector('.bank-account-name').innerHTML = bank_name;
  template.content.querySelector('.bank-institution').innerHTML = institution;

  var clone = document.importNode(template.content, true);
  location.appendChild(clone);
};
