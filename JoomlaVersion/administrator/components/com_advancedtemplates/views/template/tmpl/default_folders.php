<?php
/**
 * @package         Advanced Template Manager
 * @version         2.1.2
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2016 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

/**
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
ksort($this->files, SORT_STRING);
?>

<ul class='nav nav-list directory-tree'>
	<?php foreach ($this->files as $key => $value): ?>
		<?php if (is_array($value)): ?>
			<li class="folder-select">
				<a class='folder-url nowrap' data-id='<?php echo base64_encode($key); ?>' href=''>
					<span class='icon-folder-close'>&nbsp;<?php $explodeArray = explode('/', $key);
						echo end($explodeArray); ?></span>
				</a>
				<?php echo $this->folderTree($value); ?>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>
