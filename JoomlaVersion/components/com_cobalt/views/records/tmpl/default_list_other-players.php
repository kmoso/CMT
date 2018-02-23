<?php
/**
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author: Vadim Kozhukhov
 * Author Website: http://www.torbara.com/
 * @copyright Copyright (C) 2015 Torbara (http://www.tobara.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted access'); ?>

<div class="other-players-wrap">

<div class="uk-container uk-container-center alt">
    <div class="uk-grid">
        <div class="uk-width-1-1">
            <h3 class="other-post-title">Other <span>Players</span></h3>
            <div class="uk-slidenav-position player-slider" data-uk-slider>
                <div class="uk-slider-container">
                    <div class="player-slider-btn">
                        <a href="" class="uk-slidenav uk-slidenav-previous" data-uk-slider-item="previous"></a>
                        <a href="" class="uk-slidenav uk-slidenav-next" data-uk-slider-item="next"></a>
                    </div>
                    <ul class="uk-slider uk-grid uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-4">
                        <?php foreach ($this->items AS $item): ?>

                        <li class="player-item">
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
                                        $img_src = $field->value["image"];
                                    ?>
                                    <div class="uk-cover-background" style="background-image: url(<?php echo $img_src; ?>); height: 310px; ">
                                        <img class="uk-invisible" src="<?php echo $img_src; ?>" width="600" height="400" alt="<?php echo $item->fields_by_id[2]->result; ?>">
                                    </div>
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
                                            <a href="<?php echo JRoute::_($item->url); ?>">
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
                        </li>

                        <?php endforeach; ?>
                    </ul>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
