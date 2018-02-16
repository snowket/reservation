<?php


class Statistic Extends Model
{
    public function getUsedRoomsCount($start_date, $end_date)
{

    $startDate = strtotime($start_date);
    $endDate = strtotime($end_date);
    $total_rooms_count=$this->getRoomsCount();

    $query = "SELECT date, COUNT(date) AS count
                  FROM {$this->_CONF['db']['prefix']}_booking_daily
                  WHERE active=1 AND date>='".$start_date."' AND date<='".$end_date."' AND (type='check_in' OR type='in_use')
                  GROUP BY date
                  ORDER BY date ASC";
    $result = $this->CONN->Execute($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
    $used_rooms_count=$this->mapResults($result->GetRows(),'date');
    while ($startDate <= $endDate) {
        $day_date=date('Y-m-d',$startDate);
        $percent=number_format($used_rooms_count[$day_date]['count']/($total_rooms_count/100),2);
        $return[$day_date]=array(
            'used_rooms_count' =>(int)$used_rooms_count[$day_date]['count'],
            'total_rooms_count'=>$total_rooms_count,
            'percent'=>$percent,
        );
        $startDate = strtotime('+1 day', $startDate);
    }
    return $return;
}
    public function getFullUsedRoomsCount($start_date, $end_date)
    {

        $startDate = strtotime($start_date);
        $endDate = strtotime($end_date);
        $total_rooms_count=$this->getRoomsCount();

        $query = "SELECT date, COUNT(date) AS count
                  FROM {$this->_CONF['db']['prefix']}_booking_daily
                  WHERE active=1 AND date>='".$start_date."' AND date<='".$end_date."' AND (type='check_in' OR type='in_use')
                  GROUP BY date
                  ORDER BY date ASC";
        $result = $this->CONN->Execute($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
        $used_rooms_count=$this->mapResults($result->GetRows(),'date');
        while ($startDate <= $endDate) {
            $day_date=date('Y-m-d',$startDate);
            $day_xdate=date('Y-m',$startDate);
            $day_ydate=date('d',$startDate);
            $percent=number_format($used_rooms_count[$day_date]['count']/($total_rooms_count/100),2);
            $return[$day_xdate][$day_ydate]=array(
                'used_rooms_count' =>(int)$used_rooms_count[$day_date]['count'],
                'total_rooms_count'=>$total_rooms_count,
                'percent'=>$percent,
            );
            $startDate = strtotime('+1 day', $startDate);
        }
        return $return;
    }

    public function getRoomsCount(){
        $query = "SELECT COUNT(id) AS count
                  FROM {$this->_CONF['db']['prefix']}_rooms
                  WHERE publish=1";
        $result = $this->CONN->GetRow($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
        return $result['count'];
    }

} 