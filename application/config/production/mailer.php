<?php

return array(
  'transport' => Swift_SmtpTransport::newInstance('', 465, 'ssl')
                                    ->setUsername('')
                                    ->setPassword('')
);