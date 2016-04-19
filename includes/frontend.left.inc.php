<?php

global $objFunctions;

$reportsArr = $objFunctions->iFindAll(TBL_REPORTS, array('status'=>1, ' order by report_name asc'));

?>
<!--
<div id="bs-example-navbar-collapse-1" class="collapse navbar-collapse">

-->

<div id="hidethemenu">
			<div id="sidebar-content" class="mCustomScrollbar _mCS_1"><div id="mCSB_1" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside" style="max-height: 767px;" tabindex="0"><div id="mCSB_1_container" class="mCSB_container" style="position: relative; left: 0px; top: 0px;" dir="ltr">

				<ul id="nav navbar-nav" class="staf-s-nav">

        <li class="current li_head">

						<a href="<?php echo sefToAbs('index.php?dir=dashboard&task=dashboard');?>">

							Dashboard

						</a>

					</li>

				<?php

        foreach($reportsArr as $objReport)

        {

        ?>	

          <li class="current li_head">

						<a href="<?php echo sefToAbs('index.php?dir=reports&task=show-form&id='.$objReport->report_id);?>">

							<?php echo $objReport->report_name;?>

						</a>

					</li>

       <?php

        }

        ?>   										

				</ul>

				<div class="text-center">

				<h5 style="text-align:center;">

					<?php echo nl2br($objFunctions->iFind(TBL_CONFIGURATION, 'config_value', array('config_key'=>'sidebar_welcome_message')));?>

				</h5>

			

				</div>

				

			</div>

<!--      

<div id="mCSB_1_scrollbar_vertical" class="mCSB_scrollTools mCSB_1_scrollbar mCS-light mCSB_scrollTools_vertical" style="display: block;"><div class="mCSB_draggerContainer"><div id="mCSB_1_dragger_vertical" class="mCSB_dragger" style="position: absolute; min-height: 30px; top: 0px; display: block; height: 355px; max-height: 353px;" oncontextmenu="return false;"><div class="mCSB_dragger_bar" style="line-height: 30px;"></div></div><div class="mCSB_draggerRail"></div></div></div>

-->

</div></div>

			

		</div>