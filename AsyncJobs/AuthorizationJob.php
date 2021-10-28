<?php
namespace Authorization\AsyncJobs;
class AuthorizationJob extends \Core\AsyncJobController{
    /**
     * @ScheduleJob('interval'=>300)
     */
    function refreshUserData()
    {
        (new \Authorization\Authorization())->refreshUserData();
    }
}