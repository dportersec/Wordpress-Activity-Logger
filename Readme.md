# WordPress Activity Logger

A lightweight WordPress security monitoring plugin that tracks authentication events, administrative actions, and site changes directly from the WordPress dashboard.

![WordPress](https://img.shields.io/badge/WordPress-Plugin-blue)
![PHP](https://img.shields.io/badge/PHP-8+-777BB4)
![Security](https://img.shields.io/badge/Focus-Security-green)

---

## Overview

WordPress Activity Logger is a custom security-focused plugin designed to provide basic audit logging and visibility into important events occurring within a WordPress environment.

The plugin records security-relevant actions and displays them in a dashboard widget, allowing administrators to quickly identify suspicious behavior and investigate potential issues.

This project was developed to demonstrate practical cybersecurity concepts using the WordPress Plugin API and WordPress administrative tools.

---

## Features

### Authentication Monitoring

* Successful user logins
* Failed login attempts
* Login activity timestamps
* IP address tracking

### Administrative Monitoring

* Plugin activations
* Plugin deactivations
* Theme changes
* New user creation

### Dashboard Integration

* Recent Security Events dashboard widget
* Quick visibility into recent activity
* Lightweight storage using the WordPress Options API

---

## Example Events

| Timestamp           | Event                |
| ------------------- | -------------------- |
| 2026-05-29 18:51:41 | User admin logged in |
| 2026-05-29 18:51:37 | Failed login attempt |
| 2026-05-29 18:40:12 | Plugin activated     |
| 2026-05-29 18:22:44 | Theme switched       |
| 2026-05-29 17:58:19 | New user created     |

---

## Security Use Cases

This plugin can help identify:

* Brute-force login attempts
* Unauthorized administrator activity
* Unexpected plugin modifications
* Theme tampering
* Suspicious account creation
* Early indicators of compromise

---

## Technologies Used

* PHP
* WordPress Plugin API
* WordPress Hooks & Actions
* WordPress Dashboard Widgets API
* WordPress Options API
* HTML
* CSS

---

## Skills Demonstrated

### WordPress Development

* Custom plugin architecture
* Dashboard widget development
* WordPress hooks and actions
* WordPress settings management
* Data persistence

### Cybersecurity

* Audit logging
* Event monitoring
* Authentication tracking
* Administrative activity monitoring
* Security reporting
* Incident visibility

---

## Screenshot

![Recent Security Events](https://github.com/dportersec/Wordpress-Activity-Logger/blob/main/activitylog.png)

---

## Future Roadmap

### Version 1.1

* Severity levels (Info / Warning / Critical)
* Dedicated Activity Logs page
* Log filtering
* Search functionality

### Version 1.2

* CSV export
* Log retention settings
* Clear logs functionality
* User role tracking

### Version 2.0

* File integrity monitoring
* Suspicious PHP file detection
* Malware indicators
* Email security alerts
* Security score dashboard

---

## Limitations

This project is an audit logging tool and is **not** a malware scanner, antivirus solution, Web Application Firewall (WAF), or Security Information and Event Management (SIEM) platform.

While it can help identify suspicious behavior, it should be used alongside:

* Regular WordPress updates
* Security plugins
* Secure hosting
* Routine backups
* Strong authentication policies

---

## Author

**Dillon Porter**

Aspiring Security Analyst | WordPress Developer

* GitHub: https://github.com/dportersec
* Portfolio: https://github.com/dillon-porter

---

## License

MIT License
