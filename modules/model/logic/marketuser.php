<?php
/**
 * Created by PhpStorm.
 * User: LegendFox
 * Date: 2016/4/22 0022
 * Time: 下午 3:28
 */

namespace model\logic;


use model\database\Table;

class MarketUser
{
    public function approval($status,$user_id,$address,$lng,$lat){
        $table  = new Table('market_user');
        $point  = "Point($lng $lat)";
        $pdo    = $table->getConnection();
        $point  = $pdo->quote($point);
        $format = "PointFromText(%s)";
        $geo_address = sprintf($format,$point);
        $sql    = "UPDATE market_user SET status = ?, address = ?, geo_address = $geo_address WHERE user_id = ?";
        $statement = $pdo->prepare($sql);
        return $statement->execute(array($status,$address,$user_id));
    }
}