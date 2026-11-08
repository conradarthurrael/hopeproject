# H.O.P.E. (Mental Health & Guidance Support System)

## Register
Dual Surface:
- **Brand Surface**: Public landing page (`index.php`), authentication gateways (`login.php`, `register.php`), showcasing mission, services, and inviting community members to seek support.
- **Product Surface**: Authenticated role-based portals (`html/`) for Students, Teachers, Guidance Counsellors, and Managers.

## Platform
web

## Purpose
H.O.P.E. bridges the gap between individuals seeking psychological support and campus mental health professionals. It centralizes confidential appointment scheduling, standardized psychological evaluations, real-time guidance communication, and referral case reporting into a unified, secure platform.

## Users & Roles
- **Students**: 
  - Need accessible, confidential, stigma-free support.
  - Workflows: Book guidance appointments, message counsellors via chat, complete digital psychological evaluations, review wellness resources.
- **Teachers**:
  - Need a streamlined channel to advocate for and refer students in distress.
  - Workflows: Submit student behavioral/wellness reports, track referral progress, communicate with guidance staff.
- **Guidance Counsellors**:
  - Need organized session schedules, student case histories, and confidential communications.
  - Workflows: Approve and manage appointment requests, conduct direct chat sessions, review assessments, log counseling notes and reports.
- **Managers / Administrators**:
  - Need oversight, accountability, and user verification.
  - Workflows: Verify and approve newly registered user accounts, assign user roles (Student, Teacher, Guidance Counsellor), audit transactions, and view campus health metrics.

## Operating Context
- Campus / Educational Institution guidance and counseling office.
- Accessed on desktop computers in offices, campus computer laboratories, and personal student mobile devices/laptops.
- Operating environment: PHP / Apache (XAMPP) / MySQL (`mentalhealth` database).

## Voice & Personality
- **Empathetic & Reassuring**: Welcoming tone that normalizes seeking help and eliminates stigma.
- **Professional & Confidential**: Clear boundaries, transparent privacy expectations, and dependable UX.
- **Approachable & Calming**: Gentle, readable copy without clinical coldness or bureaucratic friction.

## Anti-References & Constraints
- **No Cold Clinical Aesthetics**: Avoid stark, sterile hospital grays or intimidating medical terminology.
- **No AI-Slop Clichés**: Prohibit arbitrary purple-to-blue neon gradients, unstyled default Inter fonts, and low-contrast white-on-light-gray cards.
- **Privacy & Security Constraints**: Strict authentication boundaries; unauthorized users must never access private chat logs, session notes, or student records.
- **Stack Constraints**: Native vanilla CSS and responsive PHP templates compatible with local XAMPP Apache deployments.
