<?php

class Twig {
    private $CI;

    private $_config = array();
    private $_twig;
    private $_twig_loader;

    public function __construct(){
        log_message('debug', 'Twig: library initialized');

        $this->CI =& get_instance();

        $this->_config = $this->CI->config->item('twig');

        try {
            $this->_twig_loader = new Twig_Loader_Filesystem($this->_config['template_dir']);
        } catch (Exception $e) {
            show_error(htmlspecialchars_decode($e->getMessage()), 500, 'Twig Exception');
        }

        if($this->_config['environment']['cache'] === true){
            $this->_config['environment']['cache'] = APPPATH.'cache/twig';
        }

        $this->_twig = new Twig_Environment($this->_twig_loader, $this->_config['environment']);
    }

    public function render($template, $data = array()){
        $template = $this->addExtension($template);
        return $this->_twig->render($template, $data);
    }

    public function display($template, $data = array()){
        $this->_twig->display($template, $data);
    }

    private function addExtension($template){
        $ext = '.'.$this->_config['template_ext'];

        if(substr($template, -strlen($ext)) !== $ext){
            return $template .= $ext;
        }

        return $template;
    }
}

?>  