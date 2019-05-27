<?php
// This file is part of Stack - http://stack.maths.ed.ac.uk/
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


// Answer test controller class.
//
// @copyright  2012 University of Birmingham
// @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.

require_once(__DIR__ . '/anstest.class.php');
require_once(__DIR__ . '/at_general_cas.class.php');
require_once(__DIR__ . '/../cas/connector.class.php');
require_once(__DIR__ . '/../cas/ast.container.class.php');

class stack_ans_test_controller {
    protected static $types = array(
              'AlgEquiv'             => 'stackOptions_AnsTest_values_AlgEquiv',
              'EqualComAss'          => 'stackOptions_AnsTest_values_EqualComAss',
              'CasEqual'             => 'stackOptions_AnsTest_values_CasEqual',
              'SameType'             => 'stackOptions_AnsTest_values_SameType',
              'SubstEquiv'           => 'stackOptions_AnsTest_values_SubstEquiv',
              'SysEquiv'             => 'stackOptions_AnsTest_values_SysEquiv',
              'Sets'                 => 'stackOptions_AnsTest_values_Sets',
              'Expanded'             => 'stackOptions_AnsTest_values_Expanded',
              'FacForm'              => 'stackOptions_AnsTest_values_FacForm',
              'SingleFrac'           => 'stackOptions_AnsTest_values_SingleFrac',
              'PartFrac'             => 'stackOptions_AnsTest_values_PartFrac',
              'CompSquare'           => 'stackOptions_AnsTest_values_CompSquare',
              'Equiv'                => 'stackOptions_AnsTest_values_Equiv',
              'EquivFirst'           => 'stackOptions_AnsTest_values_EquivFirst',
              'GT'                   => 'stackOptions_AnsTest_values_GT',
              'GTE'                  => 'stackOptions_AnsTest_values_GTE',
              'SigFigsStrict'        => 'stackOptions_AnsTest_values_SigFigsStrict',
              'NumAbsolute'          => 'stackOptions_AnsTest_values_NumAbsolute',
              'NumRelative'          => 'stackOptions_AnsTest_values_NumRelative',
              'NumSigFigs'           => 'stackOptions_AnsTest_values_NumSigFigs',
              'NumDecPlaces'         => 'stackOptions_AnsTest_values_NumDecPlaces',
              'NumDecPlacesWrong'    => 'stackOptions_AnsTest_values_NumDecPlacesWrong',
              'Units'                => 'stackOptions_AnsTest_values_UnitsSigFigs',
              'UnitsStrict'          => 'stackOptions_AnsTest_values_UnitsStrictSigFigs',
              'UnitsAbsolute'        => 'stackOptions_AnsTest_values_UnitsAbsolute',
              'UnitsStrictAbsolute'  => 'stackOptions_AnsTest_values_UnitsStrictAbsolute',
              'UnitsRelative'        => 'stackOptions_AnsTest_values_UnitsRelative',
              'UnitsStrictRelative'  => 'stackOptions_AnsTest_values_UnitsStrictRelative',
              'LowestTerms'          => 'stackOptions_AnsTest_values_LowestTerms',
              'Diff'                 => 'stackOptions_AnsTest_values_Diff',
              'Int'                  => 'stackOptions_AnsTest_values_Int',
              'String'               => 'stackOptions_AnsTest_values_String',
              'StringSloppy'         => 'stackOptions_AnsTest_values_StringSloppy',
              'RegExp'               => 'stackOptions_AnsTest_values_RegExp',
              );

