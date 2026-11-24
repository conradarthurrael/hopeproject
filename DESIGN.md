# H.O.P.E. Design System

## Visual direction

Use `index.php` as the public visual reference for authenticated views: calm layouts, readable typography, clear hierarchy, and spacious cards. The duplicate root auth pages and nested `login-register-page/` copies have been removed. Authentication now uses the existing routes `html/mental-health-login.php` and `html/mental-health-register.php`, with registration processed by `html/mental-health-signup.php`.

## Palette and interaction tokens

| Token | Value | Use |
| --- | --- | --- |
| Milk White | `#FAF9F6` | Main page canvas |
| Soft Milk White | `#F8F7F2` | Cards, panels, and navigation |
| Forest Green | `#2E5A44` | Primary actions, active states, icons, and chart bars |
| Sage Green | `#3B7A57` | Secondary emphasis and hover accents |
| Deep Charcoal | `#1F2933` | Body copy and headings |
| Soft Border | `#E2E0D8` | Card outlines, form boundaries, and table dividers |
| Primary hover | `#234635` | Darker green hover state with a 1px lift |
| Secondary hover | `#E6EFEA` | Soft sage tint with Forest Green text and border |

Buttons transition with `all .2s ease`. Use solid fills; do not use linear or radial gradients.

## Typography and layout

- Use the system sans-serif stack beginning with Segoe UI for interface text.
- Use Georgia sparingly for editorial emphasis on the landing page.
- Keep line lengths comfortable, headings compact, and section spacing generous.
- Use rounded cards, clear form labels, visible focus states, and comfortable touch targets.
- Use the manager dashboard's fixed, 270px left sidebar as the portal navigation master. Preserve its vertical layout, spacing, 44px logo sizing/placement, profile block, and section hierarchy across logged-in views.
- Portal navigation routes Dashboard and the logo by `$_SESSION['user_type']`: Teacher, Student, Manager/Admin, or Guidance Counsellor. Hover feedback uses smooth color changes only, without movement or size changes.
- Login and registration use a centered split card up to 1040px wide. A Forest Green H.O.P.E. details panel sits beside the form on desktop and stacks below it on screens 768px and narrower.
- At 860px and below, the master sidebar follows the manager stylesheet's responsive layout. At 768px and below, cards stack and data tables scroll horizontally.

## External CSS structure

Shared tokens, navigation, controls, breakpoints, and reveal states live in `assets/css/global.css`. Page styles are organized in `landing.css`, `auth.css`, `dashboard.css`, `users.css`, `chat.css`, and `reports.css`. Existing page stylesheets remain available for page-specific details and are loaded before the shared overrides.

## Product components

- The manager dashboard renders role counts, appointments, recent active users, and pending approvals as responsive Forest/Sage bar charts.
- The user management page filters the current result cards immediately using a strict name-prefix search and a combined role selection.
- Chat reads the existing `messages` table (`sender_id`, `receiver_id`, `message`, `sent_at`). AJAX sends use the existing message handlers; message HTML refreshes every 2.5 seconds through the existing display endpoint. No chat schema change is needed.
- Sender bubbles align right in Forest Green; receiver bubbles align left on a neutral surface. Both wrap safely on mobile.
- The existing `users.address` column is collected by the registration form and written by the signup handler. The SQL dump and signup handler retain an idempotent missing-column guard.

## Motion and accessibility

Entrance and scroll reveals use `IntersectionObserver`, honor reduced-motion preferences, and fall back to visible content if JavaScript is delayed or unavailable. Keep keyboard focus visible and text contrast high.
