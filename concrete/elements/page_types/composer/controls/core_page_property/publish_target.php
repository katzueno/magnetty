<?php
defined('C5_EXECUTE') or die("Access Denied.");
$pagetype = $set->getPageTypeObject();
$target = $pagetype->getPageTypePublishTargetObject();
$c = $control->getPageObject();
if (is_object($c)) {
    $cParentID = $c->getPageDraftTargetParentPageID();
} else if ($control->getTargetParentPageID()) {
    $cParentID = $control->getTargetParentPageID();
}
$parent = Page::getByID($cParentID);
if (is_object($parent) && $parent->isError()) {
    unset($parent);
}

?>

<div class="form-group">
	<label class="control-label"><?php echo $label?></label>
	<?php if($description): ?>
	<i class="fa fa-question-circle launch-tooltip" title="" data-original-title="<?php echo $description?>"></i>
	<?php endif; ?>
	<div data-composer-field="name">
		<?php echo $target->includeChooseTargetForm($pagetype, $parent)?>
	</div>
</div>