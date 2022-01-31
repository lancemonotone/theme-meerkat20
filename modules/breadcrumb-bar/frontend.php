<?php

/**
 * This file should be used to render each module instance.
 * You have access to two variables in this file:
 *
 * $module An instance of your module class.
 * $settings The module's settings.
 *
 *
 */

$context = \Timber\Timber::get_context();

$context['breadcrumbs'] = str_replace("&raquo;", "&#47;", $context['breadcrumbs']);

$dom = new \DOMDocument();
$dom->loadHTML($context['breadcrumbs']);
$finder = new \DOMXPath($dom);


if ($settings->wms_home_crumb == 'true'){
    $classname= 'wms-home-crumb';
    $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
    
     foreach ($nodes as $node) {
       $sib = $node->nextSibling;
     
        if ($sib) $sib->parentNode->removeChild($sib);
        $node->parentNode->removeChild($node);
        
    }
    $context['breadcrumbs'] = $dom->saveHTML();
}
if ($settings->wms_dept_crumb == 'true'){
    $classname= 'dept-home-crumb';
    $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
    
     foreach ($nodes as $node) {
        $sib = $node->nextSibling;
        if ($sib) $sib->parentNode->removeChild($sib);
        $node->parentNode->removeChild($node);
        
    }
    $context['breadcrumbs'] = $dom->saveHTML();
}
$context['node']  = $module->node;
\Timber\Timber::render('breadcrumb-bar.twig', $context);