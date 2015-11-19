<?php

/*
   Author: isjing
   Author URI: ?
   License: MIT License (MIT)
*/

/*
   Copyright isjing (email: ?)
   Permission is hereby granted, free of charge, to any person obtaining a copy
   of this software and associated documentation files (the "Software"), to deal
   in the Software without restriction, including without limitation the rights
   to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
   copies of the Software, and to permit persons to whom the Software is
   furnished to do so, subject to the following conditions:
   The above copyright notice and this permission notice shall be included in
   all copies or substantial portions of the Software.
   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
   IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
   AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
   LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
   OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
   THE SOFTWARE.
*/



class Manage {
    private $settingsFile = 'settings.yml';
    private $settings;

    public function run(){
        global $argc, $argv;

        $path = sprintf('%s/%s', dirname(__FILE__), $this->settingsFile);

        if( file_exists($path) ){
            $this->settings = yaml_parse_file($path);
        }
        else{
            printf('File %s does not exist', $path);
            exit();
        }

        $cmd = $argv[1];

        if( $cmd == 'project'){
        }
        elseif( $cmd == 'module' ){
            $module = $argv[2];
            $moduleDir = sprintf('%s/modules/%s', $this->settings['root'], $module);
            $moduleTemplatesDir = sprintf('%s/modules/%s/templates', $this->settings['root'], $module);
            $moduleControllerFile = sprintf('%s/modules/%s/controller.php', $this->settings['root'], $module);
            $moduleRoutesFile = sprintf('%s/modules/%s/routes.yml', $this->settings['root'], $module);
            $moduleModelsFile = sprintf('%s/modules/%s/models.yml', $this->settings['root'], $module);

            $controlllerContent = "<?php\n\nclass [[module]] {\n\tpublic function index(){\n\t\techo 'index :)';\n\t}\n}";
            $controlllerContent = str_replace('[[module]]', ucfirst($module), $controlllerContent);
            $routesContent = "- route: ^\$\n  controller: index";
            $modelsContent = '';

            if( !file_exists($moduleDir) ){
                mkdir($moduleDir);
                mkdir($moduleTemplatesDir);
                file_put_contents($moduleControllerFile, $controlllerContent);
                file_put_contents($moduleRoutesFile, $routesContent);
                file_put_contents($moduleModelsFile, $modelsContent);
            }
            else{
                echo "Module directory already exists.\n";
            }
        }
        return 0;
    }
}

$manage = new Manage();
$manage->run();
