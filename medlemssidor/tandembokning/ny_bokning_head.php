
  <!-- Main style sheet -->
  <link rel="stylesheet" type="text/css" href="<?=DIR_REL?>/single_pages/includes/styles.css" />
  <link rel="stylesheet" type="text/css" href="<?=DIR_REL?>/single_pages/medlemssidor/tandembokning/ny_bokning.css" />
  <!-- <link rel="stylesheet" type="text/css" href="<?=DIR_REL?>/single_pages/medlemssidor/tandembokning/ny_bokning_mobile.css" /> -->

  <!-- Javascript functions -->
  <script type="text/javascript" src="<?=DIR_REL?>/single_pages/includes/scripts.js"></script>
  <script type="text/javascript" src="<?=DIR_REL?>/single_pages/medlemssidor/tandembokning/ny_bokning_scripts.js"></script>


  <!-- The calendar -->
  <link rel="stylesheet" type="text/css" href="<?=DIR_REL?>/single_pages/includes/calendar/calendar.css" />
  <script type="text/javascript" src="<?=DIR_REL?>/single_pages/includes/calendar/calendar_scripts.js"></script>

  <script type="text/javascript">
    function CheckValues($char, $mozChar, $value) {
      var keyCode;
      if($mozChar != null) {
        keyCode = $mozChar;
        if ((keyCode == 13) || (keyCode == 0)) {
          ajax_f('get_pk', $value);
        }
      }
      else {
        keyCode = $char;
        if (keyCode == 13) {
          ajax_f('get_pk', $value);
        }
      }
    }
  </script>


