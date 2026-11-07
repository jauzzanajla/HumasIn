<style>
    .mac-wrap {
        --accent: #4E8BBC;
        --accent-soft: rgba(78, 139, 188, .12);
        --text: #1d1d1f;
        --muted: #6e6e73;
        --line: rgba(0, 0, 0, .07);
        --surface: #ffffff;
        --shadow: 0 1px 2px rgba(0, 0, 0, .04), 0 8px 24px rgba(0, 0, 0, .04);

        max-width: 1280px;
        margin: 0 auto;
        color: var(--text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", Roboto, sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .mac-wrap * { box-sizing: border-box; }
    .mac-narrow { max-width: 960px; }

    /* ---------- Header ---------- */
    .mac-head { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 28px; gap: 16px; }
    .mac-title { font-size: 24px; font-weight: 700; letter-spacing: -.02em; margin: 0 0 4px; }
    .mac-sub { font-size: 13px; color: var(--muted); margin: 0; }

    .mac-btn-primary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 18px; border-radius: 999px; border: 0;
        background: linear-gradient(180deg, #5b98c8, #4E8BBC);
        color: #fff; font: inherit; font-size: 13px; font-weight: 600; text-decoration: none;
        box-shadow: 0 1px 2px rgba(0,0,0,.12), 0 6px 16px rgba(78,139,188,.28), inset 0 1px 0 rgba(255,255,255,.25);
        transition: transform .15s ease, filter .15s ease; cursor: pointer;
    }
    .mac-btn-primary:hover { filter: brightness(1.05); transform: translateY(-1px); }
    .mac-btn-primary:active { transform: translateY(0); }

    /* ---------- Alert ---------- */
    .mac-alert {
        display: flex; gap: 10px; align-items: flex-start;
        margin-bottom: 20px; padding: 12px 16px; border-radius: 12px;
        background: rgba(255, 59, 48, .08); border: 1px solid rgba(255, 59, 48, .18);
        color: #b42318; font-size: 13px; line-height: 1.5;
    }
    .mac-alert svg { flex-shrink: 0; margin-top: 2px; }
    .mac-alert strong { display: block; margin-bottom: 2px; }

    /* ---------- Stat cards ---------- */
    .mac-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
    .mac-stat {
        display: flex; align-items: center; gap: 16px; padding: 20px; background: var(--surface);
        border: 1px solid var(--line); border-radius: 16px; box-shadow: var(--shadow);
    }
    .mac-stat-icon { width: 44px; height: 44px; flex-shrink: 0; display: grid; place-items: center; border-radius: 12px; }
    .mac-stat-icon svg { width: 22px; height: 22px; }
    .mac-stat-label { font-size: 12px; font-weight: 500; color: var(--muted); margin-bottom: 2px; }
    .mac-stat-value { font-size: 28px; font-weight: 700; letter-spacing: -.03em; line-height: 1.1; font-variant-numeric: tabular-nums; }
    .tone-blue   { background: rgba(78,139,188,.14); color: #3b7aab; }
    .tone-indigo { background: rgba(88,86,214,.12);  color: #5856d6; }
    .tone-green  { background: rgba(52,199,89,.14);  color: #248a3d; }
    .tone-amber  { background: rgba(255,159,10,.15); color: #c77700; }

    /* ---------- Panel / Card ---------- */
    .mac-panel, .mac-card {
        background: var(--surface); border: 1px solid var(--line);
        border-radius: 18px; box-shadow: var(--shadow); overflow: hidden;
    }
    .mac-panel { margin-bottom: 32px; }

    .mac-toolbar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; padding: 16px 20px; border-bottom: 1px solid var(--line); }
    .mac-search { position: relative; flex: 1; min-width: 260px; }
    .mac-search svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #8e8e93; pointer-events: none; }
    .mac-input, .mac-select {
        height: 38px; border-radius: 10px; border: 1px solid transparent;
        background: rgba(120,120,128,.1); color: var(--text);
        font: inherit; font-size: 13px; outline: none;
        transition: background .15s, box-shadow .15s, border-color .15s;
    }
    .mac-input { width: 100%; padding: 0 14px 0 36px; }
    .mac-input::placeholder { color: #8e8e93; }
    .mac-select {
        width: 180px; padding: 0 32px 0 12px; appearance: none; cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238e8e93' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 12px center;
    }
    .mac-input:focus, .mac-select:focus { background-color: #fff; border-color: var(--accent); box-shadow: 0 0 0 4px var(--accent-soft); }

    .mac-btn {
        height: 38px; padding: 0 18px; border-radius: 10px; border: 0;
        background: var(--accent); color: #fff; font: inherit; font-size: 13px; font-weight: 600;
        cursor: pointer; transition: filter .15s;
        display: inline-flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none;
    }
    .mac-btn:hover { filter: brightness(1.06); }
    .mac-btn-ghost {
        display: inline-flex; align-items: center; justify-content: center; height: 38px; padding: 0 16px; border-radius: 10px;
        background: rgba(120,120,128,.1); color: #3a3a3c; font: inherit; font-size: 13px; font-weight: 600; text-decoration: none;
        border: 0; cursor: pointer; transition: background .15s;
    }
    .mac-btn-ghost:hover { background: rgba(120,120,128,.18); }
    .mac-btn-tint {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 38px; padding: 0 18px; border-radius: 10px;
        background: var(--accent-soft); color: #3b7aab; font: inherit; font-size: 13px; font-weight: 600; text-decoration: none;
        border: 0; cursor: pointer; transition: background .15s;
    }
    .mac-btn-tint:hover { background: rgba(78,139,188,.2); }

    /* ---------- Table ---------- */
    .mac-table-wrap { overflow-x: auto; }
    .mac-table { width: 100%; border-collapse: collapse; min-width: 960px; }
    .mac-table thead th {
        padding: 12px 20px; text-align: left; background: #fafafc;
        font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase;
        color: var(--muted); border-bottom: 1px solid var(--line); white-space: nowrap;
    }
    .mac-table tbody td { padding: 14px 20px; vertical-align: middle; border-bottom: 1px solid var(--line); font-size: 13px; }
    .mac-table tbody tr:last-child td { border-bottom: 0; }
    .mac-table tbody tr { transition: background .15s; }
    .mac-table tbody tr:hover { background: #f7f9fc; }
    .t-center { text-align: center !important; }

    .mac-thumb { width: 46px; height: 46px; border-radius: 12px; object-fit: cover; display: block; border: 1px solid var(--line); box-shadow: 0 1px 3px rgba(0,0,0,.08); }
    .mac-thumb-empty { width: 46px; height: 46px; border-radius: 12px; display: grid; place-items: center; background: rgba(120,120,128,.1); color: #8e8e93; }
    .mac-thumb-empty svg { width: 20px; height: 20px; }
    .mac-name { font-weight: 600; letter-spacing: -.005em; }
    .mac-desc { color: var(--muted); max-width: 300px; line-height: 1.45; }
    .mac-qty { font-weight: 600; font-variant-numeric: tabular-nums; }

    .mac-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .mac-badge::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .badge-green { background: rgba(52,199,89,.14);  color: #248a3d; }
    .badge-amber { background: rgba(255,159,10,.16); color: #b26a00; }
    .badge-red   { background: rgba(255,59,48,.12);  color: #c9342a; }

    .mac-cond { display: inline-flex; align-items: center; gap: 8px; color: #3a3a3c; font-weight: 500; }
    .mac-cond::before { content: ""; width: 8px; height: 8px; border-radius: 50%; background: var(--dot); }
    .cond-baik   { --dot: #34c759; }
    .cond-ringan { --dot: #ff9f0a; }
    .cond-berat  { --dot: #ff3b30; }

    .mac-actions { display: inline-flex; align-items: center; gap: 4px; }
    .mac-act { padding: 6px 12px; border-radius: 8px; border: 0; background: transparent; font: inherit; font-size: 12px; font-weight: 600; text-decoration: none; cursor: pointer; transition: background .15s, color .15s; }
    .act-detail { color: #3a3a3c; } .act-detail:hover { background: rgba(120,120,128,.14); }
    .act-edit { color: #3b7aab; }   .act-edit:hover { background: var(--accent-soft); }
    .act-del { color: #c9342a; }    .act-del:hover { background: rgba(255,59,48,.12); }

    .mac-empty { padding: 64px 20px; text-align: center; color: var(--muted); font-size: 13px; }
    .mac-empty svg { width: 36px; height: 36px; margin-bottom: 10px; color: #c7c7cc; }
    .mac-foot { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 14px 20px; border-top: 1px solid var(--line); background: #fafafc; font-size: 12px; color: var(--muted); }

    /* ---------- Back link ---------- */
    .mac-back {
        display: inline-flex; align-items: center; gap: 6px; margin-bottom: 20px;
        font-size: 13px; font-weight: 500; color: #3b7aab; text-decoration: none; transition: opacity .15s;
    }
    .mac-back:hover { opacity: .7; }
    .mac-back svg { width: 16px; height: 16px; }

    /* ---------- Card header / body / footer ---------- */
    .mac-card-head { display: flex; justify-content: space-between; align-items: center; gap: 16px; padding: 22px 32px; border-bottom: 1px solid var(--line); }
    .mac-card-title { font-size: 17px; font-weight: 700; letter-spacing: -.015em; margin: 0 0 3px; }
    .mac-card-sub { font-size: 13px; color: var(--muted); margin: 0; }
    .mac-card-body { padding: 32px; }
    .mac-card-foot { display: flex; justify-content: flex-end; gap: 10px; padding: 18px 32px; border-top: 1px solid var(--line); background: #fafafc; }

    /* ---------- Form ---------- */
    .mac-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 22px 24px; }
    .mac-span-2 { grid-column: 1 / -1; }
    .mac-label { display: block; font-size: 12px; font-weight: 600; color: #3a3a3c; margin-bottom: 7px; }
    .mac-label .req { color: #ff3b30; margin-left: 2px; }
    .mac-hint { font-size: 11px; color: var(--muted); margin-top: 6px; }

    .mac-control {
        width: 100%; height: 40px; padding: 0 14px; border-radius: 10px;
        border: 1px solid var(--line); background: rgba(120,120,128,.08); color: var(--text);
        font: inherit; font-size: 13px; outline: none;
        transition: background .15s, box-shadow .15s, border-color .15s;
    }
    .mac-control::placeholder { color: #8e8e93; }
    .mac-control:focus { background: #fff; border-color: var(--accent); box-shadow: 0 0 0 4px var(--accent-soft); }
    select.mac-control {
        appearance: none; cursor: pointer; padding-right: 36px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238e8e93' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 14px center;
    }
    textarea.mac-control { height: auto; min-height: 110px; padding: 12px 14px; line-height: 1.5; resize: vertical; }

    .mac-drop {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
        padding: 18px 20px; border-radius: 12px; border: 1.5px dashed rgba(78,139,188,.4); background: rgba(78,139,188,.05);
        transition: background .15s, border-color .15s;
    }
    .mac-drop:hover { background: rgba(78,139,188,.09); border-color: var(--accent); }
    .mac-drop input[type=file] { font-size: 12px; color: var(--muted); cursor: pointer; }
    .mac-drop input[type=file]::file-selector-button {
        margin-right: 12px; padding: 7px 14px; border: 0; border-radius: 8px;
        background: #fff; color: #3a3a3c; font: inherit; font-size: 12px; font-weight: 600; cursor: pointer;
        box-shadow: 0 1px 2px rgba(0,0,0,.1), 0 0 0 1px var(--line); transition: background .15s;
    }
    .mac-drop input[type=file]::file-selector-button:hover { background: #f5f5f7; }
    .mac-drop-note { font-size: 11px; color: var(--muted); }

    .mac-current { display: flex; align-items: center; gap: 14px; margin-bottom: 12px; padding: 12px; border-radius: 12px; background: #fafafc; border: 1px solid var(--line); }
    .mac-current img { width: 56px; height: 56px; object-fit: cover; border-radius: 10px; border: 1px solid var(--line); }
    .mac-current span { font-size: 12px; color: var(--muted); line-height: 1.45; }

    /* ---------- Detail ---------- */
    .mac-detail { display: grid; grid-template-columns: 280px 1fr; gap: 36px; align-items: start; }
    .mac-photo, .mac-photo-empty { width: 100%; aspect-ratio: 1 / 1; border-radius: 16px; }
    .mac-photo { object-fit: cover; display: block; border: 1px solid var(--line); box-shadow: 0 4px 16px rgba(0,0,0,.08); }
    .mac-photo-empty { display: grid; place-items: center; align-content: center; gap: 8px; background: rgba(120,120,128,.08); border: 1.5px dashed rgba(120,120,128,.3); color: #8e8e93; font-size: 12px; }
    .mac-photo-empty svg { width: 32px; height: 32px; }

    .mac-detail-name { font-size: 26px; font-weight: 700; letter-spacing: -.025em; line-height: 1.2; margin: 0 0 22px; }
    .mac-kv-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0; border: 1px solid var(--line); border-radius: 14px; overflow: hidden; margin-bottom: 22px; }
    .mac-kv { padding: 16px 18px; }
    .mac-kv + .mac-kv { border-left: 1px solid var(--line); }
    .mac-kv-label { font-size: 11px; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; color: var(--muted); margin-bottom: 8px; }
    .mac-kv-value { font-size: 15px; font-weight: 600; min-height: 24px; display: flex; align-items: center; }
    .mac-note { padding: 16px 18px; border-radius: 14px; background: #fafafc; border: 1px solid var(--line); font-size: 13px; line-height: 1.65; color: #3a3a3c; white-space: pre-line; }

    @media (max-width: 1100px) { .mac-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 800px) {
        .mac-detail { grid-template-columns: 1fr; }
        .mac-form-grid { grid-template-columns: 1fr; }
        .mac-kv-grid { grid-template-columns: 1fr; }
        .mac-kv + .mac-kv { border-left: 0; border-top: 1px solid var(--line); }
    }
</style>