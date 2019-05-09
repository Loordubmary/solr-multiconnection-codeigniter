<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Loader extends CI_Loader {
    public function view($template, $data = array(), $return = FALSE) {
        $CI =& get_instance();

        try {
            $output = $CI->twig->render($template, $data);
        } catch (Exception $e) {
            show_error(htmlspecialchars_decode($e->getMessage()), 500, 'Twig Exception');
        }

        // Return the output if the return value is TRUE.
        if ($return === TRUE) {
            return $output;
        }

        // Otherwise append to output just like a view.
        $CI->output->append_output($output);
    }
}

?>