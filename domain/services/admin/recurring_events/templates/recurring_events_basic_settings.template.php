<?php
/* @var EE_Recurring_Events_Config $config  */
/* @var array $yes_no_values */
/* @var string $return_action */
?>
<div class="padding">
	<h4>
		<?php _e('Recurring Events Settings', 'event_espresso'); ?>
	</h4>
	<table class="form-table">
		<tbody>

			<tr>
				<th><?php _e('Reset Recurring Events Settings?', 'event_espresso');?></th>
				<td>
					<?php echo EEH_Form_Fields::select( __('Reset Recurring Events Settings?', 'event_espresso'), 0, $yes_no_values, 'reset_recurring_events', 'reset_recurring_events' ); ?><br/>
					<span class="description">
						<?php _e('Set to \'Yes\' and then click \'Save\' to confirm reset all basic and advanced Event Espresso Recurring Events settings to their plugin defaults.', 'event_espresso'); ?>
					</span>
				</td>
			</tr>

		</tbody>
	</table>

</div>

<input type='hidden' name="return_action" value="<?php echo $return_action?>">

