<?php
/**
 * Dump methods
 * 
 * @param type $data
 * @param type $description
 */
function dump($data, $description = null)
{

    if ('development' === APPLICATION_ENV) {
        if (null !== $description) {
            echo $description , '<br />';    
        }

        if (is_bool($data)) {
            if (true === $data) {
                echo 'true';
            } else {
                echo 'false';
            }
        } elseif (null === $data) {
            echo 'null';
        } else {
            echo '<pre>';
            print_r($data);
            echo '</pre>';
        }
        
        exit;
    }
}

/**
 * Helper for generate translate file *.po
 * 
 * @param string $messageId
 * @return string
 */
function translate($messageId) 
{
    return $messageId;
}
