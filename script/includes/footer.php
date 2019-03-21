</div> <!-- End col-md-12 -->
</div> <!-- End main -->
</div> <!-- End container --> 
</div> <!-- End wrap -->

    <div id="footer" class="visible-xs footer-xs">
		<div class="container">
			<center>
			  <p class="text-muted">
				<a  href="<?php echo (rootpath()); ?>/contact"><?php echo $lang_array['contact_us'];?></a>
			  <?php echo (listPages()); ?>
			  </p>
			  <span><b>&copy; <?php echo(date("Y")) ?>  - </b> 
			  <b><?php echo $lang_array['powered_by'];?> </b><a href="http://www.nexthon.com" target="_blank"><b><?php echo $lang_array['developer'];?>.</b></a></span>
			</center>
		</div>
    </div>
	<div id="footer" class="hidden-xs">
		<div class="container">
			<p>
			<span class="text-muted"><a href="<?php echo (rootpath()); ?>/contact"><?php echo $lang_array['contact_us'];?></a>
			  <?php echo (listPages()); ?></span>
			  <span class="pull-right"><b>&copy; <?php echo(date("Y")) ?> - </b> 
			  <b><?php echo $lang_array['powered_by'];?> </b><a href="http://www.nexthon.com" target="_blank"><b><?php echo $lang_array['developer'];?>.</b></a></span>
			</p>
		</div>
    </div>
		<!-- JavaScript -->
	<script type="application/javascript" src="<?php echo rootpath()?>/style/js/video.js"></script>
	<script src="<?php echo(rootpath()) ?>/audio/audio.js" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
		AudioJS.setupAllWhenReady();
	</script>
	<script type="text/javascript" src="<?php echo(rootpath()) ?>/style/js/selecttransform.js"></script>
	<script type="text/javascript" src="<?php echo(rootpath()) ?>/style/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo rootpath()?>/style/js/jquery.easing-sooper.js"></script>
	<script type="text/javascript" src="<?php echo rootpath()?>/style/js/jquery.sooperfish.min.js"></script>
	<script type="text/javascript" src="<?php echo(rootpath()) ?>/style/js/adjust_thumbs.js"></script>
	<script type="text/javascript" src="<?php echo(rootpath()) ?>/style/js/typeahead.js"></script>
	<script type="text/javascript">
		
		$(window).resize(function() 
		{
			var width = $(window).width();
			if(width > 1199)
			{
				var element = document.getElementById('sf-menu');	
				element.style.display = 'block'; 
			}
		});
		function s_h(id) 
		{ 
			var element = document.getElementById(id);			
			if (element.style.display == 'block') 
			{ 
				element.style.display = 'none'; 
			} 
			else 
			{
				element.style.display = 'block'; 
			} 
		};
		
		$(document).ready(function() {
		  $('ul.sf-menu').sooperfish();
		});
	
		$(function(){
			$('form').jqTransform();
		});
		
		$('.search_box').keypress(function (e) 
		{
			if (e.which == 13) 
			{  
				var parentId =this.id;
				if(parentId=='mobileSearch') {
				var selectCategory=$('#selectmobileCategory').val();
				var search=$(this).val();
				} else if(parentId=='systemSearch') {
				var selectCategory=$('#selectsystemCategory').val();
				var search=$(this).val();
				}
				search=search.trim();
				if(search !="") 
				{
					search=search.replace(/[^a-z0-9]/gi, "-");
					search=search.toLowerCase();
					var intIndexOfMatch = search.indexOf("--");	
					while (intIndexOfMatch != -1)
					{	
						search = search.replace( "--", "-" )		
						intIndexOfMatch = search.indexOf( "--" );	
					}
					window.location="<?php echo rootpath()?>/search/"+selectCategory+"/"+search;
				}
				return false;  
			}
		});
		$(".clickMe").click(function()
		{
			var getid=this.id;
			if(getid=='systemFeild') 
			{	
				var selectCategory=$('#selectsystemCategory').val();
				var search=$('.systemFeild').val();
				search=search.trim();
			}
			else 
			{
				var selectCategory=$('#selectmobileCategory').val();
				var search=$('.mobileFeild').val();
				search=search.trim();
			}
			if(search !="") 
			{
				search=search.replace(/[^a-z0-9]/gi, "-");
				search=search.toLowerCase();
				var intIndexOfMatch = search.indexOf("--");	
				while (intIndexOfMatch != -1)
				{	
					search = search.replace( "--", "-" )		
					intIndexOfMatch = search.indexOf( "--" );	
				}
				window.location="<?php echo rootpath()?>/search/"+selectCategory+"/"+search;
			}
		});
        $(document).ready(function() 
		{
			$('input.typeahead').bind("typeahead:selected", function () 
			{
				var selectCategory=$('#selectsystemCategory').val();
				if(selectCategory=="")
				selectCategory=$('#selectmobileCategory').val();
				var value=$(this).val();
				value=value.split(/\s+/).slice(0,9).join(" ");
				search=value.replace(/[^a-z0-9]/gi, " ");
				search=search.trim();
				search=search.replace(/[^a-z0-9]/gi, "-");
				var intIndexOfMatch = search.indexOf("--");	
				while (intIndexOfMatch != -1)
				{	
					search = search.replace( "--", "-" );
					intIndexOfMatch = search.indexOf( "--" );	
				}
				window.location.href = "<?php echo(rootpath()) ?>/search/"+selectCategory+"/"+search;
			});
			$('.search_box').typeahead([
			{
				name: 'search',
				remote: '<?php echo(rootpath()) ?>/includes/autocomplete.php?query=%QUERY',
			}]);
			$.ajax
			({
				type:'POST',
				url: '<?php echo rootpath()?>/increment.php',
				data: {'PageViews':'PageViews','UniqHits':'UniqHits'},
				success: function(res) {}
			});
        });
		$(".purchase").click(function()
		{
			var permalink=this.id;
			$.ajax
			({
				type:'POST',
				url: '<?php echo rootpath()?>/increment.php',
				data: {'PermaLink':permalink},
				success: function(res) {}
			});
		});
		$('.closevideo').click(function()
		{
			var id=$(this).attr('id');
			var src= $('#demo-'+id+'_html5_api').attr('src');
			$('#demo-'+id+'_html5_api').attr('src',src);
		});
		$('.closevideo2').click(function()
		{
			var id=$(this).attr('id');
			var src= $('#demo2-'+id+'_html5_api').attr('src');
			$('#demo1-'+id+'_html5_api').attr('src',src);
		});
	</script>
</body>
</html>