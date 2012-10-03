$ ->
  $(".new-bid-form").validate_rfpez
    rules:
      "bid[approach]":
        required: true

      "bid[previous_work]":
        required: true

      "bid[employee_details]":
        required: true

  $("#new-officer-form, .account-form-officer").validate_rfpez
    rules:
      "user[email]":
        email: true
        required: true
        remote: "/validation/email"

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

  $("#new-vendor-form, .account-form-vendor").validate_rfpez
    rules:
      "vendor[more_info]":
        required: true

      "vendor[homepage_url]":
        required: true
        url: true

      "vendor[portfolio_url]":
        url: true

      "vendor[sourcecode_url]":
        url: true

      "vendor[image_url]":
        required: true
        url: true

      "user[email]":
        required: true
        email: true
        remote: "/validation/email"

      "user[password]":
        required: true
        minlength: 8

      "vendor[company_name]":
        required: true

      "vendor[contact_name]":
        required: true

      "vendor[address]":
        required: true

      "vendor[city]":
        required: true

      "vendor[state]":
        required: true

      "vendor[zip]":
        required: true
        digits: true