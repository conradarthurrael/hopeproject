<?php
$contact_sent = false;
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $contact_sent = true;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>H.O.P.E. | Wellness &amp; Guidance Support</title>
<link rel="stylesheet" href="assets/css/index.css">
</head>
  <body class="landing-page">
    <nav class="landing-nav" aria-label="Primary">
      <div class="landing-nav-inner">
        <a href="#home" class="landing-brand" aria-label="H.O.P.E. home">
          <img src="images/hope-logo.png" alt="H.O.P.E. logo" class="landing-logo" />
        </a>
        <ul class="landing-links">
          <li><a href="#home">Home</a></li>
          <li><a href="#services">Services</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
        <div class="landing-actions">
          <a href="html/mental-health-login.php" class="btn-landing btn-landing-outline">Log In</a>
          <a href="html/mental-health-signup.php" class="btn-landing btn-landing-primary">Sign Up</a>
        </div>
      </div>
    </nav>

    <main>
      <section class="hero-section" id="home">
        <div class="hero-container">
          <div class="hero-copy">
            <span class="hero-badge">WELLNESS &amp; GUIDANCE SUPPORT</span>
            <h1>A little support can make a <em><i>big difference</i></em></h1>
            <p class="hero-subtitle">
              H.O.P.E. offers a calm, secure space for non-academic wellness support, helping students and community members connect with guidance resources, assessments, and caring professionals when they need it most.
            </p>
            <div class="hero-cta">
              <a href="html/mental-health-signup.php" class="btn-landing btn-landing-primary">Get Started</a>
            </div>
          </div>
          <div class="hero-media">
            <img src="images/side-background.png" alt="Supportive wellness environment" />
          </div>
        </div>
      </section>

      <section class="landing-section services-section" id="services">
        <div class="section-header">
          <span class="section-badge">OUR SERVICES</span>
          <h2>Support designed for wellbeing</h2>
          <p>Accessible guidance, thoughtful assessments, and organized resources in one welcoming platform.</p>
        </div>
        <div class="services-grid">
          <article class="service-card landing-card">
            <div class="card-icon" aria-hidden="true">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
            </div>
            <img class="service-image" src="images/assessment.jpg" alt="Psychological assessment support" />
            <h3>Psychological Assessment</h3>
            <p>Complete standardized digital evaluations safely and confidentially to help identify needs, monitor progress, and guide personalized support.</p>
          </article>
          <article class="service-card landing-card">
            <div class="card-icon" aria-hidden="true">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"></path></svg>
            </div>
            <img class="service-image" src="images/support.jpg" alt="Guidance and wellness support" />
            <h3>Mental Health Guidance &amp; Support</h3>
            <p>Access structured wellness resources, supportive tools, and direct guidance channels to help you navigate challenges with care.</p>
          </article>
          <article class="service-card landing-card">
            <div class="card-icon" aria-hidden="true">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            </div>
            <img class="service-image" src="images/resources.jpg" alt="Curated wellness resources" />
            <h3>Resource Management</h3>
            <p>Explore a curated selection of tools, articles, and community support resources to strengthen your mental health journey.</p>
          </article>
        </div>
      </section>

      <section class="landing-section about-section" id="about">
        <div class="section-header">
          <span class="section-badge">ABOUT US</span>
          <h2>Care that is accessible and organized</h2>
          <p>We connect individuals seeking support with qualified professionals through a secure, easy-to-use platform.</p>
        </div>
        <div class="about-card landing-card">
          <p class="about-text">
            H.O.P.E. is dedicated to bridging the gap between individuals seeking mental health and wellness support and qualified professionals providing care. By integrating guidance, psychological assessments, and comprehensive support service management into a single secure platform, we make care more accessible, organized, and confidential.
          </p>
        </div>
        <div class="mission-vision">
          <article class="landing-card mission">
            <div class="card-icon" aria-hidden="true">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
            </div>
            <h3>Our Mission</h3>
            <p>To empower individuals on their wellness journey by providing reliable, technology-driven tools for psychological assessment and continuous mental health support.</p>
          </article>
          <article class="landing-card vision">
            <div class="card-icon" aria-hidden="true">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            </div>
            <h3>Our Vision</h3>
            <p>A community where mental health resources are universally accessible, stigma-free, and seamlessly integrated into everyday life.</p>
          </article>
        </div>
      </section>

      <section class="landing-section contact-section" id="contact">
        <div class="section-header">
          <span class="section-badge">CONTACT</span>
          <h2>Get in touch</h2>
          <p>Reach out for information, guidance, or support resources.</p>
        </div>
        <div class="contact-container">
          <div class="contact-info">
            <div class="info-card landing-card">
              <span class="info-label">EMAIL ADDRESS</span>
              <a href="mailto:hope@support.com" class="info-value">hope@support.com</a>
            </div>
            <div class="info-card landing-card">
              <span class="info-label">LOCATION</span>
              <p class="info-value">Guidance and Testing Office, Main Campus</p>
            </div>
            <div class="info-card landing-card">
              <span class="info-label">PHONE / HOTLINES</span>
              <div class="social-links">
                <a href="tel:+639123456789" class="info-value">+63 912 345 6789</a>
                <a href="tel:911" class="info-value">Emergency Line: 911</a>
              </div>
            </div>
            <div class="info-card landing-card">
              <span class="info-label">OPERATING HOURS</span>
              <p class="info-value">Monday &ndash; Friday: 8:00 AM &ndash; 5:00 PM</p>
            </div>
          </div>

          <form class="contact-form landing-card" method="POST" action="#contact">
            <h3 class="contact-heading">Message Us</h3>
            <?php if ($contact_sent): ?>
              <div class="notice-success">Thank you! Your message has been received.</div>
            <?php endif; ?>
            <div class="form-group">
              <label class="sr-only" for="contact-name">Name</label>
              <input id="contact-name" type="text" name="name" placeholder="Name" required />
            </div>
            <div class="form-group">
              <label class="sr-only" for="contact-email">Email</label>
              <input id="contact-email" type="email" name="email" placeholder="Email" required />
            </div>
            <div class="form-group">
              <label class="sr-only" for="contact-message">Message</label>
              <textarea id="contact-message" name="message" placeholder="Message" rows="5" required></textarea>
            </div>
            <button type="submit" name="send_message" class="btn-landing btn-landing-primary submit-btn">Send Message</button>
          </form>
        </div>
      </section>
    </main>

    <footer class="landing-footer">
      <div class="landing-footer-inner">
        <a href="#home" class="footer-brand">
          <img src="images/hope-logo.png" alt="H.O.P.E. logo" class="footer-logo" />
        </a>
        <div class="footer-links">
          <a href="html/mental-health-login.php">Log In</a>
          <a href="html/mental-health-signup.php">Register</a>
          <a href="#services">Services</a>
          <a href="#contact">Contact</a>
        </div>
        <p class="footer-copy">&copy; <?php echo date('Y'); ?> H.O.P.E. Wellness &amp; Guidance Support.</p>
      </div>
    </footer>

    <script src="assets/js/animations.js" defer></script>
  </body>
</html>