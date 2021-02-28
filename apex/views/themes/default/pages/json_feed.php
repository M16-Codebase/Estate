<!DOCTYPE html>
<html lang="ru">
<body>
<?php
echo 2222111;
exit;
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

ini_set('memory_limit', '2048M');
$xmls = file_get_contents('/var/www/estate/data/www/m16-estate.ru/shared/asset/uploads/crm/SiteDataEstate.xml');
$feed=json_decode(XML2JSON($xmls),true);
echo XML2JSON($xmls);
echo'<pre>';print_r(array_keys($feed));echo'</pre>';
function XML2JSON($xml) {

        function normalizeSimpleXML($obj, &$result) {
            $data = $obj;
            if (is_object($data)) {
                $data = get_object_vars($data);
            }
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    $res = null;
                    normalizeSimpleXML($value, $res);
                    if (($key == '@attributes') && ($key)) {
                        $result = $res;
                    } else {
                        $result[$key] = $res;
                    }
                }
            } else {
                $result = $data;
            }
        }
        normalizeSimpleXML(simplexml_load_string($xml), $result);
        return json_encode($result);
    }
//echo simplexml_load_string($xmls);
//echo json_encode(simplexml_load_string($xmls));
exit;
?>
</body>
</html>
