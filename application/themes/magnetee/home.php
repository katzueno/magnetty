<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
$this->inc('elements/header.php');
?>

	<div id="headerwrap">
		<div class="container">
			<div class="row centered">
				<div class="col-lg-8 col-lg-offset-2">
				<h1><b>趣味からみつける</b><br>
				新しい出会い</h1>
				</div>
			</div><!-- row -->
		</div><!-- container -->
	</div><!-- headerwrap -->


	<div class="container w">
		<div class="row centered">
			<br><br>
			<div class="col-lg-4">
				<i class="fa fa-heart"></i>
				<?php
         	$content = new Area('Content');
         	$content->display($c);
         	?>
			</div><!-- col-lg-4 -->

			<div class="col-lg-4">
				<?php
         	$content = new Area('Content');
         	$content->display($c);
         	?>
			</div><!-- col-lg-4 -->

			<div class="col-lg-4">
				<?php
         	$content = new Area('Content');
         	$content->display($c);
         	?>
			</div><!-- col-lg-4 -->
		</div><!-- row -->
        
        		<div class="row centered">
			<br><br>
			<div class="col-lg-4 col-lg-offset-2">
				<i class="fa fa-heart"></i>
				<h4>イベント参加者を事前に確認できます。</h4>
				<p>街コンなどのパーティーは、どんな人が参加しているか分からず、近くにいる人や話しかけられた人などとしか会話できなかったという経験はありませんか？ magneteeでは、イベント参加者のプロフィールをあらかじめ見ることができます。イベント当日、気になった人に話しかけてみてはいかがでしょう？</p>
			</div><!-- col-lg-4 -->

			<div class="col-lg-4">
				<i class="fa fa-heart"></i>
				<h4>連絡先の交換をする必要はありません。</h4>
				<p>イベントの時に話しかけたかったけど、話しかけれなかったことはありませんか？ 勇気が出なくて連絡先の交換ができなかった、チャンスを逃した経験はどうでしょう？ magneteeでは、イベント終了後に個人情報を交換せずにメールのやり取りができます。</p>
			</div><!-- col-lg-4 -->
		</div><!-- row -->
		<br>
		<br>
	</div><!-- container -->



	
	
	<div id="r">
		<div class="container">
			<div class="row centered">
				<div class="col-lg-8 col-lg-offset-2">
					<h4>WE ARE STORYTELLERS. BRANDS ARE OUR SUBJECTS. DESIGN IS OUR VOICE.</h4>
					<p>We believe ideas come from everyone, everywhere. At BlackTie, everyone within our agency walls is a designer in their own right. And there are a few principles we believe—and we believe everyone should believe—about our design craft. These truths drive us, motivate us, and ultimately help us redefine the power of design.</p>
				</div>
			</div><!-- row -->
		</div><!-- container -->
	</div><! -- r wrap -->
	
	
	
<?php
$this->inc('elements/footer.php');
?>

	