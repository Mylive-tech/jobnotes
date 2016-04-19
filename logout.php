<?php
include "settings.php";
$strUpdateQuery ="UPDATE ".TBL_STAFF." SET is_login = 0 WHERE username= '".$_SESSION['username']."'";
$objUpdateSuccess = $objDatabase->updateQuery($strUpdateQuery);
setcookie('siteAuth', '', time()-3600);   
session_start();
session_destroy();
?>
<script>
window.location='<?php echo SITE_URL."login.php";?>';
</script>