    /* 
     * Does this test require options [0] and are these evaluated by the CAS [1] ?
     */
    protected static $pops = array(
        'AlgEquiv'             => array(false, false),
        'EqualComAss'          => array(false, false),
        'CasEqual'             => array(false, false),
        'SameType'             => array(false, false),
        'SubstEquiv'           => array(false, false),
        'SysEquiv'             => array(false, false),
        'Sets'                 => array(false, false),
        'Expanded'             => array(false, false),
        'FacForm'              => array(true, true),
        'SingleFrac'           => array(false, false),
        'PartFrac'             => array(true, true),
        'CompSquare'           => array(true, true),
        'Equiv'                => array(true, true),
        'EquivFirst'           => array(true, true),
        'GT'                   => array(false, false),
        'GTE'                  => array(false, false),
        'SigFigsStrict'        => array(true, true),
        'NumAbsolute'          => array(true, true),
        'NumRelative'          => array(true, true),
        'NumSigFigs'           => array(true, true),
        'NumDecPlaces'         => array(true, true),
        'NumDecPlacesWrong'    => array(true, true),
        'Units'                => array(true, true),
        'UnitsStrict'          => array(true, true),
        'UnitsAbsolute'        => array(true, true),
        'UnitsStrictAbsolute'  => array(true, true),
        'UnitsRelative'        => array(true, true),
        'UnitsStrictRelative'  => array(true, true),
        'LowestTerms'          => array(false, false),
        'Diff'                 => array(true, true),
        'Int'                  => array(true, true),
        'String'               => array(false, false),
        'StringSloppy'         => array(false, false),
        'RegExp'               => array(true, false),
    );

    /**
     * The answertest object that the functions call.
     * @var stack_anstest
     * @access private
     */
    private $at;

    /**
     *
     *
     * @param  string $AnsTest
     * @param  string $sans A CAS string assumed to represent the student's answer.
     * @param  string $tans A CAS string assumed to represent the tecaher's answer.
     * @param  object $options
     * @param  CasString $casoption
     * @access public
     */
    public function __construct(string $anstest, $sans = null, $tans = null, $options = null, $casoption = null) {

        switch($anstest) {
            case 'AlgEquiv':
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATAlgEquiv', $casoption, $options);
                break;

            case 'EqualComAss':
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATEqualComAss', $casoption, $options, false);
                break;

            case 'CasEqual':
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATCASEqual', $casoption, $options, false);
                break;

            case 'SameType':
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATSameType', $casoption, $options);
                break;

            case 'SubstEquiv':
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATSubstEquiv', $casoption, $options);
                break;

            case 'Sets':
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATSets', $casoption, $options, false);
                break;

            case 'Expanded':
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATExpanded', $casoption, $options);
                break;

            case 'FacForm':
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATFacForm', $casoption, $options, false);
                break;

            case 'SingleFrac':
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATSingleFrac', $casoption, $options, false);
                break;

            case 'PartFrac':
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATPartFrac', $casoption, $options, true);
                break;

            case 'CompSquare':
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATCompSquare', $casoption, $options, true);
                break;

            case 'Equiv':
                if (trim($casoption) == '' ) {
                    // Note the *string* 'null' here is not mistake: this is passed to Maxima.
                    $this->at = new stack_answertest_general_cas($sans, $tans, 'ATEquiv', 'null', $options);
                } else {
                    $this->at = new stack_answertest_general_cas($sans, $tans, 'ATEquiv', $casoption, $options);
                }
                break;

            case 'EquivFirst':
                if (trim($casoption) == '' ) {
                    $this->at = new stack_answertest_general_cas($sans, $tans, 'ATEquivFirst', 'null', $options);
                } else {
                    $this->at = new stack_answertest_general_cas($sans, $tans, 'ATEquivFirst', $casoption, $options);
                }
                break;

            case 'String':
                require_once(__DIR__ . '/atstring.class.php');
                $this->at = new stack_anstest_atstring($sans, $tans, $options, $casoption);
                break;

            case 'StringSloppy':
                require_once(__DIR__ . '/stringsloppy.class.php');
                $this->at = new stack_anstest_stringsloppy($sans, $tans, $options, $casoption);
                break;

            case 'RegExp':
                require_once(__DIR__ . '/atregexp.class.php');
                $this->at = new stack_anstest_atregexp($sans, $tans, $options, $casoption);
                break;

            case 'Diff':
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATDiff', $casoption, $options, false, true);
                break;

            case 'Int':
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATInt', $casoption, $options, false, true);
                break;

            case 'GT':
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATGT', $casoption, $options);
                break;

            case 'GTE':
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATGTE', $casoption, $options);
                break;

            case 'SigFigsStrict':
                require_once(__DIR__ . '/atnumsigfigs.class.php');
                $this->at = new stack_anstest_atnumsigfigs($sans, $tans, $options, $casoption, 'ATSigFigsStrict', true);
                break;

            case 'NumAbsolute':
                if (trim($casoption) == '') {
                    $casoption = '0.05';
                }
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATNumAbsolute', $casoption, $options, true);
                break;

            case 'NumRelative':
                if (trim($casoption) == '') {
                    $casoption = '0.05';
                }
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATNumRelative', $casoption, $options, true);
                break;

            case 'NumSigFigs':
                require_once(__DIR__ . '/atnumsigfigs.class.php');
                $this->at = new stack_anstest_atnumsigfigs($sans, $tans, $options, $casoption, 'ATNumSigFigs', true);
                break;

            case 'NumDecPlaces':
                require_once(__DIR__ . '/atdecplaces.class.php');
                $this->at = new stack_anstest_atdecplaces($sans, $tans, $options, $casoption);
                break;

            case 'NumDecPlacesWrong':
                require_once(__DIR__ . '/atdecplaceswrong.class.php');
                $this->at = new stack_anstest_atdecplaceswrong($sans, $tans, $options, $casoption);
                break;

            case 'Units':
                require_once(__DIR__ . '/atnumsigfigs.class.php');
                $this->at = new stack_anstest_atnumsigfigs($sans, $tans, $options, $casoption, 'ATUnitsSigFigs', false);
                break;

            case 'UnitsStrict':
                require_once(__DIR__ . '/atnumsigfigs.class.php');
                $this->at = new stack_anstest_atnumsigfigs($sans, $tans, $options, $casoption, 'ATUnitsStrictSigFigs', false);
                break;

            case 'UnitsAbsolute':
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATUnitsAbsolute', $casoption, $options, false);
                break;

            case 'UnitsStrictAbsolute':
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATUnitsStrictAbsolute', $casoption, $options, false);
                break;

            case 'UnitsRelative':
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATUnitsRelative', $casoption, $options, false);
                break;

            case 'UnitsStrictRelative':
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATUnitsStrictRelative', $casoption, $options, false);
                break;

            case 'LowestTerms':
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATLowestTerms', $casoption, $options, false);
                break;

            case 'SysEquiv':
                $this->at = new stack_answertest_general_cas($sans, $tans, 'ATSysEquiv', $casoption, $options);
                break;

            default:
                throw new stack_exception('stack_ans_test_controller: called with invalid answer test name: '.$anstest);
        }
    }

