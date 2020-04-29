{if (isset($boxberry_point))}
	{script src="js/addons/boxberry/boxberry.js"}
	
	{if ($boxberry_point === false)}
		<a href="#" onclick="boxberry.open('callback_backend','','{$s_city}');return false;">Boxberry ПВЗ: <span class="bxb_office_number">Не задан...</span></a>
	{else}
		<a href="#" onclick="boxberry.open('callback_backend','','{$s_city}');return false;">Boxberry ПВЗ: <span class="bxb_office_number">{$boxberry_point}</span></a>
	{/if}
{/if}