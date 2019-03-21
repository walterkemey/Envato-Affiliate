<?php 
class App {
	protected $config=array(), $action="", $do="", $id="", $sandbox = FALSE;
	protected $actions = array("home","search","top","tags","category","product","page","contact","rss");
	public function run()
	{
	
		if(isset($_GET["a"]) && !empty($_GET["a"]))
		{
			$var=explode("/", $_GET["a"]);
			if(count($var) > 4 && $var[0]!='search') 
			return $this->_404();
			$this->action = $var[0];
			if(isset($var[1]) && !empty($var[1]))
			if(isset($var[2]) && !empty($var[2]))
			$this->id = $var[2];
			if(($var[0]=='tags' && $var[1]=="") || ($var[0]=='page' && $var[1]=="") || ($var[0]=='product' && $var[1]=="") || ($var[0]=='category' && $var[1]=="") || ($var[0]=='search' && ($var[1]=="" || $var[2]=="")) || ($var[0]=='rss' && (($var[1]=="category" && $var[2]=="") || ($var[1]=="tag" && $var[2]=="")))) {
			$this->_404();
			}
			else if($var[0]=='home' || $var[0]=='top' || $var[0]=='category' || $var[0]=='search' || $var[0]=='tags' || $var[0]=='product' || $var[0]=='rss' || $var[0]=='contact' || $var[0]=='page')
			{
			if(in_array($var[0],$this->actions))
			{
				if($var[0]=='search' && !is_numeric($var[3]))
				return $this->releventSearch($var[1],$var[2],$var[3],$var[4]);
				else
				return $this->{$var[0]}($var[1],$var[2],$var[3]);
			}
			}
			else if($var[0]=='index.php' || $var[0]=='index')
			{
				return $this->home($var[1],$var[2],$var[3]);
			} else {
			$this->_404();
			}
			return $this->_404();
		   
		}
		else 
		{
			return $this->home($var[1],$var[2],$var[3]);
		}
	}	
	protected function home($data,$page,$noneed)
	{   
	    if($data !="" && ($data=='id' || $data=='price' || $data=='clicks') && $page=="") {
		$this->sortBy=$data;
		}
		else if($data !="" && ($data=='ASC' || $data=='DESC') && $page=="") {
		$this->sortOrder=$data;
		} else if(is_numeric($data) && $page==""){
		$this->Page=$data;
		} else if($data !="" && ($data=='id' || $data=='price' || $data=='clicks') && $page!="") {
		$this->sortBy=$data;
		$this->Page=$page;
		}
		else if($data !="" && ($data=='ASC' || $data=='DESC') && $page!="") {
		$this->sortOrder=$data;
		$this->Page=$page;
		}
		include(ROOT."/home.php");	
	}
    protected function top($data,$page,$noneed)
	{   
	    if($data !="" && ($data=='today' || $data=='weekly' || $data=='monthly' || $data=='alltime') && $page=="") {
		$this->sortBy=$data;
		}else if(is_numeric($data) && $page==""){
		$this->Page=$data;
		} else if($data !="" && ($data=='today' || $data=='weekly' || $data=='monthly' || $data=='alltime') && $page!="") {
		$this->sortBy=$data;
		$this->Page=$page;
		}
		include(ROOT."/top.php"); 	
	}	
	protected function tags($tagname,$data,$page)
	{   
	    if($data !="" && ($data=='id' || $data=='price' || $data=='clicks') && $page=="") {
		$this->sortBy=$data;
		}
		else if($data !="" && ($data=='ASC' || $data=='DESC') && $page=="") {
		$this->sortOrder=$data;
		} else if(is_numeric($data) && $page==""){
		$this->Page=$data;
		} else if($data !="" && ($data=='id' || $data=='price' || $data=='clicks') && $page!="") {
		$this->sortBy=$data;
		$this->Page=$page;
		}
		else if($data !="" && ($data=='ASC' || $data=='DESC') && $page!="") {
		$this->sortOrder=$data;
		$this->Page=$page;
		}
		$this->tagName=$tagname;
		include(ROOT."/tags.php");
	}
	protected function category($categoryname,$data,$page)
	{   
	    if($data !="" && ($data=='id' || $data=='price' || $data=='clicks') && $page=="") {
		$this->sortBy=$data;
		}
		else if($data !="" && ($data=='ASC' || $data=='DESC') && $page=="") {
		$this->sortOrder=$data;
		} else if(is_numeric($data) && $page==""){
		$this->Page=$data;
		} else if($data !="" && ($data=='id' || $data=='price' || $data=='clicks') && $page!="") {
		$this->sortBy=$data;
		$this->Page=$page;
		}
		else if($data !="" && ($data=='ASC' || $data=='DESC') && $page!="") {
		$this->sortOrder=$data;
		$this->Page=$page;
		}
		$this->categoryName=$categoryname;
		include(ROOT."/category.php");
	}
	protected function releventSearch($category,$search,$data,$page)
	{  
		$this->SearchCategory=$category;
		$this->Search=$search;
		if($data !="" && ($data=='id' || $data=='price' || $data=='clicks' || $data=='relevence') && $page=="") {
		$this->SortBy=$data;
		}
		else if($data !="" && ($data=='ASC' || $data=='DESC') && $page=="") {
		$this->SortOrder=$data;
		} else if($data !="" && ($data=='id' || $data=='price' || $data=='clicks' || $data=='relevence') && $page!="") {
		$this->SortBy=$data;
		$this->Page=$page;
		}
		else if($data !="" && ($data=='ASC' || $data=='DESC') && $page!="") {
		$this->SortOrder=$data;
		$this->Page=$page;
		}
		include(ROOT."/search.php");
	}
	protected function search($category,$search,$page)
	{  
		$this->SearchCategory=$category;
		$this->Search=$search;
		$this->Page=$page;
		include(ROOT."/search.php");
	}
	protected function product($product,$noneed,$noneed)
	{  
		$this->Product=$product;
		include(ROOT."/product.php");
	}
	/* protected function rss($data,$noneed,$noneed)
	{  
		$this->data=$data;
		include(ROOT."/rss.php");
	} */
	protected function page($permalink,$noneed,$noneed)
	{  
		$this->Permalink=$permalink;
		include(ROOT."/page.php");
	}
	protected function rss($type,$data,$search)
	{ 
		if($type=='recent' || $type=='tag' || $type=='category' || $type=='top')
		$this->Type=$type;
		$this->data=$data;
		include(ROOT."/rss.php");
	}
	protected function contact($noneed,$noneed,$noneed)
	{  
		include(ROOT."/contact.php");
	}
	protected function _404()
	{
		include(ROOT."/404.php");		
	}
}