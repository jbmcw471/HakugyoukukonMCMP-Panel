<?php
namespace HakugyokuSoulMVC;

use HakugyokuSoulMVC\base\Controller;

class HakugyokuSoulMVC {
	protected $config;
	public static $timeStart;

	function __construct($config) {
		$this->config = parse_ini_file($config,true);
	}
	
	public function run(){
	    HakugyokuSoulMVC::$timeStart = microtime(true);
		$this->isDebug();
		$this->getConf();
		$this->route();
	}
	
	
	private function route(){
        $urlParam = @$_SERVER['REQUEST_URI'];

        if ($urlParam == null){
            $urlParam = @$_SERVER['HTTP_X_ORIGINAL_URL'];
        }

		//del /
        $urlParam = trim($urlParam, '/');

        //Go to the Default Controller
        if ($urlParam == null ){
            $this->runController(MVC_CONTROLLER,MVC_ACTION,array());
            return;
        }

        //get an array
        $urlParam = explode("/",$urlParam);

        //Get the controller
        $controller = $urlParam[0];

        //Get the Action
        $action = $urlParam[1];

        array_shift($urlParam);
        array_shift($urlParam);

        $this->runController($controller,$action,$urlParam);

        //print_r($urlParam);
	}


    private function runController($controllerName,$actionName,$urlParam){
        //echo "$controllerName<br>$actionName<br>";
        //print_r($urlParam);

        //add a "Controller"
        $controllerName = $controllerName."Controller";

        //file V
        if (is_file(APP_PATH."application/Controllers/$controllerName.php") == false){
            exit("Can not find the controller file: ".APP_PATH."application/Controllers/$controllerName.php");
        }

        //Load
        $this->loadClass($controllerName);

        //Class V
        if (class_exists("\\application\\Controllers\\$controllerName") == false){
            exit("Can not find the controller class: "."\\application\\Controllers\\$controllerName");
        }

        //Action V
        if (method_exists("\\application\\Controllers\\$controllerName",$actionName) == false){
            exit("Can not find the Action: $actionName form Controller"."\\application\\Controllers\\$controllerName");
        }

        $class = "\\application\\Controllers\\$controllerName";

        (new $class($controllerName,$actionName,$urlParam))->$actionName();
	}
	
	
	// 检测开发环境
    private function isDebug(){
        if (APP_DEBUG === true) {
            error_reporting(E_ALL);
            ini_set('display_errors','On');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors','Off');
            ini_set('log_errors', 'On');
        }
    }

    private function getConf(){
		define("DB_HOST",$this->config['Mysql']['Host']);
		define("DB_NAME",$this->config['Mysql']['Dbname']);
		define("DB_USER",$this->config['Mysql']['Username']);
		define("DB_PASS",$this->config['Mysql']['Password']);
		define("DB_PORT",$this->config['Mysql']['Port']);
		
		define("WEB_TITLE",$this->config["Web"]['Title']);
		
		define("MVC_CONTROLLER",$this->config["Web"]['DefaultController']);
		define("MVC_ACTION",$this->config["Web"]['DefaultAction']);

		define("APP_KEY",$this->config["Web"]['APP_Key']);
		define("APP_VER",$this->config["Web"]['Version']);
	}

    private function loadClass($controller){
        $controller = str_replace("Controller","",$controller);

	    include_once APP_PATH."HakugyokuSoulMVC/base/Controller.php";
        include_once APP_PATH."HakugyokuSoulMVC/base/Model.php";
        include_once APP_PATH."HakugyokuSoulMVC/base/View.php";
        include_once APP_PATH."HakugyokuSoulMVC/db/HakugyokuSoulSql.php";
        include_once APP_PATH."application/Models/$controller"."Model.php";
        include_once APP_PATH."application/Controllers/$controller"."Controller.php";
        include_once APP_PATH."HakugyokuSoulMVC/base/Model.php";
        include_once APP_PATH."HakugyokuSoulMVC/tools/BootStrap.php";
	}
}