<?php
/*
Plugin Name: LandscapeNotes&trade;
Description:  This plug-in will show LandscapeNotes&trade; Section.
Author: CT
Site Plugin: Yes
Icon Class: icon-leaf
Version: 1.02.31
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
class landscape_notes
{
  private $objDBCon, $objFunction;
  function __construct($objFunction)
  {
    $this->objDBCon = new Database();	
    $this->objFunction = $objFunction;	
  }
  
}
?>