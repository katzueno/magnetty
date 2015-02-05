<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
$this->inc('elements/header.php');
?>

	<div class="container desc">
		<div class="row">
			<br><br>
            
            <!--メイン カラム-->
	  		<div class="col-lg-7 col-lg-push-2 centered" style="background-color:#fff;">
			<h3>Apple WatchKit もくもく勉強会 #3</h3>
    		<h4 class="time"><img src="assets/img/icon_time.jpg" width="23" height="23">2015-02-22（日）14:00 - 18:00</h4>
			</div><!-- col-lg-6 -->
           
            <!--左カラム-->
            <div class="col-lg-2 col-lg-pull-7 sideL">
				<?php
         	$content = new Area('Content');
         	$content->display($c);
         	?>
            </div><!-- col-lg-6 -->
            
            
            <!--右カラム-->
			<div class="col-lg-3">
            	<?php
         	$content = new Area('Content');
         	$content->display($c);
         	?>
				
			</div>
		</div><!-- row -->
		
		

		<br><br>
		<hr>
		<br><br>
		
		<br><br>
	</div><!-- container -->



<?php
$this->inc('elements/footer.php');
?>
