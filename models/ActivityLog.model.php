<?php

/**
 * Created by PhpStorm.
 * User: nino
 * Date: 6/13/2016
 * Time: 12:20 PM
 */
class ActivityLog Extends Model
{
    public function getLastActivityLogs($count=0)
    {
        if($count!=0){
            $limit="LIMIT ".$count;
        }
        $query = "SELECT *
                  FROM {$this->_CONF['db']['prefix']}_activity_log
                  ORDER BY id DESC
                  {$limit}";
        $result = $this->CONN->Execute($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
        return $this->mapResults($guests = $result->GetRows());
    }

   /* public_function_addActivityLog($action, $description, $administrator_id, $log)
    {
        gatanilia modelshi
    }*/

    /*public_function_addGuestActivityLog($action, $description, $guest_id, $log)
    {
        gatanilia modelshi
    }*/
} 