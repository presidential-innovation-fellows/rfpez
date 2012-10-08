<?php

return array(
  'transport' => Swift_SmtpTransport::newInstance('', 465, 'ssl')
                                    ->setUsername('')
                                    ->setPassword(''),

  // Until we're in production, don't actually send emails to our real recipients.
  'send_all_to' => 'rfpez@sba.gov'
);