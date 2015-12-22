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
	
<h1>Debug</h1>
<p># of Tickets Available: <?php echo $magnettyTicketNum; ?></p>
<p># of Tickets RSVPed<?php echo $magnettyTicketCount; ?></p>
<p>Current Status: <?php echo $viewMode; ?></p>
<p>Cancel Availability:<?php echo $canCancel;?></p>

<?php 
/*
 * When User is not logged in
 */
if ($viewMode == 'Unregistered') {
?>
<p>Unregistered</p>

<?php }
/*
 * When Registered User
 */
	else if  ($viewMode == 'Registered') { ?>
<p>Registered</p>
<?php }
/*
 * When RSVPed User who can cancel
 */
	else if  (($viewMode == 'Admin') { ?>
<p>Admin</p>
<?php }
/*
 * When RSVPed User
 */
	else { ?>
<p>
</p>
<?php } ?>