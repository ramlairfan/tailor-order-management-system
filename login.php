<?php

require_once 'config/database.php';
require_once 'config/constants.php';
require_once 'includes/session.php';
require_once 'includes/functions.php';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: user/dashboard.php");
    }
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {

            $user = $result->fetch_assoc();

            // plain text password check
            if ($password == $user['password']) {

                $_SESSION['user_id']   = $user['id'];
                $_SESSION['full_name'] = $user['full_name'] ?? $user['name'] ?? '';
                $_SESSION['role']      = $user['role'];
                $_SESSION['success']   = "Login Successful";

                if ($user['role'] == 'admin') {
                    header("Location: admin/dashboard.php");
                } else {
                    header("Location: user/dashboard.php");
                }
                exit;

            } else {
                $error = "Invalid Password";
            }

        } else {
            $error = "User not found";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tailor Management System — Login</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --navy:   #080C18;
    --card:   #0F1526;
    --gold:   #C6973F;
    --gold2:  #E8C27A;
    --gold3:  #F5DFA0;
    --cream:  #EDE3C8;
    --muted:  #7A7D8C;
    --border: rgba(198,151,63,0.25);
    --glow:   rgba(198,151,63,0.12);
  }

  html, body {
    height: 100%;
    font-family: 'DM Sans', sans-serif;
    background: var(--navy);
    overflow: hidden;
  }

  #bg-canvas {
    position: fixed;
    inset: 0;
    z-index: 0;
    pointer-events: none;
  }

  .fabric-grid {
    position: fixed;
    inset: 0;
    z-index: 1;
    background-image:
      linear-gradient(rgba(198,151,63,0.04) 1px, transparent 1px),
      linear-gradient(90deg, rgba(198,151,63,0.04) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
  }

  .floaters {
    position: fixed;
    inset: 0;
    z-index: 2;
    pointer-events: none;
    overflow: hidden;
  }

  .scissors {
    position: absolute;
    opacity: 0.18;
    animation: floatScissors 18s ease-in-out infinite;
    transform-origin: center;
  }
  .scissors:nth-child(1) { top: 8%; left: 6%; width: 80px; animation-delay: 0s; }
  .scissors:nth-child(2) { top: 70%; left: 3%; width: 60px; animation-delay: -6s; opacity: 0.12; }
  .scissors:nth-child(3) { top: 15%; right: 7%; width: 70px; animation-delay: -3s; }
  .scissors:nth-child(4) { bottom: 10%; right: 5%; width: 50px; animation-delay: -9s; opacity: 0.10; }

  @keyframes floatScissors {
    0%,100% { transform: translateY(0px) rotate(-15deg); }
    25%      { transform: translateY(-18px) rotate(-5deg); }
    50%      { transform: translateY(-8px) rotate(-20deg); }
    75%      { transform: translateY(-22px) rotate(-10deg); }
  }

  .spool {
    position: absolute;
    opacity: 0.15;
    animation: floatSpool 14s ease-in-out infinite;
  }
  .spool:nth-child(5)  { top: 40%; left: 2%; width: 55px; animation-delay: -2s; }
  .spool:nth-child(6)  { top: 25%; right: 3%; width: 45px; animation-delay: -7s; }
  .spool:nth-child(7)  { bottom: 30%; right: 4%; width: 65px; animation-delay: -4s; opacity: 0.10; }

  @keyframes floatSpool {
    0%,100% { transform: translateY(0px) rotate(0deg); }
    50%      { transform: translateY(-20px) rotate(180deg); }
  }

  .needle {
    position: absolute;
    opacity: 0.20;
    animation: floatNeedle 20s ease-in-out infinite;
  }
  .needle:nth-child(8)  { top: 55%; left: 5%; width: 4px; height: 90px; animation-delay: -1s; }
  .needle:nth-child(9)  { top: 30%; right: 6%; width: 3px; height: 70px; animation-delay: -8s; }
  .needle:nth-child(10) { bottom: 20%; left: 8%; width: 3px; height: 80px; animation-delay: -5s; }

  @keyframes floatNeedle {
    0%,100% { transform: translateY(0) rotate(-30deg); }
    50%      { transform: translateY(-25px) rotate(10deg); }
  }

  .tape {
    position: absolute;
    opacity: 0.13;
    animation: floatTape 16s ease-in-out infinite;
  }
  .tape:nth-child(11) { top: 5%;  left: 30%; animation-delay: -3s; }
  .tape:nth-child(12) { bottom: 5%; right: 25%; animation-delay: -10s; }

  @keyframes floatTape {
    0%,100% { transform: translateY(0) scaleX(1); }
    50%      { transform: translateY(-15px) scaleX(0.92); }
  }

  .thread-line {
    position: absolute;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--gold), transparent);
    opacity: 0;
    animation: threadSlide 8s ease-in-out infinite;
  }
  .thread-line:nth-child(13) { top: 20%; left: 0; right: 0; animation-delay: 0s; }
  .thread-line:nth-child(14) { top: 60%; left: 0; right: 0; animation-delay: -3s; }
  .thread-line:nth-child(15) { top: 80%; left: 0; right: 0; animation-delay: -6s; }

  @keyframes threadSlide {
    0%    { opacity: 0; transform: scaleX(0) translateX(-50%); }
    30%   { opacity: 0.3; }
    60%   { opacity: 0.2; }
    100%  { opacity: 0; transform: scaleX(1.5) translateX(50%); }
  }

  .scene {
    position: relative;
    z-index: 10;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    perspective: 1200px;
    padding: 20px;
  }

  .card-wrap {
    width: 100%;
    max-width: 440px;
    transform-style: preserve-3d;
    animation: cardEntrance 1.2s cubic-bezier(0.16,1,0.3,1) both;
  }

  @keyframes cardEntrance {
    from { opacity: 0; transform: translateY(60px) rotateX(12deg); }
    to   { opacity: 1; transform: translateY(0) rotateX(0); }
  }

  .card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 52px 48px 44px;
    position: relative;
    overflow: hidden;
    box-shadow:
      0 0 0 1px rgba(198,151,63,0.08),
      0 30px 80px rgba(0,0,0,0.6),
      0 0 120px var(--glow),
      inset 0 1px 0 rgba(198,151,63,0.15);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .card:hover {
    box-shadow:
      0 0 0 1px rgba(198,151,63,0.15),
      0 40px 100px rgba(0,0,0,0.7),
      0 0 160px rgba(198,151,63,0.18),
      inset 0 1px 0 rgba(198,151,63,0.20);
  }

  .card::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 100px; height: 100px;
    background: radial-gradient(circle at 0 0, rgba(198,151,63,0.15), transparent 70%);
    border-radius: 24px 0 0 0;
  }
  .card::after {
    content: '';
    position: absolute;
    bottom: 0; right: 0;
    width: 120px; height: 120px;
    background: radial-gradient(circle at 100% 100%, rgba(198,151,63,0.10), transparent 70%);
    border-radius: 0 0 24px 0;
  }

  .shimmer-top {
    position: absolute;
    top: 0; left: 15%; right: 15%;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--gold2), transparent);
    opacity: 0.5;
  }

  .brand {
    text-align: center;
    margin-bottom: 36px;
    animation: fadeUp 0.8s 0.3s cubic-bezier(0.16,1,0.3,1) both;
  }

  .brand-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 16px;
    position: relative;
  }

  .brand-icon-ring {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    border: 1.5px solid var(--border);
    background: rgba(198,151,63,0.06);
    display: flex;
    align-items: center;
    justify-content: center;
    animation: iconPulse 3s ease-in-out infinite;
  }

  @keyframes iconPulse {
    0%,100% { box-shadow: 0 0 0 0 rgba(198,151,63,0.15), 0 0 20px rgba(198,151,63,0.05); }
    50%      { box-shadow: 0 0 0 8px rgba(198,151,63,0), 0 0 40px rgba(198,151,63,0.12); }
  }

  .brand-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 28px;
    font-weight: 300;
    color: var(--cream);
    letter-spacing: 0.03em;
    line-height: 1.2;
  }

  .brand-title span {
    color: var(--gold2);
    font-style: italic;
  }

  .brand-sub {
    font-size: 11px;
    font-weight: 400;
    color: var(--muted);
    letter-spacing: 0.25em;
    text-transform: uppercase;
    margin-top: 6px;
  }

  .divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 28px;
    animation: fadeUp 0.8s 0.45s cubic-bezier(0.16,1,0.3,1) both;
  }
  .divider-line { flex: 1; height: 1px; background: var(--border); }
  .divider-text {
    font-family: 'Cormorant Garamond', serif;
    font-size: 12px;
    color: var(--muted);
    letter-spacing: 0.15em;
    font-style: italic;
  }

  .error-box {
    background: rgba(200,60,50,0.10);
    border: 1px solid rgba(200,60,50,0.25);
    border-radius: 10px;
    padding: 12px 16px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 10px;
    animation: shakeError 0.5s cubic-bezier(0.36,0.07,0.19,0.97);
  }
  .error-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: #e05050;
    flex-shrink: 0;
    animation: pulseDot 1.5s ease-in-out infinite;
  }
  @keyframes pulseDot {
    0%,100% { opacity: 1; }
    50%      { opacity: 0.4; }
  }
  @keyframes shakeError {
    10%,90%  { transform: translateX(-2px); }
    20%,80%  { transform: translateX(3px); }
    30%,50%,70% { transform: translateX(-4px); }
    40%,60%  { transform: translateX(4px); }
  }
  .error-text {
    font-size: 13px;
    color: #f08080;
    font-weight: 400;
  }

  .field {
    margin-bottom: 20px;
    animation: fadeUp 0.8s cubic-bezier(0.16,1,0.3,1) both;
  }
  .field:nth-child(1) { animation-delay: 0.55s; }
  .field:nth-child(2) { animation-delay: 0.65s; }

  .field-label {
    display: block;
    font-size: 11px;
    font-weight: 500;
    color: var(--muted);
    letter-spacing: 0.18em;
    text-transform: uppercase;
    margin-bottom: 8px;
  }

  .field-wrap {
    position: relative;
  }

  .field-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    opacity: 0.45;
    pointer-events: none;
  }

  .field-input {
    width: 100%;
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(198,151,63,0.18);
    border-radius: 12px;
    padding: 14px 16px 14px 44px;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    color: var(--cream);
    outline: none;
    transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
    -webkit-appearance: none;
  }

  .field-input::placeholder { color: rgba(122,125,140,0.6); }

  .field-input:focus {
    background: rgba(198,151,63,0.04);
    border-color: rgba(198,151,63,0.5);
    box-shadow: 0 0 0 3px rgba(198,151,63,0.08), 0 0 20px rgba(198,151,63,0.06);
  }

  .btn-login {
    margin-top: 10px;
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #B8862E 0%, #C9A84C 40%, #DFC078 70%, #C9A84C 100%);
    background-size: 200% 200%;
    color: #1a1200;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    font-weight: 500;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.3s;
    animation: fadeUp 0.8s 0.75s cubic-bezier(0.16,1,0.3,1) both;
    box-shadow: 0 4px 20px rgba(198,151,63,0.3), 0 1px 0 rgba(255,255,255,0.15) inset;
  }

  .btn-login::before {
    content: '';
    position: absolute;
    top: 0; left: -100%;
    width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
  }

  .btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 32px rgba(198,151,63,0.45), 0 1px 0 rgba(255,255,255,0.15) inset;
  }

  .btn-login:hover::before { left: 100%; }

  .btn-login:active {
    transform: translateY(0);
    box-shadow: 0 2px 12px rgba(198,151,63,0.25);
  }

  .card-footer {
    text-align: center;
    margin-top: 28px;
    animation: fadeUp 0.8s 0.85s cubic-bezier(0.16,1,0.3,1) both;
  }

  .footer-measure {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    color: var(--muted);
    font-size: 11px;
    letter-spacing: 0.08em;
  }

  .footer-pip {
    width: 3px; height: 3px;
    border-radius: 50%;
    background: var(--gold);
    opacity: 0.5;
  }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .card-wrap { will-change: transform; }

  .pw-toggle {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    opacity: 0.35;
    transition: opacity 0.2s;
    color: var(--cream);
    line-height: 0;
  }
  .pw-toggle:hover { opacity: 0.7; }

  .stitch-border {
    position: absolute;
    inset: 8px;
    border-radius: 18px;
    border: 1px dashed rgba(198,151,63,0.08);
    pointer-events: none;
    animation: stitchRotate 40s linear infinite;
  }
  @keyframes stitchRotate {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
  }
