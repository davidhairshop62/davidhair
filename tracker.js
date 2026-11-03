/**
 * David Hair Website Visitor Analytics Tracker
 * Lightweight (~2KB), non-blocking, privacy-friendly visitor analytics & conversion tracking.
 */
(function () {
    'use strict';

    // Do not run in iframe or if already loaded
    if (window._dh_tracker_loaded) return;
    window._dh_tracker_loaded = true;

    // Helper: UUID generator
    function generateUid(prefix) {
        return (prefix || 'dh_') + Math.random().toString(36).substring(2, 10) + Date.now().toString(36);
    }

    // 1. Get or Create Persistent Visitor ID (stored across visits in localStorage)
    var VISITOR_KEY = '_dh_vid';
    var visitorId = '';
    try {
        visitorId = localStorage.getItem(VISITOR_KEY);
        if (!visitorId) {
            visitorId = generateUid('dh_v_');
            localStorage.setItem(VISITOR_KEY, visitorId);
        }
    } catch (e) {
        visitorId = generateUid('dh_v_');
    }

    // 2. Get or Create Session ID (stored in sessionStorage)
    var SESSION_KEY = '_dh_sid';
    var sessionId = '';
    try {
        sessionId = sessionStorage.getItem(SESSION_KEY);
        if (!sessionId) {
            sessionId = generateUid('dh_s_');
            sessionStorage.setItem(SESSION_KEY, sessionId);
        }
    } catch (e) {
        sessionId = generateUid('dh_s_');
    }

    // Admin detection (prevent inflating stats while managing site)
    var isAdmin = 0;
    try {
        if (
            localStorage.getItem('dh_admin_logged') === '1' ||
            sessionStorage.getItem('dh_admin_logged') === '1' ||
            document.cookie.indexOf('admin_user=') !== -1 ||
            window.location.pathname.indexOf('stock_manager') !== -1 ||
            window.location.pathname.indexOf('section_manager') !== -1
        ) {
            isAdmin = 1;
        }
    } catch (e) {}

    var currentLogId = null;
    var startTime = Date.now();

    // Send payload to backend API
    function sendPayload(endpoint, data) {
        var url = '/api_analytics.php?action=' + endpoint;
        var jsonStr = JSON.stringify(data);

        if (navigator.sendBeacon && typeof navigator.sendBeacon === 'function' && jsonStr.length < 65000) {
            try {
                var blob = new Blob([jsonStr], { type: 'application/json' });
                var sent = navigator.sendBeacon(url, blob);
                if (sent) return;
            } catch (err) {}
        }

        try {
            fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: jsonStr,
                keepalive: true
            }).then(function (res) {
                return res.json();
            }).then(function (json) {
                if (json && json.log_id) {
                    currentLogId = json.log_id;
                }
            }).catch(function () {});
        } catch (e) {}
    }

    // 3. Track Initial Pageview
    var pagePayload = {
        visitor_id: visitorId,
        session_id: sessionId,
        page_url: window.location.pathname + window.location.search,
        page_title: document.title || 'David Hair Systems',
        referrer: document.referrer || '',
        screen_res: (window.screen ? window.screen.width + 'x' + window.screen.height : ''),
        language: navigator.language || navigator.userLanguage || '',
        event_type: 'pageview',
        duration_sec: 0,
        is_admin: isAdmin
    };

    sendPayload('track', pagePayload);

    // 4. Update Time on Page (Engagement Duration)
    function updateDuration() {
        var elapsedSec = Math.round((Date.now() - startTime) / 1000);
        if (elapsedSec > 0) {
            sendPayload('update_duration', {
                log_id: currentLogId,
                visitor_id: visitorId,
                duration_sec: elapsedSec
            });
        }
    }

    // Send duration ping on visibility change or page unload
    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'hidden') {
            updateDuration();
        }
    });
    window.addEventListener('beforeunload', updateDuration);

    // Periodic heartbeat duration update at 15s, 45s, 120s
    setTimeout(updateDuration, 15000);
    setTimeout(updateDuration, 45000);
    setTimeout(updateDuration, 120000);

    // 5. Global Helper: Custom Event Tracking
    window.dhTrack = function (eventType, eventData) {
        var eventPayload = {
            visitor_id: visitorId,
            session_id: sessionId,
            page_url: window.location.pathname + window.location.search,
            page_title: document.title || 'David Hair Systems',
            referrer: document.referrer || '',
            screen_res: (window.screen ? window.screen.width + 'x' + window.screen.height : ''),
            language: navigator.language || '',
            event_type: eventType || 'custom_event',
            event_data: eventData || null,
            duration_sec: Math.round((Date.now() - startTime) / 1000),
            is_admin: isAdmin
        };
        sendPayload('track', eventPayload);
    };

    // 6. Automatic Clicks & Conversion Event Listeners
    document.addEventListener('click', function (e) {
        var target = e.target.closest('a, button');
        if (!target) return;

        var href = target.getAttribute('href') || '';
        var text = (target.innerText || target.getAttribute('title') || '').trim().substring(0, 50);

        // WhatsApp Click
        if (href.indexOf('whatsapp.com') !== -1 || href.indexOf('wa.me') !== -1) {
            window.dhTrack('whatsapp_click', { href: href, label: text });
        }
        // Phone Call Click
        else if (href.indexOf('tel:') === 0) {
            window.dhTrack('phone_call_click', { tel: href.replace('tel:', ''), label: text });
        }
        // Booking action / modal open
        else if (
            target.getAttribute('data-action') === 'book' ||
            href.indexOf('#booking') !== -1 ||
            text.indexOf('Book Consultation') !== -1 ||
            text.indexOf('Book Fitting') !== -1 ||
            text.indexOf('상담 예약') !== -1
        ) {
            window.dhTrack('booking_click', { label: text, href: href });
        }
        // Price calculator trigger
        else if (
            href.indexOf('calculator') !== -1 ||
            text.indexOf('Calculate Price') !== -1 ||
            text.indexOf('견적 계산') !== -1
        ) {
            window.dhTrack('calculator_view', { label: text });
        }
    }, true);

})();
