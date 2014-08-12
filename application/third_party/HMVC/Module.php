<?php

include_once 'Loader'. EXT;

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

    public function controller($name, $return = true)
    {
        $router = & $this->_ci_get_component('router');
        $backup = array();

        foreach (array('directory', 'class', 'method', 'module') as $prop) {
            $backup[$prop] = $router->{$prop};
        }

        $uri = $this->getName() . '/' . $name;

        $segments = $router->locate(explode('/', $uri));

        $class = isset($segments[0]) ? $segments[0] : FALSE;
        $method = isset($segments[1]) ? $segments[1] : "index";

        if (!$class) {
            throw new \RuntimeException("missed class name");
        }

        if (!array_key_exists($name, $this->_ci_controllers)) {

            $filepath = $this->getPath() . 'controllers' . DIRECTORY_SEPARATOR . $class . EXT;

            if (!file_exists($filepath)) {
                throw new \RuntimeException("could not find controller $class in module {$this->getName()}");
            }

            include_once ($filepath);

            $this->getCI()->$name = new $class();
            $this->_ci_controllers[] = $name;
        }

        // Restore router state
        foreach ($backup as $prop => $value) {
            $router->{$prop} = $value;
        }

        if ($return === true) {
            return $this->getCI()->$name;
        } else {
            return;
        }
    }

    public function getModel($model, $name = '', $db_conn = FALSE)
    {
        if (is_array($model)) {
            throw new \InvalidArgumentException('invalid value fro model');
        }

        $this->loadModel($model, $name, $db_conn);

        return $this->getCI()->{!empty($name) ? $name : $model};
    }

    public function loadModel($model, $name = '', $db_conn = FALSE)
    {
        if (is_array($model)) {
            foreach ($model as $babe) {
                parent::model($this->getName() . '/' . $babe, $name, $db_conn);
            }

            return;
        }

        return parent::model($this->getName() . '/' . $model, $name, $db_conn);
    }

    /**
     * Loads a language file
     *
     * @param	array
     * @param	string
     * @return void
     */
    public function language($file = array(), $lang = '')
    {
        if (is_array($file)) {
            foreach ($file as $langfile) {
                $this->language($this->getName() . '/' . $langfile, $lang);
            }

            return;
        }

        return parent::language($this->getName() . '/' . $langfile, $lang);
    }

    /**
     * Load Helper
     *
     * This function loads the specified helper file.
     *
     * @param	mixed
     * @return void
     */
    public function helper($helper = array())
    {
        if (is_array($helper)) {
            foreach ($helper as $help) {
                $this->helper($this->getName() . '/' . $help);
            }

            return;
        }

        return parent::helper($this->getName() . '/' . $help);
    }

    /**
     * Load View
     *
     * This function is used to load a "view" file.  It has three parameters:
     *
     * 1. The name of the "view" file to be included.
     * 2. An associative array of data to be extracted for use in the view.
     * 3. TRUE/FALSE - whether to return the data or load it.  In
     * some cases it's advantageous to be able to return data so that
     * a developer can process it in some way.
     *
     * @param	string
     * @param	array
     * @param	bool
     * @return void
     */
    public function view($view, $vars = array(), $return = FALSE)
    {
        return parent::view($this->getName() . '/' . $view, $vars, $return);
    }

    /**
     * Loads a config file
     *
     * @param	string
     * @param	bool
     * @param 	bool
     * @return void
     */
    public function config($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        return parent::config($this->getName() . '/' . $file, $use_sections, $fail_gracefully);
    }
}
