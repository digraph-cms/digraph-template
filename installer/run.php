<?php
define(
    'INSTALL_STEPS',
    [
        'Welcome',
        'Environment check'
    ]
);

// sort out next step from status in session
$NEXT_STEP = 0;
$_SESSION['STEP_STATUS'] = $_SESSION['STEP_STATUS'] ?? [];
foreach ($_SESSION['STEP_STATUS'] as $STEP_NUMBER => $status) {
    if ($status) {
        $NEXT_STEP = $STEP_NUMBER++;
    } else {
        break;
    }
}
$_SESSION['STEP_NUMBER'] = $_GET['STEP_NUMBER'] ?? $_SESSION['STEP_NUMBER'] ?? $NEXT_STEP;

// loop through steps and display them
foreach (INSTALL_STEPS as $STEP_NUMBER => $STEP_NAME) {
    // get status for classes
    if (@$_SESSION['STEP_STATUS'][$STEP_NUMBER]) {
        $STEP_STATUS_CLASS = 'step--done';
    } elseif (@$_SESSION['STEP_STATUS'][$STEP_NUMBER] === false) {
        $STEP_STATUS_CLASS = 'step--incomplete';
    } else {
        $STEP_STATUS_CLASS = 'step--null';
    }
    // display appropriate box
    if ($STEP_NUMBER == $_SESSION['STEP_NUMBER']) {
        // currently active step
        $STEP_STATUS = null;
        echo "<div class='step step--open $STEP_STATUS_CLASS'>";
        echo "<h1>$STEP_NAME</h1>";
        require __DIR__ . sprintf(
            '/steps/%s.php',
            preg_replace('/[^a-z0-9]+/', '_', strtolower($STEP_NAME))
        );
        // display advance button
        $CONTINUE_STEP = $NEXT_STEP + 1;
        if ($CONTINUE_STEP < count(INSTALL_STEPS)) {
            echo "<hr>";
            if ($STEP_STATUS) {
                echo "<a href='?STEP_NUMBER=$CONTINUE_STEP' class='button button--ready'>Continue</a>";
            } else {
                echo "<a class='button button--disabled'>Continue</a>";
            }
        }
        echo "</div>";
        $_SESSION['STEP_STATUS'][$STEP_NUMBER] = $STEP_STATUS;
    } elseif ($STEP_NUMBER > $NEXT_STEP) {
        // future step, no link
        echo "<div class='step step--collapsed $STEP_STATUS_CLASS'>";
        echo "<h1>$STEP_NAME</h1>";
        echo "</div>";
    } elseif ($STEP_NUMBER <= $NEXT_STEP) {
        // past step, can link back to
        echo "<div class='step step--collapsed $STEP_STATUS_CLASS'>";
        echo "<h1><a href='?STEP_NUMBER=$STEP_NUMBER'>$STEP_NAME</a></h1>";
        echo "</div>";
    }
}
