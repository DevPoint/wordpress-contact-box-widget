<?php
/**
 * Contact Box Widget: Default widget template
 * 
 * @since 1.0.0
 */

// Block direct requests
if (!defined('ABSPATH')) die('-1');
?>

<?php echo $args['before_widget']; ?>

<?php if ($this->has_title($instance)) : ?>
<?php echo $args['before_title']; ?><?php $this->the_title($instance); ?><?php echo $args['after_title']; ?>
<?php endif; ?>
<?php if ($this->has_phone($instance)) : ?>
<p class="phone">
	<?php if ($this->has_label($instance)) :?>
	<span class="phone-label"><?php $this->the_phone_label($instance);?></span>
	<?php endif;?>
	<a class="phone-value" href="tel:<?php $this->the_phone_link($instance);?>"><?php $this->the_phone($instance);?></a>
</p>
<?php endif; ?>
<?php if ($this->has_email($instance)) : ?>
<p class="email">
	<?php if ($this->has_label($instance)) :?>
	<span class="email-label"><?php $this->the_email_label($instance);?></span>
	<?php endif;?>
	<a class="email-value" href="mailto:<?php $this->the_email_link($instance);?>"><?php $this->the_email($instance);?></a>
</p>
<?php endif; ?>
<?php if ($this->has_facebook($instance)) : ?>
<p class="facebook">
	<?php if ($this->has_label($instance)) :?>
	<span class="facebook-label"><?php $this->the_facebook_label($instance);?></span>
	<?php endif;?>
	<a class="facebook-value" href="<?php $this->the_facebook_link($instance);?>" target="_blank"><?php $this->the_facebook($instance);?></a>
</p>
<?php endif; ?>
<?php if ($this->has_youtube($instance)) : ?>
<p class="youtube">
	<?php if ($this->has_label($instance)) :?>
	<span class="youtube-label"><?php $this->the_youtube_label($instance);?></span>
	<?php endif;?>
	<a class="youtube-value" href="<?php $this->the_youtube_link($instance);?>" target="_blank"><?php $this->the_youtube($instance);?></a>
</p>
<?php endif; ?>
<?php if ($this->has_custom_01($instance)) : ?>
<p class="custom_01">
	<?php if ($this->has_label($instance)) :?>
	<span class="custom_01-label"><?php $this->the_custom_01_label($instance);?></span>
	<?php endif;?>
	<a class="custom_01-value" href="<?php $this->the_custom_01_link($instance);?>" target="<?php $this->the_custom_01_target($instance);?>"><?php $this->the_custom_01($instance);?></a>
</p>
<?php endif; ?>
<?php if ($this->has_custom_02($instance)) : ?>
<p class="custom_02">
	<?php if ($this->has_label($instance)) :?>
	<span class="custom_02-label"><?php $this->the_custom_02_label($instance);?></span>
	<?php endif;?>
	<a class="custom_02-value" href="<?php $this->the_custom_02_link($instance);?>" target="<?php $this->the_custom_02_target($instance);?>"><?php $this->the_custom_02($instance);?></a>
</p>
<?php endif; ?>

<?php echo $args['after_widget']; ?>
