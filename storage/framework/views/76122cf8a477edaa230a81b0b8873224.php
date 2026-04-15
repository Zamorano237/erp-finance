<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo e($title ?? 'ERP Finance'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root{
            --bg:#f3f7fb;
            --bg-2:#eef4f8;
            --surface:#ffffff;
            --surface-soft:#f9fbfd;
            --surface-alt:#f4f8fb;
            --sidebar:#082b3a;
            --sidebar-2:#0d3b4f;
            --sidebar-hover:#134d64;
            --primary:#11a7a8;
            --primary-2:#1089cf;
            --primary-dark:#0a6e74;
            --primary-soft:#e8fbfb;
            --text:#14344b;
            --text-soft:#647b8f;
            --text-faint:#8ca0b1;
            --border:#d8e4ee;
            --border-2:#c7d6e3;
            --danger:#d9534f;
            --danger-soft:#fff3f2;
            --success:#2f9e44;
            --success-soft:#edf9f0;
            --warning:#f0ad4e;
            --warning-soft:#fff7ea;
            --info:#1683d7;
            --info-soft:#eef7ff;
            --shadow-sm:0 8px 20px rgba(18, 47, 67, .06);
            --shadow-md:0 16px 40px rgba(13, 34, 49, .10);
            --shadow-lg:0 22px 60px rgba(7, 28, 41, .16);
            --radius-xs:10px;
            --radius-sm:14px;
            --radius-md:18px;
            --radius-lg:24px;
            --radius-xl:30px;
            --sidebar-width:280px;
            --sidebar-collapsed:94px;
            --topbar-height:84px;
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(180deg, var(--bg-2) 0%, #f6f9fc 100%);
            color: var(--text);
        }

        body {
            min-height: 100vh;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button, input, select, textarea {
            font: inherit;
        }

        .app-shell {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--sidebar) 0%, #072433 100%);
            color: #fff;
            padding: 20px 18px 18px;
            display: flex;
            flex-direction: column;
            gap: 18px;
            transition: width .25s ease, transform .25s ease;
            position: sticky;
            top: 0;
            height: 100vh;
            z-index: 40;
            box-shadow: 14px 0 40px rgba(5, 22, 31, .18);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar-head {
            display: flex;
            justify-content: flex-end;
        }

        .sidebar-toggle {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,.12);
            background: rgba(255,255,255,.08);
            color: #fff;
            display: grid;
            place-items: center;
            cursor: pointer;
            transition: .2s ease;
        }

        .sidebar-toggle:hover {
            background: rgba(255,255,255,.14);
        }

        .brand-block {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 6px 8px 10px;
        }

        .brand-mark {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.10);
            font-weight: 800;
            font-size: 18px;
            letter-spacing: .4px;
            flex: 0 0 auto;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .brand-title {
            font-size: 19px;
            font-weight: 800;
            line-height: 1;
        }

        .brand-subtitle {
            font-size: 13px;
            line-height: 1.45;
            color: rgba(255,255,255,.72);
        }

        .sidebar.collapsed .brand-text,
        .sidebar.collapsed .nav-text,
        .sidebar.collapsed .sidebar-footer {
            display: none;
        }

        .nav-section {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            min-height: 54px;
            padding: 0 14px;
            border-radius: 18px;
            color: rgba(255,255,255,.82);
            font-weight: 700;
            transition: .2s ease;
        }

        .nav-link:hover {
            background: rgba(255,255,255,.06);
            color: #fff;
        }

        .nav-link.active {
            background: rgba(255,255,255,.10);
            box-shadow: inset 0 0 0 1px rgba(255,255,255,.05);
            color: #fff;
        }

        .nav-icon-wrap {
            width: 24px;
            min-width: 24px;
            display: grid;
            place-items: center;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 18px 16px;
            border-radius: 22px;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.08);
        }

        .sidebar-footer-title {
            font-size: 15px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .sidebar-footer-text {
            font-size: 13px;
            line-height: 1.55;
            color: rgba(255,255,255,.74);
        }

        .main-shell {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 28px;
            background: rgba(255,255,255,.78);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(210,223,233,.85);
            position: sticky;
            top: 0;
            z-index: 20;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
        }

        .topbar-mobile-toggle {
            display: none;
            width: 44px;
            height: 44px;
            border-radius: 14px;
            border: 1px solid var(--border);
            background: #fff;
            cursor: pointer;
            place-items: center;
        }

        .page-meta {
            min-width: 0;
        }

        .page-meta h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .page-meta p {
            margin: 4px 0 0;
            font-size: 13px;
            color: var(--text-soft);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .topbar-chip {
            min-height: 42px;
            padding: 0 14px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: #fff;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--text-soft);
            font-weight: 700;
            box-shadow: var(--shadow-sm);
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 10px 8px 14px;
            border-radius: 999px;
            background: #fff;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }

        .user-chip-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .user-chip-name {
            font-size: 13px;
            font-weight: 800;
            line-height: 1;
        }

        .user-chip-role {
            font-size: 11px;
            color: var(--text-soft);
            line-height: 1;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            color: #fff;
            font-weight: 800;
            font-size: 13px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
        }

        .page-content {
            padding: 26px 28px 30px;
        }

        .page-head {
            margin-bottom: 22px;
        }

        .page-title {
            margin: 0 0 8px;
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -.04em;
            color: #17344b;
        }

        .page-subtitle {
            margin: 0;
            color: var(--text-soft);
            font-size: 15px;
            line-height: 1.6;
        }

        .flash-stack {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 18px;
        }

        .flash {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 58px;
            padding: 0 18px;
            border-radius: 18px;
            font-weight: 700;
            border: 1px solid;
        }

        .flash.success {
            background: var(--success-soft);
            border-color: #c7e8d0;
            color: #2f7f40;
        }

        .flash.error {
            background: var(--danger-soft);
            border-color: #efcdca;
            color: #b84340;
        }

        .panel {
            background: rgba(255,255,255,.94);
            border: 1px solid rgba(211,224,233,.95);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .panel-toolbar {
            padding: 24px 24px 0;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .panel-body {
            padding: 24px;
        }

        .toolbar-grid {
            display: grid;
            grid-template-columns: 1.35fr repeat(4, minmax(160px, 1fr));
            gap: 14px;
            align-items: center;
        }

        .toolbar-grid.two-rows {
            grid-template-columns: 1.25fr repeat(4, minmax(160px, 1fr));
        }

        .toolbar-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            align-items: center;
        }

        .toolbar-left,
        .toolbar-right,
        .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .search-wrap {
            position: relative;
        }

        .search-wrap .field {
            padding-left: 48px;
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #7b92a5;
            pointer-events: none;
        }

        .field,
        .field-select,
        .field-textarea {
            width: 100%;
            min-height: 56px;
            border-radius: 18px;
            border: 1px solid var(--border);
            background: #fbfdff;
            outline: none;
            padding: 0 18px;
            color: var(--text);
            transition: .2s ease;
        }

        .field-textarea {
            min-height: 120px;
            padding: 16px 18px;
            resize: vertical;
        }

        .field:focus,
        .field-select:focus,
        .field-textarea:focus {
            background: #fff;
            border-color: rgba(17,167,168,.55);
            box-shadow: 0 0 0 4px rgba(17,167,168,.08);
        }

        .btn {
            min-height: 54px;
            padding: 0 20px;
            border-radius: 18px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            font-weight: 800;
            white-space: nowrap;
            transition: .2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-sm {
            min-height: 40px;
            padding: 0 14px;
            border-radius: 12px;
            font-size: 13px;
        }

        .btn-primary {
            color: #fff;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
            box-shadow: 0 12px 24px rgba(17,167,168,.18);
        }

        .btn-primary:hover {
            box-shadow: 0 16px 28px rgba(17,167,168,.24);
        }

        .btn-light {
            color: var(--text);
            background: #fff;
            border: 1px solid var(--border);
        }

        .btn-light:hover {
            background: #fbfdff;
            border-color: var(--border-2);
        }

        .btn-danger {
            color: var(--danger);
            background: #fff;
            border: 1px solid #efcfcc;
        }

        .btn-danger:hover {
            background: #fff8f8;
        }

        .btn-icon {
            width: 42px;
            min-width: 42px;
            padding: 0;
            border-radius: 12px;
        }

        .muted {
            color: var(--text-soft);
        }

        .kpi-strip {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .kpi-card,
        .stat-card,
        .mini-panel,
        .form-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 24px;
            box-shadow: var(--shadow-sm);
        }

        .kpi-card,
        .stat-card {
            padding: 20px;
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .stat-label {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-soft);
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: var(--primary-soft);
            color: var(--primary-dark);
        }

        .stat-value,
        .kpi-card .value {
            font-size: 30px;
            line-height: 1;
            font-weight: 800;
            letter-spacing: -.04em;
            color: #15334a;
            margin-bottom: 8px;
        }

        .stat-foot,
        .kpi-card .meta {
            font-size: 12px;
            color: var(--text-soft);
        }

        .kpi-card h4 {
            margin: 0 0 10px;
            font-size: 13px;
            font-weight: 800;
            color: var(--text-soft);
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .grid-4 {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        .mini-panel {
            padding: 20px;
        }

        .mini-panel-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .mini-panel-title {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
            color: #17344b;
        }

        .section-title {
            margin: 0 0 18px;
            font-size: 18px;
            font-weight: 800;
            color: #17344b;
        }

        .badge {
            min-height: 34px;
            padding: 0 12px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 800;
            background: #edf7fa;
            color: #0d6d77;
        }

        .badge.success {
            background: var(--success-soft);
            color: #297a39;
        }

        .badge.warning {
            background: var(--warning-soft);
            color: #a56d19;
        }

        .badge.danger {
            background: var(--danger-soft);
            color: #b9423f;
        }

        .badge.info {
            background: var(--info-soft);
            color: #1c73c4;
        }

        .chip-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .chip {
            min-height: 34px;
            padding: 0 12px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--text-soft);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 700;
        }

        .table-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 0 24px 18px;
            color: var(--text-soft);
            font-size: 13px;
            flex-wrap: wrap;
        }

        .table-shell {
            border-top: 1px solid var(--border);
            overflow-x: auto;
        }

        .premium-table {
            width: 100%;
            min-width: 1220px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .premium-table thead th {
            position: sticky;
            top: 0;
            z-index: 1;
            background: linear-gradient(180deg, #0b4d62 0%, #083f51 100%);
            color: #fff;
            text-align: left;
            font-size: 14px;
            font-weight: 800;
            padding: 16px 18px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .premium-table thead th:first-child {
            border-top-left-radius: 16px;
        }

        .premium-table thead th:last-child {
            border-top-right-radius: 16px;
        }

        .premium-table tbody td {
            padding: 16px 18px;
            border-bottom: 1px solid #e6eef5;
            vertical-align: middle;
            background: #fff;
            color: #17344b;
        }

        .premium-table tbody tr:nth-child(even) td {
            background: #fbfdff;
        }

        .premium-table tbody tr:hover td {
            background: #f4fbff;
        }

        .actions-col {
            width: 158px;
            min-width: 158px;
        }

        .table-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .inline-input {
            width: 100%;
            min-height: 42px;
            padding: 8px 12px;
            border-radius: 12px;
            border: 1px solid transparent;
            background: transparent;
            color: var(--text);
            outline: none;
            transition: .2s ease;
        }

        .inline-input:hover {
            background: #f7fbfe;
            border-color: #e2edf5;
        }

        .inline-input:focus {
            background: #fff;
            border-color: rgba(17,167,168,.55);
            box-shadow: 0 0 0 4px rgba(17,167,168,.08);
        }

        .inline-input.saving {
            background: #fffbe7;
        }

        .inline-input.saved {
            background: #ebfbef;
            border-color: #bfe3c6;
        }

        .inline-input.error {
            background: #fff3f2;
            border-color: #efc7c4;
        }

        .empty-state {
            text-align: center;
            padding: 38px !important;
            color: var(--text-soft);
            font-weight: 700;
        }

        .table-footer {
            padding: 18px 24px 24px;
            display: flex;
            justify-content: flex-end;
        }

        .table-footer nav {
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }

        .form-card {
            padding: 24px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .form-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .form-field {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .form-field label {
            font-size: 13px;
            font-weight: 800;
            color: #4f667a;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
            flex-wrap: wrap;
        }

        .drawer,
        .modal {
            position: fixed;
            inset: 0;
            display: none;
            z-index: 120;
        }

        .drawer.open,
        .modal.open {
            display: block;
        }

        .drawer-backdrop,
        .modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(8, 24, 33, .48);
            backdrop-filter: blur(4px);
        }

        .drawer-panel {
            position: absolute;
            top: 16px;
            right: 16px;
            width: min(430px, calc(100vw - 32px));
            height: calc(100vh - 32px);
            background: #fff;
            border-radius: 28px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
            padding: 22px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .modal-panel {
            position: relative;
            width: min(760px, calc(100vw - 32px));
            margin: 40px auto;
            background: #fff;
            border-radius: 30px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
            padding: 24px;
            z-index: 1;
        }

        .modal-panel.sm {
            width: min(520px, calc(100vw - 32px));
        }

        .drawer-head,
        .modal-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .drawer-head h3,
        .modal-head h3 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            color: #183650;
        }

        .drawer-close,
        .modal-close {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            border: 1px solid var(--border);
            background: #fff;
            cursor: pointer;
            font-size: 18px;
        }

        .check-grid {
            display: flex;
            flex-direction: column;
            gap: 10px;
            overflow: auto;
            padding-right: 4px;
        }

        .check-tile {
            min-height: 56px;
            padding: 0 16px;
            border-radius: 18px;
            border: 1px solid var(--border);
            background: #fbfdff;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            color: #23435b;
        }

        .check-tile input {
            width: 18px;
            height: 18px;
        }

        .drawer-actions,
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        .list-card {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .list-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            min-height: 56px;
            padding: 0 16px;
            border-radius: 18px;
            border: 1px solid var(--border);
            background: #fbfdff;
        }

        .list-row-main {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .list-row-title {
            font-size: 14px;
            font-weight: 800;
            color: #183650;
        }

        .list-row-subtitle {
            font-size: 12px;
            color: var(--text-soft);
        }

        .legend-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        @media (max-width: 1280px) {
            .toolbar-grid,
            .toolbar-grid.two-rows {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .kpi-strip,
            .grid-4 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 980px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                width: var(--sidebar-width) !important;
                transform: translateX(-100%);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .topbar-mobile-toggle {
                display: grid;
            }

            .page-content {
                padding: 20px;
            }

            .toolbar-grid,
            .toolbar-grid.two-rows,
            .kpi-strip,
            .grid-2,
            .grid-3,
            .grid-4,
            .form-grid,
            .form-grid-3,
            .legend-grid {
                grid-template-columns: 1fr;
            }

            .toolbar-row,
            .table-meta {
                flex-direction: column;
                align-items: stretch;
            }
        }

        @media (max-width: 640px) {
            .topbar {
                padding: 16px 18px;
            }

            .page-content {
                padding: 16px;
            }

            .panel-toolbar,
            .panel-body,
            .table-footer {
                padding-left: 16px;
                padding-right: 16px;
            }

            .page-title {
                font-size: 24px;
            }

            .user-chip-text,
            .topbar-chip {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" id="appSidebar">
        <div class="sidebar-head">
            <button class="sidebar-toggle" type="button" id="sidebarCollapseBtn" title="Replier / déplier">
                <i data-lucide="panel-left-close"></i>
            </button>
        </div>

        <div class="brand-block">
            <div class="brand-mark">ERP</div>
            <div class="brand-text">
                <div class="brand-title">ERP Finance</div>
                <div class="brand-subtitle">Socle premium Laravel<br>Gestion modulaire</div>
            </div>
        </div>

        <nav class="nav-section">
            <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">
                <span class="nav-icon-wrap"><i data-lucide="layout-dashboard"></i></span>
                <span class="nav-text">Dashboard</span>
            </a>

            <a class="nav-link <?php echo e(request()->routeIs('suppliers.*') ? 'active' : ''); ?>" href="<?php echo e(route('suppliers.index')); ?>">
                <span class="nav-icon-wrap"><i data-lucide="building-2"></i></span>
                <span class="nav-text">Fournisseurs</span>
            </a>

            <a class="nav-link <?php echo e(request()->routeIs('expenses.*') ? 'active' : ''); ?>" href="<?php echo e(route('expenses.index')); ?>">
                <span class="nav-icon-wrap"><i data-lucide="receipt"></i></span>
                <span class="nav-text">Dépenses</span>
            </a>

            <a class="nav-link <?php echo e(request()->routeIs('budgets.*') ? 'active' : ''); ?>" href="<?php echo e(route('budgets.index')); ?>">
                <span class="nav-icon-wrap"><i data-lucide="wallet-cards"></i></span>
                <span class="nav-text">Budgets</span>
            </a>

            <a class="nav-link <?php echo e(request()->routeIs('treasury.*') ? 'active' : ''); ?>" href="<?php echo e(route('treasury.index')); ?>">
                <span class="nav-icon-wrap"><i data-lucide="line-chart"></i></span>
                <span class="nav-text">Trésorerie</span>
            </a>

            <a class="nav-link <?php echo e(request()->routeIs('options.*') ? 'active' : ''); ?>" href="<?php echo e(route('options.index')); ?>">
                <span class="nav-icon-wrap"><i data-lucide="settings-2"></i></span>
                <span class="nav-text">Options</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-footer-title">Socle V2 premium</div>
            <div class="sidebar-footer-text">
                Référentiels, édition inline, préférences colonnes, listes paramétrables, base ERP modulaire.
            </div>
        </div>
    </aside>

    <div class="main-shell">
        <header class="topbar">
            <div class="topbar-left">
                <button type="button" class="topbar-mobile-toggle" id="mobileSidebarBtn">
                    <i data-lucide="menu"></i>
                </button>

                <div class="page-meta">
                    <h2><?php echo e($title ?? 'ERP Finance'); ?></h2>
                    <p><?php echo e($subtitle ?? 'Pilotage ERP modulaire premium'); ?></p>
                </div>
            </div>

            <div class="topbar-right">
                <div class="topbar-chip">
                    <i data-lucide="sparkles"></i>
                    Interface premium
                </div>

                <div class="user-chip">
                    <div class="user-chip-text">
                        <div class="user-chip-name"><?php echo e(auth()->user()->name ?? 'Admin'); ?></div>
                        <div class="user-chip-role"><?php echo e(auth()->user()->role ?? 'ERP Manager'); ?></div>
                    </div>
                    <div class="user-avatar">
                        <?php echo e(strtoupper(substr(auth()->user()->name ?? 'A', 0, 1))); ?>

                    </div>
                </div>
            </div>
        </header>

        <main class="page-content">
            <?php if(session('success') || session('error')): ?>
                <div class="flash-stack">
                    <?php if(session('success')): ?>
                        <div class="flash success">
                            <i data-lucide="badge-check"></i>
                            <span><?php echo e(session('success')); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if(session('error')): ?>
                        <div class="flash error">
                            <i data-lucide="triangle-alert"></i>
                            <span><?php echo e(session('error')); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</div>

<script>
    lucide.createIcons();

    const sidebar = document.getElementById('appSidebar');
    const sidebarCollapseBtn = document.getElementById('sidebarCollapseBtn');
    const mobileSidebarBtn = document.getElementById('mobileSidebarBtn');

    if (sidebarCollapseBtn) {
        sidebarCollapseBtn.addEventListener('click', function () {
            if (window.innerWidth <= 980) {
                sidebar.classList.toggle('mobile-open');
            } else {
                sidebar.classList.toggle('collapsed');
                localStorage.setItem('erp.sidebar.collapsed', sidebar.classList.contains('collapsed') ? '1' : '0');
            }
        });
    }

    if (mobileSidebarBtn) {
        mobileSidebarBtn.addEventListener('click', function () {
            sidebar.classList.toggle('mobile-open');
        });
    }

    if (window.innerWidth > 980 && localStorage.getItem('erp.sidebar.collapsed') === '1') {
        sidebar.classList.add('collapsed');
    }

    document.addEventListener('click', function (e) {
        if (window.innerWidth > 980) return;
        if (!sidebar.contains(e.target) && mobileSidebarBtn && !mobileSidebarBtn.contains(e.target)) {
            sidebar.classList.remove('mobile-open');
        }
    });

    document.querySelectorAll('[data-open-modal]').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-open-modal');
            const modal = document.getElementById(id);
            if (modal) modal.classList.add('open');
        });
    });

    document.querySelectorAll('[data-close-modal]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = btn.closest('.modal');
            if (modal) modal.classList.remove('open');
        });
    });

    document.querySelectorAll('[data-open-drawer]').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-open-drawer');
            const drawer = document.getElementById(id);
            if (drawer) drawer.classList.add('open');
        });
    });

    document.querySelectorAll('[data-close-drawer]').forEach(btn => {
        btn.addEventListener('click', () => {
            const drawer = btn.closest('.drawer');
            if (drawer) drawer.classList.remove('open');
        });
    });
</script>
</body>
</html><?php /**PATH C:\dev\erp-finance\resources\views/layouts/app.blade.php ENDPATH**/ ?>