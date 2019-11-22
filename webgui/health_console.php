<!DOCTYPE html>
<!--
#
# "SystemImager" 
# Edit /etc/dhcp/dhcpd.conf
#
#  Copyright (C) 2019 Olivier LAHAYE <olivier.lahaye1@free.fr>
#
#  vi: set filetype=html et ts=4:
#
# View services statuses
# View stats (clients count, clusters count, images count, ....)

-->
<html>
<head>
<title>SystemImager System Health.</title>
  <style type='text/css' media='screen'>@import url('css/screen.css');</style>
  <style type='text/css' media='screen'>@import url('css/sliders.css');</style>
  <style type='text/css' media='screen'>@import url('css/flex_table.css');</style>
  <!-- <style type='text/css' media='screen'>@import url('css/print.css');</style> -->
  <script src="functions.js"></script>
</head>
<body>

<!-- SystemImager header -->
<section class="flex"> <!-- start of flex column -->
<table id="headerTable">
  <tbody>
    <tr>
      <td><a href="index.php"><img src="css/SystemImagerBanner.png" alt="SystemImager"></a></td>
      <td id="clientData1">&nbsp;</td>
      <td id="clientData2">&nbsp;</td>
    </tr>
  </tbody>
</table>
<hr style="width: 100%"/>
<table id="filtersTable" width="99%">
  <tbody>
    <tr id="filtersRow">
      <td>SystemImager Health Reporting:</td>
      <td style="text-align:right">
        <span>&nbsp;</span>
      </td>
    </tr>
  </tbody>
</table>
<hr style="width: 100%"/>
<div id="parameters" class="flex_content">

<fieldset><legend>&nbsp;Services&nbsp;</legend>
<?php
include 'functions.php';

function displayNeed($need,$enabled,$active) {
	if($need === 'optional') {
		return("<td><span class='pri_detail'>optional</span></td>");
	} elseif($enabled === 'indirect') {
		return("<td><span class='pri_info'>mandatory</span></td>");
	} else if($active === 'active') {
		return("<td><span class='pri_info'>mandatory</span></td>");
	} else {
		return("<td><span class='pri_error'>mandatory</span></td>");
	}
}

function displayServiceStatus($need,$enabled, $is_up) {
	$class='pri_info';
	$image='images/yes.svg';
	$alt='yes';

	if($enabled === NULL) {
		$enabled='unavailable';
	}
	if($enabled === 'disabled') {
		if($need === 'mandatory') {
			$class='pri_error';
			$image='images/no.svg';
			$alt='no';
		}
	}
	$output="<td><img src='".$image."' alt='".$alt."' style='width:1.5em;height:1.5em;'>&nbsp;<span class='".$class."'>".$enabled."</span></td>\n";

	$class='pri_info';
	$image='images/yes.svg';
	$alt='yes';

	if($enabled === 'indirect') {
		$is_up='listening';
	}

	if($is_up === 'active') {
		if($enabled === 'disabled' || $enabled === 'unavailable') { // Service running while it is not enabled or installed at all => warn
			$class='pri_warning';
			$image='images/no.svg';
			$alt='warn';
		} // Else: ok
	} else {
		if($enabled !== 'enabled' && $enabled !== 'indirect' && $need === 'mandatory') {
			$class='pri_error';
			$image='images/no.svg';
			$alt='no';
		}
	}
	$output.="<td><img src='".$image."' alt='".$alt."' style='width:1.5em;height:1.5em;'>&nbsp;<span class='".$class."'>".$is_up."</span></td>\n";
	
	return($output);
}

$config=si_ReadConfig();
$json_services = file_get_contents("services.json");
$systemctl_available = systemd_is_available();
if ($json_services !== false) {
	$si_services=json_decode($json_services);
        if ($si_services === null  && json_last_error() !== JSON_ERROR_NONE) {
		echo "<span class='pri_error'> ERROR! Can't decode ./services.json</span>";
	} else {
		if(!$systemctl_available) {
			echo "<span class='pri_warn'>WARNING! Non-systemd service management: can't check service statuses.</span>";
		}
		echo "<table><thead>\n";
		echo "<tr><th>Service</th><th>need</th><th>enabled</th><th>running/listenning</th><tr></thead>\n<tbody>";
		foreach($si_services as $service => $service_specs) {
			echo "<tr><td>$service</td>";
			unset($is_enabled_output,$is_active_output,$service_state);
			$is_enabled=exec("systemctl is-enabled ".$service_specs[0],$is_enabled_output,$service_state);
			$is_active=exec("systemctl is-active ".$service_specs[0],$is_active_output,$active_state);
			$need=displayNeed($service_specs[1], $is_enabled_output[0], $is_active_output[0]);
			$status=displayServiceStatus($service_specs[1],$is_enabled_output[0],$is_active_output[0]);
			echo $need.$status."</tr>\n";
		}
		echo "</tbody></table>\n";
	}
} else {
	echo "<span class='pri_error'> ERROR! Can't read ./services.json</span>";
}
?>
</fieldset>
<br/>

<fieldset><legend>&nbsp;Deployment status&nbsp;</legend>
<?php
$json_clients_stats=shell_exec("/usr/lib/systemimager/web_helpers/Clients_Statuses_Stats.sh");
if($json_clients_stats === NULL) {
	echo "<span class='pri_error'> ERROR! Can't generate clients statistics!</span>\n";
} else {
	$clients_stats=json_decode($json_clients_stats);
	if ($clients_stats === null  && json_last_error() !== JSON_ERROR_NONE) {
		echo "<span class='pri_error'> ERROR! Can't decode clients stats.</span>\n";
	} else {
		echo "<table>\n";
		echo "<thead><tr><th>Clients count</th><th>Status</th></tr></thead>\n<tbody>";
		foreach($clients_stats as $stat_row) {
			echo "<tr><td>".$stat_row->{'Count'}."</td><td>".$stat_row->{'Status'}."</td></tr>\n";
			// TODO: Status to Text.
		}
		echo "</tbody></table>\n";
	}
}
?>

</fieldset>
<br/>

<fieldset><legend>&nbsp;Images&nbsp;</legend>
<!-- si_lsimage + correlation with paths. Add size info in last column.-->
Available images:
</fieldset>
<br/>
</div>
<div style="flex: 1 1 auto"></div> <!-- spacer -->
<hr style="width: 100%"/>
<span>SystemImager v5.0 - Health Reporting.</span>
</section> <!-- end flex_column -->
</body>
</html>

