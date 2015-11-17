<?php

class Template {
    private $smarty;

    function __construct(){
        $rootDir = $GLOBALS['highlands']->settings['root'];
        $module = $GLOBALS['highlands']->module;

        $templatesDir = sprintf('%s/modules/%s/templates/', $rootDir, $module);

        $this->smarty = new Smarty();
        $this->smarty->compile_dir = $rootDir . 'tmp/templates_c/';
        $this->smarty->cache_dir = $rootDir . 'tmp/cache/';
        $this->smarty->setTemplateDir($templatesDir);
    }

    public function render($template, $context=null){
        if($context){
            $this->smarty->assign($context);
        }

        return $this->smarty->fetch($template);
    }
}