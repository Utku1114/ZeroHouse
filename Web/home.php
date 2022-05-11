<?php
    include 'api/config.php';

	require('api/WAF.php');
	$xWAF = new xWAF();

	$xWAF->useCloudflare();

	$xWAF->useBlazingfast();

	$xWAF->customIPHeader('IP-Header');

	$xWAF->antiCookieSteal('username'); 


	$xWAF->checkGET();
	$xWAF->checkPOST();
        $xWAF->checkCOOKIE();

	$server_check_version = '0.0.2';
	$start_time = microtime(TRUE);

	$operating_system = PHP_OS_FAMILY;

	if ($operating_system === 'Windows') {
		// Win CPU
		$wmi = new COM('WinMgmts:\\\\.');
		$cpus = $wmi->InstancesOf('Win32_Processor');
		$cpuload = 0;
		$cpu_count = 0;
		foreach ($cpus as $key => $cpu) {
			$cpuload += $cpu->LoadPercentage;
			$cpu_count++;
		}
		// WIN MEM
		$res = $wmi->ExecQuery('SELECT FreePhysicalMemory,FreeVirtualMemory,TotalSwapSpaceSize,TotalVirtualMemorySize,TotalVisibleMemorySize FROM Win32_OperatingSystem');
		$mem = $res->ItemIndex(0);
		$memtotal = round($mem->TotalVisibleMemorySize / 1000000,2);
		$memavailable = round($mem->FreePhysicalMemory / 1000000,2);
		$memused = round($memtotal-$memavailable,2);
		// WIN CONNECTIONS
		$connections = shell_exec('netstat -nt | findstr :80 | findstr ESTABLISHED | find /C /V ""'); 
		$totalconnections = shell_exec('netstat -nt | findstr :80 | find /C /V ""');
	} else {
		// Linux CPU
		$load = sys_getloadavg();
		$cpuload = $load[0];
		$cpu_count = shell_exec('nproc');
		// Linux MEM
		$free = shell_exec('free');
		$free = (string)trim($free);
		$free_arr = explode("\n", $free);
		$mem = explode(" ", $free_arr[1]);
		$mem = array_filter($mem, function($value) { return ($value !== null && $value !== false && $value !== ''); }); // removes nulls from array
		$mem = array_merge($mem); // puts arrays back to [0],[1],[2] after 
		$memtotal = round($mem[1] / 1000000,2);
		$memused = round($mem[2] / 1000000,2);
		$memfree = round($mem[3] / 1000000,2);
		$memshared = round($mem[4] / 1000000,2);
		$memcached = round($mem[5] / 1000000,2);
		$memavailable = round($mem[6] / 1000000,2);
		// Linux Connections
		$connections = `netstat -ntu | grep :80 | grep ESTABLISHED | grep -v LISTEN | awk '{print $5}' | cut -d: -f1 | sort | uniq -c | sort -rn | grep -v 127.0.0.1 | wc -l`; 
		$totalconnections = `netstat -ntu | grep :80 | grep -v LISTEN | awk '{print $5}' | cut -d: -f1 | sort | uniq -c | sort -rn | grep -v 127.0.0.1 | wc -l`; 
	}

	//$memusage = round(($memavailable/$memtotal)*100);
	$memusage = round(($memused/$memtotal)*100);		


	$phpload = round(memory_get_usage() / 1000000,2);

	$diskfree = round(disk_free_space(".") / 1000000000);
	$disktotal = round(disk_total_space(".") / 1000000000);
	$diskused = round($disktotal - $diskfree);

	$diskusage = round($diskused/$disktotal*100);

	if ($memusage > 85 || $cpuload > 85 || $diskusage > 85) {
		$trafficlight = 'red';
	} elseif ($memusage > 50 || $cpuload > 50 || $diskusage > 50) {
		$trafficlight = 'orange';
	} else {
		$trafficlight = '#2F2';
	}

	$end_time = microtime(TRUE);
	$time_taken = $end_time - $start_time;
	$total_time = round($time_taken,4);

	// use servercheck.php?json=1
	if (isset($_GET['jsonData'])) {
		echo '{"ram":'.$memusage.',"cpu":'.$cpuload.',"disk":'.$diskusage.',"connections":'.$totalconnections.'}';
		exit;
	}

