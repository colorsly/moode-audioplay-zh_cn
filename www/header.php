<!--
/**
 * moOde audio player (C) 2014 Tim Curtis
 * http://moodeaudio.org
 *
 * tsunamp player ui (C) 2013 Andrea Coiutti & Simone De Gregori
 * http://www.tsunamp.com
 *
 * This Program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3, or (at your option)
 * any later version.
 *
 * This Program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * 汉化：Androidnews
 */
-->
<!--removeIf(GENINDEXDEV)-->

<?php
    $result = sqlRead('cfg_system', sqlConnect(), 'sessionid');
    session_id($result[0]['value']);
    $returnVal = session_start();
    //debugLog('header.php: session_start() = ' . (($returnVal) ? 'true' : 'false') . ', sessionid = ' . $result[0]['value']);
?>
<!--endRemoveIf(GENINDEXDEV)-->
<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $_SESSION['browsertitle']; ?></title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, user-scalable=no">

    <!-- RESOURCES -->
    <!-- Common CSS -->
	<link rel="stylesheet" href="css/styles.min.css?t=1672265770726">

	<!-- Common JS -->
	<script src="js/lib.min.js?t=1672265770726" defer></script>

    <!-- Playback / Library -->
	<!--removeIf(GENINDEXDEV)-->
    <?php if ($section == 'index') { ?>
	<!--endRemoveIf(GENINDEXDEV)-->
		<link rel="stylesheet" href="css/main.min.css?t=1672265770726">
		<script src="js/main.min.js?t=1672265770726" defer></script>
    <!-- Configs -->
	<!--removeIf(GENINDEXDEV)-->
    <?php } else { ?>
	<!--endRemoveIf(GENINDEXDEV)-->
		<!--removeIf(NOCONFIGSECTION)-->
		<script src="js/config.min.js?t=1672265770726" defer></script>
		<!--endRemoveIf(NOCONFIGSECTION)-->

	<!--removeIf(GENINDEXDEV)-->
	<?php }
	    // INSTALL DISPLAY MESSAGES FUNCTION, IS ACTUALY CALLED AFTER onready by applicatio.js  |scripts-panels.js
		if (isset($_SESSION['notify']['title']) && $_SESSION['notify']['title'] != '') {
			uiNotify($_SESSION['notify']);
			$_SESSION['notify']['title'] = '';
			$_SESSION['notify']['msg'] = '';
			$_SESSION['notify']['duration'] = '3';
		}
	?>
	<!--endRemoveIf(GENINDEXDEV)-->

	<!-- MOBILE APP ICONS -->
	<!-- Apple -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<link rel="apple-touch-icon" sizes="180x180" href="/v5-apple-touch-icon.png">
	<link rel="mask-icon" href="/v5-safari-pinned-tab.svg" color="#5bbad5">
	<!-- Android/Chrome -->
	<link rel="icon" type="image/png" sizes="32x32" href="/v5-favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/v5-favicon-16x16.png">
	<!--link rel="manifest" href="/site.webmanifest"-->
	<meta name="theme-color" content="rgb(32,32,32)">
	<!-- Microsoft -->
	<meta name="msapplication-TileColor" content="#da532c">
</head>


