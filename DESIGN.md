# H.O.P.E. Design System & Visual Guidelines

## Design Direction: Botanical Sanctuary
An organic, serene design language inspired by nature, wellness, and human empathy. Light backgrounds with muted sage and emerald accents foster a soothing atmosphere suitable for psychological care.

---

## 1. Color Palette & Tokens

### Primary Brand & Accents
| Token / Name | Value | Usage |
| :--- | :--- | :--- |
| `--color-primary` | `#176b24` | Primary brand green, emphasized text, dark buttons |
| `--color-accent` | `#2a9920` | Vibrant emerald green, active states, focus borders, primary CTAs |
| `--color-accent-hover` | `#23851a` | Hover states for primary actions |
| `--color-teal` | `#16493e` | Deep teal for subheaders, labels, and secondary accents |
| `--color-teal-muted` | `#245e5a` | Section tags and secondary eyebrow text |

### Backgrounds & Glassmorphism
| Token / Name | Value | Usage |
| :--- | :--- | :--- |
| `--bg-gradient` | `linear-gradient(135deg, #e3ece2 0%, #f5f9f4 48%, #c5cec2 100%)` | Global fixed body background |
| `--surface-card` | `rgba(255, 255, 255, 0.85)` | Elevated containers, form cards, dialogs |
| `--surface-card-subtle` | `rgba(255, 255, 255, 0.72)` | Service cards, informational tiles, mission blocks |
| `--surface-blur` | `blur(14px)` | Backdrop filter for sticky navigation and floating cards |
| `--border-subtle` | `1px solid rgba(35, 79, 30, 0.28)` | Card borders, navbar separator |

### Text & Feedback
| Token / Name | Value | Usage |
| :--- | :--- | :--- |
| `--text-heading` | `#1e3533` | Primary headings (H1, H2) |
| `--text-body` | `#26524e` | Paragraphs, descriptions, list items |
| `--text-muted` | `#0f1412` | Form labels, helper captions |
| `--feedback-success-bg` | `#def7ec` | Success banners, approved badges |
| `--feedback-success-text` | `#03543f` | Success message text |
| `--feedback-error-bg` | `#fde8e8` | Alert banners, validation error blocks |
| `--feedback-error-text` | `#9b1c1c` | Error message text |

---

## 2. Typography

- **Primary Font Family**: `"Segoe UI", Tahoma, Geneva, Verdana, sans-serif`
  - Clean, accessible, friendly humanistic sans-serif with excellent cross-platform support.
- **Editorial Emphasis**: `Georgia, "Times New Roman", serif`
  - Used sparingly on key italicized emotional words in hero headings (e.g. `<em>big difference</em>`).
- **Hierarchy**:
  - **H1 (Hero)**: `clamp(3rem, 6vw, 5.4rem)`, font-weight `650`, letter-spacing `-0.065em`, line-height `1.04`.
  - **H2 (Section Titles)**: `clamp(2rem, 5vw, 3.6rem)`, font-weight `650`, letter-spacing `-0.065em`.
  - **H3 (Eyebrow / Subtitles)**: `0.82rem` to `1.2rem`, bold uppercase or uppercase tracking.
  - **Body**: `1.12rem`, line-height `1.75` for high readability.

---

## 3. Component & Layout Rules

### Navigation (`.navbar`)
- Sticky to top (`position: sticky; top: 0; z-index: 1000;`).
- Frosted glass effect with subtle green-tinted gradient (`backdrop-filter: blur(14px)`).
- Flexbox layout with auto-spacing (`margin-left: auto;`) ensuring right-aligned actions never collapse or wrap incorrectly on narrow viewports.

### Cards & Surfaces
- Standard border-radius: `10px` for content cards, `28px` for standalone modal/form containers.
- Subtle inner drop-shadows: `box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1)`.
- Never use opaque pure-white boxes without border definition on the gradient background.

### Buttons & CTAs
- **Primary Action (Pill/Rounded)**:
  - Gradient: `linear-gradient(135deg, #2a9920, #176b24)`.
  - Elevation on hover: `transform: translateY(-2px); box-shadow: 0 10px 20px rgba(8, 65, 10, 0.24);`.
  - Radius: `999px` for nav buttons, `10px` to `12px` for page action triggers.
- **Secondary Action**:
  - Translucent white background `rgba(255, 255, 255, 0.72)` with green border `1px solid rgba(23, 107, 36, 0.35)`.

### Forms & Input Fields
- Inputs must feature an opaque/semi-opaque white background (`rgba(255, 255, 255, 0.9)`) with dark text (`#1e3533`) for contrast.
- Focus ring: `outline: none; border-color: #2a9920; box-shadow: 0 0 6px rgba(42, 153, 32, 0.3);`.
- Form containers center their inputs with consistent width and padding across viewports.

---

## 4. Visual Anti-Patterns
1. **Never use harsh clinical blue-gray palettes**: Mental health interfaces must evoke warmth, growth, and hope, not cold hospital wards.
2. **Never use generic AI-slop gradients**: Avoid saturated purple-to-blue neon blends, arbitrary radial rainbow glows, and unstyled default fonts.
3. **No tiny low-contrast form labels**: Always ensure high contrast (`#0f1412` or `#16493e`) for accessibility.
4. **No jarring animations**: Use gentle 0.2s–0.3s ease transitions for hover and interactive feedback.
