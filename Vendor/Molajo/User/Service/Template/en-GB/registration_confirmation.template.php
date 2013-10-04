<?php

$template = new stdClass();

$template->name    = 'registration_confirmation';
$template->subject = 'Registration Confirmation';
$template->body    = 'On {today}, {name} requested membership at this site. '
    . 'To confirm your membership, please use this {link} link.';
