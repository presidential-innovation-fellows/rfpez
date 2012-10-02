$ ->
  $("#new-officer-form").validate_rfpez
    rules:
      "user[email]":
        email: true
        required: true

      "officer[name]":
        required: true

      "officer[title]":
        required: true

      "officer[agency]":
        required: true

      "officer[phone]":
        required: true

      "officer[fax]":
        required: true
