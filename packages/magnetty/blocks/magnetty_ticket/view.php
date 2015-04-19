<?php defined('C5_EXECUTE') or die("Access Denied.");
$c = Page::getCurrentPage();

// $viewMode Status:
	// Unregistered,
	// Registered
	// RSVPed
	// Paid
	// Cancelled
	// Cancelled_Full
	// Waitlist
	// Full
	// NotAllowed
	// Admin
// SubmitStatus: MagnettyStatus
	// rsvp
	// cancel
	// pay (TBA)
// Options: $canCancel
	// 1-> Users can cancel RSVP and re-register
	// 2-> Users can cancel RSVP but cannot re-register
	// 3-> Users cannot cancel RSVP at all

?>

<?php if ($debugMode) {?>	
<h1>Debug</h1>
<p># of Tickets: <?php var_dump($magnettyTicketNum); ?></p>
<p># of Tickets RSVPed: <?php var_dump($magnettyTicketCount); ?></p>
<p># of Tickets Left: <?php echo intval($magnettyTicketNum)-$magnettyTicketCount; ?></p>
<p>Current Status: <?php var_dump($viewMode); ?></p>
<p>Cancel Availability:<?php var_dump($canCancel);?></p>
<p>Token:<?php var_dump($token);?></p>
<?php } ?>

<?php 
/*
 * When User is not logged in
 */
