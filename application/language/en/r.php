<?php

/**
 *  Note: this file is intended less as a comprehensive i18n file for everything in our app,
 *  and more as a place where we can aggregate our non-trivial copy for ease of editing it.
 *
 *  Also, note that some blocks of text appear in textareas and cannot include linebreaks.
 */

return array(

  // Organized by view filename:

  "admin" => array(
    "projects" => array(
      "project_helper_text" => "Public projects are shown in the list of templates available for forking."
    ),
    "vendors" => array(
      "ban_vendor_confirmation" => "Are you sure you want to ban this vendor? This will also remove any pending bids
                                    that they have submitted."
    )
  ),

  "bids" => array(
    "partials" => array(
      "award_modal" => array(
        "header" => "You've selected a vendor!",
        "description" => "When you award this contract, we'll send the message below to the vendor that you
                          have accepted their bid and are ready to start working with them.
                          Make sure all your \"i\"s are dotted and \"t\"s are crossed before you hit the button below.
                          We will also automatically dismiss all other bids on this project.",
        "co_warning" => "Awarding contracts is for <strong>registered contracting officers</strong> only. If you're not a CO, turn back now.",
        "due_date_warning" => "<strong>Careful!</strong> The due date for proposals hasn't passed. Awarding now may yield a protest.",
        "no_email_label" => "No thanks, I'd prefer to send an email to the vendor by myself",
      ),
      "bid_details_vendors_view" => array(
        "review" => "Your bid is currently being reviewed. We'll let you know when the status changes.",
        "dismissed" => "Your bid has been dismissed.",
        "won_header" => "<strong>Your bid won!</strong>",
        "won_body" => "Here's what the government officer said:<br /><br /><em>\":message\"</em>"
      ),
      "bid_for_review" => array(
        "congrats" => "Congrats on finding a great bid!",
        "only_co" => "Only COs can dismiss bids."
      ),
      "dismiss_modal" => array(
        "optional_fields" => "These fields are <strong>optional</strong>, and will not be shown to the vendor.
                              They may be useful to log, however, in case of a future contest."
      )
    ),
    "mine" => array(
      "find_projects" => "Find some projects!"
    ),
    "new" => array(
      "editing_draft" => "You are editing a draft saved on :date.",
      "approach_placeholder" => "Give us some quick details of the tools, techniques, and processes you'd use to create a great solution.",
      "previous_work_placeholder" => "Where possible, please provide links.",
      "employee_details_placeholder" => "One name per line. We just need to make sure nobody has been put on a list of people disallowed to work on government contracts.",
      "no_edit_warning" => "note: bids cannot be edited once submitted!"
    ),
    "review" => array(
      "stars_tip" => "Stars are shared among collaborators. By starring a bid, you can indicate to your colleagues that you think a bid stands out.",
    )
  ),

  "home" => array(
    "index_signed_out" => array(
      "site_tagline" => "A Technology Marketplace That Everybody Loves",
      "biz_header" => "For Small Business",
      "biz_description" => "Create a simple online profile and begin bidding on <a href=':url'>projects</a>.
                            If you're selected to work on one, we'll walk you through the government registration process.",
      "biz_button" => "Register as a Business",
      "gov_header" => "For Government",
      "gov_description" => "Make great statements of work. Browse innovative tech businesses and see their online portfolios.
                            Receive and review bids on your projects.",
      "gov_button" => "Register as a Government Officer"
    )
  ),







  // Globals:

  "bid_award_message" => "Congratulations, your bid won! You've been accepted to work on \":title\". The contracting officer, :officer_name (:officer_email), will follow up with details shortly.",

  "delete_bid_confirmation" => "Are you sure you want to delete your bid?",

  "footer_text" => "EasyBid is an official website of the United States Government, and was
                    created by <a href='http://wh.gov/innovationfellows/rfpez'>Team Project RFP-EZ</a> as part of the
                    Presidential Innovation Fellowship program.",

  "email_signature_text" => "-The EasyBid Team",

  "email_signature_html" => "<p><em>-The EasyBid Team</em></p>"


);