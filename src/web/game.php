<?php
error_reporting(E_ALL & ~E_DEPRECATED);
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../db/example_database.php';

use \IMSGlobal\LTI;
$launch = LTI\LTI_Message_Launch::new(new Example_Database())
    ->validate();

?><link href="static/breakout.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Gugi" rel="stylesheet"><?php

if ($launch->is_deep_link_launch()) {
    ?>
    <div class="dl-config">
        <h1>Pick a Difficulty</h1>
        <ul>
            <li><a href="<?= TOOL_HOST ?>/configure.php?diff=easy&launch_id=<?= $launch->get_launch_id(); ?>">Easy</a></li>
            <li><a href="<?= TOOL_HOST ?>/configure.php?diff=normal&launch_id=<?= $launch->get_launch_id(); ?>">Normal</a></li>
            <li><a href="<?= TOOL_HOST ?>/configure.php?diff=hard&launch_id=<?= $launch->get_launch_id(); ?>">Hard</a></li>
        </ul>
    </div>
    <?php
    die;
}
?>

<div id="game-screen">
    <div style="position:absolute;width:1000px;margin-left:-500px;left:50%; display:block">
        <div id="scoreboard" style="position:absolute; right:0; width:200px; height:486px">
            <h2 style="margin-left:12px;">Scoreboard</h2>
            <div id="user-score" style="position:absolute;left:20px;top:50px;color:white;font-size:24px;"></div>
            <table id="scoreboard-table" style="margin-left:12px;">
            </table>
        </div>
        <canvas id="breakoutbg" width="800" height="500" style="position:absolute;left:0;border:0;">
        </canvas>
        <canvas id="breakout" width="800" height="500" style="position:absolute;left:0;">
        </canvas>
    </div>
</div>

<script>
    var launch_id = "<?= $launch->get_launch_id(); ?>";
    var curr_user_name = "<?= isset($launch->get_launch_data()['name']) ? $launch->get_launch_data()['name'] : 'Visitante'; ?>";
    var curr_diff = "<?= isset($launch->get_launch_data()['https://purl.imsglobal.org/spec/lti/claim/custom']['difficulty']) ? $launch->get_launch_data()['https://purl.imsglobal.org/spec/lti/claim/custom']['difficulty'] : 'normal'; ?>";
</script>
<script type="text/javascript" src="static/breakout.js" charset="utf-8"></script>