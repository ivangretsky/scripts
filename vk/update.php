<?php
/*
 * VKupdate
 * Скрипт для обновления данных в таблице из группы вконтакте
 * gid - id группы
 * r  - render
 * d  - data
 * v  - vk
 * u  - user
 * a  - add user
 * t  - таблица
 * up - обновление
 * del - удаление
update.php?q=a_u,up_u,del_u,r_a_u,r_u_t&gid=34792066
 * a_u,   Добавляет новых пользователей
 * up_u,  Обновление пользователей
 * del_u, Удаление не нужных пользователей
 * r_a_u, Вывести Добавленых, обновленных и удаленных пользователей
 * r_u_t  Вывести таблицу
 * gid - id группы
 */

set_time_limit(0);
$secret = 'e5Fv547h85yQ2Hs5Oful'; //секретный ключ вашего приложения
$idapp = '4084400'; //id вашего приложения
// Подключаем файлы классов
require '/class/render.class.php';
require '/class/user.management.class.php';

// Создаем объект для работы с API с вконтактов
require '/class/vkapi.class.php';
$VK = new vkapi( $idapp, $secret); 

// Получаем список участников группы, один запрос 1000 участников 34792066
$groups = $VK->api( 'groups.getMembers', array('gid'=>$_GET["gid"]));



// Получаем Id пользователей
foreach ($groups["response"]["users"] as $id) $ids .= ',' .$id;
// Режем запятую лишнию
$ids = substr($ids, 1, strlen($ids)-1);
$info_user = $VK->get_info_user(array("fields"  => "photo_50, photo_100, photo_200_orig, photo_200, photo_400_orig, photo_max, photo_max_orig", "user_ids" => $ids, "v" => "5.5"));


// Команды
$q =  explode(",", $_GET["q"]);

// Создаем нужные объекты
$render = new render();
$manager = new usermanager();
foreach ($q as $_q){
 
    // Выводим полученые данные из вк
    if($_q=="r_d_v_u"){
        $render->render_vk_users("Данные из вконтакта",$info_user["response"]);
    }
    
    // Добавляем пользователей
    elseif($_q=="a_u"){
        $manager->addusers($info_user["response"]);
    }
    
    // Выводим пользователей из базы данных
    elseif($_q=="r_u_t"){
        $manager->render();
    }
    
    // Выводим новым пользователей или обновленных
    elseif($_q=="r_a_u"){
        $manager->render_table_array($render);
         
    }
    // Обновляем пользователей
    elseif($_q=="up_u"){
        $manager->updateusers($info_user["response"]);
         
    }
    // Удаление не нужных
    elseif($_q=="del_u"){
        $manager->deleteusers($info_user["response"]);
    }
    
}
?>
        
    
