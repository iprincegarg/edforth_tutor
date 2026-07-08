
    <footer class="custom-footer">
      <div class="container">
        <div class="row">
          <!-- Logo and Tagline -->
          <div class="col-md-4 footer-logo-section">
            <img src="<?php echo URLROOT; ?>/uploads/assets/logo.png" alt="EdForth Tutors Logo"
              class="footer-logo mb-3" style="max-height: 50px; background: white; padding: 5px; border-radius: 4px;">
            <p class="footer-tagline">
              Where Education Meets Revolution!
            </p>
            <p class="footer-contact">
              <i class="fa fa-phone"></i> <a href="tel:+971505290288">+971-505290288</a>
            </p>
            <p class="footer-contact">
              <i class="fa fa-envelope"></i> <a href="mailto:info@edforthtutors.com">info@edforthtutors.com</a>
            </p>
          </div>

          <!-- Navigation Links -->
          <div class="col-md-4 footer-links-section">
            <h5 class="footer-heading">Quick Links</h5>
            <ul class="footer-links">
              <li><a href="/index">Home</a></li>
              <li><a href="/about">About</a></li>
              <li>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSeMGrJtCmddjrnjPTb7Hhmqc5SfoX-t3_j8_T0mgcIypKsplQ/viewform"
                  target="_blank">Contact</a>
              </li>
              <li><a href="/curriculum">Courses</a></li>
              <li><a href="/blogs">Blogs</a></li>
              <li>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLScWFg4ZWVyfS3nXi4syKImfNmTB9gBraHjQeOmjmciGQeH-0Q/viewform?usp=pp_url&entry.230204269=%2B971-"
                  target="_blank">Career</a>
              </li>
              
              
            </ul>
          </div>

          <!-- Social Media Links -->
          <div class="col-md-4 footer-social-section">
            <h5 class="footer-heading">Follow Us</h5>
            <ul class="footer-social-links">
              <li>
                <a href="https://www.linkedin.com/in/edforth-tutors-6564b4347" target="_blank">
                  <i class="fa fa-linkedin-square"></i>
                </a>
              </li>
              <li>
                <a href="https://www.instagram.com/edforthtutors" target="_blank">
                  <i class="fa fa-instagram"></i>
                </a>
              </li>
            </ul>
            <p class="footer-note">Stay connected for the latest updates!</p>
          </div>
        </div>

        <hr class="footer-divider">

        <!-- Copyright Section -->
        <div class="row">
          <div class="col text-center footer-copyright">
            &copy; <span id="currentYear"></span> EdForth Tutors. All rights reserved.
          </div>
        </div>
      </div>
    </footer>

    <script>
      // Update the current year dynamically
      document.getElementById('currentYear').textContent = new Date().getFullYear();
    </script>


  </div>

  <script src="https://edforthtutors.com/static/js/jquery-3.2.1.min.js"></script>
  <script src="https://edforthtutors.com/static/css/bootstrap4/popper.js"></script>
  <script src="https://edforthtutors.com/static/css/bootstrap4/bootstrap.min.js"></script>
  <script src="https://edforthtutors.com/static/plugins/greensock/TweenMax.min.js"></script>
  <script src="https://edforthtutors.com/static/plugins/greensock/TimelineMax.min.js"></script>
  <script src="https://edforthtutors.com/static/plugins/scrollmagic/ScrollMagic.min.js"></script>
  <script src="https://edforthtutors.com/static/plugins/greensock/animation.gsap.min.js"></script>
  <script src="https://edforthtutors.com/static/plugins/greensock/ScrollToPlugin.min.js"></script>
  <script src="https://edforthtutors.com/static/plugins/OwlCarousel2-2.2.1/owl.carousel.js"></script>
  <script src="https://edforthtutors.com/static/plugins/easing/easing.js"></script>
  <script src="https://edforthtutors.com/static/plugins/parallax-js-master/parallax.min.js"></script>
  <script src="https://edforthtutors.com/static/js/custom.js"></script>
  <script src="https://edforthtutors.com/static/plugins/colorbox/jquery.colorbox-min.js"></script>
  <script src="https://edforthtutors.com/static/js/about.js"></script>
  
