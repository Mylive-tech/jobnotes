<?php 
require_once('../../settings.php');
//piechart ajax request
if($_POST['customerids']) {
		$scsinusr = "<form method='post' name='frmListing'><table class='table table-striped table-bordered table-hover table-checkable table-responsive'><thead class='cf'><tr><th data-class='expand'>Username/IVR Staff ID</th><th data-hide='phone'>Name</th><th data-hide='phone'>Date</th><th data-hide='phone'>Time</th><th data-hide='phone'>Clock Action</th><!--th>View Full Log</th--></tr></thead><tbody>";
			$sids=$_POST['customerids'];
			$sid_array=explode(',', $sids);
			$strSql = "SELECT s.*,st.label as userRole FROM " . TBL_STAFF . " s inner join " . TBL_STAFFTYPE . " st on s.user_type=st.id where s.username IN($sids) and s.user_type >1  and s.site_id='" . $_SESSION['site_id'] . "'";
				$objRs = $objDatabase->dbQuery($strSql); 	
			if($objRs): // Check for the resource exists or not
                $intI=1;
				foreach($sid_array as $sa){
                $staff_log = $objFunctions->getStaffLogDetails($sid_array);
			while($objRow = $objRs->fetch_object())  // Fetch the result in the object array
			{
				
			    $strStatus = ($objRow->status)?'0':'1';
				
				if($intI++%2==0)  // Condition for alternate rows color
				   $strCss='evenTr';
				else
				   $strCss='oddTr';
                $lastUser = '';
                if ($staff_log[$objRow->username] <> '' ) { 
                $rowCount=0;
                    
                            $lastUser = $objRow->username;
                            $rowCount++;
							$ivrdatetimestatus = explode(',', $staff_log[$objRow->username]);      
                            $scsinusr .= "<tr> 
                                <td align='center'>".$objRow->username."</td><td align='center'>".$objRow->f_name." ".$objRow->l_name."</td><td align='center'>".$ivrdatetimestatus[0]."</td><td align='center'>".$ivrdatetimestatus[1]."</td><td align='center'>".$ivrdatetimestatus[2]."</td><!--td align='center'><a href='".ISP::AdminUrl('reports/report_ivr_log/?staffid='.$objRow->username)."'>View</a></td--></tr>";        
                }                     
			}
			}               
			 else:
			       $scsinusr .= "<tr><td  class='errNoRecord'>No Record Found!</td></tr>";
			 endif;
 
       $scsinusr .= '</tbody></table></form>';
		echo $scsinusr ;
}
/*session_start();
$con = mysqli_connect("localhost","jobnotuser","j0bn0te5$1","jobnotesnew");
if ($con->connect_errno) {
    printf("Connect failed: %s\n", $con->connect_error);
    exit();
}
else
{
	$sids=$_POST['customerids'];
	$sid_array=explode(',', $sids);
	$strSql="SELECT s.*,st.label as userRole FROM tbl_staff s inner join tbl_staff_type st on s.user_type=st.id where s.username IN($sids) and s.user_type >1 and s.site_id='" . $_SESSION['site_id'] . "'";
	$res = mysqli_query($con, $strSql);
	$json_output = file_get_contents('http://www.ivrhq.me/applications/10042/api/select.php?account_key=3KoD1T56&table=staff_activity');
	$staff_array = json_decode($json_output, true);
	$staff_data = array();
	foreach($staff_array as $key => $val) {
			if(in_array($val['staff_id'], $sid_array))
				$staff_data[$val['staff_id']] = $val['date'].','.$val['time_24_hour_clock'].','.$val['clock_action_description'];
	}        
	$usr_details = "<div class='row'><div class='col-md-12'><div class='tabbable tabbable-custom'>				<div class='widget box box-vas'><div class='widget-content widget-content-vls'> <form method='post' name='frmListing'><table class='table table-striped table-bordered table-hover table-checkable table-responsive'><thead class='cf'><tr><th data-class='expand'>Username/IVR Staff ID</th><th data-hide='phone'>Name</th><th data-hide='phone'>Date</th><th data-hide='phone'>Time</th><th data-hide='phone'>Clock Action</th></tr></thead><tbody>";
	$intI=1; 
	while($row = mysqli_fetch_array($res))
	{
		if($intI++%2==0) $strCss='evenTr';else $strCss='oddTr';
		if ($staff_data[$row['username']] <> '' ):
			$ivrdatetimestatus=explode(',', $staff_data[$row['username']]);
			$usr_details .="<tr> <td align='center'>".$row['username']."</td><td align='center'>".$row['f_name']." ".$row['l_name']."</td><td align='center'>".$ivrdatetimestatus[0]."</td><td align='center'>".$ivrdatetimestatus[1]."</td><td align='center'>".$ivrdatetimestatus[2]."</td></tr>";
		else:
			$usr_details .="<tr><td class='errNoRecord'>No Record Found!</td></tr>";
		endif;
	}
	$usr_details .='</tbody></table></form></div></div></div></div></div>';
	echo $usr_details;
	exit;
}*/
?>