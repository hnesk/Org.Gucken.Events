<?
/*
	Creado por Cesar Rodas
	saddor@gmail.com
	Puedes utilizar el codigo para lo que quiera, siempre y cuando avise de cualquier mejora a saddor@gmail.com
*/
class SaddorLibTextCat
{
	var $lang;
	var $ranking;
	function SaddorLibTextCat($folder = ".")
	{
		$dir = dir($folder);
		while ( ($entry = $dir->read()) != FALSE)
			if (substr($entry,-3,3) == ".lm")
				$this->LoadOnMemory($folder."/".$entry);
	}
	
	function LoadOnMemory($text)
	{
				$f = fopen($text,"r");
			$content = fread($f, filesize($text) );
		fclose($f);
		$w = explode("\n",str_replace("_","",$content) );
		$name = explode("/",$text);
		$this->lang[str_replace(".lm","",$name[count($name)-1])] = $w;
		$this->ranking[str_replace(".lm","",$name[count($name)-1])] = 0;
		unset($w,$content,$name);
	}
	
	function WhatLang($text)
	{
		$text = substr(utf8_encode($text),0,1024);
		
		foreach ($this->lang as $Lang => $caracters)
		{
			foreach ($caracters as $car)
			{
				$tmp = $text;
				if (trim($car) != "") { 
					while (($tmp = strstr($tmp,$car)))
					{				
						$this->ranking[$Lang] = $this->ranking[$Lang] + 1; 
						$tmp = substr($tmp,strlen($car));
						//break;
					}
				}
			}
		}
		
		array_multisort($this->ranking,SORT_DESC);
		unset($car,$caracters,$tmp);
	}
	
}
?>