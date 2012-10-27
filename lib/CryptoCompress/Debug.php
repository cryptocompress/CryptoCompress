<?php

namespace CryptoCompress;

class Debug {

    /**
     * @var string  $format
     */
    private $format;

    /**
     * @var array   $templates
     */
    private $templates;

    /**
     * @param   string  $sapiName       - output of php_sapi_name()
     * @param   int     $extraIndent    - indent for tags in html source code
     */
    public function __construct($sapiName = 'cli', $escape = true, $extraIndent = 200) {
        switch ($sapiName) {
            case 'cli':
                $this->format = 'plain';
                break;

            default:
                if (!headers_sent()) {
                    header('Content-Type: text/html; charset=utf-8');
                }
                $this->format = 'html';
                break;
        }

        // escape "%" char with "%%"
        $this->templates = array(
            'html' => array(
                'table' => str_repeat(' ', $extraIndent) . '<table border="1" width="100%%" style="text-align: left; vertical-align: top; background-color: #FF0000 !important; z-index: 10000; color: #000000; font-size: 12px;">%s</table>' . "\n\n",
                'tr'    => '<tr>%s</tr>',
                'th'    => '<th width="1%%" style="color: #FFFF00; font-size: 40px; text-align: center; vertical-align: middle;">☭</th><th style="font-size: 20px;"><strong>' . "\n" . '%s() at %s:%u' . "" . str_repeat(' ', $extraIndent) . '</strong></th>',
                'tdnb'  => '<td id="__1__ANKER__%s" style="vertical-align: top;"><nobr>' . "\n\n" . '%s' . str_repeat(' ', $extraIndent) . '</nobr><br /><span style="color: #FFFF00; font-size: 40px; text-align: center; vertical-align: middle;" onclick="javascript: { var a = document.getElementById(\'__2__ANKER__%s\'); if (a.style.display == \'none\') { a.style.display = \'\' } else { a.style.display = \'none\' } ; }">+</span></td>',
                'td'    => '<td id="__2__ANKER__%s" ondblclick="javascript: document.location.href=\'#__1__ANKER__%s\';"><pre>' . "\n" . '%s' . str_repeat(' ', $extraIndent) . '</pre></td>',
                'trend' => '<tr style="display: none"><td colspan="2">' . "\n" . str_repeat('-', $extraIndent) . str_repeat(' ', $extraIndent) . '</td></tr>',
            ),
            'plain' => array(
                'table' => '%s' . "\n\n",
                'tr'    => '%s',
                'th'    => '%s() at %s:%u',
                'tdnb'  => "\n\n" . '%s %s %s',
                'td'    => "\n" . '%s %s %s',
                'trend' => "\n" . str_repeat('-', 80),
        ));
    }

    /**
     * Magic string builder.
     * Map method names to templates and args to "vsprintf" args.
     *
     * @param   string  $templateName   - name of template
     * @param   array   $args           - array of strings
     *
     * @return  string
     */
    public function __call($templateName, array $args = array()) {
        return vsprintf($this->templates[$this->format][$templateName], $args);
    }

    /**
     * Returns string representation of all given arguments.
     *
     * @return string
     */
    function dump() {
        $args = func_get_args();
        $bt   = debug_backtrace();

        if (count($args) < 1) {
            $args['version']     = PHP_VERSION;
            $args['environment'] = $_SERVER;
            $args['interfaces']  = get_declared_interfaces();
            $args['classes']     = get_declared_classes();
            //$args['constants']        = get_defined_constants(true);
            //$args['functions']        = get_defined_functions();
            //$args['variables']        = get_defined_vars();
            //$args['backtrace']        = $bt;
        }

        $inner = '';
        foreach ($args as $index => $arg) {
            $str = '';
            if (true === $arg) {
                $str = 'true';
            } else if (false === $arg) {
                $str = 'false';
            } else {
                $str = print_r($arg, true);
            }

            if (true) { //$escape) {
                $index = htmlspecialchars($index, ENT_QUOTES, 'UTF-8');
                $str   = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
            }

            $methods = '';
            if (is_object($arg)) {
                $methods = '<pre>' . print_r(get_class_methods($arg), true) . '</pre>';
            }

            $id    = '';
            if ($this->format != 'plain') {
                $id = rawurlencode($index) . str_replace(array('.', ' '), array('_', '_'), microtime());
            }

            $inner .= $this->tr($this->tdnb($id, $index . ' => ' . gettype($arg) . $methods, $id) . $this->td($id, $id, $str));
        }

        if (@$bt[3]['file'] == __FILE__) {
            $bt = $bt[3];
        } else {
            $bt = $bt[2];
        }

        return $this->table($this->tr($this->th($bt['function'], $bt['file'], $bt['line'])) . $inner . $this->trend());
    }

    /**
     * Return trace array.
     *
     * @return  array
     */
    public function trace() {
        $return = array();
        $trace = debug_backtrace();

        unset($trace[0]);
        unset($trace[1]);
        unset($trace[2]);

        $count = count($trace) - 1;

        foreach ($trace as $a => $b) {
            $return['#' . ($count - ($a - 3)) . ': ' . $b['function']] = $b['file'] . ':' . $b['line'];
        }

        return $return;
    }

    /**
     * Compare two variables linewise.
     *
     * @param   mixed   $a  - some var 1
     * @param   mixed   $b  - some var 2
     *
     * @return  string
     */
    public function compare($a = null, $b = null) {
        $a = explode("\n", print_r($a, true));
        $b = explode("\n", print_r($b, true));

        $diff1 = array_diff($a, $b);
        if (!empty($diff1)) {
            return $diff1;
        }

        $diff2 = array_diff($b, $a);
        if (!empty($diff2)) {
            return $diff2;
        }

        return 'no differences found!';
    }

}
