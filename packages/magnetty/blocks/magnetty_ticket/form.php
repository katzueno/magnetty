<?php  
defined('C5_EXECUTE') or die("Access Denied.");
?>
<fieldset>
<legend><?php echo t('Basic Ticket Set-ups');?></legend>

<div class="form-group">
	<?php echo $form->label('ticketName', t('Ticket Name'))?>
	<?php echo $form->text('ticketName', $ticketName, array('style'=>'width: 60%;')); ?>
</div>

<div class="form-group">
	<?php echo $form->label('ticketNum', t('# of Tickets'))?>
	<?php echo $form->number('ticketNum', $ticketName, array('style'=>'width: 60%;')); ?>
</div>

<div class="form-group">
	<?php echo $form->label('ticketPrice', t('Price'))?>
	<?php echo $form->text('ticketPrice', $ticketName, array('style'=>'width: 60%;')); ?>
</div>

<?php /*

You must add
		<field name="allowedGroups" type="C" size="255">
		</field>
back to db.xml
	
<div class="form-group">
	<?php echo $form->label('allowedGroups', t('Groups ID to be allowed'))?>
	<?php echo $form->text('allowedGroups', $allowedGroups, array('style'=>'width: 60%;')); ?>
</div>
<p><?php echo t("Go to Dashboard's Groups page to find your desired group numeric ID number which should mentioned in the URL. This is the group that allowed to RSVP. Or, enter '1' for guest user or '2' for registered user."); ?></p>
*/?>

<div class="form-group">
	<?php echo $form->label('canCancel', t('Cancellation Settings'))?>
	<?php echo $form->select('canCancel', $canCancel, array('1' => t('Users can cancel RSVP and re-register'), '2' => t('Users can cancel RSVP but cannot re-register'), '3' => t('Users cannot cancel RSVP at all')), '1'); ?>
</div>
</fieldset>

<p><?php echo t("If you didn't fill out the following fields, the default settings will be use. Please go to [Dashboard] - [Magnetty] - [Settings] to change the default texts."); ?></p>

<fieldset>
<legend><?php echo t('RSVP Confirmation Email')?></legend>>
<div class="form-group">
	<?php echo $form->label('emailConfirmationSubject', t('Subject'))?>
	<?php echo $form->text('emailConfirmationSubject', $emailConfirmationSubject, array('style'=>'width: 60%;')); ?>
</div>
<div class="form-group">
    <?php   echo $form->label('emailConfirmationBody', t('Body')); ?>
    <?php   echo $form->textarea('emailConfirmationBody', $emailConfirmationBody); ?>
</div>
</fieldset>

<fieldset>
<legend><?php echo t('Cancellation Confirmation Email');?></legend>
<div class="form-group">
	<?php echo $form->label('emailCancellationTitle', t('Subject'))?>
	<?php echo $form->text('emailCancellationSubject', $emailCancellationSubject, array('style'=>'width: 60%;')); ?>
</div>
<div class="form-group">
    <?php   echo $form->label('emailCancellationBody', t('Body')); ?>
    <?php   echo $form->textarea('emailCancellationBody', $emailCancellationBody); ?>
</div>
</fieldset>

<fieldset>
<legend><?php echo t('Payment Confirmation Email')?></legend>
<div class="form-group">
	<?php echo $form->label('emailPaymentSubject', t('Subject'))?>
	<?php echo $form->text('emailPaymentSubject', $emailPaymentSubject, array('style'=>'width: 60%;')); ?>
</div>
<div class="form-group">
    <?php   echo $form->label('emailPaymentBody', t('Body')); ?>
    <?php   echo $form->textarea('emailPaymentBody', $emailConfirmationBody); ?>
</div>
</fieldset>

<fieldset>
<legend><?php echo t('Wait-list Confirmation');?></legend>
<div class="form-group">
	<?php echo $form->label('emailWaitlistSubect', t('Subject'))?>
	<?php echo $form->text('emailWaitlistSubect', $emailWaitlistSubect, array('style'=>'width: 60%;')); ?>
</div>
<div class="form-group">
    <?php   echo $form->label('emailWaitlistBody', t('Body')); ?>
    <?php   echo $form->textarea('emailWaitlistBody', $emailConfirmationBody); ?>
</div>
</fieldset>