<?php
        class NBGCCalendar {
            
            public function __construct(){
                $this->calendar = htmlentities($_SERVER['PHP_SELF']);
            }

            private $dayLabels = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
            private $currentYear=0;
            private $currentMonth=0;
            private $currentDay=0;
            private $currentDate=null;
            private $daysInMonth=0;
            private $naviHref= null;

            public function show(){
                $year  = null;
                $month = null;
         
                if(null==$year && isset($_GET['year'])){
                    $year = $_GET['year'];
                }else if(null==$year){
                    $year = date("Y",time());  
                }          
         
                if(null==$month && isset($_GET['month'])){
                    $month = $_GET['month'];
                }else if(null==$month){
                    $month = date("m",time());
                }                  
                $this->currentYear=$year;     
                $this->currentMonth=$month;  
                $this->daysInMonth=$this->daysInMonth($month,$year);  
                $content='<div class="calendar">';
                $content.='<div class="calendar-header">'.$this->createNavi().'</div>';
                $content.='<div class="calendar-content">'
                    .'<ul class="label">'
                    .$this->createLabels()
                    .'</ul>'
                    .'<ul class="dates">';    
                                 
                $weeksInMonth = $this->weeksInMonth($month,$year);
                // Create weeks in a month
                for( $i=0; $i<$weeksInMonth; $i++ ){
                                     
                    //Create days in a week
                    for($j=0;$j<=6;$j++){
                        $content.=$this->showDay($i*7+$j);
                    }
                }
                                 
                $content.='</ul>';
                $content.='</div>';
                $content.='</div>';

                return $content;
            }

            private function showDay($cellNumber){
         
                if($this->currentDay==0){
                    $firstDayOfTheWeek = date('N',strtotime($this->currentYear.'-'.$this->currentMonth.'-01'));
                     
                    if(intval($cellNumber) == intval($firstDayOfTheWeek)){
                        $this->currentDay=1;
                    }
                }
         
                if( ($this->currentDay!=0)&&($this->currentDay<=$this->daysInMonth) ){
                    $this->currentDate = date('Y-m-d',strtotime($this->currentYear.'-'.$this->currentMonth.'-'.($this->currentDay)));
                    $cellContent = $this->currentDay;
                    $this->currentDay++;   
             
                }else{
                    $this->currentDate =null;
                    $cellContent=null;
                }

                return '<li id="li-'.$this->currentDate.'" class="'
                    .($cellNumber%7==1?' start ':($cellNumber%7==0?' end ':' '))
                    .($cellContent==null?'mask':'').'">'
                    .$cellContent.'</li>';
            }
     

            private function createNavi(){
                $nextMonth = $this->currentMonth==12?1:intval($this->currentMonth)+1;
                $nextYear = $this->currentMonth==12?intval($this->currentYear)+1:$this->currentYear;
                $preMonth = $this->currentMonth==1?12:intval($this->currentMonth)-1;
                $preYear = $this->currentMonth==1?intval($this->currentYear)-1:$this->currentYear;
         
                return
                    '<div class="navigation">'.
                        '<a class="prev" href="'.$this->naviHref.'?month='.sprintf('%02d',$preMonth).'&year='.$preYear.'">Prev</a>'.
                            '<span class="title">'.date('M Y',strtotime($this->currentYear.'-'.$this->currentMonth.'-1')).'</span>'.
                        '<a class="next" href="'.$this->naviHref.'?month='.sprintf("%02d", $nextMonth).'&year='.$nextYear.'">Next</a>'.
                    '</div>';
            }

            private function createLabels(){  
                 
                $content='';
                foreach($this->dayLabels as $index=>$label){
                    $content.='<li class="'.($label==6?'end title':'start title').' title">'.$label.'</li>';
                }
                return $content;
            }

            private function weeksInMonth($month=null,$year=null){
         
                if( null==($year) ) {
                    $year =  date("Y",time()); 
                }
         
                if(null==($month)) {
                    $month = date("m",time());
                }
         
                // find number of days in this month
                $daysInMonths = $this->daysInMonth($month,$year);
                $numOfweeks = ($daysInMonths%7==0?0:1) + intval($daysInMonths/7);
                $monthEndingDay= date('N',strtotime($year.'-'.$month.'-'.$daysInMonths));
                $monthStartDay = date('N',strtotime($year.'-'.$month.'-01'));
         
                if($monthEndingDay<$monthStartDay){
                    $numOfweeks++;
                }
         
                return $numOfweeks;
            }

            private function daysInMonth($month=null,$year=null){
         
                if(null==($year))
                    $year =  date("Y",time()); 
 
                if(null==($month))
                    $month = date("m",time());

                return date('t',strtotime($year.'-'.$month.'-01'));
            }

        }
    ?>