if ($viewMode == 'Unregistered') {
?>
	<p><a type="button" class="btn btn-danger btn-block" href="<?php echo URL::to('/login', 'forward') . '/' . $c->getCollectionID();?>"><strong><?php echo t('ログイン or 新規会員登録') ?></strong></a>
	</p>
	<?php }
	/*
	 * When Registered User
	 */
		else if  (($viewMode == 'Registered') || ($viewMode == 'Admin')) { ?>
	<p>
		<form method="post" action="<?php echo $this->action('rsvp'); ?>" onSubmit="return checkSubmit()">
			<button type="submit" class="btn btn-danger btn-block" onclick="$(this).closest('form').submit();return false" >
				<strong><?php echo t('このイベントに参加する') ?></strong>
			</button>
			<input type="hidden" name="token" value="<?php  echo $token->generate('rsvp'); ?>" />
			<input type="hidden" name="MagnettybID" value="<?php echo $bID;?>" />
			<input type="hidden" name="MagnettyuID" value="<?php echo $uID;?>" />
			<input type="hidden" name="MagnettyStatus" value="rsvp" />
		</form>
		<script type="text/javascript">
		function checkSubmit() {
			return confirm("<?php echo t('このイベントに参加申し込みをしますか？');?>");
		}
		</script>
	</p>
<?php }
/*
 * When RSVPed User who can cancel
 */
	else if  (($viewMode == 'RSVPed') && !($canCancel=='3')) { ?>
	<p>
		<form method="post" action="<?php echo $this->action('rsvp'); ?>" onSubmit="return checkSubmit()">
			<button type="submit" class="btn btn-danger btn-block" onclick="$(this).closest('form').submit();return false" >
				<strong><?php echo t('申込をキャンセルする') ?></strong>
			</button>
			<input type="hidden" name="token" value="<?php  echo $token->generate('rsvp'); ?>" />
			<input type="hidden" name="MagnettybID" value="<?php echo $bID;?>" />
			<input type="hidden" name="MagnettyuID" value="<?php echo $uID;?>" />
			<input type="hidden" name="MagnettyStatus" value="cancel" />
		</form>
		<script type="text/javascript">
		function checkSubmit() {
			return confirm("<?php echo t('このイベントに参加申し込みをキャンセルしますか？');?>");
		}
		</script>
	</p>
<?php }
/*
 * When RSVPed User
 */
	else if (($viewMode == 'RSVPed') && ($canCancel=='3')) { ?>
<p>
	<button type="submit" class="btn btn-danger btn-block" >
		<strong><?php echo t('お申込み済みです') ?></strong>
	</button>
</p>
<?php }
/*
 * When RSVPed & Paid User who can cancel
 */
	else if  (($viewMode == 'Paid') && !($canCancel=='3')) { ?>
	<p>
		<form method="post" action="<?php echo $this->action('rsvp'); ?>" onSubmit="return checkSubmit()">
			<button type="submit" class="btn btn-danger btn-block" onclick="$(this).closest('form').submit();return false" >
				<strong><?php echo t('お支払い済み | 申込をキャンセルする') ?></strong>
			</button>
			<input type="hidden" name="token" value="<?php  echo $token->generate('rsvp'); ?>" />
			<input type="hidden" name="MagnettybID" value="<?php echo $bID;?>" />
			<input type="hidden" name="MagnettyuID" value="<?php echo $uID;?>" />
			<input type="hidden" name="MagnettyStatus" value="cancel" />
		</form>
		<script type="text/javascript">
		function checkSubmit() {
			return confirm("<?php echo t('このイベントに参加申し込みをキャンセルしますか？お支払い済みのイベントの返金についてはイベント事の返金ルールを参照ください。');?>");
		}
		</script>
	</p>
<?php }
/*
 * When RSVPed & Paid User who cannot cancel
 */
	else if (($viewMode == 'Paid') && ($canCancel=='3')) { ?>
	<p>
		<button type="submit" class="btn btn-danger btn-block" >
			<strong><?php echo t('お支払い済みです') ?></strong>
		</button>
	</p>
<?php }
/*
 * When Cancelled user and they can re-register
 */
	else if  (($viewMode == 'Cancelled') && ($canCancel=='1')) { ?>
	<p>
		<form method="post" action="<?php echo $this->action('rsvp'); ?>" onSubmit="return checkSubmit()">
			<button type="submit" class="btn btn-danger btn-block" onclick="$(this).closest('form').submit();return false" >
				<strong><?php echo t('キャンセル済み | 再参加申込') ?></strong>
			</button>
			<input type="hidden" name="token" value="<?php  echo $token->generate('rsvp'); ?>" />
			<input type="hidden" name="MagnettybID" value="<?php echo $bID;?>" />
			<input type="hidden" name="MagnettyuID" value="<?php echo $uID;?>" />
			<input type="hidden" name="MagnettyStatus" value="rsvp" />
		</form>
		<script type="text/javascript">
		function checkSubmit() {
			return confirm("<?php echo t('このイベントに再び参加申し込みをしますか？');?>");
		}
		</script>
	</p>
<?php }
/*
 * When Cancelled user and they are full
 */
	else if (
		(($viewMode == 'Cancelled')  && !($canCancel=='1')) ||
		($viewMode == 'Cancelled_Full')
		) { ?>
	<p>
		<button type="submit" class="btn btn-danger btn-block" >
			<strong><?php echo t('キャンセル済み') ?></strong>
		</button>
	</p>
<?php }
/*
 * When Cancelled user and they are full
 */
	else if (
		($viewMode == 'Full')
		) { ?>
	<p>
		<form method="post" action="<?php echo $this->action('rsvp'); ?>" onSubmit="return checkSubmit()">
			<button type="submit" class="btn btn-danger btn-block" onclick="$(this).closest('form').submit();return false" >
				<strong><?php echo t('キャンセル待ち') ?></strong>
			</button>
			<input type="hidden" name="token" value="<?php  echo $token->generate('rsvp'); ?>" />
			<input type="hidden" name="MagnettybID" value="<?php echo $bID;?>" />
			<input type="hidden" name="MagnettyuID" value="<?php echo $uID;?>" />
			<input type="hidden" name="MagnettyStatus" value="rsvp" />
		</form>
		<script type="text/javascript">
		function checkSubmit() {
			return confirm("<?php echo t('このイベントにキャンセル待ちで申し込みますか？');?>");
		}
		</script>
	</p>
<?php }
	else if (
		($viewMode == 'Waitlist')
		) { ?>
	<p>
		<form method="post" action="<?php echo $this->action('rsvp'); ?>" onSubmit="return checkSubmit()">
			<button type="submit" class="btn btn-danger btn-block" onclick="$(this).closest('form').submit();return false" >
				<strong><?php echo t('キャンセル待ち取り消し') ?></strong>
			</button>
			<input type="hidden" name="token" value="<?php  echo $token->generate('rsvp'); ?>" />
			<input type="hidden" name="MagnettybID" value="<?php echo $bID;?>" />
			<input type="hidden" name="MagnettyuID" value="<?php echo $uID;?>" />
			<input type="hidden" name="MagnettyStatus" value="cancel" />
		</form>
		<script type="text/javascript">
		function checkSubmit() {
			return confirm("<?php echo t('このイベントのキャンセル待ち申し込みをキャンセルしますか？');?>");
		}
		</script>
	</p>
<?php } ?>