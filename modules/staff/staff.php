<?php
/**
 * @Author: CT
 * @Purpose: Class for Staff Module which inherits the HTML view class
 */
class staff extends STAFF_HTML_CONTENT

{
	private $intId;
	private $objDatabase;
	private $objMainFrame;
	private $objRecord;
	private $objSet;
	public $url, $callback, $facebook, $facebookProfile;

	/**
	 *@Purpose: Define the construction of the class and create the instaces of Database class, Mainframe class and Functions class
	 */
	function __construct($intId, $objMainframe, $objFunctions)
	{
		$this->intId = $intId;
		$this->objDatabase = new Database();
		$this->objMainFrame = $objMainframe;
		$this->objFunction = $objFunctions;
		parent::__construct($this->objFunction, $this->objDatabase); // Call the construction of the HTML view class
	}

	/**
	 *@Purpose: Show Admin Content Form to Add/Edit Content
	 *@Input: id will be input for the edit content form
	 */
	public function admin_Staff_Form()
	{
		if ($this->intId <> ''): // condition i.e page for the edit content
			$strSql = 'SELECT * FROM ' . TBL_STAFF . ' where id=%d';
			$this->objRecord = $this->objDatabase->fetchRows(sprintf($strSql, $this->intId));
			parent::admin_Staff_Form($this->objRecord, $_REQUEST['type']); // show the form with already filled values
			else:
				$this->objFunction->filterPosted();
				$this->objRecord = (object)$_POST; // converion of Posted array to object message
				parent::admin_Staff_Form($this->objRecord, $_REQUEST['type']); // // show the form to Add content
			endif;
		} // End Function
        
		public function get_admin_Staff_details()
	{
		if ($this->intId <> ''): // condition i.e page for the edit content
			$strSql = 'SELECT * FROM ' . TBL_STAFF . ' where id=%d';
			$this->objRecord = $this->objDatabase->fetchRows(sprintf($strSql, $this->intId));
			//parent::admin_Staff_Form($this->objRecord, $_REQUEST['type']); // show the form with already filled values
			return $this->objRecord;
			else:
				$this->objFunction->filterPosted();
				$this->objRecord = (object)$_POST; // converion of Posted array to object message
				//parent::admin_Staff_Form($this->objRecord, $_REQUEST['type']); // // show the form to Add content
				return $this->objRecord;
			endif;
		}
		
		public function users_Edit_Form()
		{
			$strSql = 'SELECT * FROM ' . TBL_STAFF . ' where id=%d';
			$this->objRecord = $this->objDatabase->fetchRows(sprintf($strSql, $_SESSION['adminid']));
			parent::users_Edit_Form($this->objRecord); // show the form with already filled values
		} // End Function
		/**
		 *@Purpose: Show Content Listing to Modify/Delete
		 *@Description: This function will list all the contents in Grid Format with edit link.
		 */
		public function admin_staffListing()
		{
			$strSql = "SELECT s.*,st.label as userRole FROM " . TBL_STAFF . " s left join " . TBL_STAFFTYPE . " st on s.user_type=st.id where s.user_type >1  and s.site_id='" . $_SESSION['site_id'] . "'";
			$this->objSet = $this->objDatabase->dbQuery($strSql);
			parent::admin_staffListing($this->objSet, 'staff');
		} // End Function
        
		public function import_staff()
		{
			parent::import_staff();
		} // End Function
        
		public function save_import_staff($tbl)
		{
?>       

<div id="content" class="add-new-job-l">			

  <div class="container">				  

    <div class="edit_form">  			 

      <div class="col-md-12">             

<?php
			if (isset($_POST['save'])) {
				if (is_uploaded_file($_FILES['md_file']['tmp_name'])) {
					echo "<h1>" . "File " . $_FILES['md_file']['name'] . " uploaded successfully." . "</h1>";
					echo "<h2>Displaying uploaded data:</h2>";
					$file1 = $_FILES['md_file']['tmp_name'];
					$row = 1;
					echo "<table class='table table-striped table-bordered table-hover' border=1><thead>

                                                <th>S.N</th>

                                                <th>Username/IVR Staff ID</th>

												  <th>First Name</th>

                                                  <th>Last Name</th>

                                                  <th>Email</th>

                                                  <th>Phone</th>

                                                  <th>Password</th>

                                                  <th>Role</th>

                                                </tr>

                                             </thead>

									<tbody>";
                        if (($handle = fopen($file1, "r")) !== FALSE) {
						$i = 0;
						while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
							if ($i > 0) {
								$num = count($data);
								echo "<tr><td>$i</td>";
								$row++;
								for ($c = 0; $c < $num; $c++) {
									echo "<td>" . $data[$c] . "</td>";
								}

								echo "</tr>";
							}

							$i++;
						}
					}

					echo "</tbody></table>";
				}

				$file = $_FILES['md_file']['tmp_name'];
				$handle = fopen($file, "r");
				$i = 0;
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					if ($i > 0) {
						$objStaffRole = $this->objDatabase->fetchRows("SELECT * FROM " . TBL_STAFFTYPE . " where LOWER(label) = '" . strtolower($data[7]) . "'");
						$data[5] = md5($data[5]);
						$import = "INSERT into " . $tbl . "(site_id, username, ivr_staff_id, f_name,l_name,email,phone,password, user_type) values('" . $_SESSION['site_id'] . "', '$data[0]', '" . addslashes($data[1]) . "','" . addslashes($data[2]) . "','$data[3]','$data[4]','$data[5]', '" . $objStaffRole->id . "')";
						$this->objDatabase->dbQuery($import);
					}

					$i++;
				}

