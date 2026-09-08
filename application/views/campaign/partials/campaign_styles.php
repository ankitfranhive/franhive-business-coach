<style>
.cm-status-card { border: 0; border-radius: 14px; color: #fff; min-height: 120px; }
.cm-status-card h3 { font-size: 28px; font-weight: 700; margin: 0; }
.cm-status-card p { margin: 0; opacity: .9; font-size: 13px; }
.cm-chip { display:inline-block; margin: 0 6px 6px 0; padding: 4px 10px; border-radius: 16px; background:#eef4ff; color:#265ed7; font-size:12px; cursor:pointer; border:1px solid #d6e4ff; }
.cm-chip:hover { background:#265ed7; color:#fff; }
.cm-step { display:flex; gap:8px; margin-bottom: 18px; flex-wrap: wrap; }
.cm-step span { padding: 6px 12px; border-radius: 20px; background:#f0f0f0; font-size:12px; }
.cm-step span.active { background:#265ed7; color:#fff; }
.cm-tz-bar { background:#f6f8fb; border:1px solid #e8eef8; border-radius:12px; padding:14px 16px; margin-bottom:16px; }
.cm-tz-clocks { display:flex; flex-wrap:wrap; gap:8px; margin-bottom:12px; }
.cm-tz-chip { display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; background:#fff; color:#265ed7; border:1px solid #d6e4ff; font-weight:600; font-size:13px; }
.cm-tz-chip .cm-tz-name { color:#1f2937; }
.cm-tz-chip .cm-tz-live { font-variant-numeric: tabular-nums; }
.cm-tz-help { color:#6b7280; font-size:12px; margin:6px 0 0; }
.cm-contact-toolbar { display:flex; flex-wrap:wrap; align-items:center; gap:10px; margin:0 0 12px; }
.cm-contact-toolbar input[type="search"] { max-width:320px; }
.cm-contact-toolbar select { max-width:140px; }
.cm-contact-meta { color:#6b7280; font-size:13px; }
.cm-contact-pager { display:flex; flex-wrap:wrap; align-items:center; gap:6px; margin:12px 0 16px; }
.cm-contact-pager button { min-width:36px; }
.cm-contact-pager .cm-page-btn.active { background:#265ed7; color:#fff; border-color:#265ed7; }
.cm-contact-empty { display:none; padding:18px; text-align:center; color:#6b7280; background:#f8fafc; border-radius:8px; margin-bottom:12px; }
.cm-sent-table td,
.cm-sent-table th {
    white-space: normal !important;
    overflow: visible !important;
    text-overflow: unset !important;
    max-width: none !important;
    word-break: break-word;
}
.cm-sent-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #e8f5e9;
    color: #1b5e20;
    border: 1px solid #c8e6c9;
    border-radius: 999px;
    padding: 3px 10px;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}
.gmail-mail {
    max-width: 860px;
    margin: 0 auto;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
    overflow: hidden;
}
.gmail-mail-top {
    padding: 18px 22px 14px;
    border-bottom: 1px solid #eee;
}
.gmail-subject {
    font-size: 22px;
    font-weight: 600;
    color: #202124;
    margin: 8px 0 14px;
    line-height: 1.35;
}
.gmail-meta {
    display: flex;
    gap: 12px;
    align-items: flex-start;
}
.gmail-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #1a73e8;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    flex: 0 0 40px;
}
.gmail-meta-text { min-width: 0; flex: 1; }
.gmail-from {
    font-weight: 700;
    color: #202124;
    font-size: 14px;
}
.gmail-from span { font-weight: 400; color: #5f6368; }
.gmail-to, .gmail-date {
    color: #5f6368;
    font-size: 13px;
    margin-top: 2px;
}
.gmail-body-frame {
    width: 100%;
    min-height: 280px;
    border: 0;
    background: #fff;
}
.gmail-body-print { display: none; }
.gmail-actions { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 14px; }
@media print {
    @page { size: A4; margin: 10mm; }
    html, body {
        height: auto !important;
        min-height: 0 !important;
        background: #fff !important;
        overflow: visible !important;
    }
    .header, .left-side-bar, .right-sidebar, .footer-wrap,
    .mobile-menu-overlay, .page-header, .gmail-actions, .gmail-body-frame {
        display: none !important;
    }
    .main-container,
    .sent-email-print-page,
    .pd-ltr-20,
    .pd-ltr-20.xs-pd-20-10 {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: none !important;
        background: #fff !important;
        min-height: 0 !important;
    }
    .gmail-mail {
        max-width: none !important;
        margin: 0 !important;
        box-shadow: none !important;
        border: 1px solid #ddd !important;
        border-radius: 0 !important;
        overflow: visible !important;
        page-break-inside: auto;
        break-inside: auto;
    }
    .gmail-mail-top { padding: 8px 12px 10px !important; }
    .gmail-subject { font-size: 16px !important; margin: 6px 0 8px !important; }
    .gmail-body-print {
        display: block !important;
        padding: 8px 12px 12px;
    }
    .gmail-body-print * {
        page-break-inside: auto !important;
        break-inside: auto !important;
    }
    .gmail-body-print p { margin: 0 0 8px !important; }
    .gmail-body-print img { max-width: 100% !important; height: auto !important; }
    .gmail-avatar { width: 28px; height: 28px; flex-basis: 28px; font-size: 12px; }
}
</style>
