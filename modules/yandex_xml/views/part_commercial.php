<?php
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
					if ($name == 'metro')
					{
						foreach ($var as $k1 => $v1)
						{
							$xml[] = "\t\t\t".'<'.$name.'>';
							foreach ($v1 as $k2 => $v2)
							{
								$xml[] = "\t\t\t\t".'<'.$k2.'>'.$v2.'</'.$k2.'>';
							}
							$xml[] = "\t\t\t".'</'.$name.'>';
						}
					}
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

if ($sell)
{
	$xml[] = "\t\t".'<price>';
	$xml[] = "\t\t\t".'<value>'.$row['cond']['price']['value'].'</value>';
	$xml[] = "\t\t\t".'<currency>'.$row['cond']['price']['currency'].'</currency>';
	$xml[] = "\t\t".'</price>';

	if (isset($row['cond']['price_m']))
	{
		$xml[] = "\t\t".'<price>';
		$xml[] = "\t\t\t".'<value>'.$row['cond']['price_m']['value'].'</value>';
		$xml[] = "\t\t\t".'<currency>'.$row['cond']['price_m']['currency'].'</currency>';
		$xml[] = "\t\t\t".'<unit>кв.м</unit>';
		$xml[] = "\t\t".'</price>';
	}
}

if ($rent)
{
	$xml[] = "\t\t".'<price>';
	$xml[] = "\t\t\t".'<value>'.$row['cond']['price_arenda']['value'].'</value>';
	$xml[] = "\t\t\t".'<currency>'.$row['cond']['price_arenda']['currency'].'</currency>';
	$xml[] = "\t\t\t".'<period>месяц</period>';
	$xml[] = "\t\t".'</price>';

	if (isset($row['cond']['price_arenda_m']))
	{
		$xml[] = "\t\t".'<price>';
		$xml[] = "\t\t\t".'<value>'.$row['cond']['price_arenda_m']['value'].'</value>';
		$xml[] = "\t\t\t".'<currency>'.$row['cond']['price_arenda_m']['currency'].'</currency>';
		$xml[] = "\t\t\t".'<period>месяц</period>';
		$xml[] = "\t\t\t".'<unit>кв.м</unit>';
		$xml[] = "\t\t".'</price>';
	}
}

$xml[] = "\t\t".'<mortgage>'.$row['cond']['mortgage'].'</mortgage>';
$xml[] = "\t\t".'<deal-status>'.$row['cond']['deal-status'].'</deal-status>';

foreach ($row['info'] as $k => $v)
{
	switch ($k)
	{
		case 'image':
			foreach ($v as $img)
				$xml[] = "\t\t".'<image>'.$this->gen->domain.$img.'</image>';
			break;
		
		case 'description':
			$text = htmlspecialchars($row['info']['description']);
			$xml[] = "\t\t".'<description>';
			$xml[] = "\t\t\t".implode("\n\t\t\t", explode("\n", $text));
			$xml[] = "\t\t".'</description>';
			break;

		case 'area':
			if (is_array($v))
			{
				foreach ($v as $room)
					$xml[] = $this->gen->getAreaTag($k, $room);
			}
			else
			{
				$xml[] = $this->gen->getAreaTag($k, $v);
			}
			break;

		default:
			$xml[] = "\t\t".'<'.$k.'>'.$v.'</'.$k.'>';
			break;
	}
}

foreach ($row['obj'] as $k => $v)
{
	$xml[] = "\t\t".'<'.$k.'>'.$v.'</'.$k.'>';
}

$xml[] = "\t".'</offer>';

echo implode("\r\n", $xml);
?>