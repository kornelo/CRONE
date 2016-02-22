	
<?php
/* Implementacja listy zadań CRON */

$cronfile='crontable.cron';

//Wpisywanie zadan do listy CRON
if(isset($_POST['cron']))
	{
	$newtask = $_POST['cron'];

	if(!empty($_POST['command'])){
		$cronline = "\r\n".$newtask['minutes']." ".$newtask['hour']." ".$newtask['day_nr']." ".$newtask['month']." ".$newtask['day_week']." ".$_POST['command'];
		$result = file_put_contents($cronfile, $cronline, FILE_APPEND);
		if($result !== false)
			echo "Added new TASK!";
		else
			echo "Adding failed!";
	}
	else echo "Command can't be empty!";
	}

//Usuwanie zadan z tablicy
if(isset($_POST['delete'])){

	//zczytywanie danych
	if(file_exists($cronfile)){
		$cronjobs = file_get_contents($cronfile);
		$cronjobs = explode("\r\n", $cronjobs);
	}
	
	//usuwanie danych
	$newtable = "";
	foreach($cronjobs as $job){
		if(md5($job) != $_POST['delete'])
			$newtable .= $job."\r\n";
	}
	
	//zapis danych
	$result = file_put_contents($cronfile, $newtable);
	
	if($result !== false)
		echo "Remove complete!";
	else
		echo "Remove failed!";
	}

//Zczytywanie zadan z listy
if(file_exists($cronfile))
	{
	$cronjobs = file_get_contents($cronfile);
	$cronjobs = explode("\r\n", $cronjobs);
	}
?>


<html>
<head>
<meta charset="utf-8">
<title>CRON Task List</title>
</head>
<body>
<h2>CRON TASK LIST</h2>
<table>
	<tr>
		<th>Minutes</th>
		<th>Hour</th>
		<th>Day_NR</th>
		<th>Month</th>
		<th>Day_in_Week</th>
		<th>Command</th>
	</tr>
	
<? if(!empty($cronjobs)) 
	{ 
	foreach($cronjobs as $cronjob)
		{
		$cron = explode(' ', $cronjob);
		if(!empty($cron[0]))
			{
			//polaczenie z odstepami
			$command = "";
			for($i = 5; $i < count($cron); $i++)
				{
				$command .= $cron[$i]." ";
				}
	?>
	<tr>
		<td><?=$cron[0]?></td>
		<td><?=$cron[1]?></td>
		<td><?=$cron[2]?></td>
		<td><?=$cron[3]?></td>
		<td><?=$cron[4]?></td>
		<td><?=$command?></td>
		<td>
		<form method="post">
			<input type="hidden" name="action" value="delete" />
			<input type="hidden" name="delete" value="<?=md5($cronjob)?>" >
			<input type="submit" value="DELETE">
		</form>
		</td>
	</tr>
<? } } } ?>
</table>

<h2>Add NEW Task</h2>

<form method="post">
	<p>Minutes:<select name="cron[minutes]"><option value="*">*</option><? for($i=0; $i< 60; $i++){ ?><option value="<?=$i?>"><?=$i?></option><? } ?></select></p>
	<p>Hour:<select name="cron[hour]"><option value="*">*</option><? for($i=0; $i< 24; $i++){ ?><option value="<?=$i?>"><?=$i?></option><? } ?></select></p>
	<p>Day_NR:<select name="cron[day_nr]"><option value="*">*</option><? for($i=1; $i< 32; $i++){ ?><option value="<?=$i?>"><?=$i?></option><? } ?></select></p>
	<p>Month:<select name="cron[month]"><option value="*">*</option><? for($i=1; $i< 13; $i++){ ?><option value="<?=$i?>"><?=$i?></option><? } ?></select></p>
	<p>Day in week:<select name="cron[day_week]"><option value="*">*</option><? for($i=0; $i<8; $i++){ ?><option value="<?=$i?>"><?=$i?></option><? } ?></select></p>
	<p>Command:<input type="text" name="command" /></p>
	<p><input type="submit" value="Add" /></p>
	<input type="hidden" name="action" value="insert"> 
</form>
</body>
</html>