?>

<?php

require_once "api/config.php";


$gelendata = $_POST['rolebutton'];
$acik = 'acik';
$kapali = 'kapali';
$sonuc;

if(isset($gelendata)){
	
$sql=$pdo->prepare("select * from roleler");
$sql->execute();
while($row=$sql->fetch(PDO::FETCH_ASSOC)){
  $suanki = $row['durum'];
	if($suanki == 'acik'){
	$veri=$pdo->prepare("UPDATE roleler SET durum = :veri"); 
		$veri->bindValue(':veri', $kapali, PDO::PARAM_STR);
		
		$sonuc=$veri->execute();
		
		

	}else if($suanki == 'kapali'){
		$veri=$pdo->prepare("UPDATE roleler SET durum = :veri"); 
		$veri->bindValue(':veri', $acik, PDO::PARAM_STR);
		
		$sonuc=$veri->execute();
		
		if($sonuc){
		//echo 'basarili';
		}else{ //echo 'basarisiz';
		}
	}else{
	return;
	}
}
								
}

?>

<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: /");
    exit;
	
	require_once "api/server.php";
	
	
}
?>

<!DOCTYPE html>
<html class="loading dark-layout" lang="en" data-layout="dark-layout" data-textdirection="ltr">
<!-- BEGIN: Head-->
<style>
/* width */
::-webkit-scrollbar {
  width: 15px;
}

