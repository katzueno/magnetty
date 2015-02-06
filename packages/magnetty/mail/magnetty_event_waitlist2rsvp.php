<?php
defined('C5_EXECUTE') or die("Access Denied.");

$date = new DateTime();
$systemMsg = t('
This email was sent from Magnetty Ticket System.
If you receive this email by mistake, please contact the webmaster.
');

$emailBodyAddition = t("
Congratulations!

Someone has cancelled her/his ticket, and you've got the ticket!
We're looking forward to seeing you.

If you decided not to attend the event, please make sure to cancel your RSVP, so that other people can get your ticket.

If you've forgotten what kind of ticket you have RSVPed, please click the URL below.

");


$subject = h($emailSubject);
/**
 * HTML BODY START
 */
ob_start();
?>

<header><h2><?php echo h($emailSubject);?></h2></header>

<article>
<p><strong><?php echo t('Ticket Name') . ': <a href="' . $ticketURL . ' target="_blank">'. $ticketName . '</a>';?></strong></p>
<p>&nbsp;</p>
<p><strong><?php t("Dear %s", $userName);?></strong></p>
<p>&nbsp;</p>
<?php echo nlbr(h($emailBodyAddition)); ?>
<p>&nbsp;</p>
<?php echo $emailBodyHTML; ?>
</article>
<hr />
<footer>
	<p><strong><?php echo t('Ticket Name') . ': <a href="' . $ticketURL . ' target="_blank">'. $ticketName . '</a>';?></strong></p>
	<div style="color:#555;font-size:10px;">
		<p>
<?php echo $systemMsg ;?></p>
		<p>Copyright &copy; <?php echo date('Y') . ' ' .  $siteName; ?></p>
		<p><?php echo t('This email was sent at %s', $date->format('Y-m-d H:i:s')) ;?></p>
	</div>
</footer>

<?php
$bodyHTML = ob_get_clean();
/**
 * HTML BODY END
 *
 * ======================
 *
 * PLAIN TEXT BODY START
 */
ob_start();
?>

<?php echo h($emailSubject);?>
==========

<?php echo t('Ticket Name');?>: <?php echo $ticketName;?>

<?php t("Dear %s", $userName);?>

<?php echo h($emailBodyAddition); ?>

<?php echo $emailBodyPlain;?>

----------
<?php echo t('Ticket Name');?>: <?php echo $ticketName;?>
<?php echo t('Ticket URL');?>: <?php echo $ticketURL;?>
----------
<?php echo $systemMsg; ?>

Copyright &copy; <?php echo date('Y') . ' ' .  $siteName; ?>
<?php echo t('This email was sent at %s', $date->format('Y-m-d H:i:s')) ;?>

