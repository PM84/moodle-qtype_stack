<?php
// This file is part of Stack - https://stack.maths.ed.ac.uk
//
// Stack is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Stack is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Stack.  If not, see <http://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/filter.interface.php');
require_once(__DIR__ . '/../cassecurity.class.php');

/**
 * AST filter that identifies cases like 'xsin(x)' and splits them
 * 'x*sin(x)'. Applies to all possible globally known functions and
 * tries to find the longest possible suffix. Probably causes issues
 * with self defined functions.
 *
 * Tags the stars and adds 'missing_stars' answernote.
 */
class stack_ast_filter_402_split_prefix_from_common_function_name implements stack_cas_astfilter {

    public function filter(MP_Node $ast, array &$errors, array &$answernotes, stack_cas_security $identifierrules): MP_Node {
        $known = stack_cas_security::get_protected_identifiers('function', $identifierrules->get_units());

        $process = function($node) use (&$answernotes, $known) {
            if ($node instanceof MP_Functioncall && $node->name instanceof MP_Identifier) {
                // Is it known?
                if (array_key_exists($node->name->value, $known)) {
                    return true;
                }

                // Find if there are any suffixes.
                $longest = false;
                $value = $node->name->value;
                $len = core_text::strlen($value);
                foreach ($known as $key => $other) {
                    if ($len > core_text::strlen($key)) {
                        if (core_text::substr($value, -core_text::strlen($key)) === $key) {
                            $longest = $key;
                            break;
                        }
                    }
                }

                // Split.
                if ($longest !== false) {
                    $prefix = core_text::substr($value, 0, -core_text::strlen($longest));
                    $node->name->value = $longest;
                    $nop = new MP_Operation('*', new MP_Identifier($prefix), $node);
                    $nop->position['insertstars'] = true;
                    $answernotes[] = 'missing_stars';
                    $node->parentnode->replace($node, $nop);
                    return false;
                }
            }
            return true;
        };
        // @codingStandardsIgnoreStart
        while ($ast->callbackRecurse($process) !== true) {
        }
        // @codingStandardsIgnoreEnd
        return $ast;
    }
}