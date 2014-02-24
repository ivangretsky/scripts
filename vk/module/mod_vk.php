<?php
   
/**
 * @package     Joomla.Tutorials
 * @subpackage  Module
 * @copyright   (C) 2012 http://jomla-code.ru
 * @license     License GNU General Public License version 2 or later; see LICENSE.txt
 */
   
// No direct access to this file
defined('_JEXEC') or die;




$db =& JFactory::getDBO();
$sql = "SELECT * FROM `groupvk` ORDER BY rand() ASC LIMIT ".$params->get('count_render_users'); 
$db->setQuery($sql);
$options = $db->loadObjectList();

$sql2 = "SELECT * FROM `groupvk`"; 
$db->setQuery($sql2);
$count = count($db->loadObjectList());


$i = 0;
print "<div style='border:none;!important;background: none!important;width:200px;'>";
foreach ($options as $info){
    if($i==3){
        print "<br/>";
        $i = 0;
    }
    
    ?><div class="vk_div">
    <a href='http://vk.com/id<?php print $info->{"idvk"};?>'  target='_blank'>
    <img src='<?php print $info->{"foto"};?>' class='vk_img' />
    <span class='vk_name'><?php print $info->{"name"};?></span>
    </a>
    </div><?php
    $i++;
}
print "</div>";
if($params->get('group_vk_link')!="")print "<a href='http://vk.com/".$params->get('group_vk_link')."' class='link_col' target='_blank'><span>Количество участников: ".$count."</span></a>";
if($params->get('group_link')!="")print "<a href='".$params->get('group_link')."' class='link_group' target='_blank'><span>Посмотреть всех участников</span></a>";

?><div class="vk_div_html"><?php
if($params->get('html_code')!="")print $params->get('html_code');
?></div><?php


?>
