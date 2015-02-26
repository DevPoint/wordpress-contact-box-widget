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
	<?php if ($this->is_label_length($instance, 'short')) :?>
	<span class="phone-label"><?php _ex('Phone:', 'short', $this->get_widget_text_domain());?></span>
	<?php elseif ($this->is_label_length($instance, 'long')) : ?>
	<span class="phone-label"><?php _ex('Phone:', 'long', $this->get_widget_text_domain());?></span>
	<?php endif;?>
	<a class="phone-value" href="tel:<?php $this->the_phone_link($instance);?>"><?php $this->the_phone($instance);?></a>
</p>
<?php endif; ?>
<?php if ($this->has_email($instance)) : ?>
<p class="email">
	<?php if ($this->is_label_length($instance, 'short')) :?>
	<span class="email-label"><?php _ex('Email:', 'short', $this->get_widget_text_domain());?></span>
	<?php elseif ($this->is_label_length($instance, 'long')) : ?>
	<span class="email-label"><?php _ex('Email:', 'long', $this->get_widget_text_domain());?></span>
	<?php endif;?>
	<a class="email-value" href="mailto:<?php $this->the_email_link($instance);?>"><?php $this->the_email($instance);?></a>
</p>
<?php endif; ?>
<?php if ($this->has_facebook($instance)) : ?>
<p class="facebook">
	<?php if ($this->is_label_length($instance, 'short')) :?>
	<span class="facebook-label"><?php _ex('Facebook:', 'short', $this->get_widget_text_domain());?></span>
	<?php elseif ($this->is_label_length($instance, 'long')) : ?>
	<span class="facebook-label"><?php _ex('Facebook:', 'long', $this->get_widget_text_domain());?></span>
	<?php endif;?>
	<a class="facebook-value" href="<?php $this->the_facebook_link($instance);?>"><?php $this->the_facebook($instance);?></a>
</p>
<?php endif; ?>

<?php echo $args['after_widget']; ?>
