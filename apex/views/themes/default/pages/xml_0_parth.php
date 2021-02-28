<?php

if (!function_exists('getAreaTag'))
{
	function getAreaTag($name, $value)
	{
		return "\t\t<{$name}>\r\n\t\t\t<value>{$value}</value>\r\n\t\t\t<unit>кв.м</unit>\r\n\t\t</{$name}>";
	}
}
$domain = 'http://m16-estate.ru';

$agent = "\t\t<sales-agent>
\t\t\t<name>М16-Недвижимость</name>
\t\t\t<phone>+7(812)688-88-85</phone>
\t\t\t<category>агентство</category>
\t\t\t<organization>Агентство недвижимости Вячеслава Малафеева «М16-Недвижимость».</organization>
\t\t\t<url>{$domain}</url>
\t\t\t<email>mail@m16.bz</email>
\t\t\t<photo>{$domain}/asset/assets/img/m16-logo.png</photo>
\t\t</sales-agent>";


$xml = array();

$xml[] = "\r\n\t".'<offer internal-id="'.$id.'">';

foreach ($row['offer'] as $k => $v)
{
	switch ($k)
	{
		case 'location':
			$xml[] = "\t\t".'<'.$k.'>';
			foreach ($v as $name => $var)
			{
				if (is_array($var))
				{
					$xml[] = "\t\t\t".'<'.$name.'>';
					foreach ($var as $k1 => $v1)
					{
						$xml[] = "\t\t\t\t".'<'.$k1.'>'.$v1.'</'.$k1.'>';
					}
					$xml[] = "\t\t\t".'</'.$name.'>';
				}
				else
				{
					$xml[] = "\t\t\t".'<'.$name.'>'.$var.'</'.$name.'>';
				}
			}
			$xml[] = "\t\t".'</'.$k.'>';
			break;
		
		default:
			$xml[] = "\t\t".'<'.$k.'>'.$v.'</'.$k.'>';
			break;
	}
}

$xml[] = $agent;

$xml[] = "\t\t".'<price>';
$xml[] = "\t\t\t".'<value>'.$row['cond']['price']['value'].'</value>';
$xml[] = "\t\t\t".'<currency>'.$row['cond']['price']['currency'].'</currency>';
$xml[] = "\t\t".'</price>';

$xml[] = "\t\t".'<mortgage>'.$row['cond']['mortgage'].'</mortgage>';
$xml[] = "\t\t".'<deal-status>'.$row['cond']['deal-status'].'</deal-status>';

foreach ($row['info'] as $k => $v)
{
	switch ($k)
	{
		case 'image':
			foreach ($v as $img)
				$xml[] = "\t\t".'<image>'.$domain.$img.'</image>';
			break;
		
		case 'description':
			$text = htmlspecialchars($row['info']['description']);
			$xml[] = "\t\t".'<description>';
			$xml[] = "\t\t\t".implode("\n\t\t\t", explode("\n", $text));
			$xml[] = "\t\t".'</description>';
			break;

		case 'area':
		case 'living-space':
		case 'room-space':
		case 'kitchen-space':
			if (is_array($v))
			{
				foreach ($v as $room)
					$xml[] = getAreaTag($k, $room);
			}
			else
			{
				$xml[] = getAreaTag($k, $v);
			}
			break;

		default:
			$xml[] = "\t\t".'<'.$k.'>'.$v.'</'.$k.'>';
			break;
	}
}

foreach ($row['flat'] as $k => $v)
{
	$xml[] = "\t\t".'<'.$k.'>'.$v.'</'.$k.'>';
}

foreach ($row['obj'] as $k => $v)
{
	$xml[] = "\t\t".'<'.$k.'>'.$v.'</'.$k.'>';
}

$xml[] = "\t".'</offer>';

echo implode("\r\n", $xml);
?>