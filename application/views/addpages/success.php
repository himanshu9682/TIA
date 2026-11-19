   <?php
session_start();

if (!isset($_SESSION['enrollment_success'])) {
    header('Location: enrollment.php');
    exit;
}

$enrollment = $_SESSION['enrollment_success'];

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$referenceId = $enrollment['reference_id'];

$phone = trim(
    ($enrollment['primary_country_code'] ?? '') . ' ' .
    ($enrollment['primary_phone'] ?? '')
);

$dob = date('d M Y', strtotime($enrollment['date_of_birth']));

$location = $enrollment['city'] . ', ' .
            $enrollment['state'] . ', ' .
            $enrollment['country'];

$education = $enrollment['highest_qualification'] . ' (' .
             $enrollment['area_of_study'] . ')';
?>




  
<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Enrollment Confirmed: ENR-2026-381C28 | The IoT Academy</title>

    

    <!-- Google Font: Inter -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">



    <!-- Bootstrap CSS for Site Navigation Components -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">



    <!-- The IoT Academy Common CSS -->

    <link href="http://localhost/TIA/assets/dit/css/custom.css" rel="stylesheet">



    <!-- Swiper CSS for Header Ticker Marquee -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">



    <style>

        :root {

            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;

            --color-primary: #2563eb;

            --color-primary-hover: #1d4ed8;

            --color-indigo: #4f46e5;

            --color-purple: #7c3aed;

            --color-green: #16a34a;

            --color-green-light: #dcfce7;

            --color-green-border: #86efac;

            --bg-page: #f1f5f9;

            --bg-card: #ffffff;

            --border-light: #e2e8f0;

            --text-heading: #0f172a;

            --text-body: #334155;

            --text-muted: #64748b;

            --shadow-card: 0 10px 30px -5px rgba(15, 23, 42, 0.08), 0 4px 12px -2px rgba(15, 23, 42, 0.04);

            --shadow-btn: 0 4px 14px 0 rgba(37, 99, 235, 0.35);

            --radius-card: 16px;

            --radius-btn: 10px;

        }



        *, *::before, *::after {

            box-sizing: border-box;

        }



        html {

            scroll-behavior: smooth;

            font-size: 16px;

            -webkit-text-size-adjust: 100%;

        }



        body {

            font-family: var(--font-sans);

            background-color: var(--bg-page);

            color: var(--text-body);

            line-height: 1.5;

            min-height: 100vh;

            display: flex;

            flex-direction: column;

            overflow-x: hidden;

            width: 100%;

        }



        /* Page Layout */

        .page-wrapper {

            flex: 1;

            padding: 36px 16px 64px;

            width: 100%;

            display: flex;

            justify-content: center;

            align-items: flex-start;

        }

        .receipt-container {

            width: 100%;

            max-width: 820px;

            margin: 0 auto;

        }



        /* Success Card */

        .receipt-card {

            background: var(--bg-card);

            border-radius: var(--radius-card);

            box-shadow: var(--shadow-card);

            border: 1px solid var(--border-light);

            padding: 36px 32px 40px;

            display: flex;

            flex-direction: column;

            align-items: center;

            text-align: center;

            position: relative;

            overflow: hidden;

        }



        .receipt-card::before {

            content: '';

            position: absolute;

            top: 0;

            left: 0;

            right: 0;

            height: 6px;

            background: #ff9d00;

        }



        /* Animated Icon Badge */

        .success-icon-wrap {

            margin-bottom: 20px;

            position: relative;

        }

        .success-icon-circle {

            width: 72px;

            height: 72px;

            border-radius: 50%;

            background: #ecfdf5;

            color: #059669;

            border: 2px solid #a7f3d0;

            display: flex;

            align-items: center;

            justify-content: center;

            box-shadow: 0 0 0 8px rgba(16, 185, 129, 0.12);

            animation: pulse-ring 2s infinite;

        }

        @keyframes pulse-ring {

            0%   { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.25); }

            70%  { box-shadow: 0 0 0 14px rgba(16, 185, 129, 0); }

            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }

        }



        /* Headings */

        .receipt-title {

            font-size: 26px;

            font-weight: 800;

            color: var(--text-heading);

            letter-spacing: -0.5px;

            margin-bottom: 8px;

        }

        .receipt-subtitle {

            font-size: 15px;

            color: var(--text-muted);

            max-width: 580px;

            line-height: 1.55;

            margin-bottom: 24px;

        }



        /* Reference ID Box */

        .ref-box {

            background: #f8fafc;

            border: 1px dashed #cbd5e1;

            border-radius: 12px;

            padding: 14px 20px;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 16px;

            margin-bottom: 28px;

            width: 100%;

            max-width: 480px;

        }

        .ref-info {

            display: flex;

            flex-direction: column;

            text-align: left;

        }

        .ref-label {

            font-size: 11px;

            font-weight: 700;

            color: var(--text-muted);

            text-transform: uppercase;

            letter-spacing: 0.8px;

        }

        .ref-code {

            font-size: 20px;

            font-weight: 800;

            color: #ff9d00;

            letter-spacing: 1px;

            font-family: monospace, var(--font-sans);

        }

        .btn-copy {

            background: #ffffff;

            border: 1px solid #cbd5e1;

            color: var(--text-body);

            font-size: 13px;

            font-weight: 600;

            padding: 7px 12px;

            border-radius: 8px;

            cursor: pointer;

            display: flex;

            align-items: center;

            gap: 6px;

            transition: all 0.2s ease;

        }

        .btn-copy:hover {

            background: #f1f5f9;

            border-color: #94a3b8;

            color: var(--text-heading);

        }



        /* Course Highlight Banner */

        .course-highlight {

            width: 100%;

            background: linear-gradient(135deg, #134a8c 0%, #0b2545 50%, #0b2545 100%);

            border-radius: 12px;

            padding: 18px 22px;

            color: #ffffff;

            display: flex;

            align-items: center;

            justify-content: space-between;

            text-align: left;

            margin-bottom: 28px;

            gap: 16px;

        }

        .course-highlight-left {

            display: flex;

            align-items: center;

            gap: 14px;

            min-width: 0;

        }

        .course-icon-badge {

            width: 42px;

            height: 42px;

            border-radius: 10px;

            background: rgba(255, 255, 255, 0.16);

            display: flex;

            align-items: center;

            justify-content: center;

            flex-shrink: 0;

        }

        .course-meta-text {

            display: flex;

            flex-direction: column;

            min-width: 0;

        }

        .course-meta-label {

            font-size: 11px;

            text-transform: uppercase;

            letter-spacing: 0.8px;

            color: rgba(255, 255, 255, 0.75);

            font-weight: 600;

        }

        .course-meta-title {

            font-size: 17px;

            font-weight: 700;

            color: #ffffff;

            line-height: 1.3;

        }

        .status-badge-chip {

            background: rgba(16, 185, 129, 0.2);

            border: 1px solid rgba(110, 231, 183, 0.4);

            color: #a7f3d0;

            padding: 6px 12px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: 600;

            white-space: nowrap;

            display: flex;

            align-items: center;

            gap: 6px;

        }

        .status-dot {

            width: 8px;

            height: 8px;

            border-radius: 50%;

            background: #10b981;

            display: inline-block;

        }



        /* Summary Details Grid */

        .details-section {

            width: 100%;

            border: 1px solid var(--border-light);

            border-radius: 12px;

            padding: 22px 24px;

            margin-bottom: 26px;

            background: #ffffff;

            text-align: left;

        }

        .section-subhead {

            font-size: 15px;

            font-weight: 700;

            color: var(--text-heading);

            padding-bottom: 12px;

            margin-bottom: 16px;

            border-bottom: 1px solid var(--border-light);

            display: flex;

            align-items: center;

            gap: 8px;

        }

        .details-grid {

            display: grid;

            grid-template-columns: repeat(2, 1fr);

            gap: 16px 24px;

        }

        .detail-item {

            display: flex;

            flex-direction: column;

            gap: 3px;

        }

        .detail-label {

            font-size: 12px;

            font-weight: 600;

            color: var(--text-muted);

            text-transform: uppercase;

            letter-spacing: 0.5px;

        }

        .detail-value {

            font-size: 14px;

            font-weight: 600;

            color: var(--text-heading);

            word-break: break-word;

        }

        .detail-value.highlight {

            color: var(--color-primary);

        }



        /* Next Steps Box */

        .next-steps-card {

            width: 100%;

            background: #f8fafc;

            border: 1px solid var(--border-light);

            border-left: 4px solid var(--color-primary);

            border-radius: 10px;

            padding: 20px 22px;

            text-align: left;

            margin-bottom: 30px;

        }

        .next-steps-header {

            display: flex;

            align-items: center;

            gap: 10px;

            font-size: 15px;

            font-weight: 700;

            color: var(--text-heading);

            margin-bottom: 12px;

        }

        .steps-list {

            display: flex;

            flex-direction: column;

            gap: 10px;

            list-style: none;

        }

        .step-row {

            display: flex;

            align-items: flex-start;

            gap: 10px;

            font-size: 13px;

            line-height: 1.5;

            color: var(--text-body);

        }

        .step-num {

            width: 22px;

            height: 22px;

            border-radius: 50%;

            background: #eff6ff;

            color: var(--color-primary);

            font-size: 11px;

            font-weight: 700;

            display: flex;

            align-items: center;

            justify-content: center;

            flex-shrink: 0;

            margin-top: 1px;

            border: 1px solid #bfdbfe;

        }



        /* Action Buttons */

        .actions-row {

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 14px;

            width: 100%;

            flex-wrap: wrap;

        }

        .btn-action-outline {
            background: #ffffff;
            color: var(--text-heading);
            border: 1px solid #cbd5e1;
            font-size: 14px;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: var(--radius-btn);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .btn-action-outline:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            color: var(--text-heading);
            text-decoration: none;
        }
        .btn-action-primary, .btn-action-print, .btn-action-new {
            background: #ff9d00;
            color: #ffffff;
            border: none;
            font-size: 14px;
            font-weight: 600;
            padding: 12px 26px;
            border-radius: var(--radius-btn);
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px 0 rgba(255, 157, 0, 0.35);
            transition: all 0.2s ease;
        }
        .btn-action-primary:hover, .btn-action-print:hover, .btn-action-new:hover {
            background: #e88d00;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px 0 rgba(232, 141, 0, 0.45);
            text-decoration: none;
        }



        /* ============================================================================
           RESPONSIVE BREAKPOINTS (MOBILE & TABLET ENHANCEMENTS)
           ============================================================================ */

        @media (max-width: 768px) {
            .page-wrapper {
                padding: 20px 14px 44px;
            }
            .receipt-card {
                padding: 28px 20px 32px;
                border-radius: 14px;
            }
            .receipt-title {
                font-size: 22px;
                line-height: 1.3;
            }
            .course-highlight {
                flex-direction: column;
                align-items: flex-start;
                padding: 16px;
                gap: 12px;
            }
            .course-highlight-left {
                width: 100%;
                gap: 12px;
            }
            .details-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .ref-box {
                flex-direction: column;
                gap: 12px;
                text-align: center;
                padding: 14px 16px;
                max-width: 100%;
            }
            .ref-info {
                text-align: center;
            }
            .actions-row {
                flex-direction: column;
                width: 100%;
                gap: 10px;
            }
            .btn-action-outline,
            .btn-action-primary,
            .btn-action-print,
            .btn-action-new {
                width: 100%;
                justify-content: center;
                height: 46px;
                font-size: 14.5px;
                padding: 12px 18px;
            }
        }

        @media (max-width: 480px) {
            .page-wrapper {
                padding: 14px 10px 36px;
            }
            .receipt-card {
                padding: 22px 14px 26px;
                border-radius: 12px;
            }
            .success-icon-wrap {
                margin-bottom: 14px;
            }
            .success-icon-circle {
                width: 58px;
                height: 58px;
            }
            .success-icon-circle svg {
                width: 28px;
                height: 28px;
            }
            .receipt-title {
                font-size: 19px;
                line-height: 1.35;
                margin-bottom: 6px;
            }
            .receipt-subtitle {
                font-size: 13.5px;
                margin-bottom: 18px;
                line-height: 1.5;
            }
            .ref-box {
                padding: 12px 12px;
                margin-bottom: 22px;
                border-radius: 10px;
            }
            .ref-code {
                font-size: 18px;
                letter-spacing: 0.5px;
                word-break: break-all;
            }
            .btn-copy {
                width: 100%;
                justify-content: center;
                padding: 9px 14px;
                font-size: 13px;
                height: 40px;
            }
            .course-highlight {
                padding: 14px 12px;
                margin-bottom: 22px;
                border-radius: 10px;
                gap: 10px;
            }
            .course-icon-badge {
                width: 38px;
                height: 38px;
                border-radius: 8px;
            }
            .course-meta-title {
                font-size: 15px;
                line-height: 1.35;
                word-break: break-word;
            }
            .status-badge-chip {
                font-size: 11px;
                padding: 4px 10px;
            }
            .details-section {
                padding: 16px 12px;
                margin-bottom: 20px;
                border-radius: 10px;
            }
            .section-subhead {
                font-size: 14px;
                margin-bottom: 12px;
                padding-bottom: 8px;
            }
            .details-grid {
                gap: 8px;
            }
            .detail-item {
                padding: 8px 10px;
                background: #f8fafc;
                border-radius: 6px;
            }
            .detail-label {
                font-size: 11px;
            }
            .detail-value {
                font-size: 13.5px;
            }
            .next-steps-card {
                padding: 16px 12px;
                margin-bottom: 22px;
                border-radius: 10px;
            }
            .next-steps-header {
                font-size: 14px;
                margin-bottom: 10px;
            }
            .step-row {
                gap: 8px;
                font-size: 12.5px;
            }
            .step-num {
                width: 20px;
                height: 20px;
                font-size: 10px;
            }
            .btn-action-outline,
            .btn-action-primary,
            .btn-action-print,
            .btn-action-new {
                height: 46px;
                font-size: 14px;
            }
            .btn-action-outline:active,
            .btn-action-primary:active,
            .btn-action-print:active,
            .btn-action-new:active {
                transform: scale(0.99);
            }
        }

        @media (max-width: 360px) {
            .page-wrapper {
                padding: 10px 6px 28px;
            }
            .receipt-card {
                padding: 18px 10px 22px;
            }
            .receipt-title {
                font-size: 17px;
            }
            .ref-code {
                font-size: 16px;
            }
            .course-meta-title {
                font-size: 14px;
            }
            .btn-action-outline,
            .btn-action-primary,
            .btn-action-print,
            .btn-action-new {
                height: 44px;
                font-size: 13.5px;
                padding: 10px 12px;
            }
        }



        /* PRINT STYLESHEET */

        @media print {

            body {

                background: #ffffff !important;

                color: #000000 !important;

            }

            .site-header, .actions-row, .btn-copy, .header-link {

                display: none !important;

            }

            .page-wrapper {

                padding: 0 !important;

            }

            .receipt-card {

                box-shadow: none !important;

                border: 1px solid #000000 !important;

                padding: 20px !important;

            }

            .course-highlight {

                background: #f1f5f9 !important;

                color: #000000 !important;

                border: 1px solid #cbd5e1 !important;

            }

            .course-meta-label, .course-meta-title {

                color: #000000 !important;

            }

            .status-badge-chip {

                background: transparent !important;

                color: #000000 !important;

                border: 1px solid #000000 !important;

            }

            .ref-box {

                background: #ffffff !important;

                border: 1px solid #000000 !important;

            }

        }

    </style>

</head>

<body>



    <!-- Site Header & Navigation -->

    
        <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P57SX2T" height="0" width="0"
        style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<!-- <style>


#snow {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  overflow: hidden;
  z-index: 9999;
}

.snowflake {
  position: absolute;
  top: -10px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: radial-gradient(
    circle,
    #ffffff 0%,
    #dbefff 60%,
    rgba(255, 255, 255, 0) 70%
  );
  opacity: 0.85;
  box-shadow: 0 0 4px rgba(0, 0, 0, 0.25);
  animation-name: fall;
  animation-timing-function: linear;
  animation-iteration-count: infinite;
}

@keyframes fall {
  0% {
    transform: translateY(0) translateX(0);
  }
  100% {
    transform: translateY(100vh) translateX(20px);
  }
}
@keyframes santarun {
    0% {
        transform: translateX(-100px);
    }
    100% {
        transform: translateX(100vw);
    }
}
.santa{
    position: fixed;
    bottom:0px;
    left:0px;
    z-index: 999999;
    animation: santarun 20s linear infinite;
}
</style> -->
<!-- <div id="snow"></div> -->
<!-- <img class="santa" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/191814/santas.gif" alt="Santa Be Watchin" /> -->




<div id="enqform-overlay">
    <div class="enqform-cv-spinner">
        <span class="enqform-spinner"></span>
    </div>
</div>
<div class="header-top">
    <div class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="follow-us">
                    <p class="head">Follow Us:</p>
                    <ul>
                        <li>
                            <a href="https://instagram.com/the_iot_academy" aria-label="instagram" target="_blank"
                                rel="nofollow noopener">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15"
                                    fill="none">
                                    <path
                                        d="M15 7.60096C14.9593 8.75574 14.9649 9.91427 14.8666 11.0639C14.7276 12.6878 13.8976 14.1257 12.1478 14.6787C11.4864 14.8877 10.8044 14.9599 10.1186 14.9697C8.62163 14.9922 7.1237 15.0166 5.62718 14.9847C4.74433 14.966 3.85307 14.9824 2.98473 14.726C1.59166 14.315 0.689626 13.4283 0.28378 12.0359C0.0609632 11.272 0.0146211 10.4846 0.00947192 9.6954C0.000109865 8.25849 -0.00644358 6.8211 0.0104081 5.38419C0.0211745 4.4717 0.0511331 3.55641 0.374592 2.68329C0.889505 1.29465 1.90669 0.47965 3.33019 0.211107C4.07448 0.0709772 4.84451 0.0372336 5.60424 0.0198932C6.89854 -0.00963247 8.19378 -0.00260256 9.48809 0.0170812C10.3204 0.0297351 11.1569 0.0405143 11.9676 0.281875C13.3673 0.699452 14.296 1.58428 14.6934 3.00761C14.9228 3.82917 14.9434 4.67182 14.9574 5.51448C14.9691 6.2095 14.9597 6.90452 14.9597 7.60002C14.9728 7.60002 14.9869 7.60049 15 7.60096ZM13.5854 7.50301C13.5976 7.50301 13.6097 7.50301 13.6219 7.50301C13.6219 7.18291 13.6299 6.86235 13.6205 6.54272C13.5934 5.64617 13.6069 4.74493 13.5166 3.85448C13.3799 2.50427 12.4807 1.62553 11.1386 1.45681C10.6307 1.39307 10.1158 1.34995 9.60465 1.35089C7.78841 1.35417 5.97217 1.36636 4.15686 1.40807C3.64523 1.41978 3.13593 1.55101 2.68843 1.82892C1.78686 2.38851 1.42267 3.26678 1.39599 4.2669C1.34591 6.1392 1.35433 8.01385 1.3534 9.88708C1.3534 10.3225 1.38804 10.7611 1.44936 11.1923C1.6263 12.4343 2.30739 13.2104 3.53195 13.4747C4.12784 13.6031 4.75182 13.6303 5.36457 13.6434C7.25758 13.6832 9.15246 13.7545 11.0417 13.5558C11.7518 13.4812 12.3856 13.223 12.8729 12.677C13.3649 12.1254 13.5554 11.4468 13.5774 10.7358C13.6107 9.65931 13.5854 8.58046 13.5854 7.50301Z"
                                        fill="white" />
                                    <path
                                        d="M11.3184 7.49691C11.324 9.6251 9.60371 11.3409 7.46401 11.3413C5.38142 11.3418 3.65646 9.61432 3.65319 7.52456C3.64991 5.3945 5.35521 3.66983 7.46963 3.66561C9.59247 3.66139 11.3128 5.37388 11.3184 7.49691ZM9.99036 7.50863C9.98334 6.10124 8.87862 4.99707 7.47899 4.99942C6.09294 5.00176 4.97464 6.12045 4.98119 7.49879C4.98775 8.90618 6.09341 10.0113 7.49257 10.008C8.87862 10.0047 9.99692 8.88556 9.99036 7.50863Z"
                                        fill="white" />
                                    <path
                                        d="M12.366 3.50907C12.3608 3.99461 11.9681 4.38828 11.4864 4.38969C11.0024 4.3911 10.5891 3.97352 10.5956 3.49033C10.6026 2.99777 11.0094 2.6069 11.5051 2.61721C11.9957 2.62752 12.3711 3.01651 12.366 3.50907Z"
                                        fill="white" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.facebook.com/academyforiot/" aria-label="facebook" target="_blank"
                                rel="nofollow noopener">
                                <svg xmlns="http://www.w3.org/2000/svg" width="8" height="15" viewBox="0 0 8 15"
                                    fill="none">
                                    <path
                                        d="M7.56876 8.16801H5.21266V15H2.35368V8.17927H0V5.50035H2.35126C2.35126 5.3863 2.34981 5.29572 2.35126 5.20467C2.36772 4.43122 2.344 3.65496 2.41079 2.88573C2.54922 1.30222 3.71033 0.197424 5.33462 0.0378526C6.22179 -0.0494423 7.10315 0.0317513 8 0.105436V2.48868C7.91578 2.48868 7.83108 2.48727 7.7459 2.48868C7.23819 2.49947 6.72902 2.48868 6.22373 2.52857C5.55968 2.58114 5.2417 2.89699 5.21604 3.53481C5.19039 4.1745 5.21024 4.81654 5.21024 5.48674H7.9245C7.80446 6.39113 7.68927 7.26174 7.56876 8.16754V8.16801Z"
                                        fill="white" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.youtube.com/channel/UCyIXtUMsOX02mpi5PKex9aA" aria-label="youtube"
                                target="_blank" rel="nofollow noopener">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="12" viewBox="0 0 15 12"
                                    fill="none">
                                    <path
                                        d="M7.49634 0C9.1601 0.0671946 10.8215 0.120061 12.482 0.205042C13.7632 0.270755 14.5965 1.02077 14.8129 2.34835C14.9367 3.10676 14.9969 3.88543 14.9993 4.6552C15.0044 6.13596 14.9833 7.61918 14.9052 9.09746C14.8586 9.98038 14.6101 10.8164 13.8644 11.3771C13.4324 11.7017 12.9417 11.81 12.4265 11.845C9.425 12.0501 6.42261 12.0362 3.41975 11.8934C2.87441 11.8678 2.32202 11.8085 1.7908 11.681C1.0535 11.5041 0.564156 10.9898 0.299253 10.2417C0.0936356 9.66269 0.023528 9.05546 0.0155291 8.44923C-0.00517381 6.83607 -0.00564433 5.22241 0.0169407 3.60973C0.023528 3.1374 0.0988113 2.65814 0.203267 2.19717C0.482756 0.962958 1.28499 0.263344 2.48953 0.204054C4.15799 0.122037 5.8274 0.0667006 7.49634 0ZM7.3693 11.1089C8.92296 11.0496 10.7091 10.9893 12.4947 10.9078C12.9981 10.885 13.4748 10.7121 13.7072 10.1993C13.8658 9.84945 14.0046 9.45419 14.0192 9.07374C14.0799 7.51196 14.1128 5.94821 14.1067 4.38544C14.1039 3.70756 14.0248 3.02178 13.9034 2.35527C13.7401 1.45901 13.1473 1.17739 12.4119 1.13144C11.6219 1.08253 10.83 1.05782 10.039 1.02126C7.51375 0.904657 4.99082 0.977781 2.46977 1.13539C1.96207 1.16701 1.49814 1.36366 1.26664 1.86811C1.10667 2.21643 0.973509 2.61367 0.958453 2.99411C0.897756 4.53119 0.866231 6.07024 0.868583 7.6088C0.869524 8.33658 0.888816 9.07276 1.08785 9.78571C1.23747 10.3213 1.54802 10.6765 2.06841 10.8065C2.28109 10.8593 2.49988 10.9023 2.71726 10.9122C4.18952 10.9799 5.66225 11.0377 7.36977 11.1084L7.3693 11.1089Z"
                                        fill="white" />
                                    <path
                                        d="M6.23487 3.32515C6.29886 3.34787 6.42449 3.37307 6.53177 3.43384C7.60456 4.04452 8.67405 4.66163 9.74496 5.27626C9.97457 5.40769 10.1242 5.59544 10.1209 5.882C10.1176 6.16758 9.96563 6.35088 9.73508 6.48082C8.67358 7.07866 7.61491 7.68292 6.55153 8.27729C6.03678 8.56485 5.57238 8.27285 5.56956 7.6592C5.56344 6.45859 5.56532 5.25749 5.56956 4.05688C5.57097 3.62308 5.81893 3.33305 6.23487 3.32515ZM6.47248 4.4551V7.26888C7.30013 6.80099 8.1019 6.34742 8.94084 5.87311C8.09625 5.38792 7.29637 4.92843 6.47248 4.4551Z"
                                        fill="white" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.linkedin.com/school/theiotacademy/" aria-label="linkedin"
                                target="_blank" rel="nofollow noopener">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 16"
                                    fill="none">
                                    <path
                                        d="M9.13375 15.0003H5.22774V4.67953H9.12438V5.26713C9.51044 5.10417 9.86323 4.91294 10.2385 4.80367C11.9092 4.31816 13.7833 5.18373 14.5681 6.77734C14.8431 7.3357 14.9968 7.92473 14.9977 8.55019C15.001 10.6605 14.9991 12.7707 14.9991 14.881C14.9991 14.9126 14.9939 14.9443 14.9893 14.9951H11.0828C11.0828 14.8911 11.0828 14.7894 11.0828 14.6878C11.0828 12.9533 11.0837 11.2188 11.0818 9.48431C11.0814 8.91253 10.8677 8.55882 10.4404 8.40114C9.82809 8.1754 9.18763 8.59956 9.13937 9.2648C9.13235 9.36017 9.13375 9.45651 9.13375 9.55236C9.13375 11.2629 9.13375 12.9735 9.13375 14.684V15.0003ZM5.88225 5.34238V14.3212H8.4783C8.4783 14.2095 8.4783 14.1151 8.4783 14.0212C8.47877 12.4391 8.46986 10.8565 8.48345 9.27438C8.49235 8.21422 9.52777 7.44018 10.5266 7.72296C11.2796 7.93576 11.7364 8.56601 11.7392 9.422C11.7443 10.9562 11.7406 12.4908 11.741 14.025C11.741 14.1247 11.741 14.2249 11.741 14.3212H14.3305C14.3357 14.2574 14.3422 14.2105 14.3422 14.1635C14.3422 12.2775 14.354 10.3916 14.3376 8.50562C14.323 6.81712 12.9798 5.41571 11.3316 5.33662C10.3786 5.29109 9.60086 5.66589 8.96883 6.38289C8.82125 6.55016 8.66898 6.71311 8.47267 6.92927V5.34142L5.88225 5.34238Z"
                                        fill="white" />
                                    <path
                                        d="M0.0197142 4.67091H3.90277V14.997H0.0197142V4.67091ZM3.23982 14.326V5.34525H0.670951V14.326H3.23982Z"
                                        fill="white" />
                                    <path
                                        d="M1.94575 3.99386C0.871442 3.98955 -0.00187105 3.08994 3.01057e-06 1.98952C0.00187707 0.875671 0.877533 -0.00572336 1.97714 2.79868e-05C3.05801 0.00577934 3.92663 0.907304 3.91164 2.00821C3.89665 3.11582 3.02287 3.99817 1.94575 3.99386ZM3.25197 1.9876C3.24822 1.25047 2.6757 0.665267 1.95793 0.664309C1.23361 0.66383 0.652645 1.26485 0.658736 2.00869C0.664827 2.74055 1.24579 3.32191 1.96683 3.31664C2.68788 3.31185 3.25572 2.72473 3.25197 1.9876Z"
                                        fill="white" />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-8">
                <div class="scroll-part ">


                    <div class="swiper scrollHeaderSwiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <p>
                                    <a href="http://localhost/TIA/all-courses" target="_blank">
                                        <strong class="text-white">🪔✨ Celebrate Diwali with New Skills! 🎉📚 Unlock <strong>FLAT 50% Off</strong>🔥 on All In-House Courses 💻🚀 🎁🌟</strong>
                                        <span class="appiled-btn-header-alert2"> 🌟🚀Enroll Today🚀🌟</</span>
                                    </a>
                                </p>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<header>
    <nav class="navbar navbar-expand-lg p-0">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-0  gap-md-2">

                <!-- Logo link -->
                <a class="navbar-brand d-flex align-items-center m-0" href="http://localhost/TIA/">

                    <img loading="lazy" src="http://localhost/TIA/assets/dit/images/navbar/Diwali Logo.svg"
                        alt="the IoT Academy Logo" class="img-fluid" width="109" height="67">

                </a>

                <!-- Forbes tag link -->
                <a target="_blank"
                    href="https://www.forbesindia.com/article/upfront/brand-connect/visionary-companies-building-indias-future/2991497/1"
                    class="d-flex  gap-0  gap-md-2 align-items-center forbes-tag text-decoration-none">

                    <img loading="lazy" src="http://localhost/TIA/assets/dit/images/navbar/forbes01.svg"
                        alt="forbes" class="img-fluid forbes01-img" width="43.9" height="74.37">


                    <!--<svg class="img-fluid forbes01-img" width="44" height="75" viewBox="0 0 44 75" fill="none" xmlns="http://www.w3.org/2000/svg">-->
                    <!--<g opacity="0.37">-->
                    <!--<path opacity="0.05" d="M39.0372 72.9261C38.9403 74.1344 35.5254 74.2953 20.0506 74.2953C5.72564 74.2167 0.0951056 74.3922 0.133496 72.9261C0.303509 66.376 10.592 57.7236 19.2389 57.7236C27.8858 57.7236 39.3681 68.769 39.039 72.9261H39.0372Z" fill="#F9F9F9"/>-->
                    <!--<path opacity="0.1" d="M38.9076 72.9041C38.8162 74.065 35.4251 74.2185 20.0417 74.2185C5.80262 74.1399 0.228753 74.3209 0.265315 72.9041C0.426188 66.621 10.6653 58.1733 19.2611 58.1733C27.8568 58.1733 39.2202 68.8494 38.9094 72.9041H38.9076Z" fill="#F2F2F2"/>-->
                    <!--<path opacity="0.14" d="M38.7758 72.8822C38.6881 73.9955 35.3207 74.1399 20.0305 74.1399C5.8774 74.0631 0.360204 74.2459 0.394938 72.8822C0.548498 66.8659 10.7365 58.623 19.2792 58.623C27.8219 58.623 39.0701 68.9317 38.774 72.8822H38.7758Z" fill="#ECECEC"/>-->
                    <!--<path opacity="0.19" d="M38.6459 72.8585C38.5655 73.9243 35.2201 74.0596 20.0213 74.0596C5.95412 73.9828 0.495421 74.1711 0.526499 72.8585C0.670919 67.1092 10.8114 59.071 19.301 59.071C27.7907 59.071 38.933 69.0122 38.6441 72.8585H38.6459Z" fill="#E6E6E6"/>-->
                    <!--<path opacity="0.24" d="M38.5165 72.8366C38.4415 73.8548 35.1199 73.9828 20.0143 73.9828C6.03122 73.906 0.631025 74.098 0.660274 72.8366C0.795553 67.3523 10.8866 59.5208 19.3251 59.5208C27.7636 59.5208 38.7889 69.0927 38.5165 72.8366Z" fill="#DFDFDF"/>-->
                    <!--<path opacity="0.29" d="M38.3866 72.8147C38.3172 73.7854 35.0175 73.9042 20.0051 73.9042C6.10796 73.8274 0.764432 74.023 0.791853 72.8147C0.91982 67.5973 10.9597 59.9705 19.347 59.9705C27.7343 59.9705 38.6444 69.1749 38.3848 72.8147H38.3866Z" fill="#D9D9D9"/>-->
                    <!--<path opacity="0.33" d="M38.2567 72.7927C38.1927 73.7177 34.9168 73.8256 19.9958 73.8256C6.18458 73.7506 0.897733 73.9499 0.925155 72.7927C1.04398 67.8422 11.0345 60.4202 19.3706 60.4202C27.7068 60.4202 38.5017 69.2553 38.2567 72.7927Z" fill="#D3D3D3"/>-->
                    <!--<path opacity="0.38" d="M38.1268 72.7708C38.0684 73.6483 34.8143 73.747 19.9867 73.747C6.26132 73.672 1.03114 73.8749 1.05673 72.7708C1.16825 68.0872 11.1094 60.8699 19.3925 60.8699C27.6756 60.8699 38.3554 69.3376 38.1268 72.7708Z" fill="#CCCCCC"/>-->
                    <!--<path opacity="0.43" d="M37.9952 72.7488C37.9422 73.5788 34.7119 73.6702 19.9775 73.6702C6.33806 73.5952 1.16455 73.8036 1.18831 72.7488C1.29069 68.3322 11.1825 61.3196 19.4144 61.3196C27.6463 61.3196 38.2109 69.4199 37.9952 72.7488Z" fill="#C6C6C6"/>-->
                    <!--<path opacity="0.48" d="M37.8656 72.7251C37.8162 73.5075 34.6097 73.5898 19.9685 73.5898C6.41503 73.5148 1.30002 73.7269 1.32013 72.7251C1.41336 68.5753 11.2558 61.7693 19.4366 61.7693C27.6173 61.7693 38.0667 69.5003 37.8656 72.7251Z" fill="#C0C0C0"/>-->
                    <!--<path opacity="0.52" d="M37.7357 72.7031C37.6919 73.438 34.5092 73.5111 19.9593 73.5111C6.49177 73.438 1.43343 73.6519 1.45171 72.7031C1.53763 68.8184 11.3289 62.219 19.4584 62.219C27.588 62.219 37.9204 69.5826 37.7357 72.7031Z" fill="#B9B9B9"/>-->
                    <!--<path opacity="0.57" d="M37.6059 72.6812C37.5675 73.3685 34.4067 73.4344 19.9502 73.4344C6.56851 73.3612 1.56684 73.5806 1.58329 72.6812C1.66007 69.0634 11.4038 62.6687 19.4803 62.6687C27.5569 62.6687 37.7741 69.663 37.6041 72.6812H37.6059Z" fill="#B3B3B3"/>-->
                    <!--<path opacity="0.62" d="M37.4761 72.6592C37.4432 73.2991 34.3062 73.3557 19.9428 73.3557C6.64709 73.2826 1.70209 73.5056 1.71855 72.6592C1.78619 69.3083 11.4806 63.1184 19.5059 63.1184C27.5313 63.1184 37.6315 69.7453 37.4779 72.6592H37.4761Z" fill="#ADADAD"/>-->
                    <!--<path opacity="0.67" d="M37.3446 72.6373C37.3153 73.2296 34.2039 73.2771 19.9319 73.2771C6.72213 73.204 1.8338 73.4325 1.84842 72.6373C1.90875 69.5533 11.552 63.5681 19.5261 63.5681C27.5003 63.5681 37.4835 69.8257 37.3464 72.6373H37.3446Z" fill="#A6A6A6"/>-->
                    <!--<path opacity="0.71" d="M37.2147 72.6154C37.1909 73.1601 34.1015 73.2003 19.9228 73.2003C6.79703 73.1291 1.96903 73.3594 1.98 72.6154C2.03119 69.7983 11.625 64.0178 19.548 64.0178C27.471 64.0178 37.3372 69.9079 37.2147 72.6154Z" fill="#A0A0A0"/>-->
                    <!--<path opacity="0.76" d="M37.0849 72.5917C37.0648 73.0889 34.0009 73.12 19.9136 73.12C6.87377 73.0487 2.10244 73.2827 2.11159 72.5917C2.15363 70.0415 11.6999 64.4658 19.5699 64.4658C27.4398 64.4658 37.1891 69.9867 37.0849 72.5917Z" fill="#9A9A9A"/>-->
                    <!--<path opacity="0.81" d="M36.9549 72.5698C36.9385 73.0195 33.8984 73.0414 19.9061 73.0414C6.95223 72.9701 2.23757 73.2096 2.24488 72.5698C2.27962 70.2846 11.7747 64.9155 19.5935 64.9155C27.4123 64.9155 37.0427 70.0689 36.9568 72.5698H36.9549Z" fill="#939393"/>-->
                    <!--<path opacity="0.86" d="M36.8251 72.5478C36.8141 72.95 33.7978 72.9628 19.8969 72.9628C7.02897 72.8915 2.37099 73.1346 2.37647 72.5478C2.40206 70.5296 11.8478 65.3652 19.6154 65.3652C27.383 65.3652 36.8927 70.1494 36.8251 72.5478Z" fill="#8D8D8D"/>-->
                    <!--<path opacity="0.9" d="M36.6955 72.5259C36.6882 72.8805 33.6956 72.886 19.888 72.886C7.10597 72.8165 2.50465 73.0633 2.5083 72.5259C2.52658 70.7746 11.9212 65.8149 19.6376 65.8149C27.354 65.8149 36.743 70.2298 36.6955 72.5259Z" fill="#878787"/>-->
                    <!--<path opacity="0.95" d="M36.5639 72.5039C36.5602 72.8111 33.5932 72.8074 19.877 72.8074C7.18088 72.7379 2.63807 72.9884 2.63989 72.5039C2.64903 71.0195 11.9961 66.2646 19.6595 66.2646C27.3228 66.2646 36.5895 70.3121 36.5657 72.5039H36.5639Z" fill="#808080"/>-->
                    <!--<path d="M36.4339 72.4802C36.4339 72.7398 33.4925 72.727 19.8695 72.727C7.25934 72.6575 2.77319 72.9116 2.77319 72.4802C2.77319 71.2627 12.0709 66.7144 19.6831 66.7144C27.2953 66.7144 36.4357 70.3925 36.4357 72.4802H36.4339Z" fill="#7A7A7A"/>-->
                    <!--</g>-->
                    <!--<path d="M36.4085 61.1807H2.73315V72.5679H36.4085V61.1807Z" fill="url(#paint0_linear_684_1555)"/>-->
                    <!--<path d="M33.8089 56.0947H5.33271L2.73315 61.1805H36.4085L33.8089 56.0947Z" fill="url(#paint1_linear_684_1555)"/>-->
                    <!--<path opacity="0.4" d="M30.84 63.7327H8.31787V69.8129H30.84V63.7327Z" fill="url(#paint2_linear_684_1555)" style="mix-blend-mode:multiply"/>-->
                    <!--<path d="M2.73315 61.1808C2.73315 61.1808 16.9996 61.1058 19.5699 61.0711C22.1402 61.0363 36.4067 61.1808 36.4067 61.1808C36.4067 61.1808 21.0799 61.4586 19.5699 61.4586C18.0599 61.4586 2.73315 61.1808 2.73315 61.1808Z" fill="#5C5C5C"/>-->
                    <!--<path d="M30.6847 63.9685H8.47339V69.5771H30.6847V63.9685Z" fill="url(#paint3_linear_684_1555)"/>-->
                    <!--<path d="M11.7876 58.6431L22.2406 60.0745L35.1598 58.7875L33.754 56.0947H19.2609L11.7876 58.6431Z" fill="url(#paint4_linear_684_1555)" style="mix-blend-mode:multiply"/>-->
                    <!--<path d="M29.0907 54.0494C29.0907 53.4278 28.7343 53.4863 28.3869 53.1572C27.1018 51.9324 23.8149 50.5339 19.7528 50.5339C15.6908 50.5339 12.6397 52.0732 11.2632 53.3145C10.8336 53.702 10.4167 53.6271 10.4167 54.2285C10.4167 55.5557 14.5976 56.095 19.7528 56.095C24.9081 56.095 29.0889 55.3766 29.0889 54.0494H29.0907Z" fill="url(#paint5_linear_684_1555)"/>-->
                    <!--<path d="M10.4167 58.4786V54.2283C10.4167 54.2283 11.2504 55.8571 19.7528 55.8571C28.2553 55.8571 29.0889 53.9504 29.0889 53.9504V58.6779C29.0889 58.6779 26.7855 60.0691 19.7528 60.0691C12.7201 60.0691 10.4167 58.4805 10.4167 58.4805V58.4786Z" fill="url(#paint6_linear_684_1555)"/>-->
                    <!--<path d="M25.1548 59.7476C25.1548 59.7476 25.0726 57.2138 25.0726 56.3875C25.0726 55.7514 25.3852 55.4644 25.3852 55.4644C24.8715 55.5832 23.4236 55.8044 21.7784 55.8574C19.9484 55.9159 17.8937 55.8025 17.0491 55.8025C17.0491 55.8025 18.2556 56.0676 18.6541 56.2632C18.9521 56.4095 19.2135 56.8354 19.2135 57.2742V60.0693C20.7912 60.0693 22.7527 60.0181 25.1548 59.7476Z" fill="url(#paint7_linear_684_1555)"/>-->
                    <!--<path d="M26.8092 59.4402C27.63 59.2574 28.2022 59.0673 28.5733 58.9229V54.4038C28.2278 54.625 26.875 55.2612 25.4436 55.4221C25.4436 55.4221 26.8074 55.3855 26.8074 55.7383V59.442L26.8092 59.4402Z" fill="url(#paint8_linear_684_1555)" style="mix-blend-mode:multiply"/>-->
                    <!--<path d="M11.2632 58.8866C11.7111 59.0621 12.3509 59.2705 13.2229 59.4643V55.373C12.307 55.1938 11.6836 54.9909 11.2632 54.8044V58.8848V58.8866Z" fill="url(#paint9_linear_684_1555)" style="mix-blend-mode:multiply"/>-->
                    <!--<path d="M11.2632 54.806C11.2632 54.7493 11.6069 54.3545 12.1553 54.4751C12.8756 54.6342 15.0802 51.6982 19.0509 51.6982C22.6394 51.6982 25.2244 52.5995 26.7801 53.3728C27.2535 53.6086 27.7526 53.9998 28.1146 53.8298C28.8714 53.477 29.0889 53.9505 29.0889 53.9505C29.0889 53.9505 28.1328 55.8572 19.7529 55.8572C12.4277 55.8572 11.2632 54.806 11.2632 54.806Z" fill="url(#paint10_linear_684_1555)" style="mix-blend-mode:multiply"/>-->
                    <!--<path d="M11.965 55.0619C12.0052 54.5848 13.1916 55.1606 13.4914 54.8041C15.5133 52.3965 18.6997 52.2649 18.6997 52.2649C21.6137 51.8572 24.1035 54.7749 25.2954 54.7127C26.4874 54.6524 27.7067 54.3179 27.6573 54.6232C27.608 54.9284 24.7726 55.8809 18.9958 55.7365C13.4731 55.5975 11.9631 55.0619 11.9631 55.0619H11.965Z" fill="url(#paint11_linear_684_1555)"/>-->
                    <!--<path d="M11.089 53.6982C10.8971 53.7548 11.2335 54.221 12.1183 54.583C13.0031 54.9449 12.9756 54.9705 14.5112 55.1881C16.0468 55.4056 17.7525 55.4312 17.7525 55.4312C17.7525 55.4312 14.3997 55.1424 13.2243 54.6195C12.047 54.0967 11.4876 53.5812 11.0909 53.6982H11.089Z" fill="url(#paint12_linear_684_1555)"/>-->
                    <!--<path d="M18.5354 52.3968C18.5354 52.3968 21.6852 52.2085 21.7748 51.693C21.8644 51.1793 27.1402 27.9296 27.1402 27.9296C27.1402 27.9296 25.548 25.9187 24.835 26.1289C18.0985 28.1051 19.3489 47.6127 18.5354 52.3968Z" fill="url(#paint13_linear_684_1555)"/>-->
                    <!--<path d="M43.8997 29.4265C43.8997 29.4265 42.7626 30.4064 41.9509 30.2346C41.1393 30.0627 27.1397 27.9293 27.1397 27.9293L25.0703 26.5747L43.8997 29.4265Z" fill="url(#paint14_linear_684_1555)"/>-->
                    <!--<path d="M12.8863 15.5168C12.8863 15.5168 3.18281 19.7599 1.9068 20.9097C1.29805 21.4563 1.32912 21.6209 1.32912 21.6209L13.8808 25.5714L12.8863 15.5187V15.5168Z" fill="url(#paint15_radial_684_1555)"/>-->
                    <!--<path d="M28.9006 17.4636L31.3978 17.0815L43.9002 29.4267C43.9002 29.4267 42.6881 29.5546 41.3244 29.3956C39.9606 29.2365 28.9006 17.4636 28.9006 17.4636Z" fill="url(#paint16_linear_684_1555)"/>-->
                    <!--<path d="M37.7045 4.25756C38.4796 3.77494 39.5125 4.16798 39.5125 4.16798L31.3976 17.0816L28.7651 17.4857C28.7651 17.4857 33.86 6.65236 37.7045 4.25938V4.25756Z" fill="url(#paint17_linear_684_1555)"/>-->
                    <!--<path d="M16.9451 0.718326C16.9451 0.718326 17.9944 -0.204861 18.4002 0.0419314C18.8061 0.288724 23.3617 8.69431 23.5939 8.60839C23.8242 8.52247 37.9426 3.84254 37.9846 4.16794C38.0267 4.49334 22.2484 11.2244 21.7073 11.0946C21.1661 10.9648 16.9451 0.716498 16.9451 0.716498V0.718326Z" fill="url(#paint18_linear_684_1555)"/>-->
                    <!--<path d="M18.5351 52.3968C19.1603 52.3127 23.6775 28.2495 25.3411 27.1929C26.1509 26.6792 41.0536 29.7376 41.3241 29.3939C41.5947 29.0502 28.9004 17.4619 28.9004 17.4619C28.9004 17.4619 37.2639 4.94859 37.9842 4.16616C38.7045 3.38374 22.2059 9.91004 22.2059 9.91004C22.2059 9.91004 18.036 0.621484 16.9446 0.716545C15.9538 0.802466 13.4274 15.3431 12.8863 15.515C12.3452 15.6868 1.70564 20.811 2.24676 20.9828C2.78788 21.1547 13.0307 24.1162 13.0307 24.1162C13.0307 24.1162 17.2737 52.5686 18.5351 52.3968Z" fill="#251C0D"/>-->
                    <!--<path d="M19.4312 52.3218C19.4312 52.3218 24.3542 29.9459 25.0708 29.3444C25.7893 28.7448 26.8989 28.8052 26.8989 28.8052L21.7784 51.8812C21.7784 51.8812 21.1422 52.2194 19.4312 52.3218Z" fill="url(#paint19_linear_684_1555)" style="mix-blend-mode:multiply"/>-->
                    <!--<path d="M18.5352 52.3966C19.1604 52.3125 23.407 27.6314 25.0724 26.5748C25.8841 26.0611 41.0555 29.7392 41.326 29.3955C41.5966 29.0518 28.767 17.4855 28.767 17.4855C28.767 17.4855 36.6991 5.36338 37.4194 4.58095C38.1397 3.79853 22.206 9.91351 22.206 9.91351C22.206 9.91351 17.3049 1.25016 16.9447 1.33608C16.5846 1.422 13.5408 15.427 12.9997 15.5989C12.4586 15.7707 4.04933 20.0576 4.59045 20.2295C5.13157 20.4013 13.0911 24.0886 13.0911 24.0886C13.0911 24.0886 17.2756 52.5703 18.537 52.3984L18.5352 52.3966Z" fill="url(#paint20_linear_684_1555)"/>-->
                    <!--<path opacity="0.5" d="M22.204 9.9118C22.204 9.9118 17.3047 1.24845 16.9427 1.33438C16.8824 1.349 16.7453 1.76032 16.5588 2.42392C16.7691 5.96128 15.036 15.1676 24.0705 20.5422C28.9478 23.4434 40.0681 28.9606 40.7573 28.6479C37.9841 25.8034 28.765 17.4838 28.765 17.4838C28.765 17.4838 36.6971 5.36167 37.4174 4.57924C38.1377 3.79682 22.204 9.9118 22.204 9.9118Z" fill="url(#paint21_radial_684_1555)" style="mix-blend-mode:hard-light"/>-->
                    <!--<path opacity="0.2" d="M13.0894 24.0867C13.0894 24.0867 16.2209 25.0738 16.9448 28.4138C17.6687 31.7537 18.5353 52.3966 18.5353 52.3966C18.5353 52.3966 16.9923 48.7678 15.4714 39.2544C13.9504 29.741 13.0894 24.0867 13.0894 24.0867Z" fill="url(#paint22_linear_684_1555)"/>-->
                    <!--<path d="M28.9006 17.4634C28.9006 17.4634 29.5953 17.3391 29.7982 17.1874C29.9682 17.0612 35.7907 8.471 37.8601 5.07988C38.266 4.41445 37.8364 4.16035 37.8729 4.1439C38.3299 3.93549 39.085 4.05798 39.085 4.05798C39.085 4.05798 30.5185 17.0795 30.5404 17.2294C30.5624 17.3793 42.3006 28.8488 42.7283 29.0736C43.1561 29.2985 43.8654 29.2802 43.8983 29.4246C43.9312 29.569 42.339 29.7317 41.9404 29.6586C36.0064 28.5709 25.7088 27.198 25.8788 26.8123C26.047 26.4265 32.6683 27.379 33.9626 27.2218C35.4105 27.0463 35.4489 26.5838 35.6847 26.6094C35.9205 26.635 40.5218 28.9128 40.7558 28.6459C40.9898 28.379 35.6719 23.816 35.7048 23.7502C35.7377 23.6862 36.2459 24.0244 36.3976 23.9586C36.5494 23.8946 30.3174 17.5621 30.0468 17.4799C29.7763 17.3976 28.8951 17.6407 28.897 17.4597L28.9006 17.4634Z" fill="#251C0D"/>-->
                    <!--<path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd" d="M19.2098 54.018C19.1183 55.0326 17.8478 55.7547 16.3853 55.6213C14.921 55.4897 13.8022 54.5537 13.8937 53.5372C13.9851 52.5208 15.2538 51.8024 16.7181 51.934C18.1824 52.0656 19.303 53.0016 19.2116 54.018H19.2098Z" fill="url(#paint23_radial_684_1555)" style="mix-blend-mode:color-dodge"/>-->
                    <!--<path d="M19.7527 60.0691C26.7854 60.0691 29.0888 58.6779 29.0888 58.6779V57.4897C29.0888 57.4897 27.4161 58.7657 20.7472 59.0307C14.0783 59.2958 10.4148 57.135 10.4148 57.135V58.4805C10.4148 58.4805 12.72 60.0691 19.7509 60.0691H19.7527Z" fill="#AB9A77"/>-->
                    <!--<path d="M17.0014 22.8694L20.7545 19.7013C21.8312 18.7653 22.3943 18.2114 22.5259 17.4381C22.6703 16.5606 22.1219 16.0195 21.2316 16.0195C20.3413 16.0195 19.5863 16.6191 19.4273 17.5643H17.824C18.1202 15.7965 19.632 14.6521 21.482 14.6521C23.3321 14.6521 24.4216 15.7928 24.1584 17.3504C23.9756 18.3997 23.3412 19.2461 21.2407 20.981L19.3724 22.6116L19.3596 22.6756H23.4089L23.1767 24.0851H16.7966L17.0014 22.8676V22.8694Z" fill="#B7903B"/>-->
                    <!--<path d="M10.2501 66.0691L10.4238 65.9338L10.3616 66.3799H10.1624L10.1715 65.9338H11.5791L11.5864 66.3799H11.389L11.3268 65.9338L11.4987 66.0691H10.2538H10.2501ZM10.9228 67.3433L11.2848 67.3799V67.5078H10.464V67.3799L10.8186 67.3433H10.9228ZM10.6706 66.676C10.6706 66.4293 10.6706 66.1806 10.6632 65.9338H11.0801C11.0727 66.1751 11.0727 66.4256 11.0727 66.676V66.7638C11.0727 67.0124 11.0727 67.261 11.0801 67.5078H10.6632C10.6706 67.2665 10.6706 67.0161 10.6706 66.7656V66.676Z" fill="#7F5A0E"/>-->
                    <!--<path d="M11.8334 66.0012L11.6853 65.9774V65.8769L12.1789 65.769L12.2173 65.7928L12.2081 66.1273V66.6008L12.2154 66.6319V67.0048C12.2154 67.1529 12.2173 67.3997 12.2228 67.5076H11.8261C11.8315 67.3997 11.8334 67.1529 11.8334 67.0048V66.0012ZM12.3306 67.396V67.5076H11.6999V67.396L11.9211 67.3485H12.1039L12.3288 67.396H12.3306ZM12.9394 66.7251V67.0048C12.9394 67.1584 12.9412 67.3997 12.9467 67.5076H12.55C12.5555 67.3997 12.5573 67.1584 12.5573 67.0048V66.7526C12.5573 66.5972 12.5262 66.5442 12.4293 66.5442C12.3416 66.5442 12.2374 66.6191 12.1387 66.7379H12.0527V66.5661H12.21L12.1186 66.6337C12.2867 66.3906 12.4184 66.3138 12.5902 66.3138C12.8023 66.3138 12.9412 66.4491 12.9412 66.7251H12.9394ZM12.8279 67.3485L13.0619 67.396V67.5076H12.4293V67.396L12.6469 67.3485H12.8279Z" fill="#7F5A0E"/>-->
                    <!--<path d="M14.294 66.8202C14.294 66.8714 14.2903 66.9098 14.2812 66.95H13.3434V66.8239H13.7931C13.9082 66.8239 13.9375 66.78 13.9375 66.674C13.9375 66.5168 13.8625 66.4382 13.7748 66.4382C13.6742 66.4382 13.5847 66.546 13.5847 66.8934C13.5847 67.1858 13.7291 67.3175 13.9082 67.3175C14.038 67.3175 14.1258 67.2645 14.1989 67.1895L14.2812 67.2663C14.1825 67.4546 14.0106 67.546 13.7785 67.546C13.4293 67.546 13.1807 67.3175 13.1807 66.9317C13.1807 66.546 13.4878 66.3157 13.7986 66.3157C14.1093 66.3157 14.294 66.5314 14.294 66.8221V66.8202Z" fill="#7F5A0E"/>-->
                    <!--<path d="M14.9192 66.0636V65.9357H15.6925V66.0636L15.3378 66.1002H15.2665L14.9192 66.0636ZM15.3378 67.3433L15.6925 67.3799V67.5078H14.9192V67.3799L15.2665 67.3433H15.3378ZM15.1038 66.676C15.1038 66.4293 15.1038 66.1806 15.0965 65.9338H15.5133C15.506 66.1751 15.506 66.4256 15.506 66.676V66.7638C15.506 67.0124 15.506 67.261 15.5133 67.5078H15.0965C15.1038 67.2665 15.1038 67.0161 15.1038 66.7656V66.676Z" fill="#7F5A0E"/>-->
                    <!--<path d="M15.7749 66.9246C15.7749 66.5553 16.04 66.314 16.3837 66.314C16.7273 66.314 16.9924 66.5516 16.9924 66.9246C16.9924 67.2975 16.7548 67.5443 16.3837 67.5443C16.0126 67.5443 15.7749 67.292 15.7749 66.9246ZM16.5866 66.93C16.5866 66.59 16.5171 66.4383 16.3837 66.4383C16.2502 66.4383 16.1807 66.59 16.1807 66.93C16.1807 67.2701 16.2484 67.42 16.3837 67.42C16.5189 67.42 16.5866 67.2719 16.5866 66.93Z" fill="#7F5A0E"/>-->
                    <!--<path d="M17.0543 66.0691L17.228 65.9338L17.1658 66.3799H16.9666L16.9757 65.9338H18.3833L18.3906 66.3799H18.1932L18.131 65.9338L18.3029 66.0691H17.058H17.0543ZM17.7252 67.3433L18.0872 67.3799V67.5078H17.2664V67.3799L17.621 67.3433H17.7252ZM17.4748 66.676C17.4748 66.4293 17.4748 66.1806 17.4674 65.9338H17.8843C17.8769 66.1751 17.8769 66.4256 17.8769 66.676V66.7638C17.8769 67.0124 17.8769 67.261 17.8843 67.5078H17.4674C17.4748 67.2665 17.4748 67.0161 17.4748 66.7656V66.676Z" fill="#7F5A0E"/>-->
                    <!--<path d="M18.9354 67.3799L19.1822 67.3433H19.2243L19.4509 67.3799V67.5078H18.9336V67.3799H18.9354ZM19.6173 65.9302H19.8641L20.4034 67.5078H19.9939L19.5844 66.2208L19.674 66.1002L19.2169 67.5078H19.0744L19.6173 65.9302ZM19.3595 66.9119H20.0067L20.0597 67.0508H19.3193L19.3595 66.9119ZM19.7617 67.3853L20.0323 67.3488H20.1932L20.5003 67.3853V67.5096H19.7617V67.3853Z" fill="#7F5A0E"/>-->
                    <!--<path d="M20.9535 66.888C20.9535 67.164 21.0869 67.3158 21.288 67.3158C21.4032 67.3158 21.4891 67.2627 21.5586 67.1823L21.6427 67.2554C21.5512 67.451 21.3776 67.5443 21.1509 67.5443C20.8237 67.5443 20.5549 67.334 20.5549 66.9319C20.5549 66.5297 20.8438 66.314 21.2021 66.314C21.4544 66.314 21.6317 66.4712 21.65 66.6631C21.6244 66.7491 21.575 66.7874 21.4891 66.7874C21.394 66.7874 21.3172 66.7289 21.3045 66.5608L21.2825 66.3889L21.4562 66.4876C21.3812 66.4529 21.3191 66.4364 21.2441 66.4364C21.107 66.4364 20.9571 66.5644 20.9571 66.888H20.9535Z" fill="#7F5A0E"/>-->
                    <!--<path d="M22.237 66.8405C22.3358 66.8112 22.5058 66.7673 22.6191 66.7399V66.8405C22.5058 66.8697 22.376 66.9044 22.3065 66.9355C22.1987 66.9812 22.1274 67.0617 22.1274 67.175C22.1274 67.2957 22.1913 67.3505 22.2718 67.3505C22.3175 67.3505 22.3559 67.3322 22.4327 67.2755L22.5295 67.2024L22.5551 67.2372L22.4546 67.3468C22.3394 67.4693 22.2645 67.5443 22.0908 67.5443C21.9062 67.5443 21.7764 67.4346 21.7764 67.2372C21.7764 67.0671 21.8714 66.9465 22.2407 66.8405H22.237ZM22.4199 67.3121V66.7125C22.4199 66.5004 22.3778 66.4383 22.2261 66.4383C22.1676 66.4383 22.1054 66.4456 22.0012 66.473L22.1657 66.3798L22.1493 66.5461C22.1438 66.7052 22.0652 66.7692 21.9738 66.7692C21.8879 66.7692 21.8312 66.7271 21.8129 66.6485C21.8257 66.4492 22.0177 66.314 22.3632 66.314C22.6703 66.314 22.7928 66.4438 22.7928 66.7655V67.303C22.7928 67.3505 22.8129 67.3724 22.8476 67.3724C22.8732 67.3724 22.8915 67.3596 22.9208 67.3194L22.9847 67.3615C22.9262 67.4913 22.8421 67.5424 22.6959 67.5424C22.5332 67.5424 22.4436 67.4565 22.4217 67.3121H22.4199Z" fill="#7F5A0E"/>-->
                    <!--<path d="M23.6961 67.3358C23.8112 67.3358 23.8917 67.2499 23.9666 67.1329L23.9959 67.1713C23.8771 67.4272 23.7308 67.5424 23.5188 67.5424C23.2464 67.5424 23.0398 67.3285 23.0398 66.9373C23.0398 66.5461 23.2976 66.3121 23.5681 66.3121C23.7747 66.3121 23.8752 66.4254 23.9867 66.6393L23.963 66.6869C23.8698 66.5735 23.8003 66.5169 23.7034 66.5169C23.559 66.5169 23.4475 66.6521 23.4475 66.9355C23.4475 67.1987 23.5498 67.3377 23.6979 67.3377L23.6961 67.3358ZM24.3432 67.3962V67.5077L23.8752 67.5351L23.8442 67.3212V66.526L23.8588 66.5004V65.9995L23.6906 65.9757V65.8752L24.197 65.7673L24.2354 65.7911L24.2262 66.1256V67.5059L24.0635 67.3322L24.3432 67.3943V67.3962Z" fill="#7F5A0E"/>-->
                    <!--<path d="M25.5769 66.8202C25.5769 66.8714 25.5733 66.9098 25.5641 66.95H24.6263V66.8239H25.076C25.1912 66.8239 25.2205 66.78 25.2205 66.674C25.2205 66.5168 25.1455 66.4382 25.0578 66.4382C24.959 66.4382 24.8676 66.546 24.8676 66.8934C24.8676 67.1858 25.0121 67.3175 25.1912 67.3175C25.321 67.3175 25.4087 67.2645 25.4819 67.1895L25.5641 67.2663C25.4654 67.4546 25.2936 67.546 25.0614 67.546C24.7122 67.546 24.4636 67.3175 24.4636 66.9317C24.4636 66.546 24.7707 66.3157 25.0815 66.3157C25.3923 66.3157 25.5769 66.5314 25.5769 66.8221V66.8202Z" fill="#7F5A0E"/>-->
                    <!--<path d="M25.8386 67.5076C25.8441 67.3997 25.8459 67.1529 25.8459 67.0049V66.8659C25.8459 66.7343 25.8459 66.6849 25.8386 66.5881L25.6924 66.5661V66.4711L26.1659 66.312L26.2079 66.3376L26.2298 66.6027V67.003C26.2298 67.1511 26.2317 67.3979 26.2372 67.5058H25.8405L25.8386 67.5076ZM26.3432 67.3961V67.5076H25.7125V67.3961L25.9337 67.3485H26.1165L26.3414 67.3961H26.3432ZM26.9391 66.7252V67.0049C26.9391 67.1584 26.941 67.3997 26.9465 67.5076H26.5571C26.5626 67.3997 26.5644 67.1584 26.5644 67.0049V66.7745C26.5644 66.6063 26.5315 66.546 26.4255 66.546C26.3194 66.546 26.2134 66.6319 26.1403 66.7215H26.0525V66.5661H26.2024L26.1311 66.6338C26.2755 66.418 26.4145 66.3138 26.5955 66.3138C26.8221 66.3138 26.9391 66.4491 26.9391 66.7252ZM26.8349 67.3485L27.0561 67.3961V67.5076H26.4382V67.3961L26.6503 67.3485H26.8368H26.8349ZM27.6466 66.738V67.0049C27.6466 67.1584 27.6484 67.3997 27.6539 67.5076H27.2645C27.27 67.3997 27.2719 67.1584 27.2719 67.0049V66.7727C27.2719 66.5917 27.2335 66.5442 27.1347 66.5442C27.036 66.5442 26.9355 66.6118 26.8441 66.7197H26.7582V66.5588H26.9263L26.8276 66.6319C26.9976 66.3833 27.1311 66.312 27.2938 66.312C27.5351 66.312 27.643 66.4509 27.643 66.7361L27.6466 66.738ZM27.5442 67.3485L27.7691 67.3961V67.5076H27.153V67.3961L27.3523 67.3485H27.5442Z" fill="#7F5A0E"/>-->
                    <!--<path d="M27.959 66.4915L27.7415 66.4531V66.3489H28.4233V66.4531L28.2094 66.4915H27.959ZM27.7488 67.8954C27.7689 67.7912 27.853 67.7419 27.9462 67.7419C28.0102 67.7419 28.087 67.7583 28.1528 67.8534L28.2277 67.9631L28.1382 68.0033L28.065 67.9631C28.1784 67.8936 28.2771 67.7638 28.3319 67.603L28.4581 67.241L28.7725 66.3525H28.9297L28.4471 67.6084C28.3283 67.9156 28.2204 68.0783 27.9992 68.0783C27.8713 68.0783 27.7597 67.9996 27.7524 67.8973L27.7488 67.8954ZM28.3575 67.6596L27.8018 66.3507H28.2149L28.5348 67.241L28.3575 67.6596ZM28.767 66.4896L28.5659 66.4531V66.3489H29.0175V66.4531L28.8109 66.4896H28.7652H28.767Z" fill="#7F5A0E"/>-->
                    <!--<defs>-->
                    <!--<linearGradient id="paint0_linear_684_1555" x1="18.0124" y1="62.0252" x2="22.8038" y2="76.9425" gradientUnits="userSpaceOnUse">-->
                    <!--<stop stop-color="#1D1D1B"/>-->
                    <!--<stop offset="1" stop-color="#575756"/>-->
                    <!--</linearGradient>-->
                    <!--<linearGradient id="paint1_linear_684_1555" x1="2.73315" y1="58.6376" x2="36.4085" y2="58.6376" gradientUnits="userSpaceOnUse">-->
                    <!--<stop stop-color="#575756"/>-->
                    <!--<stop offset="1" stop-color="#1D1D1B"/>-->
                    <!--</linearGradient>-->
                    <!--<linearGradient id="paint2_linear_684_1555" x1="8.31787" y1="66.7728" x2="30.84" y2="66.7728" gradientUnits="userSpaceOnUse">-->
                    <!--<stop stop-color="#575756"/>-->
                    <!--<stop offset="1" stop-color="#1D1D1B"/>-->
                    <!--</linearGradient>-->
                    <!--<linearGradient id="paint3_linear_684_1555" x1="8.47339" y1="66.7728" x2="30.6847" y2="66.7728" gradientUnits="userSpaceOnUse">-->
                    <!--<stop stop-color="#EAC26F"/>-->
                    <!--<stop offset="1" stop-color="#EAC26F"/>-->
                    <!--</linearGradient>-->
                    <!--<linearGradient id="paint4_linear_684_1555" x1="11.7876" y1="58.0855" x2="35.1598" y2="58.0855" gradientUnits="userSpaceOnUse">-->
                    <!--<stop stop-color="#575756"/>-->
                    <!--<stop offset="1" stop-color="#1D1D1B"/>-->
                    <!--</linearGradient>-->
                    <!--<linearGradient id="paint5_linear_684_1555" x1="10.4167" y1="53.3145" x2="29.0907" y2="53.3145" gradientUnits="userSpaceOnUse">-->
                    <!--<stop stop-color="#EAC26F"/>-->
                    <!--<stop offset="0.58" stop-color="#BF9239"/>-->
                    <!--<stop offset="1" stop-color="#A47417"/>-->
                    <!--</linearGradient>-->
                    <!--<linearGradient id="paint6_linear_684_1555" x1="10.4167" y1="57.0088" x2="29.0907" y2="57.0088" gradientUnits="userSpaceOnUse">-->
                    <!--<stop stop-color="#A47417"/>-->
                    <!--<stop offset="0.42" stop-color="#BF9239"/>-->
                    <!--<stop offset="1" stop-color="#EAC26F"/>-->
                    <!--</linearGradient>-->
                    <!--<linearGradient id="paint7_linear_684_1555" x1="17.0491" y1="57.7678" x2="25.3852" y2="57.7678" gradientUnits="userSpaceOnUse">-->
                    <!--<stop stop-color="#EAC26F"/>-->
                    <!--<stop offset="0.58" stop-color="#BF9239"/>-->
                    <!--<stop offset="1" stop-color="#A47417"/>-->
                    <!--</linearGradient>-->
                    <!--<linearGradient id="paint8_linear_684_1555" x1="25.4454" y1="56.9229" x2="28.5751" y2="56.9229" gradientUnits="userSpaceOnUse">-->
                    <!--<stop stop-color="#975A03"/>-->
                    <!--<stop offset="0.26" stop-color="#724508"/>-->
                    <!--<stop offset="0.58" stop-color="#4A2F0E"/>-->
                    <!--<stop offset="0.84" stop-color="#322211"/>-->
                    <!--<stop offset="1" stop-color="#291D13"/>-->
                    <!--</linearGradient>-->
                    <!--<linearGradient id="paint9_linear_684_1555" x1="11.2632" y1="57.1353" x2="13.2229" y2="57.1353" gradientUnits="userSpaceOnUse">-->
                    <!--<stop stop-color="#291D13"/>-->
                    <!--<stop offset="0.16" stop-color="#322211"/>-->
                    <!--<stop offset="0.42" stop-color="#4A2F0E"/>-->
                    <!--<stop offset="0.74" stop-color="#724508"/>-->
                    <!--<stop offset="1" stop-color="#975A03"/>-->
                    <!--</linearGradient>-->
                    <!--<linearGradient id="paint10_linear_684_1555" x1="11.2632" y1="53.7786" x2="29.0908" y2="53.7786" gradientUnits="userSpaceOnUse">-->
                    <!--<stop stop-color="#975A03"/>-->
                    <!--<stop offset="0.26" stop-color="#724508"/>-->
                    <!--<stop offset="0.58" stop-color="#4A2F0E"/>-->
                    <!--<stop offset="0.84" stop-color="#322211"/>-->
                    <!--<stop offset="1" stop-color="#291D13"/>-->
                    <!--</linearGradient>-->
                    <!--<linearGradient id="paint11_linear_684_1555" x1="11.965" y1="53.9906" x2="27.6592" y2="53.9906" gradientUnits="userSpaceOnUse">-->
                    <!--<stop stop-color="#F4E0B6"/>-->
                    <!--<stop offset="0.25" stop-color="#F3DFB4"/>-->
                    <!--<stop offset="0.33" stop-color="#F2DCAD"/>-->
                    <!--<stop offset="0.4" stop-color="#EFD7A1"/>-->
                    <!--<stop offset="0.45" stop-color="#EBD090"/>-->
                    <!--<stop offset="0.49" stop-color="#E6C77A"/>-->
                    <!--<stop offset="0.51" stop-color="#E5C472"/>-->
                    <!--<stop offset="0.53" stop-color="#E2C06F"/>-->
                    <!--<stop offset="0.84" stop-color="#BB8F46"/>-->
                    <!--<stop offset="1" stop-color="#AC7C37"/>-->
                    <!--</linearGradient>-->
                    <!--<linearGradient id="paint12_linear_684_1555" x1="11.0379" y1="54.5574" x2="17.7525" y2="54.5574" gradientUnits="userSpaceOnUse">-->
                    <!--<stop stop-color="#EAC26F"/>-->
                    <!--<stop offset="0.58" stop-color="#BF9239"/>-->
                    <!--<stop offset="1" stop-color="#A47417"/>-->
                    <!--</linearGradient>-->
                    <!--<linearGradient id="paint13_linear_684_1555" x1="18.5354" y1="39.2547" x2="27.1402" y2="39.2547" gradientUnits="userSpaceOnUse">-->
                    <!--<stop stop-color="#EAC26F"/>-->
                    <!--<stop offset="0.24" stop-color="#E1B868"/>-->
                    <!--<stop offset="0.63" stop-color="#CBA057"/>-->
                    <!--<stop offset="1" stop-color="#B28443"/>-->
                    <!--</linearGradient>-->
                    <!--<linearGradient id="paint14_linear_684_1555" x1="25.0703" y1="28.4138" x2="43.8997" y2="28.4138" gradientUnits="userSpaceOnUse">-->
                    <!--<stop stop-color="#975A03"/>-->
                    <!--<stop offset="0.25" stop-color="#B17B25"/>-->
                    <!--<stop offset="0.58" stop-color="#D0A14D"/>-->
                    <!--<stop offset="0.84" stop-color="#E3B965"/>-->
                    <!--<stop offset="1" stop-color="#EAC26F"/>-->
                    <!--</linearGradient>-->
                    <!--<radialGradient id="paint15_radial_684_1555" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(5.43503 21.7897) scale(6.72556 6.38928)">-->
                    <!--<stop stop-color="#EAC26F"/>-->
                    <!--<stop offset="0.24" stop-color="#E1B868"/>-->
                    <!--<stop offset="0.63" stop-color="#CBA057"/>-->
                    <!--<stop offset="1" stop-color="#B28443"/>-->
                    <!--</radialGradient>-->
                    <!--<linearGradient id="paint16_linear_684_1555" x1="28.9006" y1="23.277" x2="43.9002" y2="23.277" gradientUnits="userSpaceOnUse">-->
                    <!--<stop stop-color="#B28443"/>-->
                    <!--<stop offset="0.37" stop-color="#CBA057"/>-->
                    <!--<stop offset="0.76" stop-color="#E1B868"/>-->
                    <!--<stop offset="1" stop-color="#EAC26F"/>-->
                    <!--</linearGradient>-->
                    <!--<linearGradient id="paint17_linear_684_1555" x1="33.4597" y1="10.4694" x2="35.4084" y2="11.4932" gradientUnits="userSpaceOnUse">-->
                    <!--<stop stop-color="#B28443"/>-->
                    <!--<stop offset="0.37" stop-color="#CBA057"/>-->
                    <!--<stop offset="0.76" stop-color="#E1B868"/>-->
                    <!--<stop offset="1" stop-color="#EAC26F"/>-->
                    <!--</linearGradient>-->
                    <!--<linearGradient id="paint18_linear_684_1555" x1="27.5407" y1="2.01993" x2="25.7748" y2="11.6796" gradientUnits="userSpaceOnUse">-->
                    <!--<stop stop-color="#B28443"/>-->
                    <!--<stop offset="0.37" stop-color="#CBA057"/>-->
                    <!--<stop offset="0.76" stop-color="#E1B868"/>-->
                    <!--<stop offset="1" stop-color="#EAC26F"/>-->
                    <!--</linearGradient>-->
                    <!--<linearGradient id="paint19_linear_684_1555" x1="19.4312" y1="40.5616" x2="26.8989" y2="40.5616" gradientUnits="userSpaceOnUse">-->
                    <!--<stop stop-color="#291D13"/>-->
                    <!--<stop offset="0.11" stop-color="#32251A"/>-->
                    <!--<stop offset="0.3" stop-color="#4A3C2E"/>-->
                    <!--<stop offset="0.53" stop-color="#72614E"/>-->
                    <!--<stop offset="0.8" stop-color="#A9957A"/>-->
                    <!--<stop offset="1" stop-color="#D8C1A0"/>-->
                    <!--</linearGradient>-->
                    <!--<linearGradient id="paint20_linear_684_1555" x1="26.7232" y1="4.14952" x2="18.0544" y2="51.5246" gradientUnits="userSpaceOnUse">-->
                    <!--<stop stop-color="#F4E0B6"/>-->
                    <!--<stop offset="0.25" stop-color="#F3DFB4"/>-->
                    <!--<stop offset="0.33" stop-color="#F2DCAD"/>-->
                    <!--<stop offset="0.4" stop-color="#EFD7A1"/>-->
                    <!--<stop offset="0.45" stop-color="#EBD090"/>-->
                    <!--<stop offset="0.49" stop-color="#E6C77A"/>-->
                    <!--<stop offset="0.51" stop-color="#E5C472"/>-->
                    <!--<stop offset="0.53" stop-color="#E2C06F"/>-->
                    <!--<stop offset="0.84" stop-color="#BB8F46"/>-->
                    <!--<stop offset="1" stop-color="#AC7C37"/>-->
                    <!--</linearGradient>-->
                    <!--<radialGradient id="paint21_radial_684_1555" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(28.6553 14.9976) scale(12.9064)">-->
                    <!--<stop stop-color="white"/>-->
                    <!--<stop offset="0.13" stop-color="#FDFBF8"/>-->
                    <!--<stop offset="0.32" stop-color="#FAF0E4"/>-->
                    <!--<stop offset="0.55" stop-color="#F4DEC4"/>-->
                    <!--<stop offset="0.8" stop-color="#ECC598"/>-->
                    <!--<stop offset="1" stop-color="#E5B072"/>-->
                    <!--</radialGradient>-->
                    <!--<linearGradient id="paint22_linear_684_1555" x1="13.0894" y1="38.2416" x2="18.5353" y2="38.2416" gradientUnits="userSpaceOnUse">-->
                    <!--<stop stop-color="#92682E"/>-->
                    <!--<stop offset="0.54" stop-color="#B38A46"/>-->
                    <!--<stop offset="1" stop-color="#D3AB5E"/>-->
                    <!--</linearGradient>-->
                    <!--<radialGradient id="paint23_radial_684_1555" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(16.3342 53.7862) rotate(95.16) scale(1.84798 2.66536)">-->
                    <!--<stop stop-color="white"/>-->
                    <!--<stop offset="0.12" stop-color="#EAE7E4"/>-->
                    <!--<stop offset="0.32" stop-color="#B4B0AB"/>-->
                    <!--<stop offset="0.52" stop-color="#7E776F"/>-->
                    <!--<stop offset="1" stop-color="#000100"/>-->
                    <!--</radialGradient>-->
                    <!--</defs>-->
                    <!--</svg>-->


                    <div class="d-flex flex-column gap-2 forbes-right">
                        <img loading="lazy" src="http://localhost/TIA/assets/dit/images/navbar/forbes02.svg"
                            alt="forbes" class="img-fluid forbes02-img" width="130" height="35">


                        <!--<svg  class="img-fluid forbes02-img" width="130" height="35" viewBox="0 0 130 35" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">-->
                        <!--<rect width="130" height="35" fill="url(#pattern0_684_1553)"/>-->
                        <!--<defs>-->
                        <!--<pattern id="pattern0_684_1553" patternContentUnits="objectBoundingBox" width="1" height="1">-->
                        <!--<use xlink:href="#image0_684_1553" transform="matrix(0.00078125 0 0 0.00290179 0 -0.00636161)"/>-->
                        <!--</pattern>-->
                        <!--<image id="image0_684_1553" width="1280" height="349" preserveAspectRatio="none" xlink:href="data:image/png;base64,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"/>-->
                        <!--</defs>-->
                        <!--</svg>-->


                        <div class="d-flex align-items-center desc">
                            <div class="visionary">Visionary Companies</div>
                            <div class="rank">Rank #2</div>
                        </div>
                    </div>

                </a>

            </div>
            <button class="navbar-toggler btn-open " type="button" aria-label="toggler">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="16" viewBox="0 0 20 16" fill="none">
                    <path
                        d="M6.66675 3.33334H18.7501C19.0816 3.33334 19.3995 3.20164 19.634 2.96722C19.8684 2.7328 20.0001 2.41486 20.0001 2.08334C20.0001 1.75182 19.8684 1.43387 19.634 1.19945C19.3995 0.965032 19.0816 0.833336 18.7501 0.833336H6.66675C6.33523 0.833336 6.01729 0.965032 5.78286 1.19945C5.54844 1.43387 5.41675 1.75182 5.41675 2.08334C5.41675 2.41486 5.54844 2.7328 5.78286 2.96722C6.01729 3.20164 6.33523 3.33334 6.66675 3.33334Z"
                        fill="#4356A1" />
                    <path
                        d="M18.7501 6.66666H6.66675C6.33523 6.66666 6.01729 6.79836 5.78286 7.03278C5.54844 7.2672 5.41675 7.58514 5.41675 7.91666C5.41675 8.24819 5.54844 8.56613 5.78286 8.80055C6.01729 9.03497 6.33523 9.16666 6.66675 9.16666H18.7501C19.0816 9.16666 19.3995 9.03497 19.634 8.80055C19.8684 8.56613 20.0001 8.24819 20.0001 7.91666C20.0001 7.58514 19.8684 7.2672 19.634 7.03278C19.3995 6.79836 19.0816 6.66666 18.7501 6.66666Z"
                        fill="#4356A1" />
                    <path
                        d="M18.7501 12.5H6.66675C6.33523 12.5 6.01729 12.6317 5.78286 12.8661C5.54844 13.1005 5.41675 13.4185 5.41675 13.75C5.41675 14.0815 5.54844 14.3995 5.78286 14.6339C6.01729 14.8683 6.33523 15 6.66675 15H18.7501C19.0816 15 19.3995 14.8683 19.634 14.6339C19.8684 14.3995 20.0001 14.0815 20.0001 13.75C20.0001 13.4185 19.8684 13.1005 19.634 12.8661C19.3995 12.6317 19.0816 12.5 18.7501 12.5Z"
                        fill="#4356A1" />
                    <path
                        d="M2.08333 4.16667C3.23393 4.16667 4.16667 3.23393 4.16667 2.08333C4.16667 0.93274 3.23393 0 2.08333 0C0.93274 0 0 0.93274 0 2.08333C0 3.23393 0.93274 4.16667 2.08333 4.16667Z"
                        fill="#4356A1" />
                    <path
                        d="M2.08333 9.58333C3.23393 9.58333 4.16667 8.65059 4.16667 7.5C4.16667 6.3494 3.23393 5.41666 2.08333 5.41666C0.93274 5.41666 0 6.3494 0 7.5C0 8.65059 0.93274 9.58333 2.08333 9.58333Z"
                        fill="#4356A1" />
                    <path
                        d="M2.08333 15.4167C3.23393 15.4167 4.16667 14.4839 4.16667 13.3333C4.16667 12.1827 3.23393 11.25 2.08333 11.25C0.93274 11.25 0 12.1827 0 13.3333C0 14.4839 0.93274 15.4167 2.08333 15.4167Z"
                        fill="#4356A1" />
                </svg>

            </button>
            <button class="allCourseBtnfp">All Courses</button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-lg-0">

                    <li class="nav-item">
                        <a href="http://localhost/TIA/" class="nav-link active">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link dropdown-toggle allCourseBtn" href="#">Programs</a>
                    </li>

                    <li class="nav-item">
                        <a href="http://localhost/TIA/industrial-summer-training-programs"
                            class="nav-link">Industrial Summer Training Programs<span
                                class="new_blink_icon_style">New</span></a>
                    </li>
                    <!-- <li class="nav-item">
                        <a href="http://localhost/TIA/startup-seed-fund-program" class="nav-link">Seed
                            Fund Program <span class="new_blink_icon_style">New</span></a>
                    </li> -->
                    <!--<li class="nav-item dropdown">-->
                    <!--    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"-->
                    <!--        aria-expanded="false">-->
                    <!--        Career-->
                    <!--    </a>-->
                    <!--    <ul class="dropdown-menu sub-menu">-->
                    <!--        <li><a class="dropdown-item" href="https://job.uctconsulting.com/" target="_blank"-->
                    <!--                rel="nofollow noopener">Job Portal</a></li>-->
                    <!--        <li><a class="dropdown-item" href="http://localhost/TIA/hire-from-us"-->
                    <!--                target="_blank">Hire From Us</a></li>-->
                    <!--        <li>-->
                    <!--            <a class="dropdown-item" href="http://localhost/TIA/our-placements"-->
                    <!--                class="nav-link">Placements</a>-->
                    <!--        </li>-->
                    <!--    </ul>-->
                    <!--</li>-->
                    <li class="nav-item">
                        <a href="http://localhost/TIA/1pps/" class="nav-link">1PPS</a>
                    </li>
                    <li class="nav-item">
                        <a href="http://localhost/TIA/blog/" class="nav-link">Blogs</a>
                    </li>
                </ul>
                <div class="btn-group">
                    <a href="https://learn.upskillcampus.com/s/dashboard" class="login-btn custom-btn"><svg
                            xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 15 16" fill="none">
                            <path
                                d="M0.0116894 14.1943C-0.102993 12.3127 0.632105 10.8015 1.95566 9.49819C4.67891 6.81536 7.36549 4.09587 10.0765 1.39988C11.5025 -0.0191054 13.6937 0.280867 14.6657 2.01205C15.2692 3.08593 15.0445 4.46825 14.081 5.45938C13.2735 6.29065 12.4454 7.10123 11.6257 7.92028C9.73247 9.81414 7.82798 11.6958 5.95076 13.6056C4.80017 14.7764 3.45876 15.4694 1.79679 15.4788C1.35874 15.4816 0.918812 15.4769 0.481701 15.4986C0.105692 15.5174 -0.0221514 15.3519 0.00792931 14.9936C0.0295499 14.7294 0.0126294 14.4614 0.0126294 14.1943H0.0116894Z"
                                fill="white" />
                        </svg> Login</a>
                </div>
            </div>
        </div>
    </nav>
</header>
<!--<div class="text-center mx-auto py-2 forbes-strip text-white">-->

<!--    <a class="container-fluid text-decoration-none text-white px-2" target="_blank" href="https://www.forbesindia.com/article/upfront/brand-connect/visionary-companies-building-indias-future/2991497/1">-->

<!--        <span>NEWS:</span> The loT Academy is proud to be <strong>Ranked #2 in Forbes India's Visionary Companies</strong> Building India's Future! | <span class="tukur-text">Learn More</span>-->
<!--    </a>-->
<!--</div>-->
<!--megamenu-->
<!-- <section class="nsh-all-courses-hover">
    <div class="container-fluid">
        <div class="nsh-ach-inner">
            <div class="nsh-ach-left">
                <div class="nsh-achl-each courses-active-tab2 finanace">
                    <span>Data Science, ML & AI <span class="new">Best Selling</span></span>
                    <span class="icon courses-active-icon"></span>
                </div>
                <div class="nsh-achl-each generativeaicoursetb">
                    <span>Generative AI <span class="new">Top Trending</span></span>
                    <span class="icon"></span>
                </div>
                <div class="nsh-achl-each management">
                    <span>Embedded Systems & IoT <span class="new">Guaranteed Placement</span></span>
                    <span class="icon"></span>
                </div>
                <div class="nsh-achl-each newanalyticscourse">
                    <span>Data Analyst <span class="new">Most in Demand</span></span>
                    <span class="icon"></span>
                </div>
                <div class="nsh-achl-each sorttermprograms">
                    <span>Short Term Programs <span class="new">Trending</span></span>
                    <span class="icon"></span>
                </div>
                <div class="nsh-achl-each analytics">
                    <span>Java Full Stack</span>
                    <span class="icon"></span>
                </div>
                <div class="nsh-achl-each technology">
                    <span>Digital Marketing</span>
                    <span class="icon"></span>
                </div>
                <div class="nsh-achl-each marketing">
                    <span>Python</span>
                    <span class="icon"></span>
                </div>
                <div class="nsh-achl-each damlgenaicoursetb">
                    <span>Self Paced Course</span>
                    <span class="icon"></span>
                </div>
                <div class="nsh-achl-each sorttermcoursetb">
                    <span>Summer Training/Internship <span class="new">New</span></span>
                    <span class="icon"></span>
                </div>
            </div>
            <div class="nsh-ach-right finanace d-flex">
                <a href="http://localhost/TIA/advanced-certification-in-data-science-machine-learning-and-ai-by-eict-iitg">
                    <img decoding="async" loading="lazy" src="http://localhost/TIA/assets/dit/images/home/guwahati.svg" alt="eict-logo" class="img-fluid">
                    <span class="content">
                        <span>Advanced Certification in Applied Data Science, Machine Learning & AI By E&ICT Academy, IIT Guwahati</span>
                        <span class="duration">9 months</span>
                    </span>
                </a>
                <a href="http://localhost/TIA/online-certification-in-applied-data-science-machine-learning-edge-ai-by-eict-academy-iit-guwahati">
                    <img decoding="async" loading="lazy" src="http://localhost/TIA/assets/dit/images/home/guwahati.svg" alt="eict-logo" class="img-fluid">
                    <span class="content">
                        <span>Online Certification in Applied Data Science, Machine Learning and Edge AI By E&ICT Academy, IIT Guwahati </span>
                        <span class="duration">6 months</span>
                    </span>
                </a>
            </div>
            <div class="nsh-ach-right generativeaicoursetb d-none">
                <a href="http://localhost/TIA/advanced-generative-ai-course">
                    <img decoding="async" loading="lazy" src="http://localhost/TIA/assets/dit/images/home/guwahati.svg" alt="eict-logo" class="img-fluid">
                    <span class="content">
                        <span>Advanced Generative AI Certification Course by E&ICT Academy, IIT Guwahati</span>
                        <span class="duration">6 months</span>
                    </span>
                </a>
            </div>
            
            <div class="nsh-ach-right sorttermprograms d-none">
                <div style="width: 100%; display: flex; flex-wrap: wrap; gap: 1rem;">
                    <a href="http://localhost/TIA/short-term-programs">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="29" viewBox="0 0 48 29" fill="none">
                                <path d="M20.3044 5.93391C20.5931 5.53086 20.8533 5.12833 21.1523 4.75578C23.1934 2.21502 25.835 0.672469 29.0587 0.173872C32.7083 -0.390797 36.0443 0.414276 38.9761 2.67143C41.4075 4.54282 42.8967 7.01039 43.4186 10.035C43.6425 11.3321 43.617 12.6337 43.3696 13.9262C43.3308 14.1285 43.3885 14.1915 43.5548 14.2657C45.2684 15.0342 46.5612 16.2438 47.3478 17.9511C48.8717 21.2572 47.6498 25.1225 44.4685 27.0386C43.9624 27.3436 43.3977 27.555 42.8518 27.7903C42.6003 27.8986 42.4391 27.8269 42.3697 27.6353C42.2978 27.4356 42.3947 27.2999 42.6646 27.2023C45.0241 26.3484 46.6198 24.7652 47.2075 22.3078C47.832 19.6969 46.7234 17.0032 44.4501 15.4835C43.9716 15.1633 43.4313 14.9336 42.913 14.6743C42.6819 14.5585 42.6197 14.4721 42.6758 14.221C43.6961 9.68075 41.7045 5.22642 37.965 2.70192C36.1749 1.4933 34.199 0.774628 32.039 0.667386C27.1742 0.425966 23.3766 2.29837 20.7615 6.43708C20.6064 6.68206 20.4722 6.72678 20.1983 6.58651C18.7535 5.84801 17.2154 5.55526 15.6053 5.68334C13.6387 5.83988 11.9128 6.5799 10.4701 7.92931C9.11613 9.19486 8.30396 10.7496 8.05449 12.5844C8.00092 12.9803 8.00603 13.3849 7.99582 13.7859C7.98715 14.1219 7.91114 14.1966 7.56882 14.2083C5.02977 14.2967 3.007 15.3427 1.61835 17.4814C0.0123727 19.9561 0.390909 23.2923 2.46674 25.3985C3.37278 26.3179 4.43901 26.9593 5.6843 27.3065C5.71389 27.3146 5.74246 27.3278 5.77256 27.3354C5.96999 27.3863 6.0608 27.5143 6.02815 27.7121C5.99856 27.8925 5.82255 27.9992 5.61135 27.9255C5.0864 27.7431 4.54461 27.5835 4.05486 27.3283C1.9474 26.23 0.573035 24.539 0.135321 22.1909C-0.323821 19.7279 0.39397 17.5887 2.1586 15.8194C3.51817 14.4558 5.19455 13.7432 7.11886 13.6096C7.25405 13.6004 7.3668 13.6055 7.37547 13.4047C7.51934 10.1046 9.08858 7.6574 11.9807 6.10875C14.5799 4.71715 17.2883 4.68259 20.0126 5.80532C20.1115 5.84598 20.2085 5.89121 20.3049 5.9334L20.3044 5.93391Z" fill="#03045E"/>
                                <path d="M10.5676 23.8793C12.3516 23.8555 14.1351 23.8351 15.9191 23.8072C17.6639 23.7802 19.4081 23.7472 21.1528 23.7152C22.7808 23.6852 24.4092 23.6547 26.0371 23.6222C27.541 23.5927 29.045 23.5622 30.5489 23.5292C31.8743 23.5002 33.2002 23.4727 34.5256 23.4351C35.6102 23.4041 36.6948 23.3619 37.7789 23.3187C38.1462 23.304 38.361 22.9533 38.2268 22.6001C37.8187 21.5261 37.408 20.4527 36.9988 19.3788C36.9662 19.2934 36.9381 19.2065 36.8943 19.0825C37.2259 19.0825 37.5319 19.0738 37.8365 19.0906C37.8875 19.0931 37.9523 19.1983 37.9763 19.268C38.2288 19.9963 38.4737 20.7272 38.7221 21.4575C38.7415 21.5144 38.767 21.5693 38.8104 21.6751C38.8609 21.5393 38.8976 21.4504 38.9283 21.3594C39.1594 20.6646 39.395 19.9709 39.6159 19.2725C39.6654 19.1165 39.7389 19.0682 39.895 19.0758C40.1419 19.088 40.3899 19.0789 40.6664 19.0789C40.6419 19.1633 40.6271 19.2268 40.6046 19.2878C40.119 20.6128 39.6348 21.9383 39.1451 23.2623C38.863 24.0257 38.4946 24.2819 37.6712 24.2738C35.8408 24.2555 34.0103 24.229 32.1804 24.2046C31.1336 24.1909 30.0867 24.1736 29.0399 24.1589C26.8916 24.1279 24.7438 24.0969 22.5956 24.0659C21.5564 24.0506 20.5172 24.0349 19.4785 24.0196C17.3226 23.9886 15.1672 23.9546 13.0112 23.9281C12.1975 23.9185 11.3833 23.9266 10.5691 23.9266L10.5681 23.8788L10.5676 23.8793Z" fill="#03045E"/>
                                <path d="M36.3111 22.751H35.3827C35.3827 22.38 35.3862 22.0257 35.3816 21.6715C35.3755 21.1932 35.3663 20.715 35.3521 20.2367C35.3454 20.0136 35.2648 19.8276 35.0301 19.7518C34.7837 19.6725 34.57 19.7437 34.3873 19.9109C34.1705 20.1096 34.1251 20.3785 34.12 20.6519C34.1093 21.2466 34.1088 21.8413 34.1057 22.4359C34.1037 22.7683 34.1057 22.7683 33.7604 22.7683C33.6206 22.7683 33.4803 22.7607 33.3415 22.7703C33.1849 22.781 33.1436 22.7124 33.1451 22.566C33.1517 22.0329 33.1502 21.4997 33.1461 20.9671C33.1446 20.7434 33.1354 20.5198 33.1206 20.2967C33.0972 19.9465 32.9829 19.7793 32.7462 19.7305C32.4498 19.6695 32.0865 19.8159 31.9922 20.1C31.9013 20.3724 31.8646 20.6707 31.8534 20.9594C31.8325 21.4916 31.8478 22.0252 31.8463 22.5584C31.8463 22.6184 31.836 22.6783 31.8279 22.7663C31.5488 22.7663 31.279 22.7719 31.0096 22.7602C30.9678 22.7581 30.8953 22.6748 30.8953 22.629C30.8917 21.4707 30.8963 20.3119 30.9004 19.1536C30.9004 19.1399 30.9127 19.1257 30.9264 19.0957H31.7779C31.7845 19.2365 31.7906 19.365 31.7988 19.5399C32.1304 19.2151 32.4727 18.992 32.9308 18.9905C33.3895 18.9894 33.7415 19.1607 33.9925 19.5572C34.1037 19.4581 34.1955 19.3671 34.2965 19.2873C34.7087 18.961 35.1715 18.9096 35.6561 19.0758C36.0418 19.2085 36.2943 19.4952 36.3045 19.8997C36.328 20.8405 36.3122 21.7818 36.3122 22.75L36.3111 22.751Z" fill="#03045E"/>
                                <path d="M8.89734 22.7683C9.12079 22.1975 9.32587 21.6751 9.52994 21.1521C9.96255 20.0466 10.3977 18.9422 10.8242 17.8347C10.8773 17.6975 10.941 17.6431 11.091 17.6512C11.3308 17.6644 11.5726 17.6436 11.8114 17.6614C11.8894 17.667 12.0042 17.7305 12.0312 17.7961C12.6893 19.4098 13.3383 21.0275 13.9877 22.6443C13.9959 22.6651 13.9953 22.6895 14.0071 22.7683C13.7581 22.7683 13.5143 22.7485 13.2755 22.7734C13.0061 22.8018 12.8653 22.7119 12.7949 22.4481C12.7317 22.2113 12.6169 21.9886 12.5343 21.7564C12.4929 21.64 12.4343 21.5831 12.3006 21.5841C11.7267 21.5902 11.1527 21.5897 10.5788 21.5841C10.4452 21.5826 10.388 21.64 10.3477 21.7589C10.2544 22.0364 10.1416 22.3073 10.0503 22.5853C10.008 22.7139 9.94775 22.7754 9.80389 22.7703C9.51871 22.7612 9.23302 22.7678 8.89734 22.7678V22.7683ZM12.1425 20.7475C11.9032 20.1071 11.6772 19.5023 11.4512 18.898C11.4333 18.9 11.4155 18.902 11.3976 18.9041C11.1762 19.5109 10.9553 20.1183 10.7263 20.7475H12.1425Z" fill="#03045E"/>
                                <path d="M33.4303 14.5153C33.4303 15.79 32.5584 16.6733 31.3004 16.6728C29.9806 16.6723 29.1557 15.8357 29.1547 14.4975C29.1541 13.2452 30.0332 12.3684 31.2912 12.3664C32.5523 12.3644 33.4303 13.2467 33.4303 14.5158V14.5153ZM32.36 14.5153C32.3008 14.284 32.2697 14.035 32.1743 13.8144C32.0141 13.4439 31.7034 13.2416 31.2963 13.2421C30.879 13.2426 30.5673 13.4546 30.4025 13.8317C30.2066 14.281 30.203 14.7506 30.3984 15.2009C30.5607 15.575 30.855 15.7951 31.2825 15.7996C31.7192 15.8042 32.0243 15.5907 32.187 15.2019C32.2743 14.9936 32.3008 14.7598 32.36 14.5148V14.5153Z" fill="#03045E"/>
                                <path d="M25.0918 22.2499C25.0076 22.3221 24.9729 22.348 24.9423 22.3785C24.2638 23.0616 23.2659 22.9828 22.7134 22.2016C22.2487 21.5454 22.2359 20.285 22.6869 19.6227C23.1664 18.9188 24.1521 18.7765 24.8117 19.3157C24.8632 19.3579 24.9153 19.4001 25.003 19.4713V17.6695H25.9621V22.7658C25.7065 22.7658 25.4601 22.7719 25.2147 22.7597C25.1719 22.7576 25.1066 22.6829 25.0979 22.6341C25.0795 22.5304 25.0923 22.4217 25.0923 22.2494L25.0918 22.2499ZM23.3256 20.8674C23.3343 20.87 23.343 20.8725 23.3516 20.8746C23.3598 21.0052 23.3608 21.1368 23.3787 21.2664C23.3924 21.3645 23.42 21.4626 23.4542 21.5561C23.5725 21.8763 23.8531 22.0817 24.1725 22.0908C24.4622 22.0989 24.7775 21.8966 24.8949 21.5932C25.0709 21.1373 25.0668 20.6687 24.8974 20.2138C24.779 19.8962 24.5286 19.7208 24.1811 19.7223C23.8337 19.7244 23.5868 19.9068 23.4659 20.2189C23.3868 20.4227 23.37 20.6499 23.3256 20.8669V20.8674Z" fill="#03045E"/>
                                <path d="M17.7062 16.5885H16.7834V11.5191H17.7231V13.3631C17.8231 13.2782 17.8771 13.2319 17.9312 13.1862C18.3807 12.8076 18.8857 12.7313 19.4326 12.9265C19.8086 13.0607 20.0647 13.3412 20.0861 13.7275C20.1397 14.6734 20.1438 15.6218 20.1682 16.5859H19.176C19.176 16.4187 19.1775 16.2662 19.176 16.1137C19.1683 15.4652 19.1688 14.8167 19.1474 14.1687C19.1352 13.8012 18.9837 13.6279 18.6852 13.5679C18.372 13.5049 17.9919 13.6548 17.8817 13.9425C17.7904 14.1814 17.744 14.4487 17.7348 14.7059C17.7134 15.3077 17.7261 15.911 17.7246 16.5137C17.7246 16.5285 17.7174 16.5437 17.7067 16.5879L17.7062 16.5885Z" fill="#03045E"/>
                                <path d="M21.8084 22.7673C21.4865 22.7673 21.2238 22.7734 20.9615 22.7622C20.9105 22.7602 20.8457 22.7002 20.8161 22.6504C20.7707 22.5736 20.7503 22.4822 20.7116 22.3754C20.6335 22.4349 20.5748 22.4806 20.5146 22.5249C20.0871 22.8425 19.6101 22.9127 19.1041 22.781C18.6597 22.6656 18.3679 22.3038 18.3414 21.8672C18.3087 21.332 18.5153 20.9564 18.9959 20.7917C19.3739 20.6621 19.7734 20.5935 20.1631 20.4974C20.2305 20.4807 20.3004 20.4695 20.3647 20.4441C20.4764 20.3998 20.6478 20.4283 20.6417 20.2311C20.635 20.0253 20.5784 19.8499 20.3611 19.7716C19.9035 19.6065 19.5622 19.7325 19.2964 20.1788C19.1602 20.1544 19.0173 20.1284 18.8745 20.103C18.7383 20.0786 18.6021 20.0547 18.4398 20.0263C18.5607 19.5887 18.7842 19.2507 19.2168 19.1104C19.7759 18.929 20.3499 18.9366 20.91 19.1028C21.3692 19.239 21.5676 19.5475 21.5865 20.0944C21.5982 20.4415 21.586 20.7892 21.5895 21.1363C21.5952 21.6725 21.5283 22.2169 21.8084 22.7678V22.7673ZM20.6177 20.9889C20.2677 21.0799 19.9254 21.1465 19.5994 21.2593C19.2724 21.3726 19.2046 21.7416 19.4372 21.9947C19.5928 22.164 19.8693 22.2133 20.1269 22.1177C20.5636 21.9561 20.7044 21.6491 20.6177 20.9889Z" fill="#03045E"/>
                                <path d="M27.7022 21.1922C27.6747 21.644 27.9083 22.0029 28.269 22.0994C28.6629 22.2052 28.8827 22.0822 29.145 21.6024C29.4434 21.6532 29.7454 21.705 30.0821 21.763C29.9265 22.1935 29.6771 22.506 29.2776 22.6885C28.0104 23.2669 26.7146 22.4791 26.6942 21.0972C26.6886 20.7277 26.7401 20.3358 26.8656 19.9902C27.1278 19.268 27.8099 18.9183 28.6476 19.0006C29.3587 19.0708 29.9056 19.5699 30.0597 20.3058C30.1051 20.5239 30.1163 20.749 30.1464 20.9701C30.1699 21.1434 30.0985 21.1978 29.9245 21.1953C29.273 21.1866 28.6215 21.1917 27.9701 21.1917C27.8859 21.1917 27.8022 21.1917 27.7022 21.1917V21.1922ZM27.714 20.5823H29.1674C29.1368 20.0075 28.8246 19.6827 28.367 19.7239C27.9578 19.761 27.6681 20.1366 27.714 20.5823Z" fill="#03045E"/>
                                <path d="M24.3602 15.0393H21.8977C21.8385 15.4327 22.0727 15.8179 22.4226 15.9282C22.82 16.0533 23.0797 15.9186 23.3297 15.4444C23.6261 15.4932 23.9281 15.543 24.2684 15.5989C24.1271 15.9963 23.9092 16.2881 23.5618 16.4756C22.3068 17.1541 20.9172 16.3562 20.8666 14.9265C20.8473 14.3831 20.9223 13.8571 21.2829 13.42C21.7482 12.8563 22.3645 12.7278 23.0481 12.8818C23.7052 13.0297 24.0863 13.4734 24.2347 14.1107C24.3026 14.4009 24.3163 14.7033 24.3597 15.0393H24.3602ZM21.9053 14.4502C22.3481 14.4502 22.7956 14.4538 23.243 14.4446C23.2833 14.4436 23.3542 14.3638 23.3557 14.3186C23.369 13.8927 22.9807 13.5257 22.5639 13.5593C22.1431 13.5933 21.837 13.9923 21.9053 14.4502Z" fill="#03045E"/>
                                <path d="M14.5667 16.5864H13.5515V12.3623H12.0353V11.5201H16.0783V12.3456H14.5667V16.5864Z" fill="#03045E"/>
                                <path d="M35.9515 16.5839H34.9245V12.3572H33.4068C33.4068 12.0955 33.3976 11.852 33.4155 11.6106C33.4186 11.5694 33.5349 11.5064 33.6002 11.5054C34.0348 11.4958 34.4695 11.5003 34.9041 11.5003C35.6648 11.5003 36.4254 11.5003 37.1861 11.5003H37.4559V12.3476H35.952V16.5844L35.9515 16.5839Z" fill="#03045E"/>
                                <path d="M16.9338 21.3594C17.2486 21.4143 17.5506 21.4662 17.8705 21.5221C17.7251 22.1177 17.4348 22.5584 16.84 22.7464C15.9885 23.0148 15.1233 22.7642 14.6968 22.1269C14.2703 21.489 14.2581 20.3277 14.7065 19.7056C14.855 19.4997 15.0641 19.3081 15.2886 19.1922C15.8477 18.903 16.4426 18.9163 17.0211 19.147C17.4557 19.3203 17.6802 19.6873 17.822 20.1472C17.5119 20.2057 17.2109 20.2621 16.8951 20.3216C16.8767 20.2804 16.8583 20.2418 16.8425 20.2021C16.7216 19.8921 16.5043 19.7274 16.1931 19.7376C15.8768 19.7478 15.6059 19.8723 15.4927 20.1828C15.3182 20.6626 15.3274 21.1556 15.511 21.6308C15.6263 21.9292 15.9248 22.0776 16.2676 22.0583C16.564 22.041 16.7553 21.8834 16.868 21.5632C16.8884 21.5058 16.9063 21.4468 16.9344 21.3599L16.9338 21.3594Z" fill="#03045E"/>
                                <path d="M27.1166 14.0279C27.1166 13.2635 27.1217 12.4985 27.113 11.7341C27.111 11.5522 27.1666 11.4841 27.3513 11.4973C27.5441 11.511 27.7395 11.5085 27.9328 11.4978C28.0961 11.4886 28.1481 11.5501 28.1466 11.7113C28.14 12.4528 28.1435 13.1943 28.1435 13.9359C28.1435 14.747 28.1389 15.5577 28.1471 16.3689C28.1491 16.5508 28.0935 16.6189 27.9089 16.6057C27.716 16.592 27.5206 16.5945 27.3273 16.6052C27.164 16.6144 27.112 16.5529 27.1135 16.3918C27.1196 15.604 27.1166 14.8162 27.1166 14.0284V14.0279Z" fill="#03045E"/>
                                <path d="M7.26688 27.1454C7.3383 27.0198 7.43523 26.9212 7.55818 26.8506C7.68112 26.7799 7.81734 26.7444 7.96783 26.7444C8.14435 26.7444 8.29842 26.7866 8.42953 26.8714C8.56115 26.9563 8.65706 27.0763 8.71725 27.2323H8.46422C8.41932 27.1352 8.35453 27.0605 8.27036 27.0082C8.18618 26.9558 8.08517 26.9299 7.96732 26.9299C7.84948 26.9299 7.75306 26.9563 7.66327 27.0082C7.57348 27.0605 7.50308 27.1352 7.45207 27.2313C7.40105 27.3278 7.37503 27.4407 7.37503 27.5698C7.37503 27.6989 7.40054 27.8097 7.45207 27.9062C7.50308 28.0028 7.57348 28.077 7.66327 28.1294C7.75306 28.1817 7.85458 28.2076 7.96732 28.2076C8.08007 28.2076 8.18567 28.1817 8.27036 28.1304C8.35504 28.079 8.41932 28.0043 8.46422 27.9073H8.71725C8.65706 28.0613 8.56115 28.1807 8.42953 28.2646C8.29791 28.3484 8.14435 28.3906 7.96783 28.3906C7.81785 28.3906 7.68112 28.3555 7.55818 28.2854C7.43523 28.2153 7.3383 28.1177 7.26688 27.9926C7.19597 27.8676 7.16026 27.7268 7.16026 27.5698C7.16026 27.4127 7.19597 27.2714 7.26688 27.1454Z" fill="#03045E"/>
                                <path d="M9.2621 28.3164C9.16517 28.2625 9.08916 28.1858 9.03457 28.0862C8.97947 27.9865 8.95244 27.8717 8.95244 27.7405C8.95244 27.6094 8.9805 27.4966 9.03712 27.3975C9.09375 27.2979 9.17078 27.2216 9.26822 27.1688C9.36566 27.1154 9.47484 27.089 9.59523 27.089C9.71563 27.089 9.82532 27.1154 9.92276 27.1688C10.0202 27.2221 10.0972 27.2979 10.1539 27.3965C10.2105 27.4951 10.2385 27.6099 10.2385 27.7411C10.2385 27.8722 10.2095 27.987 10.1513 28.0867C10.0931 28.1858 10.0146 28.263 9.91561 28.3169C9.81664 28.3708 9.70645 28.3977 9.58605 28.3977C9.46566 28.3977 9.35903 28.3708 9.26261 28.3169L9.2621 28.3164ZM9.79879 28.1588C9.86511 28.1233 9.91919 28.0704 9.96 27.9992C10.0008 27.9286 10.0212 27.8422 10.0212 27.74C10.0212 27.6379 10.0013 27.552 9.96102 27.4813C9.92072 27.4107 9.86817 27.3578 9.80338 27.3228C9.73859 27.2882 9.66768 27.2709 9.59217 27.2709C9.51667 27.2709 9.44423 27.2882 9.37995 27.3228C9.31567 27.3573 9.26414 27.4102 9.22537 27.4813C9.1866 27.552 9.16721 27.6384 9.16721 27.74C9.16721 27.8417 9.1866 27.9306 9.22435 28.0013C9.26261 28.0719 9.31312 28.1248 9.37638 28.1599C9.43964 28.1944 9.50953 28.2117 9.58503 28.2117C9.66054 28.2117 9.73196 28.1939 9.79879 28.1583V28.1588Z" fill="#03045E"/>
                                <path d="M11.5114 27.2262C11.6073 27.3197 11.6553 27.4539 11.6553 27.6297V28.3764H11.4461V27.6597C11.4461 27.5332 11.4145 27.4366 11.3507 27.3695C11.2874 27.3024 11.2007 27.2689 11.091 27.2689C10.9813 27.2689 10.891 27.3035 10.8252 27.3731C10.7594 27.4422 10.7268 27.5433 10.7268 27.676V28.3764H10.5156V27.1093H10.7268V27.2897C10.7686 27.2252 10.8252 27.1749 10.8977 27.1393C10.9696 27.1037 11.0487 27.0859 11.1354 27.0859C11.2905 27.0859 11.4155 27.1327 11.5114 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M12.997 27.2262C13.0929 27.3197 13.1408 27.4539 13.1408 27.6297V28.3764H12.9317V27.6597C12.9317 27.5332 12.9 27.4366 12.8363 27.3695C12.773 27.3024 12.6863 27.2689 12.5766 27.2689C12.4669 27.2689 12.3766 27.3035 12.3108 27.3731C12.245 27.4422 12.2123 27.5433 12.2123 27.676V28.3764H12.0011V27.1093H12.2123V27.2897C12.2542 27.2252 12.3108 27.1749 12.3832 27.1393C12.4552 27.1037 12.5343 27.0859 12.621 27.0859C12.7761 27.0859 12.9011 27.1327 12.997 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M14.6397 27.8214H13.6229C13.6306 27.9464 13.6739 28.044 13.752 28.1141C13.8301 28.1843 13.9249 28.2193 14.0362 28.2193C14.1275 28.2193 14.2035 28.198 14.2647 28.1558C14.3254 28.1136 14.3688 28.0567 14.3933 27.986H14.6208C14.5866 28.108 14.5188 28.2066 14.4167 28.2834C14.3147 28.3596 14.1877 28.3977 14.0362 28.3977C13.9158 28.3977 13.8076 28.3708 13.7122 28.3169C13.6168 28.263 13.5423 28.1863 13.4882 28.0867C13.4342 27.987 13.4071 27.8722 13.4071 27.7411C13.4071 27.6099 13.4337 27.4951 13.4862 27.3965C13.5388 27.2979 13.6127 27.2216 13.7076 27.1688C13.8025 27.1159 13.9122 27.089 14.0362 27.089C14.1601 27.089 14.2637 27.1154 14.3565 27.1677C14.4494 27.2201 14.5208 27.2923 14.5713 27.3838C14.6218 27.4757 14.6468 27.5794 14.6468 27.6948C14.6468 27.735 14.6443 27.7771 14.6397 27.8219V27.8214ZM14.3754 27.4437C14.3397 27.3858 14.2917 27.3421 14.2305 27.3121C14.1698 27.2821 14.102 27.2669 14.0275 27.2669C13.9209 27.2669 13.83 27.3009 13.7545 27.3685C13.679 27.4361 13.6367 27.5301 13.626 27.6506H14.429C14.429 27.5708 14.4111 27.5017 14.3754 27.4437Z" fill="#03045E"/>
                                <path d="M14.9254 27.3975C14.9779 27.2994 15.0509 27.2237 15.1447 27.1698C15.2381 27.1159 15.3452 27.089 15.4661 27.089C15.6222 27.089 15.7513 27.1271 15.8523 27.2023C15.9538 27.278 16.0207 27.3827 16.0533 27.5164H15.8258C15.8044 27.4392 15.762 27.3787 15.6993 27.3339C15.6365 27.2892 15.559 27.2669 15.4661 27.2669C15.3457 27.2669 15.2483 27.308 15.1738 27.3904C15.0993 27.4727 15.0621 27.5896 15.0621 27.7405C15.0621 27.8915 15.0993 28.0109 15.1738 28.0943C15.2483 28.1776 15.3457 28.2193 15.4661 28.2193C15.559 28.2193 15.6365 28.198 15.6982 28.1548C15.76 28.1116 15.8023 28.0501 15.8258 27.9698H16.0533C16.0191 28.0994 15.9513 28.2031 15.8493 28.2808C15.7472 28.3586 15.6192 28.3977 15.4661 28.3977C15.3457 28.3977 15.2381 28.3708 15.1447 28.3169C15.0509 28.263 14.9779 28.1868 14.9254 28.0882C14.8728 27.9896 14.8463 27.8737 14.8463 27.7416C14.8463 27.6094 14.8728 27.4961 14.9254 27.3985V27.3975Z" fill="#03045E"/>
                                <path d="M16.5915 27.2826V28.0292C16.5915 28.0907 16.6048 28.1344 16.6308 28.1599C16.6573 28.1853 16.7027 28.198 16.768 28.198H16.9236V28.3759H16.7334C16.6155 28.3759 16.5278 28.3489 16.4686 28.2951C16.4099 28.2412 16.3803 28.1522 16.3803 28.0292V27.2826H16.2155V27.1093H16.3803V26.7901H16.5915V27.1093H16.9231V27.2826H16.5915Z" fill="#03045E"/>
                                <path d="M17.1859 26.8618C17.1583 26.8338 17.144 26.8003 17.144 26.7601C17.144 26.72 17.1583 26.6859 17.1859 26.6585C17.2139 26.6305 17.2476 26.6168 17.2879 26.6168C17.3282 26.6168 17.3593 26.6305 17.3864 26.6585C17.4134 26.6864 17.4272 26.72 17.4272 26.7601C17.4272 26.8003 17.4134 26.8343 17.3864 26.8618C17.3593 26.8897 17.3267 26.9035 17.2879 26.9035C17.2491 26.9035 17.2134 26.8897 17.1859 26.8618ZM17.3899 27.1093V28.3764H17.1787V27.1093H17.3899Z" fill="#03045E"/>
                                <path d="M18.7454 27.2262C18.8413 27.3197 18.8893 27.4539 18.8893 27.6297V28.3764H18.6801V27.6597C18.6801 27.5332 18.6485 27.4366 18.5847 27.3695C18.5215 27.3024 18.4347 27.2689 18.325 27.2689C18.2154 27.2689 18.1251 27.3035 18.0593 27.3731C17.9934 27.4422 17.9608 27.5433 17.9608 27.676V28.3764H17.7496V27.1093H17.9608V27.2897C18.0026 27.2252 18.0593 27.1749 18.1317 27.1393C18.2036 27.1037 18.2827 27.0859 18.3694 27.0859C18.5245 27.0859 18.6495 27.1327 18.7454 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M20.0484 27.1601C20.131 27.2079 20.1927 27.2679 20.233 27.3406V27.1093H20.4463V28.4038C20.4463 28.5197 20.4218 28.6224 20.3718 28.7123C20.3223 28.8023 20.2519 28.8729 20.1596 28.9238C20.0677 28.9746 19.9606 29 19.8382 29C19.6708 29 19.5316 28.9609 19.4204 28.8821C19.3091 28.8033 19.2433 28.6961 19.2234 28.5609H19.4321C19.455 28.6381 19.5035 28.6996 19.5759 28.7469C19.6489 28.7942 19.7361 28.8175 19.8382 28.8175C19.9545 28.8175 20.0489 28.7814 20.1223 28.7088C20.1958 28.6361 20.2325 28.5344 20.2325 28.4038V28.138C20.1907 28.2117 20.129 28.2737 20.0468 28.323C19.9652 28.3723 19.8693 28.3972 19.7591 28.3972C19.6489 28.3972 19.5438 28.3693 19.4515 28.3139C19.3596 28.2585 19.2872 28.1807 19.2347 28.0806C19.1821 27.9804 19.1556 27.8661 19.1556 27.7385C19.1556 27.6109 19.1821 27.4956 19.2347 27.3975C19.2872 27.2994 19.3596 27.2237 19.4515 27.1698C19.5438 27.1159 19.6464 27.089 19.7591 27.089C19.8718 27.089 19.9652 27.1129 20.0484 27.1606V27.1601ZM20.1749 27.491C20.1361 27.4198 20.0841 27.3655 20.0183 27.3278C19.9524 27.2902 19.8805 27.2714 19.8014 27.2714C19.7224 27.2714 19.6504 27.2897 19.5856 27.3268C19.5209 27.3639 19.4688 27.4178 19.43 27.4885C19.3913 27.5591 19.3719 27.6425 19.3719 27.738C19.3719 27.8336 19.3913 27.9195 19.43 27.9911C19.4688 28.0628 19.5203 28.1177 19.5856 28.1553C19.6509 28.1929 19.7229 28.2117 19.8014 28.2117C19.88 28.2117 19.953 28.1929 20.0183 28.1553C20.0836 28.1177 20.1361 28.0628 20.1749 27.9911C20.2136 27.9195 20.233 27.8356 20.233 27.74C20.233 27.6445 20.2136 27.5611 20.1749 27.4905V27.491Z" fill="#03045E"/>
                                <path d="M22.4191 26.7647V26.936H21.9783V28.3764H21.7671V26.936H21.3238V26.7647H22.4191Z" fill="#03045E"/>
                                <path d="M23.57 27.1474C23.6475 27.1881 23.7077 27.2496 23.7521 27.3314C23.7965 27.4132 23.8184 27.5123 23.8184 27.6297V28.3764H23.6093V27.6597C23.6093 27.5332 23.5776 27.4366 23.5139 27.3695C23.4506 27.3024 23.3639 27.2689 23.2542 27.2689C23.1445 27.2689 23.0542 27.3035 22.9884 27.3731C22.9226 27.4422 22.8899 27.5433 22.8899 27.676V28.3764H22.6787V26.6656H22.8899V27.2897C22.9318 27.2252 22.9894 27.1749 23.0629 27.1393C23.1363 27.1037 23.219 27.0859 23.3098 27.0859C23.4057 27.0859 23.4924 27.1062 23.57 27.1469V27.1474Z" fill="#03045E"/>
                                <path d="M25.3178 27.8214H24.301C24.3087 27.9464 24.352 28.044 24.4301 28.1141C24.5081 28.1843 24.603 28.2193 24.7143 28.2193C24.8056 28.2193 24.8816 28.198 24.9428 28.1558C25.0035 28.1136 25.0469 28.0567 25.0714 27.986H25.2989C25.2647 28.108 25.1969 28.2066 25.0948 28.2834C24.9928 28.3596 24.8658 28.3977 24.7143 28.3977C24.5939 28.3977 24.4857 28.3708 24.3903 28.3169C24.2949 28.263 24.2204 28.1863 24.1663 28.0867C24.1123 27.987 24.0852 27.8722 24.0852 27.7411C24.0852 27.6099 24.1118 27.4951 24.1643 27.3965C24.2169 27.2979 24.2908 27.2216 24.3857 27.1688C24.4806 27.1159 24.5903 27.089 24.7143 27.089C24.8382 27.089 24.9418 27.1154 25.0346 27.1677C25.1275 27.2201 25.1989 27.2923 25.2494 27.3838C25.2999 27.4757 25.3249 27.5794 25.3249 27.6948C25.3249 27.735 25.3224 27.7771 25.3178 27.8219V27.8214ZM25.053 27.4437C25.0173 27.3858 24.9693 27.3421 24.9081 27.3121C24.8474 27.2821 24.7796 27.2669 24.7051 27.2669C24.5984 27.2669 24.5076 27.3009 24.4321 27.3685C24.3566 27.4361 24.3143 27.5301 24.3036 27.6506H25.1066C25.1066 27.5708 25.0887 27.5017 25.053 27.4437Z" fill="#03045E"/>
                                <path d="M26.4289 26.7647V27.7843C26.4289 27.9276 26.4641 28.0338 26.5345 28.1034C26.6049 28.1726 26.7028 28.2076 26.8283 28.2076C26.9538 28.2076 27.0492 28.1731 27.1196 28.1034C27.19 28.0343 27.2252 27.9276 27.2252 27.7843V26.7647H27.4365V27.7822C27.4365 27.9164 27.4094 28.0292 27.3553 28.1207C27.3013 28.2122 27.2283 28.2808 27.136 28.3255C27.0441 28.3703 26.9406 28.3926 26.8263 28.3926C26.712 28.3926 26.6085 28.3703 26.5161 28.3255C26.4243 28.2808 26.3513 28.2127 26.2983 28.1207C26.2452 28.0287 26.2182 27.9159 26.2182 27.7822V26.7647H26.4294H26.4289Z" fill="#03045E"/>
                                <path d="M28.7843 27.2262C28.8802 27.3197 28.9282 27.4539 28.9282 27.6297V28.3764H28.719V27.6597C28.719 27.5332 28.6874 27.4366 28.6236 27.3695C28.5603 27.3024 28.4736 27.2689 28.3639 27.2689C28.2542 27.2689 28.1639 27.3035 28.0981 27.3731C28.0323 27.4422 27.9997 27.5433 27.9997 27.676V28.3764H27.7885V27.1093H27.9997V27.2897C28.0415 27.2252 28.0981 27.1749 28.1706 27.1393C28.2425 27.1037 28.3216 27.0859 28.4083 27.0859C28.5634 27.0859 28.6884 27.1327 28.7843 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M29.274 27.3975C29.3266 27.2994 29.3995 27.2237 29.4934 27.1698C29.5868 27.1159 29.6939 27.089 29.8148 27.089C29.9709 27.089 30.1 27.1271 30.201 27.2023C30.3025 27.278 30.3693 27.3827 30.402 27.5164H30.1745C30.153 27.4392 30.1107 27.3787 30.0479 27.3339C29.9852 27.2892 29.9077 27.2669 29.8148 27.2669C29.6944 27.2669 29.597 27.308 29.5225 27.3904C29.448 27.4727 29.4108 27.5896 29.4108 27.7405C29.4108 27.8915 29.448 28.0109 29.5225 28.0943C29.597 28.1776 29.6944 28.2193 29.8148 28.2193C29.9077 28.2193 29.9852 28.198 30.0469 28.1548C30.1087 28.1116 30.151 28.0501 30.1745 27.9698H30.402C30.3678 28.0994 30.3 28.2031 30.1979 28.2808C30.0959 28.3586 29.9679 28.3977 29.8148 28.3977C29.6944 28.3977 29.5868 28.3708 29.4934 28.3169C29.3995 28.263 29.3266 28.1868 29.274 28.0882C29.2215 27.9896 29.195 27.8737 29.195 27.7416C29.195 27.6094 29.2215 27.4961 29.274 27.3985V27.3975Z" fill="#03045E"/>
                                <path d="M30.9137 28.3164C30.8168 28.2625 30.7407 28.1858 30.6862 28.0862C30.6311 27.9865 30.604 27.8717 30.604 27.7405C30.604 27.6094 30.6321 27.4966 30.6887 27.3975C30.7453 27.2979 30.8224 27.2216 30.9198 27.1688C31.0172 27.1159 31.1264 27.089 31.2468 27.089C31.3672 27.089 31.4769 27.1154 31.5743 27.1688C31.6718 27.2221 31.7488 27.2979 31.8054 27.3965C31.8621 27.4951 31.8901 27.6099 31.8901 27.7411C31.8901 27.8722 31.861 27.987 31.8029 28.0867C31.7447 28.1858 31.6662 28.263 31.5672 28.3169C31.4682 28.3708 31.358 28.3977 31.2376 28.3977C31.1172 28.3977 31.0106 28.3708 30.9142 28.3169L30.9137 28.3164ZM31.4509 28.1588C31.5172 28.1233 31.5713 28.0704 31.6121 27.9992C31.6529 27.9286 31.6733 27.8422 31.6733 27.74C31.6733 27.6379 31.6534 27.552 31.6131 27.4813C31.5728 27.4107 31.5203 27.3578 31.4555 27.3228C31.3907 27.2882 31.3198 27.2709 31.2443 27.2709C31.1688 27.2709 31.0963 27.2882 31.032 27.3228C30.9678 27.3573 30.9162 27.4102 30.8775 27.4813C30.8387 27.552 30.8193 27.6384 30.8193 27.74C30.8193 27.8417 30.8387 27.9306 30.8764 28.0013C30.9147 28.0719 30.9652 28.1248 31.0285 28.1599C31.0917 28.1944 31.1616 28.2117 31.2371 28.2117C31.3126 28.2117 31.384 28.1939 31.4509 28.1583V28.1588Z" fill="#03045E"/>
                                <path d="M33.1635 27.2262C33.2594 27.3197 33.3073 27.4539 33.3073 27.6297V28.3764H33.0982V27.6597C33.0982 27.5332 33.0665 27.4366 33.0028 27.3695C32.9395 27.3024 32.8528 27.2689 32.7431 27.2689C32.6334 27.2689 32.5431 27.3035 32.4773 27.3731C32.4115 27.4422 32.3789 27.5433 32.3789 27.676V28.3764H32.1676V27.1093H32.3789V27.2897C32.4207 27.2252 32.4773 27.1749 32.5498 27.1393C32.6217 27.1037 32.7008 27.0859 32.7875 27.0859C32.9426 27.0859 33.0676 27.1327 33.1635 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M34.6491 27.2262C34.745 27.3197 34.7929 27.4539 34.7929 27.6297V28.3764H34.5838V27.6597C34.5838 27.5332 34.5521 27.4366 34.4884 27.3695C34.4251 27.3024 34.3384 27.2689 34.2287 27.2689C34.119 27.2689 34.0287 27.3035 33.9629 27.3731C33.8971 27.4422 33.8644 27.5433 33.8644 27.676V28.3764H33.6532V27.1093H33.8644V27.2897C33.9063 27.2252 33.9629 27.1749 34.0353 27.1393C34.1073 27.1037 34.1863 27.0859 34.2731 27.0859C34.4282 27.0859 34.5531 27.1327 34.6491 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M36.2923 27.8214H35.2755C35.2832 27.9464 35.3265 28.044 35.4046 28.1141C35.4827 28.1843 35.5775 28.2193 35.6888 28.2193C35.7801 28.2193 35.8561 28.198 35.9173 28.1558C35.978 28.1136 36.0214 28.0567 36.0459 27.986H36.2734C36.2392 28.108 36.1714 28.2066 36.0693 28.2834C35.9673 28.3596 35.8403 28.3977 35.6888 28.3977C35.5684 28.3977 35.4602 28.3708 35.3648 28.3169C35.2694 28.263 35.1949 28.1863 35.1408 28.0867C35.0868 27.987 35.0597 27.8722 35.0597 27.7411C35.0597 27.6099 35.0863 27.4951 35.1388 27.3965C35.1914 27.2979 35.2653 27.2216 35.3602 27.1688C35.4551 27.1159 35.5648 27.089 35.6888 27.089C35.8127 27.089 35.9163 27.1154 36.0091 27.1677C36.102 27.2201 36.1734 27.2923 36.2239 27.3838C36.2744 27.4757 36.2994 27.5794 36.2994 27.6948C36.2994 27.735 36.2969 27.7771 36.2923 27.8219V27.8214ZM36.0275 27.4437C35.9918 27.3858 35.9438 27.3421 35.8826 27.3121C35.8219 27.2821 35.7541 27.2669 35.6796 27.2669C35.573 27.2669 35.4821 27.3009 35.4066 27.3685C35.3311 27.4361 35.2888 27.5301 35.2781 27.6506H36.0811C36.0811 27.5708 36.0632 27.5017 36.0275 27.4437Z" fill="#03045E"/>
                                <path d="M36.5774 27.3975C36.63 27.2994 36.7029 27.2237 36.7968 27.1698C36.8902 27.1159 36.9973 27.089 37.1182 27.089C37.2743 27.089 37.4034 27.1271 37.5044 27.2023C37.6059 27.278 37.6728 27.3827 37.7054 27.5164H37.4779C37.4564 27.4392 37.4141 27.3787 37.3514 27.3339C37.2886 27.2892 37.2111 27.2669 37.1182 27.2669C36.9978 27.2669 36.9004 27.308 36.8259 27.3904C36.7514 27.4727 36.7142 27.5896 36.7142 27.7405C36.7142 27.8915 36.7514 28.0109 36.8259 28.0943C36.9004 28.1776 36.9978 28.2193 37.1182 28.2193C37.2111 28.2193 37.2886 28.198 37.3503 28.1548C37.4121 28.1116 37.4544 28.0501 37.4779 27.9698H37.7054C37.6712 28.0994 37.6034 28.2031 37.5013 28.2808C37.3993 28.3586 37.2713 28.3977 37.1182 28.3977C36.9978 28.3977 36.8902 28.3708 36.7968 28.3169C36.7029 28.263 36.63 28.1868 36.5774 28.0882C36.5249 27.9896 36.4984 27.8737 36.4984 27.7416C36.4984 27.6094 36.5249 27.4961 36.5774 27.3985V27.3975Z" fill="#03045E"/>
                                <path d="M38.2441 27.2826V28.0292C38.2441 28.0907 38.2574 28.1344 38.2834 28.1599C38.3099 28.1853 38.3554 28.198 38.4207 28.198H38.5762V28.3759H38.386C38.2681 28.3759 38.1804 28.3489 38.1212 28.2951C38.0625 28.2412 38.0329 28.1522 38.0329 28.0292V27.2826H37.8682V27.1093H38.0329V26.7901H38.2441V27.1093H38.5757V27.2826H38.2441Z" fill="#03045E"/>
                                <path d="M39.9843 27.8214H38.9675C38.9752 27.9464 39.0186 28.044 39.0966 28.1141C39.1747 28.1843 39.2696 28.2193 39.3808 28.2193C39.4721 28.2193 39.5481 28.198 39.6093 28.1558C39.67 28.1136 39.7134 28.0567 39.7379 27.986H39.9654C39.9312 28.108 39.8634 28.2066 39.7613 28.2834C39.6593 28.3596 39.5323 28.3977 39.3808 28.3977C39.2604 28.3977 39.1522 28.3708 39.0568 28.3169C38.9614 28.263 38.8869 28.1863 38.8329 28.0867C38.7788 27.987 38.7517 27.8722 38.7517 27.7411C38.7517 27.6099 38.7783 27.4951 38.8308 27.3965C38.8834 27.2979 38.9573 27.2216 39.0522 27.1688C39.1471 27.1159 39.2568 27.089 39.3808 27.089C39.5047 27.089 39.6083 27.1154 39.7011 27.1677C39.794 27.2201 39.8654 27.2923 39.9159 27.3838C39.9664 27.4757 39.9914 27.5794 39.9914 27.6948C39.9914 27.735 39.9889 27.7771 39.9843 27.8219V27.8214ZM39.7195 27.4437C39.6838 27.3858 39.6358 27.3421 39.5746 27.3121C39.5139 27.2821 39.4461 27.2669 39.3716 27.2669C39.265 27.2669 39.1742 27.3009 39.0987 27.3685C39.0231 27.4361 38.9808 27.5301 38.9701 27.6506H39.7731C39.7731 27.5708 39.7552 27.5017 39.7195 27.4437Z" fill="#03045E"/>
                                <path d="M40.27 27.3975C40.3225 27.2994 40.395 27.2237 40.4868 27.1698C40.5791 27.1159 40.6822 27.089 40.797 27.089C40.8959 27.089 40.9878 27.1118 41.073 27.1571C41.1582 27.2028 41.2229 27.2623 41.2683 27.3365V26.6661H41.4816V28.3769H41.2683V28.139C41.2265 28.2147 41.1648 28.2762 41.0826 28.325C41.001 28.3738 40.9051 28.3977 40.7949 28.3977C40.6847 28.3977 40.5796 28.3698 40.4873 28.3144C40.3955 28.259 40.323 28.1812 40.2705 28.0811C40.2179 27.981 40.1914 27.8666 40.1914 27.739C40.1914 27.6115 40.2179 27.4961 40.2705 27.398L40.27 27.3975ZM41.2097 27.491C41.1709 27.4198 41.1189 27.3655 41.0531 27.3278C40.9872 27.2902 40.9153 27.2714 40.8362 27.2714C40.7572 27.2714 40.6852 27.2897 40.6204 27.3268C40.5557 27.3639 40.5036 27.4178 40.4648 27.4885C40.4261 27.5591 40.4067 27.6425 40.4067 27.738C40.4067 27.8336 40.4261 27.9195 40.4648 27.9911C40.5036 28.0628 40.5551 28.1177 40.6204 28.1553C40.6857 28.1929 40.7577 28.2117 40.8362 28.2117C40.9148 28.2117 40.9878 28.1929 41.0531 28.1553C41.1184 28.1177 41.1709 28.0628 41.2097 27.9911C41.2484 27.9195 41.2678 27.8356 41.2678 27.74C41.2678 27.6445 41.2484 27.5611 41.2097 27.4905V27.491Z" fill="#03045E"/>
                        </svg>
                        <span class="content">
                            <span>Short Term Programs</span>
                            <span class="duration">4-6 Weeks</span>
                        </span>
                    </a>
                </div>
            </div>
            
            <div class="nsh-ach-right sorttermcoursetb d-none">
                <div style="width: 100%; display: flex; flex-wrap: wrap; gap: 1rem;">
                    <a href="http://localhost/TIA/summer-training-program-in-noida">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="29" viewBox="0 0 48 29" fill="none">
                                <path d="M20.3044 5.93391C20.5931 5.53086 20.8533 5.12833 21.1523 4.75578C23.1934 2.21502 25.835 0.672469 29.0587 0.173872C32.7083 -0.390797 36.0443 0.414276 38.9761 2.67143C41.4075 4.54282 42.8967 7.01039 43.4186 10.035C43.6425 11.3321 43.617 12.6337 43.3696 13.9262C43.3308 14.1285 43.3885 14.1915 43.5548 14.2657C45.2684 15.0342 46.5612 16.2438 47.3478 17.9511C48.8717 21.2572 47.6498 25.1225 44.4685 27.0386C43.9624 27.3436 43.3977 27.555 42.8518 27.7903C42.6003 27.8986 42.4391 27.8269 42.3697 27.6353C42.2978 27.4356 42.3947 27.2999 42.6646 27.2023C45.0241 26.3484 46.6198 24.7652 47.2075 22.3078C47.832 19.6969 46.7234 17.0032 44.4501 15.4835C43.9716 15.1633 43.4313 14.9336 42.913 14.6743C42.6819 14.5585 42.6197 14.4721 42.6758 14.221C43.6961 9.68075 41.7045 5.22642 37.965 2.70192C36.1749 1.4933 34.199 0.774628 32.039 0.667386C27.1742 0.425966 23.3766 2.29837 20.7615 6.43708C20.6064 6.68206 20.4722 6.72678 20.1983 6.58651C18.7535 5.84801 17.2154 5.55526 15.6053 5.68334C13.6387 5.83988 11.9128 6.5799 10.4701 7.92931C9.11613 9.19486 8.30396 10.7496 8.05449 12.5844C8.00092 12.9803 8.00603 13.3849 7.99582 13.7859C7.98715 14.1219 7.91114 14.1966 7.56882 14.2083C5.02977 14.2967 3.007 15.3427 1.61835 17.4814C0.0123727 19.9561 0.390909 23.2923 2.46674 25.3985C3.37278 26.3179 4.43901 26.9593 5.6843 27.3065C5.71389 27.3146 5.74246 27.3278 5.77256 27.3354C5.96999 27.3863 6.0608 27.5143 6.02815 27.7121C5.99856 27.8925 5.82255 27.9992 5.61135 27.9255C5.0864 27.7431 4.54461 27.5835 4.05486 27.3283C1.9474 26.23 0.573035 24.539 0.135321 22.1909C-0.323821 19.7279 0.39397 17.5887 2.1586 15.8194C3.51817 14.4558 5.19455 13.7432 7.11886 13.6096C7.25405 13.6004 7.3668 13.6055 7.37547 13.4047C7.51934 10.1046 9.08858 7.6574 11.9807 6.10875C14.5799 4.71715 17.2883 4.68259 20.0126 5.80532C20.1115 5.84598 20.2085 5.89121 20.3049 5.9334L20.3044 5.93391Z" fill="#03045E"/>
                                <path d="M10.5676 23.8793C12.3516 23.8555 14.1351 23.8351 15.9191 23.8072C17.6639 23.7802 19.4081 23.7472 21.1528 23.7152C22.7808 23.6852 24.4092 23.6547 26.0371 23.6222C27.541 23.5927 29.045 23.5622 30.5489 23.5292C31.8743 23.5002 33.2002 23.4727 34.5256 23.4351C35.6102 23.4041 36.6948 23.3619 37.7789 23.3187C38.1462 23.304 38.361 22.9533 38.2268 22.6001C37.8187 21.5261 37.408 20.4527 36.9988 19.3788C36.9662 19.2934 36.9381 19.2065 36.8943 19.0825C37.2259 19.0825 37.5319 19.0738 37.8365 19.0906C37.8875 19.0931 37.9523 19.1983 37.9763 19.268C38.2288 19.9963 38.4737 20.7272 38.7221 21.4575C38.7415 21.5144 38.767 21.5693 38.8104 21.6751C38.8609 21.5393 38.8976 21.4504 38.9283 21.3594C39.1594 20.6646 39.395 19.9709 39.6159 19.2725C39.6654 19.1165 39.7389 19.0682 39.895 19.0758C40.1419 19.088 40.3899 19.0789 40.6664 19.0789C40.6419 19.1633 40.6271 19.2268 40.6046 19.2878C40.119 20.6128 39.6348 21.9383 39.1451 23.2623C38.863 24.0257 38.4946 24.2819 37.6712 24.2738C35.8408 24.2555 34.0103 24.229 32.1804 24.2046C31.1336 24.1909 30.0867 24.1736 29.0399 24.1589C26.8916 24.1279 24.7438 24.0969 22.5956 24.0659C21.5564 24.0506 20.5172 24.0349 19.4785 24.0196C17.3226 23.9886 15.1672 23.9546 13.0112 23.9281C12.1975 23.9185 11.3833 23.9266 10.5691 23.9266L10.5681 23.8788L10.5676 23.8793Z" fill="#03045E"/>
                                <path d="M36.3111 22.751H35.3827C35.3827 22.38 35.3862 22.0257 35.3816 21.6715C35.3755 21.1932 35.3663 20.715 35.3521 20.2367C35.3454 20.0136 35.2648 19.8276 35.0301 19.7518C34.7837 19.6725 34.57 19.7437 34.3873 19.9109C34.1705 20.1096 34.1251 20.3785 34.12 20.6519C34.1093 21.2466 34.1088 21.8413 34.1057 22.4359C34.1037 22.7683 34.1057 22.7683 33.7604 22.7683C33.6206 22.7683 33.4803 22.7607 33.3415 22.7703C33.1849 22.781 33.1436 22.7124 33.1451 22.566C33.1517 22.0329 33.1502 21.4997 33.1461 20.9671C33.1446 20.7434 33.1354 20.5198 33.1206 20.2967C33.0972 19.9465 32.9829 19.7793 32.7462 19.7305C32.4498 19.6695 32.0865 19.8159 31.9922 20.1C31.9013 20.3724 31.8646 20.6707 31.8534 20.9594C31.8325 21.4916 31.8478 22.0252 31.8463 22.5584C31.8463 22.6184 31.836 22.6783 31.8279 22.7663C31.5488 22.7663 31.279 22.7719 31.0096 22.7602C30.9678 22.7581 30.8953 22.6748 30.8953 22.629C30.8917 21.4707 30.8963 20.3119 30.9004 19.1536C30.9004 19.1399 30.9127 19.1257 30.9264 19.0957H31.7779C31.7845 19.2365 31.7906 19.365 31.7988 19.5399C32.1304 19.2151 32.4727 18.992 32.9308 18.9905C33.3895 18.9894 33.7415 19.1607 33.9925 19.5572C34.1037 19.4581 34.1955 19.3671 34.2965 19.2873C34.7087 18.961 35.1715 18.9096 35.6561 19.0758C36.0418 19.2085 36.2943 19.4952 36.3045 19.8997C36.328 20.8405 36.3122 21.7818 36.3122 22.75L36.3111 22.751Z" fill="#03045E"/>
                                <path d="M8.89734 22.7683C9.12079 22.1975 9.32587 21.6751 9.52994 21.1521C9.96255 20.0466 10.3977 18.9422 10.8242 17.8347C10.8773 17.6975 10.941 17.6431 11.091 17.6512C11.3308 17.6644 11.5726 17.6436 11.8114 17.6614C11.8894 17.667 12.0042 17.7305 12.0312 17.7961C12.6893 19.4098 13.3383 21.0275 13.9877 22.6443C13.9959 22.6651 13.9953 22.6895 14.0071 22.7683C13.7581 22.7683 13.5143 22.7485 13.2755 22.7734C13.0061 22.8018 12.8653 22.7119 12.7949 22.4481C12.7317 22.2113 12.6169 21.9886 12.5343 21.7564C12.4929 21.64 12.4343 21.5831 12.3006 21.5841C11.7267 21.5902 11.1527 21.5897 10.5788 21.5841C10.4452 21.5826 10.388 21.64 10.3477 21.7589C10.2544 22.0364 10.1416 22.3073 10.0503 22.5853C10.008 22.7139 9.94775 22.7754 9.80389 22.7703C9.51871 22.7612 9.23302 22.7678 8.89734 22.7678V22.7683ZM12.1425 20.7475C11.9032 20.1071 11.6772 19.5023 11.4512 18.898C11.4333 18.9 11.4155 18.902 11.3976 18.9041C11.1762 19.5109 10.9553 20.1183 10.7263 20.7475H12.1425Z" fill="#03045E"/>
                                <path d="M33.4303 14.5153C33.4303 15.79 32.5584 16.6733 31.3004 16.6728C29.9806 16.6723 29.1557 15.8357 29.1547 14.4975C29.1541 13.2452 30.0332 12.3684 31.2912 12.3664C32.5523 12.3644 33.4303 13.2467 33.4303 14.5158V14.5153ZM32.36 14.5153C32.3008 14.284 32.2697 14.035 32.1743 13.8144C32.0141 13.4439 31.7034 13.2416 31.2963 13.2421C30.879 13.2426 30.5673 13.4546 30.4025 13.8317C30.2066 14.281 30.203 14.7506 30.3984 15.2009C30.5607 15.575 30.855 15.7951 31.2825 15.7996C31.7192 15.8042 32.0243 15.5907 32.187 15.2019C32.2743 14.9936 32.3008 14.7598 32.36 14.5148V14.5153Z" fill="#03045E"/>
                                <path d="M25.0918 22.2499C25.0076 22.3221 24.9729 22.348 24.9423 22.3785C24.2638 23.0616 23.2659 22.9828 22.7134 22.2016C22.2487 21.5454 22.2359 20.285 22.6869 19.6227C23.1664 18.9188 24.1521 18.7765 24.8117 19.3157C24.8632 19.3579 24.9153 19.4001 25.003 19.4713V17.6695H25.9621V22.7658C25.7065 22.7658 25.4601 22.7719 25.2147 22.7597C25.1719 22.7576 25.1066 22.6829 25.0979 22.6341C25.0795 22.5304 25.0923 22.4217 25.0923 22.2494L25.0918 22.2499ZM23.3256 20.8674C23.3343 20.87 23.343 20.8725 23.3516 20.8746C23.3598 21.0052 23.3608 21.1368 23.3787 21.2664C23.3924 21.3645 23.42 21.4626 23.4542 21.5561C23.5725 21.8763 23.8531 22.0817 24.1725 22.0908C24.4622 22.0989 24.7775 21.8966 24.8949 21.5932C25.0709 21.1373 25.0668 20.6687 24.8974 20.2138C24.779 19.8962 24.5286 19.7208 24.1811 19.7223C23.8337 19.7244 23.5868 19.9068 23.4659 20.2189C23.3868 20.4227 23.37 20.6499 23.3256 20.8669V20.8674Z" fill="#03045E"/>
                                <path d="M17.7062 16.5885H16.7834V11.5191H17.7231V13.3631C17.8231 13.2782 17.8771 13.2319 17.9312 13.1862C18.3807 12.8076 18.8857 12.7313 19.4326 12.9265C19.8086 13.0607 20.0647 13.3412 20.0861 13.7275C20.1397 14.6734 20.1438 15.6218 20.1682 16.5859H19.176C19.176 16.4187 19.1775 16.2662 19.176 16.1137C19.1683 15.4652 19.1688 14.8167 19.1474 14.1687C19.1352 13.8012 18.9837 13.6279 18.6852 13.5679C18.372 13.5049 17.9919 13.6548 17.8817 13.9425C17.7904 14.1814 17.744 14.4487 17.7348 14.7059C17.7134 15.3077 17.7261 15.911 17.7246 16.5137C17.7246 16.5285 17.7174 16.5437 17.7067 16.5879L17.7062 16.5885Z" fill="#03045E"/>
                                <path d="M21.8084 22.7673C21.4865 22.7673 21.2238 22.7734 20.9615 22.7622C20.9105 22.7602 20.8457 22.7002 20.8161 22.6504C20.7707 22.5736 20.7503 22.4822 20.7116 22.3754C20.6335 22.4349 20.5748 22.4806 20.5146 22.5249C20.0871 22.8425 19.6101 22.9127 19.1041 22.781C18.6597 22.6656 18.3679 22.3038 18.3414 21.8672C18.3087 21.332 18.5153 20.9564 18.9959 20.7917C19.3739 20.6621 19.7734 20.5935 20.1631 20.4974C20.2305 20.4807 20.3004 20.4695 20.3647 20.4441C20.4764 20.3998 20.6478 20.4283 20.6417 20.2311C20.635 20.0253 20.5784 19.8499 20.3611 19.7716C19.9035 19.6065 19.5622 19.7325 19.2964 20.1788C19.1602 20.1544 19.0173 20.1284 18.8745 20.103C18.7383 20.0786 18.6021 20.0547 18.4398 20.0263C18.5607 19.5887 18.7842 19.2507 19.2168 19.1104C19.7759 18.929 20.3499 18.9366 20.91 19.1028C21.3692 19.239 21.5676 19.5475 21.5865 20.0944C21.5982 20.4415 21.586 20.7892 21.5895 21.1363C21.5952 21.6725 21.5283 22.2169 21.8084 22.7678V22.7673ZM20.6177 20.9889C20.2677 21.0799 19.9254 21.1465 19.5994 21.2593C19.2724 21.3726 19.2046 21.7416 19.4372 21.9947C19.5928 22.164 19.8693 22.2133 20.1269 22.1177C20.5636 21.9561 20.7044 21.6491 20.6177 20.9889Z" fill="#03045E"/>
                                <path d="M27.7022 21.1922C27.6747 21.644 27.9083 22.0029 28.269 22.0994C28.6629 22.2052 28.8827 22.0822 29.145 21.6024C29.4434 21.6532 29.7454 21.705 30.0821 21.763C29.9265 22.1935 29.6771 22.506 29.2776 22.6885C28.0104 23.2669 26.7146 22.4791 26.6942 21.0972C26.6886 20.7277 26.7401 20.3358 26.8656 19.9902C27.1278 19.268 27.8099 18.9183 28.6476 19.0006C29.3587 19.0708 29.9056 19.5699 30.0597 20.3058C30.1051 20.5239 30.1163 20.749 30.1464 20.9701C30.1699 21.1434 30.0985 21.1978 29.9245 21.1953C29.273 21.1866 28.6215 21.1917 27.9701 21.1917C27.8859 21.1917 27.8022 21.1917 27.7022 21.1917V21.1922ZM27.714 20.5823H29.1674C29.1368 20.0075 28.8246 19.6827 28.367 19.7239C27.9578 19.761 27.6681 20.1366 27.714 20.5823Z" fill="#03045E"/>
                                <path d="M24.3602 15.0393H21.8977C21.8385 15.4327 22.0727 15.8179 22.4226 15.9282C22.82 16.0533 23.0797 15.9186 23.3297 15.4444C23.6261 15.4932 23.9281 15.543 24.2684 15.5989C24.1271 15.9963 23.9092 16.2881 23.5618 16.4756C22.3068 17.1541 20.9172 16.3562 20.8666 14.9265C20.8473 14.3831 20.9223 13.8571 21.2829 13.42C21.7482 12.8563 22.3645 12.7278 23.0481 12.8818C23.7052 13.0297 24.0863 13.4734 24.2347 14.1107C24.3026 14.4009 24.3163 14.7033 24.3597 15.0393H24.3602ZM21.9053 14.4502C22.3481 14.4502 22.7956 14.4538 23.243 14.4446C23.2833 14.4436 23.3542 14.3638 23.3557 14.3186C23.369 13.8927 22.9807 13.5257 22.5639 13.5593C22.1431 13.5933 21.837 13.9923 21.9053 14.4502Z" fill="#03045E"/>
                                <path d="M14.5667 16.5864H13.5515V12.3623H12.0353V11.5201H16.0783V12.3456H14.5667V16.5864Z" fill="#03045E"/>
                                <path d="M35.9515 16.5839H34.9245V12.3572H33.4068C33.4068 12.0955 33.3976 11.852 33.4155 11.6106C33.4186 11.5694 33.5349 11.5064 33.6002 11.5054C34.0348 11.4958 34.4695 11.5003 34.9041 11.5003C35.6648 11.5003 36.4254 11.5003 37.1861 11.5003H37.4559V12.3476H35.952V16.5844L35.9515 16.5839Z" fill="#03045E"/>
                                <path d="M16.9338 21.3594C17.2486 21.4143 17.5506 21.4662 17.8705 21.5221C17.7251 22.1177 17.4348 22.5584 16.84 22.7464C15.9885 23.0148 15.1233 22.7642 14.6968 22.1269C14.2703 21.489 14.2581 20.3277 14.7065 19.7056C14.855 19.4997 15.0641 19.3081 15.2886 19.1922C15.8477 18.903 16.4426 18.9163 17.0211 19.147C17.4557 19.3203 17.6802 19.6873 17.822 20.1472C17.5119 20.2057 17.2109 20.2621 16.8951 20.3216C16.8767 20.2804 16.8583 20.2418 16.8425 20.2021C16.7216 19.8921 16.5043 19.7274 16.1931 19.7376C15.8768 19.7478 15.6059 19.8723 15.4927 20.1828C15.3182 20.6626 15.3274 21.1556 15.511 21.6308C15.6263 21.9292 15.9248 22.0776 16.2676 22.0583C16.564 22.041 16.7553 21.8834 16.868 21.5632C16.8884 21.5058 16.9063 21.4468 16.9344 21.3599L16.9338 21.3594Z" fill="#03045E"/>
                                <path d="M27.1166 14.0279C27.1166 13.2635 27.1217 12.4985 27.113 11.7341C27.111 11.5522 27.1666 11.4841 27.3513 11.4973C27.5441 11.511 27.7395 11.5085 27.9328 11.4978C28.0961 11.4886 28.1481 11.5501 28.1466 11.7113C28.14 12.4528 28.1435 13.1943 28.1435 13.9359C28.1435 14.747 28.1389 15.5577 28.1471 16.3689C28.1491 16.5508 28.0935 16.6189 27.9089 16.6057C27.716 16.592 27.5206 16.5945 27.3273 16.6052C27.164 16.6144 27.112 16.5529 27.1135 16.3918C27.1196 15.604 27.1166 14.8162 27.1166 14.0284V14.0279Z" fill="#03045E"/>
                                <path d="M7.26688 27.1454C7.3383 27.0198 7.43523 26.9212 7.55818 26.8506C7.68112 26.7799 7.81734 26.7444 7.96783 26.7444C8.14435 26.7444 8.29842 26.7866 8.42953 26.8714C8.56115 26.9563 8.65706 27.0763 8.71725 27.2323H8.46422C8.41932 27.1352 8.35453 27.0605 8.27036 27.0082C8.18618 26.9558 8.08517 26.9299 7.96732 26.9299C7.84948 26.9299 7.75306 26.9563 7.66327 27.0082C7.57348 27.0605 7.50308 27.1352 7.45207 27.2313C7.40105 27.3278 7.37503 27.4407 7.37503 27.5698C7.37503 27.6989 7.40054 27.8097 7.45207 27.9062C7.50308 28.0028 7.57348 28.077 7.66327 28.1294C7.75306 28.1817 7.85458 28.2076 7.96732 28.2076C8.08007 28.2076 8.18567 28.1817 8.27036 28.1304C8.35504 28.079 8.41932 28.0043 8.46422 27.9073H8.71725C8.65706 28.0613 8.56115 28.1807 8.42953 28.2646C8.29791 28.3484 8.14435 28.3906 7.96783 28.3906C7.81785 28.3906 7.68112 28.3555 7.55818 28.2854C7.43523 28.2153 7.3383 28.1177 7.26688 27.9926C7.19597 27.8676 7.16026 27.7268 7.16026 27.5698C7.16026 27.4127 7.19597 27.2714 7.26688 27.1454Z" fill="#03045E"/>
                                <path d="M9.2621 28.3164C9.16517 28.2625 9.08916 28.1858 9.03457 28.0862C8.97947 27.9865 8.95244 27.8717 8.95244 27.7405C8.95244 27.6094 8.9805 27.4966 9.03712 27.3975C9.09375 27.2979 9.17078 27.2216 9.26822 27.1688C9.36566 27.1154 9.47484 27.089 9.59523 27.089C9.71563 27.089 9.82532 27.1154 9.92276 27.1688C10.0202 27.2221 10.0972 27.2979 10.1539 27.3965C10.2105 27.4951 10.2385 27.6099 10.2385 27.7411C10.2385 27.8722 10.2095 27.987 10.1513 28.0867C10.0931 28.1858 10.0146 28.263 9.91561 28.3169C9.81664 28.3708 9.70645 28.3977 9.58605 28.3977C9.46566 28.3977 9.35903 28.3708 9.26261 28.3169L9.2621 28.3164ZM9.79879 28.1588C9.86511 28.1233 9.91919 28.0704 9.96 27.9992C10.0008 27.9286 10.0212 27.8422 10.0212 27.74C10.0212 27.6379 10.0013 27.552 9.96102 27.4813C9.92072 27.4107 9.86817 27.3578 9.80338 27.3228C9.73859 27.2882 9.66768 27.2709 9.59217 27.2709C9.51667 27.2709 9.44423 27.2882 9.37995 27.3228C9.31567 27.3573 9.26414 27.4102 9.22537 27.4813C9.1866 27.552 9.16721 27.6384 9.16721 27.74C9.16721 27.8417 9.1866 27.9306 9.22435 28.0013C9.26261 28.0719 9.31312 28.1248 9.37638 28.1599C9.43964 28.1944 9.50953 28.2117 9.58503 28.2117C9.66054 28.2117 9.73196 28.1939 9.79879 28.1583V28.1588Z" fill="#03045E"/>
                                <path d="M11.5114 27.2262C11.6073 27.3197 11.6553 27.4539 11.6553 27.6297V28.3764H11.4461V27.6597C11.4461 27.5332 11.4145 27.4366 11.3507 27.3695C11.2874 27.3024 11.2007 27.2689 11.091 27.2689C10.9813 27.2689 10.891 27.3035 10.8252 27.3731C10.7594 27.4422 10.7268 27.5433 10.7268 27.676V28.3764H10.5156V27.1093H10.7268V27.2897C10.7686 27.2252 10.8252 27.1749 10.8977 27.1393C10.9696 27.1037 11.0487 27.0859 11.1354 27.0859C11.2905 27.0859 11.4155 27.1327 11.5114 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M12.997 27.2262C13.0929 27.3197 13.1408 27.4539 13.1408 27.6297V28.3764H12.9317V27.6597C12.9317 27.5332 12.9 27.4366 12.8363 27.3695C12.773 27.3024 12.6863 27.2689 12.5766 27.2689C12.4669 27.2689 12.3766 27.3035 12.3108 27.3731C12.245 27.4422 12.2123 27.5433 12.2123 27.676V28.3764H12.0011V27.1093H12.2123V27.2897C12.2542 27.2252 12.3108 27.1749 12.3832 27.1393C12.4552 27.1037 12.5343 27.0859 12.621 27.0859C12.7761 27.0859 12.9011 27.1327 12.997 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M14.6397 27.8214H13.6229C13.6306 27.9464 13.6739 28.044 13.752 28.1141C13.8301 28.1843 13.9249 28.2193 14.0362 28.2193C14.1275 28.2193 14.2035 28.198 14.2647 28.1558C14.3254 28.1136 14.3688 28.0567 14.3933 27.986H14.6208C14.5866 28.108 14.5188 28.2066 14.4167 28.2834C14.3147 28.3596 14.1877 28.3977 14.0362 28.3977C13.9158 28.3977 13.8076 28.3708 13.7122 28.3169C13.6168 28.263 13.5423 28.1863 13.4882 28.0867C13.4342 27.987 13.4071 27.8722 13.4071 27.7411C13.4071 27.6099 13.4337 27.4951 13.4862 27.3965C13.5388 27.2979 13.6127 27.2216 13.7076 27.1688C13.8025 27.1159 13.9122 27.089 14.0362 27.089C14.1601 27.089 14.2637 27.1154 14.3565 27.1677C14.4494 27.2201 14.5208 27.2923 14.5713 27.3838C14.6218 27.4757 14.6468 27.5794 14.6468 27.6948C14.6468 27.735 14.6443 27.7771 14.6397 27.8219V27.8214ZM14.3754 27.4437C14.3397 27.3858 14.2917 27.3421 14.2305 27.3121C14.1698 27.2821 14.102 27.2669 14.0275 27.2669C13.9209 27.2669 13.83 27.3009 13.7545 27.3685C13.679 27.4361 13.6367 27.5301 13.626 27.6506H14.429C14.429 27.5708 14.4111 27.5017 14.3754 27.4437Z" fill="#03045E"/>
                                <path d="M14.9254 27.3975C14.9779 27.2994 15.0509 27.2237 15.1447 27.1698C15.2381 27.1159 15.3452 27.089 15.4661 27.089C15.6222 27.089 15.7513 27.1271 15.8523 27.2023C15.9538 27.278 16.0207 27.3827 16.0533 27.5164H15.8258C15.8044 27.4392 15.762 27.3787 15.6993 27.3339C15.6365 27.2892 15.559 27.2669 15.4661 27.2669C15.3457 27.2669 15.2483 27.308 15.1738 27.3904C15.0993 27.4727 15.0621 27.5896 15.0621 27.7405C15.0621 27.8915 15.0993 28.0109 15.1738 28.0943C15.2483 28.1776 15.3457 28.2193 15.4661 28.2193C15.559 28.2193 15.6365 28.198 15.6982 28.1548C15.76 28.1116 15.8023 28.0501 15.8258 27.9698H16.0533C16.0191 28.0994 15.9513 28.2031 15.8493 28.2808C15.7472 28.3586 15.6192 28.3977 15.4661 28.3977C15.3457 28.3977 15.2381 28.3708 15.1447 28.3169C15.0509 28.263 14.9779 28.1868 14.9254 28.0882C14.8728 27.9896 14.8463 27.8737 14.8463 27.7416C14.8463 27.6094 14.8728 27.4961 14.9254 27.3985V27.3975Z" fill="#03045E"/>
                                <path d="M16.5915 27.2826V28.0292C16.5915 28.0907 16.6048 28.1344 16.6308 28.1599C16.6573 28.1853 16.7027 28.198 16.768 28.198H16.9236V28.3759H16.7334C16.6155 28.3759 16.5278 28.3489 16.4686 28.2951C16.4099 28.2412 16.3803 28.1522 16.3803 28.0292V27.2826H16.2155V27.1093H16.3803V26.7901H16.5915V27.1093H16.9231V27.2826H16.5915Z" fill="#03045E"/>
                                <path d="M17.1859 26.8618C17.1583 26.8338 17.144 26.8003 17.144 26.7601C17.144 26.72 17.1583 26.6859 17.1859 26.6585C17.2139 26.6305 17.2476 26.6168 17.2879 26.6168C17.3282 26.6168 17.3593 26.6305 17.3864 26.6585C17.4134 26.6864 17.4272 26.72 17.4272 26.7601C17.4272 26.8003 17.4134 26.8343 17.3864 26.8618C17.3593 26.8897 17.3267 26.9035 17.2879 26.9035C17.2491 26.9035 17.2134 26.8897 17.1859 26.8618ZM17.3899 27.1093V28.3764H17.1787V27.1093H17.3899Z" fill="#03045E"/>
                                <path d="M18.7454 27.2262C18.8413 27.3197 18.8893 27.4539 18.8893 27.6297V28.3764H18.6801V27.6597C18.6801 27.5332 18.6485 27.4366 18.5847 27.3695C18.5215 27.3024 18.4347 27.2689 18.325 27.2689C18.2154 27.2689 18.1251 27.3035 18.0593 27.3731C17.9934 27.4422 17.9608 27.5433 17.9608 27.676V28.3764H17.7496V27.1093H17.9608V27.2897C18.0026 27.2252 18.0593 27.1749 18.1317 27.1393C18.2036 27.1037 18.2827 27.0859 18.3694 27.0859C18.5245 27.0859 18.6495 27.1327 18.7454 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M20.0484 27.1601C20.131 27.2079 20.1927 27.2679 20.233 27.3406V27.1093H20.4463V28.4038C20.4463 28.5197 20.4218 28.6224 20.3718 28.7123C20.3223 28.8023 20.2519 28.8729 20.1596 28.9238C20.0677 28.9746 19.9606 29 19.8382 29C19.6708 29 19.5316 28.9609 19.4204 28.8821C19.3091 28.8033 19.2433 28.6961 19.2234 28.5609H19.4321C19.455 28.6381 19.5035 28.6996 19.5759 28.7469C19.6489 28.7942 19.7361 28.8175 19.8382 28.8175C19.9545 28.8175 20.0489 28.7814 20.1223 28.7088C20.1958 28.6361 20.2325 28.5344 20.2325 28.4038V28.138C20.1907 28.2117 20.129 28.2737 20.0468 28.323C19.9652 28.3723 19.8693 28.3972 19.7591 28.3972C19.6489 28.3972 19.5438 28.3693 19.4515 28.3139C19.3596 28.2585 19.2872 28.1807 19.2347 28.0806C19.1821 27.9804 19.1556 27.8661 19.1556 27.7385C19.1556 27.6109 19.1821 27.4956 19.2347 27.3975C19.2872 27.2994 19.3596 27.2237 19.4515 27.1698C19.5438 27.1159 19.6464 27.089 19.7591 27.089C19.8718 27.089 19.9652 27.1129 20.0484 27.1606V27.1601ZM20.1749 27.491C20.1361 27.4198 20.0841 27.3655 20.0183 27.3278C19.9524 27.2902 19.8805 27.2714 19.8014 27.2714C19.7224 27.2714 19.6504 27.2897 19.5856 27.3268C19.5209 27.3639 19.4688 27.4178 19.43 27.4885C19.3913 27.5591 19.3719 27.6425 19.3719 27.738C19.3719 27.8336 19.3913 27.9195 19.43 27.9911C19.4688 28.0628 19.5203 28.1177 19.5856 28.1553C19.6509 28.1929 19.7229 28.2117 19.8014 28.2117C19.88 28.2117 19.953 28.1929 20.0183 28.1553C20.0836 28.1177 20.1361 28.0628 20.1749 27.9911C20.2136 27.9195 20.233 27.8356 20.233 27.74C20.233 27.6445 20.2136 27.5611 20.1749 27.4905V27.491Z" fill="#03045E"/>
                                <path d="M22.4191 26.7647V26.936H21.9783V28.3764H21.7671V26.936H21.3238V26.7647H22.4191Z" fill="#03045E"/>
                                <path d="M23.57 27.1474C23.6475 27.1881 23.7077 27.2496 23.7521 27.3314C23.7965 27.4132 23.8184 27.5123 23.8184 27.6297V28.3764H23.6093V27.6597C23.6093 27.5332 23.5776 27.4366 23.5139 27.3695C23.4506 27.3024 23.3639 27.2689 23.2542 27.2689C23.1445 27.2689 23.0542 27.3035 22.9884 27.3731C22.9226 27.4422 22.8899 27.5433 22.8899 27.676V28.3764H22.6787V26.6656H22.8899V27.2897C22.9318 27.2252 22.9894 27.1749 23.0629 27.1393C23.1363 27.1037 23.219 27.0859 23.3098 27.0859C23.4057 27.0859 23.4924 27.1062 23.57 27.1469V27.1474Z" fill="#03045E"/>
                                <path d="M25.3178 27.8214H24.301C24.3087 27.9464 24.352 28.044 24.4301 28.1141C24.5081 28.1843 24.603 28.2193 24.7143 28.2193C24.8056 28.2193 24.8816 28.198 24.9428 28.1558C25.0035 28.1136 25.0469 28.0567 25.0714 27.986H25.2989C25.2647 28.108 25.1969 28.2066 25.0948 28.2834C24.9928 28.3596 24.8658 28.3977 24.7143 28.3977C24.5939 28.3977 24.4857 28.3708 24.3903 28.3169C24.2949 28.263 24.2204 28.1863 24.1663 28.0867C24.1123 27.987 24.0852 27.8722 24.0852 27.7411C24.0852 27.6099 24.1118 27.4951 24.1643 27.3965C24.2169 27.2979 24.2908 27.2216 24.3857 27.1688C24.4806 27.1159 24.5903 27.089 24.7143 27.089C24.8382 27.089 24.9418 27.1154 25.0346 27.1677C25.1275 27.2201 25.1989 27.2923 25.2494 27.3838C25.2999 27.4757 25.3249 27.5794 25.3249 27.6948C25.3249 27.735 25.3224 27.7771 25.3178 27.8219V27.8214ZM25.053 27.4437C25.0173 27.3858 24.9693 27.3421 24.9081 27.3121C24.8474 27.2821 24.7796 27.2669 24.7051 27.2669C24.5984 27.2669 24.5076 27.3009 24.4321 27.3685C24.3566 27.4361 24.3143 27.5301 24.3036 27.6506H25.1066C25.1066 27.5708 25.0887 27.5017 25.053 27.4437Z" fill="#03045E"/>
                                <path d="M26.4289 26.7647V27.7843C26.4289 27.9276 26.4641 28.0338 26.5345 28.1034C26.6049 28.1726 26.7028 28.2076 26.8283 28.2076C26.9538 28.2076 27.0492 28.1731 27.1196 28.1034C27.19 28.0343 27.2252 27.9276 27.2252 27.7843V26.7647H27.4365V27.7822C27.4365 27.9164 27.4094 28.0292 27.3553 28.1207C27.3013 28.2122 27.2283 28.2808 27.136 28.3255C27.0441 28.3703 26.9406 28.3926 26.8263 28.3926C26.712 28.3926 26.6085 28.3703 26.5161 28.3255C26.4243 28.2808 26.3513 28.2127 26.2983 28.1207C26.2452 28.0287 26.2182 27.9159 26.2182 27.7822V26.7647H26.4294H26.4289Z" fill="#03045E"/>
                                <path d="M28.7843 27.2262C28.8802 27.3197 28.9282 27.4539 28.9282 27.6297V28.3764H28.719V27.6597C28.719 27.5332 28.6874 27.4366 28.6236 27.3695C28.5603 27.3024 28.4736 27.2689 28.3639 27.2689C28.2542 27.2689 28.1639 27.3035 28.0981 27.3731C28.0323 27.4422 27.9997 27.5433 27.9997 27.676V28.3764H27.7885V27.1093H27.9997V27.2897C28.0415 27.2252 28.0981 27.1749 28.1706 27.1393C28.2425 27.1037 28.3216 27.0859 28.4083 27.0859C28.5634 27.0859 28.6884 27.1327 28.7843 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M29.274 27.3975C29.3266 27.2994 29.3995 27.2237 29.4934 27.1698C29.5868 27.1159 29.6939 27.089 29.8148 27.089C29.9709 27.089 30.1 27.1271 30.201 27.2023C30.3025 27.278 30.3693 27.3827 30.402 27.5164H30.1745C30.153 27.4392 30.1107 27.3787 30.0479 27.3339C29.9852 27.2892 29.9077 27.2669 29.8148 27.2669C29.6944 27.2669 29.597 27.308 29.5225 27.3904C29.448 27.4727 29.4108 27.5896 29.4108 27.7405C29.4108 27.8915 29.448 28.0109 29.5225 28.0943C29.597 28.1776 29.6944 28.2193 29.8148 28.2193C29.9077 28.2193 29.9852 28.198 30.0469 28.1548C30.1087 28.1116 30.151 28.0501 30.1745 27.9698H30.402C30.3678 28.0994 30.3 28.2031 30.1979 28.2808C30.0959 28.3586 29.9679 28.3977 29.8148 28.3977C29.6944 28.3977 29.5868 28.3708 29.4934 28.3169C29.3995 28.263 29.3266 28.1868 29.274 28.0882C29.2215 27.9896 29.195 27.8737 29.195 27.7416C29.195 27.6094 29.2215 27.4961 29.274 27.3985V27.3975Z" fill="#03045E"/>
                                <path d="M30.9137 28.3164C30.8168 28.2625 30.7407 28.1858 30.6862 28.0862C30.6311 27.9865 30.604 27.8717 30.604 27.7405C30.604 27.6094 30.6321 27.4966 30.6887 27.3975C30.7453 27.2979 30.8224 27.2216 30.9198 27.1688C31.0172 27.1159 31.1264 27.089 31.2468 27.089C31.3672 27.089 31.4769 27.1154 31.5743 27.1688C31.6718 27.2221 31.7488 27.2979 31.8054 27.3965C31.8621 27.4951 31.8901 27.6099 31.8901 27.7411C31.8901 27.8722 31.861 27.987 31.8029 28.0867C31.7447 28.1858 31.6662 28.263 31.5672 28.3169C31.4682 28.3708 31.358 28.3977 31.2376 28.3977C31.1172 28.3977 31.0106 28.3708 30.9142 28.3169L30.9137 28.3164ZM31.4509 28.1588C31.5172 28.1233 31.5713 28.0704 31.6121 27.9992C31.6529 27.9286 31.6733 27.8422 31.6733 27.74C31.6733 27.6379 31.6534 27.552 31.6131 27.4813C31.5728 27.4107 31.5203 27.3578 31.4555 27.3228C31.3907 27.2882 31.3198 27.2709 31.2443 27.2709C31.1688 27.2709 31.0963 27.2882 31.032 27.3228C30.9678 27.3573 30.9162 27.4102 30.8775 27.4813C30.8387 27.552 30.8193 27.6384 30.8193 27.74C30.8193 27.8417 30.8387 27.9306 30.8764 28.0013C30.9147 28.0719 30.9652 28.1248 31.0285 28.1599C31.0917 28.1944 31.1616 28.2117 31.2371 28.2117C31.3126 28.2117 31.384 28.1939 31.4509 28.1583V28.1588Z" fill="#03045E"/>
                                <path d="M33.1635 27.2262C33.2594 27.3197 33.3073 27.4539 33.3073 27.6297V28.3764H33.0982V27.6597C33.0982 27.5332 33.0665 27.4366 33.0028 27.3695C32.9395 27.3024 32.8528 27.2689 32.7431 27.2689C32.6334 27.2689 32.5431 27.3035 32.4773 27.3731C32.4115 27.4422 32.3789 27.5433 32.3789 27.676V28.3764H32.1676V27.1093H32.3789V27.2897C32.4207 27.2252 32.4773 27.1749 32.5498 27.1393C32.6217 27.1037 32.7008 27.0859 32.7875 27.0859C32.9426 27.0859 33.0676 27.1327 33.1635 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M34.6491 27.2262C34.745 27.3197 34.7929 27.4539 34.7929 27.6297V28.3764H34.5838V27.6597C34.5838 27.5332 34.5521 27.4366 34.4884 27.3695C34.4251 27.3024 34.3384 27.2689 34.2287 27.2689C34.119 27.2689 34.0287 27.3035 33.9629 27.3731C33.8971 27.4422 33.8644 27.5433 33.8644 27.676V28.3764H33.6532V27.1093H33.8644V27.2897C33.9063 27.2252 33.9629 27.1749 34.0353 27.1393C34.1073 27.1037 34.1863 27.0859 34.2731 27.0859C34.4282 27.0859 34.5531 27.1327 34.6491 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M36.2923 27.8214H35.2755C35.2832 27.9464 35.3265 28.044 35.4046 28.1141C35.4827 28.1843 35.5775 28.2193 35.6888 28.2193C35.7801 28.2193 35.8561 28.198 35.9173 28.1558C35.978 28.1136 36.0214 28.0567 36.0459 27.986H36.2734C36.2392 28.108 36.1714 28.2066 36.0693 28.2834C35.9673 28.3596 35.8403 28.3977 35.6888 28.3977C35.5684 28.3977 35.4602 28.3708 35.3648 28.3169C35.2694 28.263 35.1949 28.1863 35.1408 28.0867C35.0868 27.987 35.0597 27.8722 35.0597 27.7411C35.0597 27.6099 35.0863 27.4951 35.1388 27.3965C35.1914 27.2979 35.2653 27.2216 35.3602 27.1688C35.4551 27.1159 35.5648 27.089 35.6888 27.089C35.8127 27.089 35.9163 27.1154 36.0091 27.1677C36.102 27.2201 36.1734 27.2923 36.2239 27.3838C36.2744 27.4757 36.2994 27.5794 36.2994 27.6948C36.2994 27.735 36.2969 27.7771 36.2923 27.8219V27.8214ZM36.0275 27.4437C35.9918 27.3858 35.9438 27.3421 35.8826 27.3121C35.8219 27.2821 35.7541 27.2669 35.6796 27.2669C35.573 27.2669 35.4821 27.3009 35.4066 27.3685C35.3311 27.4361 35.2888 27.5301 35.2781 27.6506H36.0811C36.0811 27.5708 36.0632 27.5017 36.0275 27.4437Z" fill="#03045E"/>
                                <path d="M36.5774 27.3975C36.63 27.2994 36.7029 27.2237 36.7968 27.1698C36.8902 27.1159 36.9973 27.089 37.1182 27.089C37.2743 27.089 37.4034 27.1271 37.5044 27.2023C37.6059 27.278 37.6728 27.3827 37.7054 27.5164H37.4779C37.4564 27.4392 37.4141 27.3787 37.3514 27.3339C37.2886 27.2892 37.2111 27.2669 37.1182 27.2669C36.9978 27.2669 36.9004 27.308 36.8259 27.3904C36.7514 27.4727 36.7142 27.5896 36.7142 27.7405C36.7142 27.8915 36.7514 28.0109 36.8259 28.0943C36.9004 28.1776 36.9978 28.2193 37.1182 28.2193C37.2111 28.2193 37.2886 28.198 37.3503 28.1548C37.4121 28.1116 37.4544 28.0501 37.4779 27.9698H37.7054C37.6712 28.0994 37.6034 28.2031 37.5013 28.2808C37.3993 28.3586 37.2713 28.3977 37.1182 28.3977C36.9978 28.3977 36.8902 28.3708 36.7968 28.3169C36.7029 28.263 36.63 28.1868 36.5774 28.0882C36.5249 27.9896 36.4984 27.8737 36.4984 27.7416C36.4984 27.6094 36.5249 27.4961 36.5774 27.3985V27.3975Z" fill="#03045E"/>
                                <path d="M38.2441 27.2826V28.0292C38.2441 28.0907 38.2574 28.1344 38.2834 28.1599C38.3099 28.1853 38.3554 28.198 38.4207 28.198H38.5762V28.3759H38.386C38.2681 28.3759 38.1804 28.3489 38.1212 28.2951C38.0625 28.2412 38.0329 28.1522 38.0329 28.0292V27.2826H37.8682V27.1093H38.0329V26.7901H38.2441V27.1093H38.5757V27.2826H38.2441Z" fill="#03045E"/>
                                <path d="M39.9843 27.8214H38.9675C38.9752 27.9464 39.0186 28.044 39.0966 28.1141C39.1747 28.1843 39.2696 28.2193 39.3808 28.2193C39.4721 28.2193 39.5481 28.198 39.6093 28.1558C39.67 28.1136 39.7134 28.0567 39.7379 27.986H39.9654C39.9312 28.108 39.8634 28.2066 39.7613 28.2834C39.6593 28.3596 39.5323 28.3977 39.3808 28.3977C39.2604 28.3977 39.1522 28.3708 39.0568 28.3169C38.9614 28.263 38.8869 28.1863 38.8329 28.0867C38.7788 27.987 38.7517 27.8722 38.7517 27.7411C38.7517 27.6099 38.7783 27.4951 38.8308 27.3965C38.8834 27.2979 38.9573 27.2216 39.0522 27.1688C39.1471 27.1159 39.2568 27.089 39.3808 27.089C39.5047 27.089 39.6083 27.1154 39.7011 27.1677C39.794 27.2201 39.8654 27.2923 39.9159 27.3838C39.9664 27.4757 39.9914 27.5794 39.9914 27.6948C39.9914 27.735 39.9889 27.7771 39.9843 27.8219V27.8214ZM39.7195 27.4437C39.6838 27.3858 39.6358 27.3421 39.5746 27.3121C39.5139 27.2821 39.4461 27.2669 39.3716 27.2669C39.265 27.2669 39.1742 27.3009 39.0987 27.3685C39.0231 27.4361 38.9808 27.5301 38.9701 27.6506H39.7731C39.7731 27.5708 39.7552 27.5017 39.7195 27.4437Z" fill="#03045E"/>
                                <path d="M40.27 27.3975C40.3225 27.2994 40.395 27.2237 40.4868 27.1698C40.5791 27.1159 40.6822 27.089 40.797 27.089C40.8959 27.089 40.9878 27.1118 41.073 27.1571C41.1582 27.2028 41.2229 27.2623 41.2683 27.3365V26.6661H41.4816V28.3769H41.2683V28.139C41.2265 28.2147 41.1648 28.2762 41.0826 28.325C41.001 28.3738 40.9051 28.3977 40.7949 28.3977C40.6847 28.3977 40.5796 28.3698 40.4873 28.3144C40.3955 28.259 40.323 28.1812 40.2705 28.0811C40.2179 27.981 40.1914 27.8666 40.1914 27.739C40.1914 27.6115 40.2179 27.4961 40.2705 27.398L40.27 27.3975ZM41.2097 27.491C41.1709 27.4198 41.1189 27.3655 41.0531 27.3278C40.9872 27.2902 40.9153 27.2714 40.8362 27.2714C40.7572 27.2714 40.6852 27.2897 40.6204 27.3268C40.5557 27.3639 40.5036 27.4178 40.4648 27.4885C40.4261 27.5591 40.4067 27.6425 40.4067 27.738C40.4067 27.8336 40.4261 27.9195 40.4648 27.9911C40.5036 28.0628 40.5551 28.1177 40.6204 28.1553C40.6857 28.1929 40.7577 28.2117 40.8362 28.2117C40.9148 28.2117 40.9878 28.1929 41.0531 28.1553C41.1184 28.1177 41.1709 28.0628 41.2097 27.9911C41.2484 27.9195 41.2678 27.8356 41.2678 27.74C41.2678 27.6445 41.2484 27.5611 41.2097 27.4905V27.491Z" fill="#03045E"/>
                        </svg>
                        <span class="content">
                            <span>Summer Training Program</span>
                            <span class="duration">4-6 Weeks</span>
                        </span>
                    </a>
                </div>
            </div>
            
            <div class="nsh-ach-right damlgenaicoursetb d-none">
                <a href="http://localhost/TIA/data-analytics-machine-learning-ai-course">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="29" viewBox="0 0 48 29" fill="none">
                            <path d="M20.3044 5.93391C20.5931 5.53086 20.8533 5.12833 21.1523 4.75578C23.1934 2.21502 25.835 0.672469 29.0587 0.173872C32.7083 -0.390797 36.0443 0.414276 38.9761 2.67143C41.4075 4.54282 42.8967 7.01039 43.4186 10.035C43.6425 11.3321 43.617 12.6337 43.3696 13.9262C43.3308 14.1285 43.3885 14.1915 43.5548 14.2657C45.2684 15.0342 46.5612 16.2438 47.3478 17.9511C48.8717 21.2572 47.6498 25.1225 44.4685 27.0386C43.9624 27.3436 43.3977 27.555 42.8518 27.7903C42.6003 27.8986 42.4391 27.8269 42.3697 27.6353C42.2978 27.4356 42.3947 27.2999 42.6646 27.2023C45.0241 26.3484 46.6198 24.7652 47.2075 22.3078C47.832 19.6969 46.7234 17.0032 44.4501 15.4835C43.9716 15.1633 43.4313 14.9336 42.913 14.6743C42.6819 14.5585 42.6197 14.4721 42.6758 14.221C43.6961 9.68075 41.7045 5.22642 37.965 2.70192C36.1749 1.4933 34.199 0.774628 32.039 0.667386C27.1742 0.425966 23.3766 2.29837 20.7615 6.43708C20.6064 6.68206 20.4722 6.72678 20.1983 6.58651C18.7535 5.84801 17.2154 5.55526 15.6053 5.68334C13.6387 5.83988 11.9128 6.5799 10.4701 7.92931C9.11613 9.19486 8.30396 10.7496 8.05449 12.5844C8.00092 12.9803 8.00603 13.3849 7.99582 13.7859C7.98715 14.1219 7.91114 14.1966 7.56882 14.2083C5.02977 14.2967 3.007 15.3427 1.61835 17.4814C0.0123727 19.9561 0.390909 23.2923 2.46674 25.3985C3.37278 26.3179 4.43901 26.9593 5.6843 27.3065C5.71389 27.3146 5.74246 27.3278 5.77256 27.3354C5.96999 27.3863 6.0608 27.5143 6.02815 27.7121C5.99856 27.8925 5.82255 27.9992 5.61135 27.9255C5.0864 27.7431 4.54461 27.5835 4.05486 27.3283C1.9474 26.23 0.573035 24.539 0.135321 22.1909C-0.323821 19.7279 0.39397 17.5887 2.1586 15.8194C3.51817 14.4558 5.19455 13.7432 7.11886 13.6096C7.25405 13.6004 7.3668 13.6055 7.37547 13.4047C7.51934 10.1046 9.08858 7.6574 11.9807 6.10875C14.5799 4.71715 17.2883 4.68259 20.0126 5.80532C20.1115 5.84598 20.2085 5.89121 20.3049 5.9334L20.3044 5.93391Z" fill="#03045E"/>
                            <path d="M10.5676 23.8793C12.3516 23.8555 14.1351 23.8351 15.9191 23.8072C17.6639 23.7802 19.4081 23.7472 21.1528 23.7152C22.7808 23.6852 24.4092 23.6547 26.0371 23.6222C27.541 23.5927 29.045 23.5622 30.5489 23.5292C31.8743 23.5002 33.2002 23.4727 34.5256 23.4351C35.6102 23.4041 36.6948 23.3619 37.7789 23.3187C38.1462 23.304 38.361 22.9533 38.2268 22.6001C37.8187 21.5261 37.408 20.4527 36.9988 19.3788C36.9662 19.2934 36.9381 19.2065 36.8943 19.0825C37.2259 19.0825 37.5319 19.0738 37.8365 19.0906C37.8875 19.0931 37.9523 19.1983 37.9763 19.268C38.2288 19.9963 38.4737 20.7272 38.7221 21.4575C38.7415 21.5144 38.767 21.5693 38.8104 21.6751C38.8609 21.5393 38.8976 21.4504 38.9283 21.3594C39.1594 20.6646 39.395 19.9709 39.6159 19.2725C39.6654 19.1165 39.7389 19.0682 39.895 19.0758C40.1419 19.088 40.3899 19.0789 40.6664 19.0789C40.6419 19.1633 40.6271 19.2268 40.6046 19.2878C40.119 20.6128 39.6348 21.9383 39.1451 23.2623C38.863 24.0257 38.4946 24.2819 37.6712 24.2738C35.8408 24.2555 34.0103 24.229 32.1804 24.2046C31.1336 24.1909 30.0867 24.1736 29.0399 24.1589C26.8916 24.1279 24.7438 24.0969 22.5956 24.0659C21.5564 24.0506 20.5172 24.0349 19.4785 24.0196C17.3226 23.9886 15.1672 23.9546 13.0112 23.9281C12.1975 23.9185 11.3833 23.9266 10.5691 23.9266L10.5681 23.8788L10.5676 23.8793Z" fill="#03045E"/>
                            <path d="M36.3111 22.751H35.3827C35.3827 22.38 35.3862 22.0257 35.3816 21.6715C35.3755 21.1932 35.3663 20.715 35.3521 20.2367C35.3454 20.0136 35.2648 19.8276 35.0301 19.7518C34.7837 19.6725 34.57 19.7437 34.3873 19.9109C34.1705 20.1096 34.1251 20.3785 34.12 20.6519C34.1093 21.2466 34.1088 21.8413 34.1057 22.4359C34.1037 22.7683 34.1057 22.7683 33.7604 22.7683C33.6206 22.7683 33.4803 22.7607 33.3415 22.7703C33.1849 22.781 33.1436 22.7124 33.1451 22.566C33.1517 22.0329 33.1502 21.4997 33.1461 20.9671C33.1446 20.7434 33.1354 20.5198 33.1206 20.2967C33.0972 19.9465 32.9829 19.7793 32.7462 19.7305C32.4498 19.6695 32.0865 19.8159 31.9922 20.1C31.9013 20.3724 31.8646 20.6707 31.8534 20.9594C31.8325 21.4916 31.8478 22.0252 31.8463 22.5584C31.8463 22.6184 31.836 22.6783 31.8279 22.7663C31.5488 22.7663 31.279 22.7719 31.0096 22.7602C30.9678 22.7581 30.8953 22.6748 30.8953 22.629C30.8917 21.4707 30.8963 20.3119 30.9004 19.1536C30.9004 19.1399 30.9127 19.1257 30.9264 19.0957H31.7779C31.7845 19.2365 31.7906 19.365 31.7988 19.5399C32.1304 19.2151 32.4727 18.992 32.9308 18.9905C33.3895 18.9894 33.7415 19.1607 33.9925 19.5572C34.1037 19.4581 34.1955 19.3671 34.2965 19.2873C34.7087 18.961 35.1715 18.9096 35.6561 19.0758C36.0418 19.2085 36.2943 19.4952 36.3045 19.8997C36.328 20.8405 36.3122 21.7818 36.3122 22.75L36.3111 22.751Z" fill="#03045E"/>
                            <path d="M8.89734 22.7683C9.12079 22.1975 9.32587 21.6751 9.52994 21.1521C9.96255 20.0466 10.3977 18.9422 10.8242 17.8347C10.8773 17.6975 10.941 17.6431 11.091 17.6512C11.3308 17.6644 11.5726 17.6436 11.8114 17.6614C11.8894 17.667 12.0042 17.7305 12.0312 17.7961C12.6893 19.4098 13.3383 21.0275 13.9877 22.6443C13.9959 22.6651 13.9953 22.6895 14.0071 22.7683C13.7581 22.7683 13.5143 22.7485 13.2755 22.7734C13.0061 22.8018 12.8653 22.7119 12.7949 22.4481C12.7317 22.2113 12.6169 21.9886 12.5343 21.7564C12.4929 21.64 12.4343 21.5831 12.3006 21.5841C11.7267 21.5902 11.1527 21.5897 10.5788 21.5841C10.4452 21.5826 10.388 21.64 10.3477 21.7589C10.2544 22.0364 10.1416 22.3073 10.0503 22.5853C10.008 22.7139 9.94775 22.7754 9.80389 22.7703C9.51871 22.7612 9.23302 22.7678 8.89734 22.7678V22.7683ZM12.1425 20.7475C11.9032 20.1071 11.6772 19.5023 11.4512 18.898C11.4333 18.9 11.4155 18.902 11.3976 18.9041C11.1762 19.5109 10.9553 20.1183 10.7263 20.7475H12.1425Z" fill="#03045E"/>
                            <path d="M33.4303 14.5153C33.4303 15.79 32.5584 16.6733 31.3004 16.6728C29.9806 16.6723 29.1557 15.8357 29.1547 14.4975C29.1541 13.2452 30.0332 12.3684 31.2912 12.3664C32.5523 12.3644 33.4303 13.2467 33.4303 14.5158V14.5153ZM32.36 14.5153C32.3008 14.284 32.2697 14.035 32.1743 13.8144C32.0141 13.4439 31.7034 13.2416 31.2963 13.2421C30.879 13.2426 30.5673 13.4546 30.4025 13.8317C30.2066 14.281 30.203 14.7506 30.3984 15.2009C30.5607 15.575 30.855 15.7951 31.2825 15.7996C31.7192 15.8042 32.0243 15.5907 32.187 15.2019C32.2743 14.9936 32.3008 14.7598 32.36 14.5148V14.5153Z" fill="#03045E"/>
                            <path d="M25.0918 22.2499C25.0076 22.3221 24.9729 22.348 24.9423 22.3785C24.2638 23.0616 23.2659 22.9828 22.7134 22.2016C22.2487 21.5454 22.2359 20.285 22.6869 19.6227C23.1664 18.9188 24.1521 18.7765 24.8117 19.3157C24.8632 19.3579 24.9153 19.4001 25.003 19.4713V17.6695H25.9621V22.7658C25.7065 22.7658 25.4601 22.7719 25.2147 22.7597C25.1719 22.7576 25.1066 22.6829 25.0979 22.6341C25.0795 22.5304 25.0923 22.4217 25.0923 22.2494L25.0918 22.2499ZM23.3256 20.8674C23.3343 20.87 23.343 20.8725 23.3516 20.8746C23.3598 21.0052 23.3608 21.1368 23.3787 21.2664C23.3924 21.3645 23.42 21.4626 23.4542 21.5561C23.5725 21.8763 23.8531 22.0817 24.1725 22.0908C24.4622 22.0989 24.7775 21.8966 24.8949 21.5932C25.0709 21.1373 25.0668 20.6687 24.8974 20.2138C24.779 19.8962 24.5286 19.7208 24.1811 19.7223C23.8337 19.7244 23.5868 19.9068 23.4659 20.2189C23.3868 20.4227 23.37 20.6499 23.3256 20.8669V20.8674Z" fill="#03045E"/>
                            <path d="M17.7062 16.5885H16.7834V11.5191H17.7231V13.3631C17.8231 13.2782 17.8771 13.2319 17.9312 13.1862C18.3807 12.8076 18.8857 12.7313 19.4326 12.9265C19.8086 13.0607 20.0647 13.3412 20.0861 13.7275C20.1397 14.6734 20.1438 15.6218 20.1682 16.5859H19.176C19.176 16.4187 19.1775 16.2662 19.176 16.1137C19.1683 15.4652 19.1688 14.8167 19.1474 14.1687C19.1352 13.8012 18.9837 13.6279 18.6852 13.5679C18.372 13.5049 17.9919 13.6548 17.8817 13.9425C17.7904 14.1814 17.744 14.4487 17.7348 14.7059C17.7134 15.3077 17.7261 15.911 17.7246 16.5137C17.7246 16.5285 17.7174 16.5437 17.7067 16.5879L17.7062 16.5885Z" fill="#03045E"/>
                            <path d="M21.8084 22.7673C21.4865 22.7673 21.2238 22.7734 20.9615 22.7622C20.9105 22.7602 20.8457 22.7002 20.8161 22.6504C20.7707 22.5736 20.7503 22.4822 20.7116 22.3754C20.6335 22.4349 20.5748 22.4806 20.5146 22.5249C20.0871 22.8425 19.6101 22.9127 19.1041 22.781C18.6597 22.6656 18.3679 22.3038 18.3414 21.8672C18.3087 21.332 18.5153 20.9564 18.9959 20.7917C19.3739 20.6621 19.7734 20.5935 20.1631 20.4974C20.2305 20.4807 20.3004 20.4695 20.3647 20.4441C20.4764 20.3998 20.6478 20.4283 20.6417 20.2311C20.635 20.0253 20.5784 19.8499 20.3611 19.7716C19.9035 19.6065 19.5622 19.7325 19.2964 20.1788C19.1602 20.1544 19.0173 20.1284 18.8745 20.103C18.7383 20.0786 18.6021 20.0547 18.4398 20.0263C18.5607 19.5887 18.7842 19.2507 19.2168 19.1104C19.7759 18.929 20.3499 18.9366 20.91 19.1028C21.3692 19.239 21.5676 19.5475 21.5865 20.0944C21.5982 20.4415 21.586 20.7892 21.5895 21.1363C21.5952 21.6725 21.5283 22.2169 21.8084 22.7678V22.7673ZM20.6177 20.9889C20.2677 21.0799 19.9254 21.1465 19.5994 21.2593C19.2724 21.3726 19.2046 21.7416 19.4372 21.9947C19.5928 22.164 19.8693 22.2133 20.1269 22.1177C20.5636 21.9561 20.7044 21.6491 20.6177 20.9889Z" fill="#03045E"/>
                            <path d="M27.7022 21.1922C27.6747 21.644 27.9083 22.0029 28.269 22.0994C28.6629 22.2052 28.8827 22.0822 29.145 21.6024C29.4434 21.6532 29.7454 21.705 30.0821 21.763C29.9265 22.1935 29.6771 22.506 29.2776 22.6885C28.0104 23.2669 26.7146 22.4791 26.6942 21.0972C26.6886 20.7277 26.7401 20.3358 26.8656 19.9902C27.1278 19.268 27.8099 18.9183 28.6476 19.0006C29.3587 19.0708 29.9056 19.5699 30.0597 20.3058C30.1051 20.5239 30.1163 20.749 30.1464 20.9701C30.1699 21.1434 30.0985 21.1978 29.9245 21.1953C29.273 21.1866 28.6215 21.1917 27.9701 21.1917C27.8859 21.1917 27.8022 21.1917 27.7022 21.1917V21.1922ZM27.714 20.5823H29.1674C29.1368 20.0075 28.8246 19.6827 28.367 19.7239C27.9578 19.761 27.6681 20.1366 27.714 20.5823Z" fill="#03045E"/>
                            <path d="M24.3602 15.0393H21.8977C21.8385 15.4327 22.0727 15.8179 22.4226 15.9282C22.82 16.0533 23.0797 15.9186 23.3297 15.4444C23.6261 15.4932 23.9281 15.543 24.2684 15.5989C24.1271 15.9963 23.9092 16.2881 23.5618 16.4756C22.3068 17.1541 20.9172 16.3562 20.8666 14.9265C20.8473 14.3831 20.9223 13.8571 21.2829 13.42C21.7482 12.8563 22.3645 12.7278 23.0481 12.8818C23.7052 13.0297 24.0863 13.4734 24.2347 14.1107C24.3026 14.4009 24.3163 14.7033 24.3597 15.0393H24.3602ZM21.9053 14.4502C22.3481 14.4502 22.7956 14.4538 23.243 14.4446C23.2833 14.4436 23.3542 14.3638 23.3557 14.3186C23.369 13.8927 22.9807 13.5257 22.5639 13.5593C22.1431 13.5933 21.837 13.9923 21.9053 14.4502Z" fill="#03045E"/>
                            <path d="M14.5667 16.5864H13.5515V12.3623H12.0353V11.5201H16.0783V12.3456H14.5667V16.5864Z" fill="#03045E"/>
                            <path d="M35.9515 16.5839H34.9245V12.3572H33.4068C33.4068 12.0955 33.3976 11.852 33.4155 11.6106C33.4186 11.5694 33.5349 11.5064 33.6002 11.5054C34.0348 11.4958 34.4695 11.5003 34.9041 11.5003C35.6648 11.5003 36.4254 11.5003 37.1861 11.5003H37.4559V12.3476H35.952V16.5844L35.9515 16.5839Z" fill="#03045E"/>
                            <path d="M16.9338 21.3594C17.2486 21.4143 17.5506 21.4662 17.8705 21.5221C17.7251 22.1177 17.4348 22.5584 16.84 22.7464C15.9885 23.0148 15.1233 22.7642 14.6968 22.1269C14.2703 21.489 14.2581 20.3277 14.7065 19.7056C14.855 19.4997 15.0641 19.3081 15.2886 19.1922C15.8477 18.903 16.4426 18.9163 17.0211 19.147C17.4557 19.3203 17.6802 19.6873 17.822 20.1472C17.5119 20.2057 17.2109 20.2621 16.8951 20.3216C16.8767 20.2804 16.8583 20.2418 16.8425 20.2021C16.7216 19.8921 16.5043 19.7274 16.1931 19.7376C15.8768 19.7478 15.6059 19.8723 15.4927 20.1828C15.3182 20.6626 15.3274 21.1556 15.511 21.6308C15.6263 21.9292 15.9248 22.0776 16.2676 22.0583C16.564 22.041 16.7553 21.8834 16.868 21.5632C16.8884 21.5058 16.9063 21.4468 16.9344 21.3599L16.9338 21.3594Z" fill="#03045E"/>
                            <path d="M27.1166 14.0279C27.1166 13.2635 27.1217 12.4985 27.113 11.7341C27.111 11.5522 27.1666 11.4841 27.3513 11.4973C27.5441 11.511 27.7395 11.5085 27.9328 11.4978C28.0961 11.4886 28.1481 11.5501 28.1466 11.7113C28.14 12.4528 28.1435 13.1943 28.1435 13.9359C28.1435 14.747 28.1389 15.5577 28.1471 16.3689C28.1491 16.5508 28.0935 16.6189 27.9089 16.6057C27.716 16.592 27.5206 16.5945 27.3273 16.6052C27.164 16.6144 27.112 16.5529 27.1135 16.3918C27.1196 15.604 27.1166 14.8162 27.1166 14.0284V14.0279Z" fill="#03045E"/>
                            <path d="M7.26688 27.1454C7.3383 27.0198 7.43523 26.9212 7.55818 26.8506C7.68112 26.7799 7.81734 26.7444 7.96783 26.7444C8.14435 26.7444 8.29842 26.7866 8.42953 26.8714C8.56115 26.9563 8.65706 27.0763 8.71725 27.2323H8.46422C8.41932 27.1352 8.35453 27.0605 8.27036 27.0082C8.18618 26.9558 8.08517 26.9299 7.96732 26.9299C7.84948 26.9299 7.75306 26.9563 7.66327 27.0082C7.57348 27.0605 7.50308 27.1352 7.45207 27.2313C7.40105 27.3278 7.37503 27.4407 7.37503 27.5698C7.37503 27.6989 7.40054 27.8097 7.45207 27.9062C7.50308 28.0028 7.57348 28.077 7.66327 28.1294C7.75306 28.1817 7.85458 28.2076 7.96732 28.2076C8.08007 28.2076 8.18567 28.1817 8.27036 28.1304C8.35504 28.079 8.41932 28.0043 8.46422 27.9073H8.71725C8.65706 28.0613 8.56115 28.1807 8.42953 28.2646C8.29791 28.3484 8.14435 28.3906 7.96783 28.3906C7.81785 28.3906 7.68112 28.3555 7.55818 28.2854C7.43523 28.2153 7.3383 28.1177 7.26688 27.9926C7.19597 27.8676 7.16026 27.7268 7.16026 27.5698C7.16026 27.4127 7.19597 27.2714 7.26688 27.1454Z" fill="#03045E"/>
                            <path d="M9.2621 28.3164C9.16517 28.2625 9.08916 28.1858 9.03457 28.0862C8.97947 27.9865 8.95244 27.8717 8.95244 27.7405C8.95244 27.6094 8.9805 27.4966 9.03712 27.3975C9.09375 27.2979 9.17078 27.2216 9.26822 27.1688C9.36566 27.1154 9.47484 27.089 9.59523 27.089C9.71563 27.089 9.82532 27.1154 9.92276 27.1688C10.0202 27.2221 10.0972 27.2979 10.1539 27.3965C10.2105 27.4951 10.2385 27.6099 10.2385 27.7411C10.2385 27.8722 10.2095 27.987 10.1513 28.0867C10.0931 28.1858 10.0146 28.263 9.91561 28.3169C9.81664 28.3708 9.70645 28.3977 9.58605 28.3977C9.46566 28.3977 9.35903 28.3708 9.26261 28.3169L9.2621 28.3164ZM9.79879 28.1588C9.86511 28.1233 9.91919 28.0704 9.96 27.9992C10.0008 27.9286 10.0212 27.8422 10.0212 27.74C10.0212 27.6379 10.0013 27.552 9.96102 27.4813C9.92072 27.4107 9.86817 27.3578 9.80338 27.3228C9.73859 27.2882 9.66768 27.2709 9.59217 27.2709C9.51667 27.2709 9.44423 27.2882 9.37995 27.3228C9.31567 27.3573 9.26414 27.4102 9.22537 27.4813C9.1866 27.552 9.16721 27.6384 9.16721 27.74C9.16721 27.8417 9.1866 27.9306 9.22435 28.0013C9.26261 28.0719 9.31312 28.1248 9.37638 28.1599C9.43964 28.1944 9.50953 28.2117 9.58503 28.2117C9.66054 28.2117 9.73196 28.1939 9.79879 28.1583V28.1588Z" fill="#03045E"/>
                            <path d="M11.5114 27.2262C11.6073 27.3197 11.6553 27.4539 11.6553 27.6297V28.3764H11.4461V27.6597C11.4461 27.5332 11.4145 27.4366 11.3507 27.3695C11.2874 27.3024 11.2007 27.2689 11.091 27.2689C10.9813 27.2689 10.891 27.3035 10.8252 27.3731C10.7594 27.4422 10.7268 27.5433 10.7268 27.676V28.3764H10.5156V27.1093H10.7268V27.2897C10.7686 27.2252 10.8252 27.1749 10.8977 27.1393C10.9696 27.1037 11.0487 27.0859 11.1354 27.0859C11.2905 27.0859 11.4155 27.1327 11.5114 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M12.997 27.2262C13.0929 27.3197 13.1408 27.4539 13.1408 27.6297V28.3764H12.9317V27.6597C12.9317 27.5332 12.9 27.4366 12.8363 27.3695C12.773 27.3024 12.6863 27.2689 12.5766 27.2689C12.4669 27.2689 12.3766 27.3035 12.3108 27.3731C12.245 27.4422 12.2123 27.5433 12.2123 27.676V28.3764H12.0011V27.1093H12.2123V27.2897C12.2542 27.2252 12.3108 27.1749 12.3832 27.1393C12.4552 27.1037 12.5343 27.0859 12.621 27.0859C12.7761 27.0859 12.9011 27.1327 12.997 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M14.6397 27.8214H13.6229C13.6306 27.9464 13.6739 28.044 13.752 28.1141C13.8301 28.1843 13.9249 28.2193 14.0362 28.2193C14.1275 28.2193 14.2035 28.198 14.2647 28.1558C14.3254 28.1136 14.3688 28.0567 14.3933 27.986H14.6208C14.5866 28.108 14.5188 28.2066 14.4167 28.2834C14.3147 28.3596 14.1877 28.3977 14.0362 28.3977C13.9158 28.3977 13.8076 28.3708 13.7122 28.3169C13.6168 28.263 13.5423 28.1863 13.4882 28.0867C13.4342 27.987 13.4071 27.8722 13.4071 27.7411C13.4071 27.6099 13.4337 27.4951 13.4862 27.3965C13.5388 27.2979 13.6127 27.2216 13.7076 27.1688C13.8025 27.1159 13.9122 27.089 14.0362 27.089C14.1601 27.089 14.2637 27.1154 14.3565 27.1677C14.4494 27.2201 14.5208 27.2923 14.5713 27.3838C14.6218 27.4757 14.6468 27.5794 14.6468 27.6948C14.6468 27.735 14.6443 27.7771 14.6397 27.8219V27.8214ZM14.3754 27.4437C14.3397 27.3858 14.2917 27.3421 14.2305 27.3121C14.1698 27.2821 14.102 27.2669 14.0275 27.2669C13.9209 27.2669 13.83 27.3009 13.7545 27.3685C13.679 27.4361 13.6367 27.5301 13.626 27.6506H14.429C14.429 27.5708 14.4111 27.5017 14.3754 27.4437Z" fill="#03045E"/>
                            <path d="M14.9254 27.3975C14.9779 27.2994 15.0509 27.2237 15.1447 27.1698C15.2381 27.1159 15.3452 27.089 15.4661 27.089C15.6222 27.089 15.7513 27.1271 15.8523 27.2023C15.9538 27.278 16.0207 27.3827 16.0533 27.5164H15.8258C15.8044 27.4392 15.762 27.3787 15.6993 27.3339C15.6365 27.2892 15.559 27.2669 15.4661 27.2669C15.3457 27.2669 15.2483 27.308 15.1738 27.3904C15.0993 27.4727 15.0621 27.5896 15.0621 27.7405C15.0621 27.8915 15.0993 28.0109 15.1738 28.0943C15.2483 28.1776 15.3457 28.2193 15.4661 28.2193C15.559 28.2193 15.6365 28.198 15.6982 28.1548C15.76 28.1116 15.8023 28.0501 15.8258 27.9698H16.0533C16.0191 28.0994 15.9513 28.2031 15.8493 28.2808C15.7472 28.3586 15.6192 28.3977 15.4661 28.3977C15.3457 28.3977 15.2381 28.3708 15.1447 28.3169C15.0509 28.263 14.9779 28.1868 14.9254 28.0882C14.8728 27.9896 14.8463 27.8737 14.8463 27.7416C14.8463 27.6094 14.8728 27.4961 14.9254 27.3985V27.3975Z" fill="#03045E"/>
                            <path d="M16.5915 27.2826V28.0292C16.5915 28.0907 16.6048 28.1344 16.6308 28.1599C16.6573 28.1853 16.7027 28.198 16.768 28.198H16.9236V28.3759H16.7334C16.6155 28.3759 16.5278 28.3489 16.4686 28.2951C16.4099 28.2412 16.3803 28.1522 16.3803 28.0292V27.2826H16.2155V27.1093H16.3803V26.7901H16.5915V27.1093H16.9231V27.2826H16.5915Z" fill="#03045E"/>
                            <path d="M17.1859 26.8618C17.1583 26.8338 17.144 26.8003 17.144 26.7601C17.144 26.72 17.1583 26.6859 17.1859 26.6585C17.2139 26.6305 17.2476 26.6168 17.2879 26.6168C17.3282 26.6168 17.3593 26.6305 17.3864 26.6585C17.4134 26.6864 17.4272 26.72 17.4272 26.7601C17.4272 26.8003 17.4134 26.8343 17.3864 26.8618C17.3593 26.8897 17.3267 26.9035 17.2879 26.9035C17.2491 26.9035 17.2134 26.8897 17.1859 26.8618ZM17.3899 27.1093V28.3764H17.1787V27.1093H17.3899Z" fill="#03045E"/>
                            <path d="M18.7454 27.2262C18.8413 27.3197 18.8893 27.4539 18.8893 27.6297V28.3764H18.6801V27.6597C18.6801 27.5332 18.6485 27.4366 18.5847 27.3695C18.5215 27.3024 18.4347 27.2689 18.325 27.2689C18.2154 27.2689 18.1251 27.3035 18.0593 27.3731C17.9934 27.4422 17.9608 27.5433 17.9608 27.676V28.3764H17.7496V27.1093H17.9608V27.2897C18.0026 27.2252 18.0593 27.1749 18.1317 27.1393C18.2036 27.1037 18.2827 27.0859 18.3694 27.0859C18.5245 27.0859 18.6495 27.1327 18.7454 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M20.0484 27.1601C20.131 27.2079 20.1927 27.2679 20.233 27.3406V27.1093H20.4463V28.4038C20.4463 28.5197 20.4218 28.6224 20.3718 28.7123C20.3223 28.8023 20.2519 28.8729 20.1596 28.9238C20.0677 28.9746 19.9606 29 19.8382 29C19.6708 29 19.5316 28.9609 19.4204 28.8821C19.3091 28.8033 19.2433 28.6961 19.2234 28.5609H19.4321C19.455 28.6381 19.5035 28.6996 19.5759 28.7469C19.6489 28.7942 19.7361 28.8175 19.8382 28.8175C19.9545 28.8175 20.0489 28.7814 20.1223 28.7088C20.1958 28.6361 20.2325 28.5344 20.2325 28.4038V28.138C20.1907 28.2117 20.129 28.2737 20.0468 28.323C19.9652 28.3723 19.8693 28.3972 19.7591 28.3972C19.6489 28.3972 19.5438 28.3693 19.4515 28.3139C19.3596 28.2585 19.2872 28.1807 19.2347 28.0806C19.1821 27.9804 19.1556 27.8661 19.1556 27.7385C19.1556 27.6109 19.1821 27.4956 19.2347 27.3975C19.2872 27.2994 19.3596 27.2237 19.4515 27.1698C19.5438 27.1159 19.6464 27.089 19.7591 27.089C19.8718 27.089 19.9652 27.1129 20.0484 27.1606V27.1601ZM20.1749 27.491C20.1361 27.4198 20.0841 27.3655 20.0183 27.3278C19.9524 27.2902 19.8805 27.2714 19.8014 27.2714C19.7224 27.2714 19.6504 27.2897 19.5856 27.3268C19.5209 27.3639 19.4688 27.4178 19.43 27.4885C19.3913 27.5591 19.3719 27.6425 19.3719 27.738C19.3719 27.8336 19.3913 27.9195 19.43 27.9911C19.4688 28.0628 19.5203 28.1177 19.5856 28.1553C19.6509 28.1929 19.7229 28.2117 19.8014 28.2117C19.88 28.2117 19.953 28.1929 20.0183 28.1553C20.0836 28.1177 20.1361 28.0628 20.1749 27.9911C20.2136 27.9195 20.233 27.8356 20.233 27.74C20.233 27.6445 20.2136 27.5611 20.1749 27.4905V27.491Z" fill="#03045E"/>
                            <path d="M22.4191 26.7647V26.936H21.9783V28.3764H21.7671V26.936H21.3238V26.7647H22.4191Z" fill="#03045E"/>
                            <path d="M23.57 27.1474C23.6475 27.1881 23.7077 27.2496 23.7521 27.3314C23.7965 27.4132 23.8184 27.5123 23.8184 27.6297V28.3764H23.6093V27.6597C23.6093 27.5332 23.5776 27.4366 23.5139 27.3695C23.4506 27.3024 23.3639 27.2689 23.2542 27.2689C23.1445 27.2689 23.0542 27.3035 22.9884 27.3731C22.9226 27.4422 22.8899 27.5433 22.8899 27.676V28.3764H22.6787V26.6656H22.8899V27.2897C22.9318 27.2252 22.9894 27.1749 23.0629 27.1393C23.1363 27.1037 23.219 27.0859 23.3098 27.0859C23.4057 27.0859 23.4924 27.1062 23.57 27.1469V27.1474Z" fill="#03045E"/>
                            <path d="M25.3178 27.8214H24.301C24.3087 27.9464 24.352 28.044 24.4301 28.1141C24.5081 28.1843 24.603 28.2193 24.7143 28.2193C24.8056 28.2193 24.8816 28.198 24.9428 28.1558C25.0035 28.1136 25.0469 28.0567 25.0714 27.986H25.2989C25.2647 28.108 25.1969 28.2066 25.0948 28.2834C24.9928 28.3596 24.8658 28.3977 24.7143 28.3977C24.5939 28.3977 24.4857 28.3708 24.3903 28.3169C24.2949 28.263 24.2204 28.1863 24.1663 28.0867C24.1123 27.987 24.0852 27.8722 24.0852 27.7411C24.0852 27.6099 24.1118 27.4951 24.1643 27.3965C24.2169 27.2979 24.2908 27.2216 24.3857 27.1688C24.4806 27.1159 24.5903 27.089 24.7143 27.089C24.8382 27.089 24.9418 27.1154 25.0346 27.1677C25.1275 27.2201 25.1989 27.2923 25.2494 27.3838C25.2999 27.4757 25.3249 27.5794 25.3249 27.6948C25.3249 27.735 25.3224 27.7771 25.3178 27.8219V27.8214ZM25.053 27.4437C25.0173 27.3858 24.9693 27.3421 24.9081 27.3121C24.8474 27.2821 24.7796 27.2669 24.7051 27.2669C24.5984 27.2669 24.5076 27.3009 24.4321 27.3685C24.3566 27.4361 24.3143 27.5301 24.3036 27.6506H25.1066C25.1066 27.5708 25.0887 27.5017 25.053 27.4437Z" fill="#03045E"/>
                            <path d="M26.4289 26.7647V27.7843C26.4289 27.9276 26.4641 28.0338 26.5345 28.1034C26.6049 28.1726 26.7028 28.2076 26.8283 28.2076C26.9538 28.2076 27.0492 28.1731 27.1196 28.1034C27.19 28.0343 27.2252 27.9276 27.2252 27.7843V26.7647H27.4365V27.7822C27.4365 27.9164 27.4094 28.0292 27.3553 28.1207C27.3013 28.2122 27.2283 28.2808 27.136 28.3255C27.0441 28.3703 26.9406 28.3926 26.8263 28.3926C26.712 28.3926 26.6085 28.3703 26.5161 28.3255C26.4243 28.2808 26.3513 28.2127 26.2983 28.1207C26.2452 28.0287 26.2182 27.9159 26.2182 27.7822V26.7647H26.4294H26.4289Z" fill="#03045E"/>
                            <path d="M28.7843 27.2262C28.8802 27.3197 28.9282 27.4539 28.9282 27.6297V28.3764H28.719V27.6597C28.719 27.5332 28.6874 27.4366 28.6236 27.3695C28.5603 27.3024 28.4736 27.2689 28.3639 27.2689C28.2542 27.2689 28.1639 27.3035 28.0981 27.3731C28.0323 27.4422 27.9997 27.5433 27.9997 27.676V28.3764H27.7885V27.1093H27.9997V27.2897C28.0415 27.2252 28.0981 27.1749 28.1706 27.1393C28.2425 27.1037 28.3216 27.0859 28.4083 27.0859C28.5634 27.0859 28.6884 27.1327 28.7843 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M29.274 27.3975C29.3266 27.2994 29.3995 27.2237 29.4934 27.1698C29.5868 27.1159 29.6939 27.089 29.8148 27.089C29.9709 27.089 30.1 27.1271 30.201 27.2023C30.3025 27.278 30.3693 27.3827 30.402 27.5164H30.1745C30.153 27.4392 30.1107 27.3787 30.0479 27.3339C29.9852 27.2892 29.9077 27.2669 29.8148 27.2669C29.6944 27.2669 29.597 27.308 29.5225 27.3904C29.448 27.4727 29.4108 27.5896 29.4108 27.7405C29.4108 27.8915 29.448 28.0109 29.5225 28.0943C29.597 28.1776 29.6944 28.2193 29.8148 28.2193C29.9077 28.2193 29.9852 28.198 30.0469 28.1548C30.1087 28.1116 30.151 28.0501 30.1745 27.9698H30.402C30.3678 28.0994 30.3 28.2031 30.1979 28.2808C30.0959 28.3586 29.9679 28.3977 29.8148 28.3977C29.6944 28.3977 29.5868 28.3708 29.4934 28.3169C29.3995 28.263 29.3266 28.1868 29.274 28.0882C29.2215 27.9896 29.195 27.8737 29.195 27.7416C29.195 27.6094 29.2215 27.4961 29.274 27.3985V27.3975Z" fill="#03045E"/>
                            <path d="M30.9137 28.3164C30.8168 28.2625 30.7407 28.1858 30.6862 28.0862C30.6311 27.9865 30.604 27.8717 30.604 27.7405C30.604 27.6094 30.6321 27.4966 30.6887 27.3975C30.7453 27.2979 30.8224 27.2216 30.9198 27.1688C31.0172 27.1159 31.1264 27.089 31.2468 27.089C31.3672 27.089 31.4769 27.1154 31.5743 27.1688C31.6718 27.2221 31.7488 27.2979 31.8054 27.3965C31.8621 27.4951 31.8901 27.6099 31.8901 27.7411C31.8901 27.8722 31.861 27.987 31.8029 28.0867C31.7447 28.1858 31.6662 28.263 31.5672 28.3169C31.4682 28.3708 31.358 28.3977 31.2376 28.3977C31.1172 28.3977 31.0106 28.3708 30.9142 28.3169L30.9137 28.3164ZM31.4509 28.1588C31.5172 28.1233 31.5713 28.0704 31.6121 27.9992C31.6529 27.9286 31.6733 27.8422 31.6733 27.74C31.6733 27.6379 31.6534 27.552 31.6131 27.4813C31.5728 27.4107 31.5203 27.3578 31.4555 27.3228C31.3907 27.2882 31.3198 27.2709 31.2443 27.2709C31.1688 27.2709 31.0963 27.2882 31.032 27.3228C30.9678 27.3573 30.9162 27.4102 30.8775 27.4813C30.8387 27.552 30.8193 27.6384 30.8193 27.74C30.8193 27.8417 30.8387 27.9306 30.8764 28.0013C30.9147 28.0719 30.9652 28.1248 31.0285 28.1599C31.0917 28.1944 31.1616 28.2117 31.2371 28.2117C31.3126 28.2117 31.384 28.1939 31.4509 28.1583V28.1588Z" fill="#03045E"/>
                            <path d="M33.1635 27.2262C33.2594 27.3197 33.3073 27.4539 33.3073 27.6297V28.3764H33.0982V27.6597C33.0982 27.5332 33.0665 27.4366 33.0028 27.3695C32.9395 27.3024 32.8528 27.2689 32.7431 27.2689C32.6334 27.2689 32.5431 27.3035 32.4773 27.3731C32.4115 27.4422 32.3789 27.5433 32.3789 27.676V28.3764H32.1676V27.1093H32.3789V27.2897C32.4207 27.2252 32.4773 27.1749 32.5498 27.1393C32.6217 27.1037 32.7008 27.0859 32.7875 27.0859C32.9426 27.0859 33.0676 27.1327 33.1635 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M34.6491 27.2262C34.745 27.3197 34.7929 27.4539 34.7929 27.6297V28.3764H34.5838V27.6597C34.5838 27.5332 34.5521 27.4366 34.4884 27.3695C34.4251 27.3024 34.3384 27.2689 34.2287 27.2689C34.119 27.2689 34.0287 27.3035 33.9629 27.3731C33.8971 27.4422 33.8644 27.5433 33.8644 27.676V28.3764H33.6532V27.1093H33.8644V27.2897C33.9063 27.2252 33.9629 27.1749 34.0353 27.1393C34.1073 27.1037 34.1863 27.0859 34.2731 27.0859C34.4282 27.0859 34.5531 27.1327 34.6491 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M36.2923 27.8214H35.2755C35.2832 27.9464 35.3265 28.044 35.4046 28.1141C35.4827 28.1843 35.5775 28.2193 35.6888 28.2193C35.7801 28.2193 35.8561 28.198 35.9173 28.1558C35.978 28.1136 36.0214 28.0567 36.0459 27.986H36.2734C36.2392 28.108 36.1714 28.2066 36.0693 28.2834C35.9673 28.3596 35.8403 28.3977 35.6888 28.3977C35.5684 28.3977 35.4602 28.3708 35.3648 28.3169C35.2694 28.263 35.1949 28.1863 35.1408 28.0867C35.0868 27.987 35.0597 27.8722 35.0597 27.7411C35.0597 27.6099 35.0863 27.4951 35.1388 27.3965C35.1914 27.2979 35.2653 27.2216 35.3602 27.1688C35.4551 27.1159 35.5648 27.089 35.6888 27.089C35.8127 27.089 35.9163 27.1154 36.0091 27.1677C36.102 27.2201 36.1734 27.2923 36.2239 27.3838C36.2744 27.4757 36.2994 27.5794 36.2994 27.6948C36.2994 27.735 36.2969 27.7771 36.2923 27.8219V27.8214ZM36.0275 27.4437C35.9918 27.3858 35.9438 27.3421 35.8826 27.3121C35.8219 27.2821 35.7541 27.2669 35.6796 27.2669C35.573 27.2669 35.4821 27.3009 35.4066 27.3685C35.3311 27.4361 35.2888 27.5301 35.2781 27.6506H36.0811C36.0811 27.5708 36.0632 27.5017 36.0275 27.4437Z" fill="#03045E"/>
                            <path d="M36.5774 27.3975C36.63 27.2994 36.7029 27.2237 36.7968 27.1698C36.8902 27.1159 36.9973 27.089 37.1182 27.089C37.2743 27.089 37.4034 27.1271 37.5044 27.2023C37.6059 27.278 37.6728 27.3827 37.7054 27.5164H37.4779C37.4564 27.4392 37.4141 27.3787 37.3514 27.3339C37.2886 27.2892 37.2111 27.2669 37.1182 27.2669C36.9978 27.2669 36.9004 27.308 36.8259 27.3904C36.7514 27.4727 36.7142 27.5896 36.7142 27.7405C36.7142 27.8915 36.7514 28.0109 36.8259 28.0943C36.9004 28.1776 36.9978 28.2193 37.1182 28.2193C37.2111 28.2193 37.2886 28.198 37.3503 28.1548C37.4121 28.1116 37.4544 28.0501 37.4779 27.9698H37.7054C37.6712 28.0994 37.6034 28.2031 37.5013 28.2808C37.3993 28.3586 37.2713 28.3977 37.1182 28.3977C36.9978 28.3977 36.8902 28.3708 36.7968 28.3169C36.7029 28.263 36.63 28.1868 36.5774 28.0882C36.5249 27.9896 36.4984 27.8737 36.4984 27.7416C36.4984 27.6094 36.5249 27.4961 36.5774 27.3985V27.3975Z" fill="#03045E"/>
                            <path d="M38.2441 27.2826V28.0292C38.2441 28.0907 38.2574 28.1344 38.2834 28.1599C38.3099 28.1853 38.3554 28.198 38.4207 28.198H38.5762V28.3759H38.386C38.2681 28.3759 38.1804 28.3489 38.1212 28.2951C38.0625 28.2412 38.0329 28.1522 38.0329 28.0292V27.2826H37.8682V27.1093H38.0329V26.7901H38.2441V27.1093H38.5757V27.2826H38.2441Z" fill="#03045E"/>
                            <path d="M39.9843 27.8214H38.9675C38.9752 27.9464 39.0186 28.044 39.0966 28.1141C39.1747 28.1843 39.2696 28.2193 39.3808 28.2193C39.4721 28.2193 39.5481 28.198 39.6093 28.1558C39.67 28.1136 39.7134 28.0567 39.7379 27.986H39.9654C39.9312 28.108 39.8634 28.2066 39.7613 28.2834C39.6593 28.3596 39.5323 28.3977 39.3808 28.3977C39.2604 28.3977 39.1522 28.3708 39.0568 28.3169C38.9614 28.263 38.8869 28.1863 38.8329 28.0867C38.7788 27.987 38.7517 27.8722 38.7517 27.7411C38.7517 27.6099 38.7783 27.4951 38.8308 27.3965C38.8834 27.2979 38.9573 27.2216 39.0522 27.1688C39.1471 27.1159 39.2568 27.089 39.3808 27.089C39.5047 27.089 39.6083 27.1154 39.7011 27.1677C39.794 27.2201 39.8654 27.2923 39.9159 27.3838C39.9664 27.4757 39.9914 27.5794 39.9914 27.6948C39.9914 27.735 39.9889 27.7771 39.9843 27.8219V27.8214ZM39.7195 27.4437C39.6838 27.3858 39.6358 27.3421 39.5746 27.3121C39.5139 27.2821 39.4461 27.2669 39.3716 27.2669C39.265 27.2669 39.1742 27.3009 39.0987 27.3685C39.0231 27.4361 38.9808 27.5301 38.9701 27.6506H39.7731C39.7731 27.5708 39.7552 27.5017 39.7195 27.4437Z" fill="#03045E"/>
                            <path d="M40.27 27.3975C40.3225 27.2994 40.395 27.2237 40.4868 27.1698C40.5791 27.1159 40.6822 27.089 40.797 27.089C40.8959 27.089 40.9878 27.1118 41.073 27.1571C41.1582 27.2028 41.2229 27.2623 41.2683 27.3365V26.6661H41.4816V28.3769H41.2683V28.139C41.2265 28.2147 41.1648 28.2762 41.0826 28.325C41.001 28.3738 40.9051 28.3977 40.7949 28.3977C40.6847 28.3977 40.5796 28.3698 40.4873 28.3144C40.3955 28.259 40.323 28.1812 40.2705 28.0811C40.2179 27.981 40.1914 27.8666 40.1914 27.739C40.1914 27.6115 40.2179 27.4961 40.2705 27.398L40.27 27.3975ZM41.2097 27.491C41.1709 27.4198 41.1189 27.3655 41.0531 27.3278C40.9872 27.2902 40.9153 27.2714 40.8362 27.2714C40.7572 27.2714 40.6852 27.2897 40.6204 27.3268C40.5557 27.3639 40.5036 27.4178 40.4648 27.4885C40.4261 27.5591 40.4067 27.6425 40.4067 27.738C40.4067 27.8336 40.4261 27.9195 40.4648 27.9911C40.5036 28.0628 40.5551 28.1177 40.6204 28.1553C40.6857 28.1929 40.7577 28.2117 40.8362 28.2117C40.9148 28.2117 40.9878 28.1929 41.0531 28.1553C41.1184 28.1177 41.1709 28.0628 41.2097 27.9911C41.2484 27.9195 41.2678 27.8356 41.2678 27.74C41.2678 27.6445 41.2484 27.5611 41.2097 27.4905V27.491Z" fill="#03045E"/>
                    </svg>
                    <span class="content">
                        <span>Data Analytics, Machine Learning & Generative AI Course</span>
                        <span class="duration">6 months</span>
                    </span>
                </a>
            </div>
            <div class="nsh-ach-right newanalyticscourse d-none">
                <div style="width: 100%; display: flex; flex-wrap: wrap; gap: 1rem;">
                    <a href="http://localhost/TIA/data-analyst-certification-course">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="29" viewBox="0 0 48 29" fill="none">
                                <path d="M20.3044 5.93391C20.5931 5.53086 20.8533 5.12833 21.1523 4.75578C23.1934 2.21502 25.835 0.672469 29.0587 0.173872C32.7083 -0.390797 36.0443 0.414276 38.9761 2.67143C41.4075 4.54282 42.8967 7.01039 43.4186 10.035C43.6425 11.3321 43.617 12.6337 43.3696 13.9262C43.3308 14.1285 43.3885 14.1915 43.5548 14.2657C45.2684 15.0342 46.5612 16.2438 47.3478 17.9511C48.8717 21.2572 47.6498 25.1225 44.4685 27.0386C43.9624 27.3436 43.3977 27.555 42.8518 27.7903C42.6003 27.8986 42.4391 27.8269 42.3697 27.6353C42.2978 27.4356 42.3947 27.2999 42.6646 27.2023C45.0241 26.3484 46.6198 24.7652 47.2075 22.3078C47.832 19.6969 46.7234 17.0032 44.4501 15.4835C43.9716 15.1633 43.4313 14.9336 42.913 14.6743C42.6819 14.5585 42.6197 14.4721 42.6758 14.221C43.6961 9.68075 41.7045 5.22642 37.965 2.70192C36.1749 1.4933 34.199 0.774628 32.039 0.667386C27.1742 0.425966 23.3766 2.29837 20.7615 6.43708C20.6064 6.68206 20.4722 6.72678 20.1983 6.58651C18.7535 5.84801 17.2154 5.55526 15.6053 5.68334C13.6387 5.83988 11.9128 6.5799 10.4701 7.92931C9.11613 9.19486 8.30396 10.7496 8.05449 12.5844C8.00092 12.9803 8.00603 13.3849 7.99582 13.7859C7.98715 14.1219 7.91114 14.1966 7.56882 14.2083C5.02977 14.2967 3.007 15.3427 1.61835 17.4814C0.0123727 19.9561 0.390909 23.2923 2.46674 25.3985C3.37278 26.3179 4.43901 26.9593 5.6843 27.3065C5.71389 27.3146 5.74246 27.3278 5.77256 27.3354C5.96999 27.3863 6.0608 27.5143 6.02815 27.7121C5.99856 27.8925 5.82255 27.9992 5.61135 27.9255C5.0864 27.7431 4.54461 27.5835 4.05486 27.3283C1.9474 26.23 0.573035 24.539 0.135321 22.1909C-0.323821 19.7279 0.39397 17.5887 2.1586 15.8194C3.51817 14.4558 5.19455 13.7432 7.11886 13.6096C7.25405 13.6004 7.3668 13.6055 7.37547 13.4047C7.51934 10.1046 9.08858 7.6574 11.9807 6.10875C14.5799 4.71715 17.2883 4.68259 20.0126 5.80532C20.1115 5.84598 20.2085 5.89121 20.3049 5.9334L20.3044 5.93391Z" fill="#03045E"/>
                                <path d="M10.5676 23.8793C12.3516 23.8555 14.1351 23.8351 15.9191 23.8072C17.6639 23.7802 19.4081 23.7472 21.1528 23.7152C22.7808 23.6852 24.4092 23.6547 26.0371 23.6222C27.541 23.5927 29.045 23.5622 30.5489 23.5292C31.8743 23.5002 33.2002 23.4727 34.5256 23.4351C35.6102 23.4041 36.6948 23.3619 37.7789 23.3187C38.1462 23.304 38.361 22.9533 38.2268 22.6001C37.8187 21.5261 37.408 20.4527 36.9988 19.3788C36.9662 19.2934 36.9381 19.2065 36.8943 19.0825C37.2259 19.0825 37.5319 19.0738 37.8365 19.0906C37.8875 19.0931 37.9523 19.1983 37.9763 19.268C38.2288 19.9963 38.4737 20.7272 38.7221 21.4575C38.7415 21.5144 38.767 21.5693 38.8104 21.6751C38.8609 21.5393 38.8976 21.4504 38.9283 21.3594C39.1594 20.6646 39.395 19.9709 39.6159 19.2725C39.6654 19.1165 39.7389 19.0682 39.895 19.0758C40.1419 19.088 40.3899 19.0789 40.6664 19.0789C40.6419 19.1633 40.6271 19.2268 40.6046 19.2878C40.119 20.6128 39.6348 21.9383 39.1451 23.2623C38.863 24.0257 38.4946 24.2819 37.6712 24.2738C35.8408 24.2555 34.0103 24.229 32.1804 24.2046C31.1336 24.1909 30.0867 24.1736 29.0399 24.1589C26.8916 24.1279 24.7438 24.0969 22.5956 24.0659C21.5564 24.0506 20.5172 24.0349 19.4785 24.0196C17.3226 23.9886 15.1672 23.9546 13.0112 23.9281C12.1975 23.9185 11.3833 23.9266 10.5691 23.9266L10.5681 23.8788L10.5676 23.8793Z" fill="#03045E"/>
                                <path d="M36.3111 22.751H35.3827C35.3827 22.38 35.3862 22.0257 35.3816 21.6715C35.3755 21.1932 35.3663 20.715 35.3521 20.2367C35.3454 20.0136 35.2648 19.8276 35.0301 19.7518C34.7837 19.6725 34.57 19.7437 34.3873 19.9109C34.1705 20.1096 34.1251 20.3785 34.12 20.6519C34.1093 21.2466 34.1088 21.8413 34.1057 22.4359C34.1037 22.7683 34.1057 22.7683 33.7604 22.7683C33.6206 22.7683 33.4803 22.7607 33.3415 22.7703C33.1849 22.781 33.1436 22.7124 33.1451 22.566C33.1517 22.0329 33.1502 21.4997 33.1461 20.9671C33.1446 20.7434 33.1354 20.5198 33.1206 20.2967C33.0972 19.9465 32.9829 19.7793 32.7462 19.7305C32.4498 19.6695 32.0865 19.8159 31.9922 20.1C31.9013 20.3724 31.8646 20.6707 31.8534 20.9594C31.8325 21.4916 31.8478 22.0252 31.8463 22.5584C31.8463 22.6184 31.836 22.6783 31.8279 22.7663C31.5488 22.7663 31.279 22.7719 31.0096 22.7602C30.9678 22.7581 30.8953 22.6748 30.8953 22.629C30.8917 21.4707 30.8963 20.3119 30.9004 19.1536C30.9004 19.1399 30.9127 19.1257 30.9264 19.0957H31.7779C31.7845 19.2365 31.7906 19.365 31.7988 19.5399C32.1304 19.2151 32.4727 18.992 32.9308 18.9905C33.3895 18.9894 33.7415 19.1607 33.9925 19.5572C34.1037 19.4581 34.1955 19.3671 34.2965 19.2873C34.7087 18.961 35.1715 18.9096 35.6561 19.0758C36.0418 19.2085 36.2943 19.4952 36.3045 19.8997C36.328 20.8405 36.3122 21.7818 36.3122 22.75L36.3111 22.751Z" fill="#03045E"/>
                                <path d="M8.89734 22.7683C9.12079 22.1975 9.32587 21.6751 9.52994 21.1521C9.96255 20.0466 10.3977 18.9422 10.8242 17.8347C10.8773 17.6975 10.941 17.6431 11.091 17.6512C11.3308 17.6644 11.5726 17.6436 11.8114 17.6614C11.8894 17.667 12.0042 17.7305 12.0312 17.7961C12.6893 19.4098 13.3383 21.0275 13.9877 22.6443C13.9959 22.6651 13.9953 22.6895 14.0071 22.7683C13.7581 22.7683 13.5143 22.7485 13.2755 22.7734C13.0061 22.8018 12.8653 22.7119 12.7949 22.4481C12.7317 22.2113 12.6169 21.9886 12.5343 21.7564C12.4929 21.64 12.4343 21.5831 12.3006 21.5841C11.7267 21.5902 11.1527 21.5897 10.5788 21.5841C10.4452 21.5826 10.388 21.64 10.3477 21.7589C10.2544 22.0364 10.1416 22.3073 10.0503 22.5853C10.008 22.7139 9.94775 22.7754 9.80389 22.7703C9.51871 22.7612 9.23302 22.7678 8.89734 22.7678V22.7683ZM12.1425 20.7475C11.9032 20.1071 11.6772 19.5023 11.4512 18.898C11.4333 18.9 11.4155 18.902 11.3976 18.9041C11.1762 19.5109 10.9553 20.1183 10.7263 20.7475H12.1425Z" fill="#03045E"/>
                                <path d="M33.4303 14.5153C33.4303 15.79 32.5584 16.6733 31.3004 16.6728C29.9806 16.6723 29.1557 15.8357 29.1547 14.4975C29.1541 13.2452 30.0332 12.3684 31.2912 12.3664C32.5523 12.3644 33.4303 13.2467 33.4303 14.5158V14.5153ZM32.36 14.5153C32.3008 14.284 32.2697 14.035 32.1743 13.8144C32.0141 13.4439 31.7034 13.2416 31.2963 13.2421C30.879 13.2426 30.5673 13.4546 30.4025 13.8317C30.2066 14.281 30.203 14.7506 30.3984 15.2009C30.5607 15.575 30.855 15.7951 31.2825 15.7996C31.7192 15.8042 32.0243 15.5907 32.187 15.2019C32.2743 14.9936 32.3008 14.7598 32.36 14.5148V14.5153Z" fill="#03045E"/>
                                <path d="M25.0918 22.2499C25.0076 22.3221 24.9729 22.348 24.9423 22.3785C24.2638 23.0616 23.2659 22.9828 22.7134 22.2016C22.2487 21.5454 22.2359 20.285 22.6869 19.6227C23.1664 18.9188 24.1521 18.7765 24.8117 19.3157C24.8632 19.3579 24.9153 19.4001 25.003 19.4713V17.6695H25.9621V22.7658C25.7065 22.7658 25.4601 22.7719 25.2147 22.7597C25.1719 22.7576 25.1066 22.6829 25.0979 22.6341C25.0795 22.5304 25.0923 22.4217 25.0923 22.2494L25.0918 22.2499ZM23.3256 20.8674C23.3343 20.87 23.343 20.8725 23.3516 20.8746C23.3598 21.0052 23.3608 21.1368 23.3787 21.2664C23.3924 21.3645 23.42 21.4626 23.4542 21.5561C23.5725 21.8763 23.8531 22.0817 24.1725 22.0908C24.4622 22.0989 24.7775 21.8966 24.8949 21.5932C25.0709 21.1373 25.0668 20.6687 24.8974 20.2138C24.779 19.8962 24.5286 19.7208 24.1811 19.7223C23.8337 19.7244 23.5868 19.9068 23.4659 20.2189C23.3868 20.4227 23.37 20.6499 23.3256 20.8669V20.8674Z" fill="#03045E"/>
                                <path d="M17.7062 16.5885H16.7834V11.5191H17.7231V13.3631C17.8231 13.2782 17.8771 13.2319 17.9312 13.1862C18.3807 12.8076 18.8857 12.7313 19.4326 12.9265C19.8086 13.0607 20.0647 13.3412 20.0861 13.7275C20.1397 14.6734 20.1438 15.6218 20.1682 16.5859H19.176C19.176 16.4187 19.1775 16.2662 19.176 16.1137C19.1683 15.4652 19.1688 14.8167 19.1474 14.1687C19.1352 13.8012 18.9837 13.6279 18.6852 13.5679C18.372 13.5049 17.9919 13.6548 17.8817 13.9425C17.7904 14.1814 17.744 14.4487 17.7348 14.7059C17.7134 15.3077 17.7261 15.911 17.7246 16.5137C17.7246 16.5285 17.7174 16.5437 17.7067 16.5879L17.7062 16.5885Z" fill="#03045E"/>
                                <path d="M21.8084 22.7673C21.4865 22.7673 21.2238 22.7734 20.9615 22.7622C20.9105 22.7602 20.8457 22.7002 20.8161 22.6504C20.7707 22.5736 20.7503 22.4822 20.7116 22.3754C20.6335 22.4349 20.5748 22.4806 20.5146 22.5249C20.0871 22.8425 19.6101 22.9127 19.1041 22.781C18.6597 22.6656 18.3679 22.3038 18.3414 21.8672C18.3087 21.332 18.5153 20.9564 18.9959 20.7917C19.3739 20.6621 19.7734 20.5935 20.1631 20.4974C20.2305 20.4807 20.3004 20.4695 20.3647 20.4441C20.4764 20.3998 20.6478 20.4283 20.6417 20.2311C20.635 20.0253 20.5784 19.8499 20.3611 19.7716C19.9035 19.6065 19.5622 19.7325 19.2964 20.1788C19.1602 20.1544 19.0173 20.1284 18.8745 20.103C18.7383 20.0786 18.6021 20.0547 18.4398 20.0263C18.5607 19.5887 18.7842 19.2507 19.2168 19.1104C19.7759 18.929 20.3499 18.9366 20.91 19.1028C21.3692 19.239 21.5676 19.5475 21.5865 20.0944C21.5982 20.4415 21.586 20.7892 21.5895 21.1363C21.5952 21.6725 21.5283 22.2169 21.8084 22.7678V22.7673ZM20.6177 20.9889C20.2677 21.0799 19.9254 21.1465 19.5994 21.2593C19.2724 21.3726 19.2046 21.7416 19.4372 21.9947C19.5928 22.164 19.8693 22.2133 20.1269 22.1177C20.5636 21.9561 20.7044 21.6491 20.6177 20.9889Z" fill="#03045E"/>
                                <path d="M27.7022 21.1922C27.6747 21.644 27.9083 22.0029 28.269 22.0994C28.6629 22.2052 28.8827 22.0822 29.145 21.6024C29.4434 21.6532 29.7454 21.705 30.0821 21.763C29.9265 22.1935 29.6771 22.506 29.2776 22.6885C28.0104 23.2669 26.7146 22.4791 26.6942 21.0972C26.6886 20.7277 26.7401 20.3358 26.8656 19.9902C27.1278 19.268 27.8099 18.9183 28.6476 19.0006C29.3587 19.0708 29.9056 19.5699 30.0597 20.3058C30.1051 20.5239 30.1163 20.749 30.1464 20.9701C30.1699 21.1434 30.0985 21.1978 29.9245 21.1953C29.273 21.1866 28.6215 21.1917 27.9701 21.1917C27.8859 21.1917 27.8022 21.1917 27.7022 21.1917V21.1922ZM27.714 20.5823H29.1674C29.1368 20.0075 28.8246 19.6827 28.367 19.7239C27.9578 19.761 27.6681 20.1366 27.714 20.5823Z" fill="#03045E"/>
                                <path d="M24.3602 15.0393H21.8977C21.8385 15.4327 22.0727 15.8179 22.4226 15.9282C22.82 16.0533 23.0797 15.9186 23.3297 15.4444C23.6261 15.4932 23.9281 15.543 24.2684 15.5989C24.1271 15.9963 23.9092 16.2881 23.5618 16.4756C22.3068 17.1541 20.9172 16.3562 20.8666 14.9265C20.8473 14.3831 20.9223 13.8571 21.2829 13.42C21.7482 12.8563 22.3645 12.7278 23.0481 12.8818C23.7052 13.0297 24.0863 13.4734 24.2347 14.1107C24.3026 14.4009 24.3163 14.7033 24.3597 15.0393H24.3602ZM21.9053 14.4502C22.3481 14.4502 22.7956 14.4538 23.243 14.4446C23.2833 14.4436 23.3542 14.3638 23.3557 14.3186C23.369 13.8927 22.9807 13.5257 22.5639 13.5593C22.1431 13.5933 21.837 13.9923 21.9053 14.4502Z" fill="#03045E"/>
                                <path d="M14.5667 16.5864H13.5515V12.3623H12.0353V11.5201H16.0783V12.3456H14.5667V16.5864Z" fill="#03045E"/>
                                <path d="M35.9515 16.5839H34.9245V12.3572H33.4068C33.4068 12.0955 33.3976 11.852 33.4155 11.6106C33.4186 11.5694 33.5349 11.5064 33.6002 11.5054C34.0348 11.4958 34.4695 11.5003 34.9041 11.5003C35.6648 11.5003 36.4254 11.5003 37.1861 11.5003H37.4559V12.3476H35.952V16.5844L35.9515 16.5839Z" fill="#03045E"/>
                                <path d="M16.9338 21.3594C17.2486 21.4143 17.5506 21.4662 17.8705 21.5221C17.7251 22.1177 17.4348 22.5584 16.84 22.7464C15.9885 23.0148 15.1233 22.7642 14.6968 22.1269C14.2703 21.489 14.2581 20.3277 14.7065 19.7056C14.855 19.4997 15.0641 19.3081 15.2886 19.1922C15.8477 18.903 16.4426 18.9163 17.0211 19.147C17.4557 19.3203 17.6802 19.6873 17.822 20.1472C17.5119 20.2057 17.2109 20.2621 16.8951 20.3216C16.8767 20.2804 16.8583 20.2418 16.8425 20.2021C16.7216 19.8921 16.5043 19.7274 16.1931 19.7376C15.8768 19.7478 15.6059 19.8723 15.4927 20.1828C15.3182 20.6626 15.3274 21.1556 15.511 21.6308C15.6263 21.9292 15.9248 22.0776 16.2676 22.0583C16.564 22.041 16.7553 21.8834 16.868 21.5632C16.8884 21.5058 16.9063 21.4468 16.9344 21.3599L16.9338 21.3594Z" fill="#03045E"/>
                                <path d="M27.1166 14.0279C27.1166 13.2635 27.1217 12.4985 27.113 11.7341C27.111 11.5522 27.1666 11.4841 27.3513 11.4973C27.5441 11.511 27.7395 11.5085 27.9328 11.4978C28.0961 11.4886 28.1481 11.5501 28.1466 11.7113C28.14 12.4528 28.1435 13.1943 28.1435 13.9359C28.1435 14.747 28.1389 15.5577 28.1471 16.3689C28.1491 16.5508 28.0935 16.6189 27.9089 16.6057C27.716 16.592 27.5206 16.5945 27.3273 16.6052C27.164 16.6144 27.112 16.5529 27.1135 16.3918C27.1196 15.604 27.1166 14.8162 27.1166 14.0284V14.0279Z" fill="#03045E"/>
                                <path d="M7.26688 27.1454C7.3383 27.0198 7.43523 26.9212 7.55818 26.8506C7.68112 26.7799 7.81734 26.7444 7.96783 26.7444C8.14435 26.7444 8.29842 26.7866 8.42953 26.8714C8.56115 26.9563 8.65706 27.0763 8.71725 27.2323H8.46422C8.41932 27.1352 8.35453 27.0605 8.27036 27.0082C8.18618 26.9558 8.08517 26.9299 7.96732 26.9299C7.84948 26.9299 7.75306 26.9563 7.66327 27.0082C7.57348 27.0605 7.50308 27.1352 7.45207 27.2313C7.40105 27.3278 7.37503 27.4407 7.37503 27.5698C7.37503 27.6989 7.40054 27.8097 7.45207 27.9062C7.50308 28.0028 7.57348 28.077 7.66327 28.1294C7.75306 28.1817 7.85458 28.2076 7.96732 28.2076C8.08007 28.2076 8.18567 28.1817 8.27036 28.1304C8.35504 28.079 8.41932 28.0043 8.46422 27.9073H8.71725C8.65706 28.0613 8.56115 28.1807 8.42953 28.2646C8.29791 28.3484 8.14435 28.3906 7.96783 28.3906C7.81785 28.3906 7.68112 28.3555 7.55818 28.2854C7.43523 28.2153 7.3383 28.1177 7.26688 27.9926C7.19597 27.8676 7.16026 27.7268 7.16026 27.5698C7.16026 27.4127 7.19597 27.2714 7.26688 27.1454Z" fill="#03045E"/>
                                <path d="M9.2621 28.3164C9.16517 28.2625 9.08916 28.1858 9.03457 28.0862C8.97947 27.9865 8.95244 27.8717 8.95244 27.7405C8.95244 27.6094 8.9805 27.4966 9.03712 27.3975C9.09375 27.2979 9.17078 27.2216 9.26822 27.1688C9.36566 27.1154 9.47484 27.089 9.59523 27.089C9.71563 27.089 9.82532 27.1154 9.92276 27.1688C10.0202 27.2221 10.0972 27.2979 10.1539 27.3965C10.2105 27.4951 10.2385 27.6099 10.2385 27.7411C10.2385 27.8722 10.2095 27.987 10.1513 28.0867C10.0931 28.1858 10.0146 28.263 9.91561 28.3169C9.81664 28.3708 9.70645 28.3977 9.58605 28.3977C9.46566 28.3977 9.35903 28.3708 9.26261 28.3169L9.2621 28.3164ZM9.79879 28.1588C9.86511 28.1233 9.91919 28.0704 9.96 27.9992C10.0008 27.9286 10.0212 27.8422 10.0212 27.74C10.0212 27.6379 10.0013 27.552 9.96102 27.4813C9.92072 27.4107 9.86817 27.3578 9.80338 27.3228C9.73859 27.2882 9.66768 27.2709 9.59217 27.2709C9.51667 27.2709 9.44423 27.2882 9.37995 27.3228C9.31567 27.3573 9.26414 27.4102 9.22537 27.4813C9.1866 27.552 9.16721 27.6384 9.16721 27.74C9.16721 27.8417 9.1866 27.9306 9.22435 28.0013C9.26261 28.0719 9.31312 28.1248 9.37638 28.1599C9.43964 28.1944 9.50953 28.2117 9.58503 28.2117C9.66054 28.2117 9.73196 28.1939 9.79879 28.1583V28.1588Z" fill="#03045E"/>
                                <path d="M11.5114 27.2262C11.6073 27.3197 11.6553 27.4539 11.6553 27.6297V28.3764H11.4461V27.6597C11.4461 27.5332 11.4145 27.4366 11.3507 27.3695C11.2874 27.3024 11.2007 27.2689 11.091 27.2689C10.9813 27.2689 10.891 27.3035 10.8252 27.3731C10.7594 27.4422 10.7268 27.5433 10.7268 27.676V28.3764H10.5156V27.1093H10.7268V27.2897C10.7686 27.2252 10.8252 27.1749 10.8977 27.1393C10.9696 27.1037 11.0487 27.0859 11.1354 27.0859C11.2905 27.0859 11.4155 27.1327 11.5114 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M12.997 27.2262C13.0929 27.3197 13.1408 27.4539 13.1408 27.6297V28.3764H12.9317V27.6597C12.9317 27.5332 12.9 27.4366 12.8363 27.3695C12.773 27.3024 12.6863 27.2689 12.5766 27.2689C12.4669 27.2689 12.3766 27.3035 12.3108 27.3731C12.245 27.4422 12.2123 27.5433 12.2123 27.676V28.3764H12.0011V27.1093H12.2123V27.2897C12.2542 27.2252 12.3108 27.1749 12.3832 27.1393C12.4552 27.1037 12.5343 27.0859 12.621 27.0859C12.7761 27.0859 12.9011 27.1327 12.997 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M14.6397 27.8214H13.6229C13.6306 27.9464 13.6739 28.044 13.752 28.1141C13.8301 28.1843 13.9249 28.2193 14.0362 28.2193C14.1275 28.2193 14.2035 28.198 14.2647 28.1558C14.3254 28.1136 14.3688 28.0567 14.3933 27.986H14.6208C14.5866 28.108 14.5188 28.2066 14.4167 28.2834C14.3147 28.3596 14.1877 28.3977 14.0362 28.3977C13.9158 28.3977 13.8076 28.3708 13.7122 28.3169C13.6168 28.263 13.5423 28.1863 13.4882 28.0867C13.4342 27.987 13.4071 27.8722 13.4071 27.7411C13.4071 27.6099 13.4337 27.4951 13.4862 27.3965C13.5388 27.2979 13.6127 27.2216 13.7076 27.1688C13.8025 27.1159 13.9122 27.089 14.0362 27.089C14.1601 27.089 14.2637 27.1154 14.3565 27.1677C14.4494 27.2201 14.5208 27.2923 14.5713 27.3838C14.6218 27.4757 14.6468 27.5794 14.6468 27.6948C14.6468 27.735 14.6443 27.7771 14.6397 27.8219V27.8214ZM14.3754 27.4437C14.3397 27.3858 14.2917 27.3421 14.2305 27.3121C14.1698 27.2821 14.102 27.2669 14.0275 27.2669C13.9209 27.2669 13.83 27.3009 13.7545 27.3685C13.679 27.4361 13.6367 27.5301 13.626 27.6506H14.429C14.429 27.5708 14.4111 27.5017 14.3754 27.4437Z" fill="#03045E"/>
                                <path d="M14.9254 27.3975C14.9779 27.2994 15.0509 27.2237 15.1447 27.1698C15.2381 27.1159 15.3452 27.089 15.4661 27.089C15.6222 27.089 15.7513 27.1271 15.8523 27.2023C15.9538 27.278 16.0207 27.3827 16.0533 27.5164H15.8258C15.8044 27.4392 15.762 27.3787 15.6993 27.3339C15.6365 27.2892 15.559 27.2669 15.4661 27.2669C15.3457 27.2669 15.2483 27.308 15.1738 27.3904C15.0993 27.4727 15.0621 27.5896 15.0621 27.7405C15.0621 27.8915 15.0993 28.0109 15.1738 28.0943C15.2483 28.1776 15.3457 28.2193 15.4661 28.2193C15.559 28.2193 15.6365 28.198 15.6982 28.1548C15.76 28.1116 15.8023 28.0501 15.8258 27.9698H16.0533C16.0191 28.0994 15.9513 28.2031 15.8493 28.2808C15.7472 28.3586 15.6192 28.3977 15.4661 28.3977C15.3457 28.3977 15.2381 28.3708 15.1447 28.3169C15.0509 28.263 14.9779 28.1868 14.9254 28.0882C14.8728 27.9896 14.8463 27.8737 14.8463 27.7416C14.8463 27.6094 14.8728 27.4961 14.9254 27.3985V27.3975Z" fill="#03045E"/>
                                <path d="M16.5915 27.2826V28.0292C16.5915 28.0907 16.6048 28.1344 16.6308 28.1599C16.6573 28.1853 16.7027 28.198 16.768 28.198H16.9236V28.3759H16.7334C16.6155 28.3759 16.5278 28.3489 16.4686 28.2951C16.4099 28.2412 16.3803 28.1522 16.3803 28.0292V27.2826H16.2155V27.1093H16.3803V26.7901H16.5915V27.1093H16.9231V27.2826H16.5915Z" fill="#03045E"/>
                                <path d="M17.1859 26.8618C17.1583 26.8338 17.144 26.8003 17.144 26.7601C17.144 26.72 17.1583 26.6859 17.1859 26.6585C17.2139 26.6305 17.2476 26.6168 17.2879 26.6168C17.3282 26.6168 17.3593 26.6305 17.3864 26.6585C17.4134 26.6864 17.4272 26.72 17.4272 26.7601C17.4272 26.8003 17.4134 26.8343 17.3864 26.8618C17.3593 26.8897 17.3267 26.9035 17.2879 26.9035C17.2491 26.9035 17.2134 26.8897 17.1859 26.8618ZM17.3899 27.1093V28.3764H17.1787V27.1093H17.3899Z" fill="#03045E"/>
                                <path d="M18.7454 27.2262C18.8413 27.3197 18.8893 27.4539 18.8893 27.6297V28.3764H18.6801V27.6597C18.6801 27.5332 18.6485 27.4366 18.5847 27.3695C18.5215 27.3024 18.4347 27.2689 18.325 27.2689C18.2154 27.2689 18.1251 27.3035 18.0593 27.3731C17.9934 27.4422 17.9608 27.5433 17.9608 27.676V28.3764H17.7496V27.1093H17.9608V27.2897C18.0026 27.2252 18.0593 27.1749 18.1317 27.1393C18.2036 27.1037 18.2827 27.0859 18.3694 27.0859C18.5245 27.0859 18.6495 27.1327 18.7454 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M20.0484 27.1601C20.131 27.2079 20.1927 27.2679 20.233 27.3406V27.1093H20.4463V28.4038C20.4463 28.5197 20.4218 28.6224 20.3718 28.7123C20.3223 28.8023 20.2519 28.8729 20.1596 28.9238C20.0677 28.9746 19.9606 29 19.8382 29C19.6708 29 19.5316 28.9609 19.4204 28.8821C19.3091 28.8033 19.2433 28.6961 19.2234 28.5609H19.4321C19.455 28.6381 19.5035 28.6996 19.5759 28.7469C19.6489 28.7942 19.7361 28.8175 19.8382 28.8175C19.9545 28.8175 20.0489 28.7814 20.1223 28.7088C20.1958 28.6361 20.2325 28.5344 20.2325 28.4038V28.138C20.1907 28.2117 20.129 28.2737 20.0468 28.323C19.9652 28.3723 19.8693 28.3972 19.7591 28.3972C19.6489 28.3972 19.5438 28.3693 19.4515 28.3139C19.3596 28.2585 19.2872 28.1807 19.2347 28.0806C19.1821 27.9804 19.1556 27.8661 19.1556 27.7385C19.1556 27.6109 19.1821 27.4956 19.2347 27.3975C19.2872 27.2994 19.3596 27.2237 19.4515 27.1698C19.5438 27.1159 19.6464 27.089 19.7591 27.089C19.8718 27.089 19.9652 27.1129 20.0484 27.1606V27.1601ZM20.1749 27.491C20.1361 27.4198 20.0841 27.3655 20.0183 27.3278C19.9524 27.2902 19.8805 27.2714 19.8014 27.2714C19.7224 27.2714 19.6504 27.2897 19.5856 27.3268C19.5209 27.3639 19.4688 27.4178 19.43 27.4885C19.3913 27.5591 19.3719 27.6425 19.3719 27.738C19.3719 27.8336 19.3913 27.9195 19.43 27.9911C19.4688 28.0628 19.5203 28.1177 19.5856 28.1553C19.6509 28.1929 19.7229 28.2117 19.8014 28.2117C19.88 28.2117 19.953 28.1929 20.0183 28.1553C20.0836 28.1177 20.1361 28.0628 20.1749 27.9911C20.2136 27.9195 20.233 27.8356 20.233 27.74C20.233 27.6445 20.2136 27.5611 20.1749 27.4905V27.491Z" fill="#03045E"/>
                                <path d="M22.4191 26.7647V26.936H21.9783V28.3764H21.7671V26.936H21.3238V26.7647H22.4191Z" fill="#03045E"/>
                                <path d="M23.57 27.1474C23.6475 27.1881 23.7077 27.2496 23.7521 27.3314C23.7965 27.4132 23.8184 27.5123 23.8184 27.6297V28.3764H23.6093V27.6597C23.6093 27.5332 23.5776 27.4366 23.5139 27.3695C23.4506 27.3024 23.3639 27.2689 23.2542 27.2689C23.1445 27.2689 23.0542 27.3035 22.9884 27.3731C22.9226 27.4422 22.8899 27.5433 22.8899 27.676V28.3764H22.6787V26.6656H22.8899V27.2897C22.9318 27.2252 22.9894 27.1749 23.0629 27.1393C23.1363 27.1037 23.219 27.0859 23.3098 27.0859C23.4057 27.0859 23.4924 27.1062 23.57 27.1469V27.1474Z" fill="#03045E"/>
                                <path d="M25.3178 27.8214H24.301C24.3087 27.9464 24.352 28.044 24.4301 28.1141C24.5081 28.1843 24.603 28.2193 24.7143 28.2193C24.8056 28.2193 24.8816 28.198 24.9428 28.1558C25.0035 28.1136 25.0469 28.0567 25.0714 27.986H25.2989C25.2647 28.108 25.1969 28.2066 25.0948 28.2834C24.9928 28.3596 24.8658 28.3977 24.7143 28.3977C24.5939 28.3977 24.4857 28.3708 24.3903 28.3169C24.2949 28.263 24.2204 28.1863 24.1663 28.0867C24.1123 27.987 24.0852 27.8722 24.0852 27.7411C24.0852 27.6099 24.1118 27.4951 24.1643 27.3965C24.2169 27.2979 24.2908 27.2216 24.3857 27.1688C24.4806 27.1159 24.5903 27.089 24.7143 27.089C24.8382 27.089 24.9418 27.1154 25.0346 27.1677C25.1275 27.2201 25.1989 27.2923 25.2494 27.3838C25.2999 27.4757 25.3249 27.5794 25.3249 27.6948C25.3249 27.735 25.3224 27.7771 25.3178 27.8219V27.8214ZM25.053 27.4437C25.0173 27.3858 24.9693 27.3421 24.9081 27.3121C24.8474 27.2821 24.7796 27.2669 24.7051 27.2669C24.5984 27.2669 24.5076 27.3009 24.4321 27.3685C24.3566 27.4361 24.3143 27.5301 24.3036 27.6506H25.1066C25.1066 27.5708 25.0887 27.5017 25.053 27.4437Z" fill="#03045E"/>
                                <path d="M26.4289 26.7647V27.7843C26.4289 27.9276 26.4641 28.0338 26.5345 28.1034C26.6049 28.1726 26.7028 28.2076 26.8283 28.2076C26.9538 28.2076 27.0492 28.1731 27.1196 28.1034C27.19 28.0343 27.2252 27.9276 27.2252 27.7843V26.7647H27.4365V27.7822C27.4365 27.9164 27.4094 28.0292 27.3553 28.1207C27.3013 28.2122 27.2283 28.2808 27.136 28.3255C27.0441 28.3703 26.9406 28.3926 26.8263 28.3926C26.712 28.3926 26.6085 28.3703 26.5161 28.3255C26.4243 28.2808 26.3513 28.2127 26.2983 28.1207C26.2452 28.0287 26.2182 27.9159 26.2182 27.7822V26.7647H26.4294H26.4289Z" fill="#03045E"/>
                                <path d="M28.7843 27.2262C28.8802 27.3197 28.9282 27.4539 28.9282 27.6297V28.3764H28.719V27.6597C28.719 27.5332 28.6874 27.4366 28.6236 27.3695C28.5603 27.3024 28.4736 27.2689 28.3639 27.2689C28.2542 27.2689 28.1639 27.3035 28.0981 27.3731C28.0323 27.4422 27.9997 27.5433 27.9997 27.676V28.3764H27.7885V27.1093H27.9997V27.2897C28.0415 27.2252 28.0981 27.1749 28.1706 27.1393C28.2425 27.1037 28.3216 27.0859 28.4083 27.0859C28.5634 27.0859 28.6884 27.1327 28.7843 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M29.274 27.3975C29.3266 27.2994 29.3995 27.2237 29.4934 27.1698C29.5868 27.1159 29.6939 27.089 29.8148 27.089C29.9709 27.089 30.1 27.1271 30.201 27.2023C30.3025 27.278 30.3693 27.3827 30.402 27.5164H30.1745C30.153 27.4392 30.1107 27.3787 30.0479 27.3339C29.9852 27.2892 29.9077 27.2669 29.8148 27.2669C29.6944 27.2669 29.597 27.308 29.5225 27.3904C29.448 27.4727 29.4108 27.5896 29.4108 27.7405C29.4108 27.8915 29.448 28.0109 29.5225 28.0943C29.597 28.1776 29.6944 28.2193 29.8148 28.2193C29.9077 28.2193 29.9852 28.198 30.0469 28.1548C30.1087 28.1116 30.151 28.0501 30.1745 27.9698H30.402C30.3678 28.0994 30.3 28.2031 30.1979 28.2808C30.0959 28.3586 29.9679 28.3977 29.8148 28.3977C29.6944 28.3977 29.5868 28.3708 29.4934 28.3169C29.3995 28.263 29.3266 28.1868 29.274 28.0882C29.2215 27.9896 29.195 27.8737 29.195 27.7416C29.195 27.6094 29.2215 27.4961 29.274 27.3985V27.3975Z" fill="#03045E"/>
                                <path d="M30.9137 28.3164C30.8168 28.2625 30.7407 28.1858 30.6862 28.0862C30.6311 27.9865 30.604 27.8717 30.604 27.7405C30.604 27.6094 30.6321 27.4966 30.6887 27.3975C30.7453 27.2979 30.8224 27.2216 30.9198 27.1688C31.0172 27.1159 31.1264 27.089 31.2468 27.089C31.3672 27.089 31.4769 27.1154 31.5743 27.1688C31.6718 27.2221 31.7488 27.2979 31.8054 27.3965C31.8621 27.4951 31.8901 27.6099 31.8901 27.7411C31.8901 27.8722 31.861 27.987 31.8029 28.0867C31.7447 28.1858 31.6662 28.263 31.5672 28.3169C31.4682 28.3708 31.358 28.3977 31.2376 28.3977C31.1172 28.3977 31.0106 28.3708 30.9142 28.3169L30.9137 28.3164ZM31.4509 28.1588C31.5172 28.1233 31.5713 28.0704 31.6121 27.9992C31.6529 27.9286 31.6733 27.8422 31.6733 27.74C31.6733 27.6379 31.6534 27.552 31.6131 27.4813C31.5728 27.4107 31.5203 27.3578 31.4555 27.3228C31.3907 27.2882 31.3198 27.2709 31.2443 27.2709C31.1688 27.2709 31.0963 27.2882 31.032 27.3228C30.9678 27.3573 30.9162 27.4102 30.8775 27.4813C30.8387 27.552 30.8193 27.6384 30.8193 27.74C30.8193 27.8417 30.8387 27.9306 30.8764 28.0013C30.9147 28.0719 30.9652 28.1248 31.0285 28.1599C31.0917 28.1944 31.1616 28.2117 31.2371 28.2117C31.3126 28.2117 31.384 28.1939 31.4509 28.1583V28.1588Z" fill="#03045E"/>
                                <path d="M33.1635 27.2262C33.2594 27.3197 33.3073 27.4539 33.3073 27.6297V28.3764H33.0982V27.6597C33.0982 27.5332 33.0665 27.4366 33.0028 27.3695C32.9395 27.3024 32.8528 27.2689 32.7431 27.2689C32.6334 27.2689 32.5431 27.3035 32.4773 27.3731C32.4115 27.4422 32.3789 27.5433 32.3789 27.676V28.3764H32.1676V27.1093H32.3789V27.2897C32.4207 27.2252 32.4773 27.1749 32.5498 27.1393C32.6217 27.1037 32.7008 27.0859 32.7875 27.0859C32.9426 27.0859 33.0676 27.1327 33.1635 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M34.6491 27.2262C34.745 27.3197 34.7929 27.4539 34.7929 27.6297V28.3764H34.5838V27.6597C34.5838 27.5332 34.5521 27.4366 34.4884 27.3695C34.4251 27.3024 34.3384 27.2689 34.2287 27.2689C34.119 27.2689 34.0287 27.3035 33.9629 27.3731C33.8971 27.4422 33.8644 27.5433 33.8644 27.676V28.3764H33.6532V27.1093H33.8644V27.2897C33.9063 27.2252 33.9629 27.1749 34.0353 27.1393C34.1073 27.1037 34.1863 27.0859 34.2731 27.0859C34.4282 27.0859 34.5531 27.1327 34.6491 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M36.2923 27.8214H35.2755C35.2832 27.9464 35.3265 28.044 35.4046 28.1141C35.4827 28.1843 35.5775 28.2193 35.6888 28.2193C35.7801 28.2193 35.8561 28.198 35.9173 28.1558C35.978 28.1136 36.0214 28.0567 36.0459 27.986H36.2734C36.2392 28.108 36.1714 28.2066 36.0693 28.2834C35.9673 28.3596 35.8403 28.3977 35.6888 28.3977C35.5684 28.3977 35.4602 28.3708 35.3648 28.3169C35.2694 28.263 35.1949 28.1863 35.1408 28.0867C35.0868 27.987 35.0597 27.8722 35.0597 27.7411C35.0597 27.6099 35.0863 27.4951 35.1388 27.3965C35.1914 27.2979 35.2653 27.2216 35.3602 27.1688C35.4551 27.1159 35.5648 27.089 35.6888 27.089C35.8127 27.089 35.9163 27.1154 36.0091 27.1677C36.102 27.2201 36.1734 27.2923 36.2239 27.3838C36.2744 27.4757 36.2994 27.5794 36.2994 27.6948C36.2994 27.735 36.2969 27.7771 36.2923 27.8219V27.8214ZM36.0275 27.4437C35.9918 27.3858 35.9438 27.3421 35.8826 27.3121C35.8219 27.2821 35.7541 27.2669 35.6796 27.2669C35.573 27.2669 35.4821 27.3009 35.4066 27.3685C35.3311 27.4361 35.2888 27.5301 35.2781 27.6506H36.0811C36.0811 27.5708 36.0632 27.5017 36.0275 27.4437Z" fill="#03045E"/>
                                <path d="M36.5774 27.3975C36.63 27.2994 36.7029 27.2237 36.7968 27.1698C36.8902 27.1159 36.9973 27.089 37.1182 27.089C37.2743 27.089 37.4034 27.1271 37.5044 27.2023C37.6059 27.278 37.6728 27.3827 37.7054 27.5164H37.4779C37.4564 27.4392 37.4141 27.3787 37.3514 27.3339C37.2886 27.2892 37.2111 27.2669 37.1182 27.2669C36.9978 27.2669 36.9004 27.308 36.8259 27.3904C36.7514 27.4727 36.7142 27.5896 36.7142 27.7405C36.7142 27.8915 36.7514 28.0109 36.8259 28.0943C36.9004 28.1776 36.9978 28.2193 37.1182 28.2193C37.2111 28.2193 37.2886 28.198 37.3503 28.1548C37.4121 28.1116 37.4544 28.0501 37.4779 27.9698H37.7054C37.6712 28.0994 37.6034 28.2031 37.5013 28.2808C37.3993 28.3586 37.2713 28.3977 37.1182 28.3977C36.9978 28.3977 36.8902 28.3708 36.7968 28.3169C36.7029 28.263 36.63 28.1868 36.5774 28.0882C36.5249 27.9896 36.4984 27.8737 36.4984 27.7416C36.4984 27.6094 36.5249 27.4961 36.5774 27.3985V27.3975Z" fill="#03045E"/>
                                <path d="M38.2441 27.2826V28.0292C38.2441 28.0907 38.2574 28.1344 38.2834 28.1599C38.3099 28.1853 38.3554 28.198 38.4207 28.198H38.5762V28.3759H38.386C38.2681 28.3759 38.1804 28.3489 38.1212 28.2951C38.0625 28.2412 38.0329 28.1522 38.0329 28.0292V27.2826H37.8682V27.1093H38.0329V26.7901H38.2441V27.1093H38.5757V27.2826H38.2441Z" fill="#03045E"/>
                                <path d="M39.9843 27.8214H38.9675C38.9752 27.9464 39.0186 28.044 39.0966 28.1141C39.1747 28.1843 39.2696 28.2193 39.3808 28.2193C39.4721 28.2193 39.5481 28.198 39.6093 28.1558C39.67 28.1136 39.7134 28.0567 39.7379 27.986H39.9654C39.9312 28.108 39.8634 28.2066 39.7613 28.2834C39.6593 28.3596 39.5323 28.3977 39.3808 28.3977C39.2604 28.3977 39.1522 28.3708 39.0568 28.3169C38.9614 28.263 38.8869 28.1863 38.8329 28.0867C38.7788 27.987 38.7517 27.8722 38.7517 27.7411C38.7517 27.6099 38.7783 27.4951 38.8308 27.3965C38.8834 27.2979 38.9573 27.2216 39.0522 27.1688C39.1471 27.1159 39.2568 27.089 39.3808 27.089C39.5047 27.089 39.6083 27.1154 39.7011 27.1677C39.794 27.2201 39.8654 27.2923 39.9159 27.3838C39.9664 27.4757 39.9914 27.5794 39.9914 27.6948C39.9914 27.735 39.9889 27.7771 39.9843 27.8219V27.8214ZM39.7195 27.4437C39.6838 27.3858 39.6358 27.3421 39.5746 27.3121C39.5139 27.2821 39.4461 27.2669 39.3716 27.2669C39.265 27.2669 39.1742 27.3009 39.0987 27.3685C39.0231 27.4361 38.9808 27.5301 38.9701 27.6506H39.7731C39.7731 27.5708 39.7552 27.5017 39.7195 27.4437Z" fill="#03045E"/>
                                <path d="M40.27 27.3975C40.3225 27.2994 40.395 27.2237 40.4868 27.1698C40.5791 27.1159 40.6822 27.089 40.797 27.089C40.8959 27.089 40.9878 27.1118 41.073 27.1571C41.1582 27.2028 41.2229 27.2623 41.2683 27.3365V26.6661H41.4816V28.3769H41.2683V28.139C41.2265 28.2147 41.1648 28.2762 41.0826 28.325C41.001 28.3738 40.9051 28.3977 40.7949 28.3977C40.6847 28.3977 40.5796 28.3698 40.4873 28.3144C40.3955 28.259 40.323 28.1812 40.2705 28.0811C40.2179 27.981 40.1914 27.8666 40.1914 27.739C40.1914 27.6115 40.2179 27.4961 40.2705 27.398L40.27 27.3975ZM41.2097 27.491C41.1709 27.4198 41.1189 27.3655 41.0531 27.3278C40.9872 27.2902 40.9153 27.2714 40.8362 27.2714C40.7572 27.2714 40.6852 27.2897 40.6204 27.3268C40.5557 27.3639 40.5036 27.4178 40.4648 27.4885C40.4261 27.5591 40.4067 27.6425 40.4067 27.738C40.4067 27.8336 40.4261 27.9195 40.4648 27.9911C40.5036 28.0628 40.5551 28.1177 40.6204 28.1553C40.6857 28.1929 40.7577 28.2117 40.8362 28.2117C40.9148 28.2117 40.9878 28.1929 41.0531 28.1553C41.1184 28.1177 41.1709 28.0628 41.2097 27.9911C41.2484 27.9195 41.2678 27.8356 41.2678 27.74C41.2678 27.6445 41.2484 27.5611 41.2097 27.4905V27.491Z" fill="#03045E"/>
                        </svg>
                        <span class="content">
                            <span>Data Analytics, Data Visualisation & Business Analytics</span>
                            <span class="duration">4 months</span>
                        </span>
                    </a>
                    <a href="http://localhost/TIA/data-analyst-with-genai-and-agenticai-course">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="29" viewBox="0 0 48 29" fill="none">
                                <path d="M20.3044 5.93391C20.5931 5.53086 20.8533 5.12833 21.1523 4.75578C23.1934 2.21502 25.835 0.672469 29.0587 0.173872C32.7083 -0.390797 36.0443 0.414276 38.9761 2.67143C41.4075 4.54282 42.8967 7.01039 43.4186 10.035C43.6425 11.3321 43.617 12.6337 43.3696 13.9262C43.3308 14.1285 43.3885 14.1915 43.5548 14.2657C45.2684 15.0342 46.5612 16.2438 47.3478 17.9511C48.8717 21.2572 47.6498 25.1225 44.4685 27.0386C43.9624 27.3436 43.3977 27.555 42.8518 27.7903C42.6003 27.8986 42.4391 27.8269 42.3697 27.6353C42.2978 27.4356 42.3947 27.2999 42.6646 27.2023C45.0241 26.3484 46.6198 24.7652 47.2075 22.3078C47.832 19.6969 46.7234 17.0032 44.4501 15.4835C43.9716 15.1633 43.4313 14.9336 42.913 14.6743C42.6819 14.5585 42.6197 14.4721 42.6758 14.221C43.6961 9.68075 41.7045 5.22642 37.965 2.70192C36.1749 1.4933 34.199 0.774628 32.039 0.667386C27.1742 0.425966 23.3766 2.29837 20.7615 6.43708C20.6064 6.68206 20.4722 6.72678 20.1983 6.58651C18.7535 5.84801 17.2154 5.55526 15.6053 5.68334C13.6387 5.83988 11.9128 6.5799 10.4701 7.92931C9.11613 9.19486 8.30396 10.7496 8.05449 12.5844C8.00092 12.9803 8.00603 13.3849 7.99582 13.7859C7.98715 14.1219 7.91114 14.1966 7.56882 14.2083C5.02977 14.2967 3.007 15.3427 1.61835 17.4814C0.0123727 19.9561 0.390909 23.2923 2.46674 25.3985C3.37278 26.3179 4.43901 26.9593 5.6843 27.3065C5.71389 27.3146 5.74246 27.3278 5.77256 27.3354C5.96999 27.3863 6.0608 27.5143 6.02815 27.7121C5.99856 27.8925 5.82255 27.9992 5.61135 27.9255C5.0864 27.7431 4.54461 27.5835 4.05486 27.3283C1.9474 26.23 0.573035 24.539 0.135321 22.1909C-0.323821 19.7279 0.39397 17.5887 2.1586 15.8194C3.51817 14.4558 5.19455 13.7432 7.11886 13.6096C7.25405 13.6004 7.3668 13.6055 7.37547 13.4047C7.51934 10.1046 9.08858 7.6574 11.9807 6.10875C14.5799 4.71715 17.2883 4.68259 20.0126 5.80532C20.1115 5.84598 20.2085 5.89121 20.3049 5.9334L20.3044 5.93391Z" fill="#03045E"/>
                                <path d="M10.5676 23.8793C12.3516 23.8555 14.1351 23.8351 15.9191 23.8072C17.6639 23.7802 19.4081 23.7472 21.1528 23.7152C22.7808 23.6852 24.4092 23.6547 26.0371 23.6222C27.541 23.5927 29.045 23.5622 30.5489 23.5292C31.8743 23.5002 33.2002 23.4727 34.5256 23.4351C35.6102 23.4041 36.6948 23.3619 37.7789 23.3187C38.1462 23.304 38.361 22.9533 38.2268 22.6001C37.8187 21.5261 37.408 20.4527 36.9988 19.3788C36.9662 19.2934 36.9381 19.2065 36.8943 19.0825C37.2259 19.0825 37.5319 19.0738 37.8365 19.0906C37.8875 19.0931 37.9523 19.1983 37.9763 19.268C38.2288 19.9963 38.4737 20.7272 38.7221 21.4575C38.7415 21.5144 38.767 21.5693 38.8104 21.6751C38.8609 21.5393 38.8976 21.4504 38.9283 21.3594C39.1594 20.6646 39.395 19.9709 39.6159 19.2725C39.6654 19.1165 39.7389 19.0682 39.895 19.0758C40.1419 19.088 40.3899 19.0789 40.6664 19.0789C40.6419 19.1633 40.6271 19.2268 40.6046 19.2878C40.119 20.6128 39.6348 21.9383 39.1451 23.2623C38.863 24.0257 38.4946 24.2819 37.6712 24.2738C35.8408 24.2555 34.0103 24.229 32.1804 24.2046C31.1336 24.1909 30.0867 24.1736 29.0399 24.1589C26.8916 24.1279 24.7438 24.0969 22.5956 24.0659C21.5564 24.0506 20.5172 24.0349 19.4785 24.0196C17.3226 23.9886 15.1672 23.9546 13.0112 23.9281C12.1975 23.9185 11.3833 23.9266 10.5691 23.9266L10.5681 23.8788L10.5676 23.8793Z" fill="#03045E"/>
                                <path d="M36.3111 22.751H35.3827C35.3827 22.38 35.3862 22.0257 35.3816 21.6715C35.3755 21.1932 35.3663 20.715 35.3521 20.2367C35.3454 20.0136 35.2648 19.8276 35.0301 19.7518C34.7837 19.6725 34.57 19.7437 34.3873 19.9109C34.1705 20.1096 34.1251 20.3785 34.12 20.6519C34.1093 21.2466 34.1088 21.8413 34.1057 22.4359C34.1037 22.7683 34.1057 22.7683 33.7604 22.7683C33.6206 22.7683 33.4803 22.7607 33.3415 22.7703C33.1849 22.781 33.1436 22.7124 33.1451 22.566C33.1517 22.0329 33.1502 21.4997 33.1461 20.9671C33.1446 20.7434 33.1354 20.5198 33.1206 20.2967C33.0972 19.9465 32.9829 19.7793 32.7462 19.7305C32.4498 19.6695 32.0865 19.8159 31.9922 20.1C31.9013 20.3724 31.8646 20.6707 31.8534 20.9594C31.8325 21.4916 31.8478 22.0252 31.8463 22.5584C31.8463 22.6184 31.836 22.6783 31.8279 22.7663C31.5488 22.7663 31.279 22.7719 31.0096 22.7602C30.9678 22.7581 30.8953 22.6748 30.8953 22.629C30.8917 21.4707 30.8963 20.3119 30.9004 19.1536C30.9004 19.1399 30.9127 19.1257 30.9264 19.0957H31.7779C31.7845 19.2365 31.7906 19.365 31.7988 19.5399C32.1304 19.2151 32.4727 18.992 32.9308 18.9905C33.3895 18.9894 33.7415 19.1607 33.9925 19.5572C34.1037 19.4581 34.1955 19.3671 34.2965 19.2873C34.7087 18.961 35.1715 18.9096 35.6561 19.0758C36.0418 19.2085 36.2943 19.4952 36.3045 19.8997C36.328 20.8405 36.3122 21.7818 36.3122 22.75L36.3111 22.751Z" fill="#03045E"/>
                       <path d="M8.89734 22.7683C9.12079 22.1975 9.32587 21.6751 9.52994 21.1521C9.96255 20.0466 10.3977 18.9422 10.8242 17.8347C10.8773 17.6975 10.941 17.6431 11.091 17.6512C11.3308 17.6644 11.5726 17.6436 11.8114 17.6614C11.8894 17.667 12.0042 17.7305 12.0312 17.7961C12.6893 19.4098 13.3383 21.0275 13.9877 22.6443C13.9959 22.6651 13.9953 22.6895 14.0071 22.7683C13.7581 22.7683 13.5143 22.7485 13.2755 22.7734C13.0061 22.8018 12.8653 22.7119 12.7949 22.4481C12.7317 22.2113 12.6169 21.9886 12.5343 21.7564C12.4929 21.64 12.4343 21.5831 12.3006 21.5841C11.7267 21.5902 11.1527 21.5897 10.5788 21.5841C10.4452 21.5826 10.388 21.64 10.3477 21.7589C10.2544 22.0364 10.1416 22.3073 10.0503 22.5853C10.008 22.7139 9.94775 22.7754 9.80389 22.7703C9.51871 22.7612 9.23302 22.7678 8.89734 22.7678V22.7683ZM12.1425 20.7475C11.9032 20.1071 11.6772 19.5023 11.4512 18.898C11.4333 18.9 11.4155 18.902 11.3976 18.9041C11.1762 19.5109 10.9553 20.1183 10.7263 20.7475H12.1425Z" fill="#03045E"/>
                                <path d="M33.4303 14.5153C33.4303 15.79 32.5584 16.6733 31.3004 16.6728C29.9806 16.6723 29.1557 15.8357 29.1547 14.4975C29.1541 13.2452 30.0332 12.3684 31.2912 12.3664C32.5523 12.3644 33.4303 13.2467 33.4303 14.5158V14.5153ZM32.36 14.5153C32.3008 14.284 32.2697 14.035 32.1743 13.8144C32.0141 13.4439 31.7034 13.2416 31.2963 13.2421C30.879 13.2426 30.5673 13.4546 30.4025 13.8317C30.2066 14.281 30.203 14.7506 30.3984 15.2009C30.5607 15.575 30.855 15.7951 31.2825 15.7996C31.7192 15.8042 32.0243 15.5907 32.187 15.2019C32.2743 14.9936 32.3008 14.7598 32.36 14.5148V14.5153Z" fill="#03045E"/>
                                <path d="M25.0918 22.2499C25.0076 22.3221 24.9729 22.348 24.9423 22.3785C24.2638 23.0616 23.2659 22.9828 22.7134 22.2016C22.2487 21.5454 22.2359 20.285 22.6869 19.6227C23.1664 18.9188 24.1521 18.7765 24.8117 19.3157C24.8632 19.3579 24.9153 19.4001 25.003 19.4713V17.6695H25.9621V22.7658C25.7065 22.7658 25.4601 22.7719 25.2147 22.7597C25.1719 22.7576 25.1066 22.6829 25.0979 22.6341C25.0795 22.5304 25.0923 22.4217 25.0923 22.2494L25.0918 22.2499ZM23.3256 20.8674C23.3343 20.87 23.343 20.8725 23.3516 20.8746C23.3598 21.0052 23.3608 21.1368 23.3787 21.2664C23.3924 21.3645 23.42 21.4626 23.4542 21.5561C23.5725 21.8763 23.8531 22.0817 24.1725 22.0908C24.4622 22.0989 24.7775 21.8966 24.8949 21.5932C25.0709 21.1373 25.0668 20.6687 24.8974 20.2138C24.779 19.8962 24.5286 19.7208 24.1811 19.7223C23.8337 19.7244 23.5868 19.9068 23.4659 20.2189C23.3868 20.4227 23.37 20.6499 23.3256 20.8669V20.8674Z" fill="#03045E"/>
                                <path d="M17.7062 16.5885H16.7834V11.5191H17.7231V13.3631C17.8231 13.2782 17.8771 13.2319 17.9312 13.1862C18.3807 12.8076 18.8857 12.7313 19.4326 12.9265C19.8086 13.0607 20.0647 13.3412 20.0861 13.7275C20.1397 14.6734 20.1438 15.6218 20.1682 16.5859H19.176C19.176 16.4187 19.1775 16.2662 19.176 16.1137C19.1683 15.4652 19.1688 14.8167 19.1474 14.1687C19.1352 13.8012 18.9837 13.6279 18.6852 13.5679C18.372 13.5049 17.9919 13.6548 17.8817 13.9425C17.7904 14.1814 17.744 14.4487 17.7348 14.7059C17.7134 15.3077 17.7261 15.911 17.7246 16.5137C17.7246 16.5285 17.7174 16.5437 17.7067 16.5879L17.7062 16.5885Z" fill="#03045E"/>
                                <path d="M21.8084 22.7673C21.4865 22.7673 21.2238 22.7734 20.9615 22.7622C20.9105 22.7602 20.8457 22.7002 20.8161 22.6504C20.7707 22.5736 20.7503 22.4822 20.7116 22.3754C20.6335 22.4349 20.5748 22.4806 20.5146 22.5249C20.0871 22.8425 19.6101 22.9127 19.1041 22.781C18.6597 22.6656 18.3679 22.3038 18.3414 21.8672C18.3087 21.332 18.5153 20.9564 18.9959 20.7917C19.3739 20.6621 19.7734 20.5935 20.1631 20.4974C20.2305 20.4807 20.3004 20.4695 20.3647 20.4441C20.4764 20.3998 20.6478 20.4283 20.6417 20.2311C20.635 20.0253 20.5784 19.8499 20.3611 19.7716C19.9035 19.6065 19.5622 19.7325 19.2964 20.1788C19.1602 20.1544 19.0173 20.1284 18.8745 20.103C18.7383 20.0786 18.6021 20.0547 18.4398 20.0263C18.5607 19.5887 18.7842 19.2507 19.2168 19.1104C19.7759 18.929 20.3499 18.9366 20.91 19.1028C21.3692 19.239 21.5676 19.5475 21.5865 20.0944C21.5982 20.4415 21.586 20.7892 21.5895 21.1363C21.5952 21.6725 21.5283 22.2169 21.8084 22.7678V22.7673ZM20.6177 20.9889C20.2677 21.0799 19.9254 21.1465 19.5994 21.2593C19.2724 21.3726 19.2046 21.7416 19.4372 21.9947C19.5928 22.164 19.8693 22.2133 20.1269 22.1177C20.5636 21.9561 20.7044 21.6491 20.6177 20.9889Z" fill="#03045E"/>
                                <path d="M27.7022 21.1922C27.6747 21.644 27.9083 22.0029 28.269 22.0994C28.6629 22.2052 28.8827 22.0822 29.145 21.6024C29.4434 21.6532 29.7454 21.705 30.0821 21.763C29.9265 22.1935 29.6771 22.506 29.2776 22.6885C28.0104 23.2669 26.7146 22.4791 26.6942 21.0972C26.6886 20.7277 26.7401 20.3358 26.8656 19.9902C27.1278 19.268 27.8099 18.9183 28.6476 19.0006C29.3587 19.0708 29.9056 19.5699 30.0597 20.3058C30.1051 20.5239 30.1163 20.749 30.1464 20.9701C30.1699 21.1434 30.0985 21.1978 29.9245 21.1953C29.273 21.1866 28.6215 21.1917 27.9701 21.1917C27.8859 21.1917 27.8022 21.1917 27.7022 21.1917V21.1922ZM27.714 20.5823H29.1674C29.1368 20.0075 28.8246 19.6827 28.367 19.7239C27.9578 19.761 27.6681 20.1366 27.714 20.5823Z" fill="#03045E"/>
                                <path d="M24.3602 15.0393H21.8977C21.8385 15.4327 22.0727 15.8179 22.4226 15.9282C22.82 16.0533 23.0797 15.9186 23.3297 15.4444C23.6261 15.4932 23.9281 15.543 24.2684 15.5989C24.1271 15.9963 23.9092 16.2881 23.5618 16.4756C22.3068 17.1541 20.9172 16.3562 20.8666 14.9265C20.8473 14.3831 20.9223 13.8571 21.2829 13.42C21.7482 12.8563 22.3645 12.7278 23.0481 12.8818C23.7052 13.0297 24.0863 13.4734 24.2347 14.1107C24.3026 14.4009 24.3163 14.7033 24.3597 15.0393H24.3602ZM21.9053 14.4502C22.3481 14.4502 22.7956 14.4538 23.243 14.4446C23.2833 14.4436 23.3542 14.3638 23.3557 14.3186C23.369 13.8927 22.9807 13.5257 22.5639 13.5593C22.1431 13.5933 21.837 13.9923 21.9053 14.4502Z" fill="#03045E"/>
                                <path d="M14.5667 16.5864H13.5515V12.3623H12.0353V11.5201H16.0783V12.3456H14.5667V16.5864Z" fill="#03045E"/>
                                <path d="M35.9515 16.5839H34.9245V12.3572H33.4068C33.4068 12.0955 33.3976 11.852 33.4155 11.6106C33.4186 11.5694 33.5349 11.5064 33.6002 11.5054C34.0348 11.4958 34.4695 11.5003 34.9041 11.5003C35.6648 11.5003 36.4254 11.5003 37.1861 11.5003H37.4559V12.3476H35.952V16.5844L35.9515 16.5839Z" fill="#03045E"/>
                                <path d="M16.9338 21.3594C17.2486 21.4143 17.5506 21.4662 17.8705 21.5221C17.7251 22.1177 17.4348 22.5584 16.84 22.7464C15.9885 23.0148 15.1233 22.7642 14.6968 22.1269C14.2703 21.489 14.2581 20.3277 14.7065 19.7056C14.855 19.4997 15.0641 19.3081 15.2886 19.1922C15.8477 18.903 16.4426 18.9163 17.0211 19.147C17.4557 19.3203 17.6802 19.6873 17.822 20.1472C17.5119 20.2057 17.2109 20.2621 16.8951 20.3216C16.8767 20.2804 16.8583 20.2418 16.8425 20.2021C16.7216 19.8921 16.5043 19.7274 16.1931 19.7376C15.8768 19.7478 15.6059 19.8723 15.4927 20.1828C15.3182 20.6626 15.3274 21.1556 15.511 21.6308C15.6263 21.9292 15.9248 22.0776 16.2676 22.0583C16.564 22.041 16.7553 21.8834 16.868 21.5632C16.8884 21.5058 16.9063 21.4468 16.9344 21.3599L16.9338 21.3594Z" fill="#03045E"/>
                                <path d="M27.1166 14.0279C27.1166 13.2635 27.1217 12.4985 27.113 11.7341C27.111 11.5522 27.1666 11.4841 27.3513 11.4973C27.5441 11.511 27.7395 11.5085 27.9328 11.4978C28.0961 11.4886 28.1481 11.5501 28.1466 11.7113C28.14 12.4528 28.1435 13.1943 28.1435 13.9359C28.1435 14.747 28.1389 15.5577 28.1471 16.3689C28.1491 16.5508 28.0935 16.6189 27.9089 16.6057C27.716 16.592 27.5206 16.5945 27.3273 16.6052C27.164 16.6144 27.112 16.5529 27.1135 16.3918C27.1196 15.604 27.1166 14.8162 27.1166 14.0284V14.0279Z" fill="#03045E"/>
                                <path d="M7.26688 27.1454C7.3383 27.0198 7.43523 26.9212 7.55818 26.8506C7.68112 26.7799 7.81734 26.7444 7.96783 26.7444C8.14435 26.7444 8.29842 26.7866 8.42953 26.8714C8.56115 26.9563 8.65706 27.0763 8.71725 27.2323H8.46422C8.41932 27.1352 8.35453 27.0605 8.27036 27.0082C8.18618 26.9558 8.08517 26.9299 7.96732 26.9299C7.84948 26.9299 7.75306 26.9563 7.66327 27.0082C7.57348 27.0605 7.50308 27.1352 7.45207 27.2313C7.40105 27.3278 7.37503 27.4407 7.37503 27.5698C7.37503 27.6989 7.40054 27.8097 7.45207 27.9062C7.50308 28.0028 7.57348 28.077 7.66327 28.1294C7.75306 28.1817 7.85458 28.2076 7.96732 28.2076C8.08007 28.2076 8.18567 28.1817 8.27036 28.1304C8.35504 28.079 8.41932 28.0043 8.46422 27.9073H8.71725C8.65706 28.0613 8.56115 28.1807 8.42953 28.2646C8.29791 28.3484 8.14435 28.3906 7.96783 28.3906C7.81785 28.3906 7.68112 28.3555 7.55818 28.2854C7.43523 28.2153 7.3383 28.1177 7.26688 27.9926C7.19597 27.8676 7.16026 27.7268 7.16026 27.5698C7.16026 27.4127 7.19597 27.2714 7.26688 27.1454Z" fill="#03045E"/>
                                <path d="M9.2621 28.3164C9.16517 28.2625 9.08916 28.1858 9.03457 28.0862C8.97947 27.9865 8.95244 27.8717 8.95244 27.7405C8.95244 27.6094 8.9805 27.4966 9.03712 27.3975C9.09375 27.2979 9.17078 27.2216 9.26822 27.1688C9.36566 27.1154 9.47484 27.089 9.59523 27.089C9.71563 27.089 9.82532 27.1154 9.92276 27.1688C10.0202 27.2221 10.0972 27.2979 10.1539 27.3965C10.2105 27.4951 10.2385 27.6099 10.2385 27.7411C10.2385 27.8722 10.2095 27.987 10.1513 28.0867C10.0931 28.1858 10.0146 28.263 9.91561 28.3169C9.81664 28.3708 9.70645 28.3977 9.58605 28.3977C9.46566 28.3977 9.35903 28.3708 9.26261 28.3169L9.2621 28.3164ZM9.79879 28.1588C9.86511 28.1233 9.91919 28.0704 9.96 27.9992C10.0008 27.9286 10.0212 27.8422 10.0212 27.74C10.0212 27.6379 10.0013 27.552 9.96102 27.4813C9.92072 27.4107 9.86817 27.3578 9.80338 27.3228C9.73859 27.2882 9.66768 27.2709 9.59217 27.2709C9.51667 27.2709 9.44423 27.2882 9.37995 27.3228C9.31567 27.3573 9.26414 27.4102 9.22537 27.4813C9.1866 27.552 9.16721 27.6384 9.16721 27.74C9.16721 27.8417 9.1866 27.9306 9.22435 28.0013C9.26261 28.0719 9.31312 28.1248 9.37638 28.1599C9.43964 28.1944 9.50953 28.2117 9.58503 28.2117C9.66054 28.2117 9.73196 28.1939 9.79879 28.1583V28.1588Z" fill="#03045E"/>
                                <path d="M11.5114 27.2262C11.6073 27.3197 11.6553 27.4539 11.6553 27.6297V28.3764H11.4461V27.6597C11.4461 27.5332 11.4145 27.4366 11.3507 27.3695C11.2874 27.3024 11.2007 27.2689 11.091 27.2689C10.9813 27.2689 10.891 27.3035 10.8252 27.3731C10.7594 27.4422 10.7268 27.5433 10.7268 27.676V28.3764H10.5156V27.1093H10.7268V27.2897C10.7686 27.2252 10.8252 27.1749 10.8977 27.1393C10.9696 27.1037 11.0487 27.0859 11.1354 27.0859C11.2905 27.0859 11.4155 27.1327 11.5114 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M12.997 27.2262C13.0929 27.3197 13.1408 27.4539 13.1408 27.6297V28.3764H12.9317V27.6597C12.9317 27.5332 12.9 27.4366 12.8363 27.3695C12.773 27.3024 12.6863 27.2689 12.5766 27.2689C12.4669 27.2689 12.3766 27.3035 12.3108 27.3731C12.245 27.4422 12.2123 27.5433 12.2123 27.676V28.3764H12.0011V27.1093H12.2123V27.2897C12.2542 27.2252 12.3108 27.1749 12.3832 27.1393C12.4552 27.1037 12.5343 27.0859 12.621 27.0859C12.7761 27.0859 12.9011 27.1327 12.997 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M14.6397 27.8214H13.6229C13.6306 27.9464 13.6739 28.044 13.752 28.1141C13.8301 28.1843 13.9249 28.2193 14.0362 28.2193C14.1275 28.2193 14.2035 28.198 14.2647 28.1558C14.3254 28.1136 14.3688 28.0567 14.3933 27.986H14.6208C14.5866 28.108 14.5188 28.2066 14.4167 28.2834C14.3147 28.3596 14.1877 28.3977 14.0362 28.3977C13.9158 28.3977 13.8076 28.3708 13.7122 28.3169C13.6168 28.263 13.5423 28.1863 13.4882 28.0867C13.4342 27.987 13.4071 27.8722 13.4071 27.7411C13.4071 27.6099 13.4337 27.4951 13.4862 27.3965C13.5388 27.2979 13.6127 27.2216 13.7076 27.1688C13.8025 27.1159 13.9122 27.089 14.0362 27.089C14.1601 27.089 14.2637 27.1154 14.3565 27.1677C14.4494 27.2201 14.5208 27.2923 14.5713 27.3838C14.6218 27.4757 14.6468 27.5794 14.6468 27.6948C14.6468 27.735 14.6443 27.7771 14.6397 27.8219V27.8214ZM14.3754 27.4437C14.3397 27.3858 14.2917 27.3421 14.2305 27.3121C14.1698 27.2821 14.102 27.2669 14.0275 27.2669C13.9209 27.2669 13.83 27.3009 13.7545 27.3685C13.679 27.4361 13.6367 27.5301 13.626 27.6506H14.429C14.429 27.5708 14.4111 27.5017 14.3754 27.4437Z" fill="#03045E"/>
                                <path d="M14.9254 27.3975C14.9779 27.2994 15.0509 27.2237 15.1447 27.1698C15.2381 27.1159 15.3452 27.089 15.4661 27.089C15.6222 27.089 15.7513 27.1271 15.8523 27.2023C15.9538 27.278 16.0207 27.3827 16.0533 27.5164H15.8258C15.8044 27.4392 15.762 27.3787 15.6993 27.3339C15.6365 27.2892 15.559 27.2669 15.4661 27.2669C15.3457 27.2669 15.2483 27.308 15.1738 27.3904C15.0993 27.4727 15.0621 27.5896 15.0621 27.7405C15.0621 27.8915 15.0993 28.0109 15.1738 28.0943C15.2483 28.1776 15.3457 28.2193 15.4661 28.2193C15.559 28.2193 15.6365 28.198 15.6982 28.1548C15.76 28.1116 15.8023 28.0501 15.8258 27.9698H16.0533C16.0191 28.0994 15.9513 28.2031 15.8493 28.2808C15.7472 28.3586 15.6192 28.3977 15.4661 28.3977C15.3457 28.3977 15.2381 28.3708 15.1447 28.3169C15.0509 28.263 14.9779 28.1868 14.9254 28.0882C14.8728 27.9896 14.8463 27.8737 14.8463 27.7416C14.8463 27.6094 14.8728 27.4961 14.9254 27.3985V27.3975Z" fill="#03045E"/>
                                <path d="M16.5915 27.2826V28.0292C16.5915 28.0907 16.6048 28.1344 16.6308 28.1599C16.6573 28.1853 16.7027 28.198 16.768 28.198H16.9236V28.3759H16.7334C16.6155 28.3759 16.5278 28.3489 16.4686 28.2951C16.4099 28.2412 16.3803 28.1522 16.3803 28.0292V27.2826H16.2155V27.1093H16.3803V26.7901H16.5915V27.1093H16.9231V27.2826H16.5915Z" fill="#03045E"/>
                                <path d="M17.1859 26.8618C17.1583 26.8338 17.144 26.8003 17.144 26.7601C17.144 26.72 17.1583 26.6859 17.1859 26.6585C17.2139 26.6305 17.2476 26.6168 17.2879 26.6168C17.3282 26.6168 17.3593 26.6305 17.3864 26.6585C17.4134 26.6864 17.4272 26.72 17.4272 26.7601C17.4272 26.8003 17.4134 26.8343 17.3864 26.8618C17.3593 26.8897 17.3267 26.9035 17.2879 26.9035C17.2491 26.9035 17.2134 26.8897 17.1859 26.8618ZM17.3899 27.1093V28.3764H17.1787V27.1093H17.3899Z" fill="#03045E"/>
                                <path d="M18.7454 27.2262C18.8413 27.3197 18.8893 27.4539 18.8893 27.6297V28.3764H18.6801V27.6597C18.6801 27.5332 18.6485 27.4366 18.5847 27.3695C18.5215 27.3024 18.4347 27.2689 18.325 27.2689C18.2154 27.2689 18.1251 27.3035 18.0593 27.3731C17.9934 27.4422 17.9608 27.5433 17.9608 27.676V28.3764H17.7496V27.1093H17.9608V27.2897C18.0026 27.2252 18.0593 27.1749 18.1317 27.1393C18.2036 27.1037 18.2827 27.0859 18.3694 27.0859C18.5245 27.0859 18.6495 27.1327 18.7454 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M20.0484 27.1601C20.131 27.2079 20.1927 27.2679 20.233 27.3406V27.1093H20.4463V28.4038C20.4463 28.5197 20.4218 28.6224 20.3718 28.7123C20.3223 28.8023 20.2519 28.8729 20.1596 28.9238C20.0677 28.9746 19.9606 29 19.8382 29C19.6708 29 19.5316 28.9609 19.4204 28.8821C19.3091 28.8033 19.2433 28.6961 19.2234 28.5609H19.4321C19.455 28.6381 19.5035 28.6996 19.5759 28.7469C19.6489 28.7942 19.7361 28.8175 19.8382 28.8175C19.9545 28.8175 20.0489 28.7814 20.1223 28.7088C20.1958 28.6361 20.2325 28.5344 20.2325 28.4038V28.138C20.1907 28.2117 20.129 28.2737 20.0468 28.323C19.9652 28.3723 19.8693 28.3972 19.7591 28.3972C19.6489 28.3972 19.5438 28.3693 19.4515 28.3139C19.3596 28.2585 19.2872 28.1807 19.2347 28.0806C19.1821 27.9804 19.1556 27.8661 19.1556 27.7385C19.1556 27.6109 19.1821 27.4956 19.2347 27.3975C19.2872 27.2994 19.3596 27.2237 19.4515 27.1698C19.5438 27.1159 19.6464 27.089 19.7591 27.089C19.8718 27.089 19.9652 27.1129 20.0484 27.1606V27.1601ZM20.1749 27.491C20.1361 27.4198 20.0841 27.3655 20.0183 27.3278C19.9524 27.2902 19.8805 27.2714 19.8014 27.2714C19.7224 27.2714 19.6504 27.2897 19.5856 27.3268C19.5209 27.3639 19.4688 27.4178 19.43 27.4885C19.3913 27.5591 19.3719 27.6425 19.3719 27.738C19.3719 27.8336 19.3913 27.9195 19.43 27.9911C19.4688 28.0628 19.5203 28.1177 19.5856 28.1553C19.6509 28.1929 19.7229 28.2117 19.8014 28.2117C19.88 28.2117 19.953 28.1929 20.0183 28.1553C20.0836 28.1177 20.1361 28.0628 20.1749 27.9911C20.2136 27.9195 20.233 27.8356 20.233 27.74C20.233 27.6445 20.2136 27.5611 20.1749 27.4905V27.491Z" fill="#03045E"/>
                                <path d="M22.4191 26.7647V26.936H21.9783V28.3764H21.7671V26.936H21.3238V26.7647H22.4191Z" fill="#03045E"/>
                                <path d="M23.57 27.1474C23.6475 27.1881 23.7077 27.2496 23.7521 27.3314C23.7965 27.4132 23.8184 27.5123 23.8184 27.6297V28.3764H23.6093V27.6597C23.6093 27.5332 23.5776 27.4366 23.5139 27.3695C23.4506 27.3024 23.3639 27.2689 23.2542 27.2689C23.1445 27.2689 23.0542 27.3035 22.9884 27.3731C22.9226 27.4422 22.8899 27.5433 22.8899 27.676V28.3764H22.6787V26.6656H22.8899V27.2897C22.9318 27.2252 22.9894 27.1749 23.0629 27.1393C23.1363 27.1037 23.219 27.0859 23.3098 27.0859C23.4057 27.0859 23.4924 27.1062 23.57 27.1469V27.1474Z" fill="#03045E"/>
                                <path d="M25.3178 27.8214H24.301C24.3087 27.9464 24.352 28.044 24.4301 28.1141C24.5081 28.1843 24.603 28.2193 24.7143 28.2193C24.8056 28.2193 24.8816 28.198 24.9428 28.1558C25.0035 28.1136 25.0469 28.0567 25.0714 27.986H25.2989C25.2647 28.108 25.1969 28.2066 25.0948 28.2834C24.9928 28.3596 24.8658 28.3977 24.7143 28.3977C24.5939 28.3977 24.4857 28.3708 24.3903 28.3169C24.2949 28.263 24.2204 28.1863 24.1663 28.0867C24.1123 27.987 24.0852 27.8722 24.0852 27.7411C24.0852 27.6099 24.1118 27.4951 24.1643 27.3965C24.2169 27.2979 24.2908 27.2216 24.3857 27.1688C24.4806 27.1159 24.5903 27.089 24.7143 27.089C24.8382 27.089 24.9418 27.1154 25.0346 27.1677C25.1275 27.2201 25.1989 27.2923 25.2494 27.3838C25.2999 27.4757 25.3249 27.5794 25.3249 27.6948C25.3249 27.735 25.3224 27.7771 25.3178 27.8219V27.8214ZM25.053 27.4437C25.0173 27.3858 24.9693 27.3421 24.9081 27.3121C24.8474 27.2821 24.7796 27.2669 24.7051 27.2669C24.5984 27.2669 24.5076 27.3009 24.4321 27.3685C24.3566 27.4361 24.3143 27.5301 24.3036 27.6506H25.1066C25.1066 27.5708 25.0887 27.5017 25.053 27.4437Z" fill="#03045E"/>
                                <path d="M26.4289 26.7647V27.7843C26.4289 27.9276 26.4641 28.0338 26.5345 28.1034C26.6049 28.1726 26.7028 28.2076 26.8283 28.2076C26.9538 28.2076 27.0492 28.1731 27.1196 28.1034C27.19 28.0343 27.2252 27.9276 27.2252 27.7843V26.7647H27.4365V27.7822C27.4365 27.9164 27.4094 28.0292 27.3553 28.1207C27.3013 28.2122 27.2283 28.2808 27.136 28.3255C27.0441 28.3703 26.9406 28.3926 26.8263 28.3926C26.712 28.3926 26.6085 28.3703 26.5161 28.3255C26.4243 28.2808 26.3513 28.2127 26.2983 28.1207C26.2452 28.0287 26.2182 27.9159 26.2182 27.7822V26.7647H26.4294H26.4289Z" fill="#03045E"/>
                                <path d="M28.7843 27.2262C28.8802 27.3197 28.9282 27.4539 28.9282 27.6297V28.3764H28.719V27.6597C28.719 27.5332 28.6874 27.4366 28.6236 27.3695C28.5603 27.3024 28.4736 27.2689 28.3639 27.2689C28.2542 27.2689 28.1639 27.3035 28.0981 27.3731C28.0323 27.4422 27.9997 27.5433 27.9997 27.676V28.3764H27.7885V27.1093H27.9997V27.2897C28.0415 27.2252 28.0981 27.1749 28.1706 27.1393C28.2425 27.1037 28.3216 27.0859 28.4083 27.0859C28.5634 27.0859 28.6884 27.1327 28.7843 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M29.274 27.3975C29.3266 27.2994 29.3995 27.2237 29.4934 27.1698C29.5868 27.1159 29.6939 27.089 29.8148 27.089C29.9709 27.089 30.1 27.1271 30.201 27.2023C30.3025 27.278 30.3693 27.3827 30.402 27.5164H30.1745C30.153 27.4392 30.1107 27.3787 30.0479 27.3339C29.9852 27.2892 29.9077 27.2669 29.8148 27.2669C29.6944 27.2669 29.597 27.308 29.5225 27.3904C29.448 27.4727 29.4108 27.5896 29.4108 27.7405C29.4108 27.8915 29.448 28.0109 29.5225 28.0943C29.597 28.1776 29.6944 28.2193 29.8148 28.2193C29.9077 28.2193 29.9852 28.198 30.0469 28.1548C30.1087 28.1116 30.151 28.0501 30.1745 27.9698H30.402C30.3678 28.0994 30.3 28.2031 30.1979 28.2808C30.0959 28.3586 29.9679 28.3977 29.8148 28.3977C29.6944 28.3977 29.5868 28.3708 29.4934 28.3169C29.3995 28.263 29.3266 28.1868 29.274 28.0882C29.2215 27.9896 29.195 27.8737 29.195 27.7416C29.195 27.6094 29.2215 27.4961 29.274 27.3985V27.3975Z" fill="#03045E"/>
                                <path d="M30.9137 28.3164C30.8168 28.2625 30.7407 28.1858 30.6862 28.0862C30.6311 27.9865 30.604 27.8717 30.604 27.7405C30.604 27.6094 30.6321 27.4966 30.6887 27.3975C30.7453 27.2979 30.8224 27.2216 30.9198 27.1688C31.0172 27.1159 31.1264 27.089 31.2468 27.089C31.3672 27.089 31.4769 27.1154 31.5743 27.1688C31.6718 27.2221 31.7488 27.2979 31.8054 27.3965C31.8621 27.4951 31.8901 27.6099 31.8901 27.7411C31.8901 27.8722 31.861 27.987 31.8029 28.0867C31.7447 28.1858 31.6662 28.263 31.5672 28.3169C31.4682 28.3708 31.358 28.3977 31.2376 28.3977C31.1172 28.3977 31.0106 28.3708 30.9142 28.3169L30.9137 28.3164ZM31.4509 28.1588C31.5172 28.1233 31.5713 28.0704 31.6121 27.9992C31.6529 27.9286 31.6733 27.8422 31.6733 27.74C31.6733 27.6379 31.6534 27.552 31.6131 27.4813C31.5728 27.4107 31.5203 27.3578 31.4555 27.3228C31.3907 27.2882 31.3198 27.2709 31.2443 27.2709C31.1688 27.2709 31.0963 27.2882 31.032 27.3228C30.9678 27.3573 30.9162 27.4102 30.8775 27.4813C30.8387 27.552 30.8193 27.6384 30.8193 27.74C30.8193 27.8417 30.8387 27.9306 30.8764 28.0013C30.9147 28.0719 30.9652 28.1248 31.0285 28.1599C31.0917 28.1944 31.1616 28.2117 31.2371 28.2117C31.3126 28.2117 31.384 28.1939 31.4509 28.1583V28.1588Z" fill="#03045E"/>
                                <path d="M33.1635 27.2262C33.2594 27.3197 33.3073 27.4539 33.3073 27.6297V28.3764H33.0982V27.6597C33.0982 27.5332 33.0665 27.4366 33.0028 27.3695C32.9395 27.3024 32.8528 27.2689 32.7431 27.2689C32.6334 27.2689 32.5431 27.3035 32.4773 27.3731C32.4115 27.4422 32.3789 27.5433 32.3789 27.676V28.3764H32.1676V27.1093H32.3789V27.2897C32.4207 27.2252 32.4773 27.1749 32.5498 27.1393C32.6217 27.1037 32.7008 27.0859 32.7875 27.0859C32.9426 27.0859 33.0676 27.1327 33.1635 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M34.6491 27.2262C34.745 27.3197 34.7929 27.4539 34.7929 27.6297V28.3764H34.5838V27.6597C34.5838 27.5332 34.5521 27.4366 34.4884 27.3695C34.4251 27.3024 34.3384 27.2689 34.2287 27.2689C34.119 27.2689 34.0287 27.3035 33.9629 27.3731C33.8971 27.4422 33.8644 27.5433 33.8644 27.676V28.3764H33.6532V27.1093H33.8644V27.2897C33.9063 27.2252 33.9629 27.1749 34.0353 27.1393C34.1073 27.1037 34.1863 27.0859 34.2731 27.0859C34.4282 27.0859 34.5531 27.1327 34.6491 27.2257V27.2262Z" fill="#03045E"/>
                                <path d="M36.2923 27.8214H35.2755C35.2832 27.9464 35.3265 28.044 35.4046 28.1141C35.4827 28.1843 35.5775 28.2193 35.6888 28.2193C35.7801 28.2193 35.8561 28.198 35.9173 28.1558C35.978 28.1136 36.0214 28.0567 36.0459 27.986H36.2734C36.2392 28.108 36.1714 28.2066 36.0693 28.2834C35.9673 28.3596 35.8403 28.3977 35.6888 28.3977C35.5684 28.3977 35.4602 28.3708 35.3648 28.3169C35.2694 28.263 35.1949 28.1863 35.1408 28.0867C35.0868 27.987 35.0597 27.8722 35.0597 27.7411C35.0597 27.6099 35.0863 27.4951 35.1388 27.3965C35.1914 27.2979 35.2653 27.2216 35.3602 27.1688C35.4551 27.1159 35.5648 27.089 35.6888 27.089C35.8127 27.089 35.9163 27.1154 36.0091 27.1677C36.102 27.2201 36.1734 27.2923 36.2239 27.3838C36.2744 27.4757 36.2994 27.5794 36.2994 27.6948C36.2994 27.735 36.2969 27.7771 36.2923 27.8219V27.8214ZM36.0275 27.4437C35.9918 27.3858 35.9438 27.3421 35.8826 27.3121C35.8219 27.2821 35.7541 27.2669 35.6796 27.2669C35.573 27.2669 35.4821 27.3009 35.4066 27.3685C35.3311 27.4361 35.2888 27.5301 35.2781 27.6506H36.0811C36.0811 27.5708 36.0632 27.5017 36.0275 27.4437Z" fill="#03045E"/>
                                <path d="M36.5774 27.3975C36.63 27.2994 36.7029 27.2237 36.7968 27.1698C36.8902 27.1159 36.9973 27.089 37.1182 27.089C37.2743 27.089 37.4034 27.1271 37.5044 27.2023C37.6059 27.278 37.6728 27.3827 37.7054 27.5164H37.4779C37.4564 27.4392 37.4141 27.3787 37.3514 27.3339C37.2886 27.2892 37.2111 27.2669 37.1182 27.2669C36.9978 27.2669 36.9004 27.308 36.8259 27.3904C36.7514 27.4727 36.7142 27.5896 36.7142 27.7405C36.7142 27.8915 36.7514 28.0109 36.8259 28.0943C36.9004 28.1776 36.9978 28.2193 37.1182 28.2193C37.2111 28.2193 37.2886 28.198 37.3503 28.1548C37.4121 28.1116 37.4544 28.0501 37.4779 27.9698H37.7054C37.6712 28.0994 37.6034 28.2031 37.5013 28.2808C37.3993 28.3586 37.2713 28.3977 37.1182 28.3977C36.9978 28.3977 36.8902 28.3708 36.7968 28.3169C36.7029 28.263 36.63 28.1868 36.5774 28.0882C36.5249 27.9896 36.4984 27.8737 36.4984 27.7416C36.4984 27.6094 36.5249 27.4961 36.5774 27.3985V27.3975Z" fill="#03045E"/>
                                <path d="M38.2441 27.2826V28.0292C38.2441 28.0907 38.2574 28.1344 38.2834 28.1599C38.3099 28.1853 38.3554 28.198 38.4207 28.198H38.5762V28.3759H38.386C38.2681 28.3759 38.1804 28.3489 38.1212 28.2951C38.0625 28.2412 38.0329 28.1522 38.0329 28.0292V27.2826H37.8682V27.1093H38.0329V26.7901H38.2441V27.1093H38.5757V27.2826H38.2441Z" fill="#03045E"/>
                                <path d="M39.9843 27.8214H38.9675C38.9752 27.9464 39.0186 28.044 39.0966 28.1141C39.1747 28.1843 39.2696 28.2193 39.3808 28.2193C39.4721 28.2193 39.5481 28.198 39.6093 28.1558C39.67 28.1136 39.7134 28.0567 39.7379 27.986H39.9654C39.9312 28.108 39.8634 28.2066 39.7613 28.2834C39.6593 28.3596 39.5323 28.3977 39.3808 28.3977C39.2604 28.3977 39.1522 28.3708 39.0568 28.3169C38.9614 28.263 38.8869 28.1863 38.8329 28.0867C38.7788 27.987 38.7517 27.8722 38.7517 27.7411C38.7517 27.6099 38.7783 27.4951 38.8308 27.3965C38.8834 27.2979 38.9573 27.2216 39.0522 27.1688C39.1471 27.1159 39.2568 27.089 39.3808 27.089C39.5047 27.089 39.6083 27.1154 39.7011 27.1677C39.794 27.2201 39.8654 27.2923 39.9159 27.3838C39.9664 27.4757 39.9914 27.5794 39.9914 27.6948C39.9914 27.735 39.9889 27.7771 39.9843 27.8219V27.8214ZM39.7195 27.4437C39.6838 27.3858 39.6358 27.3421 39.5746 27.3121C39.5139 27.2821 39.4461 27.2669 39.3716 27.2669C39.265 27.2669 39.1742 27.3009 39.0987 27.3685C39.0231 27.4361 38.9808 27.5301 38.9701 27.6506H39.7731C39.7731 27.5708 39.7552 27.5017 39.7195 27.4437Z" fill="#03045E"/>
                                <path d="M40.27 27.3975C40.3225 27.2994 40.395 27.2237 40.4868 27.1698C40.5791 27.1159 40.6822 27.089 40.797 27.089C40.8959 27.089 40.9878 27.1118 41.073 27.1571C41.1582 27.2028 41.2229 27.2623 41.2683 27.3365V26.6661H41.4816V28.3769H41.2683V28.139C41.2265 28.2147 41.1648 28.2762 41.0826 28.325C41.001 28.3738 40.9051 28.3977 40.7949 28.3977C40.6847 28.3977 40.5796 28.3698 40.4873 28.3144C40.3955 28.259 40.323 28.1812 40.2705 28.0811C40.2179 27.981 40.1914 27.8666 40.1914 27.739C40.1914 27.6115 40.2179 27.4961 40.2705 27.398L40.27 27.3975ZM41.2097 27.491C41.1709 27.4198 41.1189 27.3655 41.0531 27.3278C40.9872 27.2902 40.9153 27.2714 40.8362 27.2714C40.7572 27.2714 40.6852 27.2897 40.6204 27.3268C40.5557 27.3639 40.5036 27.4178 40.4648 27.4885C40.4261 27.5591 40.4067 27.6425 40.4067 27.738C40.4067 27.8336 40.4261 27.9195 40.4648 27.9911C40.5036 28.0628 40.5551 28.1177 40.6204 28.1553C40.6857 28.1929 40.7577 28.2117 40.8362 28.2117C40.9148 28.2117 40.9878 28.1929 41.0531 28.1553C41.1184 28.1177 41.1709 28.0628 41.2097 27.9911C41.2484 27.9195 41.2678 27.8356 41.2678 27.74C41.2678 27.6445 41.2484 27.5611 41.2097 27.4905V27.491Z" fill="#03045E"/>
                        </svg>
                        <span class="content">
                            <span>Data Analytics with Gen AI & Agentic AI</span>
                            <span class="duration">6 months</span>
                        </span>
                    </a>
                </div>
            </div>
    
            <div class="nsh-ach-right analytics d-none">
                <a href="http://localhost/TIA/java-certification-course-in-noida">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="29" viewBox="0 0 48 29" fill="none">
                            <path d="M20.3044 5.93391C20.5931 5.53086 20.8533 5.12833 21.1523 4.75578C23.1934 2.21502 25.835 0.672469 29.0587 0.173872C32.7083 -0.390797 36.0443 0.414276 38.9761 2.67143C41.4075 4.54282 42.8967 7.01039 43.4186 10.035C43.6425 11.3321 43.617 12.6337 43.3696 13.9262C43.3308 14.1285 43.3885 14.1915 43.5548 14.2657C45.2684 15.0342 46.5612 16.2438 47.3478 17.9511C48.8717 21.2572 47.6498 25.1225 44.4685 27.0386C43.9624 27.3436 43.3977 27.555 42.8518 27.7903C42.6003 27.8986 42.4391 27.8269 42.3697 27.6353C42.2978 27.4356 42.3947 27.2999 42.6646 27.2023C45.0241 26.3484 46.6198 24.7652 47.2075 22.3078C47.832 19.6969 46.7234 17.0032 44.4501 15.4835C43.9716 15.1633 43.4313 14.9336 42.913 14.6743C42.6819 14.5585 42.6197 14.4721 42.6758 14.221C43.6961 9.68075 41.7045 5.22642 37.965 2.70192C36.1749 1.4933 34.199 0.774628 32.039 0.667386C27.1742 0.425966 23.3766 2.29837 20.7615 6.43708C20.6064 6.68206 20.4722 6.72678 20.1983 6.58651C18.7535 5.84801 17.2154 5.55526 15.6053 5.68334C13.6387 5.83988 11.9128 6.5799 10.4701 7.92931C9.11613 9.19486 8.30396 10.7496 8.05449 12.5844C8.00092 12.9803 8.00603 13.3849 7.99582 13.7859C7.98715 14.1219 7.91114 14.1966 7.56882 14.2083C5.02977 14.2967 3.007 15.3427 1.61835 17.4814C0.0123727 19.9561 0.390909 23.2923 2.46674 25.3985C3.37278 26.3179 4.43901 26.9593 5.6843 27.3065C5.71389 27.3146 5.74246 27.3278 5.77256 27.3354C5.96999 27.3863 6.0608 27.5143 6.02815 27.7121C5.99856 27.8925 5.82255 27.9992 5.61135 27.9255C5.0864 27.7431 4.54461 27.5835 4.05486 27.3283C1.9474 26.23 0.573035 24.539 0.135321 22.1909C-0.323821 19.7279 0.39397 17.5887 2.1586 15.8194C3.51817 14.4558 5.19455 13.7432 7.11886 13.6096C7.25405 13.6004 7.3668 13.6055 7.37547 13.4047C7.51934 10.1046 9.08858 7.6574 11.9807 6.10875C14.5799 4.71715 17.2883 4.68259 20.0126 5.80532C20.1115 5.84598 20.2085 5.89121 20.3049 5.9334L20.3044 5.93391Z" fill="#03045E"/>
                            <path d="M10.5676 23.8793C12.3516 23.8555 14.1351 23.8351 15.9191 23.8072C17.6639 23.7802 19.4081 23.7472 21.1528 23.7152C22.7808 23.6852 24.4092 23.6547 26.0371 23.6222C27.541 23.5927 29.045 23.5622 30.5489 23.5292C31.8743 23.5002 33.2002 23.4727 34.5256 23.4351C35.6102 23.4041 36.6948 23.3619 37.7789 23.3187C38.1462 23.304 38.361 22.9533 38.2268 22.6001C37.8187 21.5261 37.408 20.4527 36.9988 19.3788C36.9662 19.2934 36.9381 19.2065 36.8943 19.0825C37.2259 19.0825 37.5319 19.0738 37.8365 19.0906C37.8875 19.0931 37.9523 19.1983 37.9763 19.268C38.2288 19.9963 38.4737 20.7272 38.7221 21.4575C38.7415 21.5144 38.767 21.5693 38.8104 21.6751C38.8609 21.5393 38.8976 21.4504 38.9283 21.3594C39.1594 20.6646 39.395 19.9709 39.6159 19.2725C39.6654 19.1165 39.7389 19.0682 39.895 19.0758C40.1419 19.088 40.3899 19.0789 40.6664 19.0789C40.6419 19.1633 40.6271 19.2268 40.6046 19.2878C40.119 20.6128 39.6348 21.9383 39.1451 23.2623C38.863 24.0257 38.4946 24.2819 37.6712 24.2738C35.8408 24.2555 34.0103 24.229 32.1804 24.2046C31.1336 24.1909 30.0867 24.1736 29.0399 24.1589C26.8916 24.1279 24.7438 24.0969 22.5956 24.0659C21.5564 24.0506 20.5172 24.0349 19.4785 24.0196C17.3226 23.9886 15.1672 23.9546 13.0112 23.9281C12.1975 23.9185 11.3833 23.9266 10.5691 23.9266L10.5681 23.8788L10.5676 23.8793Z" fill="#03045E"/>
                            <path d="M36.3111 22.751H35.3827C35.3827 22.38 35.3862 22.0257 35.3816 21.6715C35.3755 21.1932 35.3663 20.715 35.3521 20.2367C35.3454 20.0136 35.2648 19.8276 35.0301 19.7518C34.7837 19.6725 34.57 19.7437 34.3873 19.9109C34.1705 20.1096 34.1251 20.3785 34.12 20.6519C34.1093 21.2466 34.1088 21.8413 34.1057 22.4359C34.1037 22.7683 34.1057 22.7683 33.7604 22.7683C33.6206 22.7683 33.4803 22.7607 33.3415 22.7703C33.1849 22.781 33.1436 22.7124 33.1451 22.566C33.1517 22.0329 33.1502 21.4997 33.1461 20.9671C33.1446 20.7434 33.1354 20.5198 33.1206 20.2967C33.0972 19.9465 32.9829 19.7793 32.7462 19.7305C32.4498 19.6695 32.0865 19.8159 31.9922 20.1C31.9013 20.3724 31.8646 20.6707 31.8534 20.9594C31.8325 21.4916 31.8478 22.0252 31.8463 22.5584C31.8463 22.6184 31.836 22.6783 31.8279 22.7663C31.5488 22.7663 31.279 22.7719 31.0096 22.7602C30.9678 22.7581 30.8953 22.6748 30.8953 22.629C30.8917 21.4707 30.8963 20.3119 30.9004 19.1536C30.9004 19.1399 30.9127 19.1257 30.9264 19.0957H31.7779C31.7845 19.2365 31.7906 19.365 31.7988 19.5399C32.1304 19.2151 32.4727 18.992 32.9308 18.9905C33.3895 18.9894 33.7415 19.1607 33.9925 19.5572C34.1037 19.4581 34.1955 19.3671 34.2965 19.2873C34.7087 18.961 35.1715 18.9096 35.6561 19.0758C36.0418 19.2085 36.2943 19.4952 36.3045 19.8997C36.328 20.8405 36.3122 21.7818 36.3122 22.75L36.3111 22.751Z" fill="#03045E"/>
                            <path d="M8.89734 22.7683C9.12079 22.1975 9.32587 21.6751 9.52994 21.1521C9.96255 20.0466 10.3977 18.9422 10.8242 17.8347C10.8773 17.6975 10.941 17.6431 11.091 17.6512C11.3308 17.6644 11.5726 17.6436 11.8114 17.6614C11.8894 17.667 12.0042 17.7305 12.0312 17.7961C12.6893 19.4098 13.3383 21.0275 13.9877 22.6443C13.9959 22.6651 13.9953 22.6895 14.0071 22.7683C13.7581 22.7683 13.5143 22.7485 13.2755 22.7734C13.0061 22.8018 12.8653 22.7119 12.7949 22.4481C12.7317 22.2113 12.6169 21.9886 12.5343 21.7564C12.4929 21.64 12.4343 21.5831 12.3006 21.5841C11.7267 21.5902 11.1527 21.5897 10.5788 21.5841C10.4452 21.5826 10.388 21.64 10.3477 21.7589C10.2544 22.0364 10.1416 22.3073 10.0503 22.5853C10.008 22.7139 9.94775 22.7754 9.80389 22.7703C9.51871 22.7612 9.23302 22.7678 8.89734 22.7678V22.7683ZM12.1425 20.7475C11.9032 20.1071 11.6772 19.5023 11.4512 18.898C11.4333 18.9 11.4155 18.902 11.3976 18.9041C11.1762 19.5109 10.9553 20.1183 10.7263 20.7475H12.1425Z" fill="#03045E"/>
                            <path d="M33.4303 14.5153C33.4303 15.79 32.5584 16.6733 31.3004 16.6728C29.9806 16.6723 29.1557 15.8357 29.1547 14.4975C29.1541 13.2452 30.0332 12.3684 31.2912 12.3664C32.5523 12.3644 33.4303 13.2467 33.4303 14.5158V14.5153ZM32.36 14.5153C32.3008 14.284 32.2697 14.035 32.1743 13.8144C32.0141 13.4439 31.7034 13.2416 31.2963 13.2421C30.879 13.2426 30.5673 13.4546 30.4025 13.8317C30.2066 14.281 30.203 14.7506 30.3984 15.2009C30.5607 15.575 30.855 15.7951 31.2825 15.7996C31.7192 15.8042 32.0243 15.5907 32.187 15.2019C32.2743 14.9936 32.3008 14.7598 32.36 14.5148V14.5153Z" fill="#03045E"/>
                            <path d="M25.0918 22.2499C25.0076 22.3221 24.9729 22.348 24.9423 22.3785C24.2638 23.0616 23.2659 22.9828 22.7134 22.2016C22.2487 21.5454 22.2359 20.285 22.6869 19.6227C23.1664 18.9188 24.1521 18.7765 24.8117 19.3157C24.8632 19.3579 24.9153 19.4001 25.003 19.4713V17.6695H25.9621V22.7658C25.7065 22.7658 25.4601 22.7719 25.2147 22.7597C25.1719 22.7576 25.1066 22.6829 25.0979 22.6341C25.0795 22.5304 25.0923 22.4217 25.0923 22.2494L25.0918 22.2499ZM23.3256 20.8674C23.3343 20.87 23.343 20.8725 23.3516 20.8746C23.3598 21.0052 23.3608 21.1368 23.3787 21.2664C23.3924 21.3645 23.42 21.4626 23.4542 21.5561C23.5725 21.8763 23.8531 22.0817 24.1725 22.0908C24.4622 22.0989 24.7775 21.8966 24.8949 21.5932C25.0709 21.1373 25.0668 20.6687 24.8974 20.2138C24.779 19.8962 24.5286 19.7208 24.1811 19.7223C23.8337 19.7244 23.5868 19.9068 23.4659 20.2189C23.3868 20.4227 23.37 20.6499 23.3256 20.8669V20.8674Z" fill="#03045E"/>
                            <path d="M17.7062 16.5885H16.7834V11.5191H17.7231V13.3631C17.8231 13.2782 17.8771 13.2319 17.9312 13.1862C18.3807 12.8076 18.8857 12.7313 19.4326 12.9265C19.8086 13.0607 20.0647 13.3412 20.0861 13.7275C20.1397 14.6734 20.1438 15.6218 20.1682 16.5859H19.176C19.176 16.4187 19.1775 16.2662 19.176 16.1137C19.1683 15.4652 19.1688 14.8167 19.1474 14.1687C19.1352 13.8012 18.9837 13.6279 18.6852 13.5679C18.372 13.5049 17.9919 13.6548 17.8817 13.9425C17.7904 14.1814 17.744 14.4487 17.7348 14.7059C17.7134 15.3077 17.7261 15.911 17.7246 16.5137C17.7246 16.5285 17.7174 16.5437 17.7067 16.5879L17.7062 16.5885Z" fill="#03045E"/>
                            <path d="M21.8084 22.7673C21.4865 22.7673 21.2238 22.7734 20.9615 22.7622C20.9105 22.7602 20.8457 22.7002 20.8161 22.6504C20.7707 22.5736 20.7503 22.4822 20.7116 22.3754C20.6335 22.4349 20.5748 22.4806 20.5146 22.5249C20.0871 22.8425 19.6101 22.9127 19.1041 22.781C18.6597 22.6656 18.3679 22.3038 18.3414 21.8672C18.3087 21.332 18.5153 20.9564 18.9959 20.7917C19.3739 20.6621 19.7734 20.5935 20.1631 20.4974C20.2305 20.4807 20.3004 20.4695 20.3647 20.4441C20.4764 20.3998 20.6478 20.4283 20.6417 20.2311C20.635 20.0253 20.5784 19.8499 20.3611 19.7716C19.9035 19.6065 19.5622 19.7325 19.2964 20.1788C19.1602 20.1544 19.0173 20.1284 18.8745 20.103C18.7383 20.0786 18.6021 20.0547 18.4398 20.0263C18.5607 19.5887 18.7842 19.2507 19.2168 19.1104C19.7759 18.929 20.3499 18.9366 20.91 19.1028C21.3692 19.239 21.5676 19.5475 21.5865 20.0944C21.5982 20.4415 21.586 20.7892 21.5895 21.1363C21.5952 21.6725 21.5283 22.2169 21.8084 22.7678V22.7673ZM20.6177 20.9889C20.2677 21.0799 19.9254 21.1465 19.5994 21.2593C19.2724 21.3726 19.2046 21.7416 19.4372 21.9947C19.5928 22.164 19.8693 22.2133 20.1269 22.1177C20.5636 21.9561 20.7044 21.6491 20.6177 20.9889Z" fill="#03045E"/>
                            <path d="M27.7022 21.1922C27.6747 21.644 27.9083 22.0029 28.269 22.0994C28.6629 22.2052 28.8827 22.0822 29.145 21.6024C29.4434 21.6532 29.7454 21.705 30.0821 21.763C29.9265 22.1935 29.6771 22.506 29.2776 22.6885C28.0104 23.2669 26.7146 22.4791 26.6942 21.0972C26.6886 20.7277 26.7401 20.3358 26.8656 19.9902C27.1278 19.268 27.8099 18.9183 28.6476 19.0006C29.3587 19.0708 29.9056 19.5699 30.0597 20.3058C30.1051 20.5239 30.1163 20.749 30.1464 20.9701C30.1699 21.1434 30.0985 21.1978 29.9245 21.1953C29.273 21.1866 28.6215 21.1917 27.9701 21.1917C27.8859 21.1917 27.8022 21.1917 27.7022 21.1917V21.1922ZM27.714 20.5823H29.1674C29.1368 20.0075 28.8246 19.6827 28.367 19.7239C27.9578 19.761 27.6681 20.1366 27.714 20.5823Z" fill="#03045E"/>
                            <path d="M24.3602 15.0393H21.8977C21.8385 15.4327 22.0727 15.8179 22.4226 15.9282C22.82 16.0533 23.0797 15.9186 23.3297 15.4444C23.6261 15.4932 23.9281 15.543 24.2684 15.5989C24.1271 15.9963 23.9092 16.2881 23.5618 16.4756C22.3068 17.1541 20.9172 16.3562 20.8666 14.9265C20.8473 14.3831 20.9223 13.8571 21.2829 13.42C21.7482 12.8563 22.3645 12.7278 23.0481 12.8818C23.7052 13.0297 24.0863 13.4734 24.2347 14.1107C24.3026 14.4009 24.3163 14.7033 24.3597 15.0393H24.3602ZM21.9053 14.4502C22.3481 14.4502 22.7956 14.4538 23.243 14.4446C23.2833 14.4436 23.3542 14.3638 23.3557 14.3186C23.369 13.8927 22.9807 13.5257 22.5639 13.5593C22.1431 13.5933 21.837 13.9923 21.9053 14.4502Z" fill="#03045E"/>
                            <path d="M14.5667 16.5864H13.5515V12.3623H12.0353V11.5201H16.0783V12.3456H14.5667V16.5864Z" fill="#03045E"/>
                            <path d="M35.9515 16.5839H34.9245V12.3572H33.4068C33.4068 12.0955 33.3976 11.852 33.4155 11.6106C33.4186 11.5694 33.5349 11.5064 33.6002 11.5054C34.0348 11.4958 34.4695 11.5003 34.9041 11.5003C35.6648 11.5003 36.4254 11.5003 37.1861 11.5003H37.4559V12.3476H35.952V16.5844L35.9515 16.5839Z" fill="#03045E"/>
                            <path d="M16.9338 21.3594C17.2486 21.4143 17.5506 21.4662 17.8705 21.5221C17.7251 22.1177 17.4348 22.5584 16.84 22.7464C15.9885 23.0148 15.1233 22.7642 14.6968 22.1269C14.2703 21.489 14.2581 20.3277 14.7065 19.7056C14.855 19.4997 15.0641 19.3081 15.2886 19.1922C15.8477 18.903 16.4426 18.9163 17.0211 19.147C17.4557 19.3203 17.6802 19.6873 17.822 20.1472C17.5119 20.2057 17.2109 20.2621 16.8951 20.3216C16.8767 20.2804 16.8583 20.2418 16.8425 20.2021C16.7216 19.8921 16.5043 19.7274 16.1931 19.7376C15.8768 19.7478 15.6059 19.8723 15.4927 20.1828C15.3182 20.6626 15.3274 21.1556 15.511 21.6308C15.6263 21.9292 15.9248 22.0776 16.2676 22.0583C16.564 22.041 16.7553 21.8834 16.868 21.5632C16.8884 21.5058 16.9063 21.4468 16.9344 21.3599L16.9338 21.3594Z" fill="#03045E"/>
                            <path d="M27.1166 14.0279C27.1166 13.2635 27.1217 12.4985 27.113 11.7341C27.111 11.5522 27.1666 11.4841 27.3513 11.4973C27.5441 11.511 27.7395 11.5085 27.9328 11.4978C28.0961 11.4886 28.1481 11.5501 28.1466 11.7113C28.14 12.4528 28.1435 13.1943 28.1435 13.9359C28.1435 14.747 28.1389 15.5577 28.1471 16.3689C28.1491 16.5508 28.0935 16.6189 27.9089 16.6057C27.716 16.592 27.5206 16.5945 27.3273 16.6052C27.164 16.6144 27.112 16.5529 27.1135 16.3918C27.1196 15.604 27.1166 14.8162 27.1166 14.0284V14.0279Z" fill="#03045E"/>
                            <path d="M7.26688 27.1454C7.3383 27.0198 7.43523 26.9212 7.55818 26.8506C7.68112 26.7799 7.81734 26.7444 7.96783 26.7444C8.14435 26.7444 8.29842 26.7866 8.42953 26.8714C8.56115 26.9563 8.65706 27.0763 8.71725 27.2323H8.46422C8.41932 27.1352 8.35453 27.0605 8.27036 27.0082C8.18618 26.9558 8.08517 26.9299 7.96732 26.9299C7.84948 26.9299 7.75306 26.9563 7.66327 27.0082C7.57348 27.0605 7.50308 27.1352 7.45207 27.2313C7.40105 27.3278 7.37503 27.4407 7.37503 27.5698C7.37503 27.6989 7.40054 27.8097 7.45207 27.9062C7.50308 28.0028 7.57348 28.077 7.66327 28.1294C7.75306 28.1817 7.85458 28.2076 7.96732 28.2076C8.08007 28.2076 8.18567 28.1817 8.27036 28.1304C8.35504 28.079 8.41932 28.0043 8.46422 27.9073H8.71725C8.65706 28.0613 8.56115 28.1807 8.42953 28.2646C8.29791 28.3484 8.14435 28.3906 7.96783 28.3906C7.81785 28.3906 7.68112 28.3555 7.55818 28.2854C7.43523 28.2153 7.3383 28.1177 7.26688 27.9926C7.19597 27.8676 7.16026 27.7268 7.16026 27.5698C7.16026 27.4127 7.19597 27.2714 7.26688 27.1454Z" fill="#03045E"/>
                            <path d="M9.2621 28.3164C9.16517 28.2625 9.08916 28.1858 9.03457 28.0862C8.97947 27.9865 8.95244 27.8717 8.95244 27.7405C8.95244 27.6094 8.9805 27.4966 9.03712 27.3975C9.09375 27.2979 9.17078 27.2216 9.26822 27.1688C9.36566 27.1154 9.47484 27.089 9.59523 27.089C9.71563 27.089 9.82532 27.1154 9.92276 27.1688C10.0202 27.2221 10.0972 27.2979 10.1539 27.3965C10.2105 27.4951 10.2385 27.6099 10.2385 27.7411C10.2385 27.8722 10.2095 27.987 10.1513 28.0867C10.0931 28.1858 10.0146 28.263 9.91561 28.3169C9.81664 28.3708 9.70645 28.3977 9.58605 28.3977C9.46566 28.3977 9.35903 28.3708 9.26261 28.3169L9.2621 28.3164ZM9.79879 28.1588C9.86511 28.1233 9.91919 28.0704 9.96 27.9992C10.0008 27.9286 10.0212 27.8422 10.0212 27.74C10.0212 27.6379 10.0013 27.552 9.96102 27.4813C9.92072 27.4107 9.86817 27.3578 9.80338 27.3228C9.73859 27.2882 9.66768 27.2709 9.59217 27.2709C9.51667 27.2709 9.44423 27.2882 9.37995 27.3228C9.31567 27.3573 9.26414 27.4102 9.22537 27.4813C9.1866 27.552 9.16721 27.6384 9.16721 27.74C9.16721 27.8417 9.1866 27.9306 9.22435 28.0013C9.26261 28.0719 9.31312 28.1248 9.37638 28.1599C9.43964 28.1944 9.50953 28.2117 9.58503 28.2117C9.66054 28.2117 9.73196 28.1939 9.79879 28.1583V28.1588Z" fill="#03045E"/>
                            <path d="M11.5114 27.2262C11.6073 27.3197 11.6553 27.4539 11.6553 27.6297V28.3764H11.4461V27.6597C11.4461 27.5332 11.4145 27.4366 11.3507 27.3695C11.2874 27.3024 11.2007 27.2689 11.091 27.2689C10.9813 27.2689 10.891 27.3035 10.8252 27.3731C10.7594 27.4422 10.7268 27.5433 10.7268 27.676V28.3764H10.5156V27.1093H10.7268V27.2897C10.7686 27.2252 10.8252 27.1749 10.8977 27.1393C10.9696 27.1037 11.0487 27.0859 11.1354 27.0859C11.2905 27.0859 11.4155 27.1327 11.5114 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M12.997 27.2262C13.0929 27.3197 13.1408 27.4539 13.1408 27.6297V28.3764H12.9317V27.6597C12.9317 27.5332 12.9 27.4366 12.8363 27.3695C12.773 27.3024 12.6863 27.2689 12.5766 27.2689C12.4669 27.2689 12.3766 27.3035 12.3108 27.3731C12.245 27.4422 12.2123 27.5433 12.2123 27.676V28.3764H12.0011V27.1093H12.2123V27.2897C12.2542 27.2252 12.3108 27.1749 12.3832 27.1393C12.4552 27.1037 12.5343 27.0859 12.621 27.0859C12.7761 27.0859 12.9011 27.1327 12.997 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M14.6397 27.8214H13.6229C13.6306 27.9464 13.6739 28.044 13.752 28.1141C13.8301 28.1843 13.9249 28.2193 14.0362 28.2193C14.1275 28.2193 14.2035 28.198 14.2647 28.1558C14.3254 28.1136 14.3688 28.0567 14.3933 27.986H14.6208C14.5866 28.108 14.5188 28.2066 14.4167 28.2834C14.3147 28.3596 14.1877 28.3977 14.0362 28.3977C13.9158 28.3977 13.8076 28.3708 13.7122 28.3169C13.6168 28.263 13.5423 28.1863 13.4882 28.0867C13.4342 27.987 13.4071 27.8722 13.4071 27.7411C13.4071 27.6099 13.4337 27.4951 13.4862 27.3965C13.5388 27.2979 13.6127 27.2216 13.7076 27.1688C13.8025 27.1159 13.9122 27.089 14.0362 27.089C14.1601 27.089 14.2637 27.1154 14.3565 27.1677C14.4494 27.2201 14.5208 27.2923 14.5713 27.3838C14.6218 27.4757 14.6468 27.5794 14.6468 27.6948C14.6468 27.735 14.6443 27.7771 14.6397 27.8219V27.8214ZM14.3754 27.4437C14.3397 27.3858 14.2917 27.3421 14.2305 27.3121C14.1698 27.2821 14.102 27.2669 14.0275 27.2669C13.9209 27.2669 13.83 27.3009 13.7545 27.3685C13.679 27.4361 13.6367 27.5301 13.626 27.6506H14.429C14.429 27.5708 14.4111 27.5017 14.3754 27.4437Z" fill="#03045E"/>
                            <path d="M14.9254 27.3975C14.9779 27.2994 15.0509 27.2237 15.1447 27.1698C15.2381 27.1159 15.3452 27.089 15.4661 27.089C15.6222 27.089 15.7513 27.1271 15.8523 27.2023C15.9538 27.278 16.0207 27.3827 16.0533 27.5164H15.8258C15.8044 27.4392 15.762 27.3787 15.6993 27.3339C15.6365 27.2892 15.559 27.2669 15.4661 27.2669C15.3457 27.2669 15.2483 27.308 15.1738 27.3904C15.0993 27.4727 15.0621 27.5896 15.0621 27.7405C15.0621 27.8915 15.0993 28.0109 15.1738 28.0943C15.2483 28.1776 15.3457 28.2193 15.4661 28.2193C15.559 28.2193 15.6365 28.198 15.6982 28.1548C15.76 28.1116 15.8023 28.0501 15.8258 27.9698H16.0533C16.0191 28.0994 15.9513 28.2031 15.8493 28.2808C15.7472 28.3586 15.6192 28.3977 15.4661 28.3977C15.3457 28.3977 15.2381 28.3708 15.1447 28.3169C15.0509 28.263 14.9779 28.1868 14.9254 28.0882C14.8728 27.9896 14.8463 27.8737 14.8463 27.7416C14.8463 27.6094 14.8728 27.4961 14.9254 27.3985V27.3975Z" fill="#03045E"/>
                            <path d="M16.5915 27.2826V28.0292C16.5915 28.0907 16.6048 28.1344 16.6308 28.1599C16.6573 28.1853 16.7027 28.198 16.768 28.198H16.9236V28.3759H16.7334C16.6155 28.3759 16.5278 28.3489 16.4686 28.2951C16.4099 28.2412 16.3803 28.1522 16.3803 28.0292V27.2826H16.2155V27.1093H16.3803V26.7901H16.5915V27.1093H16.9231V27.2826H16.5915Z" fill="#03045E"/>
                            <path d="M17.1859 26.8618C17.1583 26.8338 17.144 26.8003 17.144 26.7601C17.144 26.72 17.1583 26.6859 17.1859 26.6585C17.2139 26.6305 17.2476 26.6168 17.2879 26.6168C17.3282 26.6168 17.3593 26.6305 17.3864 26.6585C17.4134 26.6864 17.4272 26.72 17.4272 26.7601C17.4272 26.8003 17.4134 26.8343 17.3864 26.8618C17.3593 26.8897 17.3267 26.9035 17.2879 26.9035C17.2491 26.9035 17.2134 26.8897 17.1859 26.8618ZM17.3899 27.1093V28.3764H17.1787V27.1093H17.3899Z" fill="#03045E"/>
                            <path d="M18.7454 27.2262C18.8413 27.3197 18.8893 27.4539 18.8893 27.6297V28.3764H18.6801V27.6597C18.6801 27.5332 18.6485 27.4366 18.5847 27.3695C18.5215 27.3024 18.4347 27.2689 18.325 27.2689C18.2154 27.2689 18.1251 27.3035 18.0593 27.3731C17.9934 27.4422 17.9608 27.5433 17.9608 27.676V28.3764H17.7496V27.1093H17.9608V27.2897C18.0026 27.2252 18.0593 27.1749 18.1317 27.1393C18.2036 27.1037 18.2827 27.0859 18.3694 27.0859C18.5245 27.0859 18.6495 27.1327 18.7454 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M20.0484 27.1601C20.131 27.2079 20.1927 27.2679 20.233 27.3406V27.1093H20.4463V28.4038C20.4463 28.5197 20.4218 28.6224 20.3718 28.7123C20.3223 28.8023 20.2519 28.8729 20.1596 28.9238C20.0677 28.9746 19.9606 29 19.8382 29C19.6708 29 19.5316 28.9609 19.4204 28.8821C19.3091 28.8033 19.2433 28.6961 19.2234 28.5609H19.4321C19.455 28.6381 19.5035 28.6996 19.5759 28.7469C19.6489 28.7942 19.7361 28.8175 19.8382 28.8175C19.9545 28.8175 20.0489 28.7814 20.1223 28.7088C20.1958 28.6361 20.2325 28.5344 20.2325 28.4038V28.138C20.1907 28.2117 20.129 28.2737 20.0468 28.323C19.9652 28.3723 19.8693 28.3972 19.7591 28.3972C19.6489 28.3972 19.5438 28.3693 19.4515 28.3139C19.3596 28.2585 19.2872 28.1807 19.2347 28.0806C19.1821 27.9804 19.1556 27.8661 19.1556 27.7385C19.1556 27.6109 19.1821 27.4956 19.2347 27.3975C19.2872 27.2994 19.3596 27.2237 19.4515 27.1698C19.5438 27.1159 19.6464 27.089 19.7591 27.089C19.8718 27.089 19.9652 27.1129 20.0484 27.1606V27.1601ZM20.1749 27.491C20.1361 27.4198 20.0841 27.3655 20.0183 27.3278C19.9524 27.2902 19.8805 27.2714 19.8014 27.2714C19.7224 27.2714 19.6504 27.2897 19.5856 27.3268C19.5209 27.3639 19.4688 27.4178 19.43 27.4885C19.3913 27.5591 19.3719 27.6425 19.3719 27.738C19.3719 27.8336 19.3913 27.9195 19.43 27.9911C19.4688 28.0628 19.5203 28.1177 19.5856 28.1553C19.6509 28.1929 19.7229 28.2117 19.8014 28.2117C19.88 28.2117 19.953 28.1929 20.0183 28.1553C20.0836 28.1177 20.1361 28.0628 20.1749 27.9911C20.2136 27.9195 20.233 27.8356 20.233 27.74C20.233 27.6445 20.2136 27.5611 20.1749 27.4905V27.491Z" fill="#03045E"/>
                            <path d="M22.4191 26.7647V26.936H21.9783V28.3764H21.7671V26.936H21.3238V26.7647H22.4191Z" fill="#03045E"/>
                            <path d="M23.57 27.1474C23.6475 27.1881 23.7077 27.2496 23.7521 27.3314C23.7965 27.4132 23.8184 27.5123 23.8184 27.6297V28.3764H23.6093V27.6597C23.6093 27.5332 23.5776 27.4366 23.5139 27.3695C23.4506 27.3024 23.3639 27.2689 23.2542 27.2689C23.1445 27.2689 23.0542 27.3035 22.9884 27.3731C22.9226 27.4422 22.8899 27.5433 22.8899 27.676V28.3764H22.6787V26.6656H22.8899V27.2897C22.9318 27.2252 22.9894 27.1749 23.0629 27.1393C23.1363 27.1037 23.219 27.0859 23.3098 27.0859C23.4057 27.0859 23.4924 27.1062 23.57 27.1469V27.1474Z" fill="#03045E"/>
                            <path d="M25.3178 27.8214H24.301C24.3087 27.9464 24.352 28.044 24.4301 28.1141C24.5081 28.1843 24.603 28.2193 24.7143 28.2193C24.8056 28.2193 24.8816 28.198 24.9428 28.1558C25.0035 28.1136 25.0469 28.0567 25.0714 27.986H25.2989C25.2647 28.108 25.1969 28.2066 25.0948 28.2834C24.9928 28.3596 24.8658 28.3977 24.7143 28.3977C24.5939 28.3977 24.4857 28.3708 24.3903 28.3169C24.2949 28.263 24.2204 28.1863 24.1663 28.0867C24.1123 27.987 24.0852 27.8722 24.0852 27.7411C24.0852 27.6099 24.1118 27.4951 24.1643 27.3965C24.2169 27.2979 24.2908 27.2216 24.3857 27.1688C24.4806 27.1159 24.5903 27.089 24.7143 27.089C24.8382 27.089 24.9418 27.1154 25.0346 27.1677C25.1275 27.2201 25.1989 27.2923 25.2494 27.3838C25.2999 27.4757 25.3249 27.5794 25.3249 27.6948C25.3249 27.735 25.3224 27.7771 25.3178 27.8219V27.8214ZM25.053 27.4437C25.0173 27.3858 24.9693 27.3421 24.9081 27.3121C24.8474 27.2821 24.7796 27.2669 24.7051 27.2669C24.5984 27.2669 24.5076 27.3009 24.4321 27.3685C24.3566 27.4361 24.3143 27.5301 24.3036 27.6506H25.1066C25.1066 27.5708 25.0887 27.5017 25.053 27.4437Z" fill="#03045E"/>
                            <path d="M26.4289 26.7647V27.7843C26.4289 27.9276 26.4641 28.0338 26.5345 28.1034C26.6049 28.1726 26.7028 28.2076 26.8283 28.2076C26.9538 28.2076 27.0492 28.1731 27.1196 28.1034C27.19 28.0343 27.2252 27.9276 27.2252 27.7843V26.7647H27.4365V27.7822C27.4365 27.9164 27.4094 28.0292 27.3553 28.1207C27.3013 28.2122 27.2283 28.2808 27.136 28.3255C27.0441 28.3703 26.9406 28.3926 26.8263 28.3926C26.712 28.3926 26.6085 28.3703 26.5161 28.3255C26.4243 28.2808 26.3513 28.2127 26.2983 28.1207C26.2452 28.0287 26.2182 27.9159 26.2182 27.7822V26.7647H26.4294H26.4289Z" fill="#03045E"/>
                            <path d="M28.7843 27.2262C28.8802 27.3197 28.9282 27.4539 28.9282 27.6297V28.3764H28.719V27.6597C28.719 27.5332 28.6874 27.4366 28.6236 27.3695C28.5603 27.3024 28.4736 27.2689 28.3639 27.2689C28.2542 27.2689 28.1639 27.3035 28.0981 27.3731C28.0323 27.4422 27.9997 27.5433 27.9997 27.676V28.3764H27.7885V27.1093H27.9997V27.2897C28.0415 27.2252 28.0981 27.1749 28.1706 27.1393C28.2425 27.1037 28.3216 27.0859 28.4083 27.0859C28.5634 27.0859 28.6884 27.1327 28.7843 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M29.274 27.3975C29.3266 27.2994 29.3995 27.2237 29.4934 27.1698C29.5868 27.1159 29.6939 27.089 29.8148 27.089C29.9709 27.089 30.1 27.1271 30.201 27.2023C30.3025 27.278 30.3693 27.3827 30.402 27.5164H30.1745C30.153 27.4392 30.1107 27.3787 30.0479 27.3339C29.9852 27.2892 29.9077 27.2669 29.8148 27.2669C29.6944 27.2669 29.597 27.308 29.5225 27.3904C29.448 27.4727 29.4108 27.5896 29.4108 27.7405C29.4108 27.8915 29.448 28.0109 29.5225 28.0943C29.597 28.1776 29.6944 28.2193 29.8148 28.2193C29.9077 28.2193 29.9852 28.198 30.0469 28.1548C30.1087 28.1116 30.151 28.0501 30.1745 27.9698H30.402C30.3678 28.0994 30.3 28.2031 30.1979 28.2808C30.0959 28.3586 29.9679 28.3977 29.8148 28.3977C29.6944 28.3977 29.5868 28.3708 29.4934 28.3169C29.3995 28.263 29.3266 28.1868 29.274 28.0882C29.2215 27.9896 29.195 27.8737 29.195 27.7416C29.195 27.6094 29.2215 27.4961 29.274 27.3985V27.3975Z" fill="#03045E"/>
                            <path d="M30.9137 28.3164C30.8168 28.2625 30.7407 28.1858 30.6862 28.0862C30.6311 27.9865 30.604 27.8717 30.604 27.7405C30.604 27.6094 30.6321 27.4966 30.6887 27.3975C30.7453 27.2979 30.8224 27.2216 30.9198 27.1688C31.0172 27.1159 31.1264 27.089 31.2468 27.089C31.3672 27.089 31.4769 27.1154 31.5743 27.1688C31.6718 27.2221 31.7488 27.2979 31.8054 27.3965C31.8621 27.4951 31.8901 27.6099 31.8901 27.7411C31.8901 27.8722 31.861 27.987 31.8029 28.0867C31.7447 28.1858 31.6662 28.263 31.5672 28.3169C31.4682 28.3708 31.358 28.3977 31.2376 28.3977C31.1172 28.3977 31.0106 28.3708 30.9142 28.3169L30.9137 28.3164ZM31.4509 28.1588C31.5172 28.1233 31.5713 28.0704 31.6121 27.9992C31.6529 27.9286 31.6733 27.8422 31.6733 27.74C31.6733 27.6379 31.6534 27.552 31.6131 27.4813C31.5728 27.4107 31.5203 27.3578 31.4555 27.3228C31.3907 27.2882 31.3198 27.2709 31.2443 27.2709C31.1688 27.2709 31.0963 27.2882 31.032 27.3228C30.9678 27.3573 30.9162 27.4102 30.8775 27.4813C30.8387 27.552 30.8193 27.6384 30.8193 27.74C30.8193 27.8417 30.8387 27.9306 30.8764 28.0013C30.9147 28.0719 30.9652 28.1248 31.0285 28.1599C31.0917 28.1944 31.1616 28.2117 31.2371 28.2117C31.3126 28.2117 31.384 28.1939 31.4509 28.1583V28.1588Z" fill="#03045E"/>
                            <path d="M33.1635 27.2262C33.2594 27.3197 33.3073 27.4539 33.3073 27.6297V28.3764H33.0982V27.6597C33.0982 27.5332 33.0665 27.4366 33.0028 27.3695C32.9395 27.3024 32.8528 27.2689 32.7431 27.2689C32.6334 27.2689 32.5431 27.3035 32.4773 27.3731C32.4115 27.4422 32.3789 27.5433 32.3789 27.676V28.3764H32.1676V27.1093H32.3789V27.2897C32.4207 27.2252 32.4773 27.1749 32.5498 27.1393C32.6217 27.1037 32.7008 27.0859 32.7875 27.0859C32.9426 27.0859 33.0676 27.1327 33.1635 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M34.6491 27.2262C34.745 27.3197 34.7929 27.4539 34.7929 27.6297V28.3764H34.5838V27.6597C34.5838 27.5332 34.5521 27.4366 34.4884 27.3695C34.4251 27.3024 34.3384 27.2689 34.2287 27.2689C34.119 27.2689 34.0287 27.3035 33.9629 27.3731C33.8971 27.4422 33.8644 27.5433 33.8644 27.676V28.3764H33.6532V27.1093H33.8644V27.2897C33.9063 27.2252 33.9629 27.1749 34.0353 27.1393C34.1073 27.1037 34.1863 27.0859 34.2731 27.0859C34.4282 27.0859 34.5531 27.1327 34.6491 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M36.2923 27.8214H35.2755C35.2832 27.9464 35.3265 28.044 35.4046 28.1141C35.4827 28.1843 35.5775 28.2193 35.6888 28.2193C35.7801 28.2193 35.8561 28.198 35.9173 28.1558C35.978 28.1136 36.0214 28.0567 36.0459 27.986H36.2734C36.2392 28.108 36.1714 28.2066 36.0693 28.2834C35.9673 28.3596 35.8403 28.3977 35.6888 28.3977C35.5684 28.3977 35.4602 28.3708 35.3648 28.3169C35.2694 28.263 35.1949 28.1863 35.1408 28.0867C35.0868 27.987 35.0597 27.8722 35.0597 27.7411C35.0597 27.6099 35.0863 27.4951 35.1388 27.3965C35.1914 27.2979 35.2653 27.2216 35.3602 27.1688C35.4551 27.1159 35.5648 27.089 35.6888 27.089C35.8127 27.089 35.9163 27.1154 36.0091 27.1677C36.102 27.2201 36.1734 27.2923 36.2239 27.3838C36.2744 27.4757 36.2994 27.5794 36.2994 27.6948C36.2994 27.735 36.2969 27.7771 36.2923 27.8219V27.8214ZM36.0275 27.4437C35.9918 27.3858 35.9438 27.3421 35.8826 27.3121C35.8219 27.2821 35.7541 27.2669 35.6796 27.2669C35.573 27.2669 35.4821 27.3009 35.4066 27.3685C35.3311 27.4361 35.2888 27.5301 35.2781 27.6506H36.0811C36.0811 27.5708 36.0632 27.5017 36.0275 27.4437Z" fill="#03045E"/>
                            <path d="M36.5774 27.3975C36.63 27.2994 36.7029 27.2237 36.7968 27.1698C36.8902 27.1159 36.9973 27.089 37.1182 27.089C37.2743 27.089 37.4034 27.1271 37.5044 27.2023C37.6059 27.278 37.6728 27.3827 37.7054 27.5164H37.4779C37.4564 27.4392 37.4141 27.3787 37.3514 27.3339C37.2886 27.2892 37.2111 27.2669 37.1182 27.2669C36.9978 27.2669 36.9004 27.308 36.8259 27.3904C36.7514 27.4727 36.7142 27.5896 36.7142 27.7405C36.7142 27.8915 36.7514 28.0109 36.8259 28.0943C36.9004 28.1776 36.9978 28.2193 37.1182 28.2193C37.2111 28.2193 37.2886 28.198 37.3503 28.1548C37.4121 28.1116 37.4544 28.0501 37.4779 27.9698H37.7054C37.6712 28.0994 37.6034 28.2031 37.5013 28.2808C37.3993 28.3586 37.2713 28.3977 37.1182 28.3977C36.9978 28.3977 36.8902 28.3708 36.7968 28.3169C36.7029 28.263 36.63 28.1868 36.5774 28.0882C36.5249 27.9896 36.4984 27.8737 36.4984 27.7416C36.4984 27.6094 36.5249 27.4961 36.5774 27.3985V27.3975Z" fill="#03045E"/>
                            <path d="M38.2441 27.2826V28.0292C38.2441 28.0907 38.2574 28.1344 38.2834 28.1599C38.3099 28.1853 38.3554 28.198 38.4207 28.198H38.5762V28.3759H38.386C38.2681 28.3759 38.1804 28.3489 38.1212 28.2951C38.0625 28.2412 38.0329 28.1522 38.0329 28.0292V27.2826H37.8682V27.1093H38.0329V26.7901H38.2441V27.1093H38.5757V27.2826H38.2441Z" fill="#03045E"/>
                            <path d="M39.9843 27.8214H38.9675C38.9752 27.9464 39.0186 28.044 39.0966 28.1141C39.1747 28.1843 39.2696 28.2193 39.3808 28.2193C39.4721 28.2193 39.5481 28.198 39.6093 28.1558C39.67 28.1136 39.7134 28.0567 39.7379 27.986H39.9654C39.9312 28.108 39.8634 28.2066 39.7613 28.2834C39.6593 28.3596 39.5323 28.3977 39.3808 28.3977C39.2604 28.3977 39.1522 28.3708 39.0568 28.3169C38.9614 28.263 38.8869 28.1863 38.8329 28.0867C38.7788 27.987 38.7517 27.8722 38.7517 27.7411C38.7517 27.6099 38.7783 27.4951 38.8308 27.3965C38.8834 27.2979 38.9573 27.2216 39.0522 27.1688C39.1471 27.1159 39.2568 27.089 39.3808 27.089C39.5047 27.089 39.6083 27.1154 39.7011 27.1677C39.794 27.2201 39.8654 27.2923 39.9159 27.3838C39.9664 27.4757 39.9914 27.5794 39.9914 27.6948C39.9914 27.735 39.9889 27.7771 39.9843 27.8219V27.8214ZM39.7195 27.4437C39.6838 27.3858 39.6358 27.3421 39.5746 27.3121C39.5139 27.2821 39.4461 27.2669 39.3716 27.2669C39.265 27.2669 39.1742 27.3009 39.0987 27.3685C39.0231 27.4361 38.9808 27.5301 38.9701 27.6506H39.7731C39.7731 27.5708 39.7552 27.5017 39.7195 27.4437Z" fill="#03045E"/>
                            <path d="M40.27 27.3975C40.3225 27.2994 40.395 27.2237 40.4868 27.1698C40.5791 27.1159 40.6822 27.089 40.797 27.089C40.8959 27.089 40.9878 27.1118 41.073 27.1571C41.1582 27.2028 41.2229 27.2623 41.2683 27.3365V26.6661H41.4816V28.3769H41.2683V28.139C41.2265 28.2147 41.1648 28.2762 41.0826 28.325C41.001 28.3738 40.9051 28.3977 40.7949 28.3977C40.6847 28.3977 40.5796 28.3698 40.4873 28.3144C40.3955 28.259 40.323 28.1812 40.2705 28.0811C40.2179 27.981 40.1914 27.8666 40.1914 27.739C40.1914 27.6115 40.2179 27.4961 40.2705 27.398L40.27 27.3975ZM41.2097 27.491C41.1709 27.4198 41.1189 27.3655 41.0531 27.3278C40.9872 27.2902 40.9153 27.2714 40.8362 27.2714C40.7572 27.2714 40.6852 27.2897 40.6204 27.3268C40.5557 27.3639 40.5036 27.4178 40.4648 27.4885C40.4261 27.5591 40.4067 27.6425 40.4067 27.738C40.4067 27.8336 40.4261 27.9195 40.4648 27.9911C40.5036 28.0628 40.5551 28.1177 40.6204 28.1553C40.6857 28.1929 40.7577 28.2117 40.8362 28.2117C40.9148 28.2117 40.9878 28.1929 41.0531 28.1553C41.1184 28.1177 41.1709 28.0628 41.2097 27.9911C41.2484 27.9195 41.2678 27.8356 41.2678 27.74C41.2678 27.6445 41.2484 27.5611 41.2097 27.4905V27.491Z" fill="#03045E"/>
                    </svg>
                    <span class="content">
                        <span>Certification Course for Java Full Stack Developer by The IoT Academy</span>
                        <span class="duration">6 months</span>
                    </span>
                </a>
            </div>
            <div class="nsh-ach-right technology d-none">
                <a href="http://localhost/TIA/digital-marketing-training">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="29" viewBox="0 0 48 29" fill="none">
                            <path d="M20.3044 5.93391C20.5931 5.53086 20.8533 5.12833 21.1523 4.75578C23.1934 2.21502 25.835 0.672469 29.0587 0.173872C32.7083 -0.390797 36.0443 0.414276 38.9761 2.67143C41.4075 4.54282 42.8967 7.01039 43.4186 10.035C43.6425 11.3321 43.617 12.6337 43.3696 13.9262C43.3308 14.1285 43.3885 14.1915 43.5548 14.2657C45.2684 15.0342 46.5612 16.2438 47.3478 17.9511C48.8717 21.2572 47.6498 25.1225 44.4685 27.0386C43.9624 27.3436 43.3977 27.555 42.8518 27.7903C42.6003 27.8986 42.4391 27.8269 42.3697 27.6353C42.2978 27.4356 42.3947 27.2999 42.6646 27.2023C45.0241 26.3484 46.6198 24.7652 47.2075 22.3078C47.832 19.6969 46.7234 17.0032 44.4501 15.4835C43.9716 15.1633 43.4313 14.9336 42.913 14.6743C42.6819 14.5585 42.6197 14.4721 42.6758 14.221C43.6961 9.68075 41.7045 5.22642 37.965 2.70192C36.1749 1.4933 34.199 0.774628 32.039 0.667386C27.1742 0.425966 23.3766 2.29837 20.7615 6.43708C20.6064 6.68206 20.4722 6.72678 20.1983 6.58651C18.7535 5.84801 17.2154 5.55526 15.6053 5.68334C13.6387 5.83988 11.9128 6.5799 10.4701 7.92931C9.11613 9.19486 8.30396 10.7496 8.05449 12.5844C8.00092 12.9803 8.00603 13.3849 7.99582 13.7859C7.98715 14.1219 7.91114 14.1966 7.56882 14.2083C5.02977 14.2967 3.007 15.3427 1.61835 17.4814C0.0123727 19.9561 0.390909 23.2923 2.46674 25.3985C3.37278 26.3179 4.43901 26.9593 5.6843 27.3065C5.71389 27.3146 5.74246 27.3278 5.77256 27.3354C5.96999 27.3863 6.0608 27.5143 6.02815 27.7121C5.99856 27.8925 5.82255 27.9992 5.61135 27.9255C5.0864 27.7431 4.54461 27.5835 4.05486 27.3283C1.9474 26.23 0.573035 24.539 0.135321 22.1909C-0.323821 19.7279 0.39397 17.5887 2.1586 15.8194C3.51817 14.4558 5.19455 13.7432 7.11886 13.6096C7.25405 13.6004 7.3668 13.6055 7.37547 13.4047C7.51934 10.1046 9.08858 7.6574 11.9807 6.10875C14.5799 4.71715 17.2883 4.68259 20.0126 5.80532C20.1115 5.84598 20.2085 5.89121 20.3049 5.9334L20.3044 5.93391Z" fill="#03045E"/>
                            <path d="M10.5676 23.8793C12.3516 23.8555 14.1351 23.8351 15.9191 23.8072C17.6639 23.7802 19.4081 23.7472 21.1528 23.7152C22.7808 23.6852 24.4092 23.6547 26.0371 23.6222C27.541 23.5927 29.045 23.5622 30.5489 23.5292C31.8743 23.5002 33.2002 23.4727 34.5256 23.4351C35.6102 23.4041 36.6948 23.3619 37.7789 23.3187C38.1462 23.304 38.361 22.9533 38.2268 22.6001C37.8187 21.5261 37.408 20.4527 36.9988 19.3788C36.9662 19.2934 36.9381 19.2065 36.8943 19.0825C37.2259 19.0825 37.5319 19.0738 37.8365 19.0906C37.8875 19.0931 37.9523 19.1983 37.9763 19.268C38.2288 19.9963 38.4737 20.7272 38.7221 21.4575C38.7415 21.5144 38.767 21.5693 38.8104 21.6751C38.8609 21.5393 38.8976 21.4504 38.9283 21.3594C39.1594 20.6646 39.395 19.9709 39.6159 19.2725C39.6654 19.1165 39.7389 19.0682 39.895 19.0758C40.1419 19.088 40.3899 19.0789 40.6664 19.0789C40.6419 19.1633 40.6271 19.2268 40.6046 19.2878C40.119 20.6128 39.6348 21.9383 39.1451 23.2623C38.863 24.0257 38.4946 24.2819 37.6712 24.2738C35.8408 24.2555 34.0103 24.229 32.1804 24.2046C31.1336 24.1909 30.0867 24.1736 29.0399 24.1589C26.8916 24.1279 24.7438 24.0969 22.5956 24.0659C21.5564 24.0506 20.5172 24.0349 19.4785 24.0196C17.3226 23.9886 15.1672 23.9546 13.0112 23.9281C12.1975 23.9185 11.3833 23.9266 10.5691 23.9266L10.5681 23.8788L10.5676 23.8793Z" fill="#03045E"/>
                            <path d="M36.3111 22.751H35.3827C35.3827 22.38 35.3862 22.0257 35.3816 21.6715C35.3755 21.1932 35.3663 20.715 35.3521 20.2367C35.3454 20.0136 35.2648 19.8276 35.0301 19.7518C34.7837 19.6725 34.57 19.7437 34.3873 19.9109C34.1705 20.1096 34.1251 20.3785 34.12 20.6519C34.1093 21.2466 34.1088 21.8413 34.1057 22.4359C34.1037 22.7683 34.1057 22.7683 33.7604 22.7683C33.6206 22.7683 33.4803 22.7607 33.3415 22.7703C33.1849 22.781 33.1436 22.7124 33.1451 22.566C33.1517 22.0329 33.1502 21.4997 33.1461 20.9671C33.1446 20.7434 33.1354 20.5198 33.1206 20.2967C33.0972 19.9465 32.9829 19.7793 32.7462 19.7305C32.4498 19.6695 32.0865 19.8159 31.9922 20.1C31.9013 20.3724 31.8646 20.6707 31.8534 20.9594C31.8325 21.4916 31.8478 22.0252 31.8463 22.5584C31.8463 22.6184 31.836 22.6783 31.8279 22.7663C31.5488 22.7663 31.279 22.7719 31.0096 22.7602C30.9678 22.7581 30.8953 22.6748 30.8953 22.629C30.8917 21.4707 30.8963 20.3119 30.9004 19.1536C30.9004 19.1399 30.9127 19.1257 30.9264 19.0957H31.7779C31.7845 19.2365 31.7906 19.365 31.7988 19.5399C32.1304 19.2151 32.4727 18.992 32.9308 18.9905C33.3895 18.9894 33.7415 19.1607 33.9925 19.5572C34.1037 19.4581 34.1955 19.3671 34.2965 19.2873C34.7087 18.961 35.1715 18.9096 35.6561 19.0758C36.0418 19.2085 36.2943 19.4952 36.3045 19.8997C36.328 20.8405 36.3122 21.7818 36.3122 22.75L36.3111 22.751Z" fill="#03045E"/>
                            <path d="M8.89734 22.7683C9.12079 22.1975 9.32587 21.6751 9.52994 21.1521C9.96255 20.0466 10.3977 18.9422 10.8242 17.8347C10.8773 17.6975 10.941 17.6431 11.091 17.6512C11.3308 17.6644 11.5726 17.6436 11.8114 17.6614C11.8894 17.667 12.0042 17.7305 12.0312 17.7961C12.6893 19.4098 13.3383 21.0275 13.9877 22.6443C13.9959 22.6651 13.9953 22.6895 14.0071 22.7683C13.7581 22.7683 13.5143 22.7485 13.2755 22.7734C13.0061 22.8018 12.8653 22.7119 12.7949 22.4481C12.7317 22.2113 12.6169 21.9886 12.5343 21.7564C12.4929 21.64 12.4343 21.5831 12.3006 21.5841C11.7267 21.5902 11.1527 21.5897 10.5788 21.5841C10.4452 21.5826 10.388 21.64 10.3477 21.7589C10.2544 22.0364 10.1416 22.3073 10.0503 22.5853C10.008 22.7139 9.94775 22.7754 9.80389 22.7703C9.51871 22.7612 9.23302 22.7678 8.89734 22.7678V22.7683ZM12.1425 20.7475C11.9032 20.1071 11.6772 19.5023 11.4512 18.898C11.4333 18.9 11.4155 18.902 11.3976 18.9041C11.1762 19.5109 10.9553 20.1183 10.7263 20.7475H12.1425Z" fill="#03045E"/>
                            <path d="M33.4303 14.5153C33.4303 15.79 32.5584 16.6733 31.3004 16.6728C29.9806 16.6723 29.1557 15.8357 29.1547 14.4975C29.1541 13.2452 30.0332 12.3684 31.2912 12.3664C32.5523 12.3644 33.4303 13.2467 33.4303 14.5158V14.5153ZM32.36 14.5153C32.3008 14.284 32.2697 14.035 32.1743 13.8144C32.0141 13.4439 31.7034 13.2416 31.2963 13.2421C30.879 13.2426 30.5673 13.4546 30.4025 13.8317C30.2066 14.281 30.203 14.7506 30.3984 15.2009C30.5607 15.575 30.855 15.7951 31.2825 15.7996C31.7192 15.8042 32.0243 15.5907 32.187 15.2019C32.2743 14.9936 32.3008 14.7598 32.36 14.5148V14.5153Z" fill="#03045E"/>
                            <path d="M25.0918 22.2499C25.0076 22.3221 24.9729 22.348 24.9423 22.3785C24.2638 23.0616 23.2659 22.9828 22.7134 22.2016C22.2487 21.5454 22.2359 20.285 22.6869 19.6227C23.1664 18.9188 24.1521 18.7765 24.8117 19.3157C24.8632 19.3579 24.9153 19.4001 25.003 19.4713V17.6695H25.9621V22.7658C25.7065 22.7658 25.4601 22.7719 25.2147 22.7597C25.1719 22.7576 25.1066 22.6829 25.0979 22.6341C25.0795 22.5304 25.0923 22.4217 25.0923 22.2494L25.0918 22.2499ZM23.3256 20.8674C23.3343 20.87 23.343 20.8725 23.3516 20.8746C23.3598 21.0052 23.3608 21.1368 23.3787 21.2664C23.3924 21.3645 23.42 21.4626 23.4542 21.5561C23.5725 21.8763 23.8531 22.0817 24.1725 22.0908C24.4622 22.0989 24.7775 21.8966 24.8949 21.5932C25.0709 21.1373 25.0668 20.6687 24.8974 20.2138C24.779 19.8962 24.5286 19.7208 24.1811 19.7223C23.8337 19.7244 23.5868 19.9068 23.4659 20.2189C23.3868 20.4227 23.37 20.6499 23.3256 20.8669V20.8674Z" fill="#03045E"/>
                            <path d="M17.7062 16.5885H16.7834V11.5191H17.7231V13.3631C17.8231 13.2782 17.8771 13.2319 17.9312 13.1862C18.3807 12.8076 18.8857 12.7313 19.4326 12.9265C19.8086 13.0607 20.0647 13.3412 20.0861 13.7275C20.1397 14.6734 20.1438 15.6218 20.1682 16.5859H19.176C19.176 16.4187 19.1775 16.2662 19.176 16.1137C19.1683 15.4652 19.1688 14.8167 19.1474 14.1687C19.1352 13.8012 18.9837 13.6279 18.6852 13.5679C18.372 13.5049 17.9919 13.6548 17.8817 13.9425C17.7904 14.1814 17.744 14.4487 17.7348 14.7059C17.7134 15.3077 17.7261 15.911 17.7246 16.5137C17.7246 16.5285 17.7174 16.5437 17.7067 16.5879L17.7062 16.5885Z" fill="#03045E"/>
                            <path d="M21.8084 22.7673C21.4865 22.7673 21.2238 22.7734 20.9615 22.7622C20.9105 22.7602 20.8457 22.7002 20.8161 22.6504C20.7707 22.5736 20.7503 22.4822 20.7116 22.3754C20.6335 22.4349 20.5748 22.4806 20.5146 22.5249C20.0871 22.8425 19.6101 22.9127 19.1041 22.781C18.6597 22.6656 18.3679 22.3038 18.3414 21.8672C18.3087 21.332 18.5153 20.9564 18.9959 20.7917C19.3739 20.6621 19.7734 20.5935 20.1631 20.4974C20.2305 20.4807 20.3004 20.4695 20.3647 20.4441C20.4764 20.3998 20.6478 20.4283 20.6417 20.2311C20.635 20.0253 20.5784 19.8499 20.3611 19.7716C19.9035 19.6065 19.5622 19.7325 19.2964 20.1788C19.1602 20.1544 19.0173 20.1284 18.8745 20.103C18.7383 20.0786 18.6021 20.0547 18.4398 20.0263C18.5607 19.5887 18.7842 19.2507 19.2168 19.1104C19.7759 18.929 20.3499 18.9366 20.91 19.1028C21.3692 19.239 21.5676 19.5475 21.5865 20.0944C21.5982 20.4415 21.586 20.7892 21.5895 21.1363C21.5952 21.6725 21.5283 22.2169 21.8084 22.7678V22.7673ZM20.6177 20.9889C20.2677 21.0799 19.9254 21.1465 19.5994 21.2593C19.2724 21.3726 19.2046 21.7416 19.4372 21.9947C19.5928 22.164 19.8693 22.2133 20.1269 22.1177C20.5636 21.9561 20.7044 21.6491 20.6177 20.9889Z" fill="#03045E"/>
                            <path d="M27.7022 21.1922C27.6747 21.644 27.9083 22.0029 28.269 22.0994C28.6629 22.2052 28.8827 22.0822 29.145 21.6024C29.4434 21.6532 29.7454 21.705 30.0821 21.763C29.9265 22.1935 29.6771 22.506 29.2776 22.6885C28.0104 23.2669 26.7146 22.4791 26.6942 21.0972C26.6886 20.7277 26.7401 20.3358 26.8656 19.9902C27.1278 19.268 27.8099 18.9183 28.6476 19.0006C29.3587 19.0708 29.9056 19.5699 30.0597 20.3058C30.1051 20.5239 30.1163 20.749 30.1464 20.9701C30.1699 21.1434 30.0985 21.1978 29.9245 21.1953C29.273 21.1866 28.6215 21.1917 27.9701 21.1917C27.8859 21.1917 27.8022 21.1917 27.7022 21.1917V21.1922ZM27.714 20.5823H29.1674C29.1368 20.0075 28.8246 19.6827 28.367 19.7239C27.9578 19.761 27.6681 20.1366 27.714 20.5823Z" fill="#03045E"/>
                            <path d="M24.3602 15.0393H21.8977C21.8385 15.4327 22.0727 15.8179 22.4226 15.9282C22.82 16.0533 23.0797 15.9186 23.3297 15.4444C23.6261 15.4932 23.9281 15.543 24.2684 15.5989C24.1271 15.9963 23.9092 16.2881 23.5618 16.4756C22.3068 17.1541 20.9172 16.3562 20.8666 14.9265C20.8473 14.3831 20.9223 13.8571 21.2829 13.42C21.7482 12.8563 22.3645 12.7278 23.0481 12.8818C23.7052 13.0297 24.0863 13.4734 24.2347 14.1107C24.3026 14.4009 24.3163 14.7033 24.3597 15.0393H24.3602ZM21.9053 14.4502C22.3481 14.4502 22.7956 14.4538 23.243 14.4446C23.2833 14.4436 23.3542 14.3638 23.3557 14.3186C23.369 13.8927 22.9807 13.5257 22.5639 13.5593C22.1431 13.5933 21.837 13.9923 21.9053 14.4502Z" fill="#03045E"/>
                            <path d="M14.5667 16.5864H13.5515V12.3623H12.0353V11.5201H16.0783V12.3456H14.5667V16.5864Z" fill="#03045E"/>
                            <path d="M35.9515 16.5839H34.9245V12.3572H33.4068C33.4068 12.0955 33.3976 11.852 33.4155 11.6106C33.4186 11.5694 33.5349 11.5064 33.6002 11.5054C34.0348 11.4958 34.4695 11.5003 34.9041 11.5003C35.6648 11.5003 36.4254 11.5003 37.1861 11.5003H37.4559V12.3476H35.952V16.5844L35.9515 16.5839Z" fill="#03045E"/>
                            <path d="M16.9338 21.3594C17.2486 21.4143 17.5506 21.4662 17.8705 21.5221C17.7251 22.1177 17.4348 22.5584 16.84 22.7464C15.9885 23.0148 15.1233 22.7642 14.6968 22.1269C14.2703 21.489 14.2581 20.3277 14.7065 19.7056C14.855 19.4997 15.0641 19.3081 15.2886 19.1922C15.8477 18.903 16.4426 18.9163 17.0211 19.147C17.4557 19.3203 17.6802 19.6873 17.822 20.1472C17.5119 20.2057 17.2109 20.2621 16.8951 20.3216C16.8767 20.2804 16.8583 20.2418 16.8425 20.2021C16.7216 19.8921 16.5043 19.7274 16.1931 19.7376C15.8768 19.7478 15.6059 19.8723 15.4927 20.1828C15.3182 20.6626 15.3274 21.1556 15.511 21.6308C15.6263 21.9292 15.9248 22.0776 16.2676 22.0583C16.564 22.041 16.7553 21.8834 16.868 21.5632C16.8884 21.5058 16.9063 21.4468 16.9344 21.3599L16.9338 21.3594Z" fill="#03045E"/>
                            <path d="M27.1166 14.0279C27.1166 13.2635 27.1217 12.4985 27.113 11.7341C27.111 11.5522 27.1666 11.4841 27.3513 11.4973C27.5441 11.511 27.7395 11.5085 27.9328 11.4978C28.0961 11.4886 28.1481 11.5501 28.1466 11.7113C28.14 12.4528 28.1435 13.1943 28.1435 13.9359C28.1435 14.747 28.1389 15.5577 28.1471 16.3689C28.1491 16.5508 28.0935 16.6189 27.9089 16.6057C27.716 16.592 27.5206 16.5945 27.3273 16.6052C27.164 16.6144 27.112 16.5529 27.1135 16.3918C27.1196 15.604 27.1166 14.8162 27.1166 14.0284V14.0279Z" fill="#03045E"/>
                            <path d="M7.26688 27.1454C7.3383 27.0198 7.43523 26.9212 7.55818 26.8506C7.68112 26.7799 7.81734 26.7444 7.96783 26.7444C8.14435 26.7444 8.29842 26.7866 8.42953 26.8714C8.56115 26.9563 8.65706 27.0763 8.71725 27.2323H8.46422C8.41932 27.1352 8.35453 27.0605 8.27036 27.0082C8.18618 26.9558 8.08517 26.9299 7.96732 26.9299C7.84948 26.9299 7.75306 26.9563 7.66327 27.0082C7.57348 27.0605 7.50308 27.1352 7.45207 27.2313C7.40105 27.3278 7.37503 27.4407 7.37503 27.5698C7.37503 27.6989 7.40054 27.8097 7.45207 27.9062C7.50308 28.0028 7.57348 28.077 7.66327 28.1294C7.75306 28.1817 7.85458 28.2076 7.96732 28.2076C8.08007 28.2076 8.18567 28.1817 8.27036 28.1304C8.35504 28.079 8.41932 28.0043 8.46422 27.9073H8.71725C8.65706 28.0613 8.56115 28.1807 8.42953 28.2646C8.29791 28.3484 8.14435 28.3906 7.96783 28.3906C7.81785 28.3906 7.68112 28.3555 7.55818 28.2854C7.43523 28.2153 7.3383 28.1177 7.26688 27.9926C7.19597 27.8676 7.16026 27.7268 7.16026 27.5698C7.16026 27.4127 7.19597 27.2714 7.26688 27.1454Z" fill="#03045E"/>
                            <path d="M9.2621 28.3164C9.16517 28.2625 9.08916 28.1858 9.03457 28.0862C8.97947 27.9865 8.95244 27.8717 8.95244 27.7405C8.95244 27.6094 8.9805 27.4966 9.03712 27.3975C9.09375 27.2979 9.17078 27.2216 9.26822 27.1688C9.36566 27.1154 9.47484 27.089 9.59523 27.089C9.71563 27.089 9.82532 27.1154 9.92276 27.1688C10.0202 27.2221 10.0972 27.2979 10.1539 27.3965C10.2105 27.4951 10.2385 27.6099 10.2385 27.7411C10.2385 27.8722 10.2095 27.987 10.1513 28.0867C10.0931 28.1858 10.0146 28.263 9.91561 28.3169C9.81664 28.3708 9.70645 28.3977 9.58605 28.3977C9.46566 28.3977 9.35903 28.3708 9.26261 28.3169L9.2621 28.3164ZM9.79879 28.1588C9.86511 28.1233 9.91919 28.0704 9.96 27.9992C10.0008 27.9286 10.0212 27.8422 10.0212 27.74C10.0212 27.6379 10.0013 27.552 9.96102 27.4813C9.92072 27.4107 9.86817 27.3578 9.80338 27.3228C9.73859 27.2882 9.66768 27.2709 9.59217 27.2709C9.51667 27.2709 9.44423 27.2882 9.37995 27.3228C9.31567 27.3573 9.26414 27.4102 9.22537 27.4813C9.1866 27.552 9.16721 27.6384 9.16721 27.74C9.16721 27.8417 9.1866 27.9306 9.22435 28.0013C9.26261 28.0719 9.31312 28.1248 9.37638 28.1599C9.43964 28.1944 9.50953 28.2117 9.58503 28.2117C9.66054 28.2117 9.73196 28.1939 9.79879 28.1583V28.1588Z" fill="#03045E"/>
                            <path d="M11.5114 27.2262C11.6073 27.3197 11.6553 27.4539 11.6553 27.6297V28.3764H11.4461V27.6597C11.4461 27.5332 11.4145 27.4366 11.3507 27.3695C11.2874 27.3024 11.2007 27.2689 11.091 27.2689C10.9813 27.2689 10.891 27.3035 10.8252 27.3731C10.7594 27.4422 10.7268 27.5433 10.7268 27.676V28.3764H10.5156V27.1093H10.7268V27.2897C10.7686 27.2252 10.8252 27.1749 10.8977 27.1393C10.9696 27.1037 11.0487 27.0859 11.1354 27.0859C11.2905 27.0859 11.4155 27.1327 11.5114 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M12.997 27.2262C13.0929 27.3197 13.1408 27.4539 13.1408 27.6297V28.3764H12.9317V27.6597C12.9317 27.5332 12.9 27.4366 12.8363 27.3695C12.773 27.3024 12.6863 27.2689 12.5766 27.2689C12.4669 27.2689 12.3766 27.3035 12.3108 27.3731C12.245 27.4422 12.2123 27.5433 12.2123 27.676V28.3764H12.0011V27.1093H12.2123V27.2897C12.2542 27.2252 12.3108 27.1749 12.3832 27.1393C12.4552 27.1037 12.5343 27.0859 12.621 27.0859C12.7761 27.0859 12.9011 27.1327 12.997 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M14.6397 27.8214H13.6229C13.6306 27.9464 13.6739 28.044 13.752 28.1141C13.8301 28.1843 13.9249 28.2193 14.0362 28.2193C14.1275 28.2193 14.2035 28.198 14.2647 28.1558C14.3254 28.1136 14.3688 28.0567 14.3933 27.986H14.6208C14.5866 28.108 14.5188 28.2066 14.4167 28.2834C14.3147 28.3596 14.1877 28.3977 14.0362 28.3977C13.9158 28.3977 13.8076 28.3708 13.7122 28.3169C13.6168 28.263 13.5423 28.1863 13.4882 28.0867C13.4342 27.987 13.4071 27.8722 13.4071 27.7411C13.4071 27.6099 13.4337 27.4951 13.4862 27.3965C13.5388 27.2979 13.6127 27.2216 13.7076 27.1688C13.8025 27.1159 13.9122 27.089 14.0362 27.089C14.1601 27.089 14.2637 27.1154 14.3565 27.1677C14.4494 27.2201 14.5208 27.2923 14.5713 27.3838C14.6218 27.4757 14.6468 27.5794 14.6468 27.6948C14.6468 27.735 14.6443 27.7771 14.6397 27.8219V27.8214ZM14.3754 27.4437C14.3397 27.3858 14.2917 27.3421 14.2305 27.3121C14.1698 27.2821 14.102 27.2669 14.0275 27.2669C13.9209 27.2669 13.83 27.3009 13.7545 27.3685C13.679 27.4361 13.6367 27.5301 13.626 27.6506H14.429C14.429 27.5708 14.4111 27.5017 14.3754 27.4437Z" fill="#03045E"/>
                            <path d="M14.9254 27.3975C14.9779 27.2994 15.0509 27.2237 15.1447 27.1698C15.2381 27.1159 15.3452 27.089 15.4661 27.089C15.6222 27.089 15.7513 27.1271 15.8523 27.2023C15.9538 27.278 16.0207 27.3827 16.0533 27.5164H15.8258C15.8044 27.4392 15.762 27.3787 15.6993 27.3339C15.6365 27.2892 15.559 27.2669 15.4661 27.2669C15.3457 27.2669 15.2483 27.308 15.1738 27.3904C15.0993 27.4727 15.0621 27.5896 15.0621 27.7405C15.0621 27.8915 15.0993 28.0109 15.1738 28.0943C15.2483 28.1776 15.3457 28.2193 15.4661 28.2193C15.559 28.2193 15.6365 28.198 15.6982 28.1548C15.76 28.1116 15.8023 28.0501 15.8258 27.9698H16.0533C16.0191 28.0994 15.9513 28.2031 15.8493 28.2808C15.7472 28.3586 15.6192 28.3977 15.4661 28.3977C15.3457 28.3977 15.2381 28.3708 15.1447 28.3169C15.0509 28.263 14.9779 28.1868 14.9254 28.0882C14.8728 27.9896 14.8463 27.8737 14.8463 27.7416C14.8463 27.6094 14.8728 27.4961 14.9254 27.3985V27.3975Z" fill="#03045E"/>
                            <path d="M16.5915 27.2826V28.0292C16.5915 28.0907 16.6048 28.1344 16.6308 28.1599C16.6573 28.1853 16.7027 28.198 16.768 28.198H16.9236V28.3759H16.7334C16.6155 28.3759 16.5278 28.3489 16.4686 28.2951C16.4099 28.2412 16.3803 28.1522 16.3803 28.0292V27.2826H16.2155V27.1093H16.3803V26.7901H16.5915V27.1093H16.9231V27.2826H16.5915Z" fill="#03045E"/>
                            <path d="M17.1859 26.8618C17.1583 26.8338 17.144 26.8003 17.144 26.7601C17.144 26.72 17.1583 26.6859 17.1859 26.6585C17.2139 26.6305 17.2476 26.6168 17.2879 26.6168C17.3282 26.6168 17.3593 26.6305 17.3864 26.6585C17.4134 26.6864 17.4272 26.72 17.4272 26.7601C17.4272 26.8003 17.4134 26.8343 17.3864 26.8618C17.3593 26.8897 17.3267 26.9035 17.2879 26.9035C17.2491 26.9035 17.2134 26.8897 17.1859 26.8618ZM17.3899 27.1093V28.3764H17.1787V27.1093H17.3899Z" fill="#03045E"/>
                            <path d="M18.7454 27.2262C18.8413 27.3197 18.8893 27.4539 18.8893 27.6297V28.3764H18.6801V27.6597C18.6801 27.5332 18.6485 27.4366 18.5847 27.3695C18.5215 27.3024 18.4347 27.2689 18.325 27.2689C18.2154 27.2689 18.1251 27.3035 18.0593 27.3731C17.9934 27.4422 17.9608 27.5433 17.9608 27.676V28.3764H17.7496V27.1093H17.9608V27.2897C18.0026 27.2252 18.0593 27.1749 18.1317 27.1393C18.2036 27.1037 18.2827 27.0859 18.3694 27.0859C18.5245 27.0859 18.6495 27.1327 18.7454 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M20.0484 27.1601C20.131 27.2079 20.1927 27.2679 20.233 27.3406V27.1093H20.4463V28.4038C20.4463 28.5197 20.4218 28.6224 20.3718 28.7123C20.3223 28.8023 20.2519 28.8729 20.1596 28.9238C20.0677 28.9746 19.9606 29 19.8382 29C19.6708 29 19.5316 28.9609 19.4204 28.8821C19.3091 28.8033 19.2433 28.6961 19.2234 28.5609H19.4321C19.455 28.6381 19.5035 28.6996 19.5759 28.7469C19.6489 28.7942 19.7361 28.8175 19.8382 28.8175C19.9545 28.8175 20.0489 28.7814 20.1223 28.7088C20.1958 28.6361 20.2325 28.5344 20.2325 28.4038V28.138C20.1907 28.2117 20.129 28.2737 20.0468 28.323C19.9652 28.3723 19.8693 28.3972 19.7591 28.3972C19.6489 28.3972 19.5438 28.3693 19.4515 28.3139C19.3596 28.2585 19.2872 28.1807 19.2347 28.0806C19.1821 27.9804 19.1556 27.8661 19.1556 27.7385C19.1556 27.6109 19.1821 27.4956 19.2347 27.3975C19.2872 27.2994 19.3596 27.2237 19.4515 27.1698C19.5438 27.1159 19.6464 27.089 19.7591 27.089C19.8718 27.089 19.9652 27.1129 20.0484 27.1606V27.1601ZM20.1749 27.491C20.1361 27.4198 20.0841 27.3655 20.0183 27.3278C19.9524 27.2902 19.8805 27.2714 19.8014 27.2714C19.7224 27.2714 19.6504 27.2897 19.5856 27.3268C19.5209 27.3639 19.4688 27.4178 19.43 27.4885C19.3913 27.5591 19.3719 27.6425 19.3719 27.738C19.3719 27.8336 19.3913 27.9195 19.43 27.9911C19.4688 28.0628 19.5203 28.1177 19.5856 28.1553C19.6509 28.1929 19.7229 28.2117 19.8014 28.2117C19.88 28.2117 19.953 28.1929 20.0183 28.1553C20.0836 28.1177 20.1361 28.0628 20.1749 27.9911C20.2136 27.9195 20.233 27.8356 20.233 27.74C20.233 27.6445 20.2136 27.5611 20.1749 27.4905V27.491Z" fill="#03045E"/>
                            <path d="M22.4191 26.7647V26.936H21.9783V28.3764H21.7671V26.936H21.3238V26.7647H22.4191Z" fill="#03045E"/>
                            <path d="M23.57 27.1474C23.6475 27.1881 23.7077 27.2496 23.7521 27.3314C23.7965 27.4132 23.8184 27.5123 23.8184 27.6297V28.3764H23.6093V27.6597C23.6093 27.5332 23.5776 27.4366 23.5139 27.3695C23.4506 27.3024 23.3639 27.2689 23.2542 27.2689C23.1445 27.2689 23.0542 27.3035 22.9884 27.3731C22.9226 27.4422 22.8899 27.5433 22.8899 27.676V28.3764H22.6787V26.6656H22.8899V27.2897C22.9318 27.2252 22.9894 27.1749 23.0629 27.1393C23.1363 27.1037 23.219 27.0859 23.3098 27.0859C23.4057 27.0859 23.4924 27.1062 23.57 27.1469V27.1474Z" fill="#03045E"/>
                            <path d="M25.3178 27.8214H24.301C24.3087 27.9464 24.352 28.044 24.4301 28.1141C24.5081 28.1843 24.603 28.2193 24.7143 28.2193C24.8056 28.2193 24.8816 28.198 24.9428 28.1558C25.0035 28.1136 25.0469 28.0567 25.0714 27.986H25.2989C25.2647 28.108 25.1969 28.2066 25.0948 28.2834C24.9928 28.3596 24.8658 28.3977 24.7143 28.3977C24.5939 28.3977 24.4857 28.3708 24.3903 28.3169C24.2949 28.263 24.2204 28.1863 24.1663 28.0867C24.1123 27.987 24.0852 27.8722 24.0852 27.7411C24.0852 27.6099 24.1118 27.4951 24.1643 27.3965C24.2169 27.2979 24.2908 27.2216 24.3857 27.1688C24.4806 27.1159 24.5903 27.089 24.7143 27.089C24.8382 27.089 24.9418 27.1154 25.0346 27.1677C25.1275 27.2201 25.1989 27.2923 25.2494 27.3838C25.2999 27.4757 25.3249 27.5794 25.3249 27.6948C25.3249 27.735 25.3224 27.7771 25.3178 27.8219V27.8214ZM25.053 27.4437C25.0173 27.3858 24.9693 27.3421 24.9081 27.3121C24.8474 27.2821 24.7796 27.2669 24.7051 27.2669C24.5984 27.2669 24.5076 27.3009 24.4321 27.3685C24.3566 27.4361 24.3143 27.5301 24.3036 27.6506H25.1066C25.1066 27.5708 25.0887 27.5017 25.053 27.4437Z" fill="#03045E"/>
                            <path d="M26.4289 26.7647V27.7843C26.4289 27.9276 26.4641 28.0338 26.5345 28.1034C26.6049 28.1726 26.7028 28.2076 26.8283 28.2076C26.9538 28.2076 27.0492 28.1731 27.1196 28.1034C27.19 28.0343 27.2252 27.9276 27.2252 27.7843V26.7647H27.4365V27.7822C27.4365 27.9164 27.4094 28.0292 27.3553 28.1207C27.3013 28.2122 27.2283 28.2808 27.136 28.3255C27.0441 28.3703 26.9406 28.3926 26.8263 28.3926C26.712 28.3926 26.6085 28.3703 26.5161 28.3255C26.4243 28.2808 26.3513 28.2127 26.2983 28.1207C26.2452 28.0287 26.2182 27.9159 26.2182 27.7822V26.7647H26.4294H26.4289Z" fill="#03045E"/>
                            <path d="M28.7843 27.2262C28.8802 27.3197 28.9282 27.4539 28.9282 27.6297V28.3764H28.719V27.6597C28.719 27.5332 28.6874 27.4366 28.6236 27.3695C28.5603 27.3024 28.4736 27.2689 28.3639 27.2689C28.2542 27.2689 28.1639 27.3035 28.0981 27.3731C28.0323 27.4422 27.9997 27.5433 27.9997 27.676V28.3764H27.7885V27.1093H27.9997V27.2897C28.0415 27.2252 28.0981 27.1749 28.1706 27.1393C28.2425 27.1037 28.3216 27.0859 28.4083 27.0859C28.5634 27.0859 28.6884 27.1327 28.7843 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M29.274 27.3975C29.3266 27.2994 29.3995 27.2237 29.4934 27.1698C29.5868 27.1159 29.6939 27.089 29.8148 27.089C29.9709 27.089 30.1 27.1271 30.201 27.2023C30.3025 27.278 30.3693 27.3827 30.402 27.5164H30.1745C30.153 27.4392 30.1107 27.3787 30.0479 27.3339C29.9852 27.2892 29.9077 27.2669 29.8148 27.2669C29.6944 27.2669 29.597 27.308 29.5225 27.3904C29.448 27.4727 29.4108 27.5896 29.4108 27.7405C29.4108 27.8915 29.448 28.0109 29.5225 28.0943C29.597 28.1776 29.6944 28.2193 29.8148 28.2193C29.9077 28.2193 29.9852 28.198 30.0469 28.1548C30.1087 28.1116 30.151 28.0501 30.1745 27.9698H30.402C30.3678 28.0994 30.3 28.2031 30.1979 28.2808C30.0959 28.3586 29.9679 28.3977 29.8148 28.3977C29.6944 28.3977 29.5868 28.3708 29.4934 28.3169C29.3995 28.263 29.3266 28.1868 29.274 28.0882C29.2215 27.9896 29.195 27.8737 29.195 27.7416C29.195 27.6094 29.2215 27.4961 29.274 27.3985V27.3975Z" fill="#03045E"/>
                            <path d="M30.9137 28.3164C30.8168 28.2625 30.7407 28.1858 30.6862 28.0862C30.6311 27.9865 30.604 27.8717 30.604 27.7405C30.604 27.6094 30.6321 27.4966 30.6887 27.3975C30.7453 27.2979 30.8224 27.2216 30.9198 27.1688C31.0172 27.1159 31.1264 27.089 31.2468 27.089C31.3672 27.089 31.4769 27.1154 31.5743 27.1688C31.6718 27.2221 31.7488 27.2979 31.8054 27.3965C31.8621 27.4951 31.8901 27.6099 31.8901 27.7411C31.8901 27.8722 31.861 27.987 31.8029 28.0867C31.7447 28.1858 31.6662 28.263 31.5672 28.3169C31.4682 28.3708 31.358 28.3977 31.2376 28.3977C31.1172 28.3977 31.0106 28.3708 30.9142 28.3169L30.9137 28.3164ZM31.4509 28.1588C31.5172 28.1233 31.5713 28.0704 31.6121 27.9992C31.6529 27.9286 31.6733 27.8422 31.6733 27.74C31.6733 27.6379 31.6534 27.552 31.6131 27.4813C31.5728 27.4107 31.5203 27.3578 31.4555 27.3228C31.3907 27.2882 31.3198 27.2709 31.2443 27.2709C31.1688 27.2709 31.0963 27.2882 31.032 27.3228C30.9678 27.3573 30.9162 27.4102 30.8775 27.4813C30.8387 27.552 30.8193 27.6384 30.8193 27.74C30.8193 27.8417 30.8387 27.9306 30.8764 28.0013C30.9147 28.0719 30.9652 28.1248 31.0285 28.1599C31.0917 28.1944 31.1616 28.2117 31.2371 28.2117C31.3126 28.2117 31.384 28.1939 31.4509 28.1583V28.1588Z" fill="#03045E"/>
                            <path d="M33.1635 27.2262C33.2594 27.3197 33.3073 27.4539 33.3073 27.6297V28.3764H33.0982V27.6597C33.0982 27.5332 33.0665 27.4366 33.0028 27.3695C32.9395 27.3024 32.8528 27.2689 32.7431 27.2689C32.6334 27.2689 32.5431 27.3035 32.4773 27.3731C32.4115 27.4422 32.3789 27.5433 32.3789 27.676V28.3764H32.1676V27.1093H32.3789V27.2897C32.4207 27.2252 32.4773 27.1749 32.5498 27.1393C32.6217 27.1037 32.7008 27.0859 32.7875 27.0859C32.9426 27.0859 33.0676 27.1327 33.1635 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M34.6491 27.2262C34.745 27.3197 34.7929 27.4539 34.7929 27.6297V28.3764H34.5838V27.6597C34.5838 27.5332 34.5521 27.4366 34.4884 27.3695C34.4251 27.3024 34.3384 27.2689 34.2287 27.2689C34.119 27.2689 34.0287 27.3035 33.9629 27.3731C33.8971 27.4422 33.8644 27.5433 33.8644 27.676V28.3764H33.6532V27.1093H33.8644V27.2897C33.9063 27.2252 33.9629 27.1749 34.0353 27.1393C34.1073 27.1037 34.1863 27.0859 34.2731 27.0859C34.4282 27.0859 34.5531 27.1327 34.6491 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M36.2923 27.8214H35.2755C35.2832 27.9464 35.3265 28.044 35.4046 28.1141C35.4827 28.1843 35.5775 28.2193 35.6888 28.2193C35.7801 28.2193 35.8561 28.198 35.9173 28.1558C35.978 28.1136 36.0214 28.0567 36.0459 27.986H36.2734C36.2392 28.108 36.1714 28.2066 36.0693 28.2834C35.9673 28.3596 35.8403 28.3977 35.6888 28.3977C35.5684 28.3977 35.4602 28.3708 35.3648 28.3169C35.2694 28.263 35.1949 28.1863 35.1408 28.0867C35.0868 27.987 35.0597 27.8722 35.0597 27.7411C35.0597 27.6099 35.0863 27.4951 35.1388 27.3965C35.1914 27.2979 35.2653 27.2216 35.3602 27.1688C35.4551 27.1159 35.5648 27.089 35.6888 27.089C35.8127 27.089 35.9163 27.1154 36.0091 27.1677C36.102 27.2201 36.1734 27.2923 36.2239 27.3838C36.2744 27.4757 36.2994 27.5794 36.2994 27.6948C36.2994 27.735 36.2969 27.7771 36.2923 27.8219V27.8214ZM36.0275 27.4437C35.9918 27.3858 35.9438 27.3421 35.8826 27.3121C35.8219 27.2821 35.7541 27.2669 35.6796 27.2669C35.573 27.2669 35.4821 27.3009 35.4066 27.3685C35.3311 27.4361 35.2888 27.5301 35.2781 27.6506H36.0811C36.0811 27.5708 36.0632 27.5017 36.0275 27.4437Z" fill="#03045E"/>
                            <path d="M36.5774 27.3975C36.63 27.2994 36.7029 27.2237 36.7968 27.1698C36.8902 27.1159 36.9973 27.089 37.1182 27.089C37.2743 27.089 37.4034 27.1271 37.5044 27.2023C37.6059 27.278 37.6728 27.3827 37.7054 27.5164H37.4779C37.4564 27.4392 37.4141 27.3787 37.3514 27.3339C37.2886 27.2892 37.2111 27.2669 37.1182 27.2669C36.9978 27.2669 36.9004 27.308 36.8259 27.3904C36.7514 27.4727 36.7142 27.5896 36.7142 27.7405C36.7142 27.8915 36.7514 28.0109 36.8259 28.0943C36.9004 28.1776 36.9978 28.2193 37.1182 28.2193C37.2111 28.2193 37.2886 28.198 37.3503 28.1548C37.4121 28.1116 37.4544 28.0501 37.4779 27.9698H37.7054C37.6712 28.0994 37.6034 28.2031 37.5013 28.2808C37.3993 28.3586 37.2713 28.3977 37.1182 28.3977C36.9978 28.3977 36.8902 28.3708 36.7968 28.3169C36.7029 28.263 36.63 28.1868 36.5774 28.0882C36.5249 27.9896 36.4984 27.8737 36.4984 27.7416C36.4984 27.6094 36.5249 27.4961 36.5774 27.3985V27.3975Z" fill="#03045E"/>
                            <path d="M38.2441 27.2826V28.0292C38.2441 28.0907 38.2574 28.1344 38.2834 28.1599C38.3099 28.1853 38.3554 28.198 38.4207 28.198H38.5762V28.3759H38.386C38.2681 28.3759 38.1804 28.3489 38.1212 28.2951C38.0625 28.2412 38.0329 28.1522 38.0329 28.0292V27.2826H37.8682V27.1093H38.0329V26.7901H38.2441V27.1093H38.5757V27.2826H38.2441Z" fill="#03045E"/>
                            <path d="M39.9843 27.8214H38.9675C38.9752 27.9464 39.0186 28.044 39.0966 28.1141C39.1747 28.1843 39.2696 28.2193 39.3808 28.2193C39.4721 28.2193 39.5481 28.198 39.6093 28.1558C39.67 28.1136 39.7134 28.0567 39.7379 27.986H39.9654C39.9312 28.108 39.8634 28.2066 39.7613 28.2834C39.6593 28.3596 39.5323 28.3977 39.3808 28.3977C39.2604 28.3977 39.1522 28.3708 39.0568 28.3169C38.9614 28.263 38.8869 28.1863 38.8329 28.0867C38.7788 27.987 38.7517 27.8722 38.7517 27.7411C38.7517 27.6099 38.7783 27.4951 38.8308 27.3965C38.8834 27.2979 38.9573 27.2216 39.0522 27.1688C39.1471 27.1159 39.2568 27.089 39.3808 27.089C39.5047 27.089 39.6083 27.1154 39.7011 27.1677C39.794 27.2201 39.8654 27.2923 39.9159 27.3838C39.9664 27.4757 39.9914 27.5794 39.9914 27.6948C39.9914 27.735 39.9889 27.7771 39.9843 27.8219V27.8214ZM39.7195 27.4437C39.6838 27.3858 39.6358 27.3421 39.5746 27.3121C39.5139 27.2821 39.4461 27.2669 39.3716 27.2669C39.265 27.2669 39.1742 27.3009 39.0987 27.3685C39.0231 27.4361 38.9808 27.5301 38.9701 27.6506H39.7731C39.7731 27.5708 39.7552 27.5017 39.7195 27.4437Z" fill="#03045E"/>
                            <path d="M40.27 27.3975C40.3225 27.2994 40.395 27.2237 40.4868 27.1698C40.5791 27.1159 40.6822 27.089 40.797 27.089C40.8959 27.089 40.9878 27.1118 41.073 27.1571C41.1582 27.2028 41.2229 27.2623 41.2683 27.3365V26.6661H41.4816V28.3769H41.2683V28.139C41.2265 28.2147 41.1648 28.2762 41.0826 28.325C41.001 28.3738 40.9051 28.3977 40.7949 28.3977C40.6847 28.3977 40.5796 28.3698 40.4873 28.3144C40.3955 28.259 40.323 28.1812 40.2705 28.0811C40.2179 27.981 40.1914 27.8666 40.1914 27.739C40.1914 27.6115 40.2179 27.4961 40.2705 27.398L40.27 27.3975ZM41.2097 27.491C41.1709 27.4198 41.1189 27.3655 41.0531 27.3278C40.9872 27.2902 40.9153 27.2714 40.8362 27.2714C40.7572 27.2714 40.6852 27.2897 40.6204 27.3268C40.5557 27.3639 40.5036 27.4178 40.4648 27.4885C40.4261 27.5591 40.4067 27.6425 40.4067 27.738C40.4067 27.8336 40.4261 27.9195 40.4648 27.9911C40.5036 28.0628 40.5551 28.1177 40.6204 28.1553C40.6857 28.1929 40.7577 28.2117 40.8362 28.2117C40.9148 28.2117 40.9878 28.1929 41.0531 28.1553C41.1184 28.1177 41.1709 28.0628 41.2097 27.9911C41.2484 27.9195 41.2678 27.8356 41.2678 27.74C41.2678 27.6445 41.2484 27.5611 41.2097 27.4905V27.491Z" fill="#03045E"/>
                    </svg>
                    <span class="content">
                        <span>Digital Marketing Certification Course By The IoT Academy</span>
                        <span class="duration">3/7 months</span>
                    </span>
                </a>
            </div>
            <div class="nsh-ach-right marketing d-none">
                <a href="http://localhost/TIA/python-training">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="29" viewBox="0 0 48 29" fill="none">
                            <path d="M20.3044 5.93391C20.5931 5.53086 20.8533 5.12833 21.1523 4.75578C23.1934 2.21502 25.835 0.672469 29.0587 0.173872C32.7083 -0.390797 36.0443 0.414276 38.9761 2.67143C41.4075 4.54282 42.8967 7.01039 43.4186 10.035C43.6425 11.3321 43.617 12.6337 43.3696 13.9262C43.3308 14.1285 43.3885 14.1915 43.5548 14.2657C45.2684 15.0342 46.5612 16.2438 47.3478 17.9511C48.8717 21.2572 47.6498 25.1225 44.4685 27.0386C43.9624 27.3436 43.3977 27.555 42.8518 27.7903C42.6003 27.8986 42.4391 27.8269 42.3697 27.6353C42.2978 27.4356 42.3947 27.2999 42.6646 27.2023C45.0241 26.3484 46.6198 24.7652 47.2075 22.3078C47.832 19.6969 46.7234 17.0032 44.4501 15.4835C43.9716 15.1633 43.4313 14.9336 42.913 14.6743C42.6819 14.5585 42.6197 14.4721 42.6758 14.221C43.6961 9.68075 41.7045 5.22642 37.965 2.70192C36.1749 1.4933 34.199 0.774628 32.039 0.667386C27.1742 0.425966 23.3766 2.29837 20.7615 6.43708C20.6064 6.68206 20.4722 6.72678 20.1983 6.58651C18.7535 5.84801 17.2154 5.55526 15.6053 5.68334C13.6387 5.83988 11.9128 6.5799 10.4701 7.92931C9.11613 9.19486 8.30396 10.7496 8.05449 12.5844C8.00092 12.9803 8.00603 13.3849 7.99582 13.7859C7.98715 14.1219 7.91114 14.1966 7.56882 14.2083C5.02977 14.2967 3.007 15.3427 1.61835 17.4814C0.0123727 19.9561 0.390909 23.2923 2.46674 25.3985C3.37278 26.3179 4.43901 26.9593 5.6843 27.3065C5.71389 27.3146 5.74246 27.3278 5.77256 27.3354C5.96999 27.3863 6.0608 27.5143 6.02815 27.7121C5.99856 27.8925 5.82255 27.9992 5.61135 27.9255C5.0864 27.7431 4.54461 27.5835 4.05486 27.3283C1.9474 26.23 0.573035 24.539 0.135321 22.1909C-0.323821 19.7279 0.39397 17.5887 2.1586 15.8194C3.51817 14.4558 5.19455 13.7432 7.11886 13.6096C7.25405 13.6004 7.3668 13.6055 7.37547 13.4047C7.51934 10.1046 9.08858 7.6574 11.9807 6.10875C14.5799 4.71715 17.2883 4.68259 20.0126 5.80532C20.1115 5.84598 20.2085 5.89121 20.3049 5.9334L20.3044 5.93391Z" fill="#03045E"/>
                            <path d="M10.5676 23.8793C12.3516 23.8555 14.1351 23.8351 15.9191 23.8072C17.6639 23.7802 19.4081 23.7472 21.1528 23.7152C22.7808 23.6852 24.4092 23.6547 26.0371 23.6222C27.541 23.5927 29.045 23.5622 30.5489 23.5292C31.8743 23.5002 33.2002 23.4727 34.5256 23.4351C35.6102 23.4041 36.6948 23.3619 37.7789 23.3187C38.1462 23.304 38.361 22.9533 38.2268 22.6001C37.8187 21.5261 37.408 20.4527 36.9988 19.3788C36.9662 19.2934 36.9381 19.2065 36.8943 19.0825C37.2259 19.0825 37.5319 19.0738 37.8365 19.0906C37.8875 19.0931 37.9523 19.1983 37.9763 19.268C38.2288 19.9963 38.4737 20.7272 38.7221 21.4575C38.7415 21.5144 38.767 21.5693 38.8104 21.6751C38.8609 21.5393 38.8976 21.4504 38.9283 21.3594C39.1594 20.6646 39.395 19.9709 39.6159 19.2725C39.6654 19.1165 39.7389 19.0682 39.895 19.0758C40.1419 19.088 40.3899 19.0789 40.6664 19.0789C40.6419 19.1633 40.6271 19.2268 40.6046 19.2878C40.119 20.6128 39.6348 21.9383 39.1451 23.2623C38.863 24.0257 38.4946 24.2819 37.6712 24.2738C35.8408 24.2555 34.0103 24.229 32.1804 24.2046C31.1336 24.1909 30.0867 24.1736 29.0399 24.1589C26.8916 24.1279 24.7438 24.0969 22.5956 24.0659C21.5564 24.0506 20.5172 24.0349 19.4785 24.0196C17.3226 23.9886 15.1672 23.9546 13.0112 23.9281C12.1975 23.9185 11.3833 23.9266 10.5691 23.9266L10.5681 23.8788L10.5676 23.8793Z" fill="#03045E"/>
                            <path d="M36.3111 22.751H35.3827C35.3827 22.38 35.3862 22.0257 35.3816 21.6715C35.3755 21.1932 35.3663 20.715 35.3521 20.2367C35.3454 20.0136 35.2648 19.8276 35.0301 19.7518C34.7837 19.6725 34.57 19.7437 34.3873 19.9109C34.1705 20.1096 34.1251 20.3785 34.12 20.6519C34.1093 21.2466 34.1088 21.8413 34.1057 22.4359C34.1037 22.7683 34.1057 22.7683 33.7604 22.7683C33.6206 22.7683 33.4803 22.7607 33.3415 22.7703C33.1849 22.781 33.1436 22.7124 33.1451 22.566C33.1517 22.0329 33.1502 21.4997 33.1461 20.9671C33.1446 20.7434 33.1354 20.5198 33.1206 20.2967C33.0972 19.9465 32.9829 19.7793 32.7462 19.7305C32.4498 19.6695 32.0865 19.8159 31.9922 20.1C31.9013 20.3724 31.8646 20.6707 31.8534 20.9594C31.8325 21.4916 31.8478 22.0252 31.8463 22.5584C31.8463 22.6184 31.836 22.6783 31.8279 22.7663C31.5488 22.7663 31.279 22.7719 31.0096 22.7602C30.9678 22.7581 30.8953 22.6748 30.8953 22.629C30.8917 21.4707 30.8963 20.3119 30.9004 19.1536C30.9004 19.1399 30.9127 19.1257 30.9264 19.0957H31.7779C31.7845 19.2365 31.7906 19.365 31.7988 19.5399C32.1304 19.2151 32.4727 18.992 32.9308 18.9905C33.3895 18.9894 33.7415 19.1607 33.9925 19.5572C34.1037 19.4581 34.1955 19.3671 34.2965 19.2873C34.7087 18.961 35.1715 18.9096 35.6561 19.0758C36.0418 19.2085 36.2943 19.4952 36.3045 19.8997C36.328 20.8405 36.3122 21.7818 36.3122 22.75L36.3111 22.751Z" fill="#03045E"/>
                            <path d="M8.89734 22.7683C9.12079 22.1975 9.32587 21.6751 9.52994 21.1521C9.96255 20.0466 10.3977 18.9422 10.8242 17.8347C10.8773 17.6975 10.941 17.6431 11.091 17.6512C11.3308 17.6644 11.5726 17.6436 11.8114 17.6614C11.8894 17.667 12.0042 17.7305 12.0312 17.7961C12.6893 19.4098 13.3383 21.0275 13.9877 22.6443C13.9959 22.6651 13.9953 22.6895 14.0071 22.7683C13.7581 22.7683 13.5143 22.7485 13.2755 22.7734C13.0061 22.8018 12.8653 22.7119 12.7949 22.4481C12.7317 22.2113 12.6169 21.9886 12.5343 21.7564C12.4929 21.64 12.4343 21.5831 12.3006 21.5841C11.7267 21.5902 11.1527 21.5897 10.5788 21.5841C10.4452 21.5826 10.388 21.64 10.3477 21.7589C10.2544 22.0364 10.1416 22.3073 10.0503 22.5853C10.008 22.7139 9.94775 22.7754 9.80389 22.7703C9.51871 22.7612 9.23302 22.7678 8.89734 22.7678V22.7683ZM12.1425 20.7475C11.9032 20.1071 11.6772 19.5023 11.4512 18.898C11.4333 18.9 11.4155 18.902 11.3976 18.9041C11.1762 19.5109 10.9553 20.1183 10.7263 20.7475H12.1425Z" fill="#03045E"/>
                            <path d="M33.4303 14.5153C33.4303 15.79 32.5584 16.6733 31.3004 16.6728C29.9806 16.6723 29.1557 15.8357 29.1547 14.4975C29.1541 13.2452 30.0332 12.3684 31.2912 12.3664C32.5523 12.3644 33.4303 13.2467 33.4303 14.5158V14.5153ZM32.36 14.5153C32.3008 14.284 32.2697 14.035 32.1743 13.8144C32.0141 13.4439 31.7034 13.2416 31.2963 13.2421C30.879 13.2426 30.5673 13.4546 30.4025 13.8317C30.2066 14.281 30.203 14.7506 30.3984 15.2009C30.5607 15.575 30.855 15.7951 31.2825 15.7996C31.7192 15.8042 32.0243 15.5907 32.187 15.2019C32.2743 14.9936 32.3008 14.7598 32.36 14.5148V14.5153Z" fill="#03045E"/>
                            <path d="M25.0918 22.2499C25.0076 22.3221 24.9729 22.348 24.9423 22.3785C24.2638 23.0616 23.2659 22.9828 22.7134 22.2016C22.2487 21.5454 22.2359 20.285 22.6869 19.6227C23.1664 18.9188 24.1521 18.7765 24.8117 19.3157C24.8632 19.3579 24.9153 19.4001 25.003 19.4713V17.6695H25.9621V22.7658C25.7065 22.7658 25.4601 22.7719 25.2147 22.7597C25.1719 22.7576 25.1066 22.6829 25.0979 22.6341C25.0795 22.5304 25.0923 22.4217 25.0923 22.2494L25.0918 22.2499ZM23.3256 20.8674C23.3343 20.87 23.343 20.8725 23.3516 20.8746C23.3598 21.0052 23.3608 21.1368 23.3787 21.2664C23.3924 21.3645 23.42 21.4626 23.4542 21.5561C23.5725 21.8763 23.8531 22.0817 24.1725 22.0908C24.4622 22.0989 24.7775 21.8966 24.8949 21.5932C25.0709 21.1373 25.0668 20.6687 24.8974 20.2138C24.779 19.8962 24.5286 19.7208 24.1811 19.7223C23.8337 19.7244 23.5868 19.9068 23.4659 20.2189C23.3868 20.4227 23.37 20.6499 23.3256 20.8669V20.8674Z" fill="#03045E"/>
                            <path d="M17.7062 16.5885H16.7834V11.5191H17.7231V13.3631C17.8231 13.2782 17.8771 13.2319 17.9312 13.1862C18.3807 12.8076 18.8857 12.7313 19.4326 12.9265C19.8086 13.0607 20.0647 13.3412 20.0861 13.7275C20.1397 14.6734 20.1438 15.6218 20.1682 16.5859H19.176C19.176 16.4187 19.1775 16.2662 19.176 16.1137C19.1683 15.4652 19.1688 14.8167 19.1474 14.1687C19.1352 13.8012 18.9837 13.6279 18.6852 13.5679C18.372 13.5049 17.9919 13.6548 17.8817 13.9425C17.7904 14.1814 17.744 14.4487 17.7348 14.7059C17.7134 15.3077 17.7261 15.911 17.7246 16.5137C17.7246 16.5285 17.7174 16.5437 17.7067 16.5879L17.7062 16.5885Z" fill="#03045E"/>
                            <path d="M21.8084 22.7673C21.4865 22.7673 21.2238 22.7734 20.9615 22.7622C20.9105 22.7602 20.8457 22.7002 20.8161 22.6504C20.7707 22.5736 20.7503 22.4822 20.7116 22.3754C20.6335 22.4349 20.5748 22.4806 20.5146 22.5249C20.0871 22.8425 19.6101 22.9127 19.1041 22.781C18.6597 22.6656 18.3679 22.3038 18.3414 21.8672C18.3087 21.332 18.5153 20.9564 18.9959 20.7917C19.3739 20.6621 19.7734 20.5935 20.1631 20.4974C20.2305 20.4807 20.3004 20.4695 20.3647 20.4441C20.4764 20.3998 20.6478 20.4283 20.6417 20.2311C20.635 20.0253 20.5784 19.8499 20.3611 19.7716C19.9035 19.6065 19.5622 19.7325 19.2964 20.1788C19.1602 20.1544 19.0173 20.1284 18.8745 20.103C18.7383 20.0786 18.6021 20.0547 18.4398 20.0263C18.5607 19.5887 18.7842 19.2507 19.2168 19.1104C19.7759 18.929 20.3499 18.9366 20.91 19.1028C21.3692 19.239 21.5676 19.5475 21.5865 20.0944C21.5982 20.4415 21.586 20.7892 21.5895 21.1363C21.5952 21.6725 21.5283 22.2169 21.8084 22.7678V22.7673ZM20.6177 20.9889C20.2677 21.0799 19.9254 21.1465 19.5994 21.2593C19.2724 21.3726 19.2046 21.7416 19.4372 21.9947C19.5928 22.164 19.8693 22.2133 20.1269 22.1177C20.5636 21.9561 20.7044 21.6491 20.6177 20.9889Z" fill="#03045E"/>
                            <path d="M27.7022 21.1922C27.6747 21.644 27.9083 22.0029 28.269 22.0994C28.6629 22.2052 28.8827 22.0822 29.145 21.6024C29.4434 21.6532 29.7454 21.705 30.0821 21.763C29.9265 22.1935 29.6771 22.506 29.2776 22.6885C28.0104 23.2669 26.7146 22.4791 26.6942 21.0972C26.6886 20.7277 26.7401 20.3358 26.8656 19.9902C27.1278 19.268 27.8099 18.9183 28.6476 19.0006C29.3587 19.0708 29.9056 19.5699 30.0597 20.3058C30.1051 20.5239 30.1163 20.749 30.1464 20.9701C30.1699 21.1434 30.0985 21.1978 29.9245 21.1953C29.273 21.1866 28.6215 21.1917 27.9701 21.1917C27.8859 21.1917 27.8022 21.1917 27.7022 21.1917V21.1922ZM27.714 20.5823H29.1674C29.1368 20.0075 28.8246 19.6827 28.367 19.7239C27.9578 19.761 27.6681 20.1366 27.714 20.5823Z" fill="#03045E"/>
                            <path d="M24.3602 15.0393H21.8977C21.8385 15.4327 22.0727 15.8179 22.4226 15.9282C22.82 16.0533 23.0797 15.9186 23.3297 15.4444C23.6261 15.4932 23.9281 15.543 24.2684 15.5989C24.1271 15.9963 23.9092 16.2881 23.5618 16.4756C22.3068 17.1541 20.9172 16.3562 20.8666 14.9265C20.8473 14.3831 20.9223 13.8571 21.2829 13.42C21.7482 12.8563 22.3645 12.7278 23.0481 12.8818C23.7052 13.0297 24.0863 13.4734 24.2347 14.1107C24.3026 14.4009 24.3163 14.7033 24.3597 15.0393H24.3602ZM21.9053 14.4502C22.3481 14.4502 22.7956 14.4538 23.243 14.4446C23.2833 14.4436 23.3542 14.3638 23.3557 14.3186C23.369 13.8927 22.9807 13.5257 22.5639 13.5593C22.1431 13.5933 21.837 13.9923 21.9053 14.4502Z" fill="#03045E"/>
                            <path d="M14.5667 16.5864H13.5515V12.3623H12.0353V11.5201H16.0783V12.3456H14.5667V16.5864Z" fill="#03045E"/>
                            <path d="M35.9515 16.5839H34.9245V12.3572H33.4068C33.4068 12.0955 33.3976 11.852 33.4155 11.6106C33.4186 11.5694 33.5349 11.5064 33.6002 11.5054C34.0348 11.4958 34.4695 11.5003 34.9041 11.5003C35.6648 11.5003 36.4254 11.5003 37.1861 11.5003H37.4559V12.3476H35.952V16.5844L35.9515 16.5839Z" fill="#03045E"/>
                            <path d="M16.9338 21.3594C17.2486 21.4143 17.5506 21.4662 17.8705 21.5221C17.7251 22.1177 17.4348 22.5584 16.84 22.7464C15.9885 23.0148 15.1233 22.7642 14.6968 22.1269C14.2703 21.489 14.2581 20.3277 14.7065 19.7056C14.855 19.4997 15.0641 19.3081 15.2886 19.1922C15.8477 18.903 16.4426 18.9163 17.0211 19.147C17.4557 19.3203 17.6802 19.6873 17.822 20.1472C17.5119 20.2057 17.2109 20.2621 16.8951 20.3216C16.8767 20.2804 16.8583 20.2418 16.8425 20.2021C16.7216 19.8921 16.5043 19.7274 16.1931 19.7376C15.8768 19.7478 15.6059 19.8723 15.4927 20.1828C15.3182 20.6626 15.3274 21.1556 15.511 21.6308C15.6263 21.9292 15.9248 22.0776 16.2676 22.0583C16.564 22.041 16.7553 21.8834 16.868 21.5632C16.8884 21.5058 16.9063 21.4468 16.9344 21.3599L16.9338 21.3594Z" fill="#03045E"/>
                            <path d="M27.1166 14.0279C27.1166 13.2635 27.1217 12.4985 27.113 11.7341C27.111 11.5522 27.1666 11.4841 27.3513 11.4973C27.5441 11.511 27.7395 11.5085 27.9328 11.4978C28.0961 11.4886 28.1481 11.5501 28.1466 11.7113C28.14 12.4528 28.1435 13.1943 28.1435 13.9359C28.1435 14.747 28.1389 15.5577 28.1471 16.3689C28.1491 16.5508 28.0935 16.6189 27.9089 16.6057C27.716 16.592 27.5206 16.5945 27.3273 16.6052C27.164 16.6144 27.112 16.5529 27.1135 16.3918C27.1196 15.604 27.1166 14.8162 27.1166 14.0284V14.0279Z" fill="#03045E"/>
                            <path d="M7.26688 27.1454C7.3383 27.0198 7.43523 26.9212 7.55818 26.8506C7.68112 26.7799 7.81734 26.7444 7.96783 26.7444C8.14435 26.7444 8.29842 26.7866 8.42953 26.8714C8.56115 26.9563 8.65706 27.0763 8.71725 27.2323H8.46422C8.41932 27.1352 8.35453 27.0605 8.27036 27.0082C8.18618 26.9558 8.08517 26.9299 7.96732 26.9299C7.84948 26.9299 7.75306 26.9563 7.66327 27.0082C7.57348 27.0605 7.50308 27.1352 7.45207 27.2313C7.40105 27.3278 7.37503 27.4407 7.37503 27.5698C7.37503 27.6989 7.40054 27.8097 7.45207 27.9062C7.50308 28.0028 7.57348 28.077 7.66327 28.1294C7.75306 28.1817 7.85458 28.2076 7.96732 28.2076C8.08007 28.2076 8.18567 28.1817 8.27036 28.1304C8.35504 28.079 8.41932 28.0043 8.46422 27.9073H8.71725C8.65706 28.0613 8.56115 28.1807 8.42953 28.2646C8.29791 28.3484 8.14435 28.3906 7.96783 28.3906C7.81785 28.3906 7.68112 28.3555 7.55818 28.2854C7.43523 28.2153 7.3383 28.1177 7.26688 27.9926C7.19597 27.8676 7.16026 27.7268 7.16026 27.5698C7.16026 27.4127 7.19597 27.2714 7.26688 27.1454Z" fill="#03045E"/>
                            <path d="M9.2621 28.3164C9.16517 28.2625 9.08916 28.1858 9.03457 28.0862C8.97947 27.9865 8.95244 27.8717 8.95244 27.7405C8.95244 27.6094 8.9805 27.4966 9.03712 27.3975C9.09375 27.2979 9.17078 27.2216 9.26822 27.1688C9.36566 27.1154 9.47484 27.089 9.59523 27.089C9.71563 27.089 9.82532 27.1154 9.92276 27.1688C10.0202 27.2221 10.0972 27.2979 10.1539 27.3965C10.2105 27.4951 10.2385 27.6099 10.2385 27.7411C10.2385 27.8722 10.2095 27.987 10.1513 28.0867C10.0931 28.1858 10.0146 28.263 9.91561 28.3169C9.81664 28.3708 9.70645 28.3977 9.58605 28.3977C9.46566 28.3977 9.35903 28.3708 9.26261 28.3169L9.2621 28.3164ZM9.79879 28.1588C9.86511 28.1233 9.91919 28.0704 9.96 27.9992C10.0008 27.9286 10.0212 27.8422 10.0212 27.74C10.0212 27.6379 10.0013 27.552 9.96102 27.4813C9.92072 27.4107 9.86817 27.3578 9.80338 27.3228C9.73859 27.2882 9.66768 27.2709 9.59217 27.2709C9.51667 27.2709 9.44423 27.2882 9.37995 27.3228C9.31567 27.3573 9.26414 27.4102 9.22537 27.4813C9.1866 27.552 9.16721 27.6384 9.16721 27.74C9.16721 27.8417 9.1866 27.9306 9.22435 28.0013C9.26261 28.0719 9.31312 28.1248 9.37638 28.1599C9.43964 28.1944 9.50953 28.2117 9.58503 28.2117C9.66054 28.2117 9.73196 28.1939 9.79879 28.1583V28.1588Z" fill="#03045E"/>
                            <path d="M11.5114 27.2262C11.6073 27.3197 11.6553 27.4539 11.6553 27.6297V28.3764H11.4461V27.6597C11.4461 27.5332 11.4145 27.4366 11.3507 27.3695C11.2874 27.3024 11.2007 27.2689 11.091 27.2689C10.9813 27.2689 10.891 27.3035 10.8252 27.3731C10.7594 27.4422 10.7268 27.5433 10.7268 27.676V28.3764H10.5156V27.1093H10.7268V27.2897C10.7686 27.2252 10.8252 27.1749 10.8977 27.1393C10.9696 27.1037 11.0487 27.0859 11.1354 27.0859C11.2905 27.0859 11.4155 27.1327 11.5114 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M12.997 27.2262C13.0929 27.3197 13.1408 27.4539 13.1408 27.6297V28.3764H12.9317V27.6597C12.9317 27.5332 12.9 27.4366 12.8363 27.3695C12.773 27.3024 12.6863 27.2689 12.5766 27.2689C12.4669 27.2689 12.3766 27.3035 12.3108 27.3731C12.245 27.4422 12.2123 27.5433 12.2123 27.676V28.3764H12.0011V27.1093H12.2123V27.2897C12.2542 27.2252 12.3108 27.1749 12.3832 27.1393C12.4552 27.1037 12.5343 27.0859 12.621 27.0859C12.7761 27.0859 12.9011 27.1327 12.997 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M14.6397 27.8214H13.6229C13.6306 27.9464 13.6739 28.044 13.752 28.1141C13.8301 28.1843 13.9249 28.2193 14.0362 28.2193C14.1275 28.2193 14.2035 28.198 14.2647 28.1558C14.3254 28.1136 14.3688 28.0567 14.3933 27.986H14.6208C14.5866 28.108 14.5188 28.2066 14.4167 28.2834C14.3147 28.3596 14.1877 28.3977 14.0362 28.3977C13.9158 28.3977 13.8076 28.3708 13.7122 28.3169C13.6168 28.263 13.5423 28.1863 13.4882 28.0867C13.4342 27.987 13.4071 27.8722 13.4071 27.7411C13.4071 27.6099 13.4337 27.4951 13.4862 27.3965C13.5388 27.2979 13.6127 27.2216 13.7076 27.1688C13.8025 27.1159 13.9122 27.089 14.0362 27.089C14.1601 27.089 14.2637 27.1154 14.3565 27.1677C14.4494 27.2201 14.5208 27.2923 14.5713 27.3838C14.6218 27.4757 14.6468 27.5794 14.6468 27.6948C14.6468 27.735 14.6443 27.7771 14.6397 27.8219V27.8214ZM14.3754 27.4437C14.3397 27.3858 14.2917 27.3421 14.2305 27.3121C14.1698 27.2821 14.102 27.2669 14.0275 27.2669C13.9209 27.2669 13.83 27.3009 13.7545 27.3685C13.679 27.4361 13.6367 27.5301 13.626 27.6506H14.429C14.429 27.5708 14.4111 27.5017 14.3754 27.4437Z" fill="#03045E"/>
                            <path d="M14.9254 27.3975C14.9779 27.2994 15.0509 27.2237 15.1447 27.1698C15.2381 27.1159 15.3452 27.089 15.4661 27.089C15.6222 27.089 15.7513 27.1271 15.8523 27.2023C15.9538 27.278 16.0207 27.3827 16.0533 27.5164H15.8258C15.8044 27.4392 15.762 27.3787 15.6993 27.3339C15.6365 27.2892 15.559 27.2669 15.4661 27.2669C15.3457 27.2669 15.2483 27.308 15.1738 27.3904C15.0993 27.4727 15.0621 27.5896 15.0621 27.7405C15.0621 27.8915 15.0993 28.0109 15.1738 28.0943C15.2483 28.1776 15.3457 28.2193 15.4661 28.2193C15.559 28.2193 15.6365 28.198 15.6982 28.1548C15.76 28.1116 15.8023 28.0501 15.8258 27.9698H16.0533C16.0191 28.0994 15.9513 28.2031 15.8493 28.2808C15.7472 28.3586 15.6192 28.3977 15.4661 28.3977C15.3457 28.3977 15.2381 28.3708 15.1447 28.3169C15.0509 28.263 14.9779 28.1868 14.9254 28.0882C14.8728 27.9896 14.8463 27.8737 14.8463 27.7416C14.8463 27.6094 14.8728 27.4961 14.9254 27.3985V27.3975Z" fill="#03045E"/>
                            <path d="M16.5915 27.2826V28.0292C16.5915 28.0907 16.6048 28.1344 16.6308 28.1599C16.6573 28.1853 16.7027 28.198 16.768 28.198H16.9236V28.3759H16.7334C16.6155 28.3759 16.5278 28.3489 16.4686 28.2951C16.4099 28.2412 16.3803 28.1522 16.3803 28.0292V27.2826H16.2155V27.1093H16.3803V26.7901H16.5915V27.1093H16.9231V27.2826H16.5915Z" fill="#03045E"/>
                            <path d="M17.1859 26.8618C17.1583 26.8338 17.144 26.8003 17.144 26.7601C17.144 26.72 17.1583 26.6859 17.1859 26.6585C17.2139 26.6305 17.2476 26.6168 17.2879 26.6168C17.3282 26.6168 17.3593 26.6305 17.3864 26.6585C17.4134 26.6864 17.4272 26.72 17.4272 26.7601C17.4272 26.8003 17.4134 26.8343 17.3864 26.8618C17.3593 26.8897 17.3267 26.9035 17.2879 26.9035C17.2491 26.9035 17.2134 26.8897 17.1859 26.8618ZM17.3899 27.1093V28.3764H17.1787V27.1093H17.3899Z" fill="#03045E"/>
                            <path d="M18.7454 27.2262C18.8413 27.3197 18.8893 27.4539 18.8893 27.6297V28.3764H18.6801V27.6597C18.6801 27.5332 18.6485 27.4366 18.5847 27.3695C18.5215 27.3024 18.4347 27.2689 18.325 27.2689C18.2154 27.2689 18.1251 27.3035 18.0593 27.3731C17.9934 27.4422 17.9608 27.5433 17.9608 27.676V28.3764H17.7496V27.1093H17.9608V27.2897C18.0026 27.2252 18.0593 27.1749 18.1317 27.1393C18.2036 27.1037 18.2827 27.0859 18.3694 27.0859C18.5245 27.0859 18.6495 27.1327 18.7454 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M20.0484 27.1601C20.131 27.2079 20.1927 27.2679 20.233 27.3406V27.1093H20.4463V28.4038C20.4463 28.5197 20.4218 28.6224 20.3718 28.7123C20.3223 28.8023 20.2519 28.8729 20.1596 28.9238C20.0677 28.9746 19.9606 29 19.8382 29C19.6708 29 19.5316 28.9609 19.4204 28.8821C19.3091 28.8033 19.2433 28.6961 19.2234 28.5609H19.4321C19.455 28.6381 19.5035 28.6996 19.5759 28.7469C19.6489 28.7942 19.7361 28.8175 19.8382 28.8175C19.9545 28.8175 20.0489 28.7814 20.1223 28.7088C20.1958 28.6361 20.2325 28.5344 20.2325 28.4038V28.138C20.1907 28.2117 20.129 28.2737 20.0468 28.323C19.9652 28.3723 19.8693 28.3972 19.7591 28.3972C19.6489 28.3972 19.5438 28.3693 19.4515 28.3139C19.3596 28.2585 19.2872 28.1807 19.2347 28.0806C19.1821 27.9804 19.1556 27.8661 19.1556 27.7385C19.1556 27.6109 19.1821 27.4956 19.2347 27.3975C19.2872 27.2994 19.3596 27.2237 19.4515 27.1698C19.5438 27.1159 19.6464 27.089 19.7591 27.089C19.8718 27.089 19.9652 27.1129 20.0484 27.1606V27.1601ZM20.1749 27.491C20.1361 27.4198 20.0841 27.3655 20.0183 27.3278C19.9524 27.2902 19.8805 27.2714 19.8014 27.2714C19.7224 27.2714 19.6504 27.2897 19.5856 27.3268C19.5209 27.3639 19.4688 27.4178 19.43 27.4885C19.3913 27.5591 19.3719 27.6425 19.3719 27.738C19.3719 27.8336 19.3913 27.9195 19.43 27.9911C19.4688 28.0628 19.5203 28.1177 19.5856 28.1553C19.6509 28.1929 19.7229 28.2117 19.8014 28.2117C19.88 28.2117 19.953 28.1929 20.0183 28.1553C20.0836 28.1177 20.1361 28.0628 20.1749 27.9911C20.2136 27.9195 20.233 27.8356 20.233 27.74C20.233 27.6445 20.2136 27.5611 20.1749 27.4905V27.491Z" fill="#03045E"/>
                            <path d="M22.4191 26.7647V26.936H21.9783V28.3764H21.7671V26.936H21.3238V26.7647H22.4191Z" fill="#03045E"/>
                            <path d="M23.57 27.1474C23.6475 27.1881 23.7077 27.2496 23.7521 27.3314C23.7965 27.4132 23.8184 27.5123 23.8184 27.6297V28.3764H23.6093V27.6597C23.6093 27.5332 23.5776 27.4366 23.5139 27.3695C23.4506 27.3024 23.3639 27.2689 23.2542 27.2689C23.1445 27.2689 23.0542 27.3035 22.9884 27.3731C22.9226 27.4422 22.8899 27.5433 22.8899 27.676V28.3764H22.6787V26.6656H22.8899V27.2897C22.9318 27.2252 22.9894 27.1749 23.0629 27.1393C23.1363 27.1037 23.219 27.0859 23.3098 27.0859C23.4057 27.0859 23.4924 27.1062 23.57 27.1469V27.1474Z" fill="#03045E"/>
                            <path d="M25.3178 27.8214H24.301C24.3087 27.9464 24.352 28.044 24.4301 28.1141C24.5081 28.1843 24.603 28.2193 24.7143 28.2193C24.8056 28.2193 24.8816 28.198 24.9428 28.1558C25.0035 28.1136 25.0469 28.0567 25.0714 27.986H25.2989C25.2647 28.108 25.1969 28.2066 25.0948 28.2834C24.9928 28.3596 24.8658 28.3977 24.7143 28.3977C24.5939 28.3977 24.4857 28.3708 24.3903 28.3169C24.2949 28.263 24.2204 28.1863 24.1663 28.0867C24.1123 27.987 24.0852 27.8722 24.0852 27.7411C24.0852 27.6099 24.1118 27.4951 24.1643 27.3965C24.2169 27.2979 24.2908 27.2216 24.3857 27.1688C24.4806 27.1159 24.5903 27.089 24.7143 27.089C24.8382 27.089 24.9418 27.1154 25.0346 27.1677C25.1275 27.2201 25.1989 27.2923 25.2494 27.3838C25.2999 27.4757 25.3249 27.5794 25.3249 27.6948C25.3249 27.735 25.3224 27.7771 25.3178 27.8219V27.8214ZM25.053 27.4437C25.0173 27.3858 24.9693 27.3421 24.9081 27.3121C24.8474 27.2821 24.7796 27.2669 24.7051 27.2669C24.5984 27.2669 24.5076 27.3009 24.4321 27.3685C24.3566 27.4361 24.3143 27.5301 24.3036 27.6506H25.1066C25.1066 27.5708 25.0887 27.5017 25.053 27.4437Z" fill="#03045E"/>
                            <path d="M26.4289 26.7647V27.7843C26.4289 27.9276 26.4641 28.0338 26.5345 28.1034C26.6049 28.1726 26.7028 28.2076 26.8283 28.2076C26.9538 28.2076 27.0492 28.1731 27.1196 28.1034C27.19 28.0343 27.2252 27.9276 27.2252 27.7843V26.7647H27.4365V27.7822C27.4365 27.9164 27.4094 28.0292 27.3553 28.1207C27.3013 28.2122 27.2283 28.2808 27.136 28.3255C27.0441 28.3703 26.9406 28.3926 26.8263 28.3926C26.712 28.3926 26.6085 28.3703 26.5161 28.3255C26.4243 28.2808 26.3513 28.2127 26.2983 28.1207C26.2452 28.0287 26.2182 27.9159 26.2182 27.7822V26.7647H26.4294H26.4289Z" fill="#03045E"/>
                            <path d="M28.7843 27.2262C28.8802 27.3197 28.9282 27.4539 28.9282 27.6297V28.3764H28.719V27.6597C28.719 27.5332 28.6874 27.4366 28.6236 27.3695C28.5603 27.3024 28.4736 27.2689 28.3639 27.2689C28.2542 27.2689 28.1639 27.3035 28.0981 27.3731C28.0323 27.4422 27.9997 27.5433 27.9997 27.676V28.3764H27.7885V27.1093H27.9997V27.2897C28.0415 27.2252 28.0981 27.1749 28.1706 27.1393C28.2425 27.1037 28.3216 27.0859 28.4083 27.0859C28.5634 27.0859 28.6884 27.1327 28.7843 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M29.274 27.3975C29.3266 27.2994 29.3995 27.2237 29.4934 27.1698C29.5868 27.1159 29.6939 27.089 29.8148 27.089C29.9709 27.089 30.1 27.1271 30.201 27.2023C30.3025 27.278 30.3693 27.3827 30.402 27.5164H30.1745C30.153 27.4392 30.1107 27.3787 30.0479 27.3339C29.9852 27.2892 29.9077 27.2669 29.8148 27.2669C29.6944 27.2669 29.597 27.308 29.5225 27.3904C29.448 27.4727 29.4108 27.5896 29.4108 27.7405C29.4108 27.8915 29.448 28.0109 29.5225 28.0943C29.597 28.1776 29.6944 28.2193 29.8148 28.2193C29.9077 28.2193 29.9852 28.198 30.0469 28.1548C30.1087 28.1116 30.151 28.0501 30.1745 27.9698H30.402C30.3678 28.0994 30.3 28.2031 30.1979 28.2808C30.0959 28.3586 29.9679 28.3977 29.8148 28.3977C29.6944 28.3977 29.5868 28.3708 29.4934 28.3169C29.3995 28.263 29.3266 28.1868 29.274 28.0882C29.2215 27.9896 29.195 27.8737 29.195 27.7416C29.195 27.6094 29.2215 27.4961 29.274 27.3985V27.3975Z" fill="#03045E"/>
                            <path d="M30.9137 28.3164C30.8168 28.2625 30.7407 28.1858 30.6862 28.0862C30.6311 27.9865 30.604 27.8717 30.604 27.7405C30.604 27.6094 30.6321 27.4966 30.6887 27.3975C30.7453 27.2979 30.8224 27.2216 30.9198 27.1688C31.0172 27.1159 31.1264 27.089 31.2468 27.089C31.3672 27.089 31.4769 27.1154 31.5743 27.1688C31.6718 27.2221 31.7488 27.2979 31.8054 27.3965C31.8621 27.4951 31.8901 27.6099 31.8901 27.7411C31.8901 27.8722 31.861 27.987 31.8029 28.0867C31.7447 28.1858 31.6662 28.263 31.5672 28.3169C31.4682 28.3708 31.358 28.3977 31.2376 28.3977C31.1172 28.3977 31.0106 28.3708 30.9142 28.3169L30.9137 28.3164ZM31.4509 28.1588C31.5172 28.1233 31.5713 28.0704 31.6121 27.9992C31.6529 27.9286 31.6733 27.8422 31.6733 27.74C31.6733 27.6379 31.6534 27.552 31.6131 27.4813C31.5728 27.4107 31.5203 27.3578 31.4555 27.3228C31.3907 27.2882 31.3198 27.2709 31.2443 27.2709C31.1688 27.2709 31.0963 27.2882 31.032 27.3228C30.9678 27.3573 30.9162 27.4102 30.8775 27.4813C30.8387 27.552 30.8193 27.6384 30.8193 27.74C30.8193 27.8417 30.8387 27.9306 30.8764 28.0013C30.9147 28.0719 30.9652 28.1248 31.0285 28.1599C31.0917 28.1944 31.1616 28.2117 31.2371 28.2117C31.3126 28.2117 31.384 28.1939 31.4509 28.1583V28.1588Z" fill="#03045E"/>
                            <path d="M33.1635 27.2262C33.2594 27.3197 33.3073 27.4539 33.3073 27.6297V28.3764H33.0982V27.6597C33.0982 27.5332 33.0665 27.4366 33.0028 27.3695C32.9395 27.3024 32.8528 27.2689 32.7431 27.2689C32.6334 27.2689 32.5431 27.3035 32.4773 27.3731C32.4115 27.4422 32.3789 27.5433 32.3789 27.676V28.3764H32.1676V27.1093H32.3789V27.2897C32.4207 27.2252 32.4773 27.1749 32.5498 27.1393C32.6217 27.1037 32.7008 27.0859 32.7875 27.0859C32.9426 27.0859 33.0676 27.1327 33.1635 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M34.6491 27.2262C34.745 27.3197 34.7929 27.4539 34.7929 27.6297V28.3764H34.5838V27.6597C34.5838 27.5332 34.5521 27.4366 34.4884 27.3695C34.4251 27.3024 34.3384 27.2689 34.2287 27.2689C34.119 27.2689 34.0287 27.3035 33.9629 27.3731C33.8971 27.4422 33.8644 27.5433 33.8644 27.676V28.3764H33.6532V27.1093H33.8644V27.2897C33.9063 27.2252 33.9629 27.1749 34.0353 27.1393C34.1073 27.1037 34.1863 27.0859 34.2731 27.0859C34.4282 27.0859 34.5531 27.1327 34.6491 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M36.2923 27.8214H35.2755C35.2832 27.9464 35.3265 28.044 35.4046 28.1141C35.4827 28.1843 35.5775 28.2193 35.6888 28.2193C35.7801 28.2193 35.8561 28.198 35.9173 28.1558C35.978 28.1136 36.0214 28.0567 36.0459 27.986H36.2734C36.2392 28.108 36.1714 28.2066 36.0693 28.2834C35.9673 28.3596 35.8403 28.3977 35.6888 28.3977C35.5684 28.3977 35.4602 28.3708 35.3648 28.3169C35.2694 28.263 35.1949 28.1863 35.1408 28.0867C35.0868 27.987 35.0597 27.8722 35.0597 27.7411C35.0597 27.6099 35.0863 27.4951 35.1388 27.3965C35.1914 27.2979 35.2653 27.2216 35.3602 27.1688C35.4551 27.1159 35.5648 27.089 35.6888 27.089C35.8127 27.089 35.9163 27.1154 36.0091 27.1677C36.102 27.2201 36.1734 27.2923 36.2239 27.3838C36.2744 27.4757 36.2994 27.5794 36.2994 27.6948C36.2994 27.735 36.2969 27.7771 36.2923 27.8219V27.8214ZM36.0275 27.4437C35.9918 27.3858 35.9438 27.3421 35.8826 27.3121C35.8219 27.2821 35.7541 27.2669 35.6796 27.2669C35.573 27.2669 35.4821 27.3009 35.4066 27.3685C35.3311 27.4361 35.2888 27.5301 35.2781 27.6506H36.0811C36.0811 27.5708 36.0632 27.5017 36.0275 27.4437Z" fill="#03045E"/>
                            <path d="M36.5774 27.3975C36.63 27.2994 36.7029 27.2237 36.7968 27.1698C36.8902 27.1159 36.9973 27.089 37.1182 27.089C37.2743 27.089 37.4034 27.1271 37.5044 27.2023C37.6059 27.278 37.6728 27.3827 37.7054 27.5164H37.4779C37.4564 27.4392 37.4141 27.3787 37.3514 27.3339C37.2886 27.2892 37.2111 27.2669 37.1182 27.2669C36.9978 27.2669 36.9004 27.308 36.8259 27.3904C36.7514 27.4727 36.7142 27.5896 36.7142 27.7405C36.7142 27.8915 36.7514 28.0109 36.8259 28.0943C36.9004 28.1776 36.9978 28.2193 37.1182 28.2193C37.2111 28.2193 37.2886 28.198 37.3503 28.1548C37.4121 28.1116 37.4544 28.0501 37.4779 27.9698H37.7054C37.6712 28.0994 37.6034 28.2031 37.5013 28.2808C37.3993 28.3586 37.2713 28.3977 37.1182 28.3977C36.9978 28.3977 36.8902 28.3708 36.7968 28.3169C36.7029 28.263 36.63 28.1868 36.5774 28.0882C36.5249 27.9896 36.4984 27.8737 36.4984 27.7416C36.4984 27.6094 36.5249 27.4961 36.5774 27.3985V27.3975Z" fill="#03045E"/>
                            <path d="M38.2441 27.2826V28.0292C38.2441 28.0907 38.2574 28.1344 38.2834 28.1599C38.3099 28.1853 38.3554 28.198 38.4207 28.198H38.5762V28.3759H38.386C38.2681 28.3759 38.1804 28.3489 38.1212 28.2951C38.0625 28.2412 38.0329 28.1522 38.0329 28.0292V27.2826H37.8682V27.1093H38.0329V26.7901H38.2441V27.1093H38.5757V27.2826H38.2441Z" fill="#03045E"/>
                            <path d="M39.9843 27.8214H38.9675C38.9752 27.9464 39.0186 28.044 39.0966 28.1141C39.1747 28.1843 39.2696 28.2193 39.3808 28.2193C39.4721 28.2193 39.5481 28.198 39.6093 28.1558C39.67 28.1136 39.7134 28.0567 39.7379 27.986H39.9654C39.9312 28.108 39.8634 28.2066 39.7613 28.2834C39.6593 28.3596 39.5323 28.3977 39.3808 28.3977C39.2604 28.3977 39.1522 28.3708 39.0568 28.3169C38.9614 28.263 38.8869 28.1863 38.8329 28.0867C38.7788 27.987 38.7517 27.8722 38.7517 27.7411C38.7517 27.6099 38.7783 27.4951 38.8308 27.3965C38.8834 27.2979 38.9573 27.2216 39.0522 27.1688C39.1471 27.1159 39.2568 27.089 39.3808 27.089C39.5047 27.089 39.6083 27.1154 39.7011 27.1677C39.794 27.2201 39.8654 27.2923 39.9159 27.3838C39.9664 27.4757 39.9914 27.5794 39.9914 27.6948C39.9914 27.735 39.9889 27.7771 39.9843 27.8219V27.8214ZM39.7195 27.4437C39.6838 27.3858 39.6358 27.3421 39.5746 27.3121C39.5139 27.2821 39.4461 27.2669 39.3716 27.2669C39.265 27.2669 39.1742 27.3009 39.0987 27.3685C39.0231 27.4361 38.9808 27.5301 38.9701 27.6506H39.7731C39.7731 27.5708 39.7552 27.5017 39.7195 27.4437Z" fill="#03045E"/>
                            <path d="M40.27 27.3975C40.3225 27.2994 40.395 27.2237 40.4868 27.1698C40.5791 27.1159 40.6822 27.089 40.797 27.089C40.8959 27.089 40.9878 27.1118 41.073 27.1571C41.1582 27.2028 41.2229 27.2623 41.2683 27.3365V26.6661H41.4816V28.3769H41.2683V28.139C41.2265 28.2147 41.1648 28.2762 41.0826 28.325C41.001 28.3738 40.9051 28.3977 40.7949 28.3977C40.6847 28.3977 40.5796 28.3698 40.4873 28.3144C40.3955 28.259 40.323 28.1812 40.2705 28.0811C40.2179 27.981 40.1914 27.8666 40.1914 27.739C40.1914 27.6115 40.2179 27.4961 40.2705 27.398L40.27 27.3975ZM41.2097 27.491C41.1709 27.4198 41.1189 27.3655 41.0531 27.3278C40.9872 27.2902 40.9153 27.2714 40.8362 27.2714C40.7572 27.2714 40.6852 27.2897 40.6204 27.3268C40.5557 27.3639 40.5036 27.4178 40.4648 27.4885C40.4261 27.5591 40.4067 27.6425 40.4067 27.738C40.4067 27.8336 40.4261 27.9195 40.4648 27.9911C40.5036 28.0628 40.5551 28.1177 40.6204 28.1553C40.6857 28.1929 40.7577 28.2117 40.8362 28.2117C40.9148 28.2117 40.9878 28.1929 41.0531 28.1553C41.1184 28.1177 41.1709 28.0628 41.2097 27.9911C41.2484 27.9195 41.2678 27.8356 41.2678 27.74C41.2678 27.6445 41.2484 27.5611 41.2097 27.4905V27.491Z" fill="#03045E"/>
                    </svg>
                    <span class="content">
                        <span>Python Certification Course By The IoT Academy</span>
                        <span class="duration">45 Days</span>
                    </span>
                </a>
            </div>
            <div class="nsh-ach-right management d-none">
                <a href="http://localhost/TIA/embedded-systems-training">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="29" viewBox="0 0 48 29" fill="none">
                            <path d="M20.3044 5.93391C20.5931 5.53086 20.8533 5.12833 21.1523 4.75578C23.1934 2.21502 25.835 0.672469 29.0587 0.173872C32.7083 -0.390797 36.0443 0.414276 38.9761 2.67143C41.4075 4.54282 42.8967 7.01039 43.4186 10.035C43.6425 11.3321 43.617 12.6337 43.3696 13.9262C43.3308 14.1285 43.3885 14.1915 43.5548 14.2657C45.2684 15.0342 46.5612 16.2438 47.3478 17.9511C48.8717 21.2572 47.6498 25.1225 44.4685 27.0386C43.9624 27.3436 43.3977 27.555 42.8518 27.7903C42.6003 27.8986 42.4391 27.8269 42.3697 27.6353C42.2978 27.4356 42.3947 27.2999 42.6646 27.2023C45.0241 26.3484 46.6198 24.7652 47.2075 22.3078C47.832 19.6969 46.7234 17.0032 44.4501 15.4835C43.9716 15.1633 43.4313 14.9336 42.913 14.6743C42.6819 14.5585 42.6197 14.4721 42.6758 14.221C43.6961 9.68075 41.7045 5.22642 37.965 2.70192C36.1749 1.4933 34.199 0.774628 32.039 0.667386C27.1742 0.425966 23.3766 2.29837 20.7615 6.43708C20.6064 6.68206 20.4722 6.72678 20.1983 6.58651C18.7535 5.84801 17.2154 5.55526 15.6053 5.68334C13.6387 5.83988 11.9128 6.5799 10.4701 7.92931C9.11613 9.19486 8.30396 10.7496 8.05449 12.5844C8.00092 12.9803 8.00603 13.3849 7.99582 13.7859C7.98715 14.1219 7.91114 14.1966 7.56882 14.2083C5.02977 14.2967 3.007 15.3427 1.61835 17.4814C0.0123727 19.9561 0.390909 23.2923 2.46674 25.3985C3.37278 26.3179 4.43901 26.9593 5.6843 27.3065C5.71389 27.3146 5.74246 27.3278 5.77256 27.3354C5.96999 27.3863 6.0608 27.5143 6.02815 27.7121C5.99856 27.8925 5.82255 27.9992 5.61135 27.9255C5.0864 27.7431 4.54461 27.5835 4.05486 27.3283C1.9474 26.23 0.573035 24.539 0.135321 22.1909C-0.323821 19.7279 0.39397 17.5887 2.1586 15.8194C3.51817 14.4558 5.19455 13.7432 7.11886 13.6096C7.25405 13.6004 7.3668 13.6055 7.37547 13.4047C7.51934 10.1046 9.08858 7.6574 11.9807 6.10875C14.5799 4.71715 17.2883 4.68259 20.0126 5.80532C20.1115 5.84598 20.2085 5.89121 20.3049 5.9334L20.3044 5.93391Z" fill="#03045E"/>
                            <path d="M10.5676 23.8793C12.3516 23.8555 14.1351 23.8351 15.9191 23.8072C17.6639 23.7802 19.4081 23.7472 21.1528 23.7152C22.7808 23.6852 24.4092 23.6547 26.0371 23.6222C27.541 23.5927 29.045 23.5622 30.5489 23.5292C31.8743 23.5002 33.2002 23.4727 34.5256 23.4351C35.6102 23.4041 36.6948 23.3619 37.7789 23.3187C38.1462 23.304 38.361 22.9533 38.2268 22.6001C37.8187 21.5261 37.408 20.4527 36.9988 19.3788C36.9662 19.2934 36.9381 19.2065 36.8943 19.0825C37.2259 19.0825 37.5319 19.0738 37.8365 19.0906C37.8875 19.0931 37.9523 19.1983 37.9763 19.268C38.2288 19.9963 38.4737 20.7272 38.7221 21.4575C38.7415 21.5144 38.767 21.5693 38.8104 21.6751C38.8609 21.5393 38.8976 21.4504 38.9283 21.3594C39.1594 20.6646 39.395 19.9709 39.6159 19.2725C39.6654 19.1165 39.7389 19.0682 39.895 19.0758C40.1419 19.088 40.3899 19.0789 40.6664 19.0789C40.6419 19.1633 40.6271 19.2268 40.6046 19.2878C40.119 20.6128 39.6348 21.9383 39.1451 23.2623C38.863 24.0257 38.4946 24.2819 37.6712 24.2738C35.8408 24.2555 34.0103 24.229 32.1804 24.2046C31.1336 24.1909 30.0867 24.1736 29.0399 24.1589C26.8916 24.1279 24.7438 24.0969 22.5956 24.0659C21.5564 24.0506 20.5172 24.0349 19.4785 24.0196C17.3226 23.9886 15.1672 23.9546 13.0112 23.9281C12.1975 23.9185 11.3833 23.9266 10.5691 23.9266L10.5681 23.8788L10.5676 23.8793Z" fill="#03045E"/>
                            <path d="M36.3111 22.751H35.3827C35.3827 22.38 35.3862 22.0257 35.3816 21.6715C35.3755 21.1932 35.3663 20.715 35.3521 20.2367C35.3454 20.0136 35.2648 19.8276 35.0301 19.7518C34.7837 19.6725 34.57 19.7437 34.3873 19.9109C34.1705 20.1096 34.1251 20.3785 34.12 20.6519C34.1093 21.2466 34.1088 21.8413 34.1057 22.4359C34.1037 22.7683 34.1057 22.7683 33.7604 22.7683C33.6206 22.7683 33.4803 22.7607 33.3415 22.7703C33.1849 22.781 33.1436 22.7124 33.1451 22.566C33.1517 22.0329 33.1502 21.4997 33.1461 20.9671C33.1446 20.7434 33.1354 20.5198 33.1206 20.2967C33.0972 19.9465 32.9829 19.7793 32.7462 19.7305C32.4498 19.6695 32.0865 19.8159 31.9922 20.1C31.9013 20.3724 31.8646 20.6707 31.8534 20.9594C31.8325 21.4916 31.8478 22.0252 31.8463 22.5584C31.8463 22.6184 31.836 22.6783 31.8279 22.7663C31.5488 22.7663 31.279 22.7719 31.0096 22.7602C30.9678 22.7581 30.8953 22.6748 30.8953 22.629C30.8917 21.4707 30.8963 20.3119 30.9004 19.1536C30.9004 19.1399 30.9127 19.1257 30.9264 19.0957H31.7779C31.7845 19.2365 31.7906 19.365 31.7988 19.5399C32.1304 19.2151 32.4727 18.992 32.9308 18.9905C33.3895 18.9894 33.7415 19.1607 33.9925 19.5572C34.1037 19.4581 34.1955 19.3671 34.2965 19.2873C34.7087 18.961 35.1715 18.9096 35.6561 19.0758C36.0418 19.2085 36.2943 19.4952 36.3045 19.8997C36.328 20.8405 36.3122 21.7818 36.3122 22.75L36.3111 22.751Z" fill="#03045E"/>
                            <path d="M8.89734 22.7683C9.12079 22.1975 9.32587 21.6751 9.52994 21.1521C9.96255 20.0466 10.3977 18.9422 10.8242 17.8347C10.8773 17.6975 10.941 17.6431 11.091 17.6512C11.3308 17.6644 11.5726 17.6436 11.8114 17.6614C11.8894 17.667 12.0042 17.7305 12.0312 17.7961C12.6893 19.4098 13.3383 21.0275 13.9877 22.6443C13.9959 22.6651 13.9953 22.6895 14.0071 22.7683C13.7581 22.7683 13.5143 22.7485 13.2755 22.7734C13.0061 22.8018 12.8653 22.7119 12.7949 22.4481C12.7317 22.2113 12.6169 21.9886 12.5343 21.7564C12.4929 21.64 12.4343 21.5831 12.3006 21.5841C11.7267 21.5902 11.1527 21.5897 10.5788 21.5841C10.4452 21.5826 10.388 21.64 10.3477 21.7589C10.2544 22.0364 10.1416 22.3073 10.0503 22.5853C10.008 22.7139 9.94775 22.7754 9.80389 22.7703C9.51871 22.7612 9.23302 22.7678 8.89734 22.7678V22.7683ZM12.1425 20.7475C11.9032 20.1071 11.6772 19.5023 11.4512 18.898C11.4333 18.9 11.4155 18.902 11.3976 18.9041C11.1762 19.5109 10.9553 20.1183 10.7263 20.7475H12.1425Z" fill="#03045E"/>
                            <path d="M33.4303 14.5153C33.4303 15.79 32.5584 16.6733 31.3004 16.6728C29.9806 16.6723 29.1557 15.8357 29.1547 14.4975C29.1541 13.2452 30.0332 12.3684 31.2912 12.3664C32.5523 12.3644 33.4303 13.2467 33.4303 14.5158V14.5153ZM32.36 14.5153C32.3008 14.284 32.2697 14.035 32.1743 13.8144C32.0141 13.4439 31.7034 13.2416 31.2963 13.2421C30.879 13.2426 30.5673 13.4546 30.4025 13.8317C30.2066 14.281 30.203 14.7506 30.3984 15.2009C30.5607 15.575 30.855 15.7951 31.2825 15.7996C31.7192 15.8042 32.0243 15.5907 32.187 15.2019C32.2743 14.9936 32.3008 14.7598 32.36 14.5148V14.5153Z" fill="#03045E"/>
                            <path d="M25.0918 22.2499C25.0076 22.3221 24.9729 22.348 24.9423 22.3785C24.2638 23.0616 23.2659 22.9828 22.7134 22.2016C22.2487 21.5454 22.2359 20.285 22.6869 19.6227C23.1664 18.9188 24.1521 18.7765 24.8117 19.3157C24.8632 19.3579 24.9153 19.4001 25.003 19.4713V17.6695H25.9621V22.7658C25.7065 22.7658 25.4601 22.7719 25.2147 22.7597C25.1719 22.7576 25.1066 22.6829 25.0979 22.6341C25.0795 22.5304 25.0923 22.4217 25.0923 22.2494L25.0918 22.2499ZM23.3256 20.8674C23.3343 20.87 23.343 20.8725 23.3516 20.8746C23.3598 21.0052 23.3608 21.1368 23.3787 21.2664C23.3924 21.3645 23.42 21.4626 23.4542 21.5561C23.5725 21.8763 23.8531 22.0817 24.1725 22.0908C24.4622 22.0989 24.7775 21.8966 24.8949 21.5932C25.0709 21.1373 25.0668 20.6687 24.8974 20.2138C24.779 19.8962 24.5286 19.7208 24.1811 19.7223C23.8337 19.7244 23.5868 19.9068 23.4659 20.2189C23.3868 20.4227 23.37 20.6499 23.3256 20.8669V20.8674Z" fill="#03045E"/>
                            <path d="M17.7062 16.5885H16.7834V11.5191H17.7231V13.3631C17.8231 13.2782 17.8771 13.2319 17.9312 13.1862C18.3807 12.8076 18.8857 12.7313 19.4326 12.9265C19.8086 13.0607 20.0647 13.3412 20.0861 13.7275C20.1397 14.6734 20.1438 15.6218 20.1682 16.5859H19.176C19.176 16.4187 19.1775 16.2662 19.176 16.1137C19.1683 15.4652 19.1688 14.8167 19.1474 14.1687C19.1352 13.8012 18.9837 13.6279 18.6852 13.5679C18.372 13.5049 17.9919 13.6548 17.8817 13.9425C17.7904 14.1814 17.744 14.4487 17.7348 14.7059C17.7134 15.3077 17.7261 15.911 17.7246 16.5137C17.7246 16.5285 17.7174 16.5437 17.7067 16.5879L17.7062 16.5885Z" fill="#03045E"/>
                            <path d="M21.8084 22.7673C21.4865 22.7673 21.2238 22.7734 20.9615 22.7622C20.9105 22.7602 20.8457 22.7002 20.8161 22.6504C20.7707 22.5736 20.7503 22.4822 20.7116 22.3754C20.6335 22.4349 20.5748 22.4806 20.5146 22.5249C20.0871 22.8425 19.6101 22.9127 19.1041 22.781C18.6597 22.6656 18.3679 22.3038 18.3414 21.8672C18.3087 21.332 18.5153 20.9564 18.9959 20.7917C19.3739 20.6621 19.7734 20.5935 20.1631 20.4974C20.2305 20.4807 20.3004 20.4695 20.3647 20.4441C20.4764 20.3998 20.6478 20.4283 20.6417 20.2311C20.635 20.0253 20.5784 19.8499 20.3611 19.7716C19.9035 19.6065 19.5622 19.7325 19.2964 20.1788C19.1602 20.1544 19.0173 20.1284 18.8745 20.103C18.7383 20.0786 18.6021 20.0547 18.4398 20.0263C18.5607 19.5887 18.7842 19.2507 19.2168 19.1104C19.7759 18.929 20.3499 18.9366 20.91 19.1028C21.3692 19.239 21.5676 19.5475 21.5865 20.0944C21.5982 20.4415 21.586 20.7892 21.5895 21.1363C21.5952 21.6725 21.5283 22.2169 21.8084 22.7678V22.7673ZM20.6177 20.9889C20.2677 21.0799 19.9254 21.1465 19.5994 21.2593C19.2724 21.3726 19.2046 21.7416 19.4372 21.9947C19.5928 22.164 19.8693 22.2133 20.1269 22.1177C20.5636 21.9561 20.7044 21.6491 20.6177 20.9889Z" fill="#03045E"/>
                            <path d="M27.7022 21.1922C27.6747 21.644 27.9083 22.0029 28.269 22.0994C28.6629 22.2052 28.8827 22.0822 29.145 21.6024C29.4434 21.6532 29.7454 21.705 30.0821 21.763C29.9265 22.1935 29.6771 22.506 29.2776 22.6885C28.0104 23.2669 26.7146 22.4791 26.6942 21.0972C26.6886 20.7277 26.7401 20.3358 26.8656 19.9902C27.1278 19.268 27.8099 18.9183 28.6476 19.0006C29.3587 19.0708 29.9056 19.5699 30.0597 20.3058C30.1051 20.5239 30.1163 20.749 30.1464 20.9701C30.1699 21.1434 30.0985 21.1978 29.9245 21.1953C29.273 21.1866 28.6215 21.1917 27.9701 21.1917C27.8859 21.1917 27.8022 21.1917 27.7022 21.1917V21.1922ZM27.714 20.5823H29.1674C29.1368 20.0075 28.8246 19.6827 28.367 19.7239C27.9578 19.761 27.6681 20.1366 27.714 20.5823Z" fill="#03045E"/>
                            <path d="M24.3602 15.0393H21.8977C21.8385 15.4327 22.0727 15.8179 22.4226 15.9282C22.82 16.0533 23.0797 15.9186 23.3297 15.4444C23.6261 15.4932 23.9281 15.543 24.2684 15.5989C24.1271 15.9963 23.9092 16.2881 23.5618 16.4756C22.3068 17.1541 20.9172 16.3562 20.8666 14.9265C20.8473 14.3831 20.9223 13.8571 21.2829 13.42C21.7482 12.8563 22.3645 12.7278 23.0481 12.8818C23.7052 13.0297 24.0863 13.4734 24.2347 14.1107C24.3026 14.4009 24.3163 14.7033 24.3597 15.0393H24.3602ZM21.9053 14.4502C22.3481 14.4502 22.7956 14.4538 23.243 14.4446C23.2833 14.4436 23.3542 14.3638 23.3557 14.3186C23.369 13.8927 22.9807 13.5257 22.5639 13.5593C22.1431 13.5933 21.837 13.9923 21.9053 14.4502Z" fill="#03045E"/>
                            <path d="M14.5667 16.5864H13.5515V12.3623H12.0353V11.5201H16.0783V12.3456H14.5667V16.5864Z" fill="#03045E"/>
                            <path d="M35.9515 16.5839H34.9245V12.3572H33.4068C33.4068 12.0955 33.3976 11.852 33.4155 11.6106C33.4186 11.5694 33.5349 11.5064 33.6002 11.5054C34.0348 11.4958 34.4695 11.5003 34.9041 11.5003C35.6648 11.5003 36.4254 11.5003 37.1861 11.5003H37.4559V12.3476H35.952V16.5844L35.9515 16.5839Z" fill="#03045E"/>
                            <path d="M16.9338 21.3594C17.2486 21.4143 17.5506 21.4662 17.8705 21.5221C17.7251 22.1177 17.4348 22.5584 16.84 22.7464C15.9885 23.0148 15.1233 22.7642 14.6968 22.1269C14.2703 21.489 14.2581 20.3277 14.7065 19.7056C14.855 19.4997 15.0641 19.3081 15.2886 19.1922C15.8477 18.903 16.4426 18.9163 17.0211 19.147C17.4557 19.3203 17.6802 19.6873 17.822 20.1472C17.5119 20.2057 17.2109 20.2621 16.8951 20.3216C16.8767 20.2804 16.8583 20.2418 16.8425 20.2021C16.7216 19.8921 16.5043 19.7274 16.1931 19.7376C15.8768 19.7478 15.6059 19.8723 15.4927 20.1828C15.3182 20.6626 15.3274 21.1556 15.511 21.6308C15.6263 21.9292 15.9248 22.0776 16.2676 22.0583C16.564 22.041 16.7553 21.8834 16.868 21.5632C16.8884 21.5058 16.9063 21.4468 16.9344 21.3599L16.9338 21.3594Z" fill="#03045E"/>
                            <path d="M27.1166 14.0279C27.1166 13.2635 27.1217 12.4985 27.113 11.7341C27.111 11.5522 27.1666 11.4841 27.3513 11.4973C27.5441 11.511 27.7395 11.5085 27.9328 11.4978C28.0961 11.4886 28.1481 11.5501 28.1466 11.7113C28.14 12.4528 28.1435 13.1943 28.1435 13.9359C28.1435 14.747 28.1389 15.5577 28.1471 16.3689C28.1491 16.5508 28.0935 16.6189 27.9089 16.6057C27.716 16.592 27.5206 16.5945 27.3273 16.6052C27.164 16.6144 27.112 16.5529 27.1135 16.3918C27.1196 15.604 27.1166 14.8162 27.1166 14.0284V14.0279Z" fill="#03045E"/>
                            <path d="M7.26688 27.1454C7.3383 27.0198 7.43523 26.9212 7.55818 26.8506C7.68112 26.7799 7.81734 26.7444 7.96783 26.7444C8.14435 26.7444 8.29842 26.7866 8.42953 26.8714C8.56115 26.9563 8.65706 27.0763 8.71725 27.2323H8.46422C8.41932 27.1352 8.35453 27.0605 8.27036 27.0082C8.18618 26.9558 8.08517 26.9299 7.96732 26.9299C7.84948 26.9299 7.75306 26.9563 7.66327 27.0082C7.57348 27.0605 7.50308 27.1352 7.45207 27.2313C7.40105 27.3278 7.37503 27.4407 7.37503 27.5698C7.37503 27.6989 7.40054 27.8097 7.45207 27.9062C7.50308 28.0028 7.57348 28.077 7.66327 28.1294C7.75306 28.1817 7.85458 28.2076 7.96732 28.2076C8.08007 28.2076 8.18567 28.1817 8.27036 28.1304C8.35504 28.079 8.41932 28.0043 8.46422 27.9073H8.71725C8.65706 28.0613 8.56115 28.1807 8.42953 28.2646C8.29791 28.3484 8.14435 28.3906 7.96783 28.3906C7.81785 28.3906 7.68112 28.3555 7.55818 28.2854C7.43523 28.2153 7.3383 28.1177 7.26688 27.9926C7.19597 27.8676 7.16026 27.7268 7.16026 27.5698C7.16026 27.4127 7.19597 27.2714 7.26688 27.1454Z" fill="#03045E"/>
                            <path d="M9.2621 28.3164C9.16517 28.2625 9.08916 28.1858 9.03457 28.0862C8.97947 27.9865 8.95244 27.8717 8.95244 27.7405C8.95244 27.6094 8.9805 27.4966 9.03712 27.3975C9.09375 27.2979 9.17078 27.2216 9.26822 27.1688C9.36566 27.1154 9.47484 27.089 9.59523 27.089C9.71563 27.089 9.82532 27.1154 9.92276 27.1688C10.0202 27.2221 10.0972 27.2979 10.1539 27.3965C10.2105 27.4951 10.2385 27.6099 10.2385 27.7411C10.2385 27.8722 10.2095 27.987 10.1513 28.0867C10.0931 28.1858 10.0146 28.263 9.91561 28.3169C9.81664 28.3708 9.70645 28.3977 9.58605 28.3977C9.46566 28.3977 9.35903 28.3708 9.26261 28.3169L9.2621 28.3164ZM9.79879 28.1588C9.86511 28.1233 9.91919 28.0704 9.96 27.9992C10.0008 27.9286 10.0212 27.8422 10.0212 27.74C10.0212 27.6379 10.0013 27.552 9.96102 27.4813C9.92072 27.4107 9.86817 27.3578 9.80338 27.3228C9.73859 27.2882 9.66768 27.2709 9.59217 27.2709C9.51667 27.2709 9.44423 27.2882 9.37995 27.3228C9.31567 27.3573 9.26414 27.4102 9.22537 27.4813C9.1866 27.552 9.16721 27.6384 9.16721 27.74C9.16721 27.8417 9.1866 27.9306 9.22435 28.0013C9.26261 28.0719 9.31312 28.1248 9.37638 28.1599C9.43964 28.1944 9.50953 28.2117 9.58503 28.2117C9.66054 28.2117 9.73196 28.1939 9.79879 28.1583V28.1588Z" fill="#03045E"/>
                            <path d="M11.5114 27.2262C11.6073 27.3197 11.6553 27.4539 11.6553 27.6297V28.3764H11.4461V27.6597C11.4461 27.5332 11.4145 27.4366 11.3507 27.3695C11.2874 27.3024 11.2007 27.2689 11.091 27.2689C10.9813 27.2689 10.891 27.3035 10.8252 27.3731C10.7594 27.4422 10.7268 27.5433 10.7268 27.676V28.3764H10.5156V27.1093H10.7268V27.2897C10.7686 27.2252 10.8252 27.1749 10.8977 27.1393C10.9696 27.1037 11.0487 27.0859 11.1354 27.0859C11.2905 27.0859 11.4155 27.1327 11.5114 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M12.997 27.2262C13.0929 27.3197 13.1408 27.4539 13.1408 27.6297V28.3764H12.9317V27.6597C12.9317 27.5332 12.9 27.4366 12.8363 27.3695C12.773 27.3024 12.6863 27.2689 12.5766 27.2689C12.4669 27.2689 12.3766 27.3035 12.3108 27.3731C12.245 27.4422 12.2123 27.5433 12.2123 27.676V28.3764H12.0011V27.1093H12.2123V27.2897C12.2542 27.2252 12.3108 27.1749 12.3832 27.1393C12.4552 27.1037 12.5343 27.0859 12.621 27.0859C12.7761 27.0859 12.9011 27.1327 12.997 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M14.6397 27.8214H13.6229C13.6306 27.9464 13.6739 28.044 13.752 28.1141C13.8301 28.1843 13.9249 28.2193 14.0362 28.2193C14.1275 28.2193 14.2035 28.198 14.2647 28.1558C14.3254 28.1136 14.3688 28.0567 14.3933 27.986H14.6208C14.5866 28.108 14.5188 28.2066 14.4167 28.2834C14.3147 28.3596 14.1877 28.3977 14.0362 28.3977C13.9158 28.3977 13.8076 28.3708 13.7122 28.3169C13.6168 28.263 13.5423 28.1863 13.4882 28.0867C13.4342 27.987 13.4071 27.8722 13.4071 27.7411C13.4071 27.6099 13.4337 27.4951 13.4862 27.3965C13.5388 27.2979 13.6127 27.2216 13.7076 27.1688C13.8025 27.1159 13.9122 27.089 14.0362 27.089C14.1601 27.089 14.2637 27.1154 14.3565 27.1677C14.4494 27.2201 14.5208 27.2923 14.5713 27.3838C14.6218 27.4757 14.6468 27.5794 14.6468 27.6948C14.6468 27.735 14.6443 27.7771 14.6397 27.8219V27.8214ZM14.3754 27.4437C14.3397 27.3858 14.2917 27.3421 14.2305 27.3121C14.1698 27.2821 14.102 27.2669 14.0275 27.2669C13.9209 27.2669 13.83 27.3009 13.7545 27.3685C13.679 27.4361 13.6367 27.5301 13.626 27.6506H14.429C14.429 27.5708 14.4111 27.5017 14.3754 27.4437Z" fill="#03045E"/>
                            <path d="M14.9254 27.3975C14.9779 27.2994 15.0509 27.2237 15.1447 27.1698C15.2381 27.1159 15.3452 27.089 15.4661 27.089C15.6222 27.089 15.7513 27.1271 15.8523 27.2023C15.9538 27.278 16.0207 27.3827 16.0533 27.5164H15.8258C15.8044 27.4392 15.762 27.3787 15.6993 27.3339C15.6365 27.2892 15.559 27.2669 15.4661 27.2669C15.3457 27.2669 15.2483 27.308 15.1738 27.3904C15.0993 27.4727 15.0621 27.5896 15.0621 27.7405C15.0621 27.8915 15.0993 28.0109 15.1738 28.0943C15.2483 28.1776 15.3457 28.2193 15.4661 28.2193C15.559 28.2193 15.6365 28.198 15.6982 28.1548C15.76 28.1116 15.8023 28.0501 15.8258 27.9698H16.0533C16.0191 28.0994 15.9513 28.2031 15.8493 28.2808C15.7472 28.3586 15.6192 28.3977 15.4661 28.3977C15.3457 28.3977 15.2381 28.3708 15.1447 28.3169C15.0509 28.263 14.9779 28.1868 14.9254 28.0882C14.8728 27.9896 14.8463 27.8737 14.8463 27.7416C14.8463 27.6094 14.8728 27.4961 14.9254 27.3985V27.3975Z" fill="#03045E"/>
                            <path d="M16.5915 27.2826V28.0292C16.5915 28.0907 16.6048 28.1344 16.6308 28.1599C16.6573 28.1853 16.7027 28.198 16.768 28.198H16.9236V28.3759H16.7334C16.6155 28.3759 16.5278 28.3489 16.4686 28.2951C16.4099 28.2412 16.3803 28.1522 16.3803 28.0292V27.2826H16.2155V27.1093H16.3803V26.7901H16.5915V27.1093H16.9231V27.2826H16.5915Z" fill="#03045E"/>
                            <path d="M17.1859 26.8618C17.1583 26.8338 17.144 26.8003 17.144 26.7601C17.144 26.72 17.1583 26.6859 17.1859 26.6585C17.2139 26.6305 17.2476 26.6168 17.2879 26.6168C17.3282 26.6168 17.3593 26.6305 17.3864 26.6585C17.4134 26.6864 17.4272 26.72 17.4272 26.7601C17.4272 26.8003 17.4134 26.8343 17.3864 26.8618C17.3593 26.8897 17.3267 26.9035 17.2879 26.9035C17.2491 26.9035 17.2134 26.8897 17.1859 26.8618ZM17.3899 27.1093V28.3764H17.1787V27.1093H17.3899Z" fill="#03045E"/>
                            <path d="M18.7454 27.2262C18.8413 27.3197 18.8893 27.4539 18.8893 27.6297V28.3764H18.6801V27.6597C18.6801 27.5332 18.6485 27.4366 18.5847 27.3695C18.5215 27.3024 18.4347 27.2689 18.325 27.2689C18.2154 27.2689 18.1251 27.3035 18.0593 27.3731C17.9934 27.4422 17.9608 27.5433 17.9608 27.676V28.3764H17.7496V27.1093H17.9608V27.2897C18.0026 27.2252 18.0593 27.1749 18.1317 27.1393C18.2036 27.1037 18.2827 27.0859 18.3694 27.0859C18.5245 27.0859 18.6495 27.1327 18.7454 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M20.0484 27.1601C20.131 27.2079 20.1927 27.2679 20.233 27.3406V27.1093H20.4463V28.4038C20.4463 28.5197 20.4218 28.6224 20.3718 28.7123C20.3223 28.8023 20.2519 28.8729 20.1596 28.9238C20.0677 28.9746 19.9606 29 19.8382 29C19.6708 29 19.5316 28.9609 19.4204 28.8821C19.3091 28.8033 19.2433 28.6961 19.2234 28.5609H19.4321C19.455 28.6381 19.5035 28.6996 19.5759 28.7469C19.6489 28.7942 19.7361 28.8175 19.8382 28.8175C19.9545 28.8175 20.0489 28.7814 20.1223 28.7088C20.1958 28.6361 20.2325 28.5344 20.2325 28.4038V28.138C20.1907 28.2117 20.129 28.2737 20.0468 28.323C19.9652 28.3723 19.8693 28.3972 19.7591 28.3972C19.6489 28.3972 19.5438 28.3693 19.4515 28.3139C19.3596 28.2585 19.2872 28.1807 19.2347 28.0806C19.1821 27.9804 19.1556 27.8661 19.1556 27.7385C19.1556 27.6109 19.1821 27.4956 19.2347 27.3975C19.2872 27.2994 19.3596 27.2237 19.4515 27.1698C19.5438 27.1159 19.6464 27.089 19.7591 27.089C19.8718 27.089 19.9652 27.1129 20.0484 27.1606V27.1601ZM20.1749 27.491C20.1361 27.4198 20.0841 27.3655 20.0183 27.3278C19.9524 27.2902 19.8805 27.2714 19.8014 27.2714C19.7224 27.2714 19.6504 27.2897 19.5856 27.3268C19.5209 27.3639 19.4688 27.4178 19.43 27.4885C19.3913 27.5591 19.3719 27.6425 19.3719 27.738C19.3719 27.8336 19.3913 27.9195 19.43 27.9911C19.4688 28.0628 19.5203 28.1177 19.5856 28.1553C19.6509 28.1929 19.7229 28.2117 19.8014 28.2117C19.88 28.2117 19.953 28.1929 20.0183 28.1553C20.0836 28.1177 20.1361 28.0628 20.1749 27.9911C20.2136 27.9195 20.233 27.8356 20.233 27.74C20.233 27.6445 20.2136 27.5611 20.1749 27.4905V27.491Z" fill="#03045E"/>
                            <path d="M22.4191 26.7647V26.936H21.9783V28.3764H21.7671V26.936H21.3238V26.7647H22.4191Z" fill="#03045E"/>
                            <path d="M23.57 27.1474C23.6475 27.1881 23.7077 27.2496 23.7521 27.3314C23.7965 27.4132 23.8184 27.5123 23.8184 27.6297V28.3764H23.6093V27.6597C23.6093 27.5332 23.5776 27.4366 23.5139 27.3695C23.4506 27.3024 23.3639 27.2689 23.2542 27.2689C23.1445 27.2689 23.0542 27.3035 22.9884 27.3731C22.9226 27.4422 22.8899 27.5433 22.8899 27.676V28.3764H22.6787V26.6656H22.8899V27.2897C22.9318 27.2252 22.9894 27.1749 23.0629 27.1393C23.1363 27.1037 23.219 27.0859 23.3098 27.0859C23.4057 27.0859 23.4924 27.1062 23.57 27.1469V27.1474Z" fill="#03045E"/>
                            <path d="M25.3178 27.8214H24.301C24.3087 27.9464 24.352 28.044 24.4301 28.1141C24.5081 28.1843 24.603 28.2193 24.7143 28.2193C24.8056 28.2193 24.8816 28.198 24.9428 28.1558C25.0035 28.1136 25.0469 28.0567 25.0714 27.986H25.2989C25.2647 28.108 25.1969 28.2066 25.0948 28.2834C24.9928 28.3596 24.8658 28.3977 24.7143 28.3977C24.5939 28.3977 24.4857 28.3708 24.3903 28.3169C24.2949 28.263 24.2204 28.1863 24.1663 28.0867C24.1123 27.987 24.0852 27.8722 24.0852 27.7411C24.0852 27.6099 24.1118 27.4951 24.1643 27.3965C24.2169 27.2979 24.2908 27.2216 24.3857 27.1688C24.4806 27.1159 24.5903 27.089 24.7143 27.089C24.8382 27.089 24.9418 27.1154 25.0346 27.1677C25.1275 27.2201 25.1989 27.2923 25.2494 27.3838C25.2999 27.4757 25.3249 27.5794 25.3249 27.6948C25.3249 27.735 25.3224 27.7771 25.3178 27.8219V27.8214ZM25.053 27.4437C25.0173 27.3858 24.9693 27.3421 24.9081 27.3121C24.8474 27.2821 24.7796 27.2669 24.7051 27.2669C24.5984 27.2669 24.5076 27.3009 24.4321 27.3685C24.3566 27.4361 24.3143 27.5301 24.3036 27.6506H25.1066C25.1066 27.5708 25.0887 27.5017 25.053 27.4437Z" fill="#03045E"/>
                            <path d="M26.4289 26.7647V27.7843C26.4289 27.9276 26.4641 28.0338 26.5345 28.1034C26.6049 28.1726 26.7028 28.2076 26.8283 28.2076C26.9538 28.2076 27.0492 28.1731 27.1196 28.1034C27.19 28.0343 27.2252 27.9276 27.2252 27.7843V26.7647H27.4365V27.7822C27.4365 27.9164 27.4094 28.0292 27.3553 28.1207C27.3013 28.2122 27.2283 28.2808 27.136 28.3255C27.0441 28.3703 26.9406 28.3926 26.8263 28.3926C26.712 28.3926 26.6085 28.3703 26.5161 28.3255C26.4243 28.2808 26.3513 28.2127 26.2983 28.1207C26.2452 28.0287 26.2182 27.9159 26.2182 27.7822V26.7647H26.4294H26.4289Z" fill="#03045E"/>
                            <path d="M28.7843 27.2262C28.8802 27.3197 28.9282 27.4539 28.9282 27.6297V28.3764H28.719V27.6597C28.719 27.5332 28.6874 27.4366 28.6236 27.3695C28.5603 27.3024 28.4736 27.2689 28.3639 27.2689C28.2542 27.2689 28.1639 27.3035 28.0981 27.3731C28.0323 27.4422 27.9997 27.5433 27.9997 27.676V28.3764H27.7885V27.1093H27.9997V27.2897C28.0415 27.2252 28.0981 27.1749 28.1706 27.1393C28.2425 27.1037 28.3216 27.0859 28.4083 27.0859C28.5634 27.0859 28.6884 27.1327 28.7843 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M29.274 27.3975C29.3266 27.2994 29.3995 27.2237 29.4934 27.1698C29.5868 27.1159 29.6939 27.089 29.8148 27.089C29.9709 27.089 30.1 27.1271 30.201 27.2023C30.3025 27.278 30.3693 27.3827 30.402 27.5164H30.1745C30.153 27.4392 30.1107 27.3787 30.0479 27.3339C29.9852 27.2892 29.9077 27.2669 29.8148 27.2669C29.6944 27.2669 29.597 27.308 29.5225 27.3904C29.448 27.4727 29.4108 27.5896 29.4108 27.7405C29.4108 27.8915 29.448 28.0109 29.5225 28.0943C29.597 28.1776 29.6944 28.2193 29.8148 28.2193C29.9077 28.2193 29.9852 28.198 30.0469 28.1548C30.1087 28.1116 30.151 28.0501 30.1745 27.9698H30.402C30.3678 28.0994 30.3 28.2031 30.1979 28.2808C30.0959 28.3586 29.9679 28.3977 29.8148 28.3977C29.6944 28.3977 29.5868 28.3708 29.4934 28.3169C29.3995 28.263 29.3266 28.1868 29.274 28.0882C29.2215 27.9896 29.195 27.8737 29.195 27.7416C29.195 27.6094 29.2215 27.4961 29.274 27.3985V27.3975Z" fill="#03045E"/>
                            <path d="M30.9137 28.3164C30.8168 28.2625 30.7407 28.1858 30.6862 28.0862C30.6311 27.9865 30.604 27.8717 30.604 27.7405C30.604 27.6094 30.6321 27.4966 30.6887 27.3975C30.7453 27.2979 30.8224 27.2216 30.9198 27.1688C31.0172 27.1159 31.1264 27.089 31.2468 27.089C31.3672 27.089 31.4769 27.1154 31.5743 27.1688C31.6718 27.2221 31.7488 27.2979 31.8054 27.3965C31.8621 27.4951 31.8901 27.6099 31.8901 27.7411C31.8901 27.8722 31.861 27.987 31.8029 28.0867C31.7447 28.1858 31.6662 28.263 31.5672 28.3169C31.4682 28.3708 31.358 28.3977 31.2376 28.3977C31.1172 28.3977 31.0106 28.3708 30.9142 28.3169L30.9137 28.3164ZM31.4509 28.1588C31.5172 28.1233 31.5713 28.0704 31.6121 27.9992C31.6529 27.9286 31.6733 27.8422 31.6733 27.74C31.6733 27.6379 31.6534 27.552 31.6131 27.4813C31.5728 27.4107 31.5203 27.3578 31.4555 27.3228C31.3907 27.2882 31.3198 27.2709 31.2443 27.2709C31.1688 27.2709 31.0963 27.2882 31.032 27.3228C30.9678 27.3573 30.9162 27.4102 30.8775 27.4813C30.8387 27.552 30.8193 27.6384 30.8193 27.74C30.8193 27.8417 30.8387 27.9306 30.8764 28.0013C30.9147 28.0719 30.9652 28.1248 31.0285 28.1599C31.0917 28.1944 31.1616 28.2117 31.2371 28.2117C31.3126 28.2117 31.384 28.1939 31.4509 28.1583V28.1588Z" fill="#03045E"/>
                            <path d="M33.1635 27.2262C33.2594 27.3197 33.3073 27.4539 33.3073 27.6297V28.3764H33.0982V27.6597C33.0982 27.5332 33.0665 27.4366 33.0028 27.3695C32.9395 27.3024 32.8528 27.2689 32.7431 27.2689C32.6334 27.2689 32.5431 27.3035 32.4773 27.3731C32.4115 27.4422 32.3789 27.5433 32.3789 27.676V28.3764H32.1676V27.1093H32.3789V27.2897C32.4207 27.2252 32.4773 27.1749 32.5498 27.1393C32.6217 27.1037 32.7008 27.0859 32.7875 27.0859C32.9426 27.0859 33.0676 27.1327 33.1635 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M34.6491 27.2262C34.745 27.3197 34.7929 27.4539 34.7929 27.6297V28.3764H34.5838V27.6597C34.5838 27.5332 34.5521 27.4366 34.4884 27.3695C34.4251 27.3024 34.3384 27.2689 34.2287 27.2689C34.119 27.2689 34.0287 27.3035 33.9629 27.3731C33.8971 27.4422 33.8644 27.5433 33.8644 27.676V28.3764H33.6532V27.1093H33.8644V27.2897C33.9063 27.2252 33.9629 27.1749 34.0353 27.1393C34.1073 27.1037 34.1863 27.0859 34.2731 27.0859C34.4282 27.0859 34.5531 27.1327 34.6491 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M36.2923 27.8214H35.2755C35.2832 27.9464 35.3265 28.044 35.4046 28.1141C35.4827 28.1843 35.5775 28.2193 35.6888 28.2193C35.7801 28.2193 35.8561 28.198 35.9173 28.1558C35.978 28.1136 36.0214 28.0567 36.0459 27.986H36.2734C36.2392 28.108 36.1714 28.2066 36.0693 28.2834C35.9673 28.3596 35.8403 28.3977 35.6888 28.3977C35.5684 28.3977 35.4602 28.3708 35.3648 28.3169C35.2694 28.263 35.1949 28.1863 35.1408 28.0867C35.0868 27.987 35.0597 27.8722 35.0597 27.7411C35.0597 27.6099 35.0863 27.4951 35.1388 27.3965C35.1914 27.2979 35.2653 27.2216 35.3602 27.1688C35.4551 27.1159 35.5648 27.089 35.6888 27.089C35.8127 27.089 35.9163 27.1154 36.0091 27.1677C36.102 27.2201 36.1734 27.2923 36.2239 27.3838C36.2744 27.4757 36.2994 27.5794 36.2994 27.6948C36.2994 27.735 36.2969 27.7771 36.2923 27.8219V27.8214ZM36.0275 27.4437C35.9918 27.3858 35.9438 27.3421 35.8826 27.3121C35.8219 27.2821 35.7541 27.2669 35.6796 27.2669C35.573 27.2669 35.4821 27.3009 35.4066 27.3685C35.3311 27.4361 35.2888 27.5301 35.2781 27.6506H36.0811C36.0811 27.5708 36.0632 27.5017 36.0275 27.4437Z" fill="#03045E"/>
                            <path d="M36.5774 27.3975C36.63 27.2994 36.7029 27.2237 36.7968 27.1698C36.8902 27.1159 36.9973 27.089 37.1182 27.089C37.2743 27.089 37.4034 27.1271 37.5044 27.2023C37.6059 27.278 37.6728 27.3827 37.7054 27.5164H37.4779C37.4564 27.4392 37.4141 27.3787 37.3514 27.3339C37.2886 27.2892 37.2111 27.2669 37.1182 27.2669C36.9978 27.2669 36.9004 27.308 36.8259 27.3904C36.7514 27.4727 36.7142 27.5896 36.7142 27.7405C36.7142 27.8915 36.7514 28.0109 36.8259 28.0943C36.9004 28.1776 36.9978 28.2193 37.1182 28.2193C37.2111 28.2193 37.2886 28.198 37.3503 28.1548C37.4121 28.1116 37.4544 28.0501 37.4779 27.9698H37.7054C37.6712 28.0994 37.6034 28.2031 37.5013 28.2808C37.3993 28.3586 37.2713 28.3977 37.1182 28.3977C36.9978 28.3977 36.8902 28.3708 36.7968 28.3169C36.7029 28.263 36.63 28.1868 36.5774 28.0882C36.5249 27.9896 36.4984 27.8737 36.4984 27.7416C36.4984 27.6094 36.5249 27.4961 36.5774 27.3985V27.3975Z" fill="#03045E"/>
                            <path d="M38.2441 27.2826V28.0292C38.2441 28.0907 38.2574 28.1344 38.2834 28.1599C38.3099 28.1853 38.3554 28.198 38.4207 28.198H38.5762V28.3759H38.386C38.2681 28.3759 38.1804 28.3489 38.1212 28.2951C38.0625 28.2412 38.0329 28.1522 38.0329 28.0292V27.2826H37.8682V27.1093H38.0329V26.7901H38.2441V27.1093H38.5757V27.2826H38.2441Z" fill="#03045E"/>
                            <path d="M39.9843 27.8214H38.9675C38.9752 27.9464 39.0186 28.044 39.0966 28.1141C39.1747 28.1843 39.2696 28.2193 39.3808 28.2193C39.4721 28.2193 39.5481 28.198 39.6093 28.1558C39.67 28.1136 39.7134 28.0567 39.7379 27.986H39.9654C39.9312 28.108 39.8634 28.2066 39.7613 28.2834C39.6593 28.3596 39.5323 28.3977 39.3808 28.3977C39.2604 28.3977 39.1522 28.3708 39.0568 28.3169C38.9614 28.263 38.8869 28.1863 38.8329 28.0867C38.7788 27.987 38.7517 27.8722 38.7517 27.7411C38.7517 27.6099 38.7783 27.4951 38.8308 27.3965C38.8834 27.2979 38.9573 27.2216 39.0522 27.1688C39.1471 27.1159 39.2568 27.089 39.3808 27.089C39.5047 27.089 39.6083 27.1154 39.7011 27.1677C39.794 27.2201 39.8654 27.2923 39.9159 27.3838C39.9664 27.4757 39.9914 27.5794 39.9914 27.6948C39.9914 27.735 39.9889 27.7771 39.9843 27.8219V27.8214ZM39.7195 27.4437C39.6838 27.3858 39.6358 27.3421 39.5746 27.3121C39.5139 27.2821 39.4461 27.2669 39.3716 27.2669C39.265 27.2669 39.1742 27.3009 39.0987 27.3685C39.0231 27.4361 38.9808 27.5301 38.9701 27.6506H39.7731C39.7731 27.5708 39.7552 27.5017 39.7195 27.4437Z" fill="#03045E"/>
                            <path d="M40.27 27.3975C40.3225 27.2994 40.395 27.2237 40.4868 27.1698C40.5791 27.1159 40.6822 27.089 40.797 27.089C40.8959 27.089 40.9878 27.1118 41.073 27.1571C41.1582 27.2028 41.2229 27.2623 41.2683 27.3365V26.6661H41.4816V28.3769H41.2683V28.139C41.2265 28.2147 41.1648 28.2762 41.0826 28.325C41.001 28.3738 40.9051 28.3977 40.7949 28.3977C40.6847 28.3977 40.5796 28.3698 40.4873 28.3144C40.3955 28.259 40.323 28.1812 40.2705 28.0811C40.2179 27.981 40.1914 27.8666 40.1914 27.739C40.1914 27.6115 40.2179 27.4961 40.2705 27.398L40.27 27.3975ZM41.2097 27.491C41.1709 27.4198 41.1189 27.3655 41.0531 27.3278C40.9872 27.2902 40.9153 27.2714 40.8362 27.2714C40.7572 27.2714 40.6852 27.2897 40.6204 27.3268C40.5557 27.3639 40.5036 27.4178 40.4648 27.4885C40.4261 27.5591 40.4067 27.6425 40.4067 27.738C40.4067 27.8336 40.4261 27.9195 40.4648 27.9911C40.5036 28.0628 40.5551 28.1177 40.6204 28.1553C40.6857 28.1929 40.7577 28.2117 40.8362 28.2117C40.9148 28.2117 40.9878 28.1929 41.0531 28.1553C41.1184 28.1177 41.1709 28.0628 41.2097 27.9911C41.2484 27.9195 41.2678 27.8356 41.2678 27.74C41.2678 27.6445 41.2484 27.5611 41.2097 27.4905V27.491Z" fill="#03045E"/>
                    </svg>
                    <span class="content">
                        <span>Embedded System Course In Noida By The IoT Academy</span>
                        <span class="duration">1.5/3/6 months</span>
                    </span>
                </a>
                <a href="http://localhost/TIA/iot-training">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="29" viewBox="0 0 48 29" fill="none">
                            <path d="M20.3044 5.93391C20.5931 5.53086 20.8533 5.12833 21.1523 4.75578C23.1934 2.21502 25.835 0.672469 29.0587 0.173872C32.7083 -0.390797 36.0443 0.414276 38.9761 2.67143C41.4075 4.54282 42.8967 7.01039 43.4186 10.035C43.6425 11.3321 43.617 12.6337 43.3696 13.9262C43.3308 14.1285 43.3885 14.1915 43.5548 14.2657C45.2684 15.0342 46.5612 16.2438 47.3478 17.9511C48.8717 21.2572 47.6498 25.1225 44.4685 27.0386C43.9624 27.3436 43.3977 27.555 42.8518 27.7903C42.6003 27.8986 42.4391 27.8269 42.3697 27.6353C42.2978 27.4356 42.3947 27.2999 42.6646 27.2023C45.0241 26.3484 46.6198 24.7652 47.2075 22.3078C47.832 19.6969 46.7234 17.0032 44.4501 15.4835C43.9716 15.1633 43.4313 14.9336 42.913 14.6743C42.6819 14.5585 42.6197 14.4721 42.6758 14.221C43.6961 9.68075 41.7045 5.22642 37.965 2.70192C36.1749 1.4933 34.199 0.774628 32.039 0.667386C27.1742 0.425966 23.3766 2.29837 20.7615 6.43708C20.6064 6.68206 20.4722 6.72678 20.1983 6.58651C18.7535 5.84801 17.2154 5.55526 15.6053 5.68334C13.6387 5.83988 11.9128 6.5799 10.4701 7.92931C9.11613 9.19486 8.30396 10.7496 8.05449 12.5844C8.00092 12.9803 8.00603 13.3849 7.99582 13.7859C7.98715 14.1219 7.91114 14.1966 7.56882 14.2083C5.02977 14.2967 3.007 15.3427 1.61835 17.4814C0.0123727 19.9561 0.390909 23.2923 2.46674 25.3985C3.37278 26.3179 4.43901 26.9593 5.6843 27.3065C5.71389 27.3146 5.74246 27.3278 5.77256 27.3354C5.96999 27.3863 6.0608 27.5143 6.02815 27.7121C5.99856 27.8925 5.82255 27.9992 5.61135 27.9255C5.0864 27.7431 4.54461 27.5835 4.05486 27.3283C1.9474 26.23 0.573035 24.539 0.135321 22.1909C-0.323821 19.7279 0.39397 17.5887 2.1586 15.8194C3.51817 14.4558 5.19455 13.7432 7.11886 13.6096C7.25405 13.6004 7.3668 13.6055 7.37547 13.4047C7.51934 10.1046 9.08858 7.6574 11.9807 6.10875C14.5799 4.71715 17.2883 4.68259 20.0126 5.80532C20.1115 5.84598 20.2085 5.89121 20.3049 5.9334L20.3044 5.93391Z" fill="#03045E"/>
                            <path d="M10.5676 23.8793C12.3516 23.8555 14.1351 23.8351 15.9191 23.8072C17.6639 23.7802 19.4081 23.7472 21.1528 23.7152C22.7808 23.6852 24.4092 23.6547 26.0371 23.6222C27.541 23.5927 29.045 23.5622 30.5489 23.5292C31.8743 23.5002 33.2002 23.4727 34.5256 23.4351C35.6102 23.4041 36.6948 23.3619 37.7789 23.3187C38.1462 23.304 38.361 22.9533 38.2268 22.6001C37.8187 21.5261 37.408 20.4527 36.9988 19.3788C36.9662 19.2934 36.9381 19.2065 36.8943 19.0825C37.2259 19.0825 37.5319 19.0738 37.8365 19.0906C37.8875 19.0931 37.9523 19.1983 37.9763 19.268C38.2288 19.9963 38.4737 20.7272 38.7221 21.4575C38.7415 21.5144 38.767 21.5693 38.8104 21.6751C38.8609 21.5393 38.8976 21.4504 38.9283 21.3594C39.1594 20.6646 39.395 19.9709 39.6159 19.2725C39.6654 19.1165 39.7389 19.0682 39.895 19.0758C40.1419 19.088 40.3899 19.0789 40.6664 19.0789C40.6419 19.1633 40.6271 19.2268 40.6046 19.2878C40.119 20.6128 39.6348 21.9383 39.1451 23.2623C38.863 24.0257 38.4946 24.2819 37.6712 24.2738C35.8408 24.2555 34.0103 24.229 32.1804 24.2046C31.1336 24.1909 30.0867 24.1736 29.0399 24.1589C26.8916 24.1279 24.7438 24.0969 22.5956 24.0659C21.5564 24.0506 20.5172 24.0349 19.4785 24.0196C17.3226 23.9886 15.1672 23.9546 13.0112 23.9281C12.1975 23.9185 11.3833 23.9266 10.5691 23.9266L10.5681 23.8788L10.5676 23.8793Z" fill="#03045E"/>
                            <path d="M36.3111 22.751H35.3827C35.3827 22.38 35.3862 22.0257 35.3816 21.6715C35.3755 21.1932 35.3663 20.715 35.3521 20.2367C35.3454 20.0136 35.2648 19.8276 35.0301 19.7518C34.7837 19.6725 34.57 19.7437 34.3873 19.9109C34.1705 20.1096 34.1251 20.3785 34.12 20.6519C34.1093 21.2466 34.1088 21.8413 34.1057 22.4359C34.1037 22.7683 34.1057 22.7683 33.7604 22.7683C33.6206 22.7683 33.4803 22.7607 33.3415 22.7703C33.1849 22.781 33.1436 22.7124 33.1451 22.566C33.1517 22.0329 33.1502 21.4997 33.1461 20.9671C33.1446 20.7434 33.1354 20.5198 33.1206 20.2967C33.0972 19.9465 32.9829 19.7793 32.7462 19.7305C32.4498 19.6695 32.0865 19.8159 31.9922 20.1C31.9013 20.3724 31.8646 20.6707 31.8534 20.9594C31.8325 21.4916 31.8478 22.0252 31.8463 22.5584C31.8463 22.6184 31.836 22.6783 31.8279 22.7663C31.5488 22.7663 31.279 22.7719 31.0096 22.7602C30.9678 22.7581 30.8953 22.6748 30.8953 22.629C30.8917 21.4707 30.8963 20.3119 30.9004 19.1536C30.9004 19.1399 30.9127 19.1257 30.9264 19.0957H31.7779C31.7845 19.2365 31.7906 19.365 31.7988 19.5399C32.1304 19.2151 32.4727 18.992 32.9308 18.9905C33.3895 18.9894 33.7415 19.1607 33.9925 19.5572C34.1037 19.4581 34.1955 19.3671 34.2965 19.2873C34.7087 18.961 35.1715 18.9096 35.6561 19.0758C36.0418 19.2085 36.2943 19.4952 36.3045 19.8997C36.328 20.8405 36.3122 21.7818 36.3122 22.75L36.3111 22.751Z" fill="#03045E"/>
                            <path d="M8.89734 22.7683C9.12079 22.1975 9.32587 21.6751 9.52994 21.1521C9.96255 20.0466 10.3977 18.9422 10.8242 17.8347C10.8773 17.6975 10.941 17.6431 11.091 17.6512C11.3308 17.6644 11.5726 17.6436 11.8114 17.6614C11.8894 17.667 12.0042 17.7305 12.0312 17.7961C12.6893 19.4098 13.3383 21.0275 13.9877 22.6443C13.9959 22.6651 13.9953 22.6895 14.0071 22.7683C13.7581 22.7683 13.5143 22.7485 13.2755 22.7734C13.0061 22.8018 12.8653 22.7119 12.7949 22.4481C12.7317 22.2113 12.6169 21.9886 12.5343 21.7564C12.4929 21.64 12.4343 21.5831 12.3006 21.5841C11.7267 21.5902 11.1527 21.5897 10.5788 21.5841C10.4452 21.5826 10.388 21.64 10.3477 21.7589C10.2544 22.0364 10.1416 22.3073 10.0503 22.5853C10.008 22.7139 9.94775 22.7754 9.80389 22.7703C9.51871 22.7612 9.23302 22.7678 8.89734 22.7678V22.7683ZM12.1425 20.7475C11.9032 20.1071 11.6772 19.5023 11.4512 18.898C11.4333 18.9 11.4155 18.902 11.3976 18.9041C11.1762 19.5109 10.9553 20.1183 10.7263 20.7475H12.1425Z" fill="#03045E"/>
                            <path d="M33.4303 14.5153C33.4303 15.79 32.5584 16.6733 31.3004 16.6728C29.9806 16.6723 29.1557 15.8357 29.1547 14.4975C29.1541 13.2452 30.0332 12.3684 31.2912 12.3664C32.5523 12.3644 33.4303 13.2467 33.4303 14.5158V14.5153ZM32.36 14.5153C32.3008 14.284 32.2697 14.035 32.1743 13.8144C32.0141 13.4439 31.7034 13.2416 31.2963 13.2421C30.879 13.2426 30.5673 13.4546 30.4025 13.8317C30.2066 14.281 30.203 14.7506 30.3984 15.2009C30.5607 15.575 30.855 15.7951 31.2825 15.7996C31.7192 15.8042 32.0243 15.5907 32.187 15.2019C32.2743 14.9936 32.3008 14.7598 32.36 14.5148V14.5153Z" fill="#03045E"/>
                            <path d="M25.0918 22.2499C25.0076 22.3221 24.9729 22.348 24.9423 22.3785C24.2638 23.0616 23.2659 22.9828 22.7134 22.2016C22.2487 21.5454 22.2359 20.285 22.6869 19.6227C23.1664 18.9188 24.1521 18.7765 24.8117 19.3157C24.8632 19.3579 24.9153 19.4001 25.003 19.4713V17.6695H25.9621V22.7658C25.7065 22.7658 25.4601 22.7719 25.2147 22.7597C25.1719 22.7576 25.1066 22.6829 25.0979 22.6341C25.0795 22.5304 25.0923 22.4217 25.0923 22.2494L25.0918 22.2499ZM23.3256 20.8674C23.3343 20.87 23.343 20.8725 23.3516 20.8746C23.3598 21.0052 23.3608 21.1368 23.3787 21.2664C23.3924 21.3645 23.42 21.4626 23.4542 21.5561C23.5725 21.8763 23.8531 22.0817 24.1725 22.0908C24.4622 22.0989 24.7775 21.8966 24.8949 21.5932C25.0709 21.1373 25.0668 20.6687 24.8974 20.2138C24.779 19.8962 24.5286 19.7208 24.1811 19.7223C23.8337 19.7244 23.5868 19.9068 23.4659 20.2189C23.3868 20.4227 23.37 20.6499 23.3256 20.8669V20.8674Z" fill="#03045E"/>
                            <path d="M17.7062 16.5885H16.7834V11.5191H17.7231V13.3631C17.8231 13.2782 17.8771 13.2319 17.9312 13.1862C18.3807 12.8076 18.8857 12.7313 19.4326 12.9265C19.8086 13.0607 20.0647 13.3412 20.0861 13.7275C20.1397 14.6734 20.1438 15.6218 20.1682 16.5859H19.176C19.176 16.4187 19.1775 16.2662 19.176 16.1137C19.1683 15.4652 19.1688 14.8167 19.1474 14.1687C19.1352 13.8012 18.9837 13.6279 18.6852 13.5679C18.372 13.5049 17.9919 13.6548 17.8817 13.9425C17.7904 14.1814 17.744 14.4487 17.7348 14.7059C17.7134 15.3077 17.7261 15.911 17.7246 16.5137C17.7246 16.5285 17.7174 16.5437 17.7067 16.5879L17.7062 16.5885Z" fill="#03045E"/>
                            <path d="M21.8084 22.7673C21.4865 22.7673 21.2238 22.7734 20.9615 22.7622C20.9105 22.7602 20.8457 22.7002 20.8161 22.6504C20.7707 22.5736 20.7503 22.4822 20.7116 22.3754C20.6335 22.4349 20.5748 22.4806 20.5146 22.5249C20.0871 22.8425 19.6101 22.9127 19.1041 22.781C18.6597 22.6656 18.3679 22.3038 18.3414 21.8672C18.3087 21.332 18.5153 20.9564 18.9959 20.7917C19.3739 20.6621 19.7734 20.5935 20.1631 20.4974C20.2305 20.4807 20.3004 20.4695 20.3647 20.4441C20.4764 20.3998 20.6478 20.4283 20.6417 20.2311C20.635 20.0253 20.5784 19.8499 20.3611 19.7716C19.9035 19.6065 19.5622 19.7325 19.2964 20.1788C19.1602 20.1544 19.0173 20.1284 18.8745 20.103C18.7383 20.0786 18.6021 20.0547 18.4398 20.0263C18.5607 19.5887 18.7842 19.2507 19.2168 19.1104C19.7759 18.929 20.3499 18.9366 20.91 19.1028C21.3692 19.239 21.5676 19.5475 21.5865 20.0944C21.5982 20.4415 21.586 20.7892 21.5895 21.1363C21.5952 21.6725 21.5283 22.2169 21.8084 22.7678V22.7673ZM20.6177 20.9889C20.2677 21.0799 19.9254 21.1465 19.5994 21.2593C19.2724 21.3726 19.2046 21.7416 19.4372 21.9947C19.5928 22.164 19.8693 22.2133 20.1269 22.1177C20.5636 21.9561 20.7044 21.6491 20.6177 20.9889Z" fill="#03045E"/>
                            <path d="M27.7022 21.1922C27.6747 21.644 27.9083 22.0029 28.269 22.0994C28.6629 22.2052 28.8827 22.0822 29.145 21.6024C29.4434 21.6532 29.7454 21.705 30.0821 21.763C29.9265 22.1935 29.6771 22.506 29.2776 22.6885C28.0104 23.2669 26.7146 22.4791 26.6942 21.0972C26.6886 20.7277 26.7401 20.3358 26.8656 19.9902C27.1278 19.268 27.8099 18.9183 28.6476 19.0006C29.3587 19.0708 29.9056 19.5699 30.0597 20.3058C30.1051 20.5239 30.1163 20.749 30.1464 20.9701C30.1699 21.1434 30.0985 21.1978 29.9245 21.1953C29.273 21.1866 28.6215 21.1917 27.9701 21.1917C27.8859 21.1917 27.8022 21.1917 27.7022 21.1917V21.1922ZM27.714 20.5823H29.1674C29.1368 20.0075 28.8246 19.6827 28.367 19.7239C27.9578 19.761 27.6681 20.1366 27.714 20.5823Z" fill="#03045E"/>
                            <path d="M24.3602 15.0393H21.8977C21.8385 15.4327 22.0727 15.8179 22.4226 15.9282C22.82 16.0533 23.0797 15.9186 23.3297 15.4444C23.6261 15.4932 23.9281 15.543 24.2684 15.5989C24.1271 15.9963 23.9092 16.2881 23.5618 16.4756C22.3068 17.1541 20.9172 16.3562 20.8666 14.9265C20.8473 14.3831 20.9223 13.8571 21.2829 13.42C21.7482 12.8563 22.3645 12.7278 23.0481 12.8818C23.7052 13.0297 24.0863 13.4734 24.2347 14.1107C24.3026 14.4009 24.3163 14.7033 24.3597 15.0393H24.3602ZM21.9053 14.4502C22.3481 14.4502 22.7956 14.4538 23.243 14.4446C23.2833 14.4436 23.3542 14.3638 23.3557 14.3186C23.369 13.8927 22.9807 13.5257 22.5639 13.5593C22.1431 13.5933 21.837 13.9923 21.9053 14.4502Z" fill="#03045E"/>
                            <path d="M14.5667 16.5864H13.5515V12.3623H12.0353V11.5201H16.0783V12.3456H14.5667V16.5864Z" fill="#03045E"/>
                            <path d="M35.9515 16.5839H34.9245V12.3572H33.4068C33.4068 12.0955 33.3976 11.852 33.4155 11.6106C33.4186 11.5694 33.5349 11.5064 33.6002 11.5054C34.0348 11.4958 34.4695 11.5003 34.9041 11.5003C35.6648 11.5003 36.4254 11.5003 37.1861 11.5003H37.4559V12.3476H35.952V16.5844L35.9515 16.5839Z" fill="#03045E"/>
                            <path d="M16.9338 21.3594C17.2486 21.4143 17.5506 21.4662 17.8705 21.5221C17.7251 22.1177 17.4348 22.5584 16.84 22.7464C15.9885 23.0148 15.1233 22.7642 14.6968 22.1269C14.2703 21.489 14.2581 20.3277 14.7065 19.7056C14.855 19.4997 15.0641 19.3081 15.2886 19.1922C15.8477 18.903 16.4426 18.9163 17.0211 19.147C17.4557 19.3203 17.6802 19.6873 17.822 20.1472C17.5119 20.2057 17.2109 20.2621 16.8951 20.3216C16.8767 20.2804 16.8583 20.2418 16.8425 20.2021C16.7216 19.8921 16.5043 19.7274 16.1931 19.7376C15.8768 19.7478 15.6059 19.8723 15.4927 20.1828C15.3182 20.6626 15.3274 21.1556 15.511 21.6308C15.6263 21.9292 15.9248 22.0776 16.2676 22.0583C16.564 22.041 16.7553 21.8834 16.868 21.5632C16.8884 21.5058 16.9063 21.4468 16.9344 21.3599L16.9338 21.3594Z" fill="#03045E"/>
                            <path d="M27.1166 14.0279C27.1166 13.2635 27.1217 12.4985 27.113 11.7341C27.111 11.5522 27.1666 11.4841 27.3513 11.4973C27.5441 11.511 27.7395 11.5085 27.9328 11.4978C28.0961 11.4886 28.1481 11.5501 28.1466 11.7113C28.14 12.4528 28.1435 13.1943 28.1435 13.9359C28.1435 14.747 28.1389 15.5577 28.1471 16.3689C28.1491 16.5508 28.0935 16.6189 27.9089 16.6057C27.716 16.592 27.5206 16.5945 27.3273 16.6052C27.164 16.6144 27.112 16.5529 27.1135 16.3918C27.1196 15.604 27.1166 14.8162 27.1166 14.0284V14.0279Z" fill="#03045E"/>
                            <path d="M7.26688 27.1454C7.3383 27.0198 7.43523 26.9212 7.55818 26.8506C7.68112 26.7799 7.81734 26.7444 7.96783 26.7444C8.14435 26.7444 8.29842 26.7866 8.42953 26.8714C8.56115 26.9563 8.65706 27.0763 8.71725 27.2323H8.46422C8.41932 27.1352 8.35453 27.0605 8.27036 27.0082C8.18618 26.9558 8.08517 26.9299 7.96732 26.9299C7.84948 26.9299 7.75306 26.9563 7.66327 27.0082C7.57348 27.0605 7.50308 27.1352 7.45207 27.2313C7.40105 27.3278 7.37503 27.4407 7.37503 27.5698C7.37503 27.6989 7.40054 27.8097 7.45207 27.9062C7.50308 28.0028 7.57348 28.077 7.66327 28.1294C7.75306 28.1817 7.85458 28.2076 7.96732 28.2076C8.08007 28.2076 8.18567 28.1817 8.27036 28.1304C8.35504 28.079 8.41932 28.0043 8.46422 27.9073H8.71725C8.65706 28.0613 8.56115 28.1807 8.42953 28.2646C8.29791 28.3484 8.14435 28.3906 7.96783 28.3906C7.81785 28.3906 7.68112 28.3555 7.55818 28.2854C7.43523 28.2153 7.3383 28.1177 7.26688 27.9926C7.19597 27.8676 7.16026 27.7268 7.16026 27.5698C7.16026 27.4127 7.19597 27.2714 7.26688 27.1454Z" fill="#03045E"/>
                            <path d="M9.2621 28.3164C9.16517 28.2625 9.08916 28.1858 9.03457 28.0862C8.97947 27.9865 8.95244 27.8717 8.95244 27.7405C8.95244 27.6094 8.9805 27.4966 9.03712 27.3975C9.09375 27.2979 9.17078 27.2216 9.26822 27.1688C9.36566 27.1154 9.47484 27.089 9.59523 27.089C9.71563 27.089 9.82532 27.1154 9.92276 27.1688C10.0202 27.2221 10.0972 27.2979 10.1539 27.3965C10.2105 27.4951 10.2385 27.6099 10.2385 27.7411C10.2385 27.8722 10.2095 27.987 10.1513 28.0867C10.0931 28.1858 10.0146 28.263 9.91561 28.3169C9.81664 28.3708 9.70645 28.3977 9.58605 28.3977C9.46566 28.3977 9.35903 28.3708 9.26261 28.3169L9.2621 28.3164ZM9.79879 28.1588C9.86511 28.1233 9.91919 28.0704 9.96 27.9992C10.0008 27.9286 10.0212 27.8422 10.0212 27.74C10.0212 27.6379 10.0013 27.552 9.96102 27.4813C9.92072 27.4107 9.86817 27.3578 9.80338 27.3228C9.73859 27.2882 9.66768 27.2709 9.59217 27.2709C9.51667 27.2709 9.44423 27.2882 9.37995 27.3228C9.31567 27.3573 9.26414 27.4102 9.22537 27.4813C9.1866 27.552 9.16721 27.6384 9.16721 27.74C9.16721 27.8417 9.1866 27.9306 9.22435 28.0013C9.26261 28.0719 9.31312 28.1248 9.37638 28.1599C9.43964 28.1944 9.50953 28.2117 9.58503 28.2117C9.66054 28.2117 9.73196 28.1939 9.79879 28.1583V28.1588Z" fill="#03045E"/>
                            <path d="M11.5114 27.2262C11.6073 27.3197 11.6553 27.4539 11.6553 27.6297V28.3764H11.4461V27.6597C11.4461 27.5332 11.4145 27.4366 11.3507 27.3695C11.2874 27.3024 11.2007 27.2689 11.091 27.2689C10.9813 27.2689 10.891 27.3035 10.8252 27.3731C10.7594 27.4422 10.7268 27.5433 10.7268 27.676V28.3764H10.5156V27.1093H10.7268V27.2897C10.7686 27.2252 10.8252 27.1749 10.8977 27.1393C10.9696 27.1037 11.0487 27.0859 11.1354 27.0859C11.2905 27.0859 11.4155 27.1327 11.5114 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M12.997 27.2262C13.0929 27.3197 13.1408 27.4539 13.1408 27.6297V28.3764H12.9317V27.6597C12.9317 27.5332 12.9 27.4366 12.8363 27.3695C12.773 27.3024 12.6863 27.2689 12.5766 27.2689C12.4669 27.2689 12.3766 27.3035 12.3108 27.3731C12.245 27.4422 12.2123 27.5433 12.2123 27.676V28.3764H12.0011V27.1093H12.2123V27.2897C12.2542 27.2252 12.3108 27.1749 12.3832 27.1393C12.4552 27.1037 12.5343 27.0859 12.621 27.0859C12.7761 27.0859 12.9011 27.1327 12.997 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M14.6397 27.8214H13.6229C13.6306 27.9464 13.6739 28.044 13.752 28.1141C13.8301 28.1843 13.9249 28.2193 14.0362 28.2193C14.1275 28.2193 14.2035 28.198 14.2647 28.1558C14.3254 28.1136 14.3688 28.0567 14.3933 27.986H14.6208C14.5866 28.108 14.5188 28.2066 14.4167 28.2834C14.3147 28.3596 14.1877 28.3977 14.0362 28.3977C13.9158 28.3977 13.8076 28.3708 13.7122 28.3169C13.6168 28.263 13.5423 28.1863 13.4882 28.0867C13.4342 27.987 13.4071 27.8722 13.4071 27.7411C13.4071 27.6099 13.4337 27.4951 13.4862 27.3965C13.5388 27.2979 13.6127 27.2216 13.7076 27.1688C13.8025 27.1159 13.9122 27.089 14.0362 27.089C14.1601 27.089 14.2637 27.1154 14.3565 27.1677C14.4494 27.2201 14.5208 27.2923 14.5713 27.3838C14.6218 27.4757 14.6468 27.5794 14.6468 27.6948C14.6468 27.735 14.6443 27.7771 14.6397 27.8219V27.8214ZM14.3754 27.4437C14.3397 27.3858 14.2917 27.3421 14.2305 27.3121C14.1698 27.2821 14.102 27.2669 14.0275 27.2669C13.9209 27.2669 13.83 27.3009 13.7545 27.3685C13.679 27.4361 13.6367 27.5301 13.626 27.6506H14.429C14.429 27.5708 14.4111 27.5017 14.3754 27.4437Z" fill="#03045E"/>
                            <path d="M14.9254 27.3975C14.9779 27.2994 15.0509 27.2237 15.1447 27.1698C15.2381 27.1159 15.3452 27.089 15.4661 27.089C15.6222 27.089 15.7513 27.1271 15.8523 27.2023C15.9538 27.278 16.0207 27.3827 16.0533 27.5164H15.8258C15.8044 27.4392 15.762 27.3787 15.6993 27.3339C15.6365 27.2892 15.559 27.2669 15.4661 27.2669C15.3457 27.2669 15.2483 27.308 15.1738 27.3904C15.0993 27.4727 15.0621 27.5896 15.0621 27.7405C15.0621 27.8915 15.0993 28.0109 15.1738 28.0943C15.2483 28.1776 15.3457 28.2193 15.4661 28.2193C15.559 28.2193 15.6365 28.198 15.6982 28.1548C15.76 28.1116 15.8023 28.0501 15.8258 27.9698H16.0533C16.0191 28.0994 15.9513 28.2031 15.8493 28.2808C15.7472 28.3586 15.6192 28.3977 15.4661 28.3977C15.3457 28.3977 15.2381 28.3708 15.1447 28.3169C15.0509 28.263 14.9779 28.1868 14.9254 28.0882C14.8728 27.9896 14.8463 27.8737 14.8463 27.7416C14.8463 27.6094 14.8728 27.4961 14.9254 27.3985V27.3975Z" fill="#03045E"/>
                            <path d="M16.5915 27.2826V28.0292C16.5915 28.0907 16.6048 28.1344 16.6308 28.1599C16.6573 28.1853 16.7027 28.198 16.768 28.198H16.9236V28.3759H16.7334C16.6155 28.3759 16.5278 28.3489 16.4686 28.2951C16.4099 28.2412 16.3803 28.1522 16.3803 28.0292V27.2826H16.2155V27.1093H16.3803V26.7901H16.5915V27.1093H16.9231V27.2826H16.5915Z" fill="#03045E"/>
                            <path d="M17.1859 26.8618C17.1583 26.8338 17.144 26.8003 17.144 26.7601C17.144 26.72 17.1583 26.6859 17.1859 26.6585C17.2139 26.6305 17.2476 26.6168 17.2879 26.6168C17.3282 26.6168 17.3593 26.6305 17.3864 26.6585C17.4134 26.6864 17.4272 26.72 17.4272 26.7601C17.4272 26.8003 17.4134 26.8343 17.3864 26.8618C17.3593 26.8897 17.3267 26.9035 17.2879 26.9035C17.2491 26.9035 17.2134 26.8897 17.1859 26.8618ZM17.3899 27.1093V28.3764H17.1787V27.1093H17.3899Z" fill="#03045E"/>
                            <path d="M18.7454 27.2262C18.8413 27.3197 18.8893 27.4539 18.8893 27.6297V28.3764H18.6801V27.6597C18.6801 27.5332 18.6485 27.4366 18.5847 27.3695C18.5215 27.3024 18.4347 27.2689 18.325 27.2689C18.2154 27.2689 18.1251 27.3035 18.0593 27.3731C17.9934 27.4422 17.9608 27.5433 17.9608 27.676V28.3764H17.7496V27.1093H17.9608V27.2897C18.0026 27.2252 18.0593 27.1749 18.1317 27.1393C18.2036 27.1037 18.2827 27.0859 18.3694 27.0859C18.5245 27.0859 18.6495 27.1327 18.7454 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M20.0484 27.1601C20.131 27.2079 20.1927 27.2679 20.233 27.3406V27.1093H20.4463V28.4038C20.4463 28.5197 20.4218 28.6224 20.3718 28.7123C20.3223 28.8023 20.2519 28.8729 20.1596 28.9238C20.0677 28.9746 19.9606 29 19.8382 29C19.6708 29 19.5316 28.9609 19.4204 28.8821C19.3091 28.8033 19.2433 28.6961 19.2234 28.5609H19.4321C19.455 28.6381 19.5035 28.6996 19.5759 28.7469C19.6489 28.7942 19.7361 28.8175 19.8382 28.8175C19.9545 28.8175 20.0489 28.7814 20.1223 28.7088C20.1958 28.6361 20.2325 28.5344 20.2325 28.4038V28.138C20.1907 28.2117 20.129 28.2737 20.0468 28.323C19.9652 28.3723 19.8693 28.3972 19.7591 28.3972C19.6489 28.3972 19.5438 28.3693 19.4515 28.3139C19.3596 28.2585 19.2872 28.1807 19.2347 28.0806C19.1821 27.9804 19.1556 27.8661 19.1556 27.7385C19.1556 27.6109 19.1821 27.4956 19.2347 27.3975C19.2872 27.2994 19.3596 27.2237 19.4515 27.1698C19.5438 27.1159 19.6464 27.089 19.7591 27.089C19.8718 27.089 19.9652 27.1129 20.0484 27.1606V27.1601ZM20.1749 27.491C20.1361 27.4198 20.0841 27.3655 20.0183 27.3278C19.9524 27.2902 19.8805 27.2714 19.8014 27.2714C19.7224 27.2714 19.6504 27.2897 19.5856 27.3268C19.5209 27.3639 19.4688 27.4178 19.43 27.4885C19.3913 27.5591 19.3719 27.6425 19.3719 27.738C19.3719 27.8336 19.3913 27.9195 19.43 27.9911C19.4688 28.0628 19.5203 28.1177 19.5856 28.1553C19.6509 28.1929 19.7229 28.2117 19.8014 28.2117C19.88 28.2117 19.953 28.1929 20.0183 28.1553C20.0836 28.1177 20.1361 28.0628 20.1749 27.9911C20.2136 27.9195 20.233 27.8356 20.233 27.74C20.233 27.6445 20.2136 27.5611 20.1749 27.4905V27.491Z" fill="#03045E"/>
                            <path d="M22.4191 26.7647V26.936H21.9783V28.3764H21.7671V26.936H21.3238V26.7647H22.4191Z" fill="#03045E"/>
                            <path d="M23.57 27.1474C23.6475 27.1881 23.7077 27.2496 23.7521 27.3314C23.7965 27.4132 23.8184 27.5123 23.8184 27.6297V28.3764H23.6093V27.6597C23.6093 27.5332 23.5776 27.4366 23.5139 27.3695C23.4506 27.3024 23.3639 27.2689 23.2542 27.2689C23.1445 27.2689 23.0542 27.3035 22.9884 27.3731C22.9226 27.4422 22.8899 27.5433 22.8899 27.676V28.3764H22.6787V26.6656H22.8899V27.2897C22.9318 27.2252 22.9894 27.1749 23.0629 27.1393C23.1363 27.1037 23.219 27.0859 23.3098 27.0859C23.4057 27.0859 23.4924 27.1062 23.57 27.1469V27.1474Z" fill="#03045E"/>
                            <path d="M25.3178 27.8214H24.301C24.3087 27.9464 24.352 28.044 24.4301 28.1141C24.5081 28.1843 24.603 28.2193 24.7143 28.2193C24.8056 28.2193 24.8816 28.198 24.9428 28.1558C25.0035 28.1136 25.0469 28.0567 25.0714 27.986H25.2989C25.2647 28.108 25.1969 28.2066 25.0948 28.2834C24.9928 28.3596 24.8658 28.3977 24.7143 28.3977C24.5939 28.3977 24.4857 28.3708 24.3903 28.3169C24.2949 28.263 24.2204 28.1863 24.1663 28.0867C24.1123 27.987 24.0852 27.8722 24.0852 27.7411C24.0852 27.6099 24.1118 27.4951 24.1643 27.3965C24.2169 27.2979 24.2908 27.2216 24.3857 27.1688C24.4806 27.1159 24.5903 27.089 24.7143 27.089C24.8382 27.089 24.9418 27.1154 25.0346 27.1677C25.1275 27.2201 25.1989 27.2923 25.2494 27.3838C25.2999 27.4757 25.3249 27.5794 25.3249 27.6948C25.3249 27.735 25.3224 27.7771 25.3178 27.8219V27.8214ZM25.053 27.4437C25.0173 27.3858 24.9693 27.3421 24.9081 27.3121C24.8474 27.2821 24.7796 27.2669 24.7051 27.2669C24.5984 27.2669 24.5076 27.3009 24.4321 27.3685C24.3566 27.4361 24.3143 27.5301 24.3036 27.6506H25.1066C25.1066 27.5708 25.0887 27.5017 25.053 27.4437Z" fill="#03045E"/>
                            <path d="M26.4289 26.7647V27.7843C26.4289 27.9276 26.4641 28.0338 26.5345 28.1034C26.6049 28.1726 26.7028 28.2076 26.8283 28.2076C26.9538 28.2076 27.0492 28.1731 27.1196 28.1034C27.19 28.0343 27.2252 27.9276 27.2252 27.7843V26.7647H27.4365V27.7822C27.4365 27.9164 27.4094 28.0292 27.3553 28.1207C27.3013 28.2122 27.2283 28.2808 27.136 28.3255C27.0441 28.3703 26.9406 28.3926 26.8263 28.3926C26.712 28.3926 26.6085 28.3703 26.5161 28.3255C26.4243 28.2808 26.3513 28.2127 26.2983 28.1207C26.2452 28.0287 26.2182 27.9159 26.2182 27.7822V26.7647H26.4294H26.4289Z" fill="#03045E"/>
                            <path d="M28.7843 27.2262C28.8802 27.3197 28.9282 27.4539 28.9282 27.6297V28.3764H28.719V27.6597C28.719 27.5332 28.6874 27.4366 28.6236 27.3695C28.5603 27.3024 28.4736 27.2689 28.3639 27.2689C28.2542 27.2689 28.1639 27.3035 28.0981 27.3731C28.0323 27.4422 27.9997 27.5433 27.9997 27.676V28.3764H27.7885V27.1093H27.9997V27.2897C28.0415 27.2252 28.0981 27.1749 28.1706 27.1393C28.2425 27.1037 28.3216 27.0859 28.4083 27.0859C28.5634 27.0859 28.6884 27.1327 28.7843 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M29.274 27.3975C29.3266 27.2994 29.3995 27.2237 29.4934 27.1698C29.5868 27.1159 29.6939 27.089 29.8148 27.089C29.9709 27.089 30.1 27.1271 30.201 27.2023C30.3025 27.278 30.3693 27.3827 30.402 27.5164H30.1745C30.153 27.4392 30.1107 27.3787 30.0479 27.3339C29.9852 27.2892 29.9077 27.2669 29.8148 27.2669C29.6944 27.2669 29.597 27.308 29.5225 27.3904C29.448 27.4727 29.4108 27.5896 29.4108 27.7405C29.4108 27.8915 29.448 28.0109 29.5225 28.0943C29.597 28.1776 29.6944 28.2193 29.8148 28.2193C29.9077 28.2193 29.9852 28.198 30.0469 28.1548C30.1087 28.1116 30.151 28.0501 30.1745 27.9698H30.402C30.3678 28.0994 30.3 28.2031 30.1979 28.2808C30.0959 28.3586 29.9679 28.3977 29.8148 28.3977C29.6944 28.3977 29.5868 28.3708 29.4934 28.3169C29.3995 28.263 29.3266 28.1868 29.274 28.0882C29.2215 27.9896 29.195 27.8737 29.195 27.7416C29.195 27.6094 29.2215 27.4961 29.274 27.3985V27.3975Z" fill="#03045E"/>
                            <path d="M30.9137 28.3164C30.8168 28.2625 30.7407 28.1858 30.6862 28.0862C30.6311 27.9865 30.604 27.8717 30.604 27.7405C30.604 27.6094 30.6321 27.4966 30.6887 27.3975C30.7453 27.2979 30.8224 27.2216 30.9198 27.1688C31.0172 27.1159 31.1264 27.089 31.2468 27.089C31.3672 27.089 31.4769 27.1154 31.5743 27.1688C31.6718 27.2221 31.7488 27.2979 31.8054 27.3965C31.8621 27.4951 31.8901 27.6099 31.8901 27.7411C31.8901 27.8722 31.861 27.987 31.8029 28.0867C31.7447 28.1858 31.6662 28.263 31.5672 28.3169C31.4682 28.3708 31.358 28.3977 31.2376 28.3977C31.1172 28.3977 31.0106 28.3708 30.9142 28.3169L30.9137 28.3164ZM31.4509 28.1588C31.5172 28.1233 31.5713 28.0704 31.6121 27.9992C31.6529 27.9286 31.6733 27.8422 31.6733 27.74C31.6733 27.6379 31.6534 27.552 31.6131 27.4813C31.5728 27.4107 31.5203 27.3578 31.4555 27.3228C31.3907 27.2882 31.3198 27.2709 31.2443 27.2709C31.1688 27.2709 31.0963 27.2882 31.032 27.3228C30.9678 27.3573 30.9162 27.4102 30.8775 27.4813C30.8387 27.552 30.8193 27.6384 30.8193 27.74C30.8193 27.8417 30.8387 27.9306 30.8764 28.0013C30.9147 28.0719 30.9652 28.1248 31.0285 28.1599C31.0917 28.1944 31.1616 28.2117 31.2371 28.2117C31.3126 28.2117 31.384 28.1939 31.4509 28.1583V28.1588Z" fill="#03045E"/>
                            <path d="M33.1635 27.2262C33.2594 27.3197 33.3073 27.4539 33.3073 27.6297V28.3764H33.0982V27.6597C33.0982 27.5332 33.0665 27.4366 33.0028 27.3695C32.9395 27.3024 32.8528 27.2689 32.7431 27.2689C32.6334 27.2689 32.5431 27.3035 32.4773 27.3731C32.4115 27.4422 32.3789 27.5433 32.3789 27.676V28.3764H32.1676V27.1093H32.3789V27.2897C32.4207 27.2252 32.4773 27.1749 32.5498 27.1393C32.6217 27.1037 32.7008 27.0859 32.7875 27.0859C32.9426 27.0859 33.0676 27.1327 33.1635 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M34.6491 27.2262C34.745 27.3197 34.7929 27.4539 34.7929 27.6297V28.3764H34.5838V27.6597C34.5838 27.5332 34.5521 27.4366 34.4884 27.3695C34.4251 27.3024 34.3384 27.2689 34.2287 27.2689C34.119 27.2689 34.0287 27.3035 33.9629 27.3731C33.8971 27.4422 33.8644 27.5433 33.8644 27.676V28.3764H33.6532V27.1093H33.8644V27.2897C33.9063 27.2252 33.9629 27.1749 34.0353 27.1393C34.1073 27.1037 34.1863 27.0859 34.2731 27.0859C34.4282 27.0859 34.5531 27.1327 34.6491 27.2257V27.2262Z" fill="#03045E"/>
                            <path d="M36.2923 27.8214H35.2755C35.2832 27.9464 35.3265 28.044 35.4046 28.1141C35.4827 28.1843 35.5775 28.2193 35.6888 28.2193C35.7801 28.2193 35.8561 28.198 35.9173 28.1558C35.978 28.1136 36.0214 28.0567 36.0459 27.986H36.2734C36.2392 28.108 36.1714 28.2066 36.0693 28.2834C35.9673 28.3596 35.8403 28.3977 35.6888 28.3977C35.5684 28.3977 35.4602 28.3708 35.3648 28.3169C35.2694 28.263 35.1949 28.1863 35.1408 28.0867C35.0868 27.987 35.0597 27.8722 35.0597 27.7411C35.0597 27.6099 35.0863 27.4951 35.1388 27.3965C35.1914 27.2979 35.2653 27.2216 35.3602 27.1688C35.4551 27.1159 35.5648 27.089 35.6888 27.089C35.8127 27.089 35.9163 27.1154 36.0091 27.1677C36.102 27.2201 36.1734 27.2923 36.2239 27.3838C36.2744 27.4757 36.2994 27.5794 36.2994 27.6948C36.2994 27.735 36.2969 27.7771 36.2923 27.8219V27.8214ZM36.0275 27.4437C35.9918 27.3858 35.9438 27.3421 35.8826 27.3121C35.8219 27.2821 35.7541 27.2669 35.6796 27.2669C35.573 27.2669 35.4821 27.3009 35.4066 27.3685C35.3311 27.4361 35.2888 27.5301 35.2781 27.6506H36.0811C36.0811 27.5708 36.0632 27.5017 36.0275 27.4437Z" fill="#03045E"/>
                            <path d="M36.5774 27.3975C36.63 27.2994 36.7029 27.2237 36.7968 27.1698C36.8902 27.1159 36.9973 27.089 37.1182 27.089C37.2743 27.089 37.4034 27.1271 37.5044 27.2023C37.6059 27.278 37.6728 27.3827 37.7054 27.5164H37.4779C37.4564 27.4392 37.4141 27.3787 37.3514 27.3339C37.2886 27.2892 37.2111 27.2669 37.1182 27.2669C36.9978 27.2669 36.9004 27.308 36.8259 27.3904C36.7514 27.4727 36.7142 27.5896 36.7142 27.7405C36.7142 27.8915 36.7514 28.0109 36.8259 28.0943C36.9004 28.1776 36.9978 28.2193 37.1182 28.2193C37.2111 28.2193 37.2886 28.198 37.3503 28.1548C37.4121 28.1116 37.4544 28.0501 37.4779 27.9698H37.7054C37.6712 28.0994 37.6034 28.2031 37.5013 28.2808C37.3993 28.3586 37.2713 28.3977 37.1182 28.3977C36.9978 28.3977 36.8902 28.3708 36.7968 28.3169C36.7029 28.263 36.63 28.1868 36.5774 28.0882C36.5249 27.9896 36.4984 27.8737 36.4984 27.7416C36.4984 27.6094 36.5249 27.4961 36.5774 27.3985V27.3975Z" fill="#03045E"/>
                            <path d="M38.2441 27.2826V28.0292C38.2441 28.0907 38.2574 28.1344 38.2834 28.1599C38.3099 28.1853 38.3554 28.198 38.4207 28.198H38.5762V28.3759H38.386C38.2681 28.3759 38.1804 28.3489 38.1212 28.2951C38.0625 28.2412 38.0329 28.1522 38.0329 28.0292V27.2826H37.8682V27.1093H38.0329V26.7901H38.2441V27.1093H38.5757V27.2826H38.2441Z" fill="#03045E"/>
                            <path d="M39.9843 27.8214H38.9675C38.9752 27.9464 39.0186 28.044 39.0966 28.1141C39.1747 28.1843 39.2696 28.2193 39.3808 28.2193C39.4721 28.2193 39.5481 28.198 39.6093 28.1558C39.67 28.1136 39.7134 28.0567 39.7379 27.986H39.9654C39.9312 28.108 39.8634 28.2066 39.7613 28.2834C39.6593 28.3596 39.5323 28.3977 39.3808 28.3977C39.2604 28.3977 39.1522 28.3708 39.0568 28.3169C38.9614 28.263 38.8869 28.1863 38.8329 28.0867C38.7788 27.987 38.7517 27.8722 38.7517 27.7411C38.7517 27.6099 38.7783 27.4951 38.8308 27.3965C38.8834 27.2979 38.9573 27.2216 39.0522 27.1688C39.1471 27.1159 39.2568 27.089 39.3808 27.089C39.5047 27.089 39.6083 27.1154 39.7011 27.1677C39.794 27.2201 39.8654 27.2923 39.9159 27.3838C39.9664 27.4757 39.9914 27.5794 39.9914 27.6948C39.9914 27.735 39.9889 27.7771 39.9843 27.8219V27.8214ZM39.7195 27.4437C39.6838 27.3858 39.6358 27.3421 39.5746 27.3121C39.5139 27.2821 39.4461 27.2669 39.3716 27.2669C39.265 27.2669 39.1742 27.3009 39.0987 27.3685C39.0231 27.4361 38.9808 27.5301 38.9701 27.6506H39.7731C39.7731 27.5708 39.7552 27.5017 39.7195 27.4437Z" fill="#03045E"/>
                            <path d="M40.27 27.3975C40.3225 27.2994 40.395 27.2237 40.4868 27.1698C40.5791 27.1159 40.6822 27.089 40.797 27.089C40.8959 27.089 40.9878 27.1118 41.073 27.1571C41.1582 27.2028 41.2229 27.2623 41.2683 27.3365V26.6661H41.4816V28.3769H41.2683V28.139C41.2265 28.2147 41.1648 28.2762 41.0826 28.325C41.001 28.3738 40.9051 28.3977 40.7949 28.3977C40.6847 28.3977 40.5796 28.3698 40.4873 28.3144C40.3955 28.259 40.323 28.1812 40.2705 28.0811C40.2179 27.981 40.1914 27.8666 40.1914 27.739C40.1914 27.6115 40.2179 27.4961 40.2705 27.398L40.27 27.3975ZM41.2097 27.491C41.1709 27.4198 41.1189 27.3655 41.0531 27.3278C40.9872 27.2902 40.9153 27.2714 40.8362 27.2714C40.7572 27.2714 40.6852 27.2897 40.6204 27.3268C40.5557 27.3639 40.5036 27.4178 40.4648 27.4885C40.4261 27.5591 40.4067 27.6425 40.4067 27.738C40.4067 27.8336 40.4261 27.9195 40.4648 27.9911C40.5036 28.0628 40.5551 28.1177 40.6204 28.1553C40.6857 28.1929 40.7577 28.2117 40.8362 28.2117C40.9148 28.2117 40.9878 28.1929 41.0531 28.1553C41.1184 28.1177 41.1709 28.0628 41.2097 27.9911C41.2484 27.9195 41.2678 27.8356 41.2678 27.74C41.2678 27.6445 41.2484 27.5611 41.2097 27.4905V27.491Z" fill="#03045E"/>
                    </svg>
                    <span class="content">
                        <span>Internet Of Things Course In Noida By TheIoT Academy</span>
                        <span class="duration">1.5/3/6 months</span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</section> -->

<!-- new megamenu for desktop and script is written for desktop only in the same file-->
<!-- Do not remove any code as it is dynamic and controlled with js -->

<section class="new-megamenu-desktop d-none d-lg-block">
    <div class="container-fluid">
        <div class="megamenu-grid">

            <!-- Navigation Starts -->
            <div class="megamenu-navigation">
                <div class="navigation-header sticky-top  d-flex justify-content-center align-items-center px-3"
                    style="min-height: 70px; gap:20px">
                    <h5 class=" m-0" style="color: #FFF;
                        font-family: Inter;
                        font-size: 18px;
                        font-style: normal;
                        font-weight: 700;
                        line-height: normal;">
                        E&ICT Academy, IIT Roorkee Programs</h5>

                    <!-- <img src="http://localhost/TIA/assets/dit/images/navbar/Eict-IITR-logo-white.webp" class="img-fluid"
                        style="max-width:93.8px" /> -->
                </div>

                <div class="primary-navigation">
                    <ul class="list-unstyled m-0">
                    </ul>
                </div>

                <div class="secondary-navigation">
                    <ul class="list-unstyled m-0">
                    </ul>
                </div>

            </div>
            <!-- Navigation Ends -->

            <!-- Megamenu Content Starts -->
            <div class="megamenu-content-container position-relative ">
                <div class="megamenu-content">
                </div>
                <div class="megamenu-banner position-absolute  d-flex ">
                    <img class="w-100 " loading="lazy"  src="http://localhost/TIA/assets/dit/images/navbar/new-megamenu-banner-secondary02.webp" />
                </div>
            </div>
            <!-- Megamenu Content Ends -->

            <!-- Megamenu Banner Starts -->
            <!-- <div class="megamenu-banner">
               
                <img class="img-fluid" src="http://localhost/TIA/assets/dit/images/navbar/new-megamenu-banner-secondary.webp" />
            </div> -->
            <!-- Megamenu Banner Ends -->

        </div>
    </div>
</section>
<div class="megamenu-backdrop" id="megamenu-backdrop"></div>

<!-- This Script is Only for Desktop Mega Menu -->
<script defer src="http://localhost/TIA/assets/dit/js/new-megamenu.js"></script><!-- new megamenu for desktop end-->

<!-- new Mobile MENU -->

<!-- new Mobile Menu Ends -->

<div class="zeynep-overlay"></div>
<div class="zeynep-overlay2"></div>

<div class="zeynep" id="zeynep">
    <div class="menu-header d-flex justify-content-end align-items-center">

        <img loading="lazy" class="logo img-fluid" src="http://localhost/TIA/assets/dit/images/navbar/logo.svg"
            alt="iotacademy" width="109" height="67" />
        <!--        <svg class="logo img-fluid" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 122 75">-->
        <!--  <defs>-->
        <!--    <style>-->
        <!--      .cls-1 {-->
        <!--        fill: #03045e;-->
        <!--        stroke-width: 0px;-->
        <!--      }-->
        <!--    </style>-->
        <!--  </defs>-->
        <!--  <path class="cls-1" d="M51.64,15.73c.73-1.02,1.39-2.05,2.15-2.99,5.17-6.46,11.85-10.38,20.01-11.64,9.24-1.43,17.68.61,25.11,6.35,6.15,4.76,9.93,11.02,11.25,18.71.57,3.3.5,6.6-.12,9.89-.1.51.05.67.47.86,4.34,1.95,7.61,5.03,9.6,9.36,3.86,8.4.76,18.22-7.29,23.09-1.28.77-2.71,1.31-4.09,1.91-.64.28-1.04.09-1.22-.39-.18-.51.06-.85.75-1.1,5.97-2.17,10.01-6.19,11.5-12.44,1.58-6.63-1.23-13.48-6.98-17.34-1.21-.81-2.58-1.4-3.89-2.06-.58-.29-.74-.51-.6-1.15,2.58-11.54-2.46-22.85-11.92-29.27-4.53-3.07-9.53-4.9-15-5.17-12.31-.61-21.93,4.14-28.55,14.66-.39.62-.73.74-1.43.38-3.66-1.88-7.55-2.62-11.63-2.29-4.98.4-9.35,2.28-13,5.71-3.43,3.22-5.48,7.17-6.11,11.83-.14,1.01-.12,2.03-.15,3.05-.02.85-.21,1.04-1.08,1.07-6.43.22-11.55,2.88-15.06,8.32-4.07,6.29-3.11,14.76,2.14,20.12,2.29,2.34,4.99,3.97,8.15,4.85.08.02.15.05.22.07.5.13.73.45.65.96-.08.46-.52.73-1.05.54-1.33-.46-2.7-.87-3.94-1.52-5.34-2.79-8.81-7.09-9.92-13.05-1.16-6.26.66-11.69,5.12-16.19,3.44-3.46,7.69-5.28,12.56-5.61.34-.02.63-.01.65-.52.36-8.39,4.34-14.6,11.66-18.54,6.58-3.54,13.44-3.62,20.33-.77.25.1.5.22.74.33Z"/>-->
        <!--  <path class="cls-1" d="M27,61.33c4.52-.06,9.03-.11,13.55-.18,4.42-.07,8.83-.15,13.25-.23,4.12-.08,8.24-.15,12.36-.24,3.81-.08,7.61-.15,11.42-.24,3.36-.07,6.71-.14,10.07-.24,2.75-.08,5.49-.19,8.24-.3.93-.04,1.47-.93,1.13-1.83-1.03-2.73-2.07-5.46-3.11-8.18-.08-.22-.15-.44-.26-.75.84,0,1.61-.02,2.38.02.13,0,.29.27.35.45.64,1.85,1.26,3.71,1.89,5.56.05.14.11.28.22.55.13-.34.22-.57.3-.8.59-1.77,1.18-3.53,1.74-5.3.12-.4.31-.52.71-.5.63.03,1.25,0,1.95,0-.06.21-.1.38-.16.53-1.23,3.37-2.46,6.74-3.69,10.1-.71,1.94-1.65,2.59-3.73,2.57-4.63-.05-9.27-.11-13.9-.18-2.65-.04-5.3-.08-7.95-.12-5.44-.08-10.88-.16-16.31-.24-2.63-.04-5.26-.08-7.89-.12-5.46-.08-10.91-.17-16.37-.23-2.06-.03-4.12,0-6.18,0v-.12Z"/>-->
        <!--  <path class="cls-1" d="M92.16,58.46h-2.35c0-.94,0-1.84,0-2.74-.02-1.22-.04-2.43-.08-3.65-.02-.57-.22-1.04-.82-1.23-.62-.2-1.17-.02-1.63.4-.55.51-.66,1.19-.68,1.88-.03,1.51-.03,3.02-.04,4.53,0,.84,0,.84-.87.84-.35,0-.71-.02-1.06,0-.4.03-.5-.15-.5-.52.02-1.35.01-2.71,0-4.06,0-.57-.03-1.14-.06-1.7-.06-.89-.35-1.32-.95-1.44-.75-.15-1.67.22-1.91.94-.23.69-.32,1.45-.35,2.18-.05,1.35-.01,2.71-.02,4.06,0,.15-.03.3-.05.53-.71,0-1.39.01-2.07-.02-.11,0-.29-.22-.29-.33,0-2.94,0-5.89.01-8.83,0-.04.03-.07.07-.15h2.16c.02.36.03.68.05,1.13.84-.82,1.71-1.39,2.87-1.4,1.16,0,2.05.43,2.69,1.44.28-.25.51-.48.77-.69,1.04-.83,2.22-.96,3.44-.54.98.34,1.62,1.07,1.64,2.09.06,2.39.02,4.78.02,7.24Z"/>-->
        <!--  <path class="cls-1" d="M22.77,58.51c.57-1.45,1.08-2.78,1.6-4.11,1.1-2.81,2.2-5.61,3.28-8.43.13-.35.3-.49.67-.47.61.03,1.22-.02,1.82.03.2.01.49.18.56.34,1.67,4.1,3.31,8.21,4.95,12.32.02.05.02.11.05.31-.63,0-1.25-.05-1.85.01-.68.07-1.04-.16-1.22-.83-.16-.6-.45-1.17-.66-1.76-.1-.3-.25-.44-.59-.44-1.45.02-2.91.01-4.36,0-.34,0-.48.14-.58.44-.24.7-.52,1.39-.75,2.1-.11.33-.26.48-.62.47-.72-.02-1.45,0-2.3,0ZM30.98,53.37c-.61-1.63-1.18-3.16-1.75-4.7-.04,0-.09.01-.13.02-.56,1.54-1.12,3.08-1.7,4.68h3.58Z"/>-->
        <!--  <path class="cls-1" d="M84.87,37.54c0,3.24-2.21,5.48-5.39,5.48-3.34,0-5.43-2.13-5.43-5.53,0-3.18,2.22-5.41,5.41-5.42,3.19,0,5.41,2.24,5.41,5.46ZM82.16,37.54c-.15-.59-.23-1.22-.47-1.78-.41-.94-1.19-1.46-2.22-1.45-1.06,0-1.85.54-2.26,1.5-.5,1.14-.5,2.34-.01,3.48.41.95,1.16,1.51,2.24,1.52,1.1.01,1.88-.53,2.29-1.52.22-.53.29-1.12.44-1.75Z"/>-->
        <!--  <path class="cls-1" d="M63.76,57.19c-.21.18-.3.25-.38.33-1.72,1.74-4.24,1.53-5.64-.45-1.18-1.67-1.21-4.87-.07-6.55,1.21-1.79,3.71-2.15,5.38-.78.13.11.26.21.48.4v-4.58h2.43v12.95c-.65,0-1.27.02-1.89-.01-.11,0-.27-.19-.3-.32-.05-.26-.01-.54-.01-.98ZM59.29,53.68s.04.01.07.02c.02.33.02.67.07,1,.03.25.1.5.19.74.3.81,1.01,1.34,1.82,1.36.73.02,1.53-.49,1.83-1.26.45-1.16.44-2.35,0-3.51-.3-.81-.93-1.25-1.81-1.25-.88,0-1.5.47-1.81,1.26-.2.52-.24,1.1-.36,1.65Z"/>-->
        <!--  <path class="cls-1" d="M45.07,42.81h-2.34v-12.88h2.38v4.69c.25-.22.39-.33.53-.45,1.14-.96,2.42-1.16,3.8-.66.95.34,1.6,1.05,1.65,2.04.14,2.4.15,4.81.21,7.26h-2.51c0-.43,0-.81,0-1.2-.02-1.65-.02-3.3-.07-4.94-.03-.93-.41-1.37-1.17-1.53-.79-.16-1.76.22-2.03.95-.23.61-.35,1.29-.37,1.94-.05,1.53-.02,3.06-.03,4.59,0,.04-.02.08-.05.19Z"/>-->
        <!--  <path class="cls-1" d="M55.45,58.51c-.82,0-1.48.02-2.14-.01-.13,0-.29-.16-.37-.28-.11-.2-.17-.43-.26-.7-.2.15-.35.27-.5.38-1.08.81-2.29.99-3.57.65-1.12-.29-1.86-1.21-1.93-2.32-.08-1.36.44-2.31,1.66-2.73.96-.33,1.97-.5,2.95-.75.17-.04.35-.07.51-.14.28-.11.72-.04.7-.54-.02-.52-.16-.97-.71-1.17-1.16-.42-2.02-.1-2.7,1.03-.34-.06-.71-.13-1.07-.19-.34-.06-.69-.12-1.1-.19.31-1.11.87-1.97,1.97-2.33,1.41-.46,2.87-.44,4.29-.02,1.16.35,1.66,1.13,1.71,2.52.03.88,0,1.76,0,2.65.01,1.36-.16,2.75.55,4.15ZM52.44,53.99c-.89.23-1.75.4-2.58.69-.83.29-1,1.23-.41,1.87.39.43,1.09.55,1.75.31,1.11-.41,1.46-1.19,1.24-2.87Z"/>-->
        <!--  <path class="cls-1" d="M70.37,54.5c-.07,1.15.52,2.06,1.44,2.31,1,.27,1.55-.04,2.22-1.26.75.13,1.52.26,2.37.41-.39,1.09-1.03,1.89-2.04,2.35-3.21,1.47-6.49-.53-6.54-4.04-.01-.94.12-1.93.43-2.81.66-1.84,2.39-2.72,4.51-2.51,1.8.18,3.18,1.45,3.57,3.32.12.55.14,1.13.22,1.69.06.44-.12.58-.56.57-1.65-.02-3.3,0-4.95,0-.21,0-.43,0-.68,0ZM70.4,52.95h3.68c-.08-1.46-.87-2.29-2.03-2.18-1.04.09-1.77,1.05-1.65,2.18Z"/>-->
        <!--  <path class="cls-1" d="M61.91,38.87h-6.23c-.15,1,.44,1.98,1.33,2.26,1.01.32,1.66-.02,2.3-1.23.75.12,1.52.25,2.38.39-.36,1.01-.91,1.75-1.79,2.23-3.18,1.72-6.69-.3-6.82-3.94-.05-1.38.14-2.72,1.05-3.83,1.18-1.43,2.74-1.76,4.47-1.37,1.66.38,2.63,1.5,3,3.12.17.74.21,1.51.32,2.36ZM55.7,37.37c1.12,0,2.25,0,3.39-.01.1,0,.28-.21.29-.32.03-1.08-.95-2.02-2-1.93-1.06.09-1.84,1.1-1.67,2.26Z"/>-->
        <!--  <path class="cls-1" d="M37.12,42.8h-2.57v-10.73h-3.84v-2.14h10.23v2.1h-3.83v10.78Z"/>-->
        <!--  <path class="cls-1" d="M91.25,42.79h-2.6v-10.74h-3.84c0-.67-.02-1.28.02-1.9,0-.1.3-.26.47-.27,1.1-.02,2.2-.01,3.3-.01,1.93,0,3.85,0,5.78,0h.68v2.15h-3.81v10.77Z"/>-->
        <!--  <path class="cls-1" d="M43.11,54.93c.8.14,1.56.27,2.37.41-.37,1.51-1.1,2.63-2.61,3.11-2.16.68-4.35.05-5.43-1.57-1.08-1.62-1.11-4.57.02-6.15.38-.52.91-1.01,1.47-1.3,1.42-.74,2.92-.7,4.39-.11,1.1.44,1.67,1.37,2.03,2.54-.78.15-1.55.29-2.35.44-.05-.1-.09-.2-.13-.3-.31-.79-.86-1.21-1.64-1.18-.8.03-1.49.34-1.77,1.13-.44,1.22-.42,2.47.05,3.68.29.76,1.05,1.14,1.91,1.09.75-.04,1.24-.44,1.52-1.26.05-.15.1-.3.17-.52Z"/>-->
        <!--  <path class="cls-1" d="M68.89,36.3c0-1.94.01-3.89,0-5.83,0-.46.14-.63.6-.6.49.03.98.03,1.47,0,.41-.02.54.13.54.54-.02,1.88,0,3.77,0,5.65,0,2.06-.01,4.12,0,6.18,0,.46-.14.63-.6.6-.49-.03-.98-.03-1.47,0-.41.02-.54-.13-.54-.54.02-2,0-4,0-6.01Z"/>-->
        <!--  <g>-->
        <!--    <path class="cls-1" d="M18.64,69.63c.18-.32.43-.57.74-.75.31-.18.66-.27,1.04-.27.45,0,.84.11,1.17.32.33.22.58.52.73.92h-.64c-.11-.25-.28-.44-.49-.57-.21-.13-.47-.2-.77-.2s-.54.07-.77.2c-.23.13-.41.32-.53.57-.13.24-.19.53-.19.86s.07.61.19.85c.13.24.31.43.53.57s.48.2.77.2.55-.07.77-.2.38-.32.49-.57h.64c-.15.39-.4.69-.73.91-.33.21-.72.32-1.17.32-.38,0-.73-.09-1.04-.27-.31-.18-.56-.43-.74-.74-.18-.32-.27-.68-.27-1.08s.09-.76.27-1.08Z"/>-->
        <!--    <path class="cls-1" d="M23.69,72.61c-.24-.14-.44-.33-.58-.58-.14-.25-.21-.55-.21-.88s.07-.62.21-.87c.14-.25.34-.45.58-.58s.52-.2.83-.2.58.07.83.2.44.33.58.58c.14.25.21.54.21.88s-.07.63-.22.88c-.15.25-.35.45-.6.58s-.53.21-.83.21-.57-.07-.82-.21ZM25.05,72.21c.17-.09.3-.23.41-.41.1-.18.16-.4.16-.66s-.05-.48-.15-.66-.23-.31-.4-.4c-.16-.09-.34-.13-.53-.13s-.37.04-.54.13c-.16.09-.29.22-.39.4-.1.18-.15.4-.15.66s.05.48.14.66c.1.18.22.31.39.4.16.09.34.13.53.13s.37-.04.54-.14Z"/>-->
        <!--    <path class="cls-1" d="M29.39,69.84c.24.24.36.58.36,1.03v1.9h-.53v-1.82c0-.32-.08-.57-.24-.74-.16-.17-.38-.26-.66-.26s-.51.09-.67.26c-.17.18-.25.43-.25.77v1.78h-.53v-3.22h.53v.46c.11-.16.25-.29.43-.38.18-.09.38-.14.6-.14.39,0,.71.12.95.36Z"/>-->
        <!--    <path class="cls-1" d="M33.14,69.84c.24.24.36.58.36,1.03v1.9h-.53v-1.82c0-.32-.08-.57-.24-.74-.16-.17-.38-.26-.66-.26s-.51.09-.67.26c-.17.18-.25.43-.25.77v1.78h-.53v-3.22h.53v.46c.11-.16.25-.29.43-.38.18-.09.38-.14.6-.14.39,0,.71.12.95.36Z"/>-->
        <!--    <path class="cls-1" d="M37.3,71.35h-2.57c.02.32.13.57.33.74s.44.27.72.27c.23,0,.42-.05.58-.16.15-.11.26-.25.33-.43h.58c-.09.31-.26.56-.52.76-.26.19-.58.29-.96.29-.31,0-.58-.07-.82-.21s-.43-.33-.57-.58c-.14-.25-.21-.55-.21-.88s.07-.62.2-.88c.13-.25.32-.44.56-.58s.52-.2.83-.2.58.07.81.2c.23.13.42.32.54.55.13.23.19.5.19.79,0,.1,0,.21-.02.32ZM36.63,70.39c-.09-.15-.21-.26-.37-.33-.15-.08-.33-.11-.51-.11-.27,0-.5.09-.69.26s-.3.41-.33.72h2.03c0-.2-.04-.38-.14-.53Z"/>-->
        <!--    <path class="cls-1" d="M38.03,70.27c.13-.25.32-.44.56-.58.24-.14.51-.21.81-.21.4,0,.72.1.98.29.26.19.43.46.51.8h-.58c-.05-.2-.16-.35-.32-.46-.16-.11-.36-.17-.59-.17-.31,0-.55.1-.74.31s-.28.51-.28.89.09.69.28.9c.19.21.43.32.74.32.24,0,.43-.05.59-.16.16-.11.26-.27.32-.47h.58c-.09.33-.26.59-.52.79-.26.2-.58.3-.97.3-.31,0-.58-.07-.81-.21-.24-.14-.42-.33-.56-.58-.13-.25-.2-.54-.2-.88s.07-.62.2-.87Z"/>-->
        <!--    <path class="cls-1" d="M42.25,69.98v1.9c0,.16.03.27.1.33.07.06.18.1.35.1h.39v.45h-.48c-.3,0-.52-.07-.67-.21-.15-.14-.22-.36-.22-.68v-1.9h-.42v-.44h.42v-.81h.53v.81h.84v.44h-.84Z"/>-->
        <!--    <path class="cls-1" d="M43.75,68.91c-.07-.07-.11-.16-.11-.26s.04-.19.11-.26c.07-.07.16-.11.26-.11s.18.04.25.11c.07.07.1.16.1.26s-.03.19-.1.26c-.07.07-.15.11-.25.11s-.19-.04-.26-.11ZM44.27,69.54v3.22h-.53v-3.22h.53Z"/>-->
        <!--    <path class="cls-1" d="M47.7,69.84c.24.24.36.58.36,1.03v1.9h-.53v-1.82c0-.32-.08-.57-.24-.74-.16-.17-.38-.26-.66-.26s-.51.09-.67.26c-.17.18-.25.43-.25.77v1.78h-.53v-3.22h.53v.46c.11-.16.25-.29.43-.38.18-.09.38-.14.6-.14.39,0,.71.12.95.36Z"/>-->
        <!--    <path class="cls-1" d="M51,69.67c.21.12.37.27.47.46v-.59h.54v3.29c0,.29-.06.56-.19.78-.12.23-.3.41-.54.54-.23.13-.5.19-.81.19-.42,0-.78-.1-1.06-.3-.28-.2-.45-.47-.5-.82h.53c.06.2.18.35.36.47.18.12.41.18.66.18.29,0,.53-.09.72-.28.19-.18.28-.44.28-.78v-.68c-.11.19-.26.34-.47.47-.21.13-.45.19-.73.19s-.55-.07-.78-.21c-.23-.14-.42-.34-.55-.59-.13-.25-.2-.54-.2-.87s.07-.62.2-.87c.13-.25.32-.44.55-.58.23-.14.49-.21.78-.21s.52.06.73.18ZM51.32,70.51c-.1-.18-.23-.32-.4-.41s-.35-.14-.55-.14-.38.05-.55.14-.3.23-.39.41c-.1.18-.15.39-.15.63s.05.46.15.64c.1.18.23.32.39.42s.35.14.55.14.38-.05.55-.14.3-.24.4-.42.15-.39.15-.64-.05-.45-.15-.63Z"/>-->
        <!--    <path class="cls-1" d="M57,68.66v.43h-1.12v3.66h-.53v-3.66h-1.12v-.43h2.77Z"/>-->
        <!--    <path class="cls-1" d="M59.91,69.64c.2.1.35.26.46.47s.17.46.17.76v1.9h-.53v-1.82c0-.32-.08-.57-.24-.74-.16-.17-.38-.26-.66-.26s-.51.09-.67.26c-.17.18-.25.43-.25.77v1.78h-.53v-4.35h.53v1.59c.11-.16.25-.29.44-.38s.39-.14.63-.14c.24,0,.46.05.66.16Z"/>-->
        <!--    <path class="cls-1" d="M64.33,71.35h-2.57c.02.32.13.57.33.74s.44.27.72.27c.23,0,.42-.05.58-.16.15-.11.26-.25.33-.43h.58c-.09.31-.26.56-.52.76-.26.19-.58.29-.96.29-.31,0-.58-.07-.82-.21s-.43-.33-.57-.58c-.14-.25-.21-.55-.21-.88s.07-.62.2-.88c.13-.25.32-.44.56-.58s.52-.2.83-.2.58.07.81.2c.23.13.42.32.54.55.13.23.19.5.19.79,0,.1,0,.21-.02.32ZM63.66,70.39c-.09-.15-.21-.26-.37-.33-.15-.08-.33-.11-.51-.11-.27,0-.5.09-.69.26s-.3.41-.33.72h2.03c0-.2-.04-.38-.14-.53Z"/>-->
        <!--    <path class="cls-1" d="M67.15,68.66v2.59c0,.36.09.63.27.81.18.18.43.26.74.26s.56-.09.74-.26c.18-.18.27-.45.27-.81v-2.59h.53v2.58c0,.34-.07.63-.21.86s-.32.41-.56.52c-.23.11-.49.17-.78.17s-.55-.06-.78-.17c-.23-.11-.42-.29-.55-.52s-.2-.52-.2-.86v-2.58h.53Z"/>-->
        <!--    <path class="cls-1" d="M73.11,69.84c.24.24.36.58.36,1.03v1.9h-.53v-1.82c0-.32-.08-.57-.24-.74-.16-.17-.38-.26-.66-.26s-.51.09-.67.26c-.17.18-.25.43-.25.77v1.78h-.53v-3.22h.53v.46c.11-.16.25-.29.43-.38.18-.09.38-.14.6-.14.39,0,.71.12.95.36Z"/>-->
        <!--    <path class="cls-1" d="M74.35,70.27c.13-.25.32-.44.56-.58.24-.14.51-.21.81-.21.4,0,.72.1.98.29.26.19.43.46.51.8h-.58c-.05-.2-.16-.35-.32-.46-.16-.11-.36-.17-.59-.17-.31,0-.55.1-.74.31s-.28.51-.28.89.09.69.28.9c.19.21.43.32.74.32.24,0,.43-.05.59-.16.16-.11.26-.27.32-.47h.58c-.09.33-.26.59-.52.79-.26.2-.58.3-.97.3-.31,0-.58-.07-.81-.21-.24-.14-.42-.33-.56-.58-.13-.25-.2-.54-.2-.88s.07-.62.2-.87Z"/>-->
        <!--    <path class="cls-1" d="M78.5,72.61c-.24-.14-.44-.33-.58-.58-.14-.25-.21-.55-.21-.88s.07-.62.21-.87c.14-.25.34-.45.58-.58s.52-.2.83-.2.58.07.83.2.44.33.58.58c.14.25.21.54.21.88s-.07.63-.22.88c-.15.25-.35.45-.6.58s-.53.21-.83.21-.57-.07-.82-.21ZM79.86,72.21c.17-.09.3-.23.41-.41.1-.18.16-.4.16-.66s-.05-.48-.15-.66-.23-.31-.4-.4c-.16-.09-.34-.13-.53-.13s-.37.04-.54.13c-.16.09-.29.22-.39.4-.1.18-.15.4-.15.66s.05.48.14.66c.1.18.22.31.39.4.16.09.34.13.53.13s.37-.04.54-.14Z"/>-->
        <!--    <path class="cls-1" d="M84.2,69.84c.24.24.36.58.36,1.03v1.9h-.53v-1.82c0-.32-.08-.57-.24-.74-.16-.17-.38-.26-.66-.26s-.51.09-.67.26c-.17.18-.25.43-.25.77v1.78h-.53v-3.22h.53v.46c.11-.16.25-.29.43-.38.18-.09.38-.14.6-.14.39,0,.71.12.95.36Z"/>-->
        <!--    <path class="cls-1" d="M87.96,69.84c.24.24.36.58.36,1.03v1.9h-.53v-1.82c0-.32-.08-.57-.24-.74-.16-.17-.38-.26-.66-.26s-.51.09-.67.26c-.17.18-.25.43-.25.77v1.78h-.53v-3.22h.53v.46c.11-.16.25-.29.43-.38.18-.09.38-.14.6-.14.39,0,.71.12.95.36Z"/>-->
        <!--    <path class="cls-1" d="M92.12,71.35h-2.57c.02.32.13.57.33.74s.44.27.72.27c.23,0,.42-.05.58-.16.15-.11.26-.25.33-.43h.58c-.09.31-.26.56-.52.76-.26.19-.58.29-.96.29-.31,0-.58-.07-.82-.21s-.43-.33-.57-.58c-.14-.25-.21-.55-.21-.88s.07-.62.2-.88c.13-.25.32-.44.56-.58s.52-.2.83-.2.58.07.81.2c.23.13.42.32.54.55.13.23.19.5.19.79,0,.1,0,.21-.02.32ZM91.45,70.39c-.09-.15-.21-.26-.37-.33-.15-.08-.33-.11-.51-.11-.27,0-.5.09-.69.26s-.3.41-.33.72h2.03c0-.2-.04-.38-.14-.53Z"/>-->
        <!--    <path class="cls-1" d="M92.84,70.27c.13-.25.32-.44.56-.58.24-.14.51-.21.81-.21.4,0,.72.1.98.29.26.19.43.46.51.8h-.58c-.05-.2-.16-.35-.32-.46-.16-.11-.36-.17-.59-.17-.31,0-.55.1-.74.31s-.28.51-.28.89.09.69.28.9c.19.21.43.32.74.32.24,0,.43-.05.59-.16.16-.11.26-.27.32-.47h.58c-.09.33-.26.59-.52.79-.26.2-.58.3-.97.3-.31,0-.58-.07-.81-.21-.24-.14-.42-.33-.56-.58-.13-.25-.2-.54-.2-.88s.07-.62.2-.87Z"/>-->
        <!--    <path class="cls-1" d="M97.06,69.98v1.9c0,.16.03.27.1.33.07.06.18.1.35.1h.39v.45h-.48c-.3,0-.52-.07-.67-.21-.15-.14-.22-.36-.22-.68v-1.9h-.42v-.44h.42v-.81h.53v.81h.84v.44h-.84Z"/>-->
        <!--    <path class="cls-1" d="M101.46,71.35h-2.57c.02.32.13.57.33.74s.44.27.72.27c.23,0,.42-.05.58-.16.15-.11.26-.25.33-.43h.58c-.09.31-.26.56-.52.76-.26.19-.58.29-.96.29-.31,0-.58-.07-.82-.21s-.43-.33-.57-.58c-.14-.25-.21-.55-.21-.88s.07-.62.2-.88c.13-.25.32-.44.56-.58s.52-.2.83-.2.58.07.81.2c.23.13.42.32.54.55.13.23.19.5.19.79,0,.1,0,.21-.02.32ZM100.79,70.39c-.09-.15-.21-.26-.37-.33-.15-.08-.33-.11-.51-.11-.27,0-.5.09-.69.26s-.3.41-.33.72h2.03c0-.2-.04-.38-.14-.53Z"/>-->
        <!--    <path class="cls-1" d="M102.19,70.27c.13-.25.32-.44.55-.58.23-.14.49-.21.78-.21.25,0,.48.06.7.17.22.12.38.27.49.46v-1.7h.54v4.35h-.54v-.6c-.11.19-.26.35-.47.47-.21.12-.45.19-.73.19s-.55-.07-.78-.21c-.23-.14-.42-.34-.55-.59-.13-.25-.2-.54-.2-.87s.07-.62.2-.87ZM104.56,70.51c-.1-.18-.23-.32-.4-.41s-.35-.14-.55-.14-.38.05-.55.14-.3.23-.39.41c-.1.18-.15.39-.15.63s.05.46.15.64c.1.18.23.32.39.42s.35.14.55.14.38-.05.55-.14.3-.24.4-.42.15-.39.15-.64-.05-.45-.15-.63Z"/>-->
        <!--  </g>-->
        <!--</svg>-->
        <span class="close-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                fill="none">
                <path
                    d="M19.5347 0.465273C19.2367 0.167359 18.8326 0 18.4112 0C17.9898 0 17.5857 0.167359 17.2877 0.465273L10 7.75298L2.71229 0.465273C2.41429 0.167359 2.01016 0 1.58878 0C1.1674 0 0.763278 0.167359 0.465273 0.465273C0.167359 0.763278 0 1.1674 0 1.58878C0 2.01016 0.167359 2.41429 0.465273 2.71229L7.75298 10L0.465273 17.2877C0.167359 17.5857 0 17.9898 0 18.4112C0 18.8326 0.167359 19.2367 0.465273 19.5347C0.763278 19.8326 1.1674 20 1.58878 20C2.01016 20 2.41429 19.8326 2.71229 19.5347L10 12.247L17.2877 19.5347C17.5857 19.8326 17.9898 20 18.4112 20C18.8326 20 19.2367 19.8326 19.5347 19.5347C19.8326 19.2367 20 18.8326 20 18.4112C20 17.9898 19.8326 17.5857 19.5347 17.2877L12.247 10L19.5347 2.71229C19.8326 2.41429 20 2.01016 20 1.58878C20 1.1674 19.8326 0.763278 19.5347 0.465273Z"
                    fill="#5970CC" />
            </svg>
        </span>
    </div>
    <ul>
        <li class="has-submenu" id="explore-course"><a>Explore Course</a></li>
        <li><a href="http://localhost/TIA/blog">Blog</a></li>
        <li><a href="http://localhost/TIA/our-placements">Our Placements</a></li>
        <li class="has-submenu"><a href="#" data-submenu="resources">Resources </a>
            <div id="resources" class="submenu">
                <div class="submenu-header"><a href="#" data-submenu-close="resources">Resources</a></div>
                <ul>
                    <li><a target="_blank" href="https://job.uctconsulting.com/" rel="nofollow noopener">Job Portal</a>
                    </li>
                    <li><a target="_blank" href="http://localhost/TIA/hire-from-us">Hire From Us</a></li>
                    <li><a target="_blank" href="https://www.upskillcampus.com/salary-predictor"
                            rel="nofollow noopener">Salary Predictor</a>
                    </li>
                    <li><a target="_blank" href="https://www.forum.upskillcampus.com/"
                            rel="nofollow noopener">Discussion Forum</a></li>
                    <li><a target="_blank" href="https://www.upskillcampus.com/ticket-to-corporate"
                            rel="nofollow noopener">Ticket To
                            Corporate</a></li>
                </ul>
            </div>
        </li>

        <li class="has-submenu"><a href="#" data-submenu="internshipr">Internship </a>
            <div id="internshipr" class="submenu">
                <div class="submenu-header"><a href="#" data-submenu-close="internshipr">Internship</a></div>
                <ul>
                    <li><a href="https://learn.upskillcampus.com/s/pages/basic-internships" target="_blank">Basic</a>
                    </li>
                    <li><a href="https://learn.upskillcampus.com/s/pages/online-paid-internship"
                            target="_blank">Premium</a></li>
                </ul>
            </div>
        </li>
        <li class="has-submenu"><a href="#" data-submenu="stuntemplg">Login </a>
            <div id="stuntemplg" class="submenu">
                <div class="submenu-header"><a href="#" data-submenu-close="stuntemplg">Login</a></div>
                <ul>
                    <li><a href="https://learn.upskillcampus.com/s/dashboard">Student Login</a></li>
                    <li><a href="https://theiotacademy.co:2096/">Employer Login</a></li>
                </ul>
            </div>
        </li>
    </ul>
</div>

<div class="zeynep" id="zeynep2">
    <div class="menu-header d-flex justify-content-end align-items-center">
        <img loading="lazy" class="logo img-fluid" src="http://localhost/TIA/assets/dit/images/navbar/logo.svg"
            alt="iotacademy" width="109" height="67" />



        <!--<svg class="logo img-fluid" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 122 75">-->
        <!--  <defs>-->
        <!--    <style>-->
        <!--      .cls-1 {-->
        <!--        fill: #03045e;-->
        <!--        stroke-width: 0px;-->
        <!--      }-->
        <!--    </style>-->
        <!--  </defs>-->
        <!--  <path class="cls-1" d="M51.64,15.73c.73-1.02,1.39-2.05,2.15-2.99,5.17-6.46,11.85-10.38,20.01-11.64,9.24-1.43,17.68.61,25.11,6.35,6.15,4.76,9.93,11.02,11.25,18.71.57,3.3.5,6.6-.12,9.89-.1.51.05.67.47.86,4.34,1.95,7.61,5.03,9.6,9.36,3.86,8.4.76,18.22-7.29,23.09-1.28.77-2.71,1.31-4.09,1.91-.64.28-1.04.09-1.22-.39-.18-.51.06-.85.75-1.1,5.97-2.17,10.01-6.19,11.5-12.44,1.58-6.63-1.23-13.48-6.98-17.34-1.21-.81-2.58-1.4-3.89-2.06-.58-.29-.74-.51-.6-1.15,2.58-11.54-2.46-22.85-11.92-29.27-4.53-3.07-9.53-4.9-15-5.17-12.31-.61-21.93,4.14-28.55,14.66-.39.62-.73.74-1.43.38-3.66-1.88-7.55-2.62-11.63-2.29-4.98.4-9.35,2.28-13,5.71-3.43,3.22-5.48,7.17-6.11,11.83-.14,1.01-.12,2.03-.15,3.05-.02.85-.21,1.04-1.08,1.07-6.43.22-11.55,2.88-15.06,8.32-4.07,6.29-3.11,14.76,2.14,20.12,2.29,2.34,4.99,3.97,8.15,4.85.08.02.15.05.22.07.5.13.73.45.65.96-.08.46-.52.73-1.05.54-1.33-.46-2.7-.87-3.94-1.52-5.34-2.79-8.81-7.09-9.92-13.05-1.16-6.26.66-11.69,5.12-16.19,3.44-3.46,7.69-5.28,12.56-5.61.34-.02.63-.01.65-.52.36-8.39,4.34-14.6,11.66-18.54,6.58-3.54,13.44-3.62,20.33-.77.25.1.5.22.74.33Z"/>-->
        <!--  <path class="cls-1" d="M27,61.33c4.52-.06,9.03-.11,13.55-.18,4.42-.07,8.83-.15,13.25-.23,4.12-.08,8.24-.15,12.36-.24,3.81-.08,7.61-.15,11.42-.24,3.36-.07,6.71-.14,10.07-.24,2.75-.08,5.49-.19,8.24-.3.93-.04,1.47-.93,1.13-1.83-1.03-2.73-2.07-5.46-3.11-8.18-.08-.22-.15-.44-.26-.75.84,0,1.61-.02,2.38.02.13,0,.29.27.35.45.64,1.85,1.26,3.71,1.89,5.56.05.14.11.28.22.55.13-.34.22-.57.3-.8.59-1.77,1.18-3.53,1.74-5.3.12-.4.31-.52.71-.5.63.03,1.25,0,1.95,0-.06.21-.1.38-.16.53-1.23,3.37-2.46,6.74-3.69,10.1-.71,1.94-1.65,2.59-3.73,2.57-4.63-.05-9.27-.11-13.9-.18-2.65-.04-5.3-.08-7.95-.12-5.44-.08-10.88-.16-16.31-.24-2.63-.04-5.26-.08-7.89-.12-5.46-.08-10.91-.17-16.37-.23-2.06-.03-4.12,0-6.18,0v-.12Z"/>-->
        <!--  <path class="cls-1" d="M92.16,58.46h-2.35c0-.94,0-1.84,0-2.74-.02-1.22-.04-2.43-.08-3.65-.02-.57-.22-1.04-.82-1.23-.62-.2-1.17-.02-1.63.4-.55.51-.66,1.19-.68,1.88-.03,1.51-.03,3.02-.04,4.53,0,.84,0,.84-.87.84-.35,0-.71-.02-1.06,0-.4.03-.5-.15-.5-.52.02-1.35.01-2.71,0-4.06,0-.57-.03-1.14-.06-1.7-.06-.89-.35-1.32-.95-1.44-.75-.15-1.67.22-1.91.94-.23.69-.32,1.45-.35,2.18-.05,1.35-.01,2.71-.02,4.06,0,.15-.03.3-.05.53-.71,0-1.39.01-2.07-.02-.11,0-.29-.22-.29-.33,0-2.94,0-5.89.01-8.83,0-.04.03-.07.07-.15h2.16c.02.36.03.68.05,1.13.84-.82,1.71-1.39,2.87-1.4,1.16,0,2.05.43,2.69,1.44.28-.25.51-.48.77-.69,1.04-.83,2.22-.96,3.44-.54.98.34,1.62,1.07,1.64,2.09.06,2.39.02,4.78.02,7.24Z"/>-->
        <!--  <path class="cls-1" d="M22.77,58.51c.57-1.45,1.08-2.78,1.6-4.11,1.1-2.81,2.2-5.61,3.28-8.43.13-.35.3-.49.67-.47.61.03,1.22-.02,1.82.03.2.01.49.18.56.34,1.67,4.1,3.31,8.21,4.95,12.32.02.05.02.11.05.31-.63,0-1.25-.05-1.85.01-.68.07-1.04-.16-1.22-.83-.16-.6-.45-1.17-.66-1.76-.1-.3-.25-.44-.59-.44-1.45.02-2.91.01-4.36,0-.34,0-.48.14-.58.44-.24.7-.52,1.39-.75,2.1-.11.33-.26.48-.62.47-.72-.02-1.45,0-2.3,0ZM30.98,53.37c-.61-1.63-1.18-3.16-1.75-4.7-.04,0-.09.01-.13.02-.56,1.54-1.12,3.08-1.7,4.68h3.58Z"/>-->
        <!--  <path class="cls-1" d="M84.87,37.54c0,3.24-2.21,5.48-5.39,5.48-3.34,0-5.43-2.13-5.43-5.53,0-3.18,2.22-5.41,5.41-5.42,3.19,0,5.41,2.24,5.41,5.46ZM82.16,37.54c-.15-.59-.23-1.22-.47-1.78-.41-.94-1.19-1.46-2.22-1.45-1.06,0-1.85.54-2.26,1.5-.5,1.14-.5,2.34-.01,3.48.41.95,1.16,1.51,2.24,1.52,1.1.01,1.88-.53,2.29-1.52.22-.53.29-1.12.44-1.75Z"/>-->
        <!--  <path class="cls-1" d="M63.76,57.19c-.21.18-.3.25-.38.33-1.72,1.74-4.24,1.53-5.64-.45-1.18-1.67-1.21-4.87-.07-6.55,1.21-1.79,3.71-2.15,5.38-.78.13.11.26.21.48.4v-4.58h2.43v12.95c-.65,0-1.27.02-1.89-.01-.11,0-.27-.19-.3-.32-.05-.26-.01-.54-.01-.98ZM59.29,53.68s.04.01.07.02c.02.33.02.67.07,1,.03.25.1.5.19.74.3.81,1.01,1.34,1.82,1.36.73.02,1.53-.49,1.83-1.26.45-1.16.44-2.35,0-3.51-.3-.81-.93-1.25-1.81-1.25-.88,0-1.5.47-1.81,1.26-.2.52-.24,1.1-.36,1.65Z"/>-->
        <!--  <path class="cls-1" d="M45.07,42.81h-2.34v-12.88h2.38v4.69c.25-.22.39-.33.53-.45,1.14-.96,2.42-1.16,3.8-.66.95.34,1.6,1.05,1.65,2.04.14,2.4.15,4.81.21,7.26h-2.51c0-.43,0-.81,0-1.2-.02-1.65-.02-3.3-.07-4.94-.03-.93-.41-1.37-1.17-1.53-.79-.16-1.76.22-2.03.95-.23.61-.35,1.29-.37,1.94-.05,1.53-.02,3.06-.03,4.59,0,.04-.02.08-.05.19Z"/>-->
        <!--  <path class="cls-1" d="M55.45,58.51c-.82,0-1.48.02-2.14-.01-.13,0-.29-.16-.37-.28-.11-.2-.17-.43-.26-.7-.2.15-.35.27-.5.38-1.08.81-2.29.99-3.57.65-1.12-.29-1.86-1.21-1.93-2.32-.08-1.36.44-2.31,1.66-2.73.96-.33,1.97-.5,2.95-.75.17-.04.35-.07.51-.14.28-.11.72-.04.7-.54-.02-.52-.16-.97-.71-1.17-1.16-.42-2.02-.1-2.7,1.03-.34-.06-.71-.13-1.07-.19-.34-.06-.69-.12-1.1-.19.31-1.11.87-1.97,1.97-2.33,1.41-.46,2.87-.44,4.29-.02,1.16.35,1.66,1.13,1.71,2.52.03.88,0,1.76,0,2.65.01,1.36-.16,2.75.55,4.15ZM52.44,53.99c-.89.23-1.75.4-2.58.69-.83.29-1,1.23-.41,1.87.39.43,1.09.55,1.75.31,1.11-.41,1.46-1.19,1.24-2.87Z"/>-->
        <!--  <path class="cls-1" d="M70.37,54.5c-.07,1.15.52,2.06,1.44,2.31,1,.27,1.55-.04,2.22-1.26.75.13,1.52.26,2.37.41-.39,1.09-1.03,1.89-2.04,2.35-3.21,1.47-6.49-.53-6.54-4.04-.01-.94.12-1.93.43-2.81.66-1.84,2.39-2.72,4.51-2.51,1.8.18,3.18,1.45,3.57,3.32.12.55.14,1.13.22,1.69.06.44-.12.58-.56.57-1.65-.02-3.3,0-4.95,0-.21,0-.43,0-.68,0ZM70.4,52.95h3.68c-.08-1.46-.87-2.29-2.03-2.18-1.04.09-1.77,1.05-1.65,2.18Z"/>-->
        <!--  <path class="cls-1" d="M61.91,38.87h-6.23c-.15,1,.44,1.98,1.33,2.26,1.01.32,1.66-.02,2.3-1.23.75.12,1.52.25,2.38.39-.36,1.01-.91,1.75-1.79,2.23-3.18,1.72-6.69-.3-6.82-3.94-.05-1.38.14-2.72,1.05-3.83,1.18-1.43,2.74-1.76,4.47-1.37,1.66.38,2.63,1.5,3,3.12.17.74.21,1.51.32,2.36ZM55.7,37.37c1.12,0,2.25,0,3.39-.01.1,0,.28-.21.29-.32.03-1.08-.95-2.02-2-1.93-1.06.09-1.84,1.1-1.67,2.26Z"/>-->
        <!--  <path class="cls-1" d="M37.12,42.8h-2.57v-10.73h-3.84v-2.14h10.23v2.1h-3.83v10.78Z"/>-->
        <!--  <path class="cls-1" d="M91.25,42.79h-2.6v-10.74h-3.84c0-.67-.02-1.28.02-1.9,0-.1.3-.26.47-.27,1.1-.02,2.2-.01,3.3-.01,1.93,0,3.85,0,5.78,0h.68v2.15h-3.81v10.77Z"/>-->
        <!--  <path class="cls-1" d="M43.11,54.93c.8.14,1.56.27,2.37.41-.37,1.51-1.1,2.63-2.61,3.11-2.16.68-4.35.05-5.43-1.57-1.08-1.62-1.11-4.57.02-6.15.38-.52.91-1.01,1.47-1.3,1.42-.74,2.92-.7,4.39-.11,1.1.44,1.67,1.37,2.03,2.54-.78.15-1.55.29-2.35.44-.05-.1-.09-.2-.13-.3-.31-.79-.86-1.21-1.64-1.18-.8.03-1.49.34-1.77,1.13-.44,1.22-.42,2.47.05,3.68.29.76,1.05,1.14,1.91,1.09.75-.04,1.24-.44,1.52-1.26.05-.15.1-.3.17-.52Z"/>-->
        <!--  <path class="cls-1" d="M68.89,36.3c0-1.94.01-3.89,0-5.83,0-.46.14-.63.6-.6.49.03.98.03,1.47,0,.41-.02.54.13.54.54-.02,1.88,0,3.77,0,5.65,0,2.06-.01,4.12,0,6.18,0,.46-.14.63-.6.6-.49-.03-.98-.03-1.47,0-.41.02-.54-.13-.54-.54.02-2,0-4,0-6.01Z"/>-->
        <!--  <g>-->
        <!--    <path class="cls-1" d="M18.64,69.63c.18-.32.43-.57.74-.75.31-.18.66-.27,1.04-.27.45,0,.84.11,1.17.32.33.22.58.52.73.92h-.64c-.11-.25-.28-.44-.49-.57-.21-.13-.47-.2-.77-.2s-.54.07-.77.2c-.23.13-.41.32-.53.57-.13.24-.19.53-.19.86s.07.61.19.85c.13.24.31.43.53.57s.48.2.77.2.55-.07.77-.2.38-.32.49-.57h.64c-.15.39-.4.69-.73.91-.33.21-.72.32-1.17.32-.38,0-.73-.09-1.04-.27-.31-.18-.56-.43-.74-.74-.18-.32-.27-.68-.27-1.08s.09-.76.27-1.08Z"/>-->
        <!--    <path class="cls-1" d="M23.69,72.61c-.24-.14-.44-.33-.58-.58-.14-.25-.21-.55-.21-.88s.07-.62.21-.87c.14-.25.34-.45.58-.58s.52-.2.83-.2.58.07.83.2.44.33.58.58c.14.25.21.54.21.88s-.07.63-.22.88c-.15.25-.35.45-.6.58s-.53.21-.83.21-.57-.07-.82-.21ZM25.05,72.21c.17-.09.3-.23.41-.41.1-.18.16-.4.16-.66s-.05-.48-.15-.66-.23-.31-.4-.4c-.16-.09-.34-.13-.53-.13s-.37.04-.54.13c-.16.09-.29.22-.39.4-.1.18-.15.4-.15.66s.05.48.14.66c.1.18.22.31.39.4.16.09.34.13.53.13s.37-.04.54-.14Z"/>-->
        <!--    <path class="cls-1" d="M29.39,69.84c.24.24.36.58.36,1.03v1.9h-.53v-1.82c0-.32-.08-.57-.24-.74-.16-.17-.38-.26-.66-.26s-.51.09-.67.26c-.17.18-.25.43-.25.77v1.78h-.53v-3.22h.53v.46c.11-.16.25-.29.43-.38.18-.09.38-.14.6-.14.39,0,.71.12.95.36Z"/>-->
        <!--    <path class="cls-1" d="M33.14,69.84c.24.24.36.58.36,1.03v1.9h-.53v-1.82c0-.32-.08-.57-.24-.74-.16-.17-.38-.26-.66-.26s-.51.09-.67.26c-.17.18-.25.43-.25.77v1.78h-.53v-3.22h.53v.46c.11-.16.25-.29.43-.38.18-.09.38-.14.6-.14.39,0,.71.12.95.36Z"/>-->
        <!--    <path class="cls-1" d="M37.3,71.35h-2.57c.02.32.13.57.33.74s.44.27.72.27c.23,0,.42-.05.58-.16.15-.11.26-.25.33-.43h.58c-.09.31-.26.56-.52.76-.26.19-.58.29-.96.29-.31,0-.58-.07-.82-.21s-.43-.33-.57-.58c-.14-.25-.21-.55-.21-.88s.07-.62.2-.88c.13-.25.32-.44.56-.58s.52-.2.83-.2.58.07.81.2c.23.13.42.32.54.55.13.23.19.5.19.79,0,.1,0,.21-.02.32ZM36.63,70.39c-.09-.15-.21-.26-.37-.33-.15-.08-.33-.11-.51-.11-.27,0-.5.09-.69.26s-.3.41-.33.72h2.03c0-.2-.04-.38-.14-.53Z"/>-->
        <!--    <path class="cls-1" d="M38.03,70.27c.13-.25.32-.44.56-.58.24-.14.51-.21.81-.21.4,0,.72.1.98.29.26.19.43.46.51.8h-.58c-.05-.2-.16-.35-.32-.46-.16-.11-.36-.17-.59-.17-.31,0-.55.1-.74.31s-.28.51-.28.89.09.69.28.9c.19.21.43.32.74.32.24,0,.43-.05.59-.16.16-.11.26-.27.32-.47h.58c-.09.33-.26.59-.52.79-.26.2-.58.3-.97.3-.31,0-.58-.07-.81-.21-.24-.14-.42-.33-.56-.58-.13-.25-.2-.54-.2-.88s.07-.62.2-.87Z"/>-->
        <!--    <path class="cls-1" d="M42.25,69.98v1.9c0,.16.03.27.1.33.07.06.18.1.35.1h.39v.45h-.48c-.3,0-.52-.07-.67-.21-.15-.14-.22-.36-.22-.68v-1.9h-.42v-.44h.42v-.81h.53v.81h.84v.44h-.84Z"/>-->
        <!--    <path class="cls-1" d="M43.75,68.91c-.07-.07-.11-.16-.11-.26s.04-.19.11-.26c.07-.07.16-.11.26-.11s.18.04.25.11c.07.07.1.16.1.26s-.03.19-.1.26c-.07.07-.15.11-.25.11s-.19-.04-.26-.11ZM44.27,69.54v3.22h-.53v-3.22h.53Z"/>-->
        <!--    <path class="cls-1" d="M47.7,69.84c.24.24.36.58.36,1.03v1.9h-.53v-1.82c0-.32-.08-.57-.24-.74-.16-.17-.38-.26-.66-.26s-.51.09-.67.26c-.17.18-.25.43-.25.77v1.78h-.53v-3.22h.53v.46c.11-.16.25-.29.43-.38.18-.09.38-.14.6-.14.39,0,.71.12.95.36Z"/>-->
        <!--    <path class="cls-1" d="M51,69.67c.21.12.37.27.47.46v-.59h.54v3.29c0,.29-.06.56-.19.78-.12.23-.3.41-.54.54-.23.13-.5.19-.81.19-.42,0-.78-.1-1.06-.3-.28-.2-.45-.47-.5-.82h.53c.06.2.18.35.36.47.18.12.41.18.66.18.29,0,.53-.09.72-.28.19-.18.28-.44.28-.78v-.68c-.11.19-.26.34-.47.47-.21.13-.45.19-.73.19s-.55-.07-.78-.21c-.23-.14-.42-.34-.55-.59-.13-.25-.2-.54-.2-.87s.07-.62.2-.87c.13-.25.32-.44.55-.58.23-.14.49-.21.78-.21s.52.06.73.18ZM51.32,70.51c-.1-.18-.23-.32-.4-.41s-.35-.14-.55-.14-.38.05-.55.14-.3.23-.39.41c-.1.18-.15.39-.15.63s.05.46.15.64c.1.18.23.32.39.42s.35.14.55.14.38-.05.55-.14.3-.24.4-.42.15-.39.15-.64-.05-.45-.15-.63Z"/>-->
        <!--    <path class="cls-1" d="M57,68.66v.43h-1.12v3.66h-.53v-3.66h-1.12v-.43h2.77Z"/>-->
        <!--    <path class="cls-1" d="M59.91,69.64c.2.1.35.26.46.47s.17.46.17.76v1.9h-.53v-1.82c0-.32-.08-.57-.24-.74-.16-.17-.38-.26-.66-.26s-.51.09-.67.26c-.17.18-.25.43-.25.77v1.78h-.53v-4.35h.53v1.59c.11-.16.25-.29.44-.38s.39-.14.63-.14c.24,0,.46.05.66.16Z"/>-->
        <!--    <path class="cls-1" d="M64.33,71.35h-2.57c.02.32.13.57.33.74s.44.27.72.27c.23,0,.42-.05.58-.16.15-.11.26-.25.33-.43h.58c-.09.31-.26.56-.52.76-.26.19-.58.29-.96.29-.31,0-.58-.07-.82-.21s-.43-.33-.57-.58c-.14-.25-.21-.55-.21-.88s.07-.62.2-.88c.13-.25.32-.44.56-.58s.52-.2.83-.2.58.07.81.2c.23.13.42.32.54.55.13.23.19.5.19.79,0,.1,0,.21-.02.32ZM63.66,70.39c-.09-.15-.21-.26-.37-.33-.15-.08-.33-.11-.51-.11-.27,0-.5.09-.69.26s-.3.41-.33.72h2.03c0-.2-.04-.38-.14-.53Z"/>-->
        <!--    <path class="cls-1" d="M67.15,68.66v2.59c0,.36.09.63.27.81.18.18.43.26.74.26s.56-.09.74-.26c.18-.18.27-.45.27-.81v-2.59h.53v2.58c0,.34-.07.63-.21.86s-.32.41-.56.52c-.23.11-.49.17-.78.17s-.55-.06-.78-.17c-.23-.11-.42-.29-.55-.52s-.2-.52-.2-.86v-2.58h.53Z"/>-->
        <!--    <path class="cls-1" d="M73.11,69.84c.24.24.36.58.36,1.03v1.9h-.53v-1.82c0-.32-.08-.57-.24-.74-.16-.17-.38-.26-.66-.26s-.51.09-.67.26c-.17.18-.25.43-.25.77v1.78h-.53v-3.22h.53v.46c.11-.16.25-.29.43-.38.18-.09.38-.14.6-.14.39,0,.71.12.95.36Z"/>-->
        <!--    <path class="cls-1" d="M74.35,70.27c.13-.25.32-.44.56-.58.24-.14.51-.21.81-.21.4,0,.72.1.98.29.26.19.43.46.51.8h-.58c-.05-.2-.16-.35-.32-.46-.16-.11-.36-.17-.59-.17-.31,0-.55.1-.74.31s-.28.51-.28.89.09.69.28.9c.19.21.43.32.74.32.24,0,.43-.05.59-.16.16-.11.26-.27.32-.47h.58c-.09.33-.26.59-.52.79-.26.2-.58.3-.97.3-.31,0-.58-.07-.81-.21-.24-.14-.42-.33-.56-.58-.13-.25-.2-.54-.2-.88s.07-.62.2-.87Z"/>-->
        <!--    <path class="cls-1" d="M78.5,72.61c-.24-.14-.44-.33-.58-.58-.14-.25-.21-.55-.21-.88s.07-.62.21-.87c.14-.25.34-.45.58-.58s.52-.2.83-.2.58.07.83.2.44.33.58.58c.14.25.21.54.21.88s-.07.63-.22.88c-.15.25-.35.45-.6.58s-.53.21-.83.21-.57-.07-.82-.21ZM79.86,72.21c.17-.09.3-.23.41-.41.1-.18.16-.4.16-.66s-.05-.48-.15-.66-.23-.31-.4-.4c-.16-.09-.34-.13-.53-.13s-.37.04-.54.13c-.16.09-.29.22-.39.4-.1.18-.15.4-.15.66s.05.48.14.66c.1.18.22.31.39.4.16.09.34.13.53.13s.37-.04.54-.14Z"/>-->
        <!--    <path class="cls-1" d="M84.2,69.84c.24.24.36.58.36,1.03v1.9h-.53v-1.82c0-.32-.08-.57-.24-.74-.16-.17-.38-.26-.66-.26s-.51.09-.67.26c-.17.18-.25.43-.25.77v1.78h-.53v-3.22h.53v.46c.11-.16.25-.29.43-.38.18-.09.38-.14.6-.14.39,0,.71.12.95.36Z"/>-->
        <!--    <path class="cls-1" d="M87.96,69.84c.24.24.36.58.36,1.03v1.9h-.53v-1.82c0-.32-.08-.57-.24-.74-.16-.17-.38-.26-.66-.26s-.51.09-.67.26c-.17.18-.25.43-.25.77v1.78h-.53v-3.22h.53v.46c.11-.16.25-.29.43-.38.18-.09.38-.14.6-.14.39,0,.71.12.95.36Z"/>-->
        <!--    <path class="cls-1" d="M92.12,71.35h-2.57c.02.32.13.57.33.74s.44.27.72.27c.23,0,.42-.05.58-.16.15-.11.26-.25.33-.43h.58c-.09.31-.26.56-.52.76-.26.19-.58.29-.96.29-.31,0-.58-.07-.82-.21s-.43-.33-.57-.58c-.14-.25-.21-.55-.21-.88s.07-.62.2-.88c.13-.25.32-.44.56-.58s.52-.2.83-.2.58.07.81.2c.23.13.42.32.54.55.13.23.19.5.19.79,0,.1,0,.21-.02.32ZM91.45,70.39c-.09-.15-.21-.26-.37-.33-.15-.08-.33-.11-.51-.11-.27,0-.5.09-.69.26s-.3.41-.33.72h2.03c0-.2-.04-.38-.14-.53Z"/>-->
        <!--    <path class="cls-1" d="M92.84,70.27c.13-.25.32-.44.56-.58.24-.14.51-.21.81-.21.4,0,.72.1.98.29.26.19.43.46.51.8h-.58c-.05-.2-.16-.35-.32-.46-.16-.11-.36-.17-.59-.17-.31,0-.55.1-.74.31s-.28.51-.28.89.09.69.28.9c.19.21.43.32.74.32.24,0,.43-.05.59-.16.16-.11.26-.27.32-.47h.58c-.09.33-.26.59-.52.79-.26.2-.58.3-.97.3-.31,0-.58-.07-.81-.21-.24-.14-.42-.33-.56-.58-.13-.25-.2-.54-.2-.88s.07-.62.2-.87Z"/>-->
        <!--    <path class="cls-1" d="M97.06,69.98v1.9c0,.16.03.27.1.33.07.06.18.1.35.1h.39v.45h-.48c-.3,0-.52-.07-.67-.21-.15-.14-.22-.36-.22-.68v-1.9h-.42v-.44h.42v-.81h.53v.81h.84v.44h-.84Z"/>-->
        <!--    <path class="cls-1" d="M101.46,71.35h-2.57c.02.32.13.57.33.74s.44.27.72.27c.23,0,.42-.05.58-.16.15-.11.26-.25.33-.43h.58c-.09.31-.26.56-.52.76-.26.19-.58.29-.96.29-.31,0-.58-.07-.82-.21s-.43-.33-.57-.58c-.14-.25-.21-.55-.21-.88s.07-.62.2-.88c.13-.25.32-.44.56-.58s.52-.2.83-.2.58.07.81.2c.23.13.42.32.54.55.13.23.19.5.19.79,0,.1,0,.21-.02.32ZM100.79,70.39c-.09-.15-.21-.26-.37-.33-.15-.08-.33-.11-.51-.11-.27,0-.5.09-.69.26s-.3.41-.33.72h2.03c0-.2-.04-.38-.14-.53Z"/>-->
        <!--    <path class="cls-1" d="M102.19,70.27c.13-.25.32-.44.55-.58.23-.14.49-.21.78-.21.25,0,.48.06.7.17.22.12.38.27.49.46v-1.7h.54v4.35h-.54v-.6c-.11.19-.26.35-.47.47-.21.12-.45.19-.73.19s-.55-.07-.78-.21c-.23-.14-.42-.34-.55-.59-.13-.25-.2-.54-.2-.87s.07-.62.2-.87ZM104.56,70.51c-.1-.18-.23-.32-.4-.41s-.35-.14-.55-.14-.38.05-.55.14-.3.23-.39.41c-.1.18-.15.39-.15.63s.05.46.15.64c.1.18.23.32.39.42s.35.14.55.14.38-.05.55-.14.3-.24.4-.42.15-.39.15-.64-.05-.45-.15-.63Z"/>-->
        <!--  </g>-->
        <!--</svg>-->

        <span class="close-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                fill="none">
                <path
                    d="M19.5347 0.465273C19.2367 0.167359 18.8326 0 18.4112 0C17.9898 0 17.5857 0.167359 17.2877 0.465273L10 7.75298L2.71229 0.465273C2.41429 0.167359 2.01016 0 1.58878 0C1.1674 0 0.763278 0.167359 0.465273 0.465273C0.167359 0.763278 0 1.1674 0 1.58878C0 2.01016 0.167359 2.41429 0.465273 2.71229L7.75298 10L0.465273 17.2877C0.167359 17.5857 0 17.9898 0 18.4112C0 18.8326 0.167359 19.2367 0.465273 19.5347C0.763278 19.8326 1.1674 20 1.58878 20C2.01016 20 2.41429 19.8326 2.71229 19.5347L10 12.247L17.2877 19.5347C17.5857 19.8326 17.9898 20 18.4112 20C18.8326 20 19.2367 19.8326 19.5347 19.5347C19.8326 19.2367 20 18.8326 20 18.4112C20 17.9898 19.8326 17.5857 19.5347 17.2877L12.247 10L19.5347 2.71229C19.8326 2.41429 20 2.01016 20 1.58878C20 1.1674 19.8326 0.763278 19.5347 0.465273Z"
                    fill="#5970CC" />
            </svg>
        </span>
    </div>
    <div class="submenu-header" id='custum-close'>
        <a href="#" data-submenu-close="zeynep2">Categories</a>
    </div>

    <!-- Courses List -->
    <div class="mobile-course-nav">
        <ul class="list-unstyled">


        </ul>
    </div>

    <div class="mobile-course-list">

    </div>
    <!-- Courses List End -->

</div>
<!--navbar end-->
<!-- SnowFall -->
<!-- <script>
  const snowContainer = document.getElementById("snow");

  const snowflakeCount = window.innerWidth < 768 ? 40 : 80;

  for (let i = 0; i < snowflakeCount; i++) {
    const snowflake = document.createElement("div");
    snowflake.classList.add("snowflake");

    snowflake.style.left = Math.random() * 100 + "vw";
    snowflake.style.animationDuration = 6 + Math.random() * 6 + "s";
    snowflake.style.animationDelay = Math.random() * 5 + "s";

    const size = 4 + Math.random() * 6;
    snowflake.style.width = size + "px";
    snowflake.style.height = size + "px";

    snowflake.style.opacity = 0.6 + Math.random() * 0.4;

    snowContainer.appendChild(snowflake);
  }
</script> -->

<!-- SnowFall -->
<!-- Dynamic mobile menu -->
<script>
    const mobileMenuData = {

        navigation: [


            {
                id: "n1",
                title: "Data Science, ML & AI",
                active: true,
                menuCards: [{
                        // logo: "http://localhost/TIA/assets/dit/images/navbar/Eict-IITR-logo.webp",
                        // logo2:"http://localhost/TIA/assets/dit/images/ds_ml_ai_genai_by_eict_iitroorkee/academic-partner2.webp",
                        // logo2: "http://localhost/TIA/assets/dit/images/navbar/IITR-logo.webp",
                        logosm: "http://localhost/TIA/assets/dit/images/home/iitroorkee-logo-sm.webp",
                        // logolg: "http://localhost/TIA/assets/dit/images/home/iitroorkee-logo-lg.webp",
                        // title: "The IoT Academy",
                        description: "Professional Program In Data Science, Machine Learning, AI & GenAI",
                        duration: "10 Months",
                        trending: true,
                        href: "http://localhost/TIA/ds-ml-ai-genai-by-eict-iitroorkee",
                    },
                    // {
                    //     // logo: "http://localhost/TIA/assets/dit/images/navbar/Eict-IITR-logo.webp",
                    //     // logo2:"http://localhost/TIA/assets/dit/images/ds_ml_ai_genai_by_eict_iitroorkee/academic-partner2.webp",
                    //     // logo2: "http://localhost/TIA/assets/dit/images/navbar/IITR-logo.webp",
                    //     logosm: "http://localhost/TIA/assets/dit/images/home/iitroorkee-logo-sm.webp",
                    //     // logolg: "http://localhost/TIA/assets/dit/images/home/iitroorkee-logo-lg.webp",
                    //     // title: "The IoT Academy",
                    //     description: "Applied Data Science & Machine Learning Program",
                    //     duration: "06 Months",
                    //     trending: true,
                    //     href: "http://localhost/TIA/ds-ml-by-eict-iitroorkee",
                    // },
                    // {
                    //     // logo4: "http://localhost/TIA/assets/dit/images/navbar/IITG-logo.webp",
                    //     logo5: "http://localhost/TIA/assets/dit/images/navbar/EICT-IITGuwahati-logo.webp",
                    //     // title: "IIT Roorkee",
                    //     description: "Advanced Certification In Data Science, Machine Learning and AI",
                    //     duration: "09 Months",
                    //     closed: true,
                    //     href: "http://localhost/TIA/advanced-certification-in-data-science-machine-learning-and-ai-by-eict-iitg",
                    // },
                    // {
                    //     // logo4: "http://localhost/TIA/assets/dit/images/navbar/IITG-logo.webp",
                    //     logo5: "http://localhost/TIA/assets/dit/images/navbar/EICT-IITGuwahati-logo.webp",
                    //     // title: "IIT Roorkee",
                    //     description: "Advanced Certification In Data Science, Machine Learning and Edge AI",
                    //     duration: "06 Months",
                    //     closed: true,
                    //     href: "http://localhost/TIA/online-certification-in-applied-data-science-machine-learning-edge-ai-by-eict-academy-iit-guwahati",
                    // },

                ]
            },
            {
                id: "n2",
                title: "Data Analytics & Generative AI",
                active: false,
                menuCards: [{
                        // logo: "http://localhost/TIA/assets/dit/images/navbar/Eict-IITR-logo.webp",
                        // logo2: "http://localhost/TIA/assets/dit/images/ds_ml_ai_genai_by_eict_iitroorkee/academic-partner2.webp",
                        logosm: "http://localhost/TIA/assets/dit/images/home/iitroorkee-logo-sm.webp",
                        // logolg: "http://localhost/TIA/assets/dit/images/home/iitroorkee-logo-lg.webp",
                        // title: "The IoT Academy",
                        description: "Advanced Program in Data Analytics & Generative AI",
                        duration: "6 Months",
                        trending: true,
                        href: "http://localhost/TIA/data-analytics-and-gen-ai-by-eict-iit-roorkee",
                    },
                    // {
                    //     //  logo4: "http://localhost/TIA/assets/dit/images/navbar/IITG-logo.webp",
                    //     logo5: "http://localhost/TIA/assets/dit/images/navbar/EICT-IITGuwahati-logo.webp",
                    //     // logo2: "http://localhost/TIA/assets/dit/images/navbar/IITR-logo.webp",
                    //     // title: "IIT Roorkee",
                    //     description: "Advanced Generative AI Leadership Program",
                    //     duration: "6 Months",
                    //     closed: true,
                    //     href: "http://localhost/TIA/advanced-generative-ai-course",
                    // },

                ]
            },
/*           
            {
                id: "sn9",
                title: "Job Bootcamp Program",
                new: true,
                menuCards: [{
                    logo: "http://localhost/TIA/assets/dit/images/navbar/nav-iot.webp",
                    // logo3: "https://www.upskillcampus.com/assets/dit/img/logo.svg",
                    // logo3:"http://localhost/TIA/assets/dit/images/data-analyst-gen-ai/nasscom-logo.webp",
                    // title: "Upskill Campus",
                    description: "Data Analytics & Generative AI Job Bootcamp",
                    duration: "6 Months",
                    href: "http://localhost/TIA/da-with-genai-job-bootcamp",
                }, ]
            },
            
*/
            {
                id: "n3",
                title: "Embedded Systems & IoT",
                active: false,
                menuCards: [{
                        logo: "http://localhost/TIA/assets/dit/images/navbar/nav-iot.webp",
                        // logo3: "http://localhost/TIA/assets/dit/images/data-analyst-gen-ai/nasscom-logo.webp",
                        // title: "The IoT Academy",
                        description: "AI-Powered Embedded & IoT Program ",
                        duration: "6 Months",
                        href: "http://localhost/TIA/embedded-systems-training-with-ai",
                    },
                    {
                        logo: "http://localhost/TIA/assets/dit/images/navbar/nav-iot.webp",
                        // logo3: "http://localhost/TIA/assets/dit/images/data-analyst-gen-ai/nasscom-logo.webp",
                        // title: "The IoT Academy",
                        description: "Embedded Systems and IoT Program",
                        duration: "4 Months",
                        href: "http://localhost/TIA/embedded-systems-training",
                    },

                ]
            },
            {
                id: "sn2",
                title: "Data Analyst",
                banner: {
                    bannerImg: "http://localhost/TIA/assets/dit/images/navbar/new-megamenu-banner-data-analytics.webp",
                    alt: "Mega Menu Banner"
                },
                menuCards: [{
                        logo: "http://localhost/TIA/assets/dit/images/navbar/nav-iot.webp",
                        // logo3: "http://localhost/TIA/assets/dit/images/data-analyst-gen-ai/nasscom-logo.webp",
                        // title: "The IoT Academy",
                        description: "Data Analytics with Gen AI & Agentic AI",
                        duration: "6 Months",
                        href: "http://localhost/TIA/data-analyst-with-genai-and-agenticai-course",
                    },
                    {
                        logo: "http://localhost/TIA/assets/dit/images/navbar/nav-iot.webp",
                        // logo3: "http://localhost/TIA/assets/dit/images/data-analyst-gen-ai/nasscom-logo.webp",
                        // title: "The IoT Academy",
                        description: "Data Analytics, Data Visualisation & Business Analytics",
                        duration: "4  Months",
                        href: "http://localhost/TIA/data-analyst-certification-course",
                    },
                ]
            },
            {
                id: "Gn1",
                title: "E&ICT Academy, IIT Guwahati Programs",
                banner: {
                    bannerImg: "http://localhost/TIA/assets/dit/images/navbar/new-megamenu-banner-data-analytics.webp",
                    alt: "Mega Menu Banner"
                },
                menuCards: [{
                    // logo4: "http://localhost/TIA/assets/dit/images/navbar/IITG-logo.webp",
                    logo5: "http://localhost/TIA/assets/dit/images/navbar/EICT-IITGuwahati-logo.webp",
                    // title: "IIT Roorkee",
                    description: "Advanced Certification In Data Science, Machine Learning and AI",
                    duration: "09 Months",
                    closed: true,
                    href: "http://localhost/TIA/advanced-certification-in-data-science-machine-learning-and-ai-by-eict-iitg",
                }, ]
            },
            {
                id: "sn3",
                title: "Short Term Programs",
                menuCards: [{
                    logo: "http://localhost/TIA/assets/dit/images/navbar/nav-iot.webp",
                    // logo3: "http://localhost/TIA/assets/dit/images/data-analyst-gen-ai/nasscom-logo.webp",
                    // title: "The IoT Academy",
                    description: "Career AccelX Short Term Programs",
                    duration: "4-6 Weeks",
                    href: "http://localhost/TIA/short-term-programs",
                }]
            },
            {
                id: "sn4",
                title: "Java Full Stack",
                menuCards: [{
                    logo: "http://localhost/TIA/assets/dit/images/navbar/nav-iot.webp",
                    // logo3: "http://localhost/TIA/assets/dit/images/data-analyst-gen-ai/nasscom-logo.webp",
                    // title: "The IoT Academy",
                    description: "Certification Course for Java Full Stack Development",
                    duration: "6 Months",
                    href: "http://localhost/TIA/java-certification-course-in-noida",
                }]
            },
            {
                id: "sn5",
                title: "Digital Marketing",
                menuCards: [{
                    logo: "http://localhost/TIA/assets/dit/images/navbar/nav-iot.webp",
                    // logo3: "http://localhost/TIA/assets/dit/images/data-analyst-gen-ai/nasscom-logo.webp",
                    // title: "The IoT Academy",
                    description: "Digital Marketing Certification Course",
                    duration: "3/7 Months",
                    href: "http://localhost/TIA/digital-marketing-training",
                }]
            },
            {
                id: "sn6",
                title: "Python",
                menuCards: [{
                    logo: "http://localhost/TIA/assets/dit/images/navbar/nav-iot.webp",
                    // logo3: "http://localhost/TIA/assets/dit/images/data-analyst-gen-ai/nasscom-logo.webp",
                    // title: "The IoT Academy",
                    description: "Python Certification Course",
                    duration: "45 Days",
                    href: "http://localhost/TIA/python-training",
                }]
            },
            {
                id: "sn7",
                title: "Self Paced Course",
                menuCards: [{
                    logo: "http://localhost/TIA/assets/dit/images/navbar/nav-iot.webp",
                    // logo3:"http://localhost/TIA/assets/dit/images/data-analyst-gen-ai/nasscom-logo.webp",
                    description: "Data Analytics, Machine Learning & Generative AI Course",
                    title: "The IoT Academy",
                    duration: "6 Months",
                    href: "http://localhost/TIA/data-analytics-machine-learning-ai-course",
                }]
            },
            {
                id: "sn8",
                title: "Summer Training / Internship",
                new: true,
                menuCards: [{
                        logo: "http://localhost/TIA/assets/dit/images/navbar/nav-iot.webp",
                        logo3: "https://www.upskillcampus.com/assets/dit/img/logo.svg",
                        // logo3:"http://localhost/TIA/assets/dit/images/data-analyst-gen-ai/nasscom-logo.webp",
                        // title: "Upskill Campus",
                        description: "Summer Internship Program 2026",
                        duration: "6 Weeks",
                        href: "https://www.upskillcampus.com/internships/free",
                    },
                    {
                        logo: "http://localhost/TIA/assets/dit/images/navbar/nav-iot.webp",
                        logo3: "https://www.upskillcampus.com/assets/dit/img/logo.svg",
                        // logo3:"http://localhost/TIA/assets/dit/images/data-analyst-gen-ai/nasscom-logo.webp",
                        // title: "The IoT Academy",
                        description: "Industrial Training Program 2026",
                        duration: "6 Weeks",
                        href: "http://localhost/TIA/industrial-summer-training-programs",
                        trending: true,
                    }
                ]
            },
        ]

    };
    const navigationList = document.querySelector('.mobile-course-nav ul')
    const trendingBadge = "http://localhost/TIA/assets/dit/images/navbar/tranding-badge2.webp";
    const closedBadge = "http://localhost/TIA/assets/dit/images/navbar/closed-badge2.webp";
    const renderMobileNavigation = () => {
        const navigation = mobileMenuData?.navigation;

        navigationList.innerHTML = '';
        navigation.map(nav => {
            navigationList.innerHTML += `
        <li data-id="${nav.id}" class="${nav.active ? 'active' : ''}">
            ${nav.title}
            ${nav.new ? `<span class="new-badge">NEW</span>` : ""}
        </li>
        `;
        });
    }
    navigationList?.addEventListener('click', (e) => {
        const li = e.target.closest('li');
        if (!li) return;
        const clickedId = li.dataset.id;
        // update JSON
        mobileMenuData.navigation.forEach(nav => {
            nav.active = nav.id === clickedId;
        });
        // re-render UI
        renderMobileNavigation();
        renderCards();
        console.log(mobileMenuData);
    });

    const renderCards = () => {
        const cardsList = document.querySelector('.mobile-course-list');
        cardsList.innerHTML = '';
        const cards = mobileMenuData?.navigation?.find(nav => nav.active).menuCards;
        cards?.map(card => {
            // card design will be here
            cardsList.innerHTML += `
                <a href="${card.href}" class="text-decoration-none text-black">
            <div class="megamenu-card position-relative">
                <div class="d-flex align-items-center gap-3">
                         
                        <img loading="lazy" src="${card?.logo2}" class="${card?.logo2 ? 'd-block' : 'd-none'} img-fluid"
                        style=" max-width: 50px" />
                        <img loading="lazy" src="${card?.logo}" class="${card?.logo ? 'd-block' : 'd-none'} img-fluid"
                         style="max-width: 96px" />
                         <img loading="lazy"  src="${card?.logo3}" class="${card?.logo3 ? 'd-block' : 'd-none'} img-fluid"
                         style=" max-height:30px" />
                        <img loading="lazy" src="${card?.logo4}" class="${card?.logo4 ? 'd-block' : 'd-none'} img-fluid"
                         style="max-height:50px" />
                         <img loading="lazy" src="${card?.logo5}" class="${card?.logo5 ? 'd-block' : 'd-none'} img-fluid"
                         style="max-height:37px" />
                         <img loading="lazy" src="${card?.logosm}" class="${card?.logosm ? 'd-block' : 'd-none'} img-fluid"
                         style="max-height:50px" />
                         <img loading="lazy" src="${card?.logolg}" class="${card?.logolg ? 'd-block' : 'd-none'} img-fluid"
                         style="max-height:50px" />
                         <h5 class="${card?.title ? 'd-block' : 'd-none'} m-0 fs-5" >${card.title}</h5>
                         </div>
                         

                <div class="mt-3 d-flex flex-column gap-3 align-items-start ">
                    <p class="m-0 desc" style="">${card.description}</p>
                    <span class="duration ">
                        ${card?.duration}
                    </span>
                </div>

                <div class="${card?.trending || card?.closed ? "d-flex align-items-end position-absolute bottom-0 end-0" : "d-none"}">
                        
                        <div class="position-relative">
                        
                      <img loading="lazy" class="shiner position-absolute " src="http://localhost/TIA/assets/dit/images/navbar/shiner02.png" />

                        <img loading="lazy" src="${card?.trending ? trendingBadge : closedBadge}" class="img-fluid trending-badge" style=" max-width: 140px " alt="trending badge" />

                        </div>
                    
                    
                </div>
            </div>
            </a>
                
                `

        });
    }

    function loadMobileScript() {
        renderMobileNavigation();
        renderCards();

    }
    loadMobileScript();
</script>


    


    <!-- Main Content -->

    <main class="page-wrapper">

        <div class="receipt-container">



            <div class="receipt-card">



                <!-- Success Checkmark Badge -->

                <div class="success-icon-wrap">

                    <div class="success-icon-circle">

                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">

                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>

                            <polyline points="22 4 12 14.01 9 11.01"/>

                        </svg>

                    </div>

                </div>



                <h1 class="receipt-title">Enrollment Successful!</h1>

                <p class="receipt-subtitle">

                    Thank you for applying. Your course enrollment request has been registered and is now under review. Please save your reference ID for all future correspondence.

                </p>



                <!-- Reference ID Card -->

                <div class="ref-box">

                    <div class="ref-info">

                        <span class="ref-label">Enrollment Reference ID</span>

                       <span class="ref-code" id="refCodeText">
    <?= e($referenceId) ?>
</span>
                    </div>

                    <button type="button" class="btn-copy" id="copyBtn" onclick="copyRefId()" title="Copy Reference ID to clipboard">

                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">

                            <rect width="14" height="14" x="8" y="8" rx="2" ry="2"/>

                            <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/>

                        </svg>

                        <span id="copyBtnText">Copy</span>

                    </button>

                </div>



                <!-- Course Highlight Banner -->

                <div class="course-highlight">

                    <div class="course-highlight-left">

                        <div class="course-icon-badge">

                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">

                                <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>

                                <path d="M6 2v20"/>

                            </svg>

                        </div>

                        <div class="course-meta-text">

                            <span class="course-meta-label">Selected Course</span>

                          <span class="course-meta-title">
    <?= e($enrollment['course_name']) ?>
</span>

                        </div>

                    </div>

                    

                </div>



                <!-- Details Grid Section -->

                <div class="details-section">

                    <h2 class="section-subhead">

                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">

                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>

                            <polyline points="14 2 14 8 20 8"/>

                            <line x1="16" y1="13" x2="8" y2="13"/>

                            <line x1="16" y1="17" x2="8" y2="17"/>

                        </svg>

                        Applicant & Registration Details

                    </h2>



                    <div class="details-grid">

                        <div class="detail-item">

                            <span class="detail-label">Full Name</span>

                      <span class="detail-value highlight">
    <?= e($enrollment['full_name']) ?>
</span>

                        </div>



                        <div class="detail-item">

                            <span class="detail-label">Email Address</span>

                                                 <span class="detail-value highlight">
    <?= e($enrollment['email']) ?>
</span>

                        </div>



     <div class="detail-item">
    <span class="detail-label">Primary Phone</span>

    <span class="detail-value highlight">
        <?= e($phone) ?>
    </span>
</div>



                        <div class="detail-item">

                            <span class="detail-label">Gender / DOB</span>

                                               <span class="detail-value highlight">
    <?= e($enrollment['gender']) ?>
</span>

                        </div>



                        <div class="detail-item">

                            <span class="detail-label">Location</span>

                                             <span class="detail-value highlight">
    <?= e($enrollment['city'] . ', ' . $enrollment['state'] . ', ' . $enrollment['country']) ?>
</span>

                        </div>



                        <div class="detail-item">

                            <span class="detail-label">Education</span>

                                              <span class="detail-value highlight">
    <?= e($enrollment['highest_qualification'] . ' (' . $enrollment['area_of_study'] . ')') ?>
</span>
                        </div>



                        <div class="detail-item">

                            <span class="detail-label">Professional Experience</span>

                          <span class="detail-value">
    <?= e($enrollment['years_of_experience']) ?> Years
</span>

                        </div>



                        <div class="detail-item">

                            <span class="detail-label">Submission Date</span>

                       <span class="detail-value text-muted">
    <?= e($enrollment['created_at']) ?>
</span>
                        </div>

                    </div>

                </div>



                <!-- Action Buttons -->

                <div class="actions-row">
                    <a href="http://localhost/TIA/data-analyst-certification-course" class="btn-action-outline" id="btnBackToSource" onclick="handleGoBack(event)">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="19" y1="12" x2="5" y2="12"/>
                            <polyline points="12 19 5 12 12 5"/>
                        </svg>
                        <span id="btnBackText">Return to Data Analytics Program</span>
                    </a>

                    <a href="https://rzp.io/rzp/ct0r3Jb" class="btn-action-print" target="_blank" rel="noopener noreferrer">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="20" height="14" x="2" y="5" rx="2"/>
                            <line x1="2" y1="10" x2="22" y2="10"/>
                        </svg>
                        <span>Pay Now</span>
                    </a>
                </div>



            </div>



        </div>

    </main>



    <!-- Site Footer -->

    
        <!--footer-->
<footer id="rs-footer" class="comonListActive">
    <div class="container-fluid">
        <div class="row gy-4">
            <div class="col-lg-3 f-top">
                <img decoding="async" loading="lazy"
                    src="http://localhost/TIA/assets/dit/images/navbar/footer-logo.svg" alt="iot-logo"
                    class="img-fluid footer-logo tiot" width="205" height="125">
                <p class="footer-logo-copyright">Copyright &copy; The IoT Academy</p>
                <p class="f-head">CONTACT US</p>
                <ul>
                    <li><a href="tel:+919354068856" aria-label="tel"><img
                                src="http://localhost/TIA/assets/dit/images/navbar/call-icon.svg"
                                alt="call-icon" class="img-fluid" width="24" height="24" loading="lazy">
                            +91-9354068856</a></li>
                    <li><a href="mailto:info@theiotacademy.co" aria-label="mail">
                            <img src="http://localhost/TIA/assets/dit/images/navbar/mail-icon.svg"
                                alt="mail-icon" class="img-fluid" width="24" height="24" loading="lazy">
                            info@theiotacademy.co</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-4 wi">
                <p class="f-head">RESOURCES</p>
                <ul class="a-underline">
                    <li><a href="http://localhost/TIA/blog/" target="_blank">Blog</a></li>
                    <li><a href="http://localhost/TIA/salary-predictor" target="_blank"
                            rel="nofollow noopener">Salary Predictor</a></li>
                    <li><a href="https://www.forum.upskillcampus.com/" target="_blank"
                            rel="nofollow noopener">Discussion Forum</a></li>
                    <li><a href="https://job.uctconsulting.com/" target="_blank" rel="nofollow noopener">Job Portal</a>
                    </li>
                    <li><a href="https://www.upskillcampus.com/ticket-to-corporate" target="_blank"
                            rel="nofollow noopener">Ticket To Corporate</a></li>
                    <li><a href="http://localhost/TIA/industrial-visit" target="_blank"
                            rel="nofollow noopener">Industrial Visit </a></li>
                    <!-- <li><a href="http://localhost/TIA/welcome-kit" target="_blank">Welcome Kit</a></li> -->
                </ul>
            </div>
            <div class="col-lg-3 col-md-4 wi">
                <p class="f-head">QUICK LINKS</p>
                <ul class="a-underline">
                    <li><a href="http://localhost/TIA/verify-certificate" target="_blank">Verify Certificate</a>
                    </li>
                    <li><a href="http://localhost/TIA/startup-seed-fund-program" target="_blank">Startup Seed
                            Fund Program</a>
                    </li>
                    <li><a href="http://localhost/TIA/about-us" target="_blank">About Us</a></li>
                    <li><a href="http://localhost/TIA/contact" target="_blank">Contact Us</a></li>
                    <li><a href="http://localhost/TIA/events" target="_blank">Events</a></li>
                    <li><a href="http://localhost/TIA/our-placements" target="_blank">Our Placements</a></li>
                    <li><a href="http://localhost/TIA/short-term-courses" target="_blank">Short Term Courses</a>
                    </li>
                    <li><a href="http://localhost/TIA/all-courses" target="_blank">All Courses</a></li>


                    <!--<li><a href="http://localhost/TIA/instructors-apply" target="_blank">Become An Instructor</a></li>-->
                </ul>
            </div>
            <div class="col-lg-3 col-md-4">
                <p class="f-head">TRENDING COURSES</p>
                <ul class="a-underline">
                    <li><a href="http://localhost/TIA/ds-ml-ai-genai-by-eict-iitroorkee" target="_blank">Professional Program In Data Science, Machine Learning, AI & GenAI by E&ICT Academy, IIT Roorkee</a></li>
                    <li><a href="http://localhost/TIA/data-analytics-and-gen-ai-by-eict-iit-roorkee"
                            target="_blank">Advanced Program in Applied Data Analytics & Generative AI by E&ICT Academy, IIT Roorkee</a></li>
                    <li><a href="http://localhost/TIA/embedded-systems-training-with-ai" target="_blank">AI-Powered Embedded & IoT Program</a></li>
                    
                    <li><a href="http://localhost/TIA/data-analyst-with-genai-and-agenticai-course" target="_blank">Master Data Analytics + Gen AI & Agentic AI</a></li>
                    <!-- <li><a href="http://localhost/TIA/digital-marketing-training" target="_blank">Digital
                            Marketing</a></li> -->
                </ul>
            </div>
        </div>
        <div class="social-media">
            <p class="f-head">FOLLOW US</p>
            <ul class="a-underline">
                <li><a href="https://www.facebook.com/academyforiot/" aria-label="facebook" target="_blank"
                        rel="nofollow noopener"><img loading="lazy"
                            src="http://localhost/TIA/assets/dit/images/navbar/facebook-icon.svg" alt="facebook"
                            class="img-fluid" width="25" height="24"></a></li>
                <li><a href="https://instagram.com/the_iot_academy" aria-label="instagram" target="_blank"
                        rel="nofollow noopener"><img loading="lazy"
                            src="http://localhost/TIA/assets/dit/images/navbar/instagram-icon.svg"
                            alt="instagram" class="img-fluid" width="25" height="24"></a></li>
                <li><a href="https://www.youtube.com/channel/UCyIXtUMsOX02mpi5PKex9aA" aria-label="youtube"
                        target="_blank" rel="nofollow noopener"><img loading="lazy"
                            src="http://localhost/TIA/assets/dit/images/navbar/youtube-icon.svg" alt="youtube"
                            class="img-fluid" width="25" height="24"></a></li>
                <li><a href="https://twitter.com/academyforiot" aria-label="twitter" target="_blank"
                        rel="nofollow noopener"><img loading="lazy"
                            src="http://localhost/TIA/assets/dit/images/navbar/twitter-icon.svg" alt="twitter"
                            class="img-fluid" width="24" height="24"></a></li>
                <li><a href="https://www.linkedin.com/school/theiotacademy/" aria-label="linkedin" target="_blank"
                        rel="nofollow noopener"><img loading="lazy"
                            src="http://localhost/TIA/assets/dit/images/navbar/linkedin-icon.svg" alt="linkedin"
                            class="img-fluid" width="25" height="24"></a></li>
            </ul>
        </div>
    </div>
    <div class="copyright">
        <div class="container-fluid">
            <ul>
                <li><a href="http://localhost/TIA/term-and-condition" target="_blank">Term & Conditions</a></li>
                <li><a href="http://localhost/TIA/privacy-policies" target="_blank">Privacy Policy</a></li>
                <li><a href="http://localhost/TIA/refund-policies" target="_blank">Refund Policy</a></li>
                <li><a href="http://localhost/TIA/disclaimer" target="_blank">Disclaimer</a></li>
            </ul>
            <p>&copy; 2013 -
                2026 <a href="http://localhost/TIA/" class="a-underline">The
                    IoT Academy</a>. All Rights Reserved, The EdTech Entity of <a href="https://www.uniconvergetech.in/"
                    class="a-underline">UniConverge Technologies Pvt. Ltd</a>
            </p>
        </div>
    </div>
</footer>
<!--go to top-->
<div id="scrollUp" class="d-none justify-content-center align-items-center">
    <svg xmlns="http://www.w3.org/2000/svg" width="43" height="34" viewBox="0 0 43 34" fill="none">
        <path
            d="M0.0494873 13.4556C0.0494873 13.1064 0.0299923 12.7557 0.0539861 12.408C0.103473 11.6467 0.626839 11.1057 1.37365 11.0113C2.07847 10.9214 2.7533 11.3769 2.94375 12.1098C3.02473 12.42 3.04872 12.7497 3.05772 13.0704C3.33665 22.1433 10.1929 29.7056 19.1801 30.8446C28.6787 32.048 37.5789 25.7581 39.5869 16.3735C39.8463 15.1626 39.8778 13.9022 39.9873 12.6628C40.0982 11.4144 41.019 10.707 42.0597 11.1192C42.826 11.4219 43.0105 12.0498 43.0015 12.8172C42.9505 16.7272 41.9338 20.3689 39.8583 23.6735C36.1857 29.5153 30.8921 32.9547 24.0434 33.8329C13.0227 35.2462 2.43388 27.5056 0.422892 16.6477C0.227942 15.5957 0.139464 14.5256 0 13.4631C0.0164958 13.4601 0.0329916 13.4571 0.0494873 13.4541V13.4556Z"
            fill="white" />
        <path
            d="M20.315 4.37664C19.9687 4.76202 19.6415 5.16397 19.2737 5.52838C18.0732 6.72097 16.8615 7.90252 15.6475 9.08186C14.9581 9.75214 14.051 9.79631 13.4486 9.20553C12.819 8.58826 12.8495 7.75344 13.5569 7.05666C15.6249 5.02152 17.693 2.98749 19.7803 0.972234C21.1556 -0.355076 22.9032 -0.31974 24.3248 1.05616C26.3703 3.03498 28.3967 5.03146 30.4309 7.02022C30.458 7.04672 30.485 7.07101 30.511 7.09862C31.1496 7.77221 31.162 8.62691 30.5448 9.21768C29.9672 9.76981 29.0635 9.76208 28.4226 9.14701C26.948 7.72915 25.4948 6.2892 24.0315 4.8603C23.8916 4.72448 23.746 4.59418 23.4674 4.33799C23.4674 4.72006 23.4674 4.92987 23.4674 5.13968C23.4674 10.217 23.4696 15.2944 23.4651 20.3706C23.4651 21.4451 22.8355 22.0745 21.8709 21.9939C21.1759 21.9353 20.6389 21.4451 20.5554 20.7681C20.5238 20.5142 20.5339 20.2536 20.5339 19.9963C20.5339 15.0481 20.5339 10.1 20.5339 5.15072C20.5339 4.9365 20.5339 4.72227 20.5339 4.50694L20.315 4.37664Z"
            fill="white" />
    </svg>
</div>
<!-- Footer End -->
<!-- global popup -->
<div class="modal fade tiamodal-dialog" aria-label="global modal" id="OfferpopupmdModal" tabindex="-1"
    aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">

            <div class="modal-body p-0">
                <h5 id="exampleModalLabel" class="visually-hidden">Special Offer</h5>
                <div class="row">
                    <div class="col-sm-12">
                        <button type="button" class="btn-close dmdemopopbtnr" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                        <div class="row m-0 offerpopup_row ">

                            <div class="col-lg-6  px-0 py-3 py-lg-4">
                                <!-- <img src="assets/dit/images/poppup-left-bg.webp" class="img-fluid w-100" alt="" /> -->
                                <h2 class="heading-modal mb-0">100% Placement</h1>
                                <p class="subheading-modal">And Job Assistance Programs</p>
                                <p class="white-strip text-center text-black mb-3 mb-lg-4">
                                    Join the elite certification programs led by <br><strong>E&ICT Academy IIT
                                        Roorkee</strong>
                                </p>
                                
                                <!-- Start flags -->
                                <div class="flag">
                                <img src="assets/dit/images/lflag.svg" class="lflag" alt="" />
                                <img src="assets/dit/images/rflag.svg" class="rflag" alt="" />
                                </div>
                                <!-- End flags-->
                                

                                <div class="feature-grid">
                                    <div class="feature">
                                        <div class="d-flex justify-content-center">
                                            <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M16.0472 5.60729C16.2722 5.83226 16.2722 6.20039 16.0472 6.42536L15.7648 6.70783C15.6996 6.69697 15.6326 6.69123 15.5644 6.69123H13.0531C12.9848 6.69123 12.9179 6.69697 12.8527 6.70783L12.5702 6.42536C12.3453 6.20039 12.3453 5.83226 12.5702 5.60729C12.7616 5.41593 13.0645 5.38359 13.2922 5.52974C13.3768 5.58401 13.4739 5.5927 13.5667 5.55425C13.6596 5.51581 13.7221 5.44105 13.7435 5.34285C13.8002 5.08317 14.0327 4.88717 14.3088 4.88713C14.5798 4.88713 14.8163 5.07821 14.8741 5.34285C14.8955 5.44105 14.9581 5.51581 15.0509 5.55425C15.1437 5.59274 15.2409 5.58397 15.3254 5.52974C15.5489 5.38621 15.852 5.41208 16.0472 5.60729ZM8.28944 18.9284L5.95359 14.8011C5.84064 14.6015 5.58493 14.5306 5.38537 14.6436L4.59087 15.0932C4.39131 15.2062 4.32044 15.4619 4.43336 15.6614L6.76924 19.7888C6.88216 19.9883 7.13787 20.0592 7.33742 19.9462L8.13193 19.4966C8.33149 19.3836 8.40239 19.1279 8.28944 18.9284ZM5.5 15.9811C5.345 16.0038 5.20093 15.8965 5.17827 15.7415C5.1556 15.5865 5.26286 15.4425 5.41791 15.4197C5.57296 15.3971 5.71698 15.5044 5.73965 15.6594C5.76231 15.8144 5.65501 15.9585 5.5 15.9811ZM12.9135 15.7912C13.2118 15.8275 13.4555 15.7426 13.5387 15.4319C13.6128 15.1553 13.4375 14.8971 13.1777 14.8051C12.282 14.6405 11.4156 14.3273 10.7342 14.081C9.76643 13.7311 9.52035 13.6472 8.56519 14.2003L6.90954 15.1588L8.31739 17.6464L8.35231 17.6263C8.43018 17.5815 8.51879 17.5725 8.59924 17.5939L12.509 18.6405C12.9829 18.7676 13.4341 18.7114 13.8534 18.4533L19.2434 15.1344C19.3997 15.0381 19.4763 14.8118 19.4533 14.5895C19.4347 14.4101 19.3473 14.2455 19.1823 14.1619C18.9118 14.0249 18.4377 14.0684 17.6987 14.4988L14.7286 16.2287C14.4866 16.3696 14.2374 16.4593 13.9786 16.5027C13.2361 16.6275 12.1258 16.2805 11.4594 16.0902C11.2472 16.0296 11.0317 15.968 10.8226 15.912C10.6479 15.8653 10.5441 15.6859 10.5908 15.5111C10.6375 15.3364 10.8169 15.2326 10.9916 15.2793C11.6311 15.4506 12.2654 15.6567 12.9135 15.7912ZM13.0531 8.47283H15.5644C15.874 8.47283 16.1273 8.2195 16.1273 7.90994C16.1273 7.60029 15.874 7.347 15.5644 7.347H13.0531C12.7434 7.347 12.4901 7.60029 12.4901 7.90989C12.4901 8.21954 12.7434 8.47283 13.0531 8.47283ZM0.163942 11.7322H10.0203C9.92816 12.0215 9.86844 12.319 9.84537 12.6217H0.163942C0.0737329 12.6217 1.7069e-08 12.5479 1.7069e-08 12.4578V11.8961C-4.09684e-05 11.806 0.0737329 11.7322 0.163942 11.7322ZM1.14792 8.52234H2.1653C2.34589 8.52234 2.49319 8.66964 2.49319 8.85022V11.0764H0.820037V8.85022C0.820037 8.66964 0.967298 8.52234 1.14792 8.52234ZM10.4682 3.50473H11.4856C11.6662 3.50473 11.8135 3.65204 11.8135 3.83262V5.61192C11.7234 5.87337 11.7234 6.15936 11.8135 6.42081V8.9365C11.2841 9.5521 10.7795 10.2086 10.3935 10.8862C10.3578 10.949 10.3235 11.0125 10.2909 11.0765H10.1403V3.83258C10.1403 3.65199 10.2876 3.50473 10.4682 3.50473ZM8.37891 5.08448H7.36152C7.18094 5.08448 7.03364 5.23178 7.03364 5.41236V11.0764H8.70679V5.41236C8.70679 5.23178 8.55949 5.08448 8.37891 5.08448ZM5.27208 6.84058C4.93293 6.84058 4.59382 6.84058 4.25466 6.84058C4.07408 6.84058 3.92678 6.98788 3.92678 7.16847V11.0764H5.59997V7.16847C5.60001 6.98788 5.45271 6.84058 5.27208 6.84058ZM0.75151 7.24646C2.60426 6.60123 4.51312 5.77205 6.40238 4.69524C8.08861 3.73417 9.75873 2.57654 11.3589 1.1775L11.2964 1.60698C11.2709 1.78593 11.3954 1.95167 11.5743 1.97712C11.7532 2.00258 11.919 1.87814 11.9444 1.6992L12.1373 0.373116C12.1658 0.170893 12.0028 -0.0100574 11.7968 0.000434849L10.4587 0.0730611C10.2783 0.0822418 10.1396 0.235937 10.1488 0.416314C10.158 0.596692 10.3117 0.735468 10.492 0.726247L10.9064 0.703746C9.34653 2.06491 7.72039 3.19136 6.07958 4.12657C4.22654 5.18272 2.354 5.99612 0.536295 6.62914C0.365836 6.68857 0.275791 6.87493 0.33522 7.04539C0.394649 7.21585 0.58101 7.30585 0.75151 7.24646ZM17.6541 11.2107C17.2873 10.5668 16.7428 9.85338 16.0206 9.03978C15.8796 9.097 15.7256 9.1286 15.5644 9.1286H13.0531C12.8918 9.1286 12.7378 9.097 12.5968 9.03978C11.8746 9.85338 11.3301 10.5667 10.9634 11.2107C10.5552 11.9273 10.4281 12.6471 10.5178 13.3094C10.6544 13.3567 10.8008 13.4097 10.957 13.4662C11.6204 13.706 12.4647 14.0112 13.3065 14.1646C13.3249 14.1671 13.3432 14.1713 13.3615 14.1771C13.6628 14.2748 13.9075 14.4775 14.0552 14.7334C14.2023 14.9881 14.2538 15.2936 14.1714 15.6009C14.1527 15.6709 14.1291 15.7372 14.1009 15.7995C14.2022 15.7655 14.302 15.72 14.4007 15.6625L17.3708 13.9326C17.612 13.7921 17.8363 13.6856 18.0439 13.6082C18.2218 12.8668 18.1245 12.0366 17.6541 11.2107ZM14.6259 14.1563V14.2894C14.6259 14.4705 14.4791 14.6173 14.298 14.6173C14.1169 14.6173 13.9701 14.4705 13.9701 14.2894V14.1498C13.5019 14.0482 13.1222 13.7365 13.0032 13.2594C12.9601 13.084 13.0673 12.9068 13.2427 12.8636C13.4182 12.8205 13.5954 12.9277 13.6385 13.1032C13.7105 13.3922 13.9996 13.5229 14.2758 13.5317C14.2948 13.5304 14.3118 13.5323 14.3301 13.532C14.5532 13.5267 14.9549 13.4319 14.9483 13.1526C14.9482 13.1465 14.9469 13.1335 14.9445 13.1134C14.9065 12.801 14.5041 12.7157 14.2483 12.6737C13.6812 12.5806 13.1274 12.3099 13.0959 11.6609C13.0691 11.1098 13.4589 10.7051 13.9701 10.5792V10.4345C13.9701 10.2534 14.117 10.1066 14.298 10.1066C14.4791 10.1066 14.6259 10.2534 14.6259 10.4345V10.5746C15.0283 10.6732 15.3357 10.9256 15.497 11.3099C15.567 11.4768 15.4885 11.6689 15.3216 11.739C15.1546 11.809 14.9625 11.7304 14.8925 11.5635C14.7916 11.3231 14.5731 11.1977 14.3202 11.1922C14.3065 11.1931 14.2929 11.1932 14.2792 11.1924C14.0308 11.2005 13.7351 11.3428 13.7491 11.6302C13.7626 11.9103 14.1314 11.9918 14.3533 12.0282C14.9392 12.1244 15.5144 12.3721 15.5951 13.0366C15.5985 13.0643 15.6006 13.098 15.6015 13.1373C15.6153 13.7168 15.1471 14.0572 14.6259 14.1563Z"
                                                    fill="#82027E" />
                                            </svg>
                                        </div>
                                        <div class="text-center text1">Highest Salary</div>
                                        <div class="text-center text2">42 LPA</div>
                                    </div>

                                    <div class="feature">
                                        <div class="d-flex justify-content-center">
                                            <svg class="d-block d-lg-none"  xmlns="http://www.w3.org/2000/svg" width="23" height="20" viewBox="0 0 23 20" fill="none">
                                              <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1076 15.5404L12.9397 17.3015L13.658 20L11.3128 18.4839L8.96889 20L9.68585 17.3015L7.51925 15.5404L10.3077 15.3892L11.3127 12.7832L12.3191 15.3892L15.1075 15.5404H15.1076ZM4.17681 13.8404L3.40508 11.8407L2.63334 13.8404L0.492812 13.9565L2.15621 15.307L1.6061 17.3784L3.40508 16.2143L5.20405 17.3784L4.6526 15.307L6.316 13.9565L4.17681 13.8404ZM19.9935 13.8404L19.2218 11.8407L18.4501 13.8404L16.3108 13.9565L17.9729 15.307L17.4228 17.3784L19.2218 16.2143L21.0208 17.3784L20.4706 15.307L22.1328 13.9565L19.9936 13.8404H19.9935ZM20.5762 4.90287C19.648 5.69286 18.2819 5.69286 17.3537 4.90287C16.6471 5.11405 16.0397 5.47253 15.6733 5.97965C16.565 6.62491 17.1972 7.54399 17.2442 8.77326H22.6268V7.06033C22.5863 5.9601 21.7091 5.24047 20.5762 4.90287ZM18.9649 4.9433C20.0339 4.9433 20.9008 4.07641 20.9008 3.00746C20.9008 1.9385 20.0339 1.07158 18.9649 1.07158C17.896 1.07158 17.0291 1.9385 17.0291 3.00746C17.0291 4.07641 17.896 4.9433 18.9649 4.9433ZM6.9535 5.97969C6.5872 5.47258 5.97969 5.1141 5.27312 4.90292C4.34365 5.69291 2.97876 5.69291 2.05058 4.90292C0.916457 5.24057 0.0404238 5.96015 0 7.06037V8.7733H5.38267C5.42962 7.54399 6.06183 6.62496 6.9535 5.97969ZM3.66187 4.9433C4.73083 4.9433 5.59771 4.07641 5.59771 3.00746C5.59771 1.9385 4.73083 1.07158 3.66187 1.07158C2.59292 1.07158 1.72599 1.9385 1.72599 3.00746C1.72599 4.07641 2.59292 4.9433 3.66187 4.9433ZM9.25434 5.54427C7.44235 5.99924 5.99666 7.08383 5.93407 8.79809V11.3141H16.6927V8.79809C16.6302 7.08383 15.1845 5.99924 13.3725 5.54427C12.1575 6.47246 10.4693 6.47246 9.25434 5.54427ZM11.3127 5.68766C12.8836 5.68766 14.1572 4.41535 14.1572 2.84448C14.1572 1.2736 12.8836 0 11.3127 0C9.74187 0 8.46955 1.27365 8.46955 2.84448C8.46955 4.4153 9.74315 5.68766 11.3127 5.68766Z" fill="#82027E"/>
                                            </svg>
                                            <svg class="d-none d-lg-block" xmlns="http://www.w3.org/2000/svg" width="19" height="20"
                                                viewBox="0 0 19 20" fill="none">
                                                <path
                                                    d="M2.5 5C2.5 2.2425 4.7425 0 7.5 0C10.2575 0 12.5 2.2425 12.5 5C12.5 7.7575 10.2575 10 7.5 10C4.7425 10 2.5 7.7575 2.5 5ZM16.4042 13.5558L13.87 13.1333C13.5592 13.0817 13.3333 12.815 13.3333 12.5C13.3333 12.0408 13.7067 11.6667 14.1658 11.6667L16.0583 11.6692C16.3583 11.6692 16.6283 11.825 16.78 12.0858C17.01 12.4833 17.5217 12.6192 17.9192 12.3875C18.3175 12.1567 18.4525 11.6458 18.22 11.2483C17.7733 10.48 16.945 10.0025 16.0583 10.0025H15.8325V9.16667C15.8325 8.70667 15.46 8.33333 14.9992 8.33333C14.5383 8.33333 14.1658 8.70667 14.1658 9.16667V10C12.7875 10 11.6658 11.1217 11.6658 12.5C11.6658 13.6333 12.4775 14.5917 13.595 14.7775L16.1292 15.2C16.44 15.2517 16.6658 15.5183 16.6658 15.8333C16.6658 16.2925 16.2925 16.6667 15.8333 16.6667L13.9417 16.665C13.645 16.665 13.3683 16.5058 13.22 16.2492C12.99 15.8508 12.4792 15.7158 12.0817 15.9458C11.6825 16.1767 11.5467 16.6867 11.7783 17.085C12.2242 17.8542 13.0525 18.3317 13.9417 18.3317H14.1667V19.1667C14.1667 19.6267 14.5392 20 15 20C15.4608 20 15.8333 19.6267 15.8333 19.1667V18.3333C17.2117 18.3333 18.3333 17.2117 18.3333 15.8333C18.3333 14.7 17.5217 13.7417 16.4042 13.5558ZM10.3375 17.9208C10.0025 17.3433 9.9125 16.6692 10.0842 16.0233C10.205 15.5725 10.4425 15.1733 10.7708 14.86C10.2833 14.1975 10 13.38 10 12.5C10 12.3692 10.0267 12.2458 10.0383 12.1183C9.24417 11.8317 8.39167 11.6667 7.5 11.6667C3.36417 11.6667 0 15.0317 0 19.1667C0 19.6267 0.3725 20 0.833333 20H12.6533C12.625 19.9208 12.5983 19.8408 12.5783 19.7575C11.65 19.4333 10.8433 18.7942 10.3367 17.92L10.3375 17.9208Z"
                                                    fill="#82027E" />
                                            </svg>
                                            
                                        </div>
                                        <div class="text-center text1 d-block d-lg-none">Success Rate</div>
                                        <div class="text-center text1 d-none d-lg-block">Average Salary</div>
                                        <div class="text-center text2 d-none d-lg-block">21 LPA</div>
                                        <div class="text-center text2 d-block d-lg-none">90%</div>
                                    </div>

                                    <div class="feature">
                                        <div class="d-flex justify-content-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none">
                                                <path
                                                    d="M9.90654 9.34579C12.4832 9.34579 14.5794 7.24953 14.5794 4.6729C14.5794 2.09627 12.4832 0 9.90654 0C7.32991 0 5.23364 2.09627 5.23364 4.6729C5.23364 7.24953 7.32991 9.34579 9.90654 9.34579ZM8.74989 4.25795L9.52873 5.03664L11.0086 3.06356C11.4749 2.44198 12.4132 3.147 11.9476 3.76781L10.0618 6.28224C9.87154 6.53579 9.45751 6.62535 9.17722 6.34505L7.92 5.08784C7.37164 4.53963 8.20045 3.70821 8.74989 4.25795Z"
                                                    fill="#82027E" />
                                                <path
                                                    d="M12.5005 10.6543H7.49949C7.1773 10.6543 6.91589 10.9173 6.91589 11.2412V20.0001H13.0841V11.2412C13.0841 10.9173 12.8227 10.6543 12.5005 10.6543Z"
                                                    fill="#82027E" />
                                                <path
                                                    d="M4.29907 5.51396C4.29907 6.70117 3.3367 7.66357 2.14953 7.66357C0.962366 7.66357 0 6.70117 0 5.51396C0 4.3269 0.962366 3.3645 2.14953 3.3645C3.3367 3.3645 4.29907 4.3269 4.29907 5.51396Z"
                                                    fill="#82027E" />
                                                <path
                                                    d="M20 5.51396C20 6.70117 19.0376 7.66357 17.8505 7.66357C16.6633 7.66357 15.7009 6.70117 15.7009 5.51396C15.7009 4.3269 16.6633 3.3645 17.8505 3.3645C19.0376 3.3645 20 4.3269 20 5.51396Z"
                                                    fill="#82027E" />
                                                <path
                                                    d="M5.03423 13.0674H4.21632V10.2143C4.21632 9.00387 3.26211 8.02438 2.08502 8.03748C0.924801 8.05012 0 9.04812 0 10.2467V13.3848C0 14.9373 1.21874 16.1958 2.72216 16.1958H4.21632V19.408C4.21632 19.7348 4.47323 20 4.78956 20H5.60748V13.6593C5.60748 13.3327 5.35101 13.0674 5.03423 13.0674Z"
                                                    fill="#82027E" />
                                                <path
                                                    d="M14.7979 13.0674H15.6431V10.2143C15.6431 9.00387 16.6291 8.02438 17.8454 8.03748C19.0443 8.05012 20 9.04812 20 10.2467V13.3848C20 14.9373 18.7406 16.1958 17.1871 16.1958H15.6431V19.408C15.6431 19.7348 15.3776 20 15.0507 20H14.2056V13.6593C14.2056 13.3327 14.4706 13.0674 14.7979 13.0674Z"
                                                    fill="#82027E" />
                                            </svg>
                                        </div>
                                        <div class="text-center text1">Students Placed</div>
                                        <div class="text-center text2">1290+</div>
                                    </div>

                                    <div class="feature">
                                        <div class="d-flex justify-content-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="20"
                                                viewBox="0 0 22 20" fill="none">
                                                <path
                                                    d="M16.1538 6.15385C17.8532 6.15385 19.2308 4.77626 19.2308 3.07692C19.2308 1.37759 17.8532 0 16.1538 0C14.4545 0 13.0769 1.37759 13.0769 3.07692C13.0769 4.77626 14.4545 6.15385 16.1538 6.15385Z"
                                                    fill="#82027E" />
                                                <path
                                                    d="M6.15385 7.69233C6.15329 7.43434 6.17647 7.17684 6.22308 6.9231H3.84615C2.82609 6.9231 1.84781 7.32831 1.12651 8.04961C0.405219 8.7709 0 9.74919 0 10.7692V14.6154C0 14.8194 0.0810437 15.0151 0.225302 15.1593C0.369561 15.3036 0.565218 15.3846 0.769231 15.3846H3.84615C3.84699 14.3376 4.15305 13.3136 4.72686 12.4379C5.30067 11.5621 6.11734 10.8727 7.07692 10.4539C6.47932 9.65707 6.15551 8.68833 6.15385 7.69233Z"
                                                    fill="#82027E" />
                                                <path
                                                    d="M17.6923 6.9231H15.3154C15.362 7.17684 15.3852 7.43434 15.3846 7.69233C15.383 8.68833 15.0592 9.65707 14.4615 10.4539C15.4211 10.8727 16.2378 11.5621 16.8116 12.4379C17.3854 13.3136 17.6915 14.3376 17.6923 15.3846H20.7692C20.9733 15.3846 21.1689 15.3036 21.3132 15.1593C21.4574 15.0151 21.5385 14.8194 21.5385 14.6154V10.7692C21.5385 9.74919 21.1333 8.7709 20.412 8.04961C19.6907 7.32831 18.7124 6.9231 17.6923 6.9231Z"
                                                    fill="#82027E" />
                                                <path
                                                    d="M5.38466 6.15385C7.084 6.15385 8.46159 4.77626 8.46159 3.07692C8.46159 1.37759 7.084 0 5.38466 0C3.68532 0 2.30774 1.37759 2.30774 3.07692C2.30774 4.77626 3.68532 6.15385 5.38466 6.15385Z"
                                                    fill="#82027E" />
                                                <path
                                                    d="M10.7692 10.7693C12.4685 10.7693 13.8461 9.39174 13.8461 7.6924C13.8461 5.99306 12.4685 4.61548 10.7692 4.61548C9.06985 4.61548 7.69226 5.99306 7.69226 7.6924C7.69226 9.39174 9.06985 10.7693 10.7692 10.7693Z"
                                                    fill="#82027E" />
                                                <path
                                                    d="M12.3077 11.5383H9.2308C8.21073 11.5383 7.23245 11.9435 6.51116 12.6648C5.78986 13.3861 5.38464 14.3644 5.38464 15.3845V19.2306C5.38464 19.4347 5.46569 19.6303 5.60995 19.7746C5.7542 19.9188 5.94986 19.9999 6.15387 19.9999H15.3846C15.5887 19.9999 15.7843 19.9188 15.9286 19.7746C16.0728 19.6303 16.1539 19.4347 16.1539 19.2306V15.3845C16.1539 14.8794 16.0544 14.3793 15.8611 13.9126C15.6678 13.446 15.3845 13.022 15.0274 12.6648C14.6702 12.3077 14.2462 12.0244 13.7796 11.8311C13.3129 11.6378 12.8128 11.5383 12.3077 11.5383Z"
                                                    fill="#82027E" />
                                            </svg>
                                        </div>
                                        <div class="text-center text1">Hiring Partners</div>
                                        <div class="text-center text2">400+</div>
                                    </div>


                                </div>
                                 <!-- <div class=" d-none d-lg-flex justify-content-center mt-3 mt-lg-5 "> -->
                                    <!-- <img loading="lazy" width="313" height="186.6" src="assets/dit/images/home/laptop.webp" class="img-fluid" alt="laptop"
                                        style="" /> -->
                                        <div class="diwsvg" >
                                        <svg width="340" height="55" viewBox="0 0 340 55" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M0 0H340L318.086 27.5L340 55H0L23.2422 27.5L0 0Z" fill="#FFD802"/>
                                             <path d="M49.4375 34.388C49.7475 33.706 50.1195 32.8277 50.5535 31.753C50.9875 30.6783 51.4422 29.521 51.9175 28.281C52.3928 27.0203 52.8785 25.739 53.3745 24.437C53.8705 23.1143 54.3355 21.864 54.7695 20.686C55.2035 19.508 55.5755 18.454 55.8855 17.524C56.2162 16.594 56.4538 15.881 56.5985 15.385C55.2965 15.9223 54.1805 16.7387 53.2505 17.834C52.3205 18.9293 51.6178 20.3037 51.1425 21.957C50.6672 21.7917 50.2332 21.5023 49.8405 21.089C49.4478 20.655 49.1895 20.1177 49.0655 19.477C49.2722 18.5263 49.6545 17.6583 50.2125 16.873C50.7912 16.0877 51.5145 15.416 52.3825 14.858C53.2505 14.3 54.2528 13.8763 55.3895 13.587C56.5262 13.277 57.7455 13.122 59.0475 13.122C60.7008 13.122 62.2198 13.37 63.6045 13.866C64.9892 14.3413 66.1775 15.013 67.1695 15.881C68.1615 16.749 68.9365 17.7927 69.4945 19.012C70.0525 20.2313 70.3315 21.585 70.3315 23.073C70.3315 25.0983 69.9595 27.0307 69.2155 28.87C68.4715 30.7093 67.4588 32.3317 66.1775 33.737C64.9168 35.1423 63.4495 36.2583 61.7755 37.085C60.1015 37.9323 58.3448 38.356 56.5055 38.356C54.8108 38.356 53.3538 38.015 52.1345 37.333C50.9358 36.6717 50.0368 35.69 49.4375 34.388ZM60.3805 14.672C59.3472 14.672 58.3655 14.8167 57.4355 15.106C58.3448 15.4367 59.0992 15.788 59.6985 16.16C60.2978 16.5113 60.7112 16.7903 60.9385 16.997C60.7732 17.2243 60.5252 17.6273 60.1945 18.206C59.8845 18.7847 59.5125 19.4873 59.0785 20.314C58.6652 21.1407 58.2208 22.05 57.7455 23.042C57.2702 24.0133 56.8052 25.0157 56.3505 26.049C55.1725 28.6737 54.3148 30.7403 53.7775 32.249C53.2608 33.7577 53.0025 34.8013 53.0025 35.38C53.2918 35.4833 53.6432 35.5557 54.0565 35.597C54.4698 35.6383 54.9142 35.659 55.3895 35.659C57.0635 35.659 58.6445 35.2767 60.1325 34.512C61.6412 33.7267 62.9535 32.683 64.0695 31.381C65.2062 30.079 66.0948 28.5703 66.7355 26.855C67.3968 25.119 67.7275 23.3003 67.7275 21.399C67.7275 19.2703 67.0662 17.617 65.7435 16.439C64.4208 15.261 62.6332 14.672 60.3805 14.672ZM71.2138 33.923C71.2138 33.737 71.2241 33.5097 71.2448 33.241C71.2861 32.9517 71.3481 32.5797 71.4308 32.125C71.5134 31.6497 71.6168 31.0813 71.7408 30.42C71.8854 29.7587 72.0611 28.963 72.2678 28.033C72.3091 27.8263 72.4021 27.4957 72.5468 27.041C72.6914 26.5863 72.8774 26.111 73.1048 25.615C73.3528 25.0983 73.6421 24.6023 73.9728 24.127C74.3241 23.6517 74.7374 23.3003 75.2128 23.073C75.5641 23.1557 76.0291 23.3727 76.6078 23.724C77.1864 24.0547 77.6824 24.5197 78.0958 25.119C77.6204 25.6563 77.2174 26.1937 76.8868 26.731C76.5768 27.2683 76.2874 27.8263 76.0188 28.405C75.7708 28.9837 75.5331 29.583 75.3058 30.203C75.0991 30.823 74.8614 31.474 74.5928 32.156C74.3654 32.7347 74.1588 33.334 73.9728 33.954C73.7868 34.5533 73.6834 35.0803 73.6628 35.535C73.6628 35.907 73.6524 36.1653 73.6318 36.31C73.6318 36.4753 73.5801 36.558 73.4768 36.558C73.3941 36.558 73.2288 36.5063 72.9808 36.403C72.7328 36.2997 72.4744 36.1447 72.2058 35.938C71.9578 35.7107 71.7304 35.4317 71.5238 35.101C71.3171 34.7703 71.2138 34.3777 71.2138 33.923ZM77.1348 20.469C77.1348 20.345 77.1658 20.159 77.2278 19.911C77.1244 19.8077 77.0314 19.6527 76.9488 19.446C76.8868 19.2393 76.8558 19.0533 76.8558 18.888C76.8558 18.702 76.8764 18.454 76.9178 18.144C76.9798 17.8133 77.0521 17.462 77.1348 17.09C77.2174 16.718 77.3208 16.3357 77.4448 15.943C77.5688 15.5297 77.6928 15.1577 77.8168 14.827C78.0648 14.827 78.3644 14.8993 78.7158 15.044C79.0878 15.168 79.4598 15.3437 79.8318 15.571C80.2038 15.7777 80.5448 16.0153 80.8548 16.284C81.1648 16.532 81.4024 16.7697 81.5678 16.997C81.3405 17.2243 81.0408 17.5447 80.6688 17.958C80.2968 18.3507 79.9351 18.7743 79.5838 19.229C79.2324 19.663 78.9121 20.0763 78.6228 20.469C78.3334 20.8617 78.1474 21.1407 78.0648 21.306C77.8168 21.2647 77.5998 21.1613 77.4138 20.996C77.2278 20.81 77.1348 20.6343 77.1348 20.469ZM78.788 34.729C78.788 34.3157 78.8293 33.737 78.912 32.993C79.0153 32.249 79.1393 31.443 79.284 30.575C79.4493 29.707 79.625 28.8183 79.811 27.909C80.0176 26.9997 80.2243 26.1833 80.431 25.46C80.6996 24.4887 81.113 23.7447 81.671 23.228C82.2496 22.6907 82.9626 22.422 83.81 22.422C84.2646 22.422 84.709 22.4943 85.143 22.639C85.5976 22.7837 85.8663 22.9697 85.949 23.197C85.3496 23.693 84.864 24.313 84.492 25.057C84.1406 25.7803 83.8513 26.5657 83.624 27.413C83.4173 28.2397 83.252 29.0973 83.128 29.986C83.0246 30.854 82.911 31.6703 82.787 32.435C83.469 31.5257 84.1096 30.7093 84.709 29.986C85.329 29.242 85.8766 28.653 86.352 28.219C86.9926 27.6197 87.4266 27.1237 87.654 26.731C87.8813 26.3177 88.0776 25.8733 88.243 25.398C88.367 25.026 88.5116 24.592 88.677 24.096C88.863 23.6 89.1626 23.1453 89.576 22.732C89.6586 22.7113 89.8343 22.701 90.103 22.701C90.3096 22.701 90.5266 22.7113 90.754 22.732C91.002 22.7527 91.2293 22.794 91.436 22.856C91.6426 22.918 91.8183 22.9903 91.963 23.073C92.1076 23.1557 92.1903 23.259 92.211 23.383C91.8803 23.817 91.5703 24.499 91.281 25.429C90.9916 26.3383 90.7333 27.3407 90.506 28.436C90.2786 29.5313 90.072 30.6267 89.886 31.722C89.7 32.7967 89.5346 33.706 89.39 34.45C89.4726 34.4087 89.6586 34.2433 89.948 33.954C90.2373 33.6647 90.5576 33.303 90.909 32.869C91.281 32.435 91.6633 31.9597 92.056 31.443C92.4693 30.9263 92.831 30.42 93.141 29.924C93.5336 29.3247 93.854 28.7667 94.102 28.25C94.3706 27.7127 94.598 27.1443 94.784 26.545C94.9906 25.9457 95.1766 25.2843 95.342 24.561C95.5073 23.817 95.683 22.9387 95.869 21.926C95.993 21.7607 96.2616 21.6263 96.675 21.523C97.109 21.399 97.5016 21.337 97.853 21.337C98.163 21.337 98.3903 21.3887 98.535 21.492C98.4316 22.6493 98.2353 23.6827 97.946 24.592C97.6773 25.5013 97.3053 26.39 96.83 27.258C96.3546 28.1053 95.7656 28.963 95.063 29.831C94.3603 30.6783 93.5336 31.6083 92.583 32.621C92.1903 33.0343 91.7563 33.489 91.281 33.985C90.8263 34.481 90.3716 34.946 89.917 35.38C89.483 35.7933 89.0696 36.1447 88.677 36.434C88.305 36.7233 88.0156 36.868 87.809 36.868C87.3336 36.868 86.941 36.682 86.631 36.31C86.321 35.938 86.166 35.4627 86.166 34.884C86.166 34.2847 86.1866 33.768 86.228 33.334C86.29 32.8793 86.383 32.404 86.507 31.908C86.631 31.4533 86.724 31.071 86.786 30.761C86.8686 30.451 86.9306 30.1823 86.972 29.955C87.034 29.7277 87.0753 29.5313 87.096 29.366C87.1166 29.2007 87.1373 29.0353 87.158 28.87C86.9926 28.9527 86.7756 29.1283 86.507 29.397C86.2383 29.645 85.9593 29.9447 85.67 30.296C85.3806 30.6267 85.0913 30.9677 84.802 31.319C84.5126 31.6703 84.275 31.97 84.089 32.218C83.6963 32.7553 83.3553 33.2307 83.066 33.644C82.7766 34.0573 82.4976 34.45 82.229 34.822C81.981 35.194 81.733 35.5763 81.485 35.969C81.237 36.341 80.9476 36.744 80.617 37.178C79.9556 36.9713 79.4803 36.6613 79.191 36.248C78.9223 35.8347 78.788 35.3283 78.788 34.729ZM108.882 23.476C109.088 23.476 109.326 23.5173 109.595 23.6C109.884 23.662 110.142 23.755 110.37 23.879C110.597 23.9823 110.783 24.096 110.928 24.22C111.093 24.344 111.176 24.4577 111.176 24.561C110.762 25.429 110.38 26.3073 110.029 27.196C109.677 28.064 109.378 28.901 109.13 29.707C108.882 30.513 108.685 31.2673 108.541 31.97C108.417 32.6727 108.355 33.2823 108.355 33.799C108.355 34.3777 108.417 34.8633 108.541 35.256C108.665 35.628 108.871 35.8967 109.161 36.062C108.913 36.2273 108.592 36.3617 108.2 36.465C107.807 36.5683 107.456 36.62 107.146 36.62C106.03 36.62 105.472 35.8553 105.472 34.326C105.472 33.148 105.864 31.536 106.65 29.49C106.236 30.4407 105.761 31.3397 105.224 32.187C104.686 33.0137 104.139 33.7473 103.581 34.388C103.023 35.0287 102.454 35.5557 101.876 35.969C101.318 36.3617 100.801 36.5993 100.326 36.682C99.561 36.6407 98.941 36.279 98.4657 35.597C97.9904 34.8943 97.7527 34.0057 97.7527 32.931C97.7527 31.7737 98.0007 30.5543 98.4967 29.273C98.9927 27.9917 99.623 26.8033 100.388 25.708C101.173 24.6127 102.02 23.7033 102.93 22.98C103.839 22.236 104.717 21.8433 105.565 21.802C105.771 21.864 105.999 21.988 106.247 22.174C106.515 22.36 106.763 22.577 106.991 22.825C107.239 23.073 107.445 23.3313 107.611 23.6C107.776 23.848 107.869 24.065 107.89 24.251C107.063 24.375 106.205 24.778 105.317 25.46C104.449 26.142 103.653 26.9687 102.93 27.94C102.227 28.8907 101.648 29.9137 101.194 31.009C100.739 32.1043 100.512 33.1273 100.512 34.078C100.512 34.4707 100.656 34.7187 100.946 34.822C101.545 34.698 102.237 34.1917 103.023 33.303C103.808 32.3937 104.759 30.9987 105.875 29.118C106.081 28.746 106.309 28.3327 106.557 27.878C106.825 27.4233 107.094 26.948 107.363 26.452C107.631 25.956 107.89 25.46 108.138 24.964C108.406 24.4473 108.654 23.9513 108.882 23.476ZM112.717 34.016C112.717 33.1067 112.934 31.8357 113.368 30.203C113.802 28.5497 114.412 26.6277 115.197 24.437C116.479 20.9443 117.677 18.0407 118.793 15.726C119.93 13.3907 121.036 11.572 122.11 10.27C122.358 10.3113 122.658 10.4147 123.009 10.58C123.381 10.7453 123.743 10.9313 124.094 11.138C124.446 11.324 124.756 11.5203 125.024 11.727C125.314 11.9337 125.5 12.099 125.582 12.223C123.97 14.1243 122.555 16.1703 121.335 18.361C120.116 20.5517 119 23.0627 117.987 25.894C117.119 28.3327 116.458 30.5337 116.003 32.497C115.549 34.4397 115.321 36.062 115.321 37.364C115.321 37.7153 115.321 37.9323 115.321 38.015C115.321 38.0977 115.332 38.1803 115.352 38.263C115.063 38.2217 114.815 38.1493 114.608 38.046C114.402 37.9427 114.267 37.8187 114.205 37.674C114.164 37.5707 114.05 37.4157 113.864 37.209C113.678 37.023 113.513 36.8783 113.368 36.775C113.182 36.651 113.027 36.3307 112.903 35.814C112.779 35.2767 112.717 34.6773 112.717 34.016ZM120.105 33.923C120.105 33.737 120.116 33.5097 120.136 33.241C120.178 32.9517 120.24 32.5797 120.322 32.125C120.405 31.6497 120.508 31.0813 120.632 30.42C120.777 29.7587 120.953 28.963 121.159 28.033C121.201 27.8263 121.294 27.4957 121.438 27.041C121.583 26.5863 121.769 26.111 121.996 25.615C122.244 25.0983 122.534 24.6023 122.864 24.127C123.216 23.6517 123.629 23.3003 124.104 23.073C124.456 23.1557 124.921 23.3727 125.499 23.724C126.078 24.0547 126.574 24.5197 126.987 25.119C126.512 25.6563 126.109 26.1937 125.778 26.731C125.468 27.2683 125.179 27.8263 124.91 28.405C124.662 28.9837 124.425 29.583 124.197 30.203C123.991 30.823 123.753 31.474 123.484 32.156C123.257 32.7347 123.05 33.334 122.864 33.954C122.678 34.5533 122.575 35.0803 122.554 35.535C122.554 35.907 122.544 36.1653 122.523 36.31C122.523 36.4753 122.472 36.558 122.368 36.558C122.286 36.558 122.12 36.5063 121.872 36.403C121.624 36.2997 121.366 36.1447 121.097 35.938C120.849 35.7107 120.622 35.4317 120.415 35.101C120.209 34.7703 120.105 34.3777 120.105 33.923ZM126.026 20.469C126.026 20.345 126.057 20.159 126.119 19.911C126.016 19.8077 125.923 19.6527 125.84 19.446C125.778 19.2393 125.747 19.0533 125.747 18.888C125.747 18.702 125.768 18.454 125.809 18.144C125.871 17.8133 125.944 17.462 126.026 17.09C126.109 16.718 126.212 16.3357 126.336 15.943C126.46 15.5297 126.584 15.1577 126.708 14.827C126.956 14.827 127.256 14.8993 127.607 15.044C127.979 15.168 128.351 15.3437 128.723 15.571C129.095 15.7777 129.436 16.0153 129.746 16.284C130.056 16.532 130.294 16.7697 130.459 16.997C130.232 17.2243 129.932 17.5447 129.56 17.958C129.188 18.3507 128.827 18.7743 128.475 19.229C128.124 19.663 127.804 20.0763 127.514 20.469C127.225 20.8617 127.039 21.1407 126.956 21.306C126.708 21.2647 126.491 21.1613 126.305 20.996C126.119 20.81 126.026 20.6343 126.026 20.469ZM155.123 15.106C155.185 15.4573 155.227 15.7053 155.247 15.85C155.289 15.974 155.309 16.1497 155.309 16.377C155.309 17.059 155.154 17.6997 154.844 18.299C154.555 18.8777 154.152 19.291 153.635 19.539C153.222 18.981 152.716 18.5057 152.116 18.113C151.517 17.6997 150.856 17.369 150.132 17.121C149.43 16.8523 148.696 16.656 147.931 16.532C147.167 16.408 146.423 16.346 145.699 16.346C144.976 16.346 144.294 16.408 143.653 16.532C143.033 16.6353 142.475 16.7903 141.979 16.997C141.504 17.183 141.122 17.4207 140.832 17.71C140.564 17.9993 140.429 18.33 140.429 18.702C140.491 19.1567 140.77 19.6217 141.266 20.097C141.762 20.5517 142.372 21.0063 143.095 21.461C143.819 21.895 144.614 22.329 145.482 22.763C146.371 23.197 147.239 23.6103 148.086 24.003C149.14 24.499 150.05 24.964 150.814 25.398C151.579 25.8113 152.209 26.2763 152.705 26.793C153.201 27.289 153.573 27.8573 153.821 28.498C154.069 29.1387 154.193 29.9137 154.193 30.823C154.193 31.877 154.018 32.869 153.666 33.799C153.315 34.7083 152.798 35.504 152.116 36.186C151.455 36.868 150.649 37.395 149.698 37.767C148.748 38.1597 147.683 38.356 146.505 38.356C145.348 38.356 144.098 38.1803 142.754 37.829C141.411 37.4777 140.15 36.992 138.972 36.372C137.815 35.7727 136.844 35.0803 136.058 34.295C135.294 33.489 134.911 32.6417 134.911 31.753C134.911 31.3397 135.025 30.8127 135.252 30.172C135.5 29.5313 135.934 28.8597 136.554 28.157C136.616 28.777 136.947 29.5107 137.546 30.358C138.166 31.2053 139.086 31.9907 140.305 32.714C141.442 33.3753 142.61 33.861 143.808 34.171C145.028 34.4603 146.164 34.605 147.218 34.605C148.376 34.605 149.316 34.4603 150.039 34.171C150.763 33.8817 151.331 33.5407 151.744 33.148C152.158 32.7347 152.447 32.3213 152.612 31.908C152.778 31.4947 152.871 31.164 152.891 30.916C152.871 30.5027 152.674 30.11 152.302 29.738C151.951 29.3453 151.465 28.9733 150.845 28.622C150.246 28.25 149.543 27.878 148.737 27.506C147.931 27.134 147.094 26.762 146.226 26.39C144.924 25.8113 143.798 25.274 142.847 24.778C141.917 24.2613 141.142 23.7447 140.522 23.228C139.923 22.6907 139.479 22.1327 139.189 21.554C138.921 20.9753 138.786 20.314 138.786 19.57C138.786 18.4127 139.138 17.3173 139.84 16.284C140.543 15.2507 141.556 14.4653 142.878 13.928C143.54 13.6387 144.242 13.432 144.986 13.308C145.751 13.184 146.495 13.122 147.218 13.122C148.582 13.122 149.915 13.308 151.217 13.68C152.519 14.052 153.821 14.5273 155.123 15.106ZM154.291 46.261C154.002 46.1783 153.836 45.8683 153.795 45.331C153.712 45.0623 153.65 44.742 153.609 44.37C153.588 43.998 153.578 43.5743 153.578 43.099C153.578 42.665 153.65 42.0657 153.795 41.301C153.94 40.557 154.136 39.7097 154.384 38.759C154.653 37.8083 154.973 36.7853 155.345 35.69C155.717 34.574 156.13 33.4477 156.585 32.311C157.143 30.885 157.629 29.6553 158.042 28.622C158.455 27.568 158.796 26.6587 159.065 25.894C159.354 25.1293 159.582 24.499 159.747 24.003C159.933 23.507 160.078 23.0833 160.181 22.732C160.264 22.484 160.388 22.1843 160.553 21.833C160.718 21.4817 160.904 21.1303 161.111 20.779C161.338 20.407 161.566 20.066 161.793 19.756C162.02 19.4253 162.227 19.1773 162.413 19.012C163.136 19.1153 163.818 19.3013 164.459 19.57C165.1 19.8387 165.492 20.097 165.637 20.345C165.348 20.6137 165.017 21.0373 164.645 21.616C164.273 22.174 163.911 22.794 163.56 23.476C163.209 24.1373 162.888 24.8193 162.599 25.522C162.31 26.2247 162.093 26.8447 161.948 27.382C162.155 27.0307 162.423 26.6483 162.754 26.235C163.085 25.8217 163.591 25.2533 164.273 24.53C165.038 23.7447 165.751 23.1453 166.412 22.732C167.094 22.298 167.683 22.081 168.179 22.081C168.964 22.081 169.605 22.298 170.101 22.732C170.618 23.166 170.876 23.724 170.876 24.406C170.876 25.894 170.628 27.3717 170.132 28.839C169.636 30.2857 168.923 31.598 167.993 32.776C167.642 33.2307 167.228 33.6647 166.753 34.078C166.278 34.4913 165.782 34.8633 165.265 35.194C164.748 35.504 164.242 35.752 163.746 35.938C163.25 36.124 162.806 36.217 162.413 36.217C162.041 36.217 161.71 36.1447 161.421 36C161.152 35.8347 160.966 35.6383 160.863 35.411C161.669 34.9357 162.454 34.2743 163.219 33.427C163.984 32.5797 164.686 31.6083 165.327 30.513C165.988 29.397 166.557 28.1983 167.032 26.917C167.528 25.6357 167.9 24.3337 168.148 23.011C167.363 23.4243 166.65 23.879 166.009 24.375C165.389 24.8503 164.769 25.4807 164.149 26.266C163.405 27.1753 162.816 27.909 162.382 28.467C161.948 29.025 161.586 29.5003 161.297 29.893C161.028 30.2857 160.801 30.6577 160.615 31.009C160.45 31.3397 160.264 31.753 160.057 32.249C159.768 32.9103 159.427 33.7887 159.034 34.884C158.662 35.9587 158.269 37.1677 157.856 38.511C157.463 39.8543 157.06 41.2803 156.647 42.789C156.254 44.3183 155.872 45.8373 155.5 47.346C155.273 47.3047 155.076 47.1393 154.911 46.85C154.746 46.664 154.622 46.5297 154.539 46.447C154.477 46.3643 154.394 46.3023 154.291 46.261ZM183.091 23.166C183.009 24.4473 182.781 25.553 182.409 26.483C182.037 27.3923 181.5 28.1983 180.797 28.901C180.115 29.583 179.278 30.203 178.286 30.761C177.294 31.319 176.137 31.8563 174.814 32.373C174.814 32.497 174.804 32.6313 174.783 32.776C174.783 32.9 174.783 33.0343 174.783 33.179C174.783 33.4477 174.804 33.7267 174.845 34.016C174.887 34.3053 174.959 34.574 175.062 34.822C175.166 35.07 175.31 35.2767 175.496 35.442C175.682 35.5867 175.92 35.659 176.209 35.659C176.767 35.659 177.315 35.535 177.852 35.287C178.39 35.039 178.896 34.7393 179.371 34.388C179.867 34.016 180.322 33.6233 180.735 33.21C181.169 32.776 181.552 32.3833 181.882 32.032C182.089 32.218 182.265 32.3937 182.409 32.559C181.479 33.799 180.456 34.8323 179.34 35.659C178.245 36.465 177.201 36.9817 176.209 37.209C174.783 37.0437 173.667 36.527 172.861 35.659C172.076 34.7703 171.683 33.706 171.683 32.466C171.683 31.6187 171.797 30.761 172.024 29.893C172.272 29.0043 172.603 28.157 173.016 27.351C173.45 26.5243 173.946 25.7493 174.504 25.026C175.083 24.3027 175.693 23.6723 176.333 23.135C176.995 22.5977 177.666 22.174 178.348 21.864C179.051 21.554 179.743 21.399 180.425 21.399C181.376 21.523 182.265 22.112 183.091 23.166ZM180.952 24.189C180.394 24.2717 179.816 24.499 179.216 24.871C178.617 25.243 178.049 25.739 177.511 26.359C176.995 26.979 176.519 27.7023 176.085 28.529C175.651 29.3557 175.31 30.2443 175.062 31.195C176.716 30.3683 178.09 29.3557 179.185 28.157C180.281 26.9377 180.87 25.615 180.952 24.189ZM184.713 30.544C184.713 29.366 184.95 28.2293 185.426 27.134C185.922 26.018 186.562 25.0363 187.348 24.189C188.154 23.321 189.063 22.6287 190.076 22.112C191.109 21.5747 192.173 21.306 193.269 21.306C194.343 21.306 195.17 21.5747 195.749 22.112C196.327 22.6493 196.617 23.414 196.617 24.406C196.617 24.8813 196.544 25.3463 196.4 25.801C196.276 26.235 196.1 26.6277 195.873 26.979C195.666 27.3303 195.418 27.6197 195.129 27.847C194.839 28.0537 194.55 28.157 194.261 28.157C194.013 28.157 193.754 28.0847 193.486 27.94C193.238 27.7747 193.031 27.568 192.866 27.32C194.25 26.0593 194.943 24.9123 194.943 23.879C194.943 23.2797 194.705 22.98 194.23 22.98C193.816 22.98 193.3 23.1763 192.68 23.569C192.06 23.9617 191.46 24.4783 190.882 25.119C189.993 26.111 189.311 27.2167 188.836 28.436C188.381 29.6347 188.154 30.8953 188.154 32.218C188.154 33.892 188.526 34.8943 189.27 35.225C189.58 35.1837 189.941 35.0907 190.355 34.946C190.768 34.8013 191.212 34.6153 191.688 34.388C192.163 34.14 192.649 33.861 193.145 33.551C193.661 33.241 194.157 32.9103 194.633 32.559C194.715 32.621 194.788 32.714 194.85 32.838C194.912 32.962 194.943 33.0757 194.943 33.179C194.591 33.613 194.188 34.0367 193.734 34.45C193.279 34.8633 192.804 35.2457 192.308 35.597C191.812 35.9277 191.326 36.2067 190.851 36.434C190.396 36.682 189.993 36.837 189.642 36.899C188.96 36.837 188.319 36.62 187.72 36.248C187.12 35.8967 186.593 35.4317 186.139 34.853C185.705 34.2743 185.353 33.613 185.085 32.869C184.837 32.125 184.713 31.35 184.713 30.544ZM197.575 33.923C197.575 33.737 197.585 33.5097 197.606 33.241C197.647 32.9517 197.709 32.5797 197.792 32.125C197.875 31.6497 197.978 31.0813 198.102 30.42C198.247 29.7587 198.422 28.963 198.629 28.033C198.67 27.8263 198.763 27.4957 198.908 27.041C199.053 26.5863 199.239 26.111 199.466 25.615C199.714 25.0983 200.003 24.6023 200.334 24.127C200.685 23.6517 201.099 23.3003 201.574 23.073C201.925 23.1557 202.39 23.3727 202.969 23.724C203.548 24.0547 204.044 24.5197 204.457 25.119C203.982 25.6563 203.579 26.1937 203.248 26.731C202.938 27.2683 202.649 27.8263 202.38 28.405C202.132 28.9837 201.894 29.583 201.667 30.203C201.46 30.823 201.223 31.474 200.954 32.156C200.727 32.7347 200.52 33.334 200.334 33.954C200.148 34.5533 200.045 35.0803 200.024 35.535C200.024 35.907 200.014 36.1653 199.993 36.31C199.993 36.4753 199.941 36.558 199.838 36.558C199.755 36.558 199.59 36.5063 199.342 36.403C199.094 36.2997 198.836 36.1447 198.567 35.938C198.319 35.7107 198.092 35.4317 197.885 35.101C197.678 34.7703 197.575 34.3777 197.575 33.923ZM203.496 20.469C203.496 20.345 203.527 20.159 203.589 19.911C203.486 19.8077 203.393 19.6527 203.31 19.446C203.248 19.2393 203.217 19.0533 203.217 18.888C203.217 18.702 203.238 18.454 203.279 18.144C203.341 17.8133 203.413 17.462 203.496 17.09C203.579 16.718 203.682 16.3357 203.806 15.943C203.93 15.5297 204.054 15.1577 204.178 14.827C204.426 14.827 204.726 14.8993 205.077 15.044C205.449 15.168 205.821 15.3437 206.193 15.571C206.565 15.7777 206.906 16.0153 207.216 16.284C207.526 16.532 207.764 16.7697 207.929 16.997C207.702 17.2243 207.402 17.5447 207.03 17.958C206.658 18.3507 206.296 18.7743 205.945 19.229C205.594 19.663 205.273 20.0763 204.984 20.469C204.695 20.8617 204.509 21.1407 204.426 21.306C204.178 21.2647 203.961 21.1613 203.775 20.996C203.589 20.81 203.496 20.6343 203.496 20.469ZM215.565 23.476C215.772 23.476 216.01 23.5173 216.278 23.6C216.568 23.662 216.826 23.755 217.053 23.879C217.281 23.9823 217.467 24.096 217.611 24.22C217.777 24.344 217.859 24.4577 217.859 24.561C217.446 25.429 217.064 26.3073 216.712 27.196C216.361 28.064 216.061 28.901 215.813 29.707C215.565 30.513 215.369 31.2673 215.224 31.97C215.1 32.6727 215.038 33.2823 215.038 33.799C215.038 34.3777 215.1 34.8633 215.224 35.256C215.348 35.628 215.555 35.8967 215.844 36.062C215.596 36.2273 215.276 36.3617 214.883 36.465C214.491 36.5683 214.139 36.62 213.829 36.62C212.713 36.62 212.155 35.8553 212.155 34.326C212.155 33.148 212.548 31.536 213.333 29.49C212.92 30.4407 212.445 31.3397 211.907 32.187C211.37 33.0137 210.822 33.7473 210.264 34.388C209.706 35.0287 209.138 35.5557 208.559 35.969C208.001 36.3617 207.485 36.5993 207.009 36.682C206.245 36.6407 205.625 36.279 205.149 35.597C204.674 34.8943 204.436 34.0057 204.436 32.931C204.436 31.7737 204.684 30.5543 205.18 29.273C205.676 27.9917 206.307 26.8033 207.071 25.708C207.857 24.6127 208.704 23.7033 209.613 22.98C210.523 22.236 211.401 21.8433 212.248 21.802C212.455 21.864 212.682 21.988 212.93 22.174C213.199 22.36 213.447 22.577 213.674 22.825C213.922 23.073 214.129 23.3313 214.294 23.6C214.46 23.848 214.553 24.065 214.573 24.251C213.747 24.375 212.889 24.778 212 25.46C211.132 26.142 210.337 26.9687 209.613 27.94C208.911 28.8907 208.332 29.9137 207.877 31.009C207.423 32.1043 207.195 33.1273 207.195 34.078C207.195 34.4707 207.34 34.7187 207.629 34.822C208.229 34.698 208.921 34.1917 209.706 33.303C210.492 32.3937 211.442 30.9987 212.558 29.118C212.765 28.746 212.992 28.3327 213.24 27.878C213.509 27.4233 213.778 26.948 214.046 26.452C214.315 25.956 214.573 25.46 214.821 24.964C215.09 24.4473 215.338 23.9513 215.565 23.476ZM219.401 34.016C219.401 33.1067 219.618 31.8357 220.052 30.203C220.486 28.5497 221.095 26.6277 221.881 24.437C223.162 20.9443 224.361 18.0407 225.477 15.726C226.613 13.3907 227.719 11.572 228.794 10.27C229.042 10.3113 229.341 10.4147 229.693 10.58C230.065 10.7453 230.426 10.9313 230.778 11.138C231.129 11.324 231.439 11.5203 231.708 11.727C231.997 11.9337 232.183 12.099 232.266 12.223C230.654 14.1243 229.238 16.1703 228.019 18.361C226.799 20.5517 225.683 23.0627 224.671 25.894C223.803 28.3327 223.141 30.5337 222.687 32.497C222.232 34.4397 222.005 36.062 222.005 37.364C222.005 37.7153 222.005 37.9323 222.005 38.015C222.005 38.0977 222.015 38.1803 222.036 38.263C221.746 38.2217 221.498 38.1493 221.292 38.046C221.085 37.9427 220.951 37.8187 220.889 37.674C220.847 37.5707 220.734 37.4157 220.548 37.209C220.362 37.023 220.196 36.8783 220.052 36.775C219.866 36.651 219.711 36.3307 219.587 35.814C219.463 35.2767 219.401 34.6773 219.401 34.016ZM248.56 13.432C248.952 13.5973 249.355 13.8143 249.769 14.083C250.182 14.331 250.564 14.5893 250.916 14.858C251.267 15.1267 251.556 15.3953 251.784 15.664C252.032 15.912 252.166 16.1187 252.187 16.284C251.835 16.2013 251.484 16.1497 251.133 16.129C250.781 16.0877 250.461 16.067 250.172 16.067C249.242 16.067 248.312 16.2943 247.382 16.749C246.472 17.2037 245.604 17.8237 244.778 18.609C243.951 19.3943 243.186 20.314 242.484 21.368C241.781 22.422 241.171 23.5483 240.655 24.747C240.159 25.9457 239.766 27.1857 239.477 28.467C239.187 29.7483 239.043 31.009 239.043 32.249C239.043 33.861 239.311 35.0597 239.849 35.845C240.386 36.6303 241.171 37.023 242.205 37.023C242.701 37.023 243.238 36.9197 243.817 36.713C244.395 36.527 244.984 36.2583 245.584 35.907C246.183 35.5557 246.772 35.132 247.351 34.636C247.929 34.14 248.467 33.5923 248.963 32.993C249.686 32.125 250.337 31.1433 250.916 30.048C251.494 28.932 251.98 27.754 252.373 26.514C252.786 25.274 253.096 24.003 253.303 22.701C253.53 21.3783 253.644 20.0867 253.644 18.826C253.644 18.33 253.633 17.9167 253.613 17.586C253.592 17.2347 253.561 16.9453 253.52 16.718C253.499 16.47 253.478 16.2737 253.458 16.129C253.437 15.9843 253.416 15.8603 253.396 15.757C253.644 15.881 253.912 16.036 254.202 16.222C254.512 16.3873 254.791 16.6457 255.039 16.997C255.307 17.3277 255.524 17.772 255.69 18.33C255.876 18.888 255.969 19.6113 255.969 20.5C255.969 21.9467 255.793 23.3727 255.442 24.778C255.111 26.1627 254.636 27.4853 254.016 28.746C253.416 30.0067 252.703 31.1847 251.877 32.28C251.05 33.3753 250.161 34.3363 249.211 35.163C247.971 36.2583 246.648 37.1057 245.243 37.705C243.837 38.3043 242.484 38.604 241.182 38.604C239.28 38.604 237.823 37.9943 236.811 36.775C235.819 35.5763 235.323 33.799 235.323 31.443C235.323 30.1823 235.457 28.8493 235.726 27.444C236.015 26.0387 236.408 24.654 236.904 23.29C237.42 21.926 238.03 20.6343 238.733 19.415C239.435 18.175 240.2 17.09 241.027 16.16C241.874 15.2093 242.773 14.455 243.724 13.897C244.695 13.339 245.687 13.06 246.7 13.06C247.03 13.06 247.34 13.091 247.63 13.153C247.919 13.1943 248.229 13.2873 248.56 13.432ZM277.972 13.618C277.62 13.618 277.238 13.835 276.825 14.269C276.411 14.703 275.967 15.3127 275.492 16.098C275.037 16.8627 274.572 17.7823 274.097 18.857C273.642 19.9317 273.198 21.12 272.764 22.422C273.239 22.4427 273.735 22.4737 274.252 22.515C274.789 22.5357 275.244 22.577 275.616 22.639C275.657 22.7217 275.678 22.8663 275.678 23.073C275.678 23.3003 275.647 23.5483 275.585 23.817C275.543 24.0857 275.471 24.2923 275.368 24.437C274.851 24.3957 274.334 24.3543 273.818 24.313C273.322 24.251 272.805 24.1993 272.268 24.158C271.234 27.4233 270.304 30.265 269.478 32.683C268.672 35.101 267.917 37.1677 267.215 38.883C266.533 40.619 265.892 42.0347 265.293 43.13C264.693 44.2253 264.084 45.083 263.464 45.703C262.844 46.3437 262.193 46.7777 261.511 47.005C260.849 47.253 260.116 47.377 259.31 47.377C258.38 47.377 257.594 47.2323 256.954 46.943C256.334 46.6743 255.755 46.3023 255.218 45.827C254.598 46.4057 253.957 46.8087 253.296 47.036C252.614 47.2633 251.859 47.377 251.033 47.377C249.565 47.377 248.315 46.881 247.282 45.889C247.282 45.7443 247.323 45.5893 247.406 45.424C247.488 45.2587 247.581 45.145 247.685 45.083C247.974 45.4343 248.356 45.703 248.832 45.889C249.307 46.0957 249.803 46.199 250.32 46.199C250.857 46.199 251.353 46.075 251.808 45.827C252.242 45.5997 252.676 45.207 253.11 44.649C253.523 44.091 253.936 43.3573 254.35 42.448C254.784 41.5387 255.249 40.4123 255.745 39.069C256.509 37.0023 257.253 34.7703 257.977 32.373C258.7 29.9757 259.496 27.2993 260.364 24.344C259.888 24.3853 259.423 24.4267 258.969 24.468C258.514 24.5093 258.09 24.5507 257.698 24.592C257.615 24.5093 257.543 24.3957 257.481 24.251C257.439 24.1063 257.419 23.972 257.419 23.848C257.687 23.7653 258.101 23.6723 258.659 23.569C259.237 23.445 259.919 23.321 260.705 23.197C261.18 21.647 261.697 20.252 262.255 19.012C262.813 17.772 263.422 16.7077 264.084 15.819C264.745 14.9303 265.458 14.2483 266.223 13.773C267.008 13.2977 267.855 13.06 268.765 13.06C269.571 13.06 270.335 13.2667 271.059 13.68C271.803 14.0933 272.485 14.5377 273.105 15.013C273.787 14.2897 274.5 13.711 275.244 13.277C276.008 12.843 276.752 12.626 277.476 12.626C277.703 12.626 277.93 12.6467 278.158 12.688C278.406 12.7087 278.592 12.719 278.716 12.719C278.902 12.5743 279.15 12.4503 279.46 12.347C279.77 12.223 280.038 12.161 280.266 12.161C280.514 12.161 280.834 12.2953 281.227 12.564C281.061 12.8533 280.886 13.246 280.7 13.742C280.514 14.238 280.359 14.7237 280.235 15.199C279.718 16.7283 279.17 17.7203 278.592 18.175C278.364 18.175 278.24 17.8753 278.22 17.276C278.178 16.8213 278.096 16.4287 277.972 16.098C277.868 15.7467 277.817 15.3643 277.817 14.951C277.817 14.827 277.827 14.6823 277.848 14.517C277.868 14.331 277.91 14.0313 277.972 13.618ZM263.774 39.069C264.125 38.1183 264.497 37.0437 264.89 35.845C265.282 34.6463 265.675 33.396 266.068 32.094C266.481 30.792 266.894 29.459 267.308 28.095C267.721 26.7103 268.114 25.3567 268.486 24.034H267.897C267.359 24.034 266.781 24.0443 266.161 24.065C265.561 24.065 264.941 24.0753 264.301 24.096C263.35 27.1753 262.482 29.8827 261.697 32.218C260.932 34.5327 260.209 36.5373 259.527 38.232C258.865 39.9473 258.225 41.363 257.605 42.479C257.005 43.6157 256.406 44.5353 255.807 45.238C256.117 45.5273 256.499 45.7547 256.954 45.92C257.429 46.106 257.894 46.199 258.349 46.199C258.865 46.199 259.341 46.075 259.775 45.827C260.229 45.5997 260.663 45.207 261.077 44.649C261.511 44.091 261.945 43.3573 262.379 42.448C262.813 41.5387 263.278 40.4123 263.774 39.069ZM268.92 22.453C269.292 21.2337 269.767 20.066 270.346 18.95C270.945 17.834 271.606 16.8213 272.33 15.912C271.875 15.6227 271.451 15.4263 271.059 15.323C270.666 15.2197 270.387 15.168 270.222 15.168C269.684 15.168 269.188 15.3333 268.734 15.664C268.279 15.974 267.835 16.4493 267.401 17.09C266.967 17.7307 266.533 18.516 266.099 19.446C265.685 20.376 265.241 21.461 264.766 22.701C265.427 22.639 266.099 22.5873 266.781 22.546C267.483 22.5047 268.196 22.4737 268.92 22.453ZM285.839 23.166C285.757 24.4473 285.529 25.553 285.157 26.483C284.785 27.3923 284.248 28.1983 283.545 28.901C282.863 29.583 282.026 30.203 281.034 30.761C280.042 31.319 278.885 31.8563 277.562 32.373C277.562 32.497 277.552 32.6313 277.531 32.776C277.531 32.9 277.531 33.0343 277.531 33.179C277.531 33.4477 277.552 33.7267 277.593 34.016C277.635 34.3053 277.707 34.574 277.81 34.822C277.914 35.07 278.058 35.2767 278.244 35.442C278.43 35.5867 278.668 35.659 278.957 35.659C279.515 35.659 280.063 35.535 280.6 35.287C281.138 35.039 281.644 34.7393 282.119 34.388C282.615 34.016 283.07 33.6233 283.483 33.21C283.917 32.776 284.3 32.3833 284.63 32.032C284.837 32.218 285.013 32.3937 285.157 32.559C284.227 33.799 283.204 34.8323 282.088 35.659C280.993 36.465 279.949 36.9817 278.957 37.209C277.531 37.0437 276.415 36.527 275.609 35.659C274.824 34.7703 274.431 33.706 274.431 32.466C274.431 31.6187 274.545 30.761 274.772 29.893C275.02 29.0043 275.351 28.157 275.764 27.351C276.198 26.5243 276.694 25.7493 277.252 25.026C277.831 24.3027 278.441 23.6723 279.081 23.135C279.743 22.5977 280.414 22.174 281.096 21.864C281.799 21.554 282.491 21.399 283.173 21.399C284.124 21.523 285.013 22.112 285.839 23.166ZM283.7 24.189C283.142 24.2717 282.564 24.499 281.964 24.871C281.365 25.243 280.797 25.739 280.259 26.359C279.743 26.979 279.267 27.7023 278.833 28.529C278.399 29.3557 278.058 30.2443 277.81 31.195C279.464 30.3683 280.838 29.3557 281.933 28.157C283.029 26.9377 283.618 25.615 283.7 24.189ZM286.531 35.38C286.634 34.7807 286.81 33.9953 287.058 33.024C287.326 32.032 287.636 30.9883 287.988 29.893C288.339 28.777 288.711 27.661 289.104 26.545C289.496 25.429 289.879 24.4267 290.251 23.538C290.519 22.8973 290.757 22.4013 290.964 22.05C291.17 21.678 291.398 21.368 291.646 21.12C291.935 21.1613 292.235 21.2337 292.545 21.337C292.855 21.4403 293.154 21.5643 293.444 21.709C293.733 21.8537 293.991 22.0087 294.219 22.174C294.446 22.3187 294.622 22.4633 294.746 22.608C294.188 23.2693 293.692 24.0237 293.258 24.871C292.844 25.7183 292.421 26.8033 291.987 28.126C293.103 27.134 294.084 26.1833 294.932 25.274C295.8 24.3647 296.606 23.352 297.35 22.236C297.474 22.1327 297.691 22.081 298.001 22.081C298.29 22.081 298.569 22.1327 298.838 22.236C299.106 22.3187 299.292 22.4427 299.396 22.608C299.024 23.6 298.838 24.5817 298.838 25.553C298.838 26.4623 298.972 27.1443 299.241 27.599C299.22 27.723 299.075 27.9607 298.807 28.312C298.559 28.6633 298.28 28.994 297.97 29.304C297.412 29.0147 296.916 28.622 296.482 28.126C296.048 27.6093 295.727 27.0513 295.521 26.452C294.549 27.134 293.723 27.8057 293.041 28.467C292.359 29.1077 291.77 29.831 291.274 30.637C290.798 31.4223 290.385 32.3317 290.034 33.365C289.703 34.3983 289.383 35.628 289.073 37.054C288.887 37.0333 288.659 36.961 288.391 36.837C288.143 36.7337 287.884 36.5993 287.616 36.434C287.368 36.2687 287.14 36.093 286.934 35.907C286.727 35.721 286.593 35.5453 286.531 35.38Z" fill="#82027E"/>
                                        </svg>
                                        </div>
                                        
                                        <div class="offsvg">
                                        <svg width="268" height="114" viewBox="0 0 268 114" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M167.49 13.29H221.7" stroke-miterlimit="10" stroke="white"/>
<path d="M46 13.29H100.21" stroke-miterlimit="10" stroke="white"/>
<path d="M109.549 20V5.00364H119.478V7.61775H112.719V11.1911H118.819V13.8052H112.719V20H109.549ZM121.61 20V5.00364H124.781V17.3859H131.21V20H121.61ZM136.277 20H132.88L138.057 5.00364H142.143L147.312 20H143.915L140.158 8.43054H140.041L136.277 20ZM136.065 14.1054H144.09V16.5804H136.065V14.1054ZM146.714 7.61775V5.00364H159.03V7.61775H154.439V20H151.305V7.61775H146.714Z" fill="white"/>
<path d="M20.7756 79.8766C17.4233 79.8766 14.4474 79.2871 11.848 78.1082C9.26278 76.915 7.22443 75.2743 5.73295 73.1863C4.25568 71.0982 3.50284 68.6976 3.47443 65.9846H14.9801C15.0227 67.4903 15.6051 68.6905 16.7273 69.5854C17.8636 70.4803 19.2131 70.9278 20.7756 70.9278C21.983 70.9278 23.0483 70.6721 23.9716 70.1607C24.8949 69.6351 25.6193 68.8965 26.1449 67.9448C26.6705 66.9931 26.9261 65.8851 26.9119 64.6209C26.9261 63.3425 26.6705 62.2346 26.1449 61.2971C25.6193 60.3454 24.8949 59.6138 23.9716 59.1025C23.0483 58.5769 21.983 58.3141 20.7756 58.3141C19.5256 58.3141 18.3679 58.6195 17.3026 59.2303C16.2514 59.8269 15.4773 60.6579 14.9801 61.7232L4.58239 59.6778L6.28693 35.6437H35.6051V45.1039H16.0028L15.1506 54.7346H15.4062C16.0881 53.4136 17.267 52.3198 18.9432 51.4533C20.6193 50.5726 22.5653 50.1323 24.7812 50.1323C27.3807 50.1323 29.696 50.736 31.7273 51.9434C33.7727 53.1508 35.3849 54.8198 36.5639 56.9505C37.7571 59.067 38.3466 61.5101 38.3324 64.28C38.3466 67.3198 37.6222 70.0187 36.1591 72.3766C34.7102 74.7204 32.6719 76.5599 30.044 77.8951C27.4162 79.2161 24.3267 79.8766 20.7756 79.8766ZM62.4464 80.4732C58.4833 80.459 55.06 79.5428 52.1765 77.7246C49.293 75.9064 47.07 73.2857 45.5075 69.8624C43.945 66.4391 43.1708 62.334 43.185 57.5471C43.1992 52.7459 43.9805 48.6692 45.5288 45.317C47.0913 41.9647 49.3072 39.415 52.1765 37.6678C55.06 35.9207 58.4833 35.0471 62.4464 35.0471C66.4094 35.0471 69.8327 35.9278 72.7163 37.6891C75.5998 39.4363 77.8228 41.986 79.3853 45.3383C80.9478 48.6905 81.7219 52.7601 81.7077 57.5471C81.7077 62.3624 80.9265 66.4817 79.364 69.905C77.8015 73.3283 75.5785 75.9491 72.695 77.7672C69.8256 79.5712 66.4094 80.4732 62.4464 80.4732ZM62.4464 70.8425C64.4918 70.8425 66.168 69.7772 67.4748 67.6465C68.7958 65.5016 69.4492 62.1351 69.435 57.5471C69.435 54.5499 69.1367 52.0996 68.5401 50.1962C67.9435 48.2928 67.1197 46.8866 66.0685 45.9775C65.0174 45.0542 63.81 44.5925 62.4464 44.5925C60.4009 44.5925 58.7319 45.6295 57.4393 47.7033C56.1467 49.7772 55.4862 53.0584 55.4577 57.5471C55.4435 60.6011 55.7347 63.1153 56.3313 65.0897C56.9279 67.0499 57.7518 68.4988 58.8029 69.4363C59.8683 70.3738 61.0827 70.8425 62.4464 70.8425ZM112.24 71.0982V68.7971C112.24 66.9505 112.638 65.2459 113.434 63.6834C114.243 62.1209 115.415 60.8638 116.949 59.9121C118.483 58.9604 120.351 58.4846 122.553 58.4846C124.811 58.4846 126.708 58.9533 128.242 59.8908C129.79 60.8283 130.962 62.0783 131.757 63.6408C132.553 65.2033 132.951 66.9221 132.951 68.7971V71.0982C132.951 72.9448 132.546 74.6493 131.736 76.2118C130.941 77.7743 129.769 79.0314 128.221 79.9832C126.672 80.9349 124.783 81.4107 122.553 81.4107C120.323 81.4107 118.441 80.9349 116.907 79.9832C115.373 79.0314 114.208 77.7743 113.412 76.2118C112.631 74.6493 112.24 72.9448 112.24 71.0982ZM119.655 68.7971V71.0982C119.655 71.9079 119.854 72.7104 120.252 73.5059C120.664 74.2871 121.431 74.6778 122.553 74.6778C123.732 74.6778 124.506 74.2942 124.875 73.5272C125.259 72.7601 125.451 71.9505 125.451 71.0982V68.7971C125.451 67.9448 125.273 67.1209 124.918 66.3255C124.563 65.53 123.775 65.1323 122.553 65.1323C121.431 65.1323 120.664 65.53 120.252 66.3255C119.854 67.1209 119.655 67.9448 119.655 68.7971ZM89.3995 46.1266V43.8255C89.3995 41.9505 89.8043 40.2317 90.614 38.6692C91.4379 37.1067 92.6168 35.8567 94.1509 34.9192C95.685 33.9817 97.5387 33.513 99.712 33.513C101.971 33.513 103.867 33.9817 105.401 34.9192C106.949 35.8567 108.121 37.1067 108.917 38.6692C109.712 40.2317 110.11 41.9505 110.11 43.8255V46.1266C110.11 48.0016 109.705 49.7133 108.895 51.2616C108.1 52.8099 106.928 54.0457 105.38 54.9689C103.831 55.8922 101.942 56.3539 99.712 56.3539C97.4819 56.3539 95.5998 55.8851 94.0657 54.9476C92.5316 54.0101 91.3668 52.7672 90.5714 51.2189C89.7901 49.6707 89.3995 47.9732 89.3995 46.1266ZM96.8995 43.8255V46.1266C96.8995 46.9789 97.0984 47.7886 97.4961 48.5556C97.8938 49.3226 98.6325 49.7062 99.712 49.7062C100.891 49.7062 101.665 49.3226 102.034 48.5556C102.418 47.7886 102.61 46.9789 102.61 46.1266V43.8255C102.61 42.9732 102.432 42.1493 102.077 41.3539C101.722 40.5584 100.934 40.1607 99.712 40.1607C98.5898 40.1607 97.837 40.5726 97.4535 41.3965C97.0842 42.2062 96.8995 43.0158 96.8995 43.8255ZM93.0217 79.28L123.022 35.6437H129.073L99.0728 79.28H93.0217ZM194.847 57.4618C194.847 62.3198 193.902 66.4178 192.013 69.7559C190.124 73.0797 187.574 75.6011 184.364 77.3198C181.154 79.0243 177.574 79.8766 173.625 79.8766C169.648 79.8766 166.054 79.0172 162.844 77.2985C159.648 75.5655 157.105 73.0371 155.216 69.7133C153.341 66.3752 152.404 62.2914 152.404 57.4618C152.404 52.6039 153.341 48.513 155.216 45.1891C157.105 41.8511 159.648 39.3297 162.844 37.6252C166.054 35.9064 169.648 35.0471 173.625 35.0471C177.574 35.0471 181.154 35.9064 184.364 37.6252C187.574 39.3297 190.124 41.8511 192.013 45.1891C193.902 48.513 194.847 52.6039 194.847 57.4618ZM182.659 57.4618C182.659 54.8482 182.311 52.6465 181.615 50.8567C180.934 49.0528 179.918 47.6891 178.569 46.7658C177.233 45.8283 175.586 45.3596 173.625 45.3596C171.665 45.3596 170.01 45.8283 168.661 46.7658C167.326 47.6891 166.31 49.0528 165.614 50.8567C164.932 52.6465 164.591 54.8482 164.591 57.4618C164.591 60.0755 164.932 62.2843 165.614 64.0883C166.31 65.878 167.326 67.2417 168.661 68.1792C170.01 69.1025 171.665 69.5641 173.625 69.5641C175.586 69.5641 177.233 69.1025 178.569 68.1792C179.918 67.2417 180.934 65.878 181.615 64.0883C182.311 62.2843 182.659 60.0755 182.659 57.4618ZM200.328 79.28V35.6437H231.01V45.1891H212.175V52.6891H229.135V62.2346H212.175V79.28H200.328ZM235.367 79.28V35.6437H266.049V45.1891H247.214V52.6891H264.174V62.2346H247.214V79.28H235.367Z" fill="white"/>
<path d="M23.6469 101.364C23.6469 103.029 23.3312 104.446 22.6998 105.614C22.0734 106.783 21.2183 107.675 20.1345 108.292C19.0556 108.903 17.8426 109.209 16.4953 109.209C15.138 109.209 13.92 108.901 12.8411 108.284C11.7623 107.668 10.9097 106.775 10.2832 105.607C9.65683 104.439 9.34362 103.024 9.34362 101.364C9.34362 99.6982 9.65683 98.2812 10.2832 97.1129C10.9097 95.9446 11.7623 95.0547 12.8411 94.4432C13.92 93.8267 15.138 93.5185 16.4953 93.5185C17.8426 93.5185 19.0556 93.8267 20.1345 94.4432C21.2183 95.0547 22.0734 95.9446 22.6998 97.1129C23.3312 98.2812 23.6469 99.6982 23.6469 101.364ZM20.3731 101.364C20.3731 100.285 20.2115 99.375 19.8884 98.6342C19.5702 97.8935 19.1203 97.3317 18.5386 96.9489C17.9569 96.5661 17.2758 96.3746 16.4953 96.3746C15.7147 96.3746 15.0336 96.5661 14.4519 96.9489C13.8703 97.3317 13.4178 97.8935 13.0947 98.6342C12.7765 99.375 12.6174 100.285 12.6174 101.364C12.6174 102.442 12.7765 103.352 13.0947 104.093C13.4178 104.834 13.8703 105.396 14.4519 105.778C15.0336 106.161 15.7147 106.353 16.4953 106.353C17.2758 106.353 17.9569 106.161 18.5386 105.778C19.1203 105.396 19.5702 104.834 19.8884 104.093C20.2115 103.352 20.3731 102.442 20.3731 101.364ZM29.156 102.378V109H25.9792V97.5455H29.0069V99.5664H29.1411C29.3947 98.9002 29.8197 98.3732 30.4163 97.9854C31.0129 97.5927 31.7363 97.3963 32.5864 97.3963C33.3819 97.3963 34.0754 97.5703 34.667 97.9183C35.2587 98.2663 35.7185 98.7635 36.0467 99.4098C36.3748 100.051 36.5388 100.817 36.5388 101.707V109H33.362V102.273C33.367 101.572 33.188 101.026 32.8251 100.633C32.4621 100.235 31.9625 100.036 31.3261 100.036C30.8986 100.036 30.5207 100.128 30.1926 100.312C29.8695 100.496 29.6159 100.765 29.432 101.118C29.253 101.466 29.161 101.886 29.156 102.378ZM46.5951 109H43.1349L48.4073 93.7273H52.5685L57.8334 109H54.3732L50.5475 97.2173H50.4282L46.5951 109ZM46.3789 102.997H54.5522V105.517H46.3789V102.997ZM62.7888 93.7273V109H59.612V93.7273H62.7888ZM68.5105 93.7273V109H65.3337V93.7273H68.5105ZM79.2044 93.7273V109H75.9754V93.7273H79.2044ZM84.9783 102.378V109H81.8014V97.5455H84.8291V99.5664H84.9634C85.2169 98.9002 85.642 98.3732 86.2386 97.9854C86.8352 97.5927 87.5585 97.3963 88.4087 97.3963C89.2041 97.3963 89.8977 97.5703 90.4893 97.9183C91.0809 98.2663 91.5408 98.7635 91.8689 99.4098C92.197 100.051 92.3611 100.817 92.3611 101.707V109H89.1843V102.273C89.1892 101.572 89.0103 101.026 88.6473 100.633C88.2844 100.235 87.7848 100.036 87.1484 100.036C86.7208 100.036 86.343 100.128 86.0149 100.312C85.6917 100.496 85.4382 100.765 85.2542 101.118C85.0752 101.466 84.9833 101.886 84.9783 102.378ZM102.002 101.468V103.989H95.0364V101.468H102.002ZM104.768 109V93.7273H107.997V100.029H114.552V93.7273H117.774V109H114.552V102.691H107.997V109H104.768ZM125.552 109.224C124.394 109.224 123.392 108.978 122.547 108.485C121.707 107.988 121.058 107.297 120.6 106.412C120.143 105.522 119.914 104.491 119.914 103.317C119.914 102.134 120.143 101.1 120.6 100.215C121.058 99.3253 121.707 98.6342 122.547 98.142C123.392 97.6449 124.394 97.3963 125.552 97.3963C126.71 97.3963 127.71 97.6449 128.55 98.142C129.395 98.6342 130.046 99.3253 130.504 100.215C130.961 101.1 131.19 102.134 131.19 103.317C131.19 104.491 130.961 105.522 130.504 106.412C130.046 107.297 129.395 107.988 128.55 108.485C127.71 108.978 126.71 109.224 125.552 109.224ZM125.567 106.763C126.094 106.763 126.534 106.614 126.887 106.315C127.24 106.012 127.506 105.599 127.685 105.077C127.869 104.555 127.961 103.961 127.961 103.295C127.961 102.629 127.869 102.035 127.685 101.513C127.506 100.991 127.24 100.578 126.887 100.275C126.534 99.9716 126.094 99.82 125.567 99.82C125.035 99.82 124.588 99.9716 124.225 100.275C123.867 100.578 123.596 100.991 123.412 101.513C123.233 102.035 123.143 102.629 123.143 103.295C123.143 103.961 123.233 104.555 123.412 105.077C123.596 105.599 123.867 106.012 124.225 106.315C124.588 106.614 125.035 106.763 125.567 106.763ZM140.601 104.123V97.5455H143.778V109H140.728V106.919H140.609C140.35 107.591 139.92 108.13 139.318 108.538C138.722 108.945 137.993 109.149 137.133 109.149C136.368 109.149 135.694 108.975 135.112 108.627C134.531 108.279 134.076 107.784 133.748 107.143C133.425 106.502 133.261 105.734 133.256 104.839V97.5455H136.432V104.272C136.437 104.948 136.619 105.483 136.977 105.875C137.335 106.268 137.814 106.464 138.416 106.464C138.799 106.464 139.157 106.377 139.49 106.203C139.823 106.025 140.091 105.761 140.295 105.413C140.504 105.065 140.606 104.635 140.601 104.123ZM155.85 100.812L152.941 100.991C152.891 100.742 152.785 100.518 152.621 100.32C152.456 100.116 152.24 99.9542 151.972 99.8349C151.708 99.7106 151.393 99.6484 151.025 99.6484C150.532 99.6484 150.117 99.7528 149.779 99.9616C149.441 100.165 149.272 100.439 149.272 100.782C149.272 101.055 149.382 101.287 149.6 101.475C149.819 101.664 150.194 101.816 150.726 101.93L152.799 102.348C153.913 102.577 154.743 102.945 155.29 103.452C155.837 103.959 156.111 104.625 156.111 105.45C156.111 106.201 155.889 106.86 155.447 107.426C155.009 107.993 154.408 108.436 153.642 108.754C152.882 109.067 152.004 109.224 151.01 109.224C149.493 109.224 148.285 108.908 147.385 108.277C146.491 107.64 145.966 106.775 145.812 105.681L148.937 105.517C149.031 105.98 149.26 106.333 149.623 106.576C149.986 106.815 150.45 106.934 151.017 106.934C151.574 106.934 152.021 106.827 152.36 106.614C152.703 106.395 152.877 106.114 152.882 105.771C152.877 105.483 152.755 105.246 152.516 105.062C152.277 104.874 151.91 104.729 151.412 104.63L149.429 104.235C148.31 104.011 147.477 103.623 146.931 103.071C146.389 102.52 146.118 101.816 146.118 100.961C146.118 100.225 146.317 99.5913 146.714 99.0593C147.117 98.5273 147.681 98.1172 148.407 97.8288C149.138 97.5405 149.993 97.3963 150.972 97.3963C152.419 97.3963 153.558 97.7021 154.388 98.3136C155.223 98.9251 155.71 99.7578 155.85 100.812ZM163.331 109.224C162.153 109.224 161.139 108.985 160.289 108.508C159.443 108.026 158.792 107.344 158.335 106.464C157.877 105.58 157.649 104.533 157.649 103.325C157.649 102.147 157.877 101.113 158.335 100.223C158.792 99.3327 159.436 98.6392 160.266 98.142C161.101 97.6449 162.081 97.3963 163.204 97.3963C163.96 97.3963 164.664 97.5181 165.315 97.7617C165.971 98.0004 166.543 98.3608 167.03 98.843C167.522 99.3253 167.905 99.9318 168.178 100.663C168.452 101.388 168.589 102.239 168.589 103.213V104.086H158.916V102.117H165.598C165.598 101.659 165.499 101.254 165.3 100.901C165.101 100.548 164.825 100.272 164.472 100.074C164.124 99.8697 163.719 99.7678 163.257 99.7678C162.774 99.7678 162.347 99.8796 161.974 100.103C161.606 100.322 161.318 100.618 161.109 100.991C160.9 101.359 160.793 101.769 160.788 102.221V104.093C160.788 104.66 160.893 105.15 161.101 105.562C161.315 105.975 161.616 106.293 162.004 106.517C162.392 106.74 162.851 106.852 163.383 106.852C163.736 106.852 164.06 106.803 164.353 106.703C164.646 106.604 164.897 106.455 165.106 106.256C165.315 106.057 165.474 105.813 165.583 105.525L168.522 105.719C168.372 106.425 168.067 107.041 167.604 107.568C167.147 108.09 166.555 108.498 165.829 108.791C165.109 109.08 164.276 109.224 163.331 109.224ZM189.057 99.0742H185.791C185.731 98.6516 185.61 98.2763 185.426 97.9482C185.242 97.6151 185.005 97.3317 184.717 97.098C184.429 96.8643 184.096 96.6854 183.718 96.5611C183.345 96.4368 182.94 96.3746 182.502 96.3746C181.712 96.3746 181.023 96.571 180.437 96.9638C179.85 97.3516 179.395 97.9183 179.072 98.6641C178.749 99.4048 178.587 100.305 178.587 101.364C178.587 102.452 178.749 103.367 179.072 104.108C179.4 104.849 179.857 105.408 180.444 105.786C181.031 106.164 181.709 106.353 182.48 106.353C182.912 106.353 183.313 106.295 183.681 106.181C184.053 106.067 184.384 105.9 184.672 105.681C184.961 105.458 185.199 105.187 185.388 104.869C185.582 104.55 185.716 104.188 185.791 103.78L189.057 103.795C188.973 104.496 188.762 105.172 188.423 105.823C188.09 106.469 187.64 107.049 187.074 107.561C186.512 108.068 185.841 108.471 185.06 108.769C184.285 109.062 183.407 109.209 182.428 109.209C181.065 109.209 179.847 108.901 178.774 108.284C177.705 107.668 176.86 106.775 176.238 105.607C175.622 104.439 175.313 103.024 175.313 101.364C175.313 99.6982 175.627 98.2812 176.253 97.1129C176.879 95.9446 177.73 95.0547 178.803 94.4432C179.877 93.8267 181.085 93.5185 182.428 93.5185C183.313 93.5185 184.133 93.6428 184.889 93.8913C185.649 94.1399 186.323 94.5028 186.91 94.9801C187.496 95.4524 187.973 96.0316 188.341 96.7177C188.714 97.4038 188.953 98.1893 189.057 99.0742ZM196.489 109.224C195.33 109.224 194.328 108.978 193.483 108.485C192.643 107.988 191.994 107.297 191.537 106.412C191.079 105.522 190.851 104.491 190.851 103.317C190.851 102.134 191.079 101.1 191.537 100.215C191.994 99.3253 192.643 98.6342 193.483 98.142C194.328 97.6449 195.33 97.3963 196.489 97.3963C197.647 97.3963 198.646 97.6449 199.486 98.142C200.332 98.6342 200.983 99.3253 201.44 100.215C201.898 101.1 202.126 102.134 202.126 103.317C202.126 104.491 201.898 105.522 201.44 106.412C200.983 107.297 200.332 107.988 199.486 108.485C198.646 108.978 197.647 109.224 196.489 109.224ZM196.504 106.763C197.031 106.763 197.47 106.614 197.823 106.315C198.176 106.012 198.442 105.599 198.621 105.077C198.805 104.555 198.897 103.961 198.897 103.295C198.897 102.629 198.805 102.035 198.621 101.513C198.442 100.991 198.176 100.578 197.823 100.275C197.47 99.9716 197.031 99.82 196.504 99.82C195.972 99.82 195.524 99.9716 195.161 100.275C194.803 100.578 194.532 100.991 194.348 101.513C194.169 102.035 194.08 102.629 194.08 103.295C194.08 103.961 194.169 104.555 194.348 105.077C194.532 105.599 194.803 106.012 195.161 106.315C195.524 106.614 195.972 106.763 196.504 106.763ZM211.538 104.123V97.5455H214.714V109H211.664V106.919H211.545C211.287 107.591 210.856 108.13 210.255 108.538C209.658 108.945 208.93 109.149 208.07 109.149C207.304 109.149 206.631 108.975 206.049 108.627C205.467 108.279 205.012 107.784 204.684 107.143C204.361 106.502 204.197 105.734 204.192 104.839V97.5455H207.369V104.272C207.374 104.948 207.555 105.483 207.913 105.875C208.271 106.268 208.751 106.464 209.353 106.464C209.735 106.464 210.093 106.377 210.426 106.203C210.76 106.025 211.028 105.761 211.232 105.413C211.441 105.065 211.543 104.635 211.538 104.123ZM217.256 109V97.5455H220.335V99.544H220.455C220.664 98.8331 221.014 98.2962 221.506 97.9332C221.998 97.5653 222.565 97.3814 223.207 97.3814C223.366 97.3814 223.537 97.3913 223.721 97.4112C223.905 97.4311 224.067 97.4585 224.206 97.4933V100.312C224.057 100.267 223.85 100.228 223.587 100.193C223.323 100.158 223.082 100.141 222.864 100.141C222.396 100.141 221.979 100.243 221.611 100.446C221.248 100.645 220.959 100.924 220.746 101.282C220.537 101.64 220.432 102.052 220.432 102.52V109H217.256ZM235.379 100.812L232.47 100.991C232.421 100.742 232.314 100.518 232.15 100.32C231.986 100.116 231.769 99.9542 231.501 99.8349C231.238 99.7106 230.922 99.6484 230.554 99.6484C230.062 99.6484 229.647 99.7528 229.309 99.9616C228.97 100.165 228.801 100.439 228.801 100.782C228.801 101.055 228.911 101.287 229.13 101.475C229.348 101.664 229.724 101.816 230.256 101.93L232.329 102.348C233.442 102.577 234.273 102.945 234.82 103.452C235.366 103.959 235.64 104.625 235.64 105.45C235.64 106.201 235.419 106.86 234.976 107.426C234.539 107.993 233.937 108.436 233.171 108.754C232.411 109.067 231.533 109.224 230.539 109.224C229.023 109.224 227.815 108.908 226.915 108.277C226.02 107.64 225.495 106.775 225.341 105.681L228.466 105.517C228.56 105.98 228.789 106.333 229.152 106.576C229.515 106.815 229.98 106.934 230.546 106.934C231.103 106.934 231.551 106.827 231.889 106.614C232.232 106.395 232.406 106.114 232.411 105.771C232.406 105.483 232.284 105.246 232.045 105.062C231.807 104.874 231.439 104.729 230.942 104.63L228.958 104.235C227.839 104.011 227.007 103.623 226.46 103.071C225.918 102.52 225.647 101.816 225.647 100.961C225.647 100.225 225.846 99.5913 226.244 99.0593C226.646 98.5273 227.211 98.1172 227.936 97.8288C228.667 97.5405 229.522 97.3963 230.502 97.3963C231.948 97.3963 233.087 97.7021 233.917 98.3136C234.752 98.9251 235.24 99.7578 235.379 100.812ZM242.86 109.224C241.682 109.224 240.668 108.985 239.818 108.508C238.973 108.026 238.321 107.344 237.864 106.464C237.407 105.58 237.178 104.533 237.178 103.325C237.178 102.147 237.407 101.113 237.864 100.223C238.321 99.3327 238.965 98.6392 239.796 98.142C240.631 97.6449 241.61 97.3963 242.734 97.3963C243.489 97.3963 244.193 97.5181 244.844 97.7617C245.5 98.0004 246.072 98.3608 246.559 98.843C247.052 99.3253 247.434 99.9318 247.708 100.663C247.981 101.388 248.118 102.239 248.118 103.213V104.086H238.446V102.117H245.128C245.128 101.659 245.028 101.254 244.829 100.901C244.63 100.548 244.354 100.272 244.001 100.074C243.653 99.8697 243.248 99.7678 242.786 99.7678C242.304 99.7678 241.876 99.8796 241.503 100.103C241.135 100.322 240.847 100.618 240.638 100.991C240.429 101.359 240.322 101.769 240.318 102.221V104.093C240.318 104.66 240.422 105.15 240.631 105.562C240.844 105.975 241.145 106.293 241.533 106.517C241.921 106.74 242.381 106.852 242.913 106.852C243.266 106.852 243.589 106.803 243.882 106.703C244.175 106.604 244.427 106.455 244.635 106.256C244.844 106.057 245.003 105.813 245.113 105.525L248.051 105.719C247.902 106.425 247.596 107.041 247.134 107.568C246.676 108.09 246.085 108.498 245.359 108.791C244.638 109.08 243.805 109.224 242.86 109.224ZM259.722 100.812L256.813 100.991C256.764 100.742 256.657 100.518 256.493 100.32C256.329 100.116 256.112 99.9542 255.844 99.8349C255.58 99.7106 255.265 99.6484 254.897 99.6484C254.405 99.6484 253.989 99.7528 253.651 99.9616C253.313 100.165 253.144 100.439 253.144 100.782C253.144 101.055 253.254 101.287 253.472 101.475C253.691 101.664 254.066 101.816 254.598 101.93L256.672 102.348C257.785 102.577 258.615 102.945 259.162 103.452C259.709 103.959 259.983 104.625 259.983 105.45C259.983 106.201 259.761 106.86 259.319 107.426C258.881 107.993 258.28 108.436 257.514 108.754C256.754 109.067 255.876 109.224 254.882 109.224C253.365 109.224 252.157 108.908 251.258 108.277C250.363 107.64 249.838 106.775 249.684 105.681L252.809 105.517C252.903 105.98 253.132 106.333 253.495 106.576C253.858 106.815 254.322 106.934 254.889 106.934C255.446 106.934 255.894 106.827 256.232 106.614C256.575 106.395 256.749 106.114 256.754 105.771C256.749 105.483 256.627 105.246 256.388 105.062C256.15 104.874 255.782 104.729 255.284 104.63L253.301 104.235C252.182 104.011 251.349 103.623 250.803 103.071C250.261 102.52 249.99 101.816 249.99 100.961C249.99 100.225 250.189 99.5913 250.586 99.0593C250.989 98.5273 251.553 98.1172 252.279 97.8288C253.01 97.5405 253.865 97.3963 254.844 97.3963C256.291 97.3963 257.43 97.7021 258.26 98.3136C259.095 98.9251 259.582 99.7578 259.722 100.812Z" fill="white"/>
<g style="mix-blend-mode:multiply">
<path d="M79.9999 87.9478C79.9999 89.5874 72.2146 90.9259 62.5999 90.9259C52.9853 90.9259 45.2 89.5986 45.2 87.9478C45.2 86.297 52.9965 84.9697 62.5999 84.9697C72.2034 84.9697 79.9999 86.297 79.9999 87.9478Z" fill="#E8B6A7"/>
</g>
<path d="M75.8619 78.9022C75.8619 80.263 69.8946 81.3673 62.5443 81.3673C55.1939 81.3673 49.2266 80.263 49.2266 78.9022C49.2266 77.5415 55.1939 76.4373 62.5443 76.4373C69.8946 76.4373 75.8619 77.5415 75.8619 78.9022Z" fill="#A33416"/>
<path d="M62.533 80.9768C59.4323 80.9768 56.4207 80.7761 54.0784 80.408C52.3496 80.1403 51.368 79.8391 50.8326 79.6161C51.368 79.393 52.3496 79.103 54.0784 78.8241C56.4319 78.4561 59.4323 78.2441 62.533 78.2441C65.6338 78.2441 68.6453 78.4449 70.9876 78.8241C72.7165 79.0918 73.698 79.393 74.2334 79.6161C73.698 79.8391 72.7165 80.1291 70.9876 80.408C68.6342 80.7761 65.6338 80.9768 62.533 80.9768Z" fill="#591D0C"/>
<path d="M62.6334 81.1441V80.2964C62.89 80.2964 63.1465 80.2964 63.3919 80.2964V81.1441C63.1465 81.1441 62.89 81.1441 62.6334 81.1441ZM62.5553 81.1441H62.5442C62.2877 81.1441 62.0311 81.1441 61.7746 81.1441V80.2964C62.0311 80.2964 62.2877 80.2964 62.5442 80.2964H62.5553V81.1441ZM61.6853 81.1441C61.4288 81.1441 61.1723 81.1441 60.9157 81.133L60.938 80.2853C61.1946 80.2853 61.44 80.2853 61.6965 80.2964V81.1441H61.6853ZM63.4811 81.133V80.2853C63.7265 80.2853 63.983 80.2853 64.2284 80.2741L64.2507 81.1218C63.9942 81.1218 63.7376 81.1218 63.4811 81.133ZM60.8488 81.1218C60.5923 81.1218 60.3357 81.1107 60.0792 81.0995L60.1015 80.2518C60.358 80.2518 60.6034 80.263 60.86 80.2741L60.8377 81.1218H60.8488ZM64.3176 81.1218L64.2953 80.2741C64.5519 80.2741 64.8084 80.263 65.0538 80.2518L65.0873 81.0995C64.8307 81.0995 64.5742 81.1107 64.3176 81.1218ZM60.0011 81.0995C59.7446 81.0995 59.488 81.0772 59.2315 81.066L59.265 80.2184C59.5103 80.2184 59.7669 80.2407 60.0234 80.2518L60.0011 81.0995ZM65.1653 81.0995L65.1319 80.2518C65.3884 80.2518 65.645 80.2295 65.8903 80.2184L65.9238 81.066C65.6673 81.0772 65.4107 81.0883 65.1542 81.0995H65.1653ZM59.1534 81.066C58.8969 81.0549 58.6403 81.0437 58.3838 81.0214L58.4284 80.1737C58.6738 80.1849 58.9303 80.196 59.1869 80.2072L59.1534 81.0549V81.066ZM66.013 81.066L65.9796 80.2184C66.2361 80.2072 66.4927 80.196 66.738 80.1737L66.7826 81.0214C66.5261 81.0326 66.2696 81.0437 66.013 81.066ZM58.3057 81.0214C58.0492 81.0103 57.7927 80.988 57.5361 80.9768L57.5919 80.1403C57.8373 80.1626 58.0938 80.1737 58.3503 80.1849L58.3057 81.0326V81.0214ZM66.8607 81.0214L66.805 80.1737C67.0615 80.1626 67.318 80.1403 67.5634 80.1291L67.6192 80.9657C67.3626 80.988 67.1061 80.9991 66.8496 81.0214H66.8607ZM57.458 80.9657C57.2015 80.9434 56.945 80.9322 56.6884 80.9099L56.7553 80.0733C57.0007 80.0957 57.2573 80.118 57.5138 80.1291L57.458 80.9657ZM67.7084 80.9657L67.6415 80.118C67.898 80.0956 68.1546 80.0845 68.4 80.0622L68.478 80.8987C68.2215 80.921 67.965 80.9434 67.7084 80.9657ZM56.6103 80.8987C56.3538 80.8764 56.0973 80.8541 55.8407 80.8318L55.93 79.9953C56.1753 80.0176 56.4319 80.0399 56.6884 80.0622L56.6103 80.8987ZM68.5561 80.8987L68.478 80.0622C68.7346 80.0399 68.9911 80.0176 69.2365 79.9841L69.3257 80.8207C69.0803 80.843 68.8127 80.8764 68.5561 80.8987ZM55.7627 80.8207C55.5061 80.7983 55.2384 80.7649 54.993 80.7314L55.0934 79.8949C55.3388 79.9283 55.5953 79.9507 55.8519 79.9841L55.7627 80.8207ZM69.4038 80.8095L69.3146 79.973C69.5711 79.9507 69.8277 79.9172 70.073 79.8837L70.1734 80.7203C69.928 80.7537 69.6715 80.776 69.4038 80.8095ZM54.915 80.7314C54.6584 80.698 54.3907 80.6645 54.1453 80.631L54.268 79.7945C54.5134 79.828 54.7588 79.8614 55.0153 79.8949L54.915 80.7314ZM70.2515 80.7203L70.1511 79.8837C70.4077 79.8503 70.6642 79.8168 70.8984 79.7834L71.0211 80.6199C70.7757 80.6534 70.5192 80.6868 70.2515 80.7203ZM54.0784 80.6199C53.8219 80.5864 53.5542 80.5418 53.3088 80.4972L53.4427 79.6607C53.688 79.7053 53.9334 79.7387 54.19 79.7722L54.0673 80.6087L54.0784 80.6199ZM71.088 80.6087L70.9653 79.7722C71.2219 79.7387 71.4673 79.6941 71.7127 79.6495L71.8577 80.486C71.6123 80.5307 71.3557 80.5641 71.088 80.6087ZM53.2307 80.486L53.1192 80.4637C52.8961 80.4191 52.673 80.3857 52.4723 80.341L52.6396 79.5157C52.8403 79.5603 53.0523 79.5937 53.2754 79.6384L53.3869 79.6607L53.2419 80.4972L53.2307 80.486ZM71.9357 80.4749L71.7907 79.6384H71.813C72.0584 79.5937 72.3038 79.5491 72.5269 79.5045L72.6942 80.3299C72.46 80.3745 72.2146 80.4191 71.958 80.4749H71.9357ZM52.3942 80.3299C52.1265 80.2741 51.87 80.2184 51.6357 80.1626L51.8365 79.3483C52.0596 79.4041 52.305 79.4599 52.5615 79.5157L52.3942 80.341V80.3299ZM72.7723 80.3076L72.605 79.4822C72.8615 79.4264 73.1069 79.3706 73.33 79.3149L73.5419 80.1291C73.3077 80.1849 73.0511 80.2518 72.7834 80.3076H72.7723ZM51.5465 80.1403C51.2677 80.0733 51.0111 79.9953 50.788 79.9283L51.0446 79.1253C51.2565 79.1922 51.4907 79.2591 51.7584 79.326L51.5577 80.1403H51.5465ZM73.62 80.118L73.408 79.3037C73.6646 79.2368 73.8988 79.1699 74.1107 79.0918L74.3784 79.8949C74.1442 79.973 73.8877 80.051 73.62 80.118ZM50.71 79.8949C50.4088 79.7945 50.1523 79.6941 49.9515 79.5937L50.3307 78.8353C50.5092 78.9245 50.7323 79.0137 50.9888 79.0918L50.7211 79.8949H50.71ZM74.4565 79.8614L74.1777 79.0583C74.4342 78.9691 74.6573 78.8799 74.8246 78.7906L75.2261 79.538C75.0142 79.6495 74.7577 79.761 74.4565 79.8614ZM49.8734 79.5491C49.4161 79.3037 49.1819 79.0472 49.1819 78.7684C49.1819 78.6345 49.2377 78.5118 49.338 78.3891L49.985 78.9245C49.985 78.9245 49.985 78.9245 49.9961 78.9022L49.4273 78.2887C49.5946 78.1326 49.8623 77.9764 50.208 77.8314L50.5315 78.6122C50.3865 78.668 50.275 78.7237 50.1969 78.7684C50.2192 78.7684 50.2415 78.7907 50.2638 78.8018L49.8734 79.5491ZM75.293 79.5045L74.8803 78.7795C74.7911 78.7237 74.6461 78.6568 74.4565 78.5787L74.7688 77.798C75.1034 77.9318 75.36 78.0657 75.5496 78.2107L75.0588 78.8464C75.0588 78.8464 75.0588 78.8799 75.0811 78.9022L75.6388 78.2776C75.8061 78.4337 75.8953 78.601 75.8953 78.7684C75.8953 79.036 75.6946 79.2703 75.3042 79.5045H75.293ZM50.5984 78.5787L50.2861 77.798C50.5092 77.7087 50.7657 77.6195 51.0446 77.5414L51.2788 78.3556C51.0223 78.4337 50.788 78.5118 50.5984 78.5899V78.5787ZM74.4007 78.5564C74.2 78.4783 73.9657 78.4003 73.7092 78.3334L73.9434 77.5191C74.2223 77.5972 74.4788 77.6864 74.7019 77.7757L74.4007 78.5676V78.5564ZM51.3569 78.3334L51.1227 77.5191C51.3569 77.4522 51.6134 77.3853 51.8811 77.3183L52.0707 78.1437C51.8142 78.1995 51.5688 78.2664 51.3457 78.3334H51.3569ZM73.6311 78.2999C73.408 78.233 73.1627 78.1772 72.9061 78.1214L73.0957 77.296C73.3634 77.3518 73.62 77.4187 73.8542 77.4857L73.6311 78.2999ZM52.1488 78.1326L51.9592 77.3072C52.1934 77.2514 52.4611 77.1957 52.7288 77.151L52.885 77.9764C52.6284 78.021 52.383 78.0768 52.1488 78.1214V78.1326ZM72.8392 78.1103C72.605 78.0545 72.3596 78.0099 72.103 77.9653L72.2592 77.1399C72.5269 77.1845 72.7834 77.2403 73.0288 77.296L72.8503 78.1214L72.8392 78.1103ZM52.963 77.9653L52.8069 77.1399C52.9073 77.1176 53.0077 77.1064 53.1192 77.0841C53.2642 77.0618 53.4203 77.0284 53.5765 77.006L53.7104 77.8426C53.5654 77.8649 53.4092 77.8872 53.2754 77.9206C53.175 77.943 53.0746 77.9541 52.9742 77.9764L52.963 77.9653ZM72.025 77.943C71.958 77.9318 71.88 77.9207 71.813 77.9095C71.6457 77.876 71.4673 77.8537 71.2888 77.8203L71.4227 76.9837C71.6123 77.0172 71.7907 77.0395 71.9692 77.073C72.0473 77.0841 72.1142 77.0953 72.1923 77.1176L72.0361 77.943H72.025ZM53.7773 77.8203L53.6434 76.9837C53.8888 76.9503 54.1453 76.9057 54.413 76.8722L54.5246 77.7087C54.268 77.7422 54.0227 77.7757 53.7773 77.8203ZM71.2107 77.8091C70.9653 77.7757 70.72 77.731 70.4634 77.6976L70.575 76.861C70.8315 76.8945 71.0992 76.928 71.3446 76.9726L71.2107 77.8091ZM54.6027 77.7087L54.4911 76.8722C54.7365 76.8387 54.993 76.8053 55.2607 76.783L55.3611 77.6195C55.1046 77.653 54.848 77.6753 54.6138 77.7087H54.6027ZM70.3853 77.6976C70.14 77.6641 69.8834 77.6307 69.638 77.6083L69.7273 76.7718C69.9838 76.8053 70.2515 76.8276 70.4969 76.861L70.3853 77.6976ZM55.428 77.6083L55.3388 76.7718C55.5842 76.7383 55.8407 76.716 56.1084 76.6937L56.1865 77.5303C55.93 77.5526 55.6734 77.586 55.428 77.6083ZM69.56 77.5972C69.3146 77.5749 69.058 77.5414 68.8015 77.5191L68.8796 76.6826C69.1361 76.7049 69.3926 76.7383 69.6492 76.7607L69.56 77.5972ZM56.2646 77.5191L56.1865 76.6826C56.443 76.6603 56.6996 76.638 56.9561 76.6157L57.023 77.4522C56.7665 77.4745 56.51 77.4968 56.2646 77.5191ZM68.7234 77.508C68.478 77.4857 68.2215 77.4633 67.965 77.441L68.0319 76.5934C68.2884 76.6157 68.545 76.638 68.8015 76.6603L68.7234 77.4968V77.508ZM57.09 77.441L57.023 76.6045C57.2796 76.5822 57.5361 76.571 57.7927 76.5487L57.8484 77.3964C57.5919 77.4076 57.3353 77.4299 57.09 77.4522V77.441ZM67.898 77.441C67.6526 77.4187 67.3961 77.4076 67.1396 77.3853L67.1953 76.5376C67.4519 76.5599 67.7084 76.571 67.965 76.5934L67.898 77.4299V77.441ZM57.9265 77.3853L57.8707 76.5376C58.1273 76.5153 58.3838 76.5041 58.6403 76.493L58.685 77.3407C58.4284 77.3518 58.1719 77.3741 57.9265 77.3853ZM67.0615 77.3853C66.8161 77.3741 66.5596 77.3518 66.303 77.3407L66.3476 76.493C66.6042 76.5041 66.8607 76.5153 67.1173 76.5376L67.0615 77.3853ZM58.763 77.3407L58.7184 76.493C58.975 76.4818 59.2315 76.4707 59.488 76.4595L59.5215 77.3072C59.265 77.3072 59.0084 77.3295 58.763 77.3407ZM66.225 77.3295C65.9796 77.3183 65.723 77.3072 65.4665 77.296L65.5 76.4484C65.7565 76.4484 66.013 76.4707 66.2696 76.4818L66.225 77.3295ZM59.5996 77.296L59.5661 76.4484C59.8227 76.4484 60.0792 76.426 60.3357 76.426L60.358 77.2737C60.1015 77.2737 59.845 77.2849 59.5996 77.296ZM65.3884 77.296C65.1319 77.296 64.8865 77.2737 64.63 77.2737L64.6523 76.426C64.9088 76.426 65.1653 76.4372 65.4219 76.4484L65.3884 77.296ZM60.4361 77.2737L60.4138 76.426C60.6703 76.426 60.9269 76.4149 61.1834 76.4037V77.2514C60.938 77.2514 60.6927 77.2514 60.4361 77.2737ZM64.5519 77.2737C64.2953 77.2737 64.05 77.2626 63.7934 77.2514V76.4037C64.0611 76.4037 64.3177 76.4037 64.5742 76.4149L64.5519 77.2626V77.2737ZM61.2727 77.2514V76.4037C61.518 76.4037 61.7746 76.4037 62.0311 76.4037V77.2514C61.7857 77.2514 61.5292 77.2514 61.2727 77.2514ZM63.7153 77.2514C63.4588 77.2514 63.2134 77.2514 62.9569 77.2514V76.4037C63.2246 76.4037 63.4811 76.4037 63.7265 76.4037V77.2514H63.7153ZM62.1092 77.2403V76.3926C62.3657 76.3926 62.6223 76.3926 62.8788 76.3926V77.2403C62.6223 77.2403 62.3657 77.2403 62.1092 77.2403Z" fill="#F7AC34"/>
<path d="M49.2153 79.0806C50.8549 82.2817 54.0115 86.4533 56.5099 88.461C56.5099 88.461 62.8453 90.7586 68.8796 88.461C71.3446 86.3083 74.133 83.2633 75.8619 79.0806C75.8619 79.0806 73.4415 81.1663 62.5442 81.1663C51.6469 81.1663 49.2265 79.0806 49.2265 79.0806H49.2153Z" fill="#872B13"/>
<path d="M62.533 79.761C63.0684 78.8464 67.3961 76.5041 67.5299 70.0683C67.6526 64.1568 63.8157 59.9853 62.533 56.2041C61.2503 59.9964 57.4134 64.1568 57.5361 70.0683C57.6699 76.5041 61.9976 78.8353 62.533 79.761Z" fill="#F7D716"/>
<path d="M62.533 79.2032C62.8899 78.6009 65.7341 77.0617 65.8234 72.8232C65.9015 68.9305 63.3807 66.1867 62.533 63.6882C61.6965 66.1867 59.1645 68.9305 59.2426 72.8232C59.3318 77.0617 62.1761 78.6009 62.533 79.2032Z" fill="#FF8E1F"/>
<g opacity="0.7">
<path d="M62.533 79.2367C62.7672 78.824 64.6968 77.7867 64.7526 74.9202C64.8083 72.2879 63.1018 70.4252 62.533 68.741C61.9641 70.4252 60.2576 72.2879 60.3133 74.9202C60.3691 77.7867 62.2987 78.824 62.533 79.2367Z" fill="#FF3B00"/>
</g>
</svg>

                                        </div>

                                <!-- </div> -->

                            </div>

                            <div class="col-lg-6 bg-white px-0 py-3  px-lg-4 py-lg-5">

                                <div id="newpopugvupgform_dv" class="py-3 py-lg-0">
                                    <div
                                        class="global-form-head d-flex  px-3 align-items-center justify-content-between mb-4 ">
                                        <img loading="lazy" src="assets/dit/images/home/iitroorkee-logo-sm.webp" class="img-fluid"
                                            alt="roorkee-institute" style="max-height:76px;" />

                                    </div>
                                    <h5 class="heading mb-0 text-nowrap text-start px-3" style="">Talk To <span>Our
                                            Counselor</span></h5>
                                    <form class="Newtalktocounsellorfmd form mt-2 mt-lg-4 px-3" method="post"
                                        onsubmit="return false">
                                        <input type="hidden" name="url_source" class="enqurl_source"
                                            id="Dmenqurl_source">
                                        <input type="hidden" name="form_name" id="Successform_name2"
                                            value="popup enquiry form">

                                        <div class="form-group">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <label for="firstname" class="visually-hidden">First Name</label>
                                                    <input type="text" class="form-control rounded-3 inpheftauj"
                                                        name="firstname" id="firstname" placeholder="First Name*"
                                                        required>
                                                </div>
                                                <div class="col-6">
                                                    <label for="lastname" class="visually-hidden">Last Name</label>
                                                    <input type="text" class="form-control rounded-3 inpheftauj"
                                                        name="lastname" id="lastname" placeholder="Last Name">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group mt-2">
                                            <div class="row g-2">
                                                <div class="col-4">
                                                    <select aria-label="Default select example" class="form-control"
                                                        id="selectedCountryDemo" name="countrycoden" required>
                                                    </select>
                                                </div>
                                                <div class="col-8">
                                                    <label for="tel" class="visually-hidden">Phone Number</label>
                                                    <input type="tel" class="form-control" style="border-color:#ddd;"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                                                        minlength="9" maxlength="10" name="mobileno"
                                                        placeholder="Phone Number*" id="tel" required>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="email" class="visually-hidden">Email</label>
                                            <input type="email" class="form-control inpheftauj" name="email" id="email"
                                                placeholder="Enter Email *" required>
                                        </div>
                                        <div class="form-group mt-2">
                                            <input type="hidden" name="course_intrested" id="course_intrested">

                                            <div class="custom-select-wrapper position-relative">
                                                <!--<input type="hidden" name="course_intrested" id="course_intrested">-->
                                                <div class="custom-select">
                                                    <div class="select-selected">Course Interested*</div>
                                                    <div class="select-items select-hide">

                                                        <div data-value="Data Science, Machine Learning, AI & GenAI">
                                                            Data Science, Machine Learning, AI & GenAI by E&ICT Academy,
                                                            IIT Roorkee</div>
                                                        <div data-value="Data Analytics & GenAI">
                                                            Data Analytics & GenAI by E&ICT Academy, IIT Roorkee</div>
                                                        <div data-value="Embedded Systems & IoT">Embedded Systems
                                                            & IoT</div>
                                                        <div data-value="Data Analytics with Gen AI & Agentic AI">Data
                                                            Analytics with Gen AI & Agentic AI </div>
                                                        <div data-value="Data Analytics, Data Visualization & Business Analytics (4 Months)">
                                                            Data Analytics, Data Visualization & Business Analytics (4
                                                            Months)</div>
                                                        <div data-value="Java Full Stack Development">Java Full Stack
                                                            Development</div>
                                                        <div data-value="Digital Marketing Certification Course">
                                                            Digital Marketing Certification Course</div>
                                                        <div data-value="Python Certification Course">Python
                                                            Certification Course</div>
                                                        <div data-value="Self Paced Course (DA, ML & GenAI)">Self Paced
                                                            Course (DA, ML & GenAI)</div>
                                                        <div data-value="Summer Training / Internship (4-6 weeks)">
                                                            Summer Training / Internship (4-6 weeks)</div>
                                                        <div data-value="Internet Of Things Course">Internet Of
                                                            Things Course</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="mt-3 pt-2 text-center d-flex justify-content-start align-items-center">
                                            <button type="submit"
                                                class="rounded-pill blue-btn d-flex align-items-center px-3 border-0"
                                                style="color: #FFF;
                                                text-align: center;
                                                font-family: Inter;
                                                font-size: 20px;
                                                font-style: normal;
                                                font-weight: 600;
                                                line-height: 23px;
                                                height: 53px;
                                                white-space: nowrap; 
                                                border-radius: 30px;
                                                background: linear-gradient(97deg, #82027E 1.88%, #3A015B 100%);">Submit
                                                Request</button>
                                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                style="height: 53px ;width : 53px; 
                                                background: linear-gradient(97deg, #82027E 1.88%, #3A015B 100%)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="53" height="53"
                                                    viewBox="0 0 53 53" fill="none">
                                                    <circle cx="26.5" cy="26.5" r="26.5"
                                                        fill="url(#paint0_linear_12_197)" />
                                                    <path
                                                        d="M21.5336 32.3332L29.7103 24.1565L29.7103 29.5367C29.7096 29.6894 29.7393 29.8407 29.7974 29.9818C29.8555 30.123 29.941 30.2512 30.049 30.3592C30.1569 30.4671 30.2851 30.5526 30.4263 30.6107C30.5674 30.6689 30.7187 30.6985 30.8714 30.6978C31.1769 30.6969 31.4696 30.5752 31.6856 30.3592C31.9016 30.1431 32.0234 29.8504 32.0243 29.5449L32.0161 22.5212C32.0179 21.9088 31.7768 21.3207 31.3456 20.8858C30.9114 20.4596 30.3268 20.2217 29.7184 20.2235L22.6947 20.2153C22.5427 20.2159 22.3924 20.2463 22.2522 20.305C22.1121 20.3636 21.9848 20.4493 21.8778 20.5571C21.7707 20.6649 21.6859 20.7928 21.6283 20.9334C21.5706 21.0739 21.5412 21.2245 21.5418 21.3764C21.5423 21.5284 21.5728 21.6787 21.6314 21.8189C21.6901 21.9591 21.7757 22.0863 21.8835 22.1934C21.9914 22.3004 22.1192 22.3852 22.2598 22.4428C22.4004 22.5005 22.5509 22.5299 22.7029 22.5293L28.0749 22.5212L19.8983 30.6978C19.6814 30.9147 19.5596 31.2088 19.5596 31.5155C19.5596 31.8222 19.6814 32.1163 19.8983 32.3332C20.1151 32.55 20.4092 32.6719 20.7159 32.6719C21.0226 32.6719 21.3167 32.55 21.5336 32.3332Z"
                                                        fill="white" />
                                                    <defs>
                                                        <linearGradient id="paint0_linear_12_197" x1="0.762091"
                                                            y1="3.03283" x2="58.0287" y2="9.68054"
                                                            gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#82027E" />
                                                            <stop offset="1" stop-color="#3A015B" />
                                                        </linearGradient>
                                                    </defs>
                                                </svg>

                                            </div>

                                        </div>
                                    </form>
                                    <!-- <div class="px-4 mt-3 lh-1"><span class="fw-bold">100% Placement</span><br>
                                        and Job Assistance Programs</div> -->
                                </div>

                                <div class="d-none align-items-center h-100" id="newpopuscssuccesmsg_dv">
                                    <div class="dm-demo_sucessmsg-dvv ">
                                        <div class="text-center d-flex flex-column  gap-lg-5 gap-3">
                                            <h3 class="heading fw-bold m-0">Thank You</h3>
                                            <!-- <p>
                                                <img decoding="async" loading="lazy"
                                                    src="https://s3-tiaimgv1.s3.ap-south-1.amazonaws.com/images/webpageicon/eictlogforpopupdesign.png"
                                                    alt="guwahati-institute">
                                            </p> -->
                                            <img decoding="async" loading="lazy"
                                                class="dm_demo-sucesfd_img img-fluid mx-auto"
                                                src="https://s3-tiaimgv1.s3.ap-south-1.amazonaws.com/images/digital-marketing/enquire-succ-iconm.png"
                                                alt="success_ticket_message">
                                            <p class="text-center dm_we-recieved-fvm m-0">
                                                <span>We have received your request and will connect with you soon
                                                </span>
                                            </p>
                                            <div class="dm-thnk_srtfvdc m-0 d-flex flex-column">
                                                <button data-bs-dismiss="modal" aria-label="close"
                                                    class="choose_of-enqbtnpp-bt  m-0">Ok, Got it</button>
                                                <div class="dm-pop-un_textondition">By submitting the form, you agree to
                                                    our <a href="http://localhost/TIA/term-and-condition"
                                                        class="pop-term-and-condition-btn">Terms and Conditions</a> and
                                                    our <a href="http://localhost/TIA/privacy-policies"
                                                        class="pop-term-and-condition-btn">Privacy Policy</a>.</div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<div class="cmn--headerli-wts-icon" role="presentation">
    <a title="Text Us On WhatsApp"
        href="https://api.whatsapp.com/send?phone=+919354068856&amp;text=Hi,%20I%20contacted%20you%20through%20your%20website."
        aria-label="Text us on WhatsApp">
        <span class="onenumberwtsft">1</span>
        <img decoding="async" loading="lazy"
            src="http://localhost/TIA/assets/images/socialicons/whatsappdownload.webp" alt="whatsapp"
            width="100" height="104" class="img-fluid" /></a>
</div>
<script>
    (window.requestIdleCallback || function (cb) {
        setTimeout(cb, 500);
    })(function () {
        (function (c, l, a, r, i, t, y) {
            c[a] = c[a] || function () {
                (c[a].q = c[a].q || []).push(arguments)
            };
            t = l.createElement(r);
            t.async = 1;
            t.src = "https://www.clarity.ms/tag/" + i;
            y = l.getElementsByTagName(r)[0];
            y.parentNode.insertBefore(t, y);
        })(window, document, "clarity", "script", "a4fi6zdlsd");
    });
</script>
<script defer>
    let lastScrollTop = 0;
    const header = document.querySelector("header");
    window.addEventListener("scroll", function () {
        const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

        if (currentScroll > lastScrollTop) {
            header.style.position = "static";
        } else {
            header.style.position = "sticky";
        }
        lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
    });
</script>

<!-- USe this js  as well as check css changes in custom.css for dynamic changes-->
<!-- <script>
document.addEventListener("DOMContentLoaded", function () {

    // Dynamic configuration for specific pages
    const pageConfig = {
        "advanced-generative-ai-leadership-program-by-eict-academy-iitroorkee": {
            formlogo: "assets/dit/images/apply-now-9m-dsmlai/roorkee.webp",
            formtitle: "E&ICT Academy, IIT Roorkee"
        },
    };
    
    //  Extract URL slug
    const fullURL = window.location.href; 
    const urlParts = fullURL.split("/");
    console.log(urlParts,"urlParts");
    let slug = urlParts[urlParts.length - 1] || urlParts[urlParts.length - 2];
    console.log(slug,"slug comes here");

    // Remove query params or trailing slashes
    slug = slug.replace(/[\?#].*$/, "");
    console.log(slug,"slug after replacing query params or trailing slashes");

    //  Only run update if slug matches our defined list
    if (pageConfig.hasOwnProperty(slug)) {
        const { formlogo, formtitle } = pageConfig[slug];

        // Academy name text
        const academyName = document.querySelector(".newpo-snd_lefp2");
        if (academyName) academyName.textContent = formtitle;

        // Top small logo
        const topLogo = document.querySelector(".global-form-head img");
        if (topLogo) topLogo.src = formlogo;
    }

});
</script>  -->
    


    <!-- Bootstrap Bundle JS (Popper included) -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>



    <!-- The IoT Academy Navigation Script -->

    <script src="http://localhost/TIA/assets/dit/js/custom1.js"></script>



    <!-- Swiper JS & Ticker Marquee Initialization -->

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>

    window.addEventListener("load", function () {

        const swiperContainer = document.querySelector(".scrollHeaderSwiper .swiper-wrapper");

        if (swiperContainer && typeof Swiper !== 'undefined') {

            const slides = swiperContainer.querySelectorAll(".swiper-slide");

            if (slides.length < 4) {

                const needed = 4 - slides.length;

                for (let i = 0; i < needed; i++) {

                    swiperContainer.appendChild(slides[i % slides.length].cloneNode(true));

                }

            }

            const swiperHeader = new Swiper(".scrollHeaderSwiper", {

                slidesPerView: "auto",

                loop: true,

                speed: 6000,

                autoplay: {

                    delay: 0,

                    disableOnInteraction: false,

                },

                allowTouchMove: false,

            });

            const swiperEl = document.querySelector(".scrollHeaderSwiper");

            if (swiperEl && swiperHeader.autoplay) {

                swiperEl.addEventListener("mouseenter", () => swiperHeader.autoplay.stop());

                swiperEl.addEventListener("mouseleave", () => swiperHeader.autoplay.start());

            }

        }

    });

    </script>



    <!-- Clipboard Copy Script -->

    <script>

    function copyRefId() {

        const codeEl = document.getElementById('refCodeText');

        const btnText = document.getElementById('copyBtnText');

        if (!codeEl) return;



        const code = codeEl.textContent.trim();

        if (navigator.clipboard && window.isSecureContext) {

            navigator.clipboard.writeText(code).then(showCopied).catch(fallbackCopy);

        } else {

            fallbackCopy();

        }



        function fallbackCopy() {

            const temp = document.createElement('textarea');

            temp.value = code;

            temp.style.position = 'fixed';

            temp.style.left = '-9999px';

            document.body.appendChild(temp);

            temp.select();

            document.execCommand('copy');

            document.body.removeChild(temp);

            showCopied();

        }



        function showCopied() {

            if (btnText) {

                btnText.textContent = 'Copied!';

                setTimeout(() => {

                    btnText.textContent = 'Copy';

                }, 2000);

            }

        }

    }

    </script>

    <!-- Navigation Back to Origin Script -->
    <script>
    (function() {
        const btn = document.getElementById('btnBackToSource');
        if (!btn) return;

        let originPage = null;
        try {
            originPage = sessionStorage.getItem('enroll_origin_page');
        } catch(e) {}

        const currentHref = btn.getAttribute('href');
        if (originPage && (!currentHref || currentHref === '#' || currentHref.endsWith('all-courses'))) {
            if (originPage !== window.location.href && !originPage.includes('success') && !originPage.includes('enroll-form')) {
                btn.setAttribute('href', originPage);
            }
        }
    })();

    function handleGoBack(event) {
        let originPage = null;
        try {
            originPage = sessionStorage.getItem('enroll_origin_page');
        } catch(e) {}

        if (originPage && originPage !== window.location.href && !originPage.includes('success') && !originPage.includes('enroll-form')) {
            event.preventDefault();
            window.location.href = originPage;
            return;
        }

        if (document.referrer && document.referrer.indexOf(window.location.host) !== -1) {
            if (!document.referrer.includes('success') && !document.referrer.includes('enroll-form')) {
                event.preventDefault();
                window.location.href = document.referrer;
                return;
            }
        }

        const href = event.currentTarget.getAttribute('href');
        if (href && href !== '#' && href !== 'javascript:void(0)') {
            return;
        }

        if (window.history.length > 1) {
            event.preventDefault();
            window.history.back();
            return;
        }
    }
    </script>
</body>

</html>