<body onorientationchange="javascript:location.reload(true); void 0;">
	<!-- ALBUM COVER BACKDROP -->
	<div aria-label="Album Cover Backdrop" id="cover-backdrop"></div>
	<div id="context-backdrop"></div>
	<div id="splash"><div>moOde</div></div>

    <!-- INPUT SOURCE INDICATOR -->
    <div id="inpsrc-indicator" class="inpsrc">
        <div id="inpsrc-backdrop"></div>
        <div id="inpsrc-msg"></div>
    </div>

	<!-- HEADER -->
	<div id="panel-header" class="ui-header ui-bar-f ui-header-fixed slidedown" data-position="fixed" data-role="header" role="banner">
		<div aria-label="Switch to Playbar" id="playback-switch"><div></div></div>

		<div id="config-back">
			<a aria-label="Back" href="<?php echo $_SESSION['config_back_link'] ?>"><i class="far fa-angle-left"></i></a>
		</div>

		<div id="config-tabs" class="viewswitch-cfgs hide">
			<a id="lib-config-btn" class="btn" href="lib-config.php">音乐库</a>
			<a id="snd-config-btn" class="btn" href="snd-config.php">音乐</a>
			<a id="net-config-btn" class="btn" href="net-config.php">网络</a>
			<a id="sys-config-btn" class="btn" href="sys-config.php">系统</a>
            <a id="ren-config-btn" class="btn" href="ren-config.php">渲染器</a>
		</div>

		<div id="library-header"></div>
        <div id="multiroom-sender" class="context-menu"><a class="btn" href="#notarget" data-cmd="multiroom_rx_modal"><i class="fas fa-rss"></i></a></div>

		<?php
			if ($section == 'index' && $_SESSION['camilladsp'] != "off") {
				require_once __DIR__ . '/inc/cdsp.php';
				$cdsp = new CamillaDsp($_SESSION['camilladsp'], $_SESSION['cardnum'], $_SESSION['camilladsp_quickconv']);
				$cdsp_configs = $cdsp->getAvailableConfigs();
				$select_config_label = $cdsp_configs[$_SESSION['camilladsp']];
		?>
		<div class="dropdown" id="dropdown-cdsp-btn">
			<a aria-label="Menu" class="dropdown-toggle btn" id="menu-cdsp" role="button" data-toggle="dropdown" data-target="#" href="#notarget">
                <i class="fas fa-sliders-v-square"></i>
			</a>
			<ul id="dropdown-cdsp-menu" class="dropdown-menu" role="menu" aria-labelledby="menu-settings_x">
				<?php
				foreach ($cdsp_configs as $config_file=>$config_name) {
                    $fa_selected = $_SESSION['camilladsp'] == $config_file ? '<span id="menu-check-cdsp"><i class="fal fa-check"></i></span>' : '';
					echo '<li class="context-menu dropdown-cdsp-line"><a href="#notarget" data-cmd="camilladsp_config" data-cdspconfig="' .
                        $config_file . '" data-cdsplabel="' . $config_name . '">' . $config_name . $fa_selected . '</a></li>';
				}
				?>
			</ul>
		</div>
		<?php } ?>

        <div aria-label="Update notification" id="updater-notification"><a class="btn" href="#notarget"><i class="fas fa-info-circle"></i></a></div>
		<div aria-label="Busy" class="busy-spinner"><svg xmlns='http://www.w3.org/2000/svg' width='42' height='42' viewBox='0 0 42 42' stroke='#fff'><g fill='none' fill-rule='evenodd'><g transform='translate(3 3)' stroke-width='4'><circle stroke-opacity='.35' cx='18' cy='18' r='18'/><path d='M36 18c0-9.94-8.06-18-18-18'><animateTransform attributeName='transform' type='rotate' from='0 18 18' to='360 18 18' dur='1s' repeatCount='indefinite'/></path></g></g></svg></div>

		<!-- MAIN MENU -->
		<div class="dropdown">
			<a aria-label="Menu" class="dropdown-toggle btn" id="menu-settings" role="button" data-toggle="dropdown" data-target="#" href="#notarget"><div id="mblur">mm</div><div id="mbrand">m</div></a>
			<ul class="dropdown-menu" role="menu" aria-labelledby="menu-settings">
				<?php if ($section == 'index') { ?>
					<li><a href="#configure-modal" data-toggle="modal"><i class="fas fa-cog sx"></i>系统配置</a></li>
					<li class="context-menu menu-separator"><a href="#notarget" data-cmd="preferences"><i class="fas fa-edit sx"></i>偏好配置</a></li>
                    <li class="context-menu"><a href="#notarget" data-cmd="update_library"><i class="fas fa-sync sx"></i>乐库更新</a></li>
					<li id="bluetooth-hide"><a href="blu-config.php"><i class="fas fa-wifi sx"></i>蓝牙配置</a></li>
					<li id="players-menu-item"><a href="javascript:$('#players-modal .modal-body').load('players.php',function(e){$('#players-modal').modal('show');}); void 0"><i class="fas fa-forward sx"></i>其他玩家</a></li>
                    <li><a href="javascript:audioPlayback()"><i class="fas fa-music sx"></i>音频信息</a></li>
					<li id="playhistory-hide" class="context-menu"><a href="#notarget" data-cmd="viewplayhistory"><i class="fas fa-book sx"></i>播放历史</a></li>
					<li class="context-menu"><a href="#notarget" data-cmd="quickhelp"><i class="fas fa-info sx"></i>快速帮助</a></li>
					<li class="menu-separator"><a href="javascript:location.reload(true); void 0"><i class="fas fa-redo sx"></i>刷新当前</a></li>
					<li><a href="#power-modal" data-toggle="modal"><i class="fas fa-power-off sx"></i>电源按钮</a></li>
				<?php } else { ?>
					<li class="context-menu menu-separator"><a href="#configure-modal" data-toggle="modal"><i class="fas fa-cog sx"></i>系统配置</a></li>
                    <li><a href="javascript:audioPlayback()"><i class="fas fa-music sx"></i>音频信息</a></li>
					<li id="sysinfo-menu-item"><a href="javascript:$('#sysinfo-modal .modal-body').load('sysinfo.php',function(e){$('#sysinfo-modal').modal('show');}); void 0"><i class="fas fa-file-alt sx"></i>系统信息</a></li>
                    <li class="context-menu"><a href="#notarget" data-cmd="quickhelp"><i class="fas fa-info sx"></i>快速帮助</a></li>
					<li class="context-menu menu-separator"><a href="#notarget" data-cmd="aboutmoode"><i class="fas fa-info sx"></i>关于系统</a></li>
					<li><a href="javascript:location.reload(true); void 0"><i class="fas fa-redo sx"></i>刷新当前</a></li>
					<li><a href="#power-modal" data-toggle="modal"><i class="fas fa-power-off sx"></i>电源按钮</a></li>
				<?php } ?>
			</ul>
		</div>
		<div class="panel-header">
			<span aria-label="Clock Radio" id="clockradio-icon" class="clockradio-off">•</span>
		</div>
	</div>

	<!-- PLAYBAR -->
	<div id="panel-footer" class="btn-group btn-list ui-footer ui-bar-f ui-footer-fixed slidedown" data-position="fixed" data-role="footer" role="banner">
		<div id="playbar">
			<div aria-label="Cover" id="playbar-cover"></div>
			<div aria-label="First use help" id="playbar-firstuse-help"><div></div></div>
            <div aria-label="Switch to Playback" id="playbar-switch"><div></div></div>
			<div id="playbar-controls">
				<button aria-label="Previous" class="btn btn-cmd prev"><i class="fas fa-step-backward"></i></button>
				<button aria-label="Play / Pause" class="btn btn-cmd play"><i class="fas fa-play"></i></button>
				<button aria-label="Next" class="btn btn-cmd next"><i class="fas fa-step-forward"></i></button>
			</div>
            <div id="playbar-title">
				<div id="playbar-currentsong"></div>
                <div id="playbar-title-line-2">
                    <span id="playbar-currentalbum"></span>
                    <span id="playbar-hd-badge"></span>
                </div>
				<div id="playbar-mtime">
					<div id="playbar-mcount"></div>
					<div id="playbar-mtotal"></div>
				</div>
			</div>
            <div id="playbar-timeline">
				<div class="timeline-bg"></div>
				<div class="timeline-progress"><div class="inner-progress"></div></div>
				<div class="timeline-thm">
					<input aria-label="Timeline" id="playbar-timetrack" type="range" min="0" max="1000" value="0" step="1">
				</div>
				<div id="playbar-time">
					<div id="playbar-countdown"></div>
					<span id="playbar-div">&nbsp;/&nbsp;</span>
					<div id="playbar-total"></div>
				</div>
			</div>
			<div id="playbar-toggles">
				<button aria-label="Context Menu" class="btn playback-context-menu" data-toggle="context" data-target="#context-menu-playback" class="btn btn-cmd"><i class="far fa-ellipsis-h"></i></button>
				<button aria-label="Random" class="btn btn-cmd btn-toggle random" data-cmd="random"><i class="fal fa-random"></i></button>
                <button aria-label="Queue" class="btn btn-cmd btn-toggle hide" id="cv-playqueue-btn"><i class="fal fa-list"></i></button>
				<button aria-label="Cover View" class="btn btn-cmd coverview"><i class="fal fa-tv"></i></button>
                <button aria-label="Volume" id="playbar-volume-popup-btn" class="btn volume-popup-btn" data-toggle="modal"><i class="fas fa-volume-off"></i><span id="playbar-volume-level"></span></button>
                <button aria-label="Random Album" id="random-album" class="btn btn-cmd hide"><i class="fal fa-dot-circle"></i></button>
				<button aria-label="Add To Favorites" class="btn btn-cmd add-item-to-favorites hide"><i class="fal fa-heart"></i></button>
			</div>
		</div>
	</div>

    <!-- COVERVIEW QUEUE -->
    <div id="cv-playqueue">
        <ul class="cv-playqueue"></ul>
    </div>

	<!-- Only included when generate index.html for developmed purpose -->
	<!--=include templates/indextpl.html -->
	<!--=include footer.php -->

<!-- make wellformed html; correct unclosed body and html (normally done by footer ) -->
<!-- GEN_DEV_INDEX_TAG
	</body>
</html>
GEN_DEV_INDEX_TAG -->
