<?php
/**
 * Torbara Maxx-Fitness Template for Joomla, exclusively on Envato Market: http://themeforest.net/user/torbara
 * @encoding     UTF-8
 * @version      1.0
 * @copyright    Copyright (C) 2015 Torbara (http://torbara.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@torbara.com)
 */

defined('_JEXEC') or die; ?>

<div class="trainers-module tm-trainers-slider <?php echo $moduleclass_sfx; ?>">
    <div class="trainer-wrapper">
    <div data-uk-slideset="{default: 1, animation: 'fade', duration: 400}">
        <div class="trainer-top">
            <div class="trainers-btn">
               <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slideset-item="previous"></a>
               <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slideset-item="next"></a>
            </div>
        <h3><?php echo JText::_('CMT TEAM'); ?></h3>
        </div>
        <ul class="uk-grid uk-slideset">
            <?php foreach ($list as $item) : ?>
                <li><?php echo $item->introtext; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    </div>
</div>