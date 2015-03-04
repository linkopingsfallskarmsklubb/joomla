<?php
header('content-type:text/css');
header("Expires: ".gmdate("D, d M Y H:i:s", (time()+900)) . " GMT"); 
  
  
  /*******************************************************/
  /* Apperance                                           */
  /* Change the overal appearance here                   */
  /*******************************************************/

  /* Container */
  $cc_padding         = '20px';
  $cc_padding_top     = '10px';
  $cc_padding_left    = '5px';
  $cc_border          = '1px solid #aaaaaa';
  $cc_backgroundColor = '#f7f7f7';

  /* Navigation area */
  $nav_fontSize        = '14px';
  $nav_fontWeight      = 'bold';
  $nav_height          = '40px';

  /* Date inner cell - defines the size of the date boxes */
  $icl_width          = '20px';
  $icl_height         = '24px';
  $icl_margin         = '2px';
  $icl_border         = '1px solid transparent';
  $icl_fontSize       = '12px';
  $icl_fontWeight     = 'normal';
  
  /* Date wrapper cell */
  $wcl_border         = '1px solid transparent';

  /* Date cell */
  $cl_border          = '1px solid #aaaaaa';
  $cl_backgroundColor = '#ffffff'; 
  
  /* Weekdays */
  $wd_fontSize        = '12px';
  $wd_fontWeight      = 'bold';
  $wd_border          = '1px solid '. $cc_backgroundColor; 
  $wd_backgroundColor = 'none'; 
  
  /* Weeknumber */
  $wk_width           = '30px';
  $wk_fontSize        = '12px';
  $wk_fontWeight      = 'bold';
  $wk_border          = '1px solid '. $cc_backgroundColor;
  $wk_backgroundColor = 'none'; 

  /* Dates from previous or next month */
  $oth_backgroundColor = '#dddddd'; 
  $oth_color           = '#333333'; 
  
  
    
  /*******************************************************/
  /* Calculations                                        */
  /* Do not touch                                        */
  /*******************************************************/

  preg_match ('{(\d+((.)\d+)?)}', $icl_height, $m); $icl_height_int = $m[1]; 
  preg_match ('{(\d+((.)\d+)?)}', $icl_margin, $m); $icl_margin_int = $m[1]; 
  preg_match ('{(\d+((.)\d+)?)}', $icl_border, $m); $icl_border_int = $m[1]; 
  preg_match ('{(\d+((.)\d+)?)}', $wcl_border, $m); $wcl_border_int = $m[1]; 
  preg_match ('{(\d+((.)\d+)?)}', $cl_border,  $m); $cl_border_int  = $m[1]; 
  preg_match ('{(\d+((.)\d+)?)}', $icl_width,  $m); $icl_width_int  = $m[1]; 
  preg_match ('{(\d+((.)\d+)?)}', $icl_margin, $m); $icl_margin_int = $m[1]; 
  preg_match ('{(\d+((.)\d+)?)}', $wk_width,   $m); $wk_width_int   = $m[1]; 
  preg_match ('{(\d+((.)\d+)?)}', $wk_border,  $m); $wk_border_int  = $m[1]; 
  
  $wk_height  = $icl_height_int + $icl_margin_int*2 + $wcl_border_int*2 .'px';
  $wd_width   = $icl_width_int  + $icl_margin_int*2 + $wcl_border_int*2 .'px';
  $wd_marginL = $wk_width_int   + $wk_border_int    + $cl_border_int    .'px';


