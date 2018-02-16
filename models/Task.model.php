<?php

class Task Extends Model
{
    public $error = false;
    public $table = '_tasks';
    public $rowsPerPage=30;
    public $pageBar;

    public function  __construct()
    {
        parent::__construct();
        $this->table = $this->_CONF['db']['prefix'] . $this->table;
    }

    public function createBookingTask($booking_id,$deadline_at, $task)
    {
        $query = "INSERT INTO {$this->table} set
        			      type = 'booking',
        			      group_id = {$this->session['pcms_user_group']},
        			      booking_id = {$booking_id},
        			      creator_id = {$this->session['pcms_user_id']},
        			      created_at = NOW(),
        			      deadline_at='{$deadline_at}',
        			      task = '".$this->VALIDATOR->qstr($task)."',
        			      status = 0";
        $result = $this->CONN->_query($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
        $id = $this->CONN->Insert_ID();
        $this->addActivityLog('Created Booking Task',"User({$this->session['pcms_user_id']}) Added New Task({$id}) with deadline at({$deadline_at}) to the Booking({$booking_id})",$this->session['pcms_user_id'],$task);
        return $id;
    }

    public function createHotelTask($deadline_at, $task)
    {
        $query = "INSERT INTO {$this->table} set
        			      type = 'hotel',
        			      group_id = {$this->session['pcms_user_group']},
        			      booking_id = 0,
        			      creator_id = {$this->session['pcms_user_id']},
        			      created_at = NOW(),
        			      deadline_at='{$deadline_at}',
        			      task = '".$this->VALIDATOR->qstr($task)."',
        			      status = 0";
        $result = $this->CONN->_query($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
        $id = $this->CONN->Insert_ID();
        $this->addActivityLog('Created Hotel Task',"User({$this->session['pcms_user_id']}) Added New Task({$id}) with deadline at({$deadline_at})",$this->session['pcms_user_id'],$task);
        return $id;
    }

    public function changeTaskStatus($task_id){

        $query = "SELECT * FROM {$this->table} WHERE id={$task_id}";
        $task = $this->CONN->GetRow($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());

        if($task){
            $new_status=1-$task['status'];
            if($new_status==1){
                $query = "UPDATE {$this->table} SET
        			 	 status={$new_status},
        			 	 executor_id={$this->session['pcms_user_id']},
        			 	 executed_at=NOW()
        			 	 WHERE id={$task_id}";
            }else{
                $query = "UPDATE {$this->table} SET
        			 	 status={$new_status},
        			 	 executor_id={$this->session['pcms_user_id']},
        			 	 executed_at=NULL
        			 	 WHERE id={$task_id}";
            }
            $this->CONN->Execute($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
            $this->addActivityLog('Changed Booking Task Status',"User({$this->session['pcms_user_id']}) Changed Task({$task_id}) Status to({$new_status})",$this->session['pcms_user_id'],$task);
            return true;
        }else{
            $this->FUNC->ServerError(__FILE__, __LINE__,"can't change task({$task_id}) status");
            return false;
        }

    }

    public function getTasks($where_clause="",$get_paged=true,$page=1)
    {
        $query = "SELECT * FROM {$this->table} {$where_clause} ORDER BY id DESC";

        if ($get_paged) {
            $data = $this->CONN->PageExecute($query, $this->rowsPerPage, $page) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
        } else {
            $data = $this->CONN->Execute($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
        }
        $this->pageBar =$this->getPageBar($data);
        return $data->getRows();
    }

    private function getPageBar($data){
        $parts = parse_url($_SERVER[REQUEST_URI]);
        $queryParams = array();
        parse_str($parts['query'], $queryParams);
        unset($queryParams['p']);
        $queryString = http_build_query($queryParams);
        $SELF_FILTERED = $parts['path'] . '?' . $queryString;
        return $this->FUNC->DrawPageBar_($SELF_FILTERED . "&p=", $data);
    }



    public function getBookingTasks($booking_id)
    {
        $query = "SELECT * FROM {$this->table} WHERE type='booking' AND booking_id={$booking_id} ORDER BY id DESC";
        $data = $this->CONN->Execute($query) or $this->FUNC->ServerError(__FILE__, __LINE__,$this->CONN->ErrorMsg());
        $results = $data->getRows();
        return $results;
    }

    public function getTaskByID($task_id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id={$task_id}";
        $results = $this->CONN->GetRow($query) or $this->FUNC->ServerError(__FILE__, __LINE__,$this->CONN->ErrorMsg());
        return $results;
    }

    public function deleteBookingTask($task_id)
    {
        $task=$this->getTaskByID($task_id);
        $query = "DELETE FROM {$this->table}
                      WHERE id={$task_id}";
        $this->CONN->Execute($query) or $this->FUNC->ServerError(__FILE__, __LINE__,$this->CONN->ErrorMsg());
        $this->addActivityLog('Delete Booking Task',"User({$this->session['pcms_user_id']}) Deleted Task({$task_id})",$this->session['pcms_user_id'],$task['task']);

        return true;
    }

    public function changeTaskDeadline($task_id,$deadline_at){
        $task=$this->getTaskByID($task_id);
        if($task['deadline_at']!=$deadline_at){
            $query = "UPDATE {$this->table} SET
        			 	 deadline_at='{$deadline_at}'
        			 	 WHERE id={$task_id}";
            $this->CONN->Execute($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
            $this->addActivityLog('Changed Task Deadline',"User({$this->session['pcms_user_id']}) Changed Task ({$task_id}) Deadline to {$deadline_at}",$this->session['pcms_user_id'],$task['deadline_at']."=>".$deadline_at);

        }
        return true;
    }

    public function changeTask($task_id,$task_job){
        $task=$this->getTaskByID($task_id);

        if($task['task']!=$task){
            $query = "UPDATE {$this->table} SET
                      task='".$this->VALIDATOR->qstr($task_job)."'
        			  WHERE id={$task_id}";
            p($query);
            $this->CONN->Execute($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
            $this->addActivityLog('Changed Task Job',"User({$this->session['pcms_user_id']}) Changed Task({$task_id}) Job to {$task_job}",$this->session['pcms_user_id'],$task['task']);
        }
        return true;
    }
} 