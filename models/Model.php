<?php


class Model
{

    private $activityLogTable='_activity_log';

    public function __construct()
    {
        global $_CONF, $CONN, $VALIDATOR, $TEXT, $FUNC, $_SESSION;
        $this->_CONF = $_CONF;
        $this->CONN = $CONN;
        $this->VALIDATOR = $VALIDATOR;
        $this->TEXT = $TEXT;
        $this->FUNC = $FUNC;
        $this->session = $_SESSION;
        $this->activityLogTable=$this->_CONF['db']['prefix'].$this->activityLogTable;
    }

    public function  mapResults($results, $param='id')
    {
        foreach ($results as $result) {
            $return[$result[$param]] = $result;
        }
        return $return;
    }


    /**
     * @param string $action
     *
     * @param string $description
     *
     * @param int $administrator_id Creator ID
     *
     * @param string $log
     *
     * @return  Int id of added activity log
     *
     */
    public function addActivityLog($action, $description, $administrator_id, $log)
    {
        $query = "INSERT INTO {$this->activityLogTable} set
			      action = '{$action}',
			      description = '{$description}',
			      administrator_id={$administrator_id},
			      ip = '{$_SERVER['REMOTE_ADDR']}',
				  log = '{$log}',
				  date=NOW()";
        $result = $this->CONN->_query($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
        return (int)$this->CONN->Insert_ID();
    }

    public function addGuestActivityLog($action, $description, $guest_id, $log)
    {
        $query = "INSERT INTO {$this->_CONF['db']['prefix']}_guests_activity_log set
			      action = '{$action}',
			      description = '{$description}',
			      guest_id={$guest_id},
			      ip = '{$_SERVER['REMOTE_ADDR']}',
				  log = '{$log}',
				  date=NOW()";
       
        $result = $this->CONN->_query($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
        return (int)$this->CONN->Insert_ID();
    }
} 