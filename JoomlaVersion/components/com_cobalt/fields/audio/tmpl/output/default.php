<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die();
$client_n = ($client == 'full' ? 2 : 1);
?>
<span style="display: block"><div id="mediaplayer<?php echo $record->id;?>"></div></span>
<table class="table table-condensed" id="playlist_table">
	<thead>
		<tr>
			<th><?php echo JText::_('CNAME');?></th>

			<?php if(in_array($this->params->get('params.show_year'), array('3', $client_n))):?>
			<th width="1%"><?php echo JText::_('CYEAR')?></th>
			<?php endif;?>

			<?php if(in_array($this->params->get('params.show_genre'), array('3', $client_n))):?>
			<th width="1%"><?php echo JText::_('P_GENRE')?></th>
			<?php endif;?>

			<?php if(in_array($this->params->get('params.show_album'), array('3', $client_n))):?>
			<th width="8%" style="max-width: 120px;"><?php echo JText::_('P_ALNUM')?></th>
			<?php endif;?>

			<?php if(in_array($this->params->get('params.show_artist'), array('3', $client_n))):?>
			<th width="1%"><?php echo JText::_('P_ARTIST')?></th>
			<?php endif;?>

			<?php if($this->descr):?>
			<th width="1%"><?php echo JText::_('P_LYRIC');?></th>
			<?php endif;?>
			<?php if(in_array($this->params->get('params.allow_download', 0), $this->user->getAuthorisedViewLevels())):?>
			<th width="1%"><?php echo JText::_('CSAVE');?></th>
			<?php endif;?>
		</tr>
	</thead>
	<tbody>
		<?php foreach($this->tracks as $k => $file):?>

		<?php
			$tracks[] = "{sources:[{'file':'".$this->getFileUrl($file)."', 'title':'".($file->title ? $file->title : $file->realname)."'}]}";
			$data = new JRegistry($file->params);
		?>
		<tr valign="middle">
			<td>
				<a href="javascript:void(0)" id="file_play_<?php echo $record->id;?>_<?php echo $k;?>"
					onclick="play<?php echo $record->id;?>(<?php echo $k;?>)">
					<img src="<?php echo JURI::root(TRUE)?>/media/mint/icons/16/control.png" alt="<?php echo JText::_('P_PLAY')?>" align="absmiddle"></a>
				<a href="javascript:void(0)" id="file_stop_<?php echo $record->id;?>_<?php echo $k;?>" onclick="stop<?php echo $record->id;?>(<?php echo $k;?>)" style="display:none">
					<img src="<?php echo JURI::root(TRUE)?>/media/mint/icons/16/control-stop-square.png" alt="<?php echo JText::_('P_STOP')?>" align="absmiddle"></a>
				<?php echo $file->title ? $file->title : $file->realname;?>
				<?php if($data->get('comment') && $this->params->get('params.show_comment')):?>
					<?php echo $this->params->get('params.show_comment');?>
				<?php endif;?>
			</td>

			<?php if(in_array($this->params->get('params.show_year'), array('3', $client_n))):?>
				<td nowrap="nowrap"><?php echo $data->get('year')?></td>
			<?php endif;?>

			<?php if(in_array($this->params->get('params.show_genre'), array('3', $client_n))):?>
				<td nowrap="nowrap"><?php echo $data->get('genre')?></td>
			<?php endif;?>

			<?php if(in_array($this->params->get('params.show_album'), array('3', $client_n))):?>
				<td><?php echo $data->get('album')?></td>
			<?php endif;?>

			<?php if(in_array($this->params->get('params.show_artist'), array('3', $client_n))):?>
				<td nowrap="nowrap"><?php echo $data->get('artist')?></td>
			<?php endif;?>

			<?php if($this->descr):?>
				<td nowrap="nowrap">
					<?php if(!empty($file->description)): ?>
					<center>
						<a href="#lyric<?php echo $file->id;?>" class="modal" rel="{handler:'adopt', size:{x:400,y:500}}">
							<img src="<?php echo JURI::root(TRUE)?>/media/mint/icons/16/clipboard-list.png" alt="<?php echo JText::_('P_LYRIC')?>" align="absmiddle">
						</a>
						<div  style="display: none;">
							<div id="lyric<?php echo $file->id;?>"><h3><?php echo JText::_('P_LYRIC')?></h3><br /><?php echo nl2br($file->description);?></div>
						</div>
					</center>
					<?php endif; ?>
				</td>
			<?php endif;?>
			<?php if(in_array($this->params->get('params.show_download', 0), $this->user->getAuthorisedViewLevels())):?>
				<td nowrap="nowrap">
					<center>
						<a href="<?php echo $file->url?>">
							<img src="<?php echo JURI::root(TRUE)?>/media/mint/icons/16/disk.png" alt="<?php echo JText::_('CDOWNLOAD')?>" align="absmiddle"></a>
						<?php if ($this->hits):?>
							[<?php echo $file->hits?>]
						<?php endif;?>
					</center>
				</td>
			<?php endif;?>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>

<script type="text/javascript">
	var player<?php echo $record->id;?> = jwplayer("mediaplayer<?php echo $record->id;?>").setup({
		"width": "<?php echo $this->params->get('params.width_' . $client);?>",
		"height": "<?php echo $this->params->get('params.height_' . $client);?>",
		"controlbar": "bottom",
		"repeat": "true",
		"playlist": [<?php echo implode(',', $tracks)?>]
		<?php if ($this->params->get('params.listbar_' . $client, false)):?>
			,
			"listbar": {
		        "position": 'bottom',
		        "size": '200'
		    }
		<?php endif;?>
	});

	function stop<?php echo $record->id;?>(idx)
	{
		$("file_stop_<?php echo $record->id;?>_" + idx).setStyle("display", "none");
		$("file_play_<?php echo $record->id;?>_" + idx).setStyle("display", "inline-block");
		player<?php echo $record->id;?>.stop();
	}
	function play<?php echo $record->id;?>(index)
	{
		player<?php echo $record->id;?>.playlistItem(index);
		$('playlist_table').getElements("a[id^=file_play_<?php echo $record->id;?>_]").setStyle("display", "inline-block");
		$('playlist_table').getElements("a[id^=file_stop_<?php echo $record->id;?>_]").setStyle("display", "none");
		$("file_play_<?php echo $record->id;?>_" + index).setStyle("display", "none");
		$("file_stop_<?php echo $record->id;?>_" + index).setStyle("display", "inline-block");
	}
</script>
<?php
