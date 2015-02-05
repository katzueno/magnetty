<?php  
defined('C5_EXECUTE') or die("Access Denied.");
?>
<fieldset>
<legend><?php echo t('Basic RSVP List Set-ups');?></legend>

<div class="form-group">
	<?php echo $form->label('ticketbID', t('Ticket ID'))?>
	<?php echo $form->text('ticketbID', $ticketbID, array('style'=>'width: 60%;')); ?>
	<?php /*
		echo $form->select('ticketbID', $ticketbID,
			array(
				'1' => t('Users can cancel RSVP and re-register'),
				'2' => t('Users can cancel RSVP but cannot re-register'),
				'3' => t('Users cannot cancel RSVP at all')
				),
				'1');
		*/ ?>
</div>

<div class="checkbox">
    <label>
        <input type="checkbox" name="linkProfile"
               value="1" <?php if ($linkProfile == 1) { ?> checked <?php } ?> />
        <?php echo t('Make profile clickable'); ?>
    </label>
</div>

<div class="checkbox">
    <label>
        <input type="checkbox" name="showAvatar"
               value="1" <?php if ($showAvatar == 1) { ?> checked <?php } ?> />
        <?php echo t('Show avatar'); ?>
    </label>
</div>