</style>
</head>
<body>

<canvas id="bg-canvas"></canvas>
<div class="fabric-grid"></div>

<div class="floaters">
  <!-- Scissors 1 -->
  <svg class="scissors" viewBox="0 0 120 60" fill="none" xmlns="http://www.w3.org/2000/svg">
    <g transform="translate(60,30)">
      <g style="animation: snipTop 3s ease-in-out infinite;">
        <path d="M-55,-8 Q-20,-4 0,0" stroke="#C6973F" stroke-width="4" stroke-linecap="round"/>
        <circle cx="-55" cy="-8" r="10" fill="none" stroke="#C6973F" stroke-width="2.5"/>
        <circle cx="-55" cy="-8" r="4" fill="#C6973F" opacity="0.5"/>
      </g>
      <g style="animation: snipBot 3s ease-in-out infinite;">
        <path d="M-55,8 Q-20,4 0,0" stroke="#C6973F" stroke-width="4" stroke-linecap="round"/>
        <circle cx="-55" cy="8" r="10" fill="none" stroke="#C6973F" stroke-width="2.5"/>
        <circle cx="-55" cy="8" r="4" fill="#C6973F" opacity="0.5"/>
      </g>
      <path d="M0,0 L55,-2" stroke="#C6973F" stroke-width="3.5" stroke-linecap="round"/>
      <path d="M0,0 L55,2"  stroke="#C6973F" stroke-width="3.5" stroke-linecap="round"/>
      <circle cx="0" cy="0" r="3" fill="#C6973F"/>
    </g>
    <style>
      @keyframes snipTop { 0%,100%{transform:rotate(-12deg)} 50%{transform:rotate(0deg)} }
      @keyframes snipBot { 0%,100%{transform:rotate(12deg)}  50%{transform:rotate(0deg)} }
    </style>
  </svg>

  <!-- Scissors 2 -->
  <svg class="scissors" viewBox="0 0 120 60" fill="none">
    <g transform="translate(60,30) scale(0.85)">
      <path d="M-55,-8 Q-20,-4 0,0" stroke="#E8C27A" stroke-width="4" stroke-linecap="round"/>
      <circle cx="-55" cy="-8" r="10" fill="none" stroke="#E8C27A" stroke-width="2.5"/>
      <path d="M-55,8 Q-20,4 0,0" stroke="#E8C27A" stroke-width="4" stroke-linecap="round"/>
      <circle cx="-55" cy="8" r="10" fill="none" stroke="#E8C27A" stroke-width="2.5"/>
      <path d="M0,0 L55,-2" stroke="#E8C27A" stroke-width="3.5" stroke-linecap="round"/>
      <path d="M0,0 L55,2"  stroke="#E8C27A" stroke-width="3.5" stroke-linecap="round"/>
      <circle cx="0" cy="0" r="3" fill="#E8C27A"/>
    </g>
  </svg>

  <!-- Scissors 3 -->
  <svg class="scissors" viewBox="0 0 120 60" fill="none">
    <g transform="translate(60,30) scale(0.9)">
      <path d="M-55,-8 Q-20,-4 0,0" stroke="#C6973F" stroke-width="4" stroke-linecap="round"/>
      <circle cx="-55" cy="-8" r="10" fill="none" stroke="#C6973F" stroke-width="2.5"/>
      <path d="M-55,8 Q-20,4 0,0" stroke="#C6973F" stroke-width="4" stroke-linecap="round"/>
      <circle cx="-55" cy="8" r="10" fill="none" stroke="#C6973F" stroke-width="2.5"/>
      <path d="M0,0 L55,-2" stroke="#C6973F" stroke-width="3.5" stroke-linecap="round"/>
      <path d="M0,0 L55,2"  stroke="#C6973F" stroke-width="3.5" stroke-linecap="round"/>
      <circle cx="0" cy="0" r="3" fill="#C6973F"/>
    </g>
  </svg>

  <!-- Scissors 4 -->
  <svg class="scissors" viewBox="0 0 120 60" fill="none">
    <g transform="translate(60,30) scale(0.7)">
      <path d="M-55,-8 Q-20,-4 0,0" stroke="#E8C27A" stroke-width="4" stroke-linecap="round"/>
      <circle cx="-55" cy="-8" r="10" fill="none" stroke="#E8C27A" stroke-width="2.5"/>
      <path d="M-55,8 Q-20,4 0,0" stroke="#E8C27A" stroke-width="4" stroke-linecap="round"/>
      <circle cx="-55" cy="8" r="10" fill="none" stroke="#E8C27A" stroke-width="2.5"/>
      <path d="M0,0 L55,-2" stroke="#E8C27A" stroke-width="3.5" stroke-linecap="round"/>
      <path d="M0,0 L55,2"  stroke="#E8C27A" stroke-width="3.5" stroke-linecap="round"/>
      <circle cx="0" cy="0" r="3" fill="#E8C27A"/>
    </g>
  </svg>

  <!-- Spool 1 -->
  <svg class="spool" viewBox="0 0 60 70" fill="none">
    <rect x="8" y="0"  width="44" height="12" rx="4" fill="none" stroke="#C6973F" stroke-width="2"/>
    <rect x="8" y="58" width="44" height="12" rx="4" fill="none" stroke="#C6973F" stroke-width="2"/>
    <rect x="18" y="12" width="24" height="46" rx="2" fill="none" stroke="#C6973F" stroke-width="1.5"/>
    <line x1="18" y1="20" x2="42" y2="20" stroke="#C6973F" stroke-width="1" opacity="0.5"/>
    <line x1="18" y1="27" x2="42" y2="27" stroke="#C6973F" stroke-width="1" opacity="0.5"/>
    <line x1="18" y1="34" x2="42" y2="34" stroke="#C6973F" stroke-width="1" opacity="0.5"/>
    <line x1="18" y1="41" x2="42" y2="41" stroke="#C6973F" stroke-width="1" opacity="0.5"/>
    <line x1="18" y1="48" x2="42" y2="48" stroke="#C6973F" stroke-width="1" opacity="0.5"/>
  </svg>

  <!-- Spool 2 -->
  <svg class="spool" viewBox="0 0 60 70" fill="none">
    <rect x="8" y="0"  width="44" height="12" rx="4" fill="none" stroke="#E8C27A" stroke-width="2"/>
    <rect x="8" y="58" width="44" height="12" rx="4" fill="none" stroke="#E8C27A" stroke-width="2"/>
    <rect x="18" y="12" width="24" height="46" rx="2" fill="none" stroke="#E8C27A" stroke-width="1.5"/>
    <line x1="18" y1="20" x2="42" y2="20" stroke="#E8C27A" stroke-width="1" opacity="0.5"/>
    <line x1="18" y1="30" x2="42" y2="30" stroke="#E8C27A" stroke-width="1" opacity="0.5"/>
    <line x1="18" y1="40" x2="42" y2="40" stroke="#E8C27A" stroke-width="1" opacity="0.5"/>
    <line x1="18" y1="50" x2="42" y2="50" stroke="#E8C27A" stroke-width="1" opacity="0.5"/>
  </svg>

  <!-- Spool 3 -->
  <svg class="spool" viewBox="0 0 60 70" fill="none">
    <rect x="8" y="0"  width="44" height="12" rx="4" fill="none" stroke="#C6973F" stroke-width="2"/>
    <rect x="8" y="58" width="44" height="12" rx="4" fill="none" stroke="#C6973F" stroke-width="2"/>
    <rect x="18" y="12" width="24" height="46" rx="2" fill="none" stroke="#C6973F" stroke-width="1.5"/>
  </svg>

  <!-- Needle 1 -->
  <svg class="needle" viewBox="0 0 8 100" fill="none" style="width:4px;height:90px;">
    <line x1="4" y1="5" x2="4" y2="95" stroke="#C6973F" stroke-width="2" stroke-linecap="round"/>
    <ellipse cx="4" cy="8" rx="3" ry="5" fill="none" stroke="#C6973F" stroke-width="1.5"/>
    <path d="M2,95 L4,100 L6,95" fill="#C6973F" opacity="0.8"/>
  </svg>

  <!-- Needle 2 -->
  <svg class="needle" viewBox="0 0 8 100" fill="none" style="width:3px;height:70px;">
    <line x1="4" y1="5" x2="4" y2="95" stroke="#E8C27A" stroke-width="2" stroke-linecap="round"/>
    <ellipse cx="4" cy="8" rx="3" ry="5" fill="none" stroke="#E8C27A" stroke-width="1.5"/>
    <path d="M2,95 L4,100 L6,95" fill="#E8C27A" opacity="0.8"/>
  </svg>

  <!-- Needle 3 -->
  <svg class="needle" viewBox="0 0 8 100" fill="none" style="width:3px;height:80px;">
    <line x1="4" y1="5" x2="4" y2="95" stroke="#C6973F" stroke-width="2" stroke-linecap="round"/>
    <ellipse cx="4" cy="8" rx="3" ry="5" fill="none" stroke="#C6973F" stroke-width="1.5"/>
    <path d="M2,95 L4,100 L6,95" fill="#C6973F" opacity="0.8"/>
  </svg>

  <!-- Measuring Tape 1 (static, no PHP loop) -->
  <svg class="tape" viewBox="0 0 300 28" fill="none" style="width:280px;">
    <rect x="0" y="4" width="300" height="20" rx="3" fill="none" stroke="#C6973F" stroke-width="1.5"/>
    <line x1="0"   y1="4" x2="0"   y2="24" stroke="#C6973F" stroke-width="1" opacity="0.6"/>
    <line x1="10"  y1="4" x2="10"  y2="16" stroke="#C6973F" stroke-width="1" opacity="0.6"/>
    <line x1="20"  y1="4" x2="20"  y2="16" stroke="#C6973F" stroke-width="1" opacity="0.6"/>
    <line x1="30"  y1="4" x2="30"  y2="16" stroke="#C6973F" stroke-width="1" opacity="0.6"/>
    <line x1="40"  y1="4" x2="40"  y2="16" stroke="#C6973F" stroke-width="1" opacity="0.6"/>
    <line x1="50"  y1="4" x2="50"  y2="24" stroke="#C6973F" stroke-width="1" opacity="0.6"/>
    <line x1="60"  y1="4" x2="60"  y2="16" stroke="#C6973F" stroke-width="1" opacity="0.6"/>
    <line x1="70"  y1="4" x2="70"  y2="16" stroke="#C6973F" stroke-width="1" opacity="0.6"/>
    <line x1="80"  y1="4" x2="80"  y2="16" stroke="#C6973F" stroke-width="1" opacity="0.6"/>
    <line x1="90"  y1="4" x2="90"  y2="16" stroke="#C6973F" stroke-width="1" opacity="0.6"/>
    <line x1="100" y1="4" x2="100" y2="24" stroke="#C6973F" stroke-width="1" opacity="0.6"/>
    <line x1="150" y1="4" x2="150" y2="24" stroke="#C6973F" stroke-width="1" opacity="0.6"/>
    <line x1="200" y1="4" x2="200" y2="24" stroke="#C6973F" stroke-width="1" opacity="0.6"/>
    <line x1="250" y1="4" x2="250" y2="24" stroke="#C6973F" stroke-width="1" opacity="0.6"/>
    <line x1="300" y1="4" x2="300" y2="24" stroke="#C6973F" stroke-width="1" opacity="0.6"/>
  </svg>

  <!-- Measuring Tape 2 -->
  <svg class="tape" viewBox="0 0 240 28" fill="none" style="width:220px;">
    <rect x="0" y="4" width="240" height="20" rx="3" fill="none" stroke="#E8C27A" stroke-width="1.5"/>
    <line x1="0"   y1="4" x2="0"   y2="24" stroke="#E8C27A" stroke-width="1" opacity="0.6"/>
    <line x1="50"  y1="4" x2="50"  y2="24" stroke="#E8C27A" stroke-width="1" opacity="0.6"/>
    <line x1="100" y1="4" x2="100" y2="24" stroke="#E8C27A" stroke-width="1" opacity="0.6"/>
    <line x1="150" y1="4" x2="150" y2="24" stroke="#E8C27A" stroke-width="1" opacity="0.6"/>
    <line x1="200" y1="4" x2="200" y2="24" stroke="#E8C27A" stroke-width="1" opacity="0.6"/>
    <line x1="240" y1="4" x2="240" y2="24" stroke="#E8C27A" stroke-width="1" opacity="0.6"/>
  </svg>

  <div class="thread-line"></div>
  <div class="thread-line"></div>
  <div class="thread-line"></div>
