<?php
/**
 * Contact Box Widget: Widget Admin Form 
 */

// Block direct requests
if ( !defined( 'ABSPATH' ) )
	die( '-1' );
?>

<p>
	<label for="<?php echo $this->get_field_id('title');?>"><?php _e('Title:', $this->get_widget_text_domain()); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo $instance['title'];?>" />
</p>
<?php if ($this->has_feature('phone')) : ?>
<p>
	<label for="<?php echo $this->get_field_id('phone');?>"><?php _e('Phone number:', $this->get_widget_text_domain()); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id('phone');?>" name="<?php echo $this->get_field_name('phone');?>" type="text" value="<?php echo $instance['phone'];?>" />
</p>
<?php endif; ?>
<?php if ($this->has_feature('email')) : ?>
<p>
	<label for="<?php echo $this->get_field_id('email');?>"><?php _e('Email address:', $this->get_widget_text_domain()); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id('email');?>" name="<?php echo $this->get_field_name('email');?>" type="text" value="<?php echo $instance['email'];?>" />
</p>
<?php endif; ?>
<?php if ($this->has_feature('facebook')) : ?>
<p>
	<label for="<?php echo $this->get_field_id('facebook');?>"><?php _e('Facebook link text:', $this->get_widget_text_domain()); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id('facebook');?>" name="<?php echo $this->get_field_name('facebook');?>" type="text" value="<?php echo $instance['facebook'];?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id('facebook_link');?>"><?php _e('Facebook URL:', $this->get_widget_text_domain()); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id('facebook_link');?>" name="<?php echo $this->get_field_name('facebook_link');?>" type="text" value="<?php echo $instance['facebook_link'];?>" />
</p>
<?php endif; ?>
<?php if ($this->has_feature('youtube')) : ?>
<p>
	<label for="<?php echo $this->get_field_id('youtube');?>"><?php _e('Youtube link text:', $this->get_widget_text_domain()); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id('youtube');?>" name="<?php echo $this->get_field_name('youtube');?>" type="text" value="<?php echo $instance['youtube'];?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id('youtube_link');?>"><?php _e('Youtube URL:', $this->get_widget_text_domain()); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id('youtube_link');?>" name="<?php echo $this->get_field_name('youtube_link');?>" type="text" value="<?php echo $instance['youtube_link'];?>" />
</p>
<?php endif; ?>
<p>
	<label for="<?php echo $this->get_field_id('label_length');?>"><?php _e('Label display length:', $this->get_widget_text_domain()); ?></label> 
	<select class="widefat" id="<?php echo $this->get_field_id('label_length');?>" name="<?php echo $this->get_field_name('label_length');?>">
	<?php foreach ($this->get_label_length_list() as $label_length) : ?>
	<?php $selected_str = ($this->is_label_length($instance, $label_length['name'])) ? ' selected="selected"' : ''; ?>
	<option value="<?php echo $label_length['name'];?>"<?php echo $selected_str;?> ><?php echo $label_length['label'];?></option>
	<?php endforeach; ?>
	</select>
</p>
<?php $custom_template_list = $this->get_custom_template_list(); ?>
<?php if (!empty($custom_template_list)) : ?>
<p>
	<label for="<?php echo $this->get_field_id('template');?>"><?php _e('Template:', $this->get_widget_text_domain()); ?></label> 
	<select class="widefat" id="<?php echo $this->get_field_id('template');?>" name="<?php echo $this->get_field_name('template');?>">
	<?php $selected_str = ($this->is_template($instance, 'default')) ? ' selected="selected"' : ''; ?>
	<option value="default"<?php echo $selected_str;?> ><?php _e('default', $this->get_widget_text_domain()); ?></option>
	<?php foreach ($custom_template_list as $template) : ?>
	<?php $selected_str = ($this->is_template($instance, $template['name'])) ? ' selected="selected"' : ''; ?>
	<option value="<?php echo $template['name'];?>"<?php echo $selected_str;?> ><?php echo $template['label'];?></option>
	<?php endforeach; ?>
	</select>
</p>
<?php else : ?>
<input id="<?php echo $this->get_field_id('template'); ?>" name="<?php echo $this->get_field_name('template');?>" type="hidden" value="default" />
<?php endif; ?>
