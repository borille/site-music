<?php

class My_View_Helper_CalendarHelper extends Zend_View_Helper_Abstract
{

    public function CalendarHelper( $nome )
    {
        $src = INCLUDE_PATH . '/img/calendar.png';
		
        echo "<img style='vertical-align: bottom; cursor: pointer;' width='25px' src='$src' onClick=\"displayCalendar(document.getElementById('$nome'),'dd/mm/yyyy',this)\" target='_self' title='Abrir Calendário'/>";
    }

}
?>