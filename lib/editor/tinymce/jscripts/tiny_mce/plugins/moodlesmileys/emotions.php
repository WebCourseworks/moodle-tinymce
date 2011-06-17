<?php

#################################################################################
##
## $Id: dlg_ins_smile.php,v 1.6 2007/01/27 23:23:45 skodak Exp $
##
#################################################################################

    require("../../../../../../../config.php");

    $id = optional_param('id', SITEID, PARAM_INT);

    require_course_login($id);
    @header('Content-Type: text/html; charset=utf-8');

    $pixpath = "$CFG->pixpath/s";

    $fullnames = get_list_of_pixnames();

    $emoticons = array ( 'smiley'     => ':-)',
                         'biggrin'    => ':-D',
                         'wink'       => ';-)',
                         'mixed'      => ':-/',
                         'thoughtful' => 'V-.',
                         'tongueout'  => ':-P',
                         'cool'       => 'B-)',
                         'approve'    => '^-)',
                         'wideeyes'   => '8-)',
                         'clown'      => ':o)',
                         'sad'        => ':-(',
                         'shy'        => '8-.',
                         'blush'      => ':-I',
                         'kiss'       => ':-X',
                         'surprise'   => '8-o',
                         'blackeye'   => 'P-|',
                         'angry'      => '8-[',
                         'dead'       => 'xx-P',
                         'sleepy'     => '|-.',
                         'evil'       => '}-]' );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>{#emotions_dlg.title}</title>
	<script type="text/javascript" src="../../tiny_mce_popup.js"></script>
	<script type="text/javascript" src="js/emotions.js"></script>
</head>
<body style='display: none;' onload="EmotionsDialog.init();">
<table width="100%"><tr><td class="title" nowrap="nowrap"><?php print_string('chooseicon', 'editor'); ?></td></tr></table>
<table border="0" align="center" cellpadding="5">
  <tr valign="top">
    <td>
    <table border="0">
<?php
    $list = array('smiley', 'biggrin', 'wink', 'mixed', 'thoughtful',
                  'tongueout', 'cool', 'approve', 'wideeyes', 'surprise');
    foreach ($list as $image) {
        $name = $fullnames[$image];
        $icon = $emoticons[$image];
        echo '<tr>';
        echo "<td>";
        echo "<a href=\"javascript:EmotionsDialog.insert('{$pixpath}/{$image}.gif', '{$name}');\">";
        echo "<img alt=\"$name\" class=\"icon\" src=\"$pixpath/$image.gif\" border='0'/></a></td>";
        echo "<td>$name</td>";
        echo "<td class=\"smile\">$icon</td>";
        echo "</tr>";
    }
?>
    </table>
    </td>
    <td>
    <table border="0" align="center">

<?php
    $list = array('sad', 'shy', 'blush', 'kiss', 'clown', 'blackeye',
                  'angry', 'dead', 'sleepy', 'evil');
    foreach ($list as $image) {
        $name = $fullnames[$image];
        $icon = $emoticons[$image];
        echo '<tr>';
        echo "<td>";
        echo "<a href=\"javascript:EmotionsDialog.insert('{$pixpath}/{$image}.gif', '{$name}');\">";
        echo "<img alt=\"$name\" class=\"icon\" src=\"$pixpath/$image.gif\" border='0'/></a></td>";
        echo "<td>$name</td>";
        echo "<td class=\"smile\">$icon</td>";
        echo "</tr>";
    }
?>
    </table>
    </td>
  </tr>
</table>
</body>
</html>
