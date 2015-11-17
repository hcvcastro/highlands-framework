<?php

class Core {
    private $version = '0.1';
    private $modulesPath = 'modules/';
    private $routesFile = 'routes.yml';
    private $settingsFile = 'settings.yml';
    private $routes;

    public $settings;
    public $module;

    public $ROUTE_FILE_DOES_NOT_EXIST = 1;
    public $TEMPLATE_FILE_DOES_NOT_EXIST = 2;

    function __construct(){
        // Load settings
        $settingsPath = realpath(dirname(__FILE__) . '/../' . $this->settingsFile);

        if( file_exists($settingsPath) ){
            $this->settings = yaml_parse_file($settingsPath);
        }
        else{
            printf('File %s does not exist', $settingsPath);
            exit();
        }

        // Set timezone
        if(isset($this->settings['timezone']))
        {
            date_default_timezone_set($this->settings['timezone']);
        }
        else {
            date_default_timezone_set('UTC');
        }
    }

    function __destruct(){}

    /**
     * @return string Return the framework core version
     */
    public function version(){
        return $this->version;
    }

    public function log($message){
        if( isset($this->settings['log']) ){
            $log = sprintf("[%s] %s\r\n", date('d/m/Y H:i:s'), $message);
            file_put_contents($this->settings['root'] . '/' .$this->settings['log'], $log, FILE_APPEND);
        }
    }

    public function messsage($message){
        if( isset($this->settings['log']) ){
            $this->log($message);
        }

        printf("[%s] %s\n", date('d/m/Y H:i:s'), $message);
    }

    public function parseRoute($request, $routes, $module=NULL){
        foreach($routes as $route){
            $route['route'] = sprintf('#%s#', $route['route']);
            if( preg_match($route['route'], $request) == 1){
                if( isset($route['module']) ){
                    $routesFile = sprintf('%s/modules/%s/routes.yml', $this->settings['root'], $route['module']);
                    if( file_exists($routesFile) ){
                        $moduleRoutes = yaml_parse_file($routesFile);
                        $filterRequest = preg_filter($route['route'], '', $request, 1);
                        $this->parseRoute($filterRequest, $moduleRoutes, $route['module']);
                        exit();
                    }
                    else{
                        $this->messsage(sprintf('Route file %s does not exist', $this->routesFile));
                        throw new Exception(
                            sprintf('Route file %s does not exist', $this->routesFile),
                            $this->ROUTE_FILE_DOES_NOT_EXIST
                        );
                    }
                }
                elseif( isset($route['controller']) && $module ){
                    $this->module = $module;

                    $controller = sprintf('%s/modules/%s/controller.php', $this->settings['root'], $module);
                    include $controller;
                    $class = ucfirst($module);
                    $object = new $class();
                    $object->$route['controller']();
                    exit();
                }
                elseif( isset($route['template']) && $module ){
                    $this->module = $module;

                    $templateFile = sprintf('%s/modules/%s/templates/%s', $this->settings['root'], $module, $route['template']);
                    if( file_exists($templateFile) ){
                        $template = new Template();
                        echo $template->render($templateFile);
                        exit();
                    }
                    else{
                        $this->messsage(sprintf('Template file %s does not exist', $templateFile));
                        throw new Exception(
                            sprintf('Template file %s does not exist', $template),
                            $this->TEMPLATE_FILE_DOES_NOT_EXIST
                        );
                    }

                }
            }
        }

        $this->messsage('Route does not exist');
    }

    public function run(){
        if( file_exists($this->settings['root'] . $this->routesFile) ){
            $this->routes = yaml_parse_file($this->settings['root'] . $this->routesFile);
        }
        else{
            printf('[Error] File %s does not exist', $this->settings['root'] . $this->routesFile);
            exit();
        }

        if( isset($this->settings['timezone']) ){
            date_default_timezone_set($this->settings['timezone']);
        }

        $this->DB();

        if(!defined('TEST')){
            $request = isset($_GET['route']) ? $_GET['route'] : '';
            $this->parseRoute($request, $this->routes);
        }

        return 0;
    }

    public function DB(){
        if($this->settings['db']['dbm'] == 'mysql') {}
    }
}