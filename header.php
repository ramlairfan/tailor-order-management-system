<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo SITE_NAME; ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
:root{
  --navy:#080C18;--navy2:#0F1526;--navy3:#161D35;--navy4:#1E2540;
  --gold:#C6973F;--gold2:#E8C27A;--gold3:#F5DFA0;--cream:#EDE3C8;
  --muted:#7A7D8C;--border:rgba(198,151,63,0.22);--glow:rgba(198,151,63,0.10);
  --danger:#C84B4B;--success:#3D9970;--warning:#C9952A;--info:#3A7BD5;
  --sidebar-w:260px;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'DM Sans',sans-serif;background:var(--navy);color:var(--cream);font-size:14px;min-height:100vh;}
a{color:var(--gold2);text-decoration:none;}
a:hover{color:var(--gold3);}

/* ── LAYOUT ── */
.app-layout{display:flex;min-height:100vh;}
.main-wrap{flex:1;display:flex;flex-direction:column;min-width:0;margin-left:var(--sidebar-w);}
.page-content{flex:1;padding:28px 32px;}

/* ── TOPBAR ── */
.topbar{
  height:60px;background:var(--navy2);border-bottom:1px solid var(--border);
  display:flex;align-items:center;justify-content:space-between;
  padding:0 28px;position:sticky;top:0;z-index:100;
}
.topbar-brand{font-family:'Cormorant Garamond',serif;font-size:20px;color:var(--gold2);font-style:italic;letter-spacing:.04em;}
.topbar-right{display:flex;align-items:center;gap:18px;}
.topbar-user{display:flex;align-items:center;gap:10px;font-size:13px;color:var(--muted);}
.topbar-user strong{color:var(--cream);}
.topbar-avatar{
  width:34px;height:34px;border-radius:50%;
  background:linear-gradient(135deg,var(--gold),var(--gold2));
  display:flex;align-items:center;justify-content:center;
  font-size:13px;font-weight:600;color:#1a1200;
}
.btn-logout{
  font-size:12px;color:var(--muted);border:1px solid var(--border);
  border-radius:8px;padding:6px 14px;transition:.2s;
  background:transparent;cursor:pointer;
}
.btn-logout:hover{border-color:var(--gold);color:var(--gold2);}

/* ── SIDEBAR ── */
.sidebar{
  width:var(--sidebar-w);background:var(--navy2);border-right:1px solid var(--border);
  position:fixed;top:0;left:0;height:100vh;overflow-y:auto;z-index:200;
  display:flex;flex-direction:column;padding:0 0 20px;
}
.sidebar-logo{
  height:60px;display:flex;align-items:center;padding:0 20px;
  border-bottom:1px solid var(--border);gap:10px;
}
.sidebar-logo-icon{
  width:32px;height:32px;border-radius:8px;
  background:linear-gradient(135deg,var(--gold),var(--gold2));
  display:flex;align-items:center;justify-content:center;font-size:15px;color:#1a1200;
}
.sidebar-logo-text{font-family:'Cormorant Garamond',serif;font-size:17px;color:var(--cream);font-style:italic;}
.sidebar-section{padding:20px 14px 6px;font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:var(--muted);}
.sidebar-nav{list-style:none;padding:0 10px;}
.sidebar-nav li{margin-bottom:3px;}
.sidebar-nav a{
  display:flex;align-items:center;gap:11px;padding:10px 14px;border-radius:10px;
  color:var(--muted);font-size:13px;font-weight:500;transition:.2s;
}
.sidebar-nav a:hover,.sidebar-nav a.active{
  background:rgba(198,151,63,0.1);color:var(--gold2);
}
.sidebar-nav a i{font-size:16px;width:18px;text-align:center;}
.sidebar-divider{border:none;border-top:1px solid var(--border);margin:12px 16px;}
.sidebar-bottom{margin-top:auto;padding:0 10px;}

