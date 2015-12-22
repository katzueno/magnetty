<?php
defined('C5_EXECUTE') or die("Access Denied.");

$date = new DateTime();

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
<?php echo $emailBodyHTML; ?>
</article>
<hr />
<footer>
	<p><strong><?php echo t('Ticket Name') . ': <a href="' . $ticketURL . ' target="_blank">'. $ticketName . '</a>';?></strong></p>
	<div style="color:#555;font-size:10px;">
		<p><?php echo t('
		This email was sent from Magnetty Ticket System.
		If you receive this email by mistake, please contact the webmaster.
		');?></p>
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

<?php echo $emailBodyPlain;?>

----------
<?php echo t('Ticket Name');?>: <?php echo $ticketName;?>
<?php echo t('Ticket URL');?>: <?php echo $ticketURL;?>
----------
<?php echo t('
This email was sent from Magnetty Ticket System.
If you receive this email by mistake, please contact the webmaster.
'); ?>

Copyright &copy; <?php echo date('Y') . ' ' .  $siteName; ?>
<?php echo t('This email was sent at %s', $date->format('Y-m-d H:i:s')) ;?>

