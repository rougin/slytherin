<?php

// Specify the paths in this variable ---
$paths = array(__DIR__ . '/src');
$paths[] = realpath(__DIR__ . '/tests');
// --------------------------------------

// Specify the rules for code formatting ---------
$rules = array('@PSR12' => true);

$cscp = 'control_structure_continuation_position';
$rules[$cscp] = array('position' => 'next_line');

$braces = array();
$braces['control_structures_opening_brace'] = 'next_line_unless_newline_at_signature_end';
$braces['functions_opening_brace'] = 'next_line_unless_newline_at_signature_end';
$braces['anonymous_functions_opening_brace'] = 'next_line_unless_newline_at_signature_end';
$braces['anonymous_classes_opening_brace'] = 'next_line_unless_newline_at_signature_end';
$braces['allow_single_line_empty_anonymous_classes'] = false;
$braces['allow_single_line_anonymous_functions'] = false;

$rules['braces_position'] = $braces;

$rules['new_with_parentheses'] = array('named_class' => false);

$visibility = array('elements' => array());
$visibility['elements'] = array('method', 'property');
$rules['visibility_required'] = $visibility;
// -----------------------------------------------

$finder = new \PhpCsFixer\Finder;

$finder->in((array) $paths);

$config = new \PhpCsFixer\Config;

$config->setRules($rules);

return $config->setFinder($finder);