/* Track */
::-webkit-scrollbar-track {
  
  
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: #0A8A8A; 
 
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #0A8A8A; 
}
</style>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="ZeroHouse">
    <title>ZeroHouse - Home</title>
    <link rel="apple-touch-icon" href="app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/charts/apexcharts.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/extensions/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/themes/bordered-layout.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/themes/semi-dark-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/css/core/menu/menu-types/horizontal-menu.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/plugins/charts/chart-apex.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/plugins/extensions/ext-component-toastr.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/pages/app-invoice-list.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/style.css">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="horizontal-layout horizontal-menu  navbar-floating footer-static  " data-open="hover" data-menu="horizontal-menu" data-col="">

    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar-expand-lg navbar navbar-fixed align-items-center navbar-shadow navbar-brand-center" data-nav="brand-center">
        <div class="navbar-header d-xl-block d-none">
            <ul class="nav navbar-nav">
                <li class="nav-item"><a class="navbar-brand" href="home.php"><span class="brand-logo">
                            <svg viewbox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
                                <defs>
                                    <lineargradient id="linearGradient-1" x1="100%" y1="10.5120544%" x2="50%" y2="89.4879456%">
                                        <stop stop-color="#000000" offset="0%"></stop>
                                        <stop stop-color="#FFFFFF" offset="100%"></stop>
                                    </lineargradient>
                                    <lineargradient id="linearGradient-2" x1="64.0437835%" y1="46.3276743%" x2="37.373316%" y2="100%">
                                        <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                        <stop stop-color="#FFFFFF" offset="100%"></stop>
                                    </lineargradient>
                                </defs>
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Artboard" transform="translate(-400.000000, -178.000000)">
                                        <g id="Group" transform="translate(400.000000, 178.000000)">
                                            <path class="text-primary" id="Path" d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z" style="fill:currentColor"></path>
                                            <path id="Path1" d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z" fill="url(#linearGradient-1)" opacity="0.2"></path>
                                            <polygon id="Path-2" fill="#000000" opacity="0.049999997" points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325"></polygon>
                                            <polygon id="Path-21" fill="#000000" opacity="0.099999994" points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338"></polygon>
                                            <polygon id="Path-3" fill="url(#linearGradient-2)" opacity="0.099999994" points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"></polygon>
                                        </g>
                                    </g>
                                </g>
                            </svg></span>
                        <h2 class="brand-text mb-0">ZeroHouse</h2>
					<?php echo file_get_contents("http://otomasyon.hexcdn.cf/api/server?json=1");?>
                    </a></li>
            </ul>
        </div>
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon" data-feather="menu"></i></a></li>
                </ul>
                <ul class="nav navbar-nav bookmark-icons">
                    <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-email.html" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Email"><i class="ficon" data-feather="mail"></i></a></li>
                    <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-chat.html" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Chat"><i class="ficon" data-feather="message-square"></i></a></li>
                    <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-calendar.html" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Calendar"><i class="ficon" data-feather="calendar"></i></a></li>
                    <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-todo.html" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Todo"><i class="ficon" data-feather="check-square"></i></a></li>
                </ul>
                <ul class="nav navbar-nav">
                    <li class="nav-item d-none d-lg-block"><a class="nav-link bookmark-star"><i class="ficon text-warning" data-feather="star"></i></a>
                        <div class="bookmark-input search-input">
                            <div class="bookmark-input-icon"><i data-feather="search"></i></div>
                            <input class="form-control input" type="text" placeholder="Bookmark" tabindex="0" data-search="search">
                            <ul class="search-list search-list-bookmark"></ul>
                        </div>
                    </li>
                </ul>
            </div>
            <ul class="nav navbar-nav align-items-center ms-auto">
                <li class="nav-item dropdown dropdown-language"><a class="nav-link dropdown-toggle" id="dropdown-flag" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="flag-icon flag-icon-tr"></i><span class="selected-language">Turkish</span></a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-flag"><a class="dropdown-item" href="#" data-language="tr"><i class="flag-icon flag-icon-us"></i>Turkish</a><a class="dropdown-item" href="#" data-language="fr"><i class="flag-icon flag-icon-fr"></i> French</a><a class="dropdown-item" href="#" data-language="de"><i class="flag-icon flag-icon-de"></i> German</a><a class="dropdown-item" href="#" data-language="pt"><i class="flag-icon flag-icon-pt"></i> Portuguese</a></div>
                </li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon" data-feather="sun"></i></a></li>
                <li class="nav-item nav-search"><a class="nav-link nav-link-search"><i class="ficon" data-feather="search"></i></a>
                    <div class="search-input">
                        <div class="search-input-icon"><i data-feather="search"></i></div>
                        <input class="form-control input" type="text" placeholder="Explore ZeroHouse..." tabindex="-1" data-search="search">
                        <div class="search-input-close"><i data-feather="x"></i></div>
                        <ul class="search-list search-list-main"></ul>
                    </div>
                </li>
                
                <li class="nav-item dropdown dropdown-notification me-25"><a class="nav-link" href="#" data-bs-toggle="dropdown"><i class="ficon" data-feather="bell"></i><span class="badge rounded-pill bg-danger badge-up">1</span></a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 me-auto">Bildirimler</h4>
                                <div class="badge rounded-pill badge-light-primary">1 Yeni</div>
                            </div>
                        </li>
                        <li class="scrollable-container media-list"><a class="d-flex" href="#">
                               
                            </a>
                            <div class="list-item d-flex align-items-center">
                                <h6 class="fw-bolder me-auto mb-0">Sistem Bildirimleri</h6>
                                <div class="form-check form-check-primary form-switch">
                                    <input class="form-check-input" id="systemNotification" type="checkbox" checked="">
                                    <label class="form-check-label" for="systemNotification"></label>
                                </div>
                            </div><a class="d-flex" href="#">
                                
                            </a><a class="d-flex" href="#">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-success">
                                            <div class="avatar-content"><i class="avatar-icon" data-feather="check"></i></div>
                                        </div>
                                    </div>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">ZeroHouse</span>&nbsp;</p><small class="notification-text">ZeroHouse sunucusu başarıyla kuruldu</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-menu-footer"><a class="btn btn-primary w-100" href="#">Tüm bildirimleri oku</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none"><span class="user-name fw-bolder"><?php echo htmlspecialchars($_SESSION["username"]); ?></span><span class="user-status">Admin</span></div><span class="avatar"><img class="round" src="https://c.tenor.com/fX2gnOTguswAAAAC/memz-virus.gif" alt="avatar" height="40" width="40"><span class="avatar-status-online"></span></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user"><a class="dropdown-item" href="profile"><i class="me-50" data-feather="user"></i> Profile</a>
                        <div class="dropdown-divider"></div><a class="dropdown-item" href="settings"><i class="me-50" data-feather="settings"></i> Settings</a><a class="dropdown-item" href="faq"><i class="me-50" data-feather="help-circle"></i> FAQ</a><a class="dropdown-item" href="logout"><i class="me-50" data-feather="power"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <ul class="main-search-list-defaultlist d-none">
        <li class="d-flex align-items-center"><a href="#">
                <h6 class="section-label mt-75 mb-0">Files</h6>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
                <div class="d-flex">
                    <div class="me-75"><img src="../../../app-assets/images/icons/xls.png" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Two new item submitted</p><small class="text-muted">Marketing Manager</small>
                    </div>
                </div><small class="search-data-size me-50 text-muted">&apos;17kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
                <div class="d-flex">
                    <div class="me-75"><img src="../../../app-assets/images/icons/jpg.png" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">52 JPG file Generated</p><small class="text-muted">FontEnd Developer</small>
                    </div>
                </div><small class="search-data-size me-50 text-muted">&apos;11kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
                <div class="d-flex">
                    <div class="me-75"><img src="../../../app-assets/images/icons/pdf.png" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">25 PDF File Uploaded</p><small class="text-muted">Digital Marketing Manager</small>
                    </div>
                </div><small class="search-data-size me-50 text-muted">&apos;150kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
                <div class="d-flex">
                    <div class="me-75"><img src="../../../app-assets/images/icons/doc.png" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Anna_Strong.doc</p><small class="text-muted">Web Designer</small>
                    </div>
                </div><small class="search-data-size me-50 text-muted">&apos;256kb</small>
            </a></li>
        <li class="d-flex align-items-center"><a href="#">
                <h6 class="section-label mt-75 mb-0">Members</h6>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view-account.html">
                <div class="d-flex align-items-center">
                    <div class="avatar me-75"><img src="../../../app-assets/images/portrait/small/avatar-s-8.jpg" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">John Doe</p><small class="text-muted">UI designer</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view-account.html">
                <div class="d-flex align-items-center">
                    <div class="avatar me-75"><img src="../../../app-assets/images/portrait/small/avatar-s-1.jpg" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Michal Clark</p><small class="text-muted">FontEnd Developer</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view-account.html">
                <div class="d-flex align-items-center">
                    <div class="avatar me-75"><img src="../../../app-assets/images/portrait/small/avatar-s-14.jpg" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Milena Gibson</p><small class="text-muted">Digital Marketing Manager</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view-account.html">
                <div class="d-flex align-items-center">
                    <div class="avatar me-75"><img src="../../../app-assets/images/portrait/small/avatar-s-6.jpg" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Anna Strong</p><small class="text-muted">Web Designer</small>
                    </div>
                </div>
            </a></li>
    </ul>
    <ul class="main-search-list-defaultlist-other-list d-none">
        <li class="auto-suggestion justify-content-between"><a class="d-flex align-items-center justify-content-between w-100 py-50">
                <div class="d-flex justify-content-start"><span class="me-75" data-feather="alert-circle"></span><span>No results found.</span></div>
            </a></li>
    </ul>
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <div class="horizontal-menu-wrapper">
        <div class="header-navbar navbar-expand-sm navbar navbar-horizontal floating-nav navbar-dark navbar-shadow menu-border container-xxl" role="navigation" data-menu="menu-wrapper" data-menu-type="floating-nav">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item me-auto"><a class="navbar-brand" href="../../../html/ltr/horizontal-menu-template-dark/index.html"><span class="brand-logo">
                                <svg viewbox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
                                    <defs>
                                        <lineargradient id="linearGradient-1" x1="100%" y1="10.5120544%" x2="50%" y2="89.4879456%">
                                            <stop stop-color="#000000" offset="0%"></stop>
                                            <stop stop-color="#FFFFFF" offset="100%"></stop>
                                        </lineargradient>
                                        <lineargradient id="linearGradient-2" x1="64.0437835%" y1="46.3276743%" x2="37.373316%" y2="100%">
                                            <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                            <stop stop-color="#FFFFFF" offset="100%"></stop>
                                        </lineargradient>
                                    </defs>
                                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <g id="Artboard" transform="translate(-400.000000, -178.000000)">
                                            <g id="Group" transform="translate(400.000000, 178.000000)">
                                                <path class="text-primary" id="Path" d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z" style="fill:currentColor"></path>
                                                <path id="Path1" d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z" fill="url(#linearGradient-1)" opacity="0.2"></path>
                                                <polygon id="Path-2" fill="#000000" opacity="0.049999997" points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325"></polygon>
                                                <polygon id="Path-21" fill="#000000" opacity="0.099999994" points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338"></polygon>
                                                <polygon id="Path-3" fill="url(#linearGradient-2)" opacity="0.099999994" points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"></polygon>
                                            </g>
                                        </g>
                                    </g>
                                </svg></span>
                            <h2 class="brand-text mb-0">ZeroHouse Project</h2>
                        </a></li>
                    <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i></a></li>
                </ul>
            </div>
            <div class="shadow-bottom"></div>
            <!-- Horizontal menu content-->
            <div class="navbar-container main-menu-content" data-menu="menu-container">
                <!-- include ../../../includes/mixins-->
                <ul class="nav navbar-nav" id="main-menu-navigation" data-menu="menu-navigation">
                    <li id="anasayfa" class="nav-item active"><a class="d-flex align-items-center" href="http://otomasyon.hexcdn.cf/home"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg><span class="menu-title text-truncate" data-i18n="Anasayfa">Anasayfa</span></a>
</li> 
					
               
                     
                    </li>
                 
                   
                   
                   
                    
                </ul>
            </div>
        </div>
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- Dashboard Analytics Start -->
                <section id="dashboard-analytics">
                    <div class="row match-height">
                        <!-- Greetings Card starts -->
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="card card-congratulations">
                                <div class="card-body text-center">
                                    <img src="../../../app-assets/images/elements/decore-left.png" class="congratulations-img-left" alt="card-img-left" />
                                    <img src="../../../app-assets/images/elements/decore-right.png" class="congratulations-img-right" alt="card-img-right" />
                                    <div class="avatar avatar-xl bg-primary shadow">
                                        <div class="avatar-content">
                                            <i data-feather="award" class="font-large-1"></i>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <h1 class="mb-1 text-white">ZeroHouse Tamamlandı,</h1>
                                        <p class="card-text m-auto w-75">
                                            ZeroHouse <strong>2</strong> günlük geliştirmenin ardından tamamlandı
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Greetings Card ends -->

                        <!-- Subscribers Chart Card starts -->
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-header flex-column align-items-start pb-0">
                                    <div class="avatar bg-light-primary p-50 m-0">
                                        <div class="avatar-content">
                                            <i data-feather="thermometer" class="font-medium-5"></i>
                                        </div>
                                    </div>
								   <?php $sql1 = "SELECT * FROM telemetri";
$sorgu1 = $pdo->prepare($sql1);
$sorgu1->execute();while($satir = $sorgu1->fetch(PDO::FETCH_ASSOC)) { ?>
                                    <h2 class="fw-bolder mt-1"> <?=$satir['sicaklik']; echo ' C';?></h2>
									 <?php } ?>
                                    <p class="card-text">Sıcaklık</p>
                                </div>
                                <div id="gained-chart"></div>
                            </div>
                        </div>
                        <!-- Subscribers Chart Card ends -->

                        <!-- Orders Chart Card starts -->
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-header flex-column align-items-start pb-0">
                                    <div class="avatar bg-light-warning p-50 m-0">
                                        <div class="avatar-content">
                                            <i data-feather="droplet" class="font-medium-5"></i>
                                        </div>
                                    </div>
									 <?php $sqlnem = "SELECT * FROM telemetri";
$sorgunem = $pdo->prepare($sqlnem);
$sorgunem->execute();while($satirnem = $sorgunem->fetch(PDO::FETCH_ASSOC)) { ?>
                                    <h2 class="fw-bolder mt-1"><?php echo '%';?> <?=$satirnem['nem']?></h2>
									 <?php } ?>
                                    <p class="card-text">Nem</p>
                                </div>
                                <div id="order-chart"></div>
                            </div>
                        </div>
                        <!-- Orders Chart Card ends -->
                    </div>

                    <div class="row match-height">
                        <!-- Avg Sessions Chart Card starts -->
                        <div class="col-lg-6 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row pb-50">
                                        <div class="col-sm-6 col-12 d-flex justify-content-between flex-column order-sm-1 order-2 mt-1 mt-sm-0">
                                            <div class="mb-1 mb-sm-0">
                                                <h2 class="fw-bolder mb-25">Sunucu istatistikleri    </h2>
                                                <p class="card-text fw-bold mb-2">Teknik</p>
                                                <div class="font-medium-2">
													<p><span>Sunucu adı:<?php echo $_SERVER['SERVER_NAME']; ?></span></p>
													<p><span>Sunucu adresi: <?php echo $_SERVER['SERVER_ADDR']; ?></span></p>
													<p><span>PHP Sürümü: <?php echo phpversion(); ?></span></p>
													<p><span>PHP Yük: <?php echo $phpload . ' GB'; ?></span></p>
													<p><span>PHP Yüklenme: <span class="text-success me-25"> <?php echo $total_time . ' saniye'; ?></span></span></p>
                                                    
                                                   
                                                </div>
                                            </div>
                                           
                                        </div>
                                        <div class="col-sm-6 col-12 d-flex justify-content-between flex-column text-end order-sm-2 order-1">
                                            <div class="dropdown chart-dropdown">
                                                <button class="btn btn-sm border-0 dropdown-toggle p-50" type="button" id="dropdownItem5" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Şuanda
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownItem5">
                                                    <a class="dropdown-item" href="#">Last 28 Days</a>
                                                    <a class="dropdown-item" href="#">Last Month</a>
                                                    <a class="dropdown-item" href="#">Last Year</a>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row avg-sessions pt-50">
                                        <div class="col-6 mb-2">
                                            <p class="mb-50">RAM Kullanımı: <?php echo $memusage . '%'?> </p>
                                            <div class="progress progress-bar-primary" style="height: 6px">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $memusage?>" aria-valuemin="<?php echo $memusage?>" aria-valuemax="100" style="width: <?php echo $memusage . '%'?>"></div>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <p class="mb-50">CPU Kullanımı: <?php echo $cpuload . '%'?></p>
                                            <div class="progress progress-bar-warning" style="height: 6px">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $cpuload?>" aria-valuemin="<?php echo $cpuload?>" aria-valuemax="100" style="width: <?php echo $cpuload . '%'?>"></div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <p class="mb-50">Sabit Disk Kullanımı: <?php echo $diskusage . '%'?></p>
                                            <div class="progress progress-bar-danger" style="height: 6px">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $diskusage?>" aria-valuemin="<?php echo $diskusage?>" aria-valuemax="100" style="width: <?php echo $diskusage . '%'?>"></div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <p class="mb-50">Toplam Bağlantı: <?php echo $totalconnections?></p>
                                            <div class="progress progress-bar-success" style="height: 6px">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $totalconnections?>" aria-valuemin="<?php echo $totalconnections?>" aria-valuemax="100" style="width: <?php echo $totalconnections . '%'?>"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Avg Sessions Chart Card ends -->

                        <!-- Support Tracker Chart Card starts -->
                        <div class="col-lg-6 col-12">
                            <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">API Anahtar Listesi &amp; Erişim</h4>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                API anahtarı, herhangi bir ilke olmadan bir uygulamayı tanımlayan basit bir şifreli dizedir. Genel verilere anonim olarak erişmek için kullanışlıdırlar ve API isteklerini kota ve faturalandırma projenizle ilişkilendirmek için kullanılırlar.
                            </p>

                            <div class="row gy-2">
                                <div class="col-12">
                                    <div class="bg-light-secondary position-relative rounded p-2">
                                        <div class="dropdown dropstart btn-pinned">
                                            <a class="btn btn-icon rounded-circle hide-arrow dropdown-toggle p-0 waves-effect waves-float waves-light" href="javascript:void(0)" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical font-medium-4"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg><span>Edit</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg><span>Delete</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="d-flex align-items-center flex-wrap">
                                            <h4 class="mb-1 me-1">Sunucu Anahtarı 1</h4>
                                            <span class="badge badge-light-primary mb-1">Full Access</span>
                                        </div>
                                        <h6 class="d-flex align-items-center fw-bolder">
                                            <span class="me-50">23eaf7f0-f4f7-495e-8b86-fad3261282ac</span>
                                            <span><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy font-medium-4 cursor-pointer"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg></span>
                                        </h6>
                                        <span>17 Nisan 2022, Saat 18:56'de oluşturuldu</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="bg-light-secondary position-relative rounded p-2">
                                        <div class="dropdown dropstart btn-pinned">
                                            <a class="btn btn-icon rounded-circle hide-arrow dropdown-toggle p-0 waves-effect waves-float waves-light" href="javascript:vodi(0)" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical font-medium-4"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg><span>Edit</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg><span>Delete</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="d-flex align-items-center flex-wrap">
                                            <h4 class="mb-1 me-1">Sunucu Anahtarı 2</h4>
                                            <span class="badge badge-light-primary mb-1">Read Only</span>
                                        </div>
                                        <h6 class="d-flex align-items-center fw-bolder">
                                            <span class="me-50">bb98e571-a2e2-4de8-90a9-2e231b5e99</span>
                                            <span><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy font-medium-4 cursor-pointer"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg></span>
                                        </h6>
                                        <span>17 Nisan 2022, Saat 18:55'de oluşturuldu</span>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                        </div>
                        <!-- Support Tracker Chart Card ends -->
                    </div>

                    <div class="row match-height">
                        <!-- Timeline Card -->
                        <div class="col-lg-4 col-12">
                            <div class="card card-user-timeline">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <i data-feather="list" class="user-timeline-title-icon"></i>
                                        <h4 class="card-title">Proje Zaman Çizelgesi</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ul class="timeline ms-50">
                                        <li class="timeline-item">
                                            <span class="timeline-point timeline-point-indicator"></span>
                                            <div class="timeline-event">
                                                <h6>Şuan</h6>
                                                <p>Proje şuanki haline evrildi</p>
                                                <div class="d-flex align-items-center">
                                                   
                                                </div>
                                            </div>
                                        </li>
                                        <li class="timeline-item">
                                            <span class="timeline-point timeline-point-warning timeline-point-indicator"></span>
                                            <div class="timeline-event">
                                                <h6>Bakım</h6>
                                                <p>Sunucu taraflı azıre yüzünden web dosyaları kaybedildi</p>
                                               
                                                
                                            </div>
                                        </li>
                                        <li class="timeline-item">
                                            <span class="timeline-point timeline-point-info timeline-point-indicator"></span>
                                            <div class="timeline-event">
                                                <h6>İlk sürüm</h6>
                                                <p>Gelişmemiş ilk sürüm geliştirildi ve kullanıldı</p>
                                               
                                            </div>
                                        </li>
                                        <li class="timeline-item">
                                            <span class="timeline-point timeline-point-danger timeline-point-indicator"></span>
                                            <div class="timeline-event">
                                                <h6>Proje başlangıç</h6>
                                                <p class="mb-0">Proje tasarlandı ve yapımına başlandı</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--/ Timeline Card -->

                        <!-- Sales Stats Chart Card starts -->
                       <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <span>Toplam aktif 1 röle</span>
                                    
                                </div>
                                <div class="d-flex justify-content-between align-items-end mt-1 pt-25">
                                    <div class="role-heading">
                                        <h4 class="fw-bolder">Röle 1</h4>
                                        <a href="javascript:;" class="role-edit-modal" data-bs-toggle="modal" data-bs-target="#addRoleModal">
											<?php
								$sql=$pdo->prepare("select * from roleler");
$sql->execute();
while($row=$sql->fetch(PDO::FETCH_ASSOC)){
  $suanki = $row['durum'];
	if($suanki == 'acik'){
		echo ' <small class="fw-bolder">Durum: <span class="text-success me-25">Açık</span></small>';
	}else if($suanki == 'kapali'){
		echo ' <small class="fw-bolder">Durum: <span class="text-danger me-25">Kapalı</span></small>';
	}else{
	echo ' <small class="fw-bolder">Durum: Unknown</small>';
	}
}
								?>
                                           
                                        </a>
                                    </div>
									<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"method="post">
										<?php
								$sql=$pdo->prepare("select * from roleler");
$sql->execute();
while($row=$sql->fetch(PDO::FETCH_ASSOC)){
  $suanki = $row['durum'];
	if($suanki == 'acik'){
		echo '<button class="btn btn-primary waves-effect waves-float waves-light" type="submit" name="rolebutton">Kapat</button>';
	}else if($suanki == 'kapali'){
		echo '<button class="btn btn-primary waves-effect waves-float waves-light" type="submit" name="rolebutton">Aç</button>';
	}else{
	echo '<button class="btn btn-primary waves-effect waves-float waves-light" type="submit" name="rolebutton">Unknown</button>';
	}
}
								?>
                                   
										</form>
                                </div>
								<div class="d-flex justify-content-between align-items-end mt-1 pt-25">
                                    <div class="role-heading">
                                        <h4 class="fw-bolder">Röle 2</h4>
                                        <a href="javascript:;" class="role-edit-modal" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                                            <small class="fw-bolder">Durum: Deaktif</small>
                                        </a>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary waves-effect">Aç</button>
                                </div>
								<div class="d-flex justify-content-between align-items-end mt-1 pt-25">
                                    <div class="role-heading">
                                        <h4 class="fw-bolder">Röle 3</h4>
                                        <a href="javascript:;" class="role-edit-modal" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                                            <small class="fw-bolder">Durum: Deaktif</small>
                                        </a>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary waves-effect">Aç</button>
                                </div>
								<div class="d-flex justify-content-between align-items-end mt-1 pt-25">
                                    <div class="role-heading">
                                        <h4 class="fw-bolder">Röle 4</h4>
                                        <a href="javascript:;" class="role-edit-modal" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                                            <small class="fw-bolder">Durum: Deaktif</small>
                                        </a>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary waves-effect">Aç</button>
                                </div>
								<div class="d-flex justify-content-between align-items-end mt-1 pt-25">
                                    <div class="role-heading">
                                        <h4 class="fw-bolder">Röle 5</h4>
                                        <a href="javascript:;" class="role-edit-modal" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                                            <small class="fw-bolder">Durum: Deaktif</small>
                                        </a>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary waves-effect">Aç</button>
                                </div>
								
                            </div>
                        </div>
                    </div>
                        <!-- Sales Stats Chart Card ends -->

                        <!-- App Design Card -->
                       
                        <!--/ App Design Card -->
                    </div>

                    <!-- List DataTable -->
                    
                    <!--/ List DataTable -->
                </section>
                <!-- Dashboard Analytics end -->

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light footer-shadow">
        <p class="clearfix mb-0"><span class="float-md-start d-block d-md-inline-block mt-25">COPYRIGHT &copy; 2021<a class="ms-25" href="#" target="_blank">ZeroHouse</a><span class="d-none d-sm-inline-block">, All rights Reserved</span></span><span class="float-md-end d-none d-md-block">Utku <i data-feather="heart"></i> Tobb</span></p>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->


    <!-- BEGIN: Vendor JS-->
    <script src="app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="app-assets/vendors/js/ui/jquery.sticky.js"></script>
    <script src="app-assets/vendors/js/charts/apexcharts.min.js"></script>
    <script src="app-assets/vendors/js/extensions/toastr.min.js"></script>
    <script src="app-assets/vendors/js/extensions/moment.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="app-assets/js/core/app-menu.js"></script>
    <script src="app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="app-assets/js/scripts/pages/dashboard-analytics.js"></script>
    <script src="app-assets/js/scripts/pages/app-invoice-list.js"></script>
    <!-- END: Page JS-->

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>
</body>
<!-- END: Body-->

</html>