</div>

<div class="scene">
  <div class="card-wrap" id="cardWrap">
    <div class="card" id="card3d">
      <div class="shimmer-top"></div>
      <div class="stitch-border"></div>

      <div class="brand">
        <div class="brand-icon">
          <div class="brand-icon-ring">
            <svg width="32" height="36" viewBox="0 0 60 70" fill="none">
              <rect x="4" y="0"  width="52" height="14" rx="5" fill="none" stroke="#C6973F" stroke-width="2.5"/>
              <rect x="4" y="56" width="52" height="14" rx="5" fill="none" stroke="#C6973F" stroke-width="2.5"/>
              <rect x="16" y="14" width="28" height="42" rx="2" fill="none" stroke="#C6973F" stroke-width="2"/>
              <line x1="16" y1="22" x2="44" y2="22" stroke="#C6973F" stroke-width="1.2" opacity="0.6"/>
              <line x1="16" y1="30" x2="44" y2="30" stroke="#C6973F" stroke-width="1.2" opacity="0.6"/>
              <line x1="16" y1="38" x2="44" y2="38" stroke="#C6973F" stroke-width="1.2" opacity="0.6"/>
              <line x1="16" y1="46" x2="44" y2="46" stroke="#C6973F" stroke-width="1.2" opacity="0.6"/>
            </svg>
          </div>
        </div>
        <h1 class="brand-title">Tailor <span>Management</span></h1>
        <p class="brand-sub">Master Atelier System</p>
      </div>

      <div class="divider">
        <div class="divider-line"></div>
        <span class="divider-text">Sign in to continue</span>
        <div class="divider-line"></div>
      </div>

      <?php if (!empty($error)): ?>
      <div class="error-box">
        <div class="error-dot"></div>
        <span class="error-text"><?php echo htmlspecialchars($error); ?></span>
      </div>
      <?php endif; ?>

      <form method="POST" autocomplete="on">

        <div class="field">
          <label class="field-label" for="email">Email Address</label>
          <div class="field-wrap">
            <svg class="field-icon" viewBox="0 0 20 20" fill="none">
              <rect x="2" y="4" width="16" height="12" rx="2" stroke="#C6973F" stroke-width="1.5"/>
              <path d="M2 7l8 5 8-5" stroke="#C6973F" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            <input
              id="email"
              type="email"
              name="email"
              class="field-input"
              placeholder="you@example.com"
              required
              autocomplete="email"
              value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
            >
          </div>
        </div>

        <div class="field">
          <label class="field-label" for="password">Password</label>
          <div class="field-wrap">
            <svg class="field-icon" viewBox="0 0 20 20" fill="none">
              <rect x="4" y="9" width="12" height="9" rx="2" stroke="#C6973F" stroke-width="1.5"/>
              <path d="M7 9V6a3 3 0 0 1 6 0v3" stroke="#C6973F" stroke-width="1.5" stroke-linecap="round"/>
              <circle cx="10" cy="14" r="1.5" fill="#C6973F" opacity="0.7"/>
            </svg>
            <input
              id="password"
              type="password"
              name="password"
              class="field-input"
              placeholder="••••••••••"
              required
              autocomplete="current-password"
            >
            <button type="button" class="pw-toggle" id="pwToggle" aria-label="Toggle password visibility">
              <svg id="eyeIcon" width="18" height="18" viewBox="0 0 20 20" fill="none">
                <path d="M2 10s3-6 8-6 8 6 8 6-3 6-8 6-8-6-8-6z" stroke="currentColor" stroke-width="1.5"/>
                <circle cx="10" cy="10" r="2.5" stroke="currentColor" stroke-width="1.5"/>
              </svg>
            </button>
          </div>
        </div>

        <button type="submit" class="btn-login">
          Enter the Atelier
        </button>

      </form>

      <div class="card-footer">
        <div class="footer-measure">
          <span>Precision</span>
          <div class="footer-pip"></div>
          <span>Craftsmanship</span>
          <div class="footer-pip"></div>
          <span>Excellence</span>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
  const canvas = document.getElementById('bg-canvas');
  const ctx    = canvas.getContext('2d');

  function resize() {
    canvas.width  = window.innerWidth;
    canvas.height = window.innerHeight;
  }
  resize();
  window.addEventListener('resize', resize);

  const threads = Array.from({length: 12}, () => ({
    x:     Math.random() * window.innerWidth,
    y:     Math.random() * window.innerHeight,
    vx:    (Math.random() - 0.5) * 0.4,
    vy:    (Math.random() - 0.5) * 0.4,
    len:   60 + Math.random() * 140,
    angle: Math.random() * Math.PI * 2,
    va:    (Math.random() - 0.5) * 0.008,
    alpha: 0.03 + Math.random() * 0.08,
    color: Math.random() > 0.5 ? '#C6973F' : '#E8C27A',
    wave:  Math.random() * Math.PI * 2,
    waveSpeed: 0.01 + Math.random() * 0.02,
  }));

  function drawThread(t) {
    ctx.save();
    ctx.globalAlpha = t.alpha;
    ctx.strokeStyle = t.color;
    ctx.lineWidth   = 1;
    ctx.beginPath();
    const cos = Math.cos(t.angle);
    const sin = Math.sin(t.angle);
    const segments = 10;
    for (let i = 0; i <= segments; i++) {
      const p  = i / segments;
      const wx = Math.sin(t.wave + p * Math.PI * 2) * 6;
      const x  = t.x + cos * (p - 0.5) * t.len + sin * wx;
      const y  = t.y + sin * (p - 0.5) * t.len - cos * wx;
      i === 0 ? ctx.moveTo(x, y) : ctx.lineTo(x, y);
    }
    ctx.stroke();
    ctx.restore();
  }

  function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    threads.forEach(t => {
      t.x    += t.vx;
      t.y    += t.vy;
      t.angle += t.va;
      t.wave  += t.waveSpeed;
      if (t.x < -200) t.x = canvas.width + 200;
      if (t.x > canvas.width + 200) t.x = -200;
      if (t.y < -200) t.y = canvas.height + 200;
      if (t.y > canvas.height + 200) t.y = -200;
      drawThread(t);
    });
    requestAnimationFrame(animate);
  }
  animate();

  const wrap  = document.getElementById('cardWrap');
  const card  = document.getElementById('card3d');
  let targetRX = 0, targetRY = 0;
  let currentRX = 0, currentRY = 0;

  document.addEventListener('mousemove', e => {
    const rect = card.getBoundingClientRect();
    const cx = rect.left + rect.width  / 2;
    const cy = rect.top  + rect.height / 2;
    const dx = (e.clientX - cx) / (window.innerWidth  / 2);
    const dy = (e.clientY - cy) / (window.innerHeight / 2);
    targetRX = -dy * 8;
    targetRY =  dx * 8;
  });

  document.addEventListener('mouseleave', () => {
    targetRX = 0;
    targetRY = 0;
  });

  (function loop() {
    currentRX += (targetRX - currentRX) * 0.06;
    currentRY += (targetRY - currentRY) * 0.06;
    wrap.style.transform = `rotateX(${currentRX}deg) rotateY(${currentRY}deg)`;
    requestAnimationFrame(loop);
  })();

  const pwInput   = document.getElementById('password');
  const pwToggle  = document.getElementById('pwToggle');
  const eyeIcon   = document.getElementById('eyeIcon');

  const eyeOpen   = `<path d="M2 10s3-6 8-6 8 6 8 6-3 6-8 6-8-6-8-6z" stroke="currentColor" stroke-width="1.5"/><circle cx="10" cy="10" r="2.5" stroke="currentColor" stroke-width="1.5"/>`;
  const eyeClosed = `<path d="M2 10s3-6 8-6 8 6 8 6-3 6-8 6-8-6-8-6z" stroke="currentColor" stroke-width="1.5"/><line x1="2" y1="2" x2="18" y2="18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>`;

  pwToggle.addEventListener('click', () => {
    const show = pwInput.type === 'password';
    pwInput.type = show ? 'text' : 'password';
    eyeIcon.innerHTML = show ? eyeClosed : eyeOpen;
  });
</script>

</body>
</html>
