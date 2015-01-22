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
<p class="phone">
	<span class="phone-label"><?php _e('Phone: ');?></span>
	<a class="phone-value" href="tel:<?php $this->the_phone_link($instance);?>"><?php $this->the_phone($instance);?></a>
</p>
<p class="email">
	<span class="email-label"><?php _e('Email: ');?></span>
	<a class="email-value" href="mailto:<?php $this->the_email_link($instance);?>"><?php $this->the_email($instance);?></a>
</p>

<?php echo $args['after_widget']; ?>
