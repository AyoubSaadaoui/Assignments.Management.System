<?php

/**
 * main controller class
 */
class Controller {

    function controller_name() {
        
		return get_class($this);
	}

    function view($view, $data = array()) {

        extract($data);
        if(file_exists("../private/views/" . $view . ".view.php")) {

            require ("../private/views/" . $view . ".view.php");
        }
        else {

            require ("../private/views/404.view.php");
        }
    }

    function load_model($model) {

            if(file_exists("../private/models/" . ucfirst($model) . ".php")) {

                require("../private/models/" . ucfirst($model) . ".php");
                return $model = new $model;
            }

            return false;
    }

    function redirect($link) {

        header("Location:" .ROOT."/".trim($link, "/"));
        die;
    }
}