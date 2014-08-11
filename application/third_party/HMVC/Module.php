<?php

include_once 'Loader' . EXT;

class Module extends HMVC_Loader
{
    protected $name;
    protected $path;

    public function __construct($name, $path)
    {
        parent::__construct();
        $this->name = $name;
        $this->path = $path;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function controller($name, $params = array(), $return = FALSE)
    {
        $router = & $this->_ci_get_component('router');
        $backup = array();

        foreach (array('directory', 'class', 'method', 'module') as $prop) {
            $backup[$prop] = $router->{$prop};
        }

        $uri = $this->getName().'/'.$name;

        $segments = $router->locate(explode('/', $uri));

        $class = isset($segments[0]) ? $segments[0] : FALSE;
        $classKey = strtolower($this->getName().'->'.$class);
        $method = isset($segments[1]) ? $segments[1] : "index";

        if (!$class) {
            throw new \RuntimeException("missed class name");
        }

        if (!array_key_exists($classKey, $this->_ci_controllers)) {

            $filepath = $this->getPath() . 'controllers' . DIRECTORY_SEPARATOR . $class . EXT;

            if (!file_exists($filepath)) {
                throw new \RuntimeException("could not find controller $class in module {$this->getName()}");
            }

            include_once ($filepath);
            $this->_ci_controllers[$classKey] = new $class();
        }

        return $this->_ci_controllers[$classKey];
    }
}