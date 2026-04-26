  </main>

  <footer class="site-footer">
    <div class="container footer-inner">
      <div>
        <p><strong>KUET Math Club</strong></p>
        <p><?= Utilities::escape(SITE_ADDRESS) ?></p>
      </div>

      <p>© <?= date('Y') ?> KUET Math Club. All rights reserved.</p>

      <div class="socials footer-socials" aria-label="Social links">
        <a href="https://www.facebook.com/kuetmathclub" target="_blank" rel="noopener noreferrer">Facebook</a>
        <a href="https://www.linkedin.com/company/kuet-math-club" target="_blank" rel="noopener noreferrer">LinkedIn</a>
      </div>
    </div>
  </footer>

  <script>
    (function () {
      var navToggle = document.querySelector('.nav-toggle-btn');
      var navLinks = document.querySelectorAll('.nav-links a');

      function setMenu(open) {
        document.body.classList.toggle('nav-open', open);
        if (navToggle) {
          navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        }
      }

      if (navToggle) {
        navToggle.addEventListener('click', function () {
          setMenu(!document.body.classList.contains('nav-open'));
        });
      }

      navLinks.forEach(function (link) {
        link.addEventListener('click', function () {
          setMenu(false);
        });
      });

      document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
          setMenu(false);
        }
      });

      window.addEventListener('resize', function () {
        if (window.innerWidth > 780) {
          setMenu(false);
        }
      });
    })();
  </script>
</body>
</html>
