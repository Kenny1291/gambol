<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__);

return (new PhpCsFixer\Config())
    ->setRules([
        'braces_position' => [
            'allow_single_line_anonymous_functions' => false,
            'classes_opening_brace' => 'same_line',
            'functions_opening_brace' => 'same_line'
        ],
        'no_multiple_statements_per_line' => true,
        'no_trailing_comma_in_singleline' => true,
        'single_line_empty_body' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_whitespace_before_comma_in_array' => true,
        'normalize_index_brace' => true,
        'trim_array_spaces' => true,
        'whitespace_after_comma_in_array' => ['ensure_single_space' => true],
        'attribute_empty_parentheses' => true,
        'class_reference_name_casing' => true,
        'constant_case' => true,
        'lowercase_keywords' => true,
        'lowercase_static_reference' => true,
        'magic_constant_casing' => true,
        'magic_method_casing' => true,
        'native_function_casing' => true,
        'native_type_declaration_casing' => true,
        'cast_spaces' => ['space' => 'none'],
        'lowercase_cast' => true,
        'no_short_bool_cast' => true,
        'no_unset_cast' => true,
        'short_scalar_cast' => true,
        'class_attributes_separation' => [
            'elements' => [
                'const' => 'none',
                'method' => 'one',
//                'property' => 'only_if_meta',//Not working as I expect, is adding space between properties with comments
                'trait_import' => 'none',
                'case' => 'none'
            ]
        ],
        'class_definition' => [
            'single_item_single_line' => true,
            'single_line' => true,
            'space_before_parenthesis' => true
        ],
        'final_class' => true,
        'final_public_method_for_abstract_class' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_null_property_initialization' => true,
        'no_php4_constructor' => true,
        'no_unneeded_final_method' => true,
//        'ordered_class_elements'
        'protected_to_private' => true,
        'self_accessor' => true,
        'self_static_accessor' => true,
        'single_class_element_per_statement' => true,
        'visibility_required' => true,
        'multiline_comment_opening_closing' => true,
        'no_empty_comment' => true,
        'no_trailing_whitespace_in_comment' => true,
        'single_line_comment_style' => true,
        'control_structure_braces' => true,
        'control_structure_continuation_position' => true,
        'include' => true,
        'no_alternative_syntax' => true,
        'no_superfluous_elseif' => true,
        'no_unneeded_braces' => true,
        'no_unneeded_control_parentheses' => true,
        'no_useless_else' => true,
        'function_declaration' => true,
        'lambda_not_used_import' => true,
        'method_argument_space' => true,
        'no_spaces_after_function_name' => true,
        'nullable_type_declaration_for_default_null_value' => true,
        'regular_callable_call' => true,
        'return_type_declaration' => true,
        'single_line_throw' => true,
        'static_lambda' => true,
        'fully_qualified_strict_types' => true,
        'global_namespace_import' => [
            'import_classes' => false,
            'import_constants' => false,
            'import_functions' => false
        ],
        'no_leading_import_slash' => true,
        'no_unneeded_import_alias' => true,
        'no_unused_imports' => true,
        'ordered_imports' => [
            'sort_algorithm' => 'length'
        ],
        'single_import_per_statement' => true,
        'single_line_after_imports' => true,
        'declare_equal_normalize' => true,
        'single_space_around_construct' => true,
        'list_syntax' => true,
        'blank_line_after_namespace' => true,
        'clean_namespace' => true,
        'no_leading_namespace_whitespace' => true,
        'assign_null_coalescing_to_coalesce_equal' => true,
        'binary_operator_spaces' => true,
        'concat_space' => ['spacing' => 'one'],
        'new_with_parentheses' => true,
        'no_space_around_double_colon' => true,
        'no_useless_nullsafe_operator' => true,
        'object_operator_without_whitespace' => true,
        'ternary_operator_spaces' => true,
        'ternary_to_null_coalescing' => true,
        'unary_operator_spaces' => true,
        'blank_line_after_opening_tag' => true,
        'full_opening_tag' => true,
        'no_closing_tag' => true,
        'no_useless_return' => true,
        'multiline_whitespace_before_semicolons' => true,
        'no_empty_statement' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'space_after_semicolon' => true,
        'declare_strict_types' => true,
        'strict_param' => true,
        'explicit_string_variable' => true,
        'array_indentation' => true,
        'compact_nullable_type_declaration' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'attribute',
                'break',
                'case',
                'continue',
                'curly_brace_block',
                'default',
                'extra',
                'parenthesis_brace_block',
                'return',
                'square_brace_block',
                'switch',
                'throw',
                'use',
                'use_trait'
            ]
        ],
        'no_spaces_around_offset' => true,
        'no_trailing_whitespace' => true,
        'no_whitespace_in_blank_line' => true,
        'spaces_inside_parentheses' => true,
        'statement_indentation' => true,
        'type_declaration_spaces' => true,
        'types_spaces' => true,


    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true)
;