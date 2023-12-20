<?php

$finder = PhpCsFixer\Finder::create()
    ->path('src')
    ->in(__DIR__);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR2' => true,
        'array_indentation' => true,
        'array_syntax' => ['syntax' => 'short'],
        'assign_null_coalescing_to_coalesce_equal' => true,
        'combine_consecutive_unsets' => true,
        'align_multiline_comment' => true,
        'class_attributes_separation' => [
            'elements' => [
                'method' => 'one',
                'property' => 'only_if_meta'
            ]
        ],
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line'
        ],
        'single_quote' => true,
        'standardize_not_equals' => true,
        'binary_operator_spaces' => [
            'operators' => [
                '=>' => 'align',
                '===' => 'align_single_space_minimal',
                '+=' => 'align_single_space',
                '=' => 'align'
            ]
        ],
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,
        'blank_line_before_statement' => true,
        'braces' => [
            'allow_single_line_closure' => true,
        ],
        'cast_spaces' => true,
        'constant_case' => ['case' => 'lower'],
        'class_definition' => ['single_line' => true],
        'concat_space' => ['spacing' => 'one'],
        'combine_consecutive_unsets' => true,
        'combine_consecutive_issets' => true,
        'compact_nullable_typehint' => true,
        'declare_equal_normalize' => true,
        'function_typehint_space' => true,
        'single_line_comment_style' => ['comment_types' => ['hash']],
        'include' => true,
        'lowercase_cast' => true,
        'empty_loop_body' => ['style' => 'braces'],
        'global_namespace_import' => true,
        'native_function_casing' => true,
        'new_with_braces' => true,
        // 'new_with_braces' => true,
        // 'no_blank_lines_after_class_opening' => true,
        // 'no_blank_lines_after_phpdoc' => true,
        'no_blank_lines_before_namespace' => true,
        // 'no_empty_comment' => true,
        // 'no_empty_phpdoc' => true,
        // 'no_empty_statement' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'curly_brace_block',
                'extra',
                'parenthesis_brace_block',
                'return',
                'square_brace_block',
                'throw',
                'use',
            ]
        ],
        // 'no_leading_import_slash' => true,
        // 'no_leading_namespace_whitespace' => true,
        // 'no_mixed_echo_print' => array('use' => 'echo'),
        'no_unset_cast' => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_null_property_initialization' => true,
        'no_useless_else' => true,
        // 'no_short_bool_cast' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_space_around_double_colon' => true,
        'no_spaces_around_offset' => ['positions' => ['inside']],
        'no_spaces_inside_parenthesis' => true,
        'no_trailing_comma_in_list_call' => true,
        // 'no_trailing_comma_in_singleline_array' => true,
        // 'no_unneeded_control_parentheses' => true,
        'single_line_throw' => true,
        'simple_to_complex_string_variable' => true,
        'no_unused_imports' => true,
        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => true,
        // 'normalize_index_brace' => true,
        'object_operator_without_whitespace' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'ordered_class_elements' => [
            'sort_algorithm' => 'alpha'
        ],
        'increment_style' => ['style' => 'post'],
        'phpdoc_add_missing_param_annotation' => true,
        // 'php_unit_fqcn_annotation' => true,
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_annotation_without_dot' => true,
        'phpdoc_indent' => true,
        // 'phpdoc_inline_tag' => true,
        // 'phpdoc_no_access' => true,
        // 'phpdoc_no_alias_tag' => true,
        // 'phpdoc_no_empty_return' => true,
        // 'phpdoc_no_package' => true,
        // 'phpdoc_no_useless_inheritdoc' => true,
        // 'phpdoc_return_self_reference' => true,
        // 'phpdoc_scalar' => true,
        // 'phpdoc_separation' => true,
        // 'phpdoc_single_line_var_spacing' => true,
        // 'phpdoc_summary' => true,
        // 'phpdoc_to_comment' => true,
        'phpdoc_trim' => true,
        // 'phpdoc_types' => true,
        // 'phpdoc_var_without_name' => true,
        // 'return_type_declaration' => true,
        // 'self_accessor' => true,
        // 'short_scalar_cast' => true,
        // 'single_blank_line_before_namespace' => true,
        // 'single_class_element_per_statement' => true,
        // 'space_after_semicolon' => true,
        'magic_constant_casing' => true,
        'ternary_operator_spaces' => true,
        'lowercase_keywords' => true,
        'lowercase_cast' => true,
        'lowercase_static_reference' => true,
        // 'trailing_comma_in_multiline_array' => true,
        'trim_array_spaces' => true,
        'unary_operator_spaces' => true,
        'whitespace_after_comma_in_array' => true,
        'space_after_semicolon' => true,
        // 'single_blank_line_at_eof' => false
    ])
    // ->setIndent("\t")
    ->setLineEnding("\n")
    ->setFinder($finder);
