<?php 
defined('C5_EXECUTE') or die(_("Access Denied."));
?>

<form method="post" id="site-form" action="<?php echo $this->action('save_settings'); ?>"  enctype="multipart/form-data">

    <?php echo $token->output('save_settings');?>
    
        <h4><?php echo t('Configuration of Magnetty Event Attend') ?></h4>
        <br/>

		<p><?php echo t('We will send you the email notification of all RSVP email');?></p>
        <div class="form-group">
            <?php   echo $form->label('adminEmail', t('Admin Email Address')); ?>
            <?php   echo $form->email('adminEmail', $adminEmail); ?>
        </div>
		<p><?php echo t('This email address will also be used as FROM and REPLY-TO address.');?></p>
        <p><?php echo t('WARNING: We are not validating this email address. The error messages are not being sent at the moment. Please make sure to test before going to live!');?></p>


        <br/>

        <div class="form-group">
            <?php echo $form->label('allowCancel', t('Allow Cancellation?')); ?>
            <?php echo $form->checkbox('allowCancel', $allowCancel, true); ?>
        </div>


		<p><?php echo t('Please enter the default confirmation email bosy text.');?></p>
        <div class="form-group">
            <?php   echo $form->label('emailConfirmationText', t('Confirmation Email')); ?>
            <?php   echo $form->textarea('emailConfirmationText', $emailConfirmationText); ?>
        </div>

		<p><?php echo t('Please enter the default waitlist email body text.');?></p>
        <div class="form-group">
            <?php   echo $form->label('emailWaitlistText', t('Waitlist Email')); ?>
            <?php   echo $form->textarea('emailWaitlistText', $emailWaitlistText); ?>
        </div>

		<p><?php echo t('Please enter the default cancellation email body text.');?></p>
        <div class="form-group">
            <?php   echo $form->label('emailCancelText', t('Cancellation Email')); ?>
            <?php   echo $form->textarea('emailCancelText', $emailCancelText); ?>
        </div>

		<p><?php echo t('Please enter the default waitlist cancellation email body text.');?></p>
        <div class="form-group">
            <?php   echo $form->label('emailWaitlistCancelText', t('Waitlist Cancellation Email')); ?>
            <?php   echo $form->textarea('emailWaitlistCancelText', $emailWaitlistCancelText); ?>
        </div>

    <div class="ccm-dashboard-form-actions-wrapper">
    <div class="ccm-dashboard-form-actions">
        <button class="pull-right btn btn-success" type="submit" ><?php echo t('Save'); ?></button>
    </div>
    </div>
</form>
