<?php
$schema = $this->params->get('params.additional_fields');
$fields = @$data['add_field'];
settype($fields, 'array');
$add_fields_info = explode("\n", str_replace("\r", '', $schema));
?>

<table width="100%" cellpadding="2" cellspacing="2">
	<tr>
		<td><?php echo JText::_('E_URL')?></td>
        <td><a target="blank" href="<?php echo JRoute::_(Url::record($record), TRUE, -1);?>"><?php echo $config->get('sitename');?></a></td>
    </tr>
	<tr>
		<td><?php echo JText::_('E_TITLE') ?></td>
        <td><?php echo $record->title?></td>
    </tr>
	<tr>
		<td><?php echo JText::_('E_FROM')?></td>
        <td><?php echo $data['name'] . ' (' . $data['email_from'] . ')'?></td>
    </tr>
	<tr>
		<td><?php echo JText::_('E_MSG')?></td>
        <td><?php echo nl2br(strip_tags($data['body']))?></td>
    </tr>
	<?php if($schema && isset($data['add_field'])): ?>
		<?php foreach($add_fields_info as $f): ?>
			<?php
			if(!trim($f)) continue;
			$field_info = explode('::', $f);
			if(count($field_info) <= 2) continue;
			?>
			<tr>
				<td><?php echo JText::_(trim($field_info[2])); ?></td>
				<td><?php echo (is_array($fields[$field_info[1]]) ? implode(',', $fields[$field_info[1]]) : nl2br($fields[$field_info[1]])) ?></td>
			</tr>
		<?php endforeach; ?>
	<?php endif; ?>
</table>