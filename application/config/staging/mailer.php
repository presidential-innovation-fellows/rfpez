<?php

return array(
  'transport' => Swift_SmtpTransport::newInstance('', 465, 'ssl')
                                    ->setUsername('')
                                    ->setPassword(''),

  'send_all_to' => array('aaron.snow@gsa.gov', 'gregory.godbout@gsa.gov')
);