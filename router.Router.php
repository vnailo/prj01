<?php

class Router
{
  private $request;
  private $supportedHttpMethods = array(
    "GET",
    "POST"
  );

  function __construct(IRequest $request)
  {
   $this->request = $request;
  }

  function __call($name, $args)
  {
    list($route, $method) = $args;

    if(!in_array(strtoupper($name), $this->supportedHttpMethods))
    {
      $this->invalidMethodHandler();
    }

    $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
  }

  /**
   * Removes trailing forward slashes from the right of the route.
   * @param route (string)
   */
  private function formatRoute($route)
  { 
    $pattern = array ( 
        '?'.$_SERVER['QUERY_STRING'],
        dirname($_SERVER['SCRIPT_NAME'])
    );

    $route = str_replace( $pattern ,'',$route);
    $result = rtrim($route, '/');

    if ($result === '')
    {
      return '/';
    }
    return $result;
  }

  private function invalidMethodHandler()
  {
    header("{$this->request->serverProtocol} 405 Method Not Allowed");
  }

  private function defaultRequestHandler()
  {
  //  ob_clean() ; 
    header("{$this->request->serverProtocol} 404 Not Found");
  return <<<HTMLBODY
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL {$this->request->requestUri} was not found on this server.</p>
</body></html>
HTMLBODY;
  }

  /**
   * Resolves a route
   */
  function resolve()
  {
    $methodDictionary = $this->{strtolower($this->request->requestMethod)};
    $formatedRoute = $this->formatRoute($this->request->requestUri);
    
    if(!isset($methodDictionary[$formatedRoute]))
    {
      echo $this->defaultRequestHandler();
      return;
    }

    $method = $methodDictionary[$formatedRoute];

    echo call_user_func_array($method, array($this->request));
  }

  function __destruct()
  {
    $this->resolve();
  }
}