<?php

return array(
  'transport' => Swift_SmtpTransport::newInstance('', 465, 'ssl')
                                    ->setUsername('')
                                    ->setPassword(''),

  'send_all_to' => array('adam@presidentialinnovationfellows.org', 'jed@presidentialinnovationfellows.org')
);