<?php

class User Extends Model
{

    public function getAllUsers()
    {
        $query = "SELECT *
                  FROM {$this->_CONF['db']['prefix']}_users
                  ORDER BY id DESC";
        $result = $this->CONN->Execute($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
        return $this->mapResults($guests = $result->GetRows());
    }


} 