<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
$app = JFactory::getApplication();


if($app->input->getCmd('option') != 'com_cobalt') return;
if($app->input->getCmd('view') != 'records') return;
if(!$app->input->getInt('user_id')) return;

include_once JPATH_ROOT. '/components/com_cobalt/api.php';

$section = ItemsStore::getSection($app->input->getInt('section_id'));

if(!$section->params->get('events.subscribe_user')) return;


$list = CUsrHelper::getFolowers($app->input->getInt('user_id'), $section);

if(!$list) return;

$list = array_keys($list);
$list = array_splice($list, 0, $params->get('limit', 10));

require JModuleHelper::getLayoutPath('mod_cobalt_followers', $params->get('layout', 'default'));

?>