<?php

/**
 * Created by PhpStorm.
 * User: nino
 * Date: 6/13/2016
 * Time: 12:20 PM
 */
class Guest Extends Model
{

    public function getAllGuests()
    {
        $query = "SELECT id,id_number,first_name,last_name,type,tax,ind_discount,birth_day,telephone,email,citizenship,country,address,id_scan,extra_doc,balance,ip,group_id,reg_type,comment,created_at,updated_at, publish
                  FROM {$this->_CONF['db']['prefix']}_guests
                  ORDER BY id DESC";
        $result = $this->CONN->Execute($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
        return $this->mapResults($guests = $result->GetRows());
    }

    public function getLast($count=0)
    {
        if($count!=0){
            $limit="LIMIT ".$count;
        }
        $query = "SELECT id,id_number,first_name,last_name,type,tax,ind_discount,birth_day,telephone,email,citizenship,country,address,id_scan,extra_doc,balance,ip,group_id,reg_type,comment,created_at,updated_at, publish
                  FROM {$this->_CONF['db']['prefix']}_guests
                  WHERE reg_type='external'
                  ORDER BY id DESC
                  {$limit}";
        $result = $this->CONN->Execute($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
        return $this->mapResults($guests = $result->GetRows());
    }

} 