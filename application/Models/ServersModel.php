<?php
/**
 * ProjectName: HakugyokuSoulMcServerPanel.
 * Author: SaigyoujiYuyuko
 * QQ: 3558168775
 * Date: 2018/10/27
 * Time: 7:51
 *
 * Copyright © 2018 SaigyoujiYuyuko. All rights reserved.
 */

namespace application\models;

use HakugyokuSoulMVC\base\Model;

class ServersModel extends Model{
    public function AddDaemon(array $url){
        $url = array_values($url);

        if (count($url) != 5){
            return "-1";
        }

        for ($i = 0; $i < count($url); $i++){
            if (@$url[$i] == "" || @$url[$i] == null || @$url[$i] ==  " "){
                return "-1";
            }
        }

        $name = $url[0];
        $key = $url[1];
        $fqdn = $url[2];
        $ajaxHost = $url[3];
        $OS_type = $url[4];

        $this->setTable("daemon");
        $this->insert(array("name","key","fqdn","ajax_host","OS_type"),array($name,$key,$fqdn,$ajaxHost,$OS_type));

        return "0";
    }
}
