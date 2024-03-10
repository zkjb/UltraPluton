<?php

declare(strict_types=1);

namespace Ultrapluton\Router;


use Ultrapluton\Router\RouterInterface;

class Router implements RouterInterface
{
    /**
     * returns an array of route from our routing table
     * @var array
     */
    protected  array $routes = [];

    /**
     * returns an array of route parameters
     * @var array
     */
    protected  array $params = [];

    /**
     * Adds a suffix on to the controller name
     * @var string
     */
    protected string $controllerSuffix = 'controller';


    #[\Override] public function add(string $route, array $params = []): void
    {
        // TODO: Implement add() method.
        $this->routes[$route] = $params;
    }

    /**
     * @throws \Exception
     */
    #[\Override] public function dispatch(string $url): void
    {
        // TODO: Implement dispatch() method.
        if ($this->match($url)){
            $controllerString = $this->params['controller'];
            $controllerString = $this->transfromUpperCamelCase($controllerString);
            $controllerString = $this->getNamespace($controllerString);

            if (class_exists($controllerString)){
                $controllerObject = new $controllerString;
                $action = $this->params['action'];
                $action = $this->transfromCamelCase($action);

                if (\is_callable([$controllerObject,$action])){
                    $controllerObject->$action();
                } else{
                    throw new \Exception();
                }
            } else{
                throw new \Exception();
            }
        } else{
            throw new \Exception();
        }
    }


    public function transfromUpperCamelCase(string $string):string
    {
        return str_replace(' ','',ucwords(str_replace('-',' ',$string)));
    }

    public function transfromCamelCase(string $string):string
    {
        return \lcfirst($this->transfromUpperCamelCase($string));
    }

    /**
     * Match the route to the routes in the routing table, setting the $this->params property
     *if a route is found
     *
     * @param string $url
     * @return bool
     */
    private function match(string $url) : bool
    {
        foreach ($this->routes as $route => $params){
            if (preg_match($route,$url,$matches)){
               foreach ($matches as $key => $param){
                   if (is_string($key)){
                       $params[$key] = $param;
                   }
               }
               $this->params = $params;
               return true;
            }
        }
        return false;
    }

    /**
     * Get the namespace for the controller class. the namespace defined within the route parameter
     * only if it was added
     *
     * @param string $string
     * @return string
     */
    public function getNamespace(string $string):string
    {
        $namespace = 'App\Controller\\';
        if(array_key_exists('namespace',$this->params)){
            $namespace.= $this->params['namespace'].'\\';
        }
        return $namespace;
    }
}