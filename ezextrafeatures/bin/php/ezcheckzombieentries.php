#!/usr/bin/env php
<?php

require(__DIR__ . '/../../../../autoload.php');

use extension\ezextrafeatures\classes\helpers\cleanHelper;

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( 'Check the database for corrupt entries which are not used by eZ Publish.' . PHP_EOL .
                                                    'Please make a backup before you delete entries!' ),
                                      'use-session' => true,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );
if ($script instanceof eZScript && $cli instanceof eZCLI) {

    $script->startup();
    $script->initialize();
    if ($script->isInitialized()) {

        $cli->output('This script is not finished yet!');

        $output = new ezcConsoleOutput();

        $question = ezcConsoleQuestionDialog::YesNoQuestion(
                        $output,
                        "WARNING!\nThis script checks if there are any zombie entries in your database and can delete them.\nPLEASE MAKE A DATABASE BACKUP BEFORE YOU PROCEED!\n" .
                        "Do you want to use this script at your own risk?",
                        "n"
        );
        if ( \ezcConsoleDialogViewer::displayDialog( $question ) == 'n' ) {
            $cli->output( 'Bye bye.' );
            return $script->shutdown();
        }

        // Array to work with
        $tablesToClean = array(
                            0 => array (
                                    'checkTable' => 'ezcontentobject_tree',
                                    'srcTable' => 'ezcontentobject',
                                    'srcID' => 'id',
                                    'destID' => 'contentobject_id',
                                    'addSql' => ' AND node_id != 1 '
                            ),
                            1 => array (
                                    'checkTable' => 'ezcontentobject_attribute',
                                    'srcTable' => 'ezcontentobject',
                                    'srcID' => 'id',
                                    'destID' => 'contentobject_id',
                                    'addSql' => ''
                            ),
                            2 => array (
                                    'checkTable' => 'ezuser',
                                    'srcTable' => 'ezcontentobject',
                                    'srcID' => 'id',
                                    'destID' => 'contentobject_id',
                                    'addSql' => ''
                            ),
                            3 => array (
                                    'checkTable' => 'ezsearch_object_word_link',
                                    'srcTable' => 'ezcontentobject',
                                    'srcID' => 'id',
                                    'destID' => 'contentobject_id',
                                    'addSql' => ''
                            ),
                            4 => array (
                                    'checkTable' => 'ezkeyword_attribute_link',
                                    'srcTable' => 'ezcontentobject_attribute',
                                    'srcID' => 'id',
                                    'destID' => 'objectattribute_id',
                                    'addSql' => ''
                            ),
        );

        $cli->output( 'Starting with table checks...' );

        try {
            foreach ($tablesToClean as $key => $table) {
                $zombieCounter = cleanHelper::cleanupTableCounter( $table['checkTable'], $table['srcTable'], $table['srcID'], $table['destID'], $table['addSql'] );
                if ( $zombieCounter > 0) {
                    $cli->output( 'There are ' . $zombieCounter . ' zombie entries in ' . $table['checkTable'] . PHP_EOL .
                                  ' which have no object presented in '. $table['srcTable'] . '. ' . PHP_EOL .
                                  'You can delete them safely.');
                    $question = \ezcConsoleQuestionDialog::YesNoQuestion( $output, 'Do you want to delete them?', 'n' );
                    if ( \ezcConsoleDialogViewer::displayDialog( $question ) == 'y' ) {
                        cleanHelper::cleanupTable( $table['checkTable'], $table['srcTable'], $table['srcID'], $table['destID'], $table['addSql'] );
                    }
                } else {
                    $cli->output( $checkTable .' is ok.' );
                }
            }
        } catch (Exception $e) {
            $cli->error($e->getMessage());
            $script->shutdown(1);
        }

        $cli->output( 'Done. The database is zombie free!' );

    } else {
        $error = 'eZ Publish script did not properly initialized';
        $cli->error( $error );
    }
    $script->shutdown(1);
}
else {
    // Erroe while loading
    $error = 'eZ Publish script did not properly created. Be sure you\'re on the ezpublish root folder';
    $cli->error( $error );
}
