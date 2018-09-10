<?php
/**
 * Created by PhpStorm.
 * User: matri
 * Date: 10.09.2018
 * Time: 18:30
 */

namespace Authorization;


use Core\DB;

class Permissions
{
    protected $data=[];
public function __construct(int $userId)
{
    $this->load($userId);
}

    private function load(int $userId)
    {
        $data=DB::get("SELECT * FROM user_permission up WHERE id_user = ?",[$userId]);
        foreach ($data as $row){
            $this->data[$row['name']]=1;
        }
    }
}