/* ── PAGE HEADER ── */
.page-header{margin-bottom:24px;}
.page-header h2{font-family:'Cormorant Garamond',serif;font-size:26px;font-weight:400;color:var(--cream);}
.page-header p{font-size:13px;color:var(--muted);margin-top:2px;}
.breadcrumb-bar{display:flex;align-items:center;gap:6px;font-size:12px;color:var(--muted);margin-bottom:8px;}
.breadcrumb-bar a{color:var(--muted);}
.breadcrumb-bar a:hover{color:var(--gold2);}
.breadcrumb-bar .sep{opacity:.4;}

/* ── CARDS ── */
.tms-card{
  background:var(--navy2);border:1px solid var(--border);border-radius:16px;
  overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.3);
}
.tms-card-header{
  padding:18px 24px;border-bottom:1px solid var(--border);
  display:flex;align-items:center;justify-content:space-between;
}
.tms-card-header h5{font-size:15px;color:var(--cream);margin:0;}
.tms-card-body{padding:24px;}

/* ── STAT CARDS ── */
.stat-card{
  background:var(--navy2);border:1px solid var(--border);border-radius:16px;
  padding:22px 24px;position:relative;overflow:hidden;transition:.25s;
}
.stat-card:hover{border-color:rgba(198,151,63,0.4);transform:translateY(-2px);}
.stat-card::after{
  content:'';position:absolute;top:0;right:0;width:80px;height:80px;
  background:radial-gradient(circle at 100% 0,rgba(198,151,63,0.08),transparent 70%);
}
.stat-icon{
  width:44px;height:44px;border-radius:12px;display:flex;align-items:center;
  justify-content:center;font-size:20px;margin-bottom:14px;
}
.stat-icon.gold{background:rgba(198,151,63,0.15);color:var(--gold2);}
.stat-icon.blue{background:rgba(58,123,213,0.15);color:#6fa3e8;}
.stat-icon.green{background:rgba(61,153,112,0.15);color:#5bc49a;}
.stat-icon.red{background:rgba(200,75,75,0.15);color:#e07070;}
.stat-value{font-size:28px;font-weight:600;color:var(--cream);}
.stat-label{font-size:12px;color:var(--muted);margin-top:2px;}
.stat-sub{font-size:11px;color:var(--muted);margin-top:8px;}

/* ── TABLES ── */
.tms-table-wrap{overflow-x:auto;}
table.tms-table{width:100%;border-collapse:collapse;}
table.tms-table thead th{
  padding:12px 16px;font-size:11px;font-weight:600;letter-spacing:.12em;
  text-transform:uppercase;color:var(--muted);border-bottom:1px solid var(--border);
  white-space:nowrap;
}
table.tms-table tbody td{
  padding:14px 16px;border-bottom:1px solid rgba(198,151,63,0.07);
  font-size:13px;color:var(--cream);vertical-align:middle;
}
table.tms-table tbody tr:last-child td{border-bottom:none;}
table.tms-table tbody tr:hover td{background:rgba(198,151,63,0.04);}

/* ── BADGES ── */
.badge-tms{
  display:inline-flex;align-items:center;gap:5px;
  padding:4px 10px;border-radius:20px;font-size:11px;font-weight:500;
}
.badge-pending{background:rgba(201,149,42,0.15);color:#e8c27a;border:1px solid rgba(201,149,42,0.3);}
.badge-progress{background:rgba(58,123,213,0.15);color:#6fa3e8;border:1px solid rgba(58,123,213,0.3);}
.badge-ready{background:rgba(61,153,112,0.15);color:#5bc49a;border:1px solid rgba(61,153,112,0.3);}
.badge-delivered{background:rgba(61,153,112,0.2);color:#5bc49a;border:1px solid rgba(61,153,112,0.4);}
.badge-cancelled{background:rgba(200,75,75,0.15);color:#e07070;border:1px solid rgba(200,75,75,0.3);}
.badge-paid{background:rgba(61,153,112,0.2);color:#5bc49a;border:1px solid rgba(61,153,112,0.4);}
.badge-partial{background:rgba(201,149,42,0.15);color:#e8c27a;border:1px solid rgba(201,149,42,0.3);}
.badge-unpaid{background:rgba(200,75,75,0.15);color:#e07070;border:1px solid rgba(200,75,75,0.3);}

/* ── BUTTONS ── */
.btn-tms{
  display:inline-flex;align-items:center;gap:7px;padding:9px 18px;
  border-radius:10px;font-size:13px;font-weight:500;border:none;cursor:pointer;transition:.2s;
  font-family:'DM Sans',sans-serif;
}
.btn-tms:hover{transform:translateY(-1px);}
.btn-gold{background:linear-gradient(135deg,#B8862E,#C9A84C);color:#1a1200;box-shadow:0 4px 14px rgba(198,151,63,0.3);}
.btn-gold:hover{box-shadow:0 6px 20px rgba(198,151,63,0.45);}
.btn-ghost{background:rgba(255,255,255,0.05);color:var(--cream);border:1px solid var(--border);}
.btn-ghost:hover{background:rgba(198,151,63,0.1);border-color:var(--gold);color:var(--gold2);}
.btn-danger-tms{background:rgba(200,75,75,0.15);color:#e07070;border:1px solid rgba(200,75,75,0.3);}
.btn-danger-tms:hover{background:rgba(200,75,75,0.25);}
.btn-info-tms{background:rgba(58,123,213,0.15);color:#6fa3e8;border:1px solid rgba(58,123,213,0.3);}
.btn-info-tms:hover{background:rgba(58,123,213,0.25);}
.btn-sm-tms{padding:6px 12px;font-size:12px;border-radius:8px;}

/* ── FORMS ── */
.form-group{margin-bottom:20px;}
.form-label-tms{display:block;font-size:11px;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:var(--muted);margin-bottom:8px;}
.form-control-tms{
  width:100%;background:rgba(255,255,255,0.04);border:1px solid var(--border);
  border-radius:10px;padding:12px 16px;font-family:'DM Sans',sans-serif;
  font-size:14px;color:var(--cream);outline:none;transition:.25s;
}
.form-control-tms::placeholder{color:rgba(122,125,140,0.5);}
.form-control-tms:focus{background:rgba(198,151,63,0.05);border-color:rgba(198,151,63,0.5);box-shadow:0 0 0 3px rgba(198,151,63,0.08);}
.form-control-tms option{background:var(--navy3);color:var(--cream);}
.input-icon-wrap{position:relative;}
.input-icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:15px;pointer-events:none;}
.form-control-tms.has-icon{padding-left:42px;}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
.form-row-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;}

/* ── ALERTS ── */
.alert-tms{padding:14px 18px;border-radius:12px;margin-bottom:20px;font-size:13px;display:flex;align-items:center;gap:10px;}
.alert-success-tms{background:rgba(61,153,112,0.12);border:1px solid rgba(61,153,112,0.3);color:#5bc49a;}
.alert-danger-tms{background:rgba(200,75,75,0.12);border:1px solid rgba(200,75,75,0.3);color:#e07070;}

/* ── SEARCH BAR ── */
.search-wrap{position:relative;max-width:320px;}
.search-wrap input{padding-left:38px;}
.search-wrap i{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--muted);}

/* ── EMPTY STATE ── */
.empty-state{text-align:center;padding:60px 20px;color:var(--muted);}
.empty-state i{font-size:48px;opacity:.3;margin-bottom:16px;}
.empty-state p{font-size:14px;}

/* ── SCROLLBAR ── */
::-webkit-scrollbar{width:6px;height:6px;}
::-webkit-scrollbar-track{background:var(--navy);}
::-webkit-scrollbar-thumb{background:rgba(198,151,63,0.25);border-radius:4px;}

/* ── RESPONSIVE ── */
@media(max-width:768px){
  .sidebar{transform:translateX(-100%);}
  .sidebar.open{transform:translateX(0);}
  .main-wrap{margin-left:0;}
  .form-row,.form-row-3{grid-template-columns:1fr;}
  .page-content{padding:20px 16px;}
}
</style>
</head>
<body>