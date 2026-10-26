<!Doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>H ♡ P E</title>
    <link rel="stylesheet" href="css/mhstyle.css?v=32" />
  </head>
  <body>
    <nav class="navbar">
      <div class="nav-container">
        <img src="images/hope-logo.png" alt="Logo" class="logo" />
        <div class="nav-container2">
        <ul>
          <li><a href="#home">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#services">Services</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
</div>
      </div>
    </nav>
    <section class="home-section" id="home">
      <div>
        <div class="hero-copy">
          <h3>⦿ MENTAL HEALTH, WE CARE!</h3>
          <h1>A little support can make a <em>big difference</em></h1>
          <p>
            Even in the quietest corners of your mind, every small step toward
            healing echoes across your entire life.
          </p>
          <button class="hero-button">
            <a href="html/mhregister.php">Get Started &nbsp; →</a>
          </button>
        </div>
        <div class="logincontainer">
        <div class="logincontainer2">
            <br>
            <br>
            <h1 class="logintext">Login</h1>
            <div class="logincontainer3">
            <?php
             require "html/mhlogin.php"
             ?>
             <?php if ($error): ?>
            <p style="color:red; font-size:30px;"><?php echo $error; ?></p>
             <?php endif; ?>
                <form method="POST" action="html/mhlogin.php">
            <label>Email</label><br>
            <input type="text" name="email" placeholder="Enter Email" required><br>
            <label>Password</label><br>
            <input type="text" name="password" placeholder="Enter Password" required><br>
            <button type="submit" name="loginbutton" class="loginbutton">Login</button><br>
             </form>
            <div class="checkboxcontainer">
            <input type="checkbox"><h4 class="rememberregister">Remember Me</h4>
            <h4 class="rememberregister"><a href="html/mhregister.php">Register</a></h4>
            </div>
            </div>
        </div>
        </div>
      </div>
    </section>

    <section class="about-section" id="about">
      <h2>ABOUT US</h2>
      <div>
        <p class="about-text">
          We are dedicated to bridging the gap between individuals seeking
          mental health support and qualified professionals providing care. By
          integrating essential guidance, clinical and psychological
          assessments, and comprehensive support service management into a
          single secure platform, we make mental health care accessible,
          organized, and confidential.
        </p>
      </div>
      <div class="mission-vision">
        <div class="mission">
          <h3>OUR MISSION</h3>
          <p>
            To empower individuals on their wellness journey by providing
            reliable, technology-driven tools for psychological assessment and
            continuous mental health support.
          </p>
        </div>
        <div class="vision">
          <h3>OUR VISION</h3>
          <p>
            A community where mental health resources are universally
            accessible, stigma-free, and seamlessly integrated into everyday
            life.
          </p>
        </div>
      </div>
    </section>

    <section class="services-section" id="services">
      <h2>OUR SERVICES</h2>
      <div class="services-container">
        <div class="service-card">
          <img style="border-radius: 10px;" src="images/assessment.jpg" alt="Assessment" />
          <h3>Psychological Assessment</h3>
          <p>
            Take standardized digital evaluations safely and confidentially to help identify needs, monitor progress, and guide personalized support plans.
          </p>
        </div>
        <div class="service-card">
          <img style="border-radius: 10px;" src="images/support.jpg" alt="Support" />
          <h3>Mental Health Guidance & Support</h3>
          <p>
            Access structured wellness resources, interactive tools, and direct guidance channels to help you navigate life's challenges.
          </p>
        </div>
        <div class="service-card">
          <img style="border-radius: 10px;" src="images/resources.jpg" alt="Resources" />
          <h3>Resource Management</h3>
          <p>
            Access to a curated selection of tools, articles, and community
            support networks to enhance your mental health journey.
          </p>
        </div>
      </div>
    </section>

     <main>
        <section class="contact-section" id="contact" >
            <h2 class="contact-title">Contact Information</h2>
            <div class="contact-container">
                <div class="contact-info">
                    <div class="info-card">
                    <span class="info-label">EMAIL ADDRESS</span>
                    <a href="mailto:hope@support.com" class="info-value">hope@support.com</a>
                    </div>

                    <div class="info-card">
                        <span class="info-label">LOCATION</span>
                        <p class="info-value">Guidance and Testing Office, Main Campus</p>
                    </div>

                    <div class="info-card">
                        <span class="info-label">PHONE / HOTLINES</span>
                        <div class="social-links">
                            <a href="tel:+1234567890" class="info-value">+63 912 345 6789</a>
                            <a href="tel:+0987654321" class="info-value">Emergency Line: 911</a>
                    </div>
                    </div>

                    <div class="info-card">
                        <span class="info-label">OPERATING HOURS</span>
                        <p class="info-value">Monday – Friday: 8:00 AM – 5:00 PM</p>
                    </div>
            </div>
        </section>
    </main>
  </body>
</html>
