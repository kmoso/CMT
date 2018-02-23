<?php
/**
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author: Vadim Kozhukhov
 * Author Website: http://www.torbara.com/
 * @copyright Copyright (C) 2015 Torbara (http://www.tobara.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
$k = $p1 = 0;
$params = $this->tmpl_params['list'];
$core = array('type_id' => 'Type', 'user_id','','','','','','','','', );
JHtml::_('dropdown.init');
$exclude = $params->get('tmpl_params.field_id_exclude');
settype($exclude, 'array');
foreach ($exclude as &$value) {
	$value = $this->fields_keys_by_id[$value];
}


$commentsAPI = JPATH_SITE . '/components/com_jcomments/jcomments.php';
if (file_exists($commentsAPI)) {
    require_once($commentsAPI);
}

?>
<div class="our-team-main-wrap">
<div class="uk-container uk-container-center">
<div class="uk-grid" data-uk-grid-match>
{module 155}
<?php foreach ($this->items AS $item): ?>

<div class=" uk-width-large-1-4 uk-width-medium-1-3 uk-width-small-1-2 player-item <?php echo "tt_".md5($item->fields_by_id[26]->result); ?>">
    <div class="player-article">
        <div class="wrapper">
        <div class="img-wrap">
            <div class="player-number">
                <span>
                    <?php $field = $item->fields_by_id[36]; // Player Number ?>
                    <?php echo $field->result; ?>
                </span>
            </div>
            <div class="bio"><span><a href="<?php echo JRoute::_($item->url);?>">bio</a></span></div>
            <?php 
                $field = $item->fields_by_id[1]; // Avatar
                echo $field->result;
            ?>
            <ul class="socials">
                <?php if(@$item->fields_by_id[37]): ?><li class="twitter"><?php $field = $item->fields_by_id[37]; ?><?php echo $field->result; ?></li><?php endif; ?>
                <?php if(@$item->fields_by_id[38]): ?><li class="facebook"><?php $field = $item->fields_by_id[38]; ?><?php echo $field->result; ?></li><?php endif; ?>
                <?php if(@$item->fields_by_id[39]): ?><li class="google-plus"><?php $field = $item->fields_by_id[39]; ?><?php echo $field->result; ?></li><?php endif; ?>
                <?php if(@$item->fields_by_id[40]): ?><li class="pinterest"><?php $field = $item->fields_by_id[40]; ?><?php echo $field->result; ?></li><?php endif; ?>
                <?php if(@$item->fields_by_id[41]): ?><li class="linkedin"><?php $field = $item->fields_by_id[41]; ?><?php echo $field->result; ?></li><?php endif; ?>
            </ul>
        </div>
        <div class="info">
            <div class="name">
                <h3>
                    <a href="<?php echo JRoute::_($item->url);?>">
                        <?php $field = $item->fields_by_id[2]; // Player Name ?>
                        <?php echo $field->result; ?>
                    </a>
                </h3>
            </div>
            <?php if(@$item->fields_by_id[26]): ?>
            <div class="position">
                <?php $field = $item->fields_by_id[26]; // Player Position ?>
                <?php echo $field->result; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    </div>
</div>

<?php endforeach; ?>
</div>
</div>

{module 156}
</div>

