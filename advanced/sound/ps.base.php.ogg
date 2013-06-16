<?php

class PsBase
{
	const mask="/(?|(@)(\\{)?([a-z_][a-z_0-9]*)(\\})?(?:\\.([a-z_][a-z_0-9]*))?(:([htf]+)?)?@|[^@]+|@)/i";
	const dog=1;
	const colon=6;
	const flags=7;
	const bracket=2;
	const name=3;
	const method=5;
	
	protected static $baseDirectory='';	///< directory prefix for included by substitute files
	
	protected $template;
	
	public function __construct()
	{
		
	}
	
	public static function setDirectory($dir)
	{
		self::$baseDirectory=$dir;
	}
	
	
	public function getTemplate()
	{
		return $this->template;
	}
	
	public function __toString()
	{
		return $this->templateOut($this->getTemplate());
	}
	
	public function templateOut($template)
	{
		ob_start();
		$this->template($template);
		return ob_get_clean();
	}
	
	public function render()
	{
		$this->template($this->getTemplate());
	}
	
	public function getRender()
	{
		ob_start();
		$this->render();
		return ob_get_clean();
	}
	
	/**
	 * Template engine base
	 * <pre>
	 * template format:
	 * @@propertyName@@ echo property of current object
	 * @@methodName@@ call method of current object
	 * @@propertyName.method@@ call method of this property  
	 * @@{className}@@ create className object and call render method
	 * @@{className}.method@@ create className object and call method
	 * @@something:@@ call something and filter result throw template()
	 * @@something:h@@ call something and filter using htmlspecialchars() 
	 * </pre> 
	 *  
	 * @param string $content
	 */
	
	public function template($content,$args=array())
	{
		// Break content to pieces
		preg_match_all(self::mask,$content,$res,PREG_SET_ORDER);
		// for each piece
		foreach($res as $r)
		{
			do
			{
				// check for plain text
				if(!isset($r[self::dog]))
					break;

				// get property name
				$name=$r[self::name];
				// calculate method name
				$method=isset($r[self::method]) ? $r[self::method] : 'render';
				
				// classname used?
				if(!empty($r[self::bracket]))
				{
					if(!class_exists($name))
							break;
					// create object of this class
					$o=new $name($this);
				}else
				{
					if(method_exists($this,$name))
					{
						$o=$this;
						$method=$name;
					}else if(isset($this->$name))
					{
						$o=$this->$name;
					}
					else if(isset($this::$name))
					{
						$o=$this::$name;
					}
					else if(isset($args[$name]))
						$o=$args[$name];
					else if(null===($o=$this->findName($name,$method)))
						break;
					// get this property
				}

				if(isset($r[self::colon]))
					ob_start();	
				
				if(method_exists($o,$method))
					$o->$method();
				else
					echo $o;
					
				// check for output procesing	
				if(isset($r[self::colon]))
				{
					$out=ob_get_clean();
					if(isset($r[self::flags]))
					{
						// apply filter for every flag
						for($i=0,$l=strlen($r[self::flags]);$i<$l;++$i)
						{
							switch($r[self::flags][$i])
							{
								case 'h':
									$out=htmlspecialchars($out);break;
								case 't':
									$out=$this->templateOut($out);break;
								case 'f':
									$out=file_get_contents(self::$baseDirectory.$out);break;
								default:
									break 3;
							}
						}
						echo $out;
					}
					else 
						$this->template($out);
				}	
					
				continue 2;
					
			}while(false);
			
			echo $r[0];
		}
	}
	
	protected function findName(&$name,&$method)
	{
		return null;
	}
	
	public function getUniqueId()
	{
		return get_class($this);
	}
	public function uniqueId()
	{
		echo $this->getUniqueId();
	}
	    
} // end of class