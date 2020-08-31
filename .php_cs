<?php

$rules = [
    '@PHP71Migration' => true,
    '@PHPUnit48Migration:risky' => true,
    '@PSR2' => true,
    '@Symfony' => true,
    '@Symfony:risky' => true,
    'align_multiline_comment' => true,
    'binary_operator_spaces' => ['default' => 'single_space', 'operators' => ['=>' => null]],
    'blank_line_after_opening_tag' => true,
    'blank_line_before_statement' => ['statements' => ['return']],
    'braces' => ['allow_single_line_closure' => true],
    'cast_spaces' => true,
    'class_attributes_separation' => ['elements' => ['method']],
    'concat_space' => ['spacing' => 'one'],
    'declare_equal_normalize' => true,
    'declare_strict_types' => false,
    'dir_constant' => true,
    'doctrine_annotation_array_assignment' => true,
    'doctrine_annotation_braces' => true,
    'doctrine_annotation_indentation' => true,
    'doctrine_annotation_spaces' => true,
    'fully_qualified_strict_types' => true,
    'function_typehint_space' => true,
    'general_phpdoc_annotation_remove' => ['annotations' => ['author', 'package']],
    'include' => true,
    'increment_style' => ['style' => 'post'],
    'list_syntax' => ['syntax' => 'short'],
    'lowercase_cast' => true,
    'lowercase_constants' => true,
    'lowercase_keywords' => true,
    'lowercase_static_reference' => true,
    'magic_constant_casing' => true,
    'magic_method_casing' => true,
    'mb_str_functions' => false,
    'method_argument_space' => true,
    'method_chaining_indentation' => true,
    'method_separation' => true,
    'modernize_types_casting' => true,
    'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
    'native_function_casing' => true,
    'native_function_type_declaration_casing' => true,
    'new_with_braces' => true,
    'no_alias_functions' => true,
    'no_blank_lines_after_class_opening' => true,
    'no_blank_lines_after_phpdoc' => true,
    'no_break_comment' => true,
    'no_closing_tag' => true,
    'no_empty_comment' => true,
    'no_empty_phpdoc' => true,
    'no_empty_statement' => true,
    'no_extra_blank_lines' => ['tokens' => ['extra', 'throw', 'use', 'use_trait']],
    'no_leading_import_slash' => true,
    'no_leading_namespace_whitespace' => true,
    'no_mixed_echo_print' => ['use' => 'echo'],
    'no_multiline_whitespace_around_double_arrow' => true,
    'no_multiline_whitespace_before_semicolons' => true,
    'no_null_property_initialization' => true,
    'no_php4_constructor' => true,
    'no_short_bool_cast' => true,
    'no_short_echo_tag' => true,
    'no_singleline_whitespace_before_semicolons' => true,
    'no_spaces_after_function_name' => true,
    'no_spaces_around_offset' => true,
    'no_spaces_inside_parenthesis' => true,
    'no_superfluous_elseif' => true,
    'no_superfluous_phpdoc_tags' => true,
    'no_trailing_comma_in_list_call' => true,
    'no_trailing_comma_in_singleline_array' => true,
    'no_trailing_whitespace' => true,
    'no_trailing_whitespace_in_comment' => true,
    'no_unneeded_control_parentheses' => true,
    'no_unneeded_curly_braces' => true,
    'no_unneeded_final_method' => true,
    'no_unset_cast' => true,
    'no_unused_imports' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'no_whitespace_before_comma_in_array' => true,
    'no_whitespace_in_blank_line' => true,
    'normalize_index_brace' => true,
    'nullable_type_declaration_for_default_null_value' => true,
    'not_operator_with_space' => false,
    'not_operator_with_successor_space' => false,
    'object_operator_without_whitespace' => true,
    'ordered_class_elements' => true,
    'ordered_imports' => ['sortAlgorithm' => 'alpha'],
    'phpdoc_add_missing_param_annotation' => ['only_untyped' => false],
    'phpdoc_align' => ['align' => 'vertical'],
    'phpdoc_annotation_without_dot' => false,
    'phpdoc_indent' => true,
    'phpdoc_inline_tag' => true,
    'phpdoc_no_access' => true,
    'phpdoc_no_package' => true,
    'phpdoc_no_useless_inheritdoc' => true,
    'phpdoc_order' => true,
    'phpdoc_scalar' => true,
    'phpdoc_single_line_var_spacing' => true,
    'phpdoc_summary' => true,
    'phpdoc_to_comment' => true,
    'phpdoc_trim' => true,
    'phpdoc_types' => true,
    'phpdoc_types_order' => ['null_adjustment' => 'always_last'],
    'phpdoc_var_without_name' => true,
    'protected_to_private' => true,
    'psr0' => false,
    'psr4' => false,
    'return_assignment' => true,
    'self_accessor' => true,
    'self_static_accessor' => true,
    'semicolon_after_instruction' => true,
    'short_scalar_cast' => true,
    'simple_to_complex_string_variable' => true,
    'simplified_null_return' => true,
    'single_blank_line_at_eof' => true,
    'single_blank_line_before_namespace' => true,
    'single_class_element_per_statement' => true,
    'single_line_comment_style' => true,
    'single_quote' => true,
    'space_after_semicolon' => true,
    'standardize_increment' => true,
    'standardize_not_equals' => true,
    'static_lambda' => false,
    'switch_case_space' => true,
    'ternary_operator_spaces' => true,
    'ternary_to_null_coalescing' => true,
    'trailing_comma_in_multiline_array' => true,
    'trim_array_spaces' => true,
    'unary_operator_spaces' => true,
    'visibility_required' => ['elements' => ['method', 'property', 'const']],
    'whitespace_after_comma_in_array' => true,
];

$finder = Symfony\Component\Finder\Finder::create()
    ->in(__DIR__)
    ->name('*.php')
    ->notPath('vendor')
    ->notPath('views')
    ->notPath('libraries')
    ->notPath('cache')
    ->notPath('logs')
    ->notPath('third_party')
    ->notPath('public')
    ->notPath('assets')
    ->notPath('storage')
    //->notPath('bootstrap')
    ->notName('*.test.php')
    ->notName('*/migrations/*')
    ->notName('*/seeds/*')
    ->notName('*.js')
    ->notName('_ide*.php')
    ->notName('.phpstorm*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules($rules)
    //->setIndent("\t")
    ->setLineEnding("\n")
    ->setUsingCache(true)
    ->setCacheFile(__DIR__ . '/.php_cs.cache')
    ->setFinder($finder);