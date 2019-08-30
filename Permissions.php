<?php

namespace Authorization;


use User\Repository\UserRepository;

class Permissions
{
    protected $data = [];

    public function __construct(int $userId)
    {
        $this->data = (new UserRepository())->getPermissions($userId);
    }

    static function readStructure()
    {
        $data = [];
        $groups = [];
        $modules = scandir(__DIR__.'/../');
        foreach ($modules as $module) {
            if ($module == '.' || $module == '..') {
                continue;
            }
            $filename = __DIR__.'/../'.$module.'/permissions.xml';
            if (is_file($filename)) {
                $xml = simplexml_load_string(file_get_contents($filename));
                foreach ($xml->group as $group) {
                    $groups[$group->name->__toString()] = $group;
                    foreach ($group->permission as $permission) {
                        $data[$group->name->__toString()][$permission->name->__toString()] = $permission;
                    }
                }
            }
        }
        $ret = [];
        foreach ($groups as $group) {
            $groupArray = (object)(array)$group;
            unset($groupArray->permission);
            $groupArray->children = array_values($data[$group->name->__toString()] ?? []);
            $ret[] = $groupArray;
        }
        return $ret;
    }

    public function can(string $group, string $permission)
    {
        return isset($this->data[$group]) && isset($this->data[$group][$permission]) && $this->data[$group][$permission];
    }
}