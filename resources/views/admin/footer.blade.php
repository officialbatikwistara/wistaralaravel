<footer class="footer-admin text-center py-3 mt-5">
  <div class="container">
    <p class="mb-1 fw-semibold text-white">© 2025 Batik Wistara Admin Panel</p>
    <small class="text-white-50">
      Dibuat dengan <i class="fa-solid fa-heart text-danger"></i> oleh Tim Developer Batik Wistara
    </small>
  </div>
</footer>

<!-- Script Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
  .footer-admin {
    background: linear-gradient(135deg, #1c1f24, #2b3035);
    color: #ffffff;
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.15);
  }

  .footer-admin p,
  .footer-admin small {
    color: #ffffff;
  }

  .footer-admin i {
    animation: pulse 1.5s infinite;
  }

  @keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.8; }
    50% { transform: scale(1.2); opacity: 1; }
  }
</style>
</body>
</html>