?>


  /*******************************************************/
  /* Highlights                                          */
  /* Name must be on the form "X_[inner|outer]_X"          */
  /*******************************************************/

  .hl_outer_orange {
    background-color: #FF4500 !important;
  }

  .hl_outer_green {
    background-color: #AEE542 !important;
  }

  .hl_outer_blue {
    background-color: #ADD8E6 !important;
  }


  .hl_inner_orange {
    background-color: #FF4500           !important;
    border:           1px solid #777777 !important;
  }

  .hl_inner_green {
    background-color: #6B8E23           !important;
    border:           1px solid #777777 !important;
  }

  .hl_inner_blue {
    background-color: #ADD8E6           !important;
    border:           1px solid #777777 !important;
  }


  .hl_inner_bold {
    font-weight: bolder; !important;
  }



  /*******************************************************/
  /* Container                                           */
  /* This is the full container div.                     */
  /*******************************************************/

  .cal_cont {
    float:                 left;
    height:                auto;
    width:                 auto;
    min-width:             220px;
    padding:               <?=$cc_padding;         ?>;
    padding-top:           <?=$cc_padding_top;     ?>;
    padding-left:          <?=$cc_padding_left;    ?>;
    border:                <?=$cc_border;          ?>;
    background-color:      <?=$cc_backgroundColor; ?>;
    -webkit-touch-callout: none;
    -webkit-user-select:   none;
    -khtml-user-select:    none;
    -moz-user-select:      none;
    -ms-user-select:       none;
    user-select:           none;
  }


  /*******************************************************/
  /* Navigation                                          */
  /* Pevious month, next month and current month         */
  /*******************************************************/

  .calendar_nav {
    position:         relative;
    width:            auto;
    height:           <?=$nav_height; ?>;
    text-align:       center;
    background-color: none;
  }

  .calendar_nav_date {
    position:         relative;
    width:            100%;
    height:           <?=$nav_height; ?>;
    font-size:        <?=$nav_fontSize;        ?>;
    font-weight:      <?=$nav_fontWeight;      ?>;
    display:          table;
    text-align:       center;
    background-color: none;
  }

  .calendar_nav_left {
    //float:            left;
    position:         absolute;
    top:              0px;  //<?=$cc_padding; ?>;
    left:             12px; //<?=$cc_padding; ?>;
    width:            30px;
    height:           <?=$nav_height; ?>;
    display:          table;
    text-align:       left;
    background-color: none;
    z-index:          2;
  }

  .calendar_nav_right {
    //float:            right;
    position:         absolute;
    top:              0px; //<?=$cc_padding; ?>;
    right:            0px; //<?=$cc_padding; ?>;
    width:            30px;
    height:           <?=$nav_height; ?>;
    display:          table;
    text-align:       right;
    background-color: none;
    z-index:          2;
  }

  .calendar_nav_img_cent {
    display:        table-cell;
    vertical-align: middle;
    width:          20px;
    height:         20px; 
    cursor:         pointer;
  }
  .calendar_nav_date_cent {
    display:        table-cell;
    vertical-align: middle;
  }


  /*******************************************************/
  /* Calendar object containers                          */
  /* Week, weekday and all dates                         */
  /*******************************************************/

  .calendar_wd {
    clear:       both;
    width:       auto;
    margin-top:  10px;
    margin-left: <?=$wd_marginL; ?>;
  }

  .calendar_wk {
    clear:            both;
    float:            left;
    margin-top:       <?php echo($cl_border_int/2 .'px'); ?>;
    background-color: none;
  }

  .calendar_cl {
    float: left;
  }


  /*******************************************************/
  /*                                                     */
  /*                                                     */
  /*******************************************************/

  /* Container around the date cells (adds top/left border) */
  .cont_cell {
    overflow:    hidden;
	  width:       auto;
    border-top:  <?=$cl_border ?>;
    border-left: <?=$cl_border ?>;
  }

  .wd {
    float:            left;
    height:           15px;
    width:            <?=$wd_width;           ?>;
    margin:           0px;
    padding:          0px;
    font-size:        <?=$wd_fontSize;        ?>;
    font-weight:      <?=$wd_fontWeight;      ?>;
    text-align:       center;
    cursor:           default;
    border-right:     <?=$wd_border;          ?>;
    border-bottom:    <?=$wd_border;          ?>;
    background-color: <?=$wd_backgroundColor; ?>;
  }

  .wk {
    clear:            both;
    width:            <?=$wk_width            ?>;
    height:           <?=$wk_height           ?>;
    margin:           0px;
    padding:          0px;
    line-height:      <?=$wk_height           ?>;
    text-align:       center;
    font-size:        <?=$wk_fontSize;        ?>;
    font-weight:      <?=$wk_fontWeight;      ?>;
    cursor:           default;
    border-right:     <?=$wk_border;          ?>;
    border-bottom:    <?=$wk_border;          ?>;
    background-color: <?=$wk_backgroundColor; ?>;
  }

  .cell {
    float:              left;
    width:              auto; 
    height:             auto; 
    margin:             0px;
    padding:            0px;
    background-color:   <?=$cl_backgroundColor; ?>;
    border-right:       <?=$cl_border;          ?>;
    border-bottom:      <?=$cl_border;          ?>;
    cursor:             default;
    -webkit-box-sizing: border-box;
    -moz-box-sizing:    border-box;
    -ms-box-sizing:     border-box;
    -o-box-sizing:      border-box;
    box-sizing:         border-box;
  }

  .std {
    cursor:             pointer;
  }

  .cell_wrapper {
    height:             auto;
    width:              100%;
    border:             <?=$wcl_border;?>;
    -webkit-box-sizing: border-box;
    -moz-box-sizing:    border-box;
    -ms-box-sizing:     border-box;
    -o-box-sizing:      border-box;
    box-sizing:         border-box;
  }

  .innercell {  
    width:               <?=$icl_width;      ?>; 
    height:              <?=$icl_height;     ?>; 
    line-height:         <?=$icl_height;     ?>;
    text-align:          center;
    margin:              <?=$icl_margin;     ?>; 
    padding:             0px;
    border:              <?=$icl_border;     ?>;
    font-size:           <?=$icl_fontSize;   ?>;
    -moz-user-select:    -moz-none;
    -khtml-user-select:  none;
    -webkit-user-select: none;
    user-select:         none;
    -webkit-box-sizing:  border-box;
    -moz-box-sizing:     border-box;
    -ms-box-sizing:      border-box;
    -o-box-sizing:       border-box;
    box-sizing:          border-box;
  }

  .std .innercell:hover {  
    border:             1px solid #ff0000;
  }



  /* Dates from previous/next months */
  .other {  
    color:            <?=$oth_color;           ?>;
    background-color: <?=$oth_backgroundColor; ?>;
  }

  /* Mondays start on new row */
  .md {
    clear: both;
  }


  /* Selected date */
  .sel_date .cell_wrapper {
    border-color: #000;
  }

  /* Current date */
  .cur_date {
    text-decoration: underline !important;
  }









