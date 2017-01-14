<?php
class HTML_MEDIA {
    private $objFunction;
    public function __construct($objfunc) {
        $this->objFunction = $objfunc;
    }
    
    protected function medialist($main_images, $user_images) {
        $user_images = ltrim($user_images, ","); 
        $main_images = ltrim($main_images, ","); 
        $main_images = str_replace(",,", ",", $main_images);
        $user_images = str_replace(",,", ",", $user_images);
        $totalPropertyImages = $main_images.",".$user_images;
        
        if ($_GET['filter'] == 'staff') {
           $totalPropertyImages = ltrim($user_images, ","); 
        }
        
        if ($_GET['filter'] == 'main') {
           $totalPropertyImages = $main_images; 
        }
        
        $filearray = explode(",", $totalPropertyImages);
        $totalImages = count($filearray);
        $optimizedImages = 0;
        foreach ($filearray as $file) {
            if (file_exists('upload/mobile/'.$file))
            $optimizedImages++;
        }
        
    ?>
    <div id="content">
        <div class="container"> 
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-left heding_6">Media Manager</h4>
                    <div class="widget box box-vas">
                        <div class="widget-content formTableBg widget-content-vls">
                        <form method="post">
                            <input type="hidden" name="dir" value="media">
                            <input type="hidden" name="task" value="download-image">
                            <span><b>Total Images:</b> <?php echo $totalImages;?>  </span>
                            <span><b>Optimized Images:</b> <?php echo $optimizedImages; /*$glob = glob("upload/mobile/{*.jpg,*.gif,*.png,*.PNG,*.JPG,*.JPEG,*.GIF}", GLOB_BRACE);echo count($glob);*/ ?></span>
                            <span id="progress">
                            <?php
                            if ($totalImages >count($glob)) {
                                ?>
                            <input type="button" value="Optimize All" onclick="optimizeImages();">
                            <?php 
                            }
                            ?>
                            </span>
                            <span><a href="<?php echo SITE_ADMINURL;?>index.php?dir=media&task=export" target="_blank"><button>Export Optimized Images</button></a></span>
                            <span><input type="submit" value="Export Selected" name="saveselected">
                            <span><input type="submit" onclick="return confirm('Only the selected images will be removed. The properties will not be affected with this deletion but if these images are attached then property will show blank images.');" value="Remove Selected" name="rmselected">
                            <p>
                            <label>Filter By: </label>
                            <select name="filter" onchange="window.location='<?php echo SITE_ADMINURL;?>index.php?dir=media&task=list&filter='+this.value;">
                            <option value="all">All Images</option>
                            <option value="staff" <?php if ($_GET['filter']=='staff') echo 'selected';?>>Staff Images</option>
                            <option value="main" <?php if ($_GET['filter']=='main') echo 'selected';?>>Main Images</option>
                            </select>
                            </p>
                            <div class="form_holder mediamanager" style="padding-top:20px;">
                            <?php
                                if (!isset($_GET['page'])) {
                                    $currentPage = 1;
                                }
                                else {
                                    $currentPage = $_GET['page'];
                                }
                                $start = ($currentPage-1)*30;
                                $limit = $start+30;
                                
                                if ($limit >$totalImages) {
                                    $limit = $totalImages;
                                }
                                $totalPages = ceil($totalImages/30);
                                for($i = $start; $i< $limit; $i++) {
                                    $file = "upload/".$filearray[$i];
                                ?>
                                <div style="margin: 0px 10px 10px 0px; position: relative; width: 160px; height: 240px; float:left; text-align:center; border:1px solid #ccc; overflow:hidden;">
                                    <input style="display:none;" onclick="$(this).is(':checked')?$('#img_check_<?php echo $i;?>').show(1000):$('#img_check_<?php echo $i;?>').hide(1000);" type="checkbox" id="check_<?php echo $i;?>" value="<?php echo basename($file);?>" name="imgdownload[]">
                                    <label for="check_<?php echo $i;?>" id="img_check_<?php echo $i;?>" style="display: none; position: absolute; left:0px; width:160px; height:110px;">
                                    <img src="<?php echo SITE_URL;?>upload/tmp/trans_checked.png" style="width:160px; height:110px;">
                                    </label>
                                    <label for="check_<?php echo $i;?>" style="cursor: pointer;">
                                    <img alt="Click to Select" title="Click to Select" src="<?php echo SITE_URL;?>upload/tmp/blank.gif" data-src="<?php echo SITE_URL.$file;?>" style="width:160px !important; height: 110px !important;" border="0" width="130" height="110">
                                    </label>
                                    <br>                                    
                                    <?php echo substr(basename($file),0,30);?>
                                    <br>
                                    <?php
                                    $org_size = filesize('upload/'.basename($file));
                                    $compress_size = filesize('upload/mobile/'.basename($file));
                                    ?>
                                    Actual Size: <?php echo $this->objFunction->sizeFormatter($org_size);?><br>
                                    Optimized Size: <?php echo $this->objFunction->sizeFormatter($compress_size);?><br>
                                    Reduced Size: <?php echo round(((($org_size-$compress_size)*100)/$org_size),2).' %';?><br>
                                    Timestamp: <?php echo date ("m/d/Y", filemtime($file));?>
                                </div>
                                <?php
                                }
                            ?>                            
                            </div>
                            <div class="clear"></div>
                            <div>
                                Go to Page: <select id="page" onchange="window.location='<?php echo SITE_ADMINURL;?>media/list/page/'+this.value">
                                <?php
                                for ($j=1; $j<=$totalPages; $j++) {
                                ?>
                                <option value="<?php echo $j;?>" <?php if ($j==$currentPage) echo 'selected';?>><?php echo $j;?></option>
                                <?php
                                }
                                ?>
                                </select>
                                <p style="float:right;">
                                <a style="margin: 0px 0px; border: 1px solid #ccc; padding: 0px 5px 0px 7px;" id="m_prev" href="<?php echo SITE_ADMINURL.'media/list/page/1';?>">PREV</a>
                                <?php
                                for ($j=1; $j<=$totalPages; $j++) {
                                ?>
                                 <a style="margin: 0px 0px; border: 1px solid #ccc; padding: 0px 5px 0px 7px;" id="<?php echo 'mediapage_'.$j;?>" href="<?php echo SITE_ADMINURL.'media/list/page/'.$j;?>"><?php echo $j;?></a> 
                                <?php
                                }
                                ?>
                                <a style="margin: 0px 0px; border: 1px solid #ccc; padding: 0px 5px 0px 7px;" id="m_next" href="<?php echo SITE_ADMINURL.'media/list/page/'.($j-1);?>">NEXT</a>
                                </p>
                                <div style="clear:both;"></div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script defer>
$(document).ready(function(e) {
	var pageno = $('#page').val();
	var optionlast = $('#page option').last().val();
	if(pageno != 1)
	{
		$('#m_prev').attr('href', '<?php echo SITE_ADMINURL.'media/list/page/';?>'+(pageno-1));
	}
	else
	{
		$('#m_prev').css('pointer-events','none');
	}
	if(pageno != optionlast)
	{
		$('#m_next').attr('href', '<?php echo SITE_ADMINURL.'media/list/page/';?>'+(parseInt(pageno)+parseInt(1)));
	}
	else
	{
		$('#m_next').css('pointer-events','none');
	}
	$('#mediapage_'+pageno).addClass('active');
});
function lazyloadMedia() {
     $("div.mediamanager img").each(function() {
        $(this).attr("src", $(this).attr("data-src"));
    });
}
function sendtooptimize(index) {
   $.ajax({
        method: "POST",
        url: "<?php echo SITE_ADMINURL;?>media/optimize",
        data: { index: index}
    })
    .done(function( msg ) {
        var nexttIndex = parseInt(index)+10;
        if (nexttIndex<=<?php echo $totalImages;?>) {
            
            $("#progress").html('Optimization of images in progress '+nexttIndex+' out of <?php echo $totalImages;?>');
            sendtooptimize(nexttIndex);
        }
        else {
            $("#progress").html('');
        }
    }); 
}
function optimizeImages() {
    var imgindex=1;
    $("#progress").html('Optimization of images in progress '+imgindex+' out <?php echo $totalImages;?>');
    
    sendtooptimize(imgindex);
}
setTimeout(function(){
    lazyloadMedia();
}, 6000);
</script>
    <?php
    }
}

?>