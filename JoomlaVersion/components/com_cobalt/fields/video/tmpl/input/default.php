<style>
<!--
.link_delete {
	cursor: pointer;
	<?php if($this->only_one):?>
	display:none;
	<?php endif;?>
	position:absolute;
	top: 4px;
	right: 4px;
}
.element-box {
	position: relative;
}
.element-box textarea, .element-box input {
	margin-bottom: 15px;
	width: 98%;
}
.video-title {
	cursor: pointer;
	margin-bottom:10px;
	font-size: 16px;
}
-->
</style>
<div id="video-field">
	<?php if($this->only_one):?>
		<p class="small"><?php echo JText::_('CONLYONE')?></p>
	<?php endif;?>
	<?php if($this->upload):?>
		<div class="video-title" data-toggle="collapse" data-target="#upload-pan<?php echo $this->id ?>"><img src="<?php echo JURI::root(TRUE)?>/media/mint/icons/16/drive-upload.png" align="absmiddle" /> <?php echo JText::_('CUPLOAD')?></div>
		<div id="upload-pan<?php echo $this->id ?>" class="collapse fade video-pan-<?php echo $this->id; ?>">
			<div class="well">
				<?php echo $this->upload;?>
			</div>
		</div>
	<?php endif;?>

	<?php if(in_array($this->params->get('params.embed', 1), $this->user->getAuthorisedViewLevels())): ?>
		<div class="video-title" data-toggle="collapse" data-target="#embed-pan<?php echo $this->id ?>"><img src="<?php echo JURI::root(TRUE)?>/media/mint/icons/16/film-cast.png" align="absmiddle" /> <?php echo JText::_('CEMBED')?></div>
		<div id="embed-pan<?php echo $this->id ?>" class="collapse fade video-pan-<?php echo $this->id; ?>">
			<div class="well">
				<div id="input_embeds">
					<?php foreach ($this->embed AS $embed):?>
						<div class="element-box">
							<textarea style="" name="jform[fields][<?php echo $this->id; ?>][embed][]" cols="50" rows="5"
							id="<?php echo $this->formControl.$this->name;?>" ><?php echo $embed;?>
							</textarea><img align="absmiddle" src="<?php echo JURI::root(TRUE)?>/media/mint/icons/16/cross-button.png"
							class="link_delete" onclick="Cobalt.deleteFormElement('embed', this);">
						</div>
					<?php endforeach;?>
				</div>

				<?php if(!$this->only_one):?>
					<div id="embed-button">
						<button class="btn" type="button" onclick="Cobalt.addFormElement('embed', <?php echo $this->id; ?>);">
							<img src="<?php echo JURI::root(TRUE); ?>/media/mint/icons/16/plus-button.png" align="absmiddle">
							<?php echo JText::_('F_ADDEMBEDE'); ?>
						</button>
					</div>
				<?php endif;?>
			</div>
		</div>
	<?php endif;?>

	<?php if(in_array($this->params->get('params.link', 1), $this->user->getAuthorisedViewLevels())): ?>
		<div class="video-title" data-toggle="collapse" data-target="#link-pan<?php echo $this->id ?>">
			<img src="<?php echo JURI::root(TRUE)?>/media/mint/icons/16/chain.png" align="absmiddle" /> <?php echo JText::_('CLINK')?></div>

		<div id="link-pan<?php echo $this->id ?>" class="video-pan-<?php echo $this->id; ?> collapse fade">
			<div class="well">
				<p><?php echo JText::_('WEUNDERSTAND');?>:
					<?php foreach ($this->params->get('params.adapters', array()) as $adapter):?>
						<img align="absmiddle" src="<?php echo JURI::root(TRUE); ?>/components/com_cobalt/fields/video/adapters/icons/<?php echo $adapter;?>.png"
							alt="<?php echo ucfirst($adapter); ?>" title="<?php echo ucfirst($adapter); ?>" />
					<?php endforeach;?>
					<?php echo JText::_('WEUNDERSTAND2');?>
				</p>

				<div id="input_links">
					<?php foreach ($this->link AS $link):?>
						<div class="element-box">
							<input name="jform[fields][<?php echo $this->id;?>][link][]" type="text" value="<?php echo $link; ?>"  id="<?php echo $this->formControl.$this->name;?>" /><img align="absmiddle" src="<?php echo JURI::root(TRUE)?>/media/mint/icons/16/cross-button.png" class="link_delete" onclick="Cobalt.deleteFormElement('link', this);">
						</div>
					<?php endforeach;?>
				</div>

				<?php if(!$this->only_one):?>
					<div id="link-button">
						<button class="btn" type="button" onclick="Cobalt.addFormElement('link', <?php echo $this->id; ?>);">
							<img src="<?php echo JURI::root(TRUE); ?>/media/mint/icons/16/plus-button.png" align="absmiddle">
							<?php echo JText::_('F_ONEMOREVIDEO'); ?>
						</button>
					</div>
				<?php endif;?>
			</div>
		</div>
	<?php endif;?>
</div>

<script type='text/javascript'>
!function($)
{
	$('.video-pan-<?php echo $this->id; ?>').first().collapse('show');

	lnk_count = <?php echo (int)count($this->link);?>;
	emb_count = <?php echo (int)count($this->embed);?>;

	Cobalt.addFormElement = function (type, id)
	{
		if(type == 'embed')
		{
			<?php if($this->params->get('params.embed_max_count', 0)): ?>
				if(emb_count >= <?php echo $this->params->get('params.embed_max_count', 0);?>)
				{
					alert('<?php echo JText::sprintf('CMAXCOUNTEMBED', $this->params->get('params.embed_max_count', 0));?>');
					return false;
				}
			<?php endif;?>
			central_div = 'input_embeds';
			// item_div = 'embed_div element-box';
			input = 'textarea';
			// btn_id = 'embed-button';
			emb_count++;
		}
		else if(type == 'link')
		{
			<?php if($this->params->get('params.link_max_count', 0)): ?>
				if(lnk_count >= <?php echo $this->params->get('params.link_max_count', 0);?>)
				{
					alert('<?php echo JText::sprintf('CMAXCOUNTLINKS', $this->params->get('params.link_max_count', 0));?>');
					return false;
				}
			<?php endif;?>
			central_div = 'input_links';
			// item_div = 'link_div element-box';
			input = 'input';
			// btn_id = 'link-button';
			lnk_count++;
		}
		else
		{
			return;
		}

		var input_div = $(document.createElement("div")).attr({
			'class': 'element-box'
		});


		var input = $(document.createElement(input)).attr({
			 type: "text",
			 name: 'jform[fields][' + id + '][' + type + '][]',
			 rows: 5
			}).appendTo(input_div);

		var close_link = $(document.createElement("img")).attr({
			 'class': 'link_delete',
			 'src': '<?php echo JURI::root(TRUE)?>/media/mint/icons/16/cross-button.png'
			}).appendTo(input_div);

		close_link.on('click', function(){
			Cobalt.deleteFormElement(type, this);
		});

		$("#"+central_div).append(input_div);

	}

	Cobalt.deleteFormElement = function (type, second)
	{
		if(type == 'embed')
		{
			emb_count--;
		}
		else if(type == 'link')
		{
			lnk_count--;
		}
		console.log(second);
		$(second).parent('div.element-box').remove();
		//el.remove();
	}
}(jQuery);
</script>