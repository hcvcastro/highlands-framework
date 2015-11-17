<?php

class Controller {
    public function render($templateFile, $context = NULL, $options = NULL){
        $template = new Template();
        echo $template->render($templateFile, $context);
    }
}