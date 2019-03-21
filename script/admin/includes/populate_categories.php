<?php
function fetchCategoriesList($website) 
{
	if($website=="themeforest") 
	{
		$categoriesPermalinks = array("1"=>"wordpress","2"=>"site-templates","3"=>"marketing","4"=>"cms-themes","5"=>"ecommerce","6"=>"psd-templates","7"=>"blogging","8"=>"muse-templates","9"=>"forums","10"=>"typeengine-themes");
		$categoriesNames = array("1"=>"Wordpress","2"=>"Web Templates","3"=>"Marketing","4"=>"CMS","5"=>"eCommerce","6"=>"PSD","7"=>"Blogging","8"=>"Muse Templates","9"=>"Forums","10"=>"Type Engine");
	} 
	else if($website=="codecanyon") 
	{
		$categoriesPermalinks = array("1"=>"php-scripts","2"=>"wordpress","3"=>"javascript","4"=>"css","5"=>"mobile","6"=>"html5","7"=>"skins","8"=>"plugins","9"=>"net","10"=>"apps","11"=>"edge-animate-templates");
		$categoriesNames = array("1"=>"PHP Scripts","2"=>"Wordpress","3"=>"Javascript","4"=>"CSS","5"=>"Mobile","6"=>"HTML5","7"=>"Skins","8"=>"Plugins","9"=>".NET","10"=>"Apps","11"=>"Edge Animate Templates");
	} 
	else if($website=="videohive") 
	{
		$categoriesPermalinks = array("1"=>"after-effects-project-files","2"=>"apple-motion-templates","3"=>"motion-graphics","4"=>"stock-footage","5"=>"cinema-4d-templates","6"=>"add-ons");
		$categoriesNames = array("1"=>"After Effects Templates","2"=>"Apple Motion Templates","3"=>"Motion Graphics","4"=>"Stock Footage","5"=>"Cinema 4D Templates","6"=>"After Effects Add Ons");
	} 
	else if($website=="audiojungle") 
	{
		$categoriesPermalinks = array("1"=>"music","2"=>"music-packs","3"=>"sound","4"=>"source-files","5"=>"logos-idents");
		$categoriesNames = array("1"=>"Music","2"=>"Music Packs","3"=>"Sound Effects","4"=>"Source Files","5"=>"Intro Music");
	} 
	else if($website=="graphicriver") 
	{
		$categoriesPermalinks = array("1"=>"graphics","2"=>"print-templates","3"=>"web-elements","4"=>"add-ons","5"=>"vectors","6"=>"presentation-templates","7"=>"infographics","8"=>"icons","9"=>"fonts","10"=>"logo-templates","11"=>"edge-animate-templates","12"=>"isolated-objects","13"=>"t-shirts","14"=>"textures");
		$categoriesNames = array("1"=>"Graphics","2"=>"Print Templates","3"=>"Web Elements","4"=>"Add-ons","5"=>"Vector","6"=>"Presentation Templates","7"=>"Infographic Design","8"=>"Icons","9"=>"Fonts","10"=>"Logo Templates","11"=>"Edge Animate Templates","12"=>"Isolated Objects","13"=>"T-Shirt Design","14"=>"Texture");
	} 
	else if($website=="3docean") 
	{
		$categoriesPermalinks = array("1"=>"3d-models","2"=>"cg-textures","3"=>"materials-and-shaders","4"=>"scripts-and-plugins","5"=>"2d-concepts","6"=>"animation-data","7"=>"render-setups");
		$categoriesNames = array("1"=>"3D Models","2"=>"Textures","3"=>"Materials & Shaders","4"=>"3D Plugins","5"=>"2D Shapes","6"=>"Animation Data","7"=>"Render Setup");
	} 
	else if($website=="activeden") 
	{
		$categoriesPermalinks = array("1"=>"flash","2"=>"flex","3"=>"unity-3d","4"=>"jsfl-extensions");
		$categoriesNames = array("1"=>"Flash Templates","2"=>"Flex Components","3"=>"Unity 3D","4"=>"JSFL Extensions");
	}
	$count = count($categoriesPermalinks);
	echo '<select class="form-control" name="categories">';
	for($i=1;$i<=$count;$i++) 
	{
		echo '<option value="'.$categoriesPermalinks[$i].'">'.$categoriesNames[$i].'</option>\n'; 
	}
	echo '</select>';
}
if(isset($_POST['website'])) 
{
	fetchCategoriesList($_POST['website']);
}
?>