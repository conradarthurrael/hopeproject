# H.O.P.E. Mental Health & Guidance Support System

## Product

H.O.P.E. connects students with campus mental health support and gives teachers, counsellors, and managers tools to coordinate care. The existing PHP/MySQL app supports appointment scheduling, referrals, guidance communication, account approval, and profile management.

## Canonical routes and navigation

The public entry is `index.php`. The canonical login and registration views use the existing `html/mental-health-login.php` and `html/mental-health-register.php` files; `html/mental-health-signup.php` processes registration. The duplicate root auth pages, static HTML redirect copies, and nested `login-register-page/` copies have been removed. Inner-page Home and logo links follow the signed-in `user_type` session value to the appropriate role dashboard.

## Role workflows

- **Students:** contact guidance staff, book appointments, and use wellbeing resources.
- **Teachers:** submit student wellbeing referrals and coordinate follow-up.
- **Guidance counsellors:** manage appointments, reports, and private chats.
- **Managers and administrators:** review accounts, manage users, and monitor campus activity.

## Shared interface

All active views follow a solid Milk White and Green design system: `#FAF9F6` and `#F8F7F2` surfaces, Forest Green `#2E5A44`, Sage Green `#3B7A57`, Deep Charcoal `#1F2933`, and borders `#E2E0D8`. Logged-in views use the manager dashboard's 270px left sidebar, original vertical spacing, and logo placement. Dashboard and logo routes follow the signed-in role. Sidebar hover feedback changes colors without moving or resizing items. Primary buttons darken to `#234635` with a slight lift; outline buttons use the soft Sage tint `#E6EFEA`. Login and registration pair their forms with a responsive H.O.P.E. mission and feature panel. Shared and page-specific styles live in `assets/css/`, with responsive behavior at 768px.

The manager dashboard visualizes role counts, appointment totals, active users, and pending approvals as bar graphs. User cards support immediate first-letter name-prefix search combined with role filtering. The registration form collects an address in the existing `users.address` column.

## Chat flow

Private messages use the existing `messages` table and its sender, receiver, message, and timestamp columns. The chat composer sends through the existing PHP message endpoints with fetch, while the display endpoint polls every 2.5 seconds. Sender and receiver bubbles stay visually distinct and responsive without a full-page send/receive reload.

## Operating context and principles

- PHP and Apache via XAMPP, using the existing MySQL connection in `html/mental-health-db.php`.
- Desktop, tablet, and mobile access.
- Empathetic, confidential, readable workflows with clear role-based navigation.
