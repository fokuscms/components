<?php

	namespace fokuscms\Components\Foundation;
	
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Matcher\UrlMatcher;
	use Symfony\Component\Routing\Exception\ResourceNotFoundException;
	use Symfony\Component\HttpKernel\Controller\ControllerResolver;
	
	class Application {
		
		protected $matcher;
		protected $resolver;
		
	    public function __construct (UrlMatcher $matcher,
	    		ControllerResolver $resolver){
	    	$this->matcher = $matcher;
    	    $this->resolver = $resolver;
	    }
	    
	    public function handle(Request $request){
	    	try {
	    		$request->attributes->add($this->matcher->match($request->getPathInfo()));
	    
	    		$controller = $this->resolver->getController($request);
	    		$arguments = $this->resolver->getArguments($request, $controller);
	    
	    		$response = call_user_func_array($controller, $arguments);
	    	} catch (ResourceNotFoundException $e) {
	    		return new Response('Not Found <br>'.$e, 404);
	    	} catch (\Exception $e) {
	    		return new Response('An error occurred'.$e, 500);
	    	}
	    	
	    	return $response;
	    }
	     
		
	}