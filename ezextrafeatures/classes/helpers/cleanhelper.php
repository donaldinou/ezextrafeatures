<?php
namespace extension\ezextrafeatures\classes\helpers {

    abstract class cleanHelper extends Helper {

        public static function cleanupTableCounter( $checkTable = 'ezcontentobject_tree', $srcTable = 'ezcontentobject', $srcID = 'id', $destID = 'contentobject_id', $addSql = '' ) {
            $result = 0;
            $db = \eZDB::instance();
            if ($db instanceof \eZDB) {
                $query = 'SELECT COUNT(*) counter '.
                         'FROM ' . $checkTable .
                         'LEFT JOIN ' . $srcTable . ' ON ' . $srcTable.$srcID . ' = ' . $checkTable.$destID .
                         'WHERE ' . $srcTable.$srcID . ' IS NULL ' . $addSql;
                \eZDebug::writeDebug( $query, 'sql' );

                $queryResult = $db->arrayQuery( $query );
                if ( is_array($queryResult) && !empty($queryResult) && isset($queryResult[0]['counter']) ) {
                    $result = $queryResult[0]['counter'];
                }
            }
            return $result;
        }

        /**
         * @brief Cleanup zombie tables
         * @detail Cleanup zombie tables
         *
         * @param string $checkTable
         * @param string $srcTable
         * @param string $srcID
         * @param string $destID
         * @param string $addSql
         */
        public static function cleanupTable( $checkTable = 'ezcontentobject_tree', $srcTable = 'ezcontentobject', $srcID = 'id', $destID = 'contentobject_id', $addSql = '' ) {
            $db = \eZDB::instance();
            if ($db instanceof \eZDB) {
                $query = 'SELECT DISTINCT `'. $destID . '` ' .
                         'FROM ' . $checkTable .
                         'LEFT JOIN ' . $srcTable . ' ON ' . $srcTable.$srcID . ' = ' . $checkTable.$destID .
                         'WHERE ' . $srcTable.$srcID . ' IS NULL ' . $addSql;
                \eZDebug::writeDebug( $query, 'sql' );

                $queryResult = $db->arrayQuery( $query );
                if ( is_array($queryResult) && !empty($queryResult) ) {
                    $destIDList = array();
                    foreach ($queryResult as $key => $item) {
                        $destIDList[] = $item[$destID];
                    }
                    $query = 'DELETE FROM '. $checkTable . ' ' .
                             'WHERE '. $destID . ' IN ( ' . explode(',', $destIDList) . ' )';
                    \eZDebug::writeDebug( $query, 'sql' );
                    $db->query( $query );
                }
            }
        }

    }

}
