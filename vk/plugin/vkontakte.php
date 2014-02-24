<?php
ini_set("display_errors", "on");
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Import library dependencies
jimport('joomla.event.plugin');
 
class plgContentVkontakte extends JPlugin
{
    public function onContentPrepare($context, &$row, &$params, $page = 0)
    {
        // /Если нету параметра в адресной строке делаем 0
        if(empty($_GET["pg_vk_limitstart"])) $_GET["pg_vk_limitstart"] = 0;
        if(stripos($row->text, "{vk_render_users}") !== FALSE ) {
        $db =& JFactory::getDBO();
        $sql = "SELECT * FROM `groupvk` ORDER BY `id` ASC LIMIT ".$_GET["pg_vk_limitstart"].", 50";
        $db->setQuery($sql);
        $options = $db->loadObjectList();

        $sql2 = "SELECT * FROM `groupvk`"; 
        $db->setQuery($sql2);
        $count = count($db->loadObjectList());


        $i = 0;
        ?><div class="vk_render_all_users"><?php
        // Выводим пользователей
        foreach ($options as $info){
            $i++;
            ?><div class="vk_render_user"><?php
                ?><div class="vk_render_user_img"><a href="http://vk.com/id<?php print $info->{"idvk"};?>"  target="_blank"><img src="<?php
                    print $info->{"foto"};
                    ?>" /></a></div><?php
                ?><div class="vk_render_user_name"><a href="http://vk.com/id<?php print $info->{"idvk"};?>"  target="_blank"><?php
                    print $_GET["pg_vk_limitstart"]+$i.". ".$info->{"name"}." ".$info->{"surname"};
                    ?></a></div><?php
            ?></div><?php
        }
        ?></div><?php
        
  // Загружаем содержимое, если оно уже не было получено:

      jimport('joomla.html.pagination');
      $_pagination = new JPagination($count, $_GET["pg_vk_limitstart"], 50, "pg_vk_" );
      print "<table class='pg_div'><tr><td>".$_pagination->getPagesLinks()."</td></tr></table>";
        
        
        }
        $row->text = str_replace("{vk_render_users}", "", $row->text);
    }
}
ini_set("display_errors", "off");
?>