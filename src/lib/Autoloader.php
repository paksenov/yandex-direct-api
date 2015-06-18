<?php

namespace YDAPI\lib;

/*require_once("/srv/http/oldadmin/lib/yandex_direct_api/lib/Registry.php");
require_once("/srv/http/oldadmin/lib/yandex_direct_api/lib/Response.php");
require_once("/srv/http/oldadmin/lib/yandex_direct_api/lib/Request.php");
*/

class Autoloader
{

private $dirs;
    
public function __construct($dirs)
    {
        $this->dirs = is_array($dirs) ? $dirs : array($dirs);
        $autoloader_init_params = array($this, 'loadClass');
        
        spl_autoload_register($autoloader_init_params);
    }

private function loadClass($class_name)
    {
        foreach($this->dirs as $dir_name)
            foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir_name)) as $class_file)
                if($class_file->isFile())
                    {
                        $current_file   = strtolower($class_file->getBasename('.php'));
                        $search_file    = strtolower($class_name);
                        $file_extension = end(explode('.', end(explode('/', $class_file->getPathName()))));

                        if($current_file == $search_file && $file_extension == 'php')
                            {
                                require_once($class_file->getPathName());
                                break;
                            }
                    }
    }

}

?>
