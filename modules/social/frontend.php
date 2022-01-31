<?php

/**
 * This file should be used to render each module instance.
 * You have access to two variables in this file:
 *
 * $module An instance of your module class.
 * $settings The module's settings.
 *
 */
$URL = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$context = \Timber\Timber::get_context();
$context['URL'] =  urlencode($URL);   
  
\Timber\Timber::render('social.twig', $context);