				fclose($handle);
				print "Import done";
			}

?>                                                                  

        <div class="clearfix">

        </div>  			 

      </div>   

      <div class="clearfix">

      </div>   

    </div>   	 			  			

  </div>			

  <!-- /.container -->		

</div>                                

<?php
		} // End Function
		public function users_login()
		{
			parent::users_login();
		} // End Function
		public function recover_password()
		{
			parent::recover_password();

			// $this-> users_signup();

		} // End Function
		public function users_register()
		{
			parent::users_signup();
		} //end function
		
        public function users_checklogin()
		{
			$this->objFunction->checklogin();
		} //end function
		
        public function users_logout()
		{
			session_destroy();
			@header("Location: " . SITE_URL);
			exit;
		} //end function
        
        
		/**
		 *@Purpose: SAVE content (Add new Content and Update the existing content
		 *@Description: This function will save the content. IF content is new then content will be added otherwise content data will be updated.
		 */
		public function saveContent($tbl)
		{
			if ($this->intId == ''): //Check for content posted is "New" or "Existing"
				$this->intId = $this->objDatabase->insertForm($tbl); //Insert new content in table
				if ($this->intId) {
					//$this->objFunction->showMessage('Record has been added successfully.', $_SERVER['REQUEST_URI']); //Function to show the message
					return '<h3>Record has been updated successfully.</h3>';
				}
				else {
					$this->objFunction->filterPosted();
					$this->objRecord = (object)$_POST; // converion of Posted array to object message
					parent::admin_Form($this->objRecord);
				}
				else:
					if ($_POST['password'] != '') $_POST['db_password'] = md5($_POST['password']);
					$this->objDatabase->updateForm($tbl); //Update Exisiting content
					return '<h3>Record has been updated successfully.</h3>';
					//$this->objFunction->showMessage('Record has been updated successfully.', $_SERVER['REQUEST_URI']); //Function to show the message
				endif;
			} //end function
            
			/**
			 *@Purpose: Modify content Status Activate/Deactivate
			 *@Description: This function will update the content status i.e  through this function active content can be deavtivated and deactivate content can be activated.
			 */
			public function modifyContent($tbl)
			{
				$this->intId = is_array($_REQUEST['delete']) ? implode(',', $_REQUEST['delete']) : $_REQUEST['delete']; // Get the posted id of the selected content pages
				$intStatus = $_REQUEST['status'];
				$strSql = 'Update ' . $tbl . ' set status=\'' . $intStatus . '\' where id in (' . $this->intId . ')';
				$this->objDatabase->dbQuery($strSql);
				$strWord = 'Modified';
				if ($intStatus == '-1') {
					$strWord = 'Deleted';
					$this->objDatabase->dbQuery('DELETE FROM ' . $tbl . ' where id in (' . $this->intId . ')');
				}

				// $this->updatecache();

				$this->objFunction->showMessage('Record has been ' . $strWord . ' successfully.', ISP::AdminUrl('staff/users-listing/')); //Function to show the message
			} //end function
            
            
			public function admin_userRoles()
			{
				parent::admin_userRoles();
			}

			public function savePermissions()
			{
				$this->objDatabase->dbQuery('TRUNCATE TABLE ' . TBL_USERPERMISSION);
				//print_r($_POST); die;
				foreach($_POST as $key => $value) {
					if (strstr($key, "permission_")) {
						$keyArray = explode("_", $key);
						$role_id = $keyArray[1];
						$permission_id = $keyArray[2];
						//echo "insert into " . TBL_USERPERMISSION . " (role_id, role_permission_id) values ('" . $role_id . "', '" . $permission_id . "')"; die;
						$this->objDatabase->dbQuery("insert into " . TBL_USERPERMISSION . " (role_id, role_permission_id) values ('" . $role_id . "', '" . $permission_id . "')");

						// echo "<br />insert into ".TBL_USERPERMISSION." (role_id, role_permission_id) values ('".$role_id."', '".$permission_id."')";

					}
				}

				//$this->objFunction->showMessage('Permissons have been updated successfully.', $_SERVER['REQUEST_URI']); //Function to show the message
				return '<h3>Permissons have been updated successfully.</h3>';
			}

			public function getUserInfo()
			{
				$strSql = "SELECT s.* FROM " . TBL_STAFF . " s where s.id='" . $_POST['uid'] . "'";
				$objSet = $this->objDatabase->fetchRows($strSql);
				echo $objSet->phone;
				die();
			}

			public function staff_ivr_log()
			{
				$strSql = "SELECT s.*,st.label as userRole FROM " . TBL_STAFF . " s inner join " . TBL_STAFFTYPE . " st on s.user_type=st.id where s.user_type >1  and s.site_id='" . $_SESSION['site_id'] . "'";
				$this->objSet = $this->objDatabase->dbQuery($strSql);
				parent::admin_staff_ivr_log($this->objSet);
			}
			
			public function staff_ivr_log_today()
			{
				$strSql = "SELECT s.*,st.label as userRole FROM " . TBL_STAFF . " s inner join " . TBL_STAFFTYPE . " st on s.user_type=st.id where s.user_type >1  and s.site_id='" . $_SESSION['site_id'] . "'";
				$this->objSet = $this->objDatabase->dbQuery($strSql);
				parent::admin_staff_ivr_log_today($this->objSet);
			}
			

			public function import_ivr_log()
			{
				$array_log = $this->objFunction->getStaffIvrLog($_GET['user']);
				$filename = 'export_ivr_log_staff_' . $_GET['user'] . '.csv';
				ob_start();
				if (intval($_GET['user']) > 0 && count($array_log) > 0) {
					$strLines = 'Date, Time, Status' . PHP_EOL;
					foreach($array_log as $log) {
						$strLines.= strftime('%b %d %Y', $log['time_stamp']) . ', ' . $log['time_12_hour_clock'] . ', ' . ucfirst($log['clock_action_description']) . PHP_EOL;
					}

					echo $strLines;
					header('Content-Type: application/csv');
					header('Content-Disposition: attachment; filename="' . $filename . '";');
					die();
				}
			}
		} // End of Class
		/**
		 @Purpose: Create the object the class "content"
		 */
		$objStaff = new staff($intId, $objMainframe, $objFunctions);
		$arrPageUrl = explode("/", $arrUrl[1]);
		$objStaff->url = $arrPageUrl[0];
		switch ($strTask) {
		case 'add-staff':
		case 'edit-staff':
			$objStaff->admin_Staff_Form();
			break;

		case 'savestaff':
			$objStaff->saveContent(TBL_STAFF);
			break;

		case 'modifystaff':
			$objStaff->modifyContent(TBL_STAFF);
			break;

		case 'users-listing':
			$objStaff->admin_staffListing();
			break;

		case 'staff_ivr_log':
			$objStaff->staff_ivr_log();
			break;
			
		case 'staff_ivr_log_today':
			$objStaff->staff_ivr_log_today();
			break;

		case 'import_ivr_log':
			$objStaff->import_ivr_log();
			break;

		case 'user-permissions':
			$objStaff->admin_userRoles();
			break;

		case 'savepermissions':
			$objStaff->savePermissions();
			break;

		case 'my-profile':
			$objStaff->users_Edit_Form();
			break;

		case 'save-user':
			$objStaff->saveUser();
			break;

		case 'signin':
			$objStaff->users_login();
			break;

		case 'checklogin':
			$objStaff->users_checklogin();
			break;

		case 'signup':
			$objStaff->users_register();
			break;

		case 'register':
			$objStaff->users_register();
			break;

		case 'forget_password':
			$objStaff->recover_password();
			break;

		case 'import':
			$objStaff->import_staff();
			break;

		case 'importstaff':
			$objStaff->save_import_staff(TBL_STAFF);
			break;

		case 'reset_password':
			$objStaff->reset_password();
			break;

		case 'logout':
			$objStaff->users_logout();
			break;

		case 'getuserinfo':
			$objStaff->getUserInfo();
			break;
		}

?>


  

 