    /**
     *
     *
     * @return bool
     * @access public
     */
    public function do_test() {
        $result = $this->at->do_test();
        return $result;
    }

    /**
     *
     *
     * @return string
     * @access public
     */
    public function get_at_errors() {
        return $this->at->get_at_errors();
    }

    /**
     *
     *
     * @return float
     * @access public
     */
    public function get_at_mark() {
        return $this->at->get_at_mark();
    }

    /**
     *
     *
     * @return bool
     * @access public
     */
    public function get_at_valid() {
        return $this->at->get_at_valid();
    }

    /**
     *
     *
     * @return string
     * @access public
     */
    public function get_at_answernote() {
        return trim($this->at->get_at_answernote());
    }

    /**
     *
     *
     * @return string
     * @access public
     */
    public function get_at_feedback() {
        return stack_maxima_translate($this->at->get_at_feedback());
    }

    /**
     * @return array the list of available answertest types. An array
     *      answertest internal name => language string key.
     */
    public static function get_available_ans_tests() {
        return self::$types;
    }

    /**
     * Returns whether the testops should be processed by the CAS for this AnswerTest
     *
     * @return bool
     * @access public
     */
    public static function process_atoptions($atest) {
        $op = self::$pops[$atest];
        return $op[1];
    }

    /**
     * Returns whether the testops are required for this test.
     *
     * @return bool
     * @access public
     */
    public static function required_atoptions($atest) {
        $op = self::$pops[$atest];
        return $op[0];
    }

    /**
     * Validates the options, when needed.
     *
     * @return bool
     * @access public
     */
    public function validate_atoptions($opt) {
        return $this->at->validate_atoptions($opt);
    }

    /**
     * Pass back CAS debug information for testing.
     *
     * @return string
     * @access public
     */
    public function get_debuginfo() {
        return $this->at->get_debuginfo();
    }

    /**
     * Returns an intelligible trace of an executed answer test.
     *
     * @return string
     * @access public
     */
    public function get_trace($includeresult = true) {
        return $this->at->get_trace($includeresult);
    }
}

