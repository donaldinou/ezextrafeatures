<?php

use extension\ezextrafeatures\autoloads\eZINIFeaturesTemplateOperators;
use extension\ezextrafeatures\autoloads\eZJSFeaturesTemplateOperators;
use extension\ezextrafeatures\autoloads\eZPHPFeaturesTemplateOperators;
use extension\ezextrafeatures\autoloads\eZExtraFeaturesTemplateOperators;

$eZTemplateOperatorArray = array();

// Set template operators from ezextrafeatures script
$eZTemplateOperatorArray[] = array( 'script' => 'extension/ezextrafeatures/autoloads/ezextrafeaturestemplateoperators.php',
                                    'class' => 'extension\\ezextrafeatures\\autoloads\\eZExtraFeaturesTemplateOperators',
                                    'operator_names' => eZExtraFeaturesTemplateOperators::operators()
);

// Set templte operators from ezphpfeatures script
$eZTemplateOperatorArray[] = array( 'script' => 'extension/ezextrafeatures/autoloads/ezphpfeaturestemplateoperators.php',
                'class' => 'extension\\ezextrafeatures\\autoloads\\eZPHPFeaturesTemplateOperators',
                'operator_names' => eZPHPFeaturesTemplateOperators::operators()
);

// Set template operators from ezjsfeatures script
$eZTemplateOperatorArray[] = array( 'script' => 'extension/ezextrafeatures/autoloads/ezjsfeaturestemplateoperators.php',
                'class' => 'extension\\ezextrafeatures\\autoloads\\eZJSFeaturesTemplateOperators',
                'operator_names' => eZJSFeaturesTemplateOperators::operators()
);

// Set template operators from ezinifeatures script
$eZTemplateOperatorArray[] = array( 'script' => 'extension/ezextrafeatures/autoloads/ezinifeaturestemplateoperators.php',
                'class' => 'extension\\ezextrafeatures\\autoloads\\eZINIFeaturesTemplateOperators',
                'operator_names' => eZINIFeaturesTemplateOperators::operators()
);
