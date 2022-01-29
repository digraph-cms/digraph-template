<?php

use DigraphCMS\Config;
use DigraphCMS\Digraph;

@session_start();
if (is_file(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
    Digraph::initialize(function () {
        Config::set('paths.root', realpath(__DIR__ . '/..'));
    });
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Digraph installation wizard</title>
    <style>
        <?php echo file_get_contents(__DIR__ . '/style.css'); ?>
    </style>
</head>

<body>
    <?php Wizard::run(); ?>
</body>

</html>
<?php

class Wizard
{
    const INSTALL_STEPS = [
        'Environment check',
        'File permissions',
        'Database setup',
        'User sources',
        'Global config',
        'Environment config',
        'Finish'
    ];
    const ERROR_INCOMPLETE = 2;
    const ERROR_SKIPPABLE = 1;
    protected static $errorMessages = [];
    protected static $confirmationMessages = [];
    public static function run()
    {
        foreach (static::INSTALL_STEPS as $stepNumber => $stepName) {
            if ($stepNumber == static::currentStep()) {
                // execute current step
                $content = executeStep($stepNumber);
                // current step
                echo "<div class='step step--open'>";
                echo "<h1>$stepName</h1>";
                if (trim($content)) {
                    echo "<hr>";
                    echo $content;
                }
                // display error/confirmation info
                if (static::$errorMessages) {
                    echo "<hr>";
                    foreach (static::$errorMessages as $error) {
                        echo "<div class='notification notification--error'>" . $error . "</div>";
                    }
                }
                if (static::$confirmationMessages) {
                    echo "<hr>";
                    foreach (static::$confirmationMessages as $error) {
                        echo "<div class='notification notification--confirmation'>" . $error . "</div>";
                    }
                }
                // display advance/skip button
                $nextStep = static::currentStep() + 1;
                if ($nextStep < count(static::INSTALL_STEPS)) {
                    if (!static::stepError()) {
                        echo "<hr>";
                        echo "<a href='?currentStep=$nextStep' class='button button--ready'>Continue</a>";
                    } elseif (static::stepError() == static::ERROR_SKIPPABLE) {
                        echo "<hr>";
                        echo "<p>This step has indicated skippable errors. You can continue, but the installation may not complete and/or your site may not work correctly.</p>";
                        echo "<a href='?currentStep=$nextStep' class='button'>Skip step</a>";
                    }
                }
                echo "</div>";
            } else {
                echo "<div class='step'><h1>";
                if ($stepNumber < static::currentStep()) {
                    echo "<a href='?currentStep=$stepNumber'>$stepName</a>";
                } else {
                    echo $stepName;
                }
                echo "</h1></div>";
            }
        }
    }
    public static function skippableError(string $message = null)
    {
        if ($message) {
            static::$errorMessages[] = $message;
        }
        static::stepError(static::ERROR_SKIPPABLE);
    }
    public static function error(string $message = null)
    {
        if ($message) {
            static::$errorMessages[] = $message;
        }
        static::stepError(static::ERROR_INCOMPLETE);
    }
    public static function confirmation(string $message)
    {
        static::$confirmationMessages[] = $message;
    }
    public static function stepError(int $set = null): int
    {
        static $stepError = 0;
        if ($set) {
            $stepError = max($stepError, $set);
        }
        return $stepError;
    }
    public static function currentStep(int $set = null): int
    {
        $_SESSION['currentStep'] = @$_GET['currentStep'] ?? @$_SESSION['currentStep'] ?? 0;
        if ($set !== null) {
            $_SESSION['currentStep'] = $set;
        }
        return $_SESSION['currentStep'] ?? 0;
    }
}

function executeStep(int $step): string
{
    ob_start();
    require __DIR__ . sprintf(
        '/steps/%s.php',
        preg_replace('/[^a-z0-9]+/', '_', strtolower(Wizard::INSTALL_STEPS[$step]))
    );
    return ob_get_clean();
}