<script>
  // Add this to your existing JavaScript file or in a script tag at the bottom of your page
  document.addEventListener('DOMContentLoaded', function () {
    // Initialize animations when page loads
    animateCoursesOnScroll();

    // Check for animations when scrolling
    window.addEventListener('scroll', function () {
      animateCoursesOnScroll();
    });

    function animateCoursesOnScroll() {
      const courseSection = document.querySelector('.courses');
      if (!courseSection) return;

      const coursePos = courseSection.getBoundingClientRect().top;
      const screenHeight = window.innerHeight;

      // If courses section is in viewport
      if (coursePos < screenHeight * 0.75) {
        const courseItems = document.querySelectorAll('.course_col');
        courseItems.forEach((item, index) => {
          setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
          }, 100 * index);
        });
      }
    }
  });
</script>

  <script>
    $(document).ready(function () {
      var owl = $(".owl-carousel");
      owl.owlCarousel({
        loop: true,
        margin: 10,
        nav: false, // Disable default navigation
        responsive: {
          0: {
            items: 1
          },
          600: {
            items: 2
          },
          1000: {
            items: 3
          }
        }
      });

      // Custom Navigation Events
      $(".custom-next").click(function () {
        owl.trigger('next.owl.carousel');
      });
      $(".custom-prev").click(function () {
        owl.trigger('prev.owl.carousel', [300]);
      });
    });
  </script>
  <script>
    document.getElementById("submitForm").addEventListener("click", async () => {
      const form = document.getElementById("contactForm");
      const errorMessage = document.getElementById("errorMessage");
      const confirmationMessage = document.getElementById("confirmationMessage");

      errorMessage.classList.add("hidden");
      confirmationMessage.classList.add("hidden");

      // Collect form data
      const formData = Object.fromEntries(new FormData(form));
      console.log("Form Data Submitted:", formData);

      // Frontend Validation
      const requiredFields = ["name", "email", "phone", "location", "grade", "curriculum", "subject", "message"];
      for (const field of requiredFields) {
        if (!formData[field] || formData[field].trim() === "" || formData[field] === `Choose ${field.charAt(0).toUpperCase() + field.slice(1)}`) {
          errorMessage.textContent = `The field '${field}' is required.`;
          errorMessage.classList.remove("hidden");
          return;
        }
      }

      try {
        const response = await fetch("/mail", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(formData),
        });

        if (response.ok) {
          confirmationMessage.textContent = "Thank you for your submission!";
          confirmationMessage.classList.remove("hidden");
          form.reset();

          // Smooth scrolling
          confirmationMessage.scrollIntoView({ behavior: "smooth" });
        } else {
          const result = await response.json();
          errorMessage.textContent = result.error || "Failed to submit the form.";
          errorMessage.classList.remove("hidden");
        }
      } catch (error) {
        errorMessage.textContent = "An error occurred while trying to submit the form.";
        errorMessage.classList.remove("hidden");
        console.error("Submission Error:", error);
      }
    });
  </script>
  <script>
    $("a[href^='#']").on("click", function (e) {
      e.preventDefault();
      $("html, body").animate(
        {
          scrollTop: $($(this).attr("href")).offset().top,
        },
        1000, // Adjust this value for animation speed
        "easeInOutCubic"
      );
    });
  </script>
  <script>
    $(".milestone_counter").each(function () {
      var $this = $(this);
      $(this).waypoint(
        function () {
          var counterValue = $this.data("end-value");
          $({ counter: $this.text() }).animate(
            { counter: counterValue },
            {
              duration: 2000,
              easing: "swing",
              step: function (now) {
                $this.text(Math.ceil(now));
              },
            }
          );
        },
        { offset: "80%" }
      );
    });
  </script>
  <script>
    $(document).ready(function () {
      // Initialize the testimonial slider
      var testimonialSlider = $(".testimonial_slider");

      testimonialSlider.owlCarousel({
        items: 3,
        loop: true,
        margin: 20,
        nav: false,
        dots: true,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        smartSpeed: 700,
        responsive: {
          0: {
            items: 1,
          },
          768: {
            items: 2,
          },
          992: {
            items: 3,
          }
        }
      });

      // Custom navigation
      $(".testimonial_nav_prev").click(function () {
        testimonialSlider.trigger('prev.owl.carousel');
      });

      $(".testimonial_nav_next").click(function () {
        testimonialSlider.trigger('next.owl.carousel');
      });
    });
  </script>
</